<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('HOSTELS_ADMIN_ROUTED', '/superadmin/hostels/bulk-update-properties.php');
require_once __DIR__ . '/_urls.php';
/**
 * Admin - Bulk Update Properties
 */

require_once __DIR__ . '/../../core/omr-connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage-properties.php');
    exit;
}

$idsRaw = $_POST['ids'] ?? '';
$action = $_POST['action'] ?? '';

if (!$idsRaw || !$action) {
    header('Location: manage-properties.php?success=0');
    exit;
}

$allowedActions = [
    'activate' => 'active',
    'pending' => 'pending',
    'inactive' => 'inactive',
];

if (!array_key_exists($action, $allowedActions)) {
    header('Location: manage-properties.php?success=0');
    exit;
}

$status = $allowedActions[$action];
$ids = array_filter(array_map('intval', explode(',', $idsRaw)));

if (empty($ids)) {
    header('Location: manage-properties.php?success=0');
    exit;
}

$idList = implode(',', array_map('intval', $ids));
$stmt = $conn->prepare("UPDATE hostels_pgs SET status = ? WHERE id IN ($idList)");
if ($stmt) {
    $stmt->bind_param('s', $status);
    $stmt->execute();
}

header('Location: manage-properties.php?success=1');
exit;


