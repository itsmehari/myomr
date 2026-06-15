<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('RENT_LEASE_ADMIN_ROUTED', '/superadmin/rent-lease/index.php');
require_once __DIR__ . '/_urls.php';
/**
 * Rent & Lease Admin Dashboard
 */
require_once __DIR__ . '/../../core/omr-connect.php';

$stats = ['pending' => 0, 'approved' => 0, 'total' => 0];
$r = $conn->query("SELECT status, COUNT(*) c FROM rent_lease_properties GROUP BY status");
if ($r) {
    while ($row = $r->fetch_assoc()) {
        $stats[$row['status']] = (int)$row['c'];
        $stats['total'] += (int)$row['c'];
    }
}

$__modulePageTitle = 'Rent & Lease Admin';
$__moduleActiveNav = '/superadmin/rent-lease/';
if (myomr_module_using_shell()) {
    myomr_module_shell_open($__modulePageTitle, $__moduleActiveNav);
} else {
?><!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Rent & Lease Admin</title></head>
<body>
<?php } ?>
<?php require_once __DIR__ . '/../../components/skip-link.php'; ?>
<main id="main-content" class="container py-4">
  <h1 class="h3 mb-4"><i class="fas fa-house me-2"></i>Rent & Lease Admin</h1>
  <div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card"><div class="card-body"><h5>Pending</h5><h2><?= $stats['pending'] ?? 0 ?></h2></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h5>Approved</h5><h2><?= $stats['approved'] ?? 0 ?></h2></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h5>Total</h5><h2><?= $stats['total'] ?? 0 ?></h2></div></div></div>
  </div>
  <a href="<?= htmlspecialchars(RENT_LEASE_ADMIN_MANAGE_URL, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-success">Manage Properties</a>
  <a href="/superadmin/index.php" class="btn btn-outline-secondary">Back to Command Center</a>
</main>
<?php if (myomr_module_using_shell()) { ?>
<?php myomr_module_shell_close(); ?>
<?php } else { ?>
</body>
</html>
<?php } ?>
