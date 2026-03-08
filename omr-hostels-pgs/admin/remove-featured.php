<?php
/**
 * Admin - Remove from Featured Handler
 */

require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();

require_once __DIR__ . '/../../core/omr-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $prop_id = (int)$_GET['id'];
    if ($prop_id > 0) {
        $stmt = $conn->prepare("UPDATE hostels_pgs SET featured = 0, featured_start = NULL, featured_end = NULL WHERE id = ?");
        $stmt->bind_param("i", $prop_id);
        $stmt->execute();
    }
}

header('Location: featured-management.php?success=1');
exit;

