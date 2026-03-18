<?php
/**
 * OMR Buy-Sell Admin Dashboard
 */
require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buy & Sell Admin | MyOMR</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
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
  <a href="/admin/" class="btn btn-outline-secondary">Back to Admin</a>
</main>
</body>
</html>
