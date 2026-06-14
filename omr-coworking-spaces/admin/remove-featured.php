<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('COWORKING_ADMIN_ROUTED', '/superadmin/coworking/remove-featured.php');
require_once __DIR__ . '/_urls.php';
/**
 * Admin - Remove from Featured Handler
 */

require_once __DIR__ . '/../../core/omr-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $space_id = (int)$_GET['id'];
    
    if ($space_id > 0) {
        $stmt = $conn->prepare("UPDATE coworking_spaces SET featured = 0, featured_start = NULL, featured_end = NULL WHERE id = ?");
        $stmt->bind_param("i", $space_id);
        $stmt->execute();
    }
}

header('Location: featured-management.php?success=1');
exit;

