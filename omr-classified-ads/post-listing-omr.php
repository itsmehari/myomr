<?php
/**
 * OMR Classified Ads — post form (login + verified email required)
 */
require_once __DIR__ . '/includes/bootstrap.php';
require_once ROOT_PATH . '/core/omr-localities-classified-ads.php';
ca_require_login();
ca_require_verified_email();

$categories = getClassifiedAdsCategories();
$localities = getClassifiedAdsLocalities();

$page_title = 'Post your ad | OMR Classified Ads | MyOMR';
$canonical_url = 'https://myomr.in/omr-classified-ads/post-listing-omr.php';

ca_session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>
<meta name="robots" content="noindex">
<link rel="canonical" href="<?= htmlspecialchars($canonical_url) ?>">
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<script>if (typeof gtag === 'function') gtag('event', 'post_listing_start', { event_category: 'classified_ads' });</script>
<?php include __DIR__ . '/includes/head-assets.php'; ?>
</head>
<body class="classified-ads-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<div class="container ca-form-shell py-4">
  <h1 class="ca-display mb-2">Post your ad <span class="ca-ta fs-5 fw-normal text-muted d-block d-sm-inline ms-sm-2">இடுகையிடு</span></h1>
  <p class="text-muted mb-4">Your ad will be reviewed before it goes live. No full-time jobs or property rent/sale — use <a href="/omr-local-job-listings/">Jobs</a> and <a href="/omr-rent-lease/">Rent &amp; Lease</a>.</p>

  <?php
    if (!empty($_SESSION['ca_post_errors'])):
        $errs = $_SESSION['ca_post_errors'];
        unset($_SESSION['ca_post_errors']);
  ?>
  <div class="alert alert-danger">
    <strong>Please fix:</strong>
    <ul class="mb-0 mt-1"><?php foreach ($errs as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
  </div>
  <?php endif; ?>

  <?php $pf = $_SESSION['ca_post_prefill'] ?? []; if (!empty($pf)) { unset($_SESSION['ca_post_prefill']); } ?>

  <div class="ca-form-panel">
  <form method="post" action="process-listing-omr.php" class="needs-validation" novalidate>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

    <div class="mb-3">
      <label class="form-label" for="category_id">Category <span class="text-danger">*</span></label>
      <select name="category_id" id="category_id" class="form-select" required>
        <option value="">Select…</option>
        <?php foreach ($categories as $c): ?>
        <option value="<?= (int) $c['id'] ?>" <?= (int) ($pf['category_id'] ?? 0) === (int) $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
      <input type="text" name="title" id="title" class="form-control" required minlength="5" maxlength="255" value="<?= htmlspecialchars($pf['title'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label class="form-label" for="description">Description <span class="text-danger">*</span></label>
      <textarea name="description" id="description" class="form-control" rows="6" required minlength="20"><?= htmlspecialchars($pf['description'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label" for="price">Price (₹) <span class="text-muted">optional</span></label>
      <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" placeholder="Leave blank if not applicable" value="<?= isset($pf['price']) && $pf['price'] !== '' ? htmlspecialchars((string) $pf['price']) : '' ?>">
    </div>

    <div class="mb-3">
      <label class="form-label" for="locality">Location / area <span class="text-danger">*</span></label>
      <select name="locality" id="locality" class="form-select" required>
        <option value="">Select…</option>
        <?php foreach ($localities as $loc): ?>
        <option value="<?= htmlspecialchars($loc) ?>" <?= ($pf['locality'] ?? '') === $loc ? 'selected' : '' ?>><?= htmlspecialchars($loc) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label" for="contact_phone">Your phone (shown after reveal) <span class="text-danger">*</span></label>
      <input type="tel" name="contact_phone" id="contact_phone" class="form-control" required minlength="10" value="<?= htmlspecialchars($pf['contact_phone'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label class="form-label" for="contact_email">Contact email <span class="text-muted">optional</span></label>
      <input type="email" name="contact_email" id="contact_email" class="form-control" value="<?= htmlspecialchars($pf['contact_email'] ?? '') ?>">
    </div>

    <button type="submit" class="btn ca-btn-submit">Submit for review</button>
    <a href="/omr-classified-ads/" class="btn btn-outline-secondary ms-2 rounded-pill fw-semibold">Cancel</a>
  </form>
  </div>
</div>
</main>

<?php omr_footer(); ?>
<?php include __DIR__ . '/includes/foot-scripts.php'; ?>
</body>
</html>
