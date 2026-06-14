<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('HOSTELS_ADMIN_ROUTED', '/superadmin/hostels/reject-property.php');
require_once __DIR__ . '/_urls.php';
/**
 * Admin - Reject pending property (deny submission)
 */

require_once __DIR__ . '/../../core/omr-connect.php';

if (($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') && isset($_GET['id'])) {
    $property_id = (int)$_GET['id'];

    if ($property_id > 0) {
        $stmt = $conn->prepare("UPDATE hostels_pgs SET status = 'inactive' WHERE id = ? AND status = 'pending'");
        $stmt->bind_param('i', $property_id);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: ' . HOSTELS_ADMIN_PROPERTIES_URL . '?success=1');
exit;
