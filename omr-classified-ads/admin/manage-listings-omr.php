<?php
/**
 * OMR Classified Ads — admin manage listings
 */
require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../includes/listing-functions.php';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = (int) $_GET['id'];
    $allowed = ['approve', 'reject', 'delete', 'feature', 'mark_sold'];
    if (in_array($action, $allowed, true) && $id > 0) {
        if ($action === 'approve') {
            $expiry = date('Y-m-d', strtotime('+10 days'));
            $stmt = $conn->prepare("UPDATE omr_classified_ads_listings SET status = 'approved', expiry_date = ? WHERE id = ?");
            $stmt->bind_param('si', $expiry, $id);
            $stmt->execute();
        } elseif ($action === 'reject') {
            $stmt = $conn->prepare("UPDATE omr_classified_ads_listings SET status = 'rejected' WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
        } elseif ($action === 'delete') {
            $stmt = $conn->prepare('DELETE FROM omr_classified_ads_listings WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
        } elseif ($action === 'feature') {
            $stmt = $conn->prepare("UPDATE omr_classified_ads_listings SET featured = 1 - COALESCE(featured, 0) WHERE id = ? AND status = 'approved'");
            $stmt->bind_param('i', $id);
            $stmt->execute();
        } elseif ($action === 'mark_sold') {
            $stmt = $conn->prepare("UPDATE omr_classified_ads_listings SET status = 'sold' WHERE id = ? AND status = 'approved'");
            $stmt->bind_param('i', $id);
            $stmt->execute();
        }
        header('Location: manage-listings-omr.php?' . http_build_query(array_diff_key($_GET, ['action' => '', 'id' => ''])));
        exit;
    }
}

$status_filter = $_GET['status'] ?? '';
$listings = [];
$where = '1=1';
$params = [];
$types = '';
if ($status_filter && in_array($status_filter, ['pending', 'approved', 'rejected', 'sold', 'expired'], true)) {
    $where = 'l.status = ?';
    $params[] = $status_filter;
    $types = 's';
}

$check = $conn->query("SHOW TABLES LIKE 'omr_classified_ads_listings'");
if ($check && $check->num_rows > 0) {
    $sql = "SELECT l.*, c.name AS category_name, u.display_name AS poster_name, u.email AS poster_email
            FROM omr_classified_ads_listings l
            LEFT JOIN omr_classified_ads_categories c ON l.category_id = c.id
            LEFT JOIN omr_classified_ads_users u ON l.user_id = u.id
            WHERE $where ORDER BY l.created_at DESC LIMIT 150";
    $stmt = $conn->prepare($sql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $listings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Classified Ads | Admin</title>
<?php include __DIR__ . '/../includes/admin-head-assets.php'; ?>
</head>
<body class="classified-ads-admin">
<?php require_once __DIR__ . '/../../components/skip-link.php'; ?>
<main id="main-content" class="container py-4">
  <header class="ca-admin-header">
    <h1>OMR Classified Ads — listings</h1>
  </header>
  <div class="mb-3 ca-admin-nav d-flex flex-wrap gap-2">
    <a href="?status=" class="btn btn-sm btn-outline-secondary">All</a>
    <a href="?status=pending" class="btn btn-sm btn-warning">Pending</a>
    <a href="?status=approved" class="btn btn-sm btn-success">Approved</a>
    <a href="?status=rejected" class="btn btn-sm btn-secondary">Rejected</a>
    <a href="?status=sold" class="btn btn-sm btn-info">Sold</a>
    <a href="?status=expired" class="btn btn-sm btn-secondary">Expired</a>
  </div>
  <div class="table-responsive">
    <table class="table table-sm">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Category</th>
          <th>Locality</th>
          <th>Price</th>
          <th>Status</th>
          <th>Poster</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($listings as $l): ?>
      <tr>
        <td><?= (int) $l['id'] ?></td>
        <td><?= htmlspecialchars(mb_strimwidth($l['title'], 0, 36, '…')) ?></td>
        <td><?= htmlspecialchars($l['category_name'] ?? '') ?></td>
        <td><?= htmlspecialchars($l['locality']) ?></td>
        <td><?= isset($l['price']) && $l['price'] > 0 ? '₹' . number_format($l['price']) : '—' ?></td>
        <td>
          <span class="badge bg-<?= $l['status'] === 'approved' ? 'success' : ($l['status'] === 'pending' ? 'warning' : 'secondary') ?>"><?= htmlspecialchars($l['status']) ?></span>
          <?php if (!empty($l['featured'])): ?><span class="badge bg-info ms-1">Featured</span><?php endif; ?>
        </td>
        <td><?= htmlspecialchars($l['poster_name'] ?? '') ?></td>
        <td>
          <?php if (in_array($l['status'], ['approved', 'sold', 'expired'], true)): ?>
          <a href="/omr-classified-ads/<?= htmlspecialchars(getClassifiedAdsDetailPath((int) $l['id'], $l['title'])) ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
          <?php endif; ?>
          <?php if ($l['status'] === 'pending'): ?>
          <a href="?action=approve&id=<?= (int) $l['id'] ?>&status=<?= urlencode($status_filter) ?>" class="btn btn-sm btn-success" title="Approve"><i class="fas fa-check"></i></a>
          <a href="?action=reject&id=<?= (int) $l['id'] ?>&status=<?= urlencode($status_filter) ?>" class="btn btn-sm btn-danger" title="Reject"><i class="fas fa-times"></i></a>
          <?php endif; ?>
          <?php if ($l['status'] === 'approved'): ?>
          <a href="?action=feature&id=<?= (int) $l['id'] ?>&status=<?= urlencode($status_filter) ?>" class="btn btn-sm btn-outline-info" title="Feature"><i class="fas fa-star"></i></a>
          <a href="?action=mark_sold&id=<?= (int) $l['id'] ?>&status=<?= urlencode($status_filter) ?>" class="btn btn-sm btn-outline-secondary" title="Mark sold"><i class="fas fa-tag"></i></a>
          <?php endif; ?>
          <a href="?action=delete&id=<?= (int) $l['id'] ?>&status=<?= urlencode($status_filter) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?');"><i class="fas fa-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if (empty($listings)): ?>
  <p class="text-muted">No listings.</p>
  <?php endif; ?>
  <a href="view-reports-omr.php" class="btn btn-outline-dark me-2">Reports</a>
  <a href="index.php" class="btn btn-outline-secondary">Back</a>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
