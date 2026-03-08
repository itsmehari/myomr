<?php
include_once '../../core/omr-connect.php';
$msg = '';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (!$conn->query("DELETE FROM events WHERE id=$id")) {
        $msg = 'Error deleting event: ' . $conn->error;
    }
}
header('Location: events-list.php' . ($msg ? '?error=' . urlencode($msg) : ''));
exit; 