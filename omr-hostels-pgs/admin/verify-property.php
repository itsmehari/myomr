<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('HOSTELS_ADMIN_ROUTED', '/superadmin/hostels/verify-property.php');
require_once __DIR__ . '/_urls.php';
/**
 * Admin - Verify Property Handler
 */

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

