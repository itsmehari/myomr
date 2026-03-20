<?php
require_once __DIR__ . '/includes/bootstrap.php';

$token = trim($_GET['token'] ?? '');
if ($token === '') {
    require_once ROOT_PATH . '/core/serve-404.php';
    exit;
}

global $conn;
$stmt = $conn->prepare('SELECT id, email, display_name FROM omr_classified_ads_users WHERE verify_token = ? AND verify_token_expires > NOW() AND email_verified = 0 LIMIT 1');
$stmt->bind_param('s', $token);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    require_once ROOT_PATH . '/core/serve-404.php';
    exit;
}

$uid = (int) $row['id'];
$upd = $conn->prepare('UPDATE omr_classified_ads_users SET email_verified = 1, verify_token = NULL, verify_token_expires = NULL WHERE id = ?');
$upd->bind_param('i', $uid);
$upd->execute();

ca_session_start();
$_SESSION['ca_user_id'] = $uid;
$_SESSION['ca_user_email'] = $row['email'];
$_SESSION['ca_user_name'] = $row['display_name'];

header('Location: /omr-classified-ads/?verified=1');
exit;
