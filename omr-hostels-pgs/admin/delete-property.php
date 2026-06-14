<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('HOSTELS_ADMIN_ROUTED', '/superadmin/hostels/delete-property.php');
require_once __DIR__ . '/_urls.php';
/**
 * Admin - Delete property listing
 */

require_once __DIR__ . '/../../core/omr-connect.php';

if (($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') && isset($_GET['id'])) {
    $property_id = (int)$_GET['id'];

    if ($property_id > 0) {
        $stmt = $conn->prepare('DELETE FROM hostels_pgs WHERE id = ?');
        $stmt->bind_param('i', $property_id);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: ' . HOSTELS_ADMIN_PROPERTIES_URL . '?success=1');
exit;
