<?php
session_start();
include_once '../core/omr-connect.php';
$msg = '';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (!$conn->query("DELETE FROM events WHERE id=$id")) {
        $_SESSION['flash_error'] = 'Error deleting event: ' . $conn->error;
    } else {
        $_SESSION['flash_success'] = 'Event deleted successfully!';
    }
}
header('Location: events-list.php');
exit; 