<?php
require_once __DIR__ . '/../_bootstrap.php';
include_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/security-helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'delete') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    $_SESSION['flash_error'] = 'Invalid CSRF token.';
    header('Location: events-list.php');
    exit;
}

$id = intval($_POST['id'] ?? 0);
if ($id > 0) {
    $stmt = $conn->prepare('DELETE FROM events WHERE id = ?');
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        $_SESSION['flash_error'] = 'Error deleting event: ' . $conn->error;
    } else {
        $_SESSION['flash_success'] = 'Event deleted successfully!';
    }
    $stmt->close();
}
header('Location: events-list.php');
exit; 
