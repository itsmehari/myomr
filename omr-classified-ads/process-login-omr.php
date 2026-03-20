<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once ROOT_PATH . '/core/security-helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login-omr.php');
    exit;
}

ca_session_start();
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    die('Invalid request.');
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$redirect = $_POST['redirect'] ?? '/omr-classified-ads/';
if (!is_string($redirect) || $redirect === '' || strpos($redirect, '/') !== 0) {
    $redirect = '/omr-classified-ads/';
}

global $conn;
$stmt = $conn->prepare('SELECT id, password_hash, display_name, email_verified FROM omr_classified_ads_users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row || !password_verify($password, $row['password_hash'])) {
    $_SESSION['ca_login_error'] = 'Invalid email or password.';
    header('Location: login-omr.php');
    exit;
}

$_SESSION['ca_user_id'] = (int) $row['id'];
$_SESSION['ca_user_email'] = $email;
$_SESSION['ca_user_name'] = $row['display_name'];

header('Location: ' . $redirect);
exit;
