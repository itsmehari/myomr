<?php
/**
 * GA4 Data API (read-only) — no Composer.
 *
 * Requires:
 *   - Google Cloud project with "Google Analytics Data API" enabled
 *   - Service account JSON key (not web-accessible)
 *   - GA4: grant the service account email Viewer (or Analyst) on the property
 *
 * Env / server config:
 *   MYOMR_GA_PROPERTY_ID           — optional; default in core/analytics-config.php (myomr_ga4_property_id)
 *   MYOMR_GA_SERVICE_ACCOUNT_PATH  — absolute path to service account JSON file
 *
 * Local dev fallback (if env unset): repo root `/.cursor/secrets/google-analytics.json`
 * (gitignored — never deploy this path to production; set MYOMR_GA_SERVICE_ACCOUNT_PATH on the server.)
 *
 * Measurement ID (G-XXXX) is for gtag only; the Data API uses the numeric property id.
 */

if (!function_exists('myomr_ga4_base64url_encode')) {
    function myomr_ga4_base64url_encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

/**
 * Load service account JSON from MYOMR_GA_SERVICE_ACCOUNT_PATH or local dev file.
 *
 * @return array{ok:bool, data?:array, error?:string}
 */
function myomr_ga4_load_service_account(): array
{
    $path = getenv('MYOMR_GA_SERVICE_ACCOUNT_PATH');
    if (!is_string($path) || $path === '') {
        $fallback = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.cursor' . DIRECTORY_SEPARATOR . 'secrets' . DIRECTORY_SEPARATOR . 'google-analytics.json';
        if (is_readable($fallback)) {
            $path = $fallback;
        } else {
            return ['ok' => false, 'error' => 'MYOMR_GA_SERVICE_ACCOUNT_PATH is not set and .cursor/secrets/google-analytics.json was not found.'];
        }
    }
    if (!is_readable($path)) {
        return ['ok' => false, 'error' => 'Service account file is not readable: ' . $path];
    }
    $raw = @file_get_contents($path);
    if ($raw === false || $raw === '') {
        return ['ok' => false, 'error' => 'Could not read service account file.'];
    }
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        return ['ok' => false, 'error' => 'Invalid JSON in service account file.'];
    }
    foreach (['type', 'client_email', 'private_key'] as $k) {
        if (empty($data[$k]) || !is_string($data[$k])) {
            return ['ok' => false, 'error' => 'Service account JSON missing field: ' . $k];
        }
    }
    if ($data['type'] !== 'service_account') {
        return ['ok' => false, 'error' => 'JSON is not a service account key.'];
    }
    return ['ok' => true, 'data' => $data];
}

/**
 * @return array{ok:bool, token?:string, error?:string}
 */
function myomr_ga4_get_access_token(array $serviceAccount): array
{
    static $cache = null;
    if ($cache !== null && isset($cache['exp']) && $cache['exp'] > time() + 60) {
        return ['ok' => true, 'token' => $cache['token']];
    }

    $now = time();
    $header = ['alg' => 'RS256', 'typ' => 'JWT'];
    $claims = [
        'iss' => $serviceAccount['client_email'],
        'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
        'aud' => 'https://oauth2.googleapis.com/token',
        'iat' => $now,
        'exp' => $now + 3600,
    ];
    $h = myomr_ga4_base64url_encode(json_encode($header, JSON_UNESCAPED_SLASHES));
    $c = myomr_ga4_base64url_encode(json_encode($claims, JSON_UNESCAPED_SLASHES));
    $unsigned = $h . '.' . $c;

    $key = openssl_pkey_get_private($serviceAccount['private_key']);
    if ($key === false) {
        return ['ok' => false, 'error' => 'Invalid private_key in service account JSON.'];
    }
    $signature = '';
    if (!openssl_sign($unsigned, $signature, $key, OPENSSL_ALGO_SHA256)) {
        return ['ok' => false, 'error' => 'Could not sign JWT.'];
    }
    $jwt = $unsigned . '.' . myomr_ga4_base64url_encode($signature);

    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
    ]);
    $resp = curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($resp === false || $code !== 200) {
        return ['ok' => false, 'error' => 'OAuth token failed (HTTP ' . $code . '): ' . substr((string)$resp, 0, 500)];
    }
    $json = json_decode($resp, true);
    if (!is_array($json) || empty($json['access_token'])) {
        return ['ok' => false, 'error' => 'OAuth response missing access_token.'];
    }
    $cache = ['token' => $json['access_token'], 'exp' => $now + (int)($json['expires_in'] ?? 3500)];
    return ['ok' => true, 'token' => $json['access_token']];
}

/**
 * @param array<string,mixed> $body Request JSON for runReport
 * @return array{ok:bool, data?:array, error?:string, http_code?:int}
 */
function myomr_ga4_run_report(array $serviceAccount, string $propertyIdNumeric, array $body): array
{
    if (!preg_match('/^\d+$/', $propertyIdNumeric)) {
        return ['ok' => false, 'error' => 'Property ID must be numeric (GA Admin → Property settings).'];
    }
    $tok = myomr_ga4_get_access_token($serviceAccount);
    if (!$tok['ok']) {
        return ['ok' => false, 'error' => $tok['error'] ?? 'Token error'];
    }
    $url = 'https://analyticsdata.googleapis.com/v1beta/properties/' . rawurlencode($propertyIdNumeric) . ':runReport';
    $payload = json_encode($body, JSON_UNESCAPED_SLASHES);
    if ($payload === false) {
        return ['ok' => false, 'error' => 'Could not encode request body.'];
    }
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 45,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $tok['token'],
            'Content-Type: application/json',
        ],
    ]);
    $resp = curl_exec($ch);
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($resp === false) {
        return ['ok' => false, 'error' => 'runReport request failed.', 'http_code' => $httpCode];
    }
    $data = json_decode($resp, true);
    if (!is_array($data)) {
        return ['ok' => false, 'error' => 'Invalid JSON from Analytics Data API.', 'http_code' => $httpCode];
    }
    if ($httpCode !== 200) {
        $msg = $data['error']['message'] ?? $resp;
        return ['ok' => false, 'error' => is_string($msg) ? $msg : 'runReport error', 'http_code' => $httpCode];
    }
    return ['ok' => true, 'data' => $data, 'http_code' => $httpCode];
}

/**
 * Extract first row metric values as strings (aggregate report, no dimensions).
 *
 * @return list<string>
 */
function myomr_ga4_first_row_metrics(array $runReportResponse): array
{
    $out = [];
    $rows = $runReportResponse['rows'] ?? null;
    if (!is_array($rows) || !isset($rows[0]['metricValues'])) {
        return $out;
    }
    foreach ($rows[0]['metricValues'] as $mv) {
        $out[] = isset($mv['value']) ? (string)$mv['value'] : '';
    }
    return $out;
}

/**
 * Turn runReport rows into a simple list of [dimensionName => value, ...] + [metricName => value, ...].
 *
 * @return list<array{dimensions: array<string,string>, metrics: array<string,string>}>
 */
function myomr_ga4_parse_rows(array $runReportResponse): array
{
    $dimHeaders = $runReportResponse['dimensionHeaders'] ?? [];
    $metHeaders = $runReportResponse['metricHeaders'] ?? [];
    $out = [];
    foreach ($runReportResponse['rows'] ?? [] as $row) {
        $dims = [];
        foreach ($row['dimensionValues'] ?? [] as $i => $dv) {
            $name = isset($dimHeaders[$i]['name']) ? (string)$dimHeaders[$i]['name'] : (string)$i;
            $dims[$name] = isset($dv['value']) ? (string)$dv['value'] : '';
        }
        $mets = [];
        foreach ($row['metricValues'] ?? [] as $i => $mv) {
            $name = isset($metHeaders[$i]['name']) ? (string)$metHeaders[$i]['name'] : (string)$i;
            $mets[$name] = isset($mv['value']) ? (string)$mv['value'] : '';
        }
        $out[] = ['dimensions' => $dims, 'metrics' => $mets];
    }
    return $out;
}
