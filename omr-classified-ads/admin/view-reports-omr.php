<?php
require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../includes/listing-functions.php';

$reports = [];
$check = $conn->query("SHOW TABLES LIKE 'omr_classified_ads_reports'");
if ($check && $check->num_rows > 0) {
    $sql = "SELECT r.*, l.title AS listing_title, l.status AS listing_status
            FROM omr_classified_ads_reports r
            LEFT JOIN omr_classified_ads_listings l ON r.listing_id = l.id
            ORDER BY r.created_at DESC LIMIT 200";
    $r = $conn->query($sql);
    if ($r) {
        $reports = $r->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Classified Ads Reports | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php require_once __DIR__ . '/../../components/skip-link.php'; ?>
<main id="main-content" class="container py-4">
  <h1 class="h3 mb-4"><i class="fas fa-flag me-2"></i>Classified ads reports</h1>
  <div class="table-responsive">
    <table class="table table-sm">
      <thead>
        <tr>
          <th>ID</th>
          <th>Listing</th>
          <th>Reason</th>
          <th>Notes</th>
          <th>Status</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($reports as $r): ?>
      <tr>
        <td><?= (int) $r['id'] ?></td>
        <td>
          <?php if (!empty($r['listing_id']) && !empty($r['listing_title'])): ?>
          <a href="/omr-classified-ads/<?= htmlspecialchars(getClassifiedAdsDetailPath((int) $r['listing_id'], $r['listing_title'])) ?>" target="_blank">
            <?= htmlspecialchars(mb_strimwidth($r['listing_title'], 0, 40, '…')) ?>
          </a>
          <br><small class="text-muted">#<?= (int) $r['listing_id'] ?> · <?= htmlspecialchars($r['listing_status'] ?? '') ?></small>
          <?php else: ?>
          #<?= (int) ($r['listing_id'] ?? 0) ?>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($r['reason']) ?></td>
        <td><?= htmlspecialchars(mb_strimwidth($r['notes'] ?? '', 0, 60, '…')) ?></td>
        <td><span class="badge bg-<?= $r['status'] === 'pending' ? 'warning' : 'secondary' ?>"><?= htmlspecialchars($r['status']) ?></span></td>
        <td><?= date('M j, Y H:i', strtotime($r['created_at'])) ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if (empty($reports)): ?>
  <p class="text-muted">No reports.</p>
  <?php endif; ?>
  <a href="manage-listings-omr.php" class="btn btn-outline-secondary me-2">Listings</a>
  <a href="index.php" class="btn btn-outline-secondary">Back</a>
</main>
</body>
</html>
