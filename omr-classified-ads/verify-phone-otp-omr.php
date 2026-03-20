<?php
/**
 * POST: code ; uses session ca_phone_otp_pending + ca_phone_otp_intent
 */
require_once __DIR__ . '/includes/bootstrap.php';
require_once ROOT_PATH . '/core/security-helpers.php';
require_once __DIR__ . '/includes/phone-otp.php';
require_once __DIR__ . '/includes/auth-identities.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /omr-classified-ads/login-omr.php');
    exit;
}

ca_session_start();
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    exit('Invalid request');
}

$e164 = $_SESSION['ca_phone_otp_pending'] ?? '';
$intent = $_SESSION['ca_phone_otp_intent'] ?? '';
$code = preg_replace('/\D/', '', $_POST['code'] ?? '');

if ($e164 === '' || !in_array($intent, ['login', 'link'], true) || strlen($code) !== 6) {
    header('Location: /omr-classified-ads/login-omr.php?phone_err=session');
    exit;
}

$pepper = getenv('MYOMR_CA_OTP_PEPPER') ?: 'myomr-classified-otp-pepper-change-me';
$try_hash = hash('sha256', $pepper . '|' . $e164 . '|' . $code);

global $conn;
$stmt = $conn->prepare(
    'SELECT id, code_hash, expires_at, attempts FROM omr_classified_ads_phone_otp WHERE phone_e164 = ? ORDER BY id DESC LIMIT 1'
);
$stmt->bind_param('s', $e164);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

$fail_redirect = $intent === 'link' ? '/omr-classified-ads/account-omr.php?phone_err=bad_code' : '/omr-classified-ads/phone-login-omr.php?step=2&phone_err=bad_code';

if (!$row || strtotime($row['expires_at']) < time()) {
    unset($_SESSION['ca_phone_otp_pending'], $_SESSION['ca_phone_otp_intent']);
    header('Location: ' . $fail_redirect);
    exit;
}

if (!hash_equals($row['code_hash'], $try_hash)) {
    $attempts = (int) $row['attempts'] + 1;
    $upd = $conn->prepare('UPDATE omr_classified_ads_phone_otp SET attempts = ? WHERE id = ?');
    $upd->bind_param('ii', $attempts, $row['id']);
    $upd->execute();
    header('Location: ' . $fail_redirect);
    exit;
}
if ((int) $row['attempts'] > 8) {
    header('Location: ' . $fail_redirect);
    exit;
}

$del = $conn->prepare('DELETE FROM omr_classified_ads_phone_otp WHERE phone_e164 = ?');
$del->bind_param('s', $e164);
$del->execute();

unset($_SESSION['ca_phone_otp_pending'], $_SESSION['ca_phone_otp_intent']);

if ($intent === 'link') {
    ca_require_login();
    $uid = ca_user_id();
    $chk = $conn->prepare('SELECT id FROM omr_classified_ads_users WHERE phone_e164 = ? AND id != ? LIMIT 1');
    $chk->bind_param('si', $e164, $uid);
    $chk->execute();
    if ($chk->get_result()->num_rows > 0) {
        header('Location: /omr-classified-ads/account-omr.php?phone_err=taken');
        exit;
    }
    $u = $conn->prepare('UPDATE omr_classified_ads_users SET phone_e164 = ?, phone_verified = 1 WHERE id = ?');
    $u->bind_param('si', $e164, $uid);
    $u->execute();
    ca_link_identity($conn, $uid, 'phone', $e164);
    header('Location: /omr-classified-ads/account-omr.php?phone_ok=1');
    exit;
}

$stmt = $conn->prepare('SELECT id, email, display_name FROM omr_classified_ads_users WHERE phone_e164 = ? AND phone_verified = 1 LIMIT 1');
$stmt->bind_param('s', $e164);
$stmt->execute();
$urow = $stmt->get_result()->fetch_assoc();

if ($urow) {
    $_SESSION['ca_user_id'] = (int) $urow['id'];
    $_SESSION['ca_user_email'] = $urow['email'];
    $_SESSION['ca_user_name'] = $urow['display_name'];
    header('Location: /omr-classified-ads/?oauth=phone_ok');
    exit;
}

$synth = ca_synthetic_email_for_phone($e164);
$chk2 = $conn->prepare('SELECT id FROM omr_classified_ads_users WHERE email = ? LIMIT 1');
$chk2->bind_param('s', $synth);
$chk2->execute();
if ($chk2->get_result()->num_rows > 0) {
    header('Location: /omr-classified-ads/login-omr.php?phone_err=conflict');
    exit;
}

$digits = preg_replace('/\D/', '', $e164);
$tail = strlen($digits) >= 4 ? substr($digits, -4) : $digits;
$display = 'User ' . $tail;
$pass = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
$ins = $conn->prepare(
    'INSERT INTO omr_classified_ads_users (email, password_hash, display_name, email_verified, verify_token, verify_token_expires, google_sub, phone_e164, phone_verified)
     VALUES (?,?,?,1,NULL,NULL,NULL,?,1)'
);
$ins->bind_param('ssss', $synth, $pass, $display, $e164);
$ins->execute();
$new_id = (int) $conn->insert_id;
ca_link_identity($conn, $new_id, 'phone', $e164);

$_SESSION['ca_user_id'] = $new_id;
$_SESSION['ca_user_email'] = $synth;
$_SESSION['ca_user_name'] = $display;

header('Location: /omr-classified-ads/?oauth=phone_ok');
exit;
