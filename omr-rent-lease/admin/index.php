<?php
/**
 * Rent & Lease Admin Dashboard
 */
require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();
require_once __DIR__ . '/../../core/omr-connect.php';

$stats = ['pending' => 0, 'approved' => 0, 'total' => 0];
$r = $conn->query("SELECT status, COUNT(*) c FROM rent_lease_properties GROUP BY status");
if ($r) {
    while ($row = $r->fetch_assoc()) {
        $stats[$row['status']] = (int)$row['c'];
        $stats['total'] += (int)$row['c'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rent & Lease Admin | MyOMR</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php require_once __DIR__ . '/../../components/skip-link.php'; ?>
<main id="main-content" class="container py-4">
  <h1 class="h3 mb-4"><i class="fas fa-house me-2"></i>Rent & Lease Admin</h1>
  <div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card"><div class="card-body"><h5>Pending</h5><h2><?= $stats['pending'] ?? 0 ?></h2></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h5>Approved</h5><h2><?= $stats['approved'] ?? 0 ?></h2></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h5>Total</h5><h2><?= $stats['total'] ?? 0 ?></h2></div></div></div>
  </div>
  <a href="manage-properties-omr.php" class="btn btn-success">Manage Properties</a>
  <a href="/admin/" class="btn btn-outline-secondary">Back to Admin</a>
</main>
</body>
</html>
