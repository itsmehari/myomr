<?php
/**
 * Google OAuth for OMR Classified Ads — config via env (no secrets in repo).
 *
 * MYOMR_CLASSIFIED_GOOGLE_CLIENT_ID
 * MYOMR_CLASSIFIED_GOOGLE_CLIENT_SECRET
 * Optional MYOMR_CLASSIFIED_GOOGLE_REDIRECT_URI (default: production callback URL)
 */
function ca_google_oauth_config(): ?array {
    $id = getenv('MYOMR_CLASSIFIED_GOOGLE_CLIENT_ID');
    $secret = getenv('MYOMR_CLASSIFIED_GOOGLE_CLIENT_SECRET');
    if (!$id || !$secret) {
        return null;
    }
    $redirect = getenv('MYOMR_CLASSIFIED_GOOGLE_REDIRECT_URI') ?: 'https://myomr.in/omr-classified-ads/auth/google-callback-omr.php';
    return [
        'client_id' => $id,
        'client_secret' => $secret,
        'redirect_uri' => $redirect,
    ];
}

function ca_google_auth_url(string $state): ?string {
    $cfg = ca_google_oauth_config();
    if (!$cfg) {
        return null;
    }
    $q = http_build_query([
        'client_id' => $cfg['client_id'],
        'redirect_uri' => $cfg['redirect_uri'],
        'response_type' => 'code',
        'scope' => 'openid email profile',
        'state' => $state,
        'access_type' => 'online',
        'prompt' => 'select_account',
    ]);
    return 'https://accounts.google.com/o/oauth2/v2/auth?' . $q;
}

/** @return array{access_token:string}|null */
function ca_google_exchange_code(string $code, array $cfg): ?array {
    $ch = curl_init('https://oauth2.googleapis.com/token');
    $post = http_build_query([
        'code' => $code,
        'client_id' => $cfg['client_id'],
        'client_secret' => $cfg['client_secret'],
        'redirect_uri' => $cfg['redirect_uri'],
        'grant_type' => 'authorization_code',
    ]);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $post,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
    ]);
    $raw = curl_exec($ch);
    curl_close($ch);
    if ($raw === false) {
        return null;
    }
    $json = json_decode($raw, true);
    if (empty($json['access_token'])) {
        return null;
    }
    return $json;
}

/** @return array{sub:string,email?:string,email_verified?:bool}|null */
function ca_google_userinfo(string $access_token): ?array {
    $ch = curl_init('https://www.googleapis.com/oauth2/v3/userinfo');
    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $access_token],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
    ]);
    $raw = curl_exec($ch);
    curl_close($ch);
    if ($raw === false) {
        return null;
    }
    $j = json_decode($raw, true);
    if (empty($j['sub'])) {
        return null;
    }
    return $j;
}
