<?php
/**
 * POST: phone, intent = login|link ; CSRF. Sends OTP via Twilio (or fails if not configured).
 */
if (file_exists(dirname(__DIR__) . '/core/env.php')) {
    require_once dirname(__DIR__) . '/core/env.php';
}
require_once __DIR__ . '/includes/bootstrap.php';
require_once ROOT_PATH . '/core/security-helpers.php';
require_once __DIR__ . '/includes/phone-otp.php';
require_once __DIR__ . '/includes/sms-twilio.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /omr-classified-ads/login-omr.php');
    exit;
}

ca_session_start();
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    exit('Invalid request');
}

$intent = $_POST['intent'] ?? '';
if (!in_array($intent, ['login', 'link'], true)) {
    header('Location: /omr-classified-ads/login-omr.php?phone_err=intent');
    exit;
}

if ($intent === 'link') {
    ca_require_login();
}

$e164 = ca_normalize_phone_in($_POST['phone'] ?? '');
if (!$e164) {
    header('Location: /omr-classified-ads/login-omr.php?phone_err=invalid');
    exit;
}

global $conn;
ca_otp_purge_expired($conn);

if (!ca_otp_rate_ok($conn, $e164, 5)) {
    header('Location: /omr-classified-ads/login-omr.php?phone_err=rate');
    exit;
}

$code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$pepper = getenv('MYOMR_CA_OTP_PEPPER') ?: 'myomr-classified-otp-pepper-change-me';
$hash = hash('sha256', $pepper . '|' . $e164 . '|' . $code);
$exp = date('Y-m-d H:i:s', strtotime('+10 minutes'));

$del = $conn->prepare('DELETE FROM omr_classified_ads_phone_otp WHERE phone_e164 = ?');
$del->bind_param('s', $e164);
$del->execute();

$ins = $conn->prepare('INSERT INTO omr_classified_ads_phone_otp (phone_e164, code_hash, expires_at, attempts) VALUES (?,?,?,0)');
$ins->bind_param('sss', $e164, $hash, $exp);
$ins->execute();

$sent = ca_send_sms_twilio($e164, 'MyOMR Classified Ads: your login code is ' . $code . '. Valid 10 minutes. Do not share.');
if (!$sent && defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) {
    error_log('CA OTP (dev, SMS not sent): ' . $e164 . ' code=' . $code);
    $sent = true;
}

if (!$sent) {
    header('Location: /omr-classified-ads/login-omr.php?phone_err=sms_config');
    exit;
}

$_SESSION['ca_phone_otp_intent'] = $intent;
$_SESSION['ca_phone_otp_pending'] = $e164;

if ($intent === 'link') {
    header('Location: /omr-classified-ads/account-omr.php?phone_step=2');
} else {
    header('Location: /omr-classified-ads/phone-login-omr.php?step=2');
}
exit;
