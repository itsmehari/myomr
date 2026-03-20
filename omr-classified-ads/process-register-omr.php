<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once ROOT_PATH . '/core/security-helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register-omr.php');
    exit;
}

ca_session_start();
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    die('Invalid request.');
}

if (ca_user_id() !== null) {
    header('Location: /omr-classified-ads/');
    exit;
}

if (file_exists(ROOT_PATH . '/core/env.php')) {
    require_once ROOT_PATH . '/core/env.php';
}

$display_name = trim(strip_tags((string) ($_POST['display_name'] ?? '')));
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$errors = [];
if (strlen($display_name) < 2) {
    $errors[] = 'Please enter your name.';
}
if (!validate_email($email)) {
    $errors[] = 'Invalid email.';
}
if (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters.';
}

global $conn;
if (empty($errors)) {
    $chk = $conn->prepare('SELECT id FROM omr_classified_ads_users WHERE email = ? LIMIT 1');
    $chk->bind_param('s', $email);
    $chk->execute();
    if ($chk->get_result()->num_rows > 0) {
        $errors[] = 'That email is already registered. Try logging in.';
    }
}

if (!empty($errors)) {
    $_SESSION['ca_reg_errors'] = $errors;
    $_SESSION['ca_reg_prefill'] = ['display_name' => $display_name, 'email' => $email];
    header('Location: register-omr.php');
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$auto_verify = (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) || getenv('MYOMR_CA_AUTO_VERIFY') === '1';

if ($auto_verify) {
    $stmt = $conn->prepare('INSERT INTO omr_classified_ads_users (email, password_hash, display_name, email_verified, verify_token, verify_token_expires) VALUES (?,?,?,1,NULL,NULL)');
    $stmt->bind_param('sss', $email, $hash, $display_name);
    $stmt->execute();
    $new_id = (int) $conn->insert_id;
    $_SESSION['ca_user_id'] = $new_id;
    $_SESSION['ca_user_email'] = $email;
    $_SESSION['ca_user_name'] = $display_name;
    header('Location: /omr-classified-ads/?registered=1');
    exit;
}

$token = bin2hex(random_bytes(32));
$exp = date('Y-m-d H:i:s', strtotime('+48 hours'));
$stmt = $conn->prepare('INSERT INTO omr_classified_ads_users (email, password_hash, display_name, email_verified, verify_token, verify_token_expires) VALUES (?,?,?,0,?,?)');
$stmt->bind_param('sssss', $email, $hash, $display_name, $token, $exp);
$stmt->execute();

$verify_url = 'https://myomr.in/omr-classified-ads/verify-email-omr.php?token=' . urlencode($token);
$subject = 'Verify your MyOMR Classified Ads account';
$body = "Hi {$display_name},\n\nClick to verify your email:\n{$verify_url}\n\n— MyOMR.in";
@mail($email, $subject, $body, "From: noreply@myomr.in\r\nContent-Type: text/plain; charset=UTF-8");

header('Location: register-omr.php?check_email=1');
exit;
