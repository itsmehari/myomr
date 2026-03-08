<?php
/**
 * Admin - Add to Featured Handler
 */

require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();

require_once __DIR__ . '/../../core/omr-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ids'])) {
    $ids = explode(',', $_GET['ids']);
    $featured_start = date('Y-m-d H:i:s');
    $featured_end = date('Y-m-d H:i:s', strtotime('+30 days'));
    
    foreach ($ids as $id) {
        $prop_id = (int)trim($id);
        if ($prop_id > 0) {
            $stmt = $conn->prepare("UPDATE hostels_pgs SET featured = 1, featured_start = ?, featured_end = ? WHERE id = ?");
            $stmt->bind_param("ssi", $featured_start, $featured_end, $prop_id);
            $stmt->execute();
        }
    }
}

header('Location: featured-management.php?success=1');
exit;

