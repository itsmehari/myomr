<?php
require_once dirname(__DIR__, 2) . '/superadmin/includes/module-router.php';
myomr_module_require_routed('BUY_SELL_ADMIN_ROUTED', '/superadmin/buy-sell/index.php');
require_once __DIR__ . '/_urls.php';
/**
 * OMR Buy-Sell Admin Dashboard
 */
require_once __DIR__ . '/../../core/omr-connect.php';

$stats = ['pending' => 0, 'approved' => 0, 'rejected' => 0, 'sold' => 0, 'expired' => 0, 'total' => 0];
$check = $conn->query("SHOW TABLES LIKE 'omr_buy_sell_listings'");
if ($check && $check->num_rows > 0) {
    $r = $conn->query("SELECT status, COUNT(*) c FROM omr_buy_sell_listings GROUP BY status");
    if ($r) {
        while ($row = $r->fetch_assoc()) {
            $stats[$row['status']] = (int)$row['c'];
            $stats['total'] += (int)$row['c'];
        }
    }
}

$__modulePageTitle = 'Buy & Sell Admin';
$__moduleActiveNav = '/superadmin/buy-sell/';
if (myomr_module_using_shell()) {
    myomr_module_shell_open($__modulePageTitle, $__moduleActiveNav);
} else {
?><!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Buy & Sell Admin</title></head>
<body>
<?php } ?>
<?php require_once __DIR__ . '/../../components/skip-link.php'; ?>
<main id="main-content" class="container py-4">
  <h1 class="h3 mb-4"><i class="fas fa-shopping-bag me-2"></i>Buy & Sell Admin</h1>
  <div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card"><div class="card-body"><h5 class="text-warning">Pending</h5><h2><?= $stats['pending'] ?? 0 ?></h2></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h5 class="text-success">Approved</h5><h2><?= $stats['approved'] ?? 0 ?></h2></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h5>Total</h5><h2><?= $stats['total'] ?? 0 ?></h2></div></div></div>
  </div>
  <a href="manage-listings-omr.php" class="btn btn-success me-2">Manage Listings</a>
  <a href="view-reports-omr.php" class="btn btn-outline-warning me-2">View Reports</a>
  <a href="manage-categories-omr.php" class="btn btn-outline-secondary me-2">Manage Categories</a>
  <a href="/superadmin/index.php" class="btn btn-outline-secondary">Back to Command Center</a>
</main>

<?php
if (myomr_module_using_shell()) {
    myomr_module_shell_close();
} else {
?>
</body></html>
<?php }
