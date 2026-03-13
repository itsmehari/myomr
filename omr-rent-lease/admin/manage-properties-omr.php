<?php
/**
 * Rent & Lease Admin - Manage Properties
 */
require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();
require_once __DIR__ . '/../../core/omr-connect.php';

$status_filter = $_GET['status'] ?? '';
$props = [];
$where = "1=1";
$params = [];
$types = '';
if ($status_filter && in_array($status_filter, ['pending','approved','rejected'], true)) {
    $where = "status = ?";
    $params[] = $status_filter;
    $types = 's';
}

$sql = "SELECT p.*, o.name as owner_name FROM rent_lease_properties p LEFT JOIN rent_lease_owners o ON p.owner_id = o.id WHERE $where ORDER BY p.created_at DESC LIMIT 100";
$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$props = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Properties | Rent & Lease Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php require_once __DIR__ . '/../../components/skip-link.php'; ?>
<main id="main-content" class="container py-4">
  <h1 class="h3 mb-4">Manage Properties</h1>
  <div class="mb-3">
    <a href="?status=" class="btn btn-sm btn-outline-secondary">All</a>
    <a href="?status=pending" class="btn btn-sm btn-warning">Pending</a>
    <a href="?status=approved" class="btn btn-sm btn-success">Approved</a>
  </div>
  <div class="table-responsive">
    <table class="table table-sm">
      <thead><tr><th>ID</th><th>Title</th><th>Type</th><th>Locality</th><th>Status</th><th>Owner</th></tr></thead>
      <tbody>
      <?php foreach ($props as $p): ?>
      <tr>
        <td><?= (int)$p['id'] ?></td>
        <td><?= htmlspecialchars($p['title']) ?></td>
        <td><?= htmlspecialchars($p['listing_type']) ?></td>
        <td><?= htmlspecialchars($p['locality']) ?></td>
        <td><span class="badge bg-<?= $p['status'] === 'approved' ? 'success' : ($p['status'] === 'pending' ? 'warning' : 'secondary') ?>"><?= htmlspecialchars($p['status']) ?></span></td>
        <td><?= htmlspecialchars($p['owner_name'] ?? '') ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <a href="index.php" class="btn btn-outline-secondary">Back</a>
</main>
</body>
</html>
