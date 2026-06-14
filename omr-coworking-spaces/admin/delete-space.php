<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('COWORKING_ADMIN_ROUTED', '/superadmin/coworking/delete-space.php');
require_once __DIR__ . '/_urls.php';
/**
 * Admin - Delete coworking space listing
 */

require_once __DIR__ . '/../../core/omr-connect.php';

if (($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') && isset($_GET['id'])) {
    $space_id = (int)$_GET['id'];

    if ($space_id > 0) {
        $stmt = $conn->prepare('DELETE FROM coworking_spaces WHERE id = ?');
        $stmt->bind_param('i', $space_id);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: ' . COWORKING_ADMIN_SPACES_URL . '?success=1');
exit;
