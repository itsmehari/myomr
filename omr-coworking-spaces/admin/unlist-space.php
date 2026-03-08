<?php
/**
 * Admin - Unlist Space Handler
 */

require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();

require_once __DIR__ . '/../../core/omr-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $space_id = (int)$_GET['id'];
    
    if ($space_id > 0) {
        $stmt = $conn->prepare("UPDATE coworking_spaces SET status = 'inactive' WHERE id = ?");
        $stmt->bind_param("i", $space_id);
        $stmt->execute();
    }
}

header('Location: manage-spaces.php?success=1');
exit;

