<?php
/**
 * Admin - Verify Property Handler
 */

require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();

require_once __DIR__ . '/../../core/omr-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $property_id = (int)$_GET['id'];
    
    if ($property_id > 0) {
        $stmt = $conn->prepare("UPDATE hostels_pgs SET verification_status = 'verified' WHERE id = ?");
        $stmt->bind_param("i", $property_id);
        $stmt->execute();
    }
}

header('Location: manage-properties.php?success=1');
exit;

