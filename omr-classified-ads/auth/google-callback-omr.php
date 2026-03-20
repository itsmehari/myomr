<?php
/**
 * Google OAuth callback — creates or links account, sets session.
 */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/oauth-google.php';
require_once dirname(__DIR__) . '/includes/auth-identities.php';

ca_session_start();

$cfg = ca_google_oauth_config();
if (!$cfg) {
    header('Location: /omr-classified-ads/login-omr.php?oauth=missing_config');
    exit;
}

$state = $_GET['state'] ?? '';
$code = $_GET['code'] ?? '';
if ($code === '' || $state === '' || !hash_equals($_SESSION['ca_google_oauth_state'] ?? '', $state)) {
    header('Location: /omr-classified-ads/login-omr.php?oauth=bad_state');
    exit;
}
unset($_SESSION['ca_google_oauth_state']);

$tok = ca_google_exchange_code($code, $cfg);
if (!$tok) {
    header('Location: /omr-classified-ads/login-omr.php?oauth=token_fail');
    exit;
}

$info = ca_google_userinfo($tok['access_token']);
if (!$info || empty($info['email'])) {
    header('Location: /omr-classified-ads/login-omr.php?oauth=no_email');
    exit;
}

$sub = $info['sub'];
$email = filter_var($info['email'], FILTER_VALIDATE_EMAIL);
if (!$email) {
    header('Location: /omr-classified-ads/login-omr.php?oauth=no_email');
    exit;
}

$display = trim(strip_tags($info['name'] ?? ''));
if ($display === '') {
    $display = explode('@', $email)[0];
}

global $conn;

$stmt = $conn->prepare('SELECT id, google_sub, email FROM omr_classified_ads_users WHERE google_sub = ? LIMIT 1');
$stmt->bind_param('s', $sub);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    $stmt2 = $conn->prepare('SELECT id, google_sub, email FROM omr_classified_ads_users WHERE email = ? LIMIT 1');
    $stmt2->bind_param('s', $email);
    $stmt2->execute();
    $row = $stmt2->get_result()->fetch_assoc();
    if ($row && !empty($row['google_sub']) && $row['google_sub'] !== $sub) {
        header('Location: /omr-classified-ads/login-omr.php?oauth=email_linked_other');
        exit;
    }
}

$user_id = null;
if ($row) {
    $user_id = (int) $row['id'];
    $upd = $conn->prepare('UPDATE omr_classified_ads_users SET google_sub = ?, email_verified = 1, display_name = ? WHERE id = ?');
    $upd->bind_param('ssi', $sub, $display, $user_id);
    $upd->execute();
    ca_link_identity($conn, $user_id, 'google', $sub);
} else {
    $pass = password_hash(bin2hex(random_bytes(24)), PASSWORD_DEFAULT);
    $ins = $conn->prepare(
        'INSERT INTO omr_classified_ads_users (email, password_hash, display_name, email_verified, verify_token, verify_token_expires, google_sub, phone_verified)
         VALUES (?,?,?,1,NULL,NULL,?,0)'
    );
    $ins->bind_param('ssss', $email, $pass, $display, $sub);
    $ins->execute();
    $user_id = (int) $conn->insert_id;
    ca_link_identity($conn, $user_id, 'google', $sub);
}

$_SESSION['ca_user_id'] = $user_id;
$_SESSION['ca_user_email'] = $email;
$_SESSION['ca_user_name'] = $display;

header('Location: /omr-classified-ads/?oauth=google_ok');
exit;
