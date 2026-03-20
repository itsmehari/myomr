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
<?php include __DIR__ . '/includes/head-assets.php'; ?>
</head>
<body class="classified-ads-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content" class="container ca-form-shell py-4">
  <?php if ($submitted): ?>
  <div class="ca-auth-card text-center py-4">
    <div class="ca-success-icon mx-auto mb-3" style="width:3.5rem;height:3.5rem;font-size:1.5rem"><i class="fas fa-check" aria-hidden="true"></i></div>
    <p class="alert alert-success border-0 mb-3">Thank you. Your report will be reviewed.</p>
    <a href="/omr-classified-ads/<?= htmlspecialchars(getClassifiedAdsDetailPath($listing_id, $listing['title'])) ?>" class="btn btn-outline-secondary rounded-pill fw-semibold">Back to listing</a>
  </div>
  <?php else: ?>
  <div class="ca-form-panel">
  <p class="ca-auth-kicker">Moderation</p>
  <h1 class="ca-display mb-3 fs-3">Report listing</h1>
  <p class="mb-3 text-muted">Reporting: <strong class="text-dark"><?= htmlspecialchars($listing['title']) ?></strong></p>
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
    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold">Submit report</button>
    <a href="/omr-classified-ads/<?= htmlspecialchars(getClassifiedAdsDetailPath($listing_id, $listing['title'])) ?>" class="btn btn-outline-secondary ms-2 rounded-pill fw-semibold">Cancel</a>
  </form>
  </div>
  <?php endif; ?>
</main>

<?php omr_footer(); ?>
<?php include __DIR__ . '/includes/foot-scripts.php'; ?>
</body>
</html>
