<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('COWORKING_ADMIN_ROUTED', '/superadmin/coworking/reject-space.php');
require_once __DIR__ . '/_urls.php';
/**
 * Admin - Reject pending coworking space
 */

require_once __DIR__ . '/../../core/omr-connect.php';

if (($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') && isset($_GET['id'])) {
    $space_id = (int)$_GET['id'];

    if ($space_id > 0) {
        $stmt = $conn->prepare("UPDATE coworking_spaces SET status = 'inactive' WHERE id = ? AND status = 'pending'");
        $stmt->bind_param('i', $space_id);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: ' . COWORKING_ADMIN_SPACES_URL . '?success=1');
exit;
