<?php
require_once __DIR__ . '/includes/bootstrap.php';

$listing_id = max(0, (int) ($_GET['listing_id'] ?? 0));
$listing = $listing_id > 0 ? getClassifiedAdsListingById($listing_id) : null;

if (!$listing) {
    require_once ROOT_PATH . '/core/serve-404.php';
    exit;
}

$page_title = 'Report listing | OMR Classified Ads | MyOMR';

ca_session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$submitted = isset($_GET['submitted']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>
<meta name="robots" content="noindex">
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="classified-ads-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content" class="container py-4">
  <?php if ($submitted): ?>
  <div class="alert alert-success">Thank you. Your report will be reviewed.</div>
  <a href="/omr-classified-ads/<?= htmlspecialchars(getClassifiedAdsDetailPath($listing_id, $listing['title'])) ?>" class="btn btn-outline-secondary">Back to listing</a>
  <?php else: ?>
  <h1 class="h2 mb-4">Report listing</h1>
  <p class="mb-3">Reporting: <strong><?= htmlspecialchars($listing['title']) ?></strong></p>
  <form method="post" action="process-report-omr.php">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    <input type="hidden" name="listing_id" value="<?= (int) $listing_id ?>">
    <div class="mb-3">
      <label class="form-label" for="reason">Reason</label>
      <select name="reason" id="reason" class="form-select" required>
        <option value="">Select…</option>
        <option value="spam">Spam</option>
        <option value="fake">Fake or misleading</option>
        <option value="inappropriate">Inappropriate</option>
        <option value="other">Other</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label" for="notes">Details (optional)</label>
      <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-danger">Submit</button>
    <a href="/omr-classified-ads/<?= htmlspecialchars(getClassifiedAdsDetailPath($listing_id, $listing['title'])) ?>" class="btn btn-outline-secondary ms-2">Cancel</a>
  </form>
  <?php endif; ?>
</main>

<?php omr_footer(); ?>
</body>
</html>
