<?php
/**
 * OMR Buy-Sell — CAPTCHA config (reCAPTCHA v2)
 * Define RECAPTCHA_SITE_KEY and RECAPTCHA_SECRET_KEY in a config or .env to enable.
 */
if (!defined('RECAPTCHA_SITE_KEY')) {
    define('RECAPTCHA_SITE_KEY', '');
}
if (!defined('RECAPTCHA_SECRET_KEY')) {
    define('RECAPTCHA_SECRET_KEY', '');
}

function isCaptchaEnabled(): bool {
    return RECAPTCHA_SITE_KEY !== '' && RECAPTCHA_SECRET_KEY !== '';
}

function verifyRecaptcha(string $response): bool {
    if (!isCaptchaEnabled()) return true;
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = ['secret' => RECAPTCHA_SECRET_KEY, 'response' => $response, 'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''];
    $opts = ['http' => ['method' => 'POST', 'header' => 'Content-type: application/x-www-form-urlencoded', 'content' => http_build_query($data)]];
    $ctx = stream_context_create($opts);
    $result = @file_get_contents($url, false, $ctx);
    if ($result === false) return false;
    $json = json_decode($result, true);
    return !empty($json['success']);
}
