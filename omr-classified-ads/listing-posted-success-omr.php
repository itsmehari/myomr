<?php
/**
 * OMR Classified Ads — thank you after post
 */
require_once __DIR__ . '/includes/bootstrap.php';
ca_require_login();

$id = max(0, (int) ($_GET['id'] ?? 0));
$viewer = ca_user_id();
$listing = null;
if ($id > 0 && $viewer !== null) {
    $listing = getClassifiedAdsListingForViewer($id, $viewer);
}

$page_title = 'Ad submitted | OMR Classified Ads | MyOMR';
$canonical_url = 'https://myomr.in/omr-classified-ads/listing-posted-success-omr.php';
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
<script>if (typeof gtag === 'function') gtag('event', 'post_listing_submit', { event_category: 'classified_ads' });</script>
<?php include __DIR__ . '/includes/head-assets.php'; ?>
</head>
<body class="classified-ads-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<div class="container ca-auth-wrap py-5 text-center">
  <div class="ca-auth-card py-4 px-3">
    <div class="ca-success-icon" aria-hidden="true"><i class="fas fa-check-circle"></i></div>
    <h1 class="ca-display mb-3 fs-3">Thanks — your ad was submitted</h1>
    <p class="text-muted mb-4 px-sm-2">It is <strong>pending approval</strong>. We usually review within a short time. You can open your listing below (only visible to you until approved).</p>
    <?php if ($listing): ?>
    <a href="/omr-classified-ads/<?= htmlspecialchars(getClassifiedAdsDetailPath($id, $listing['title'] ?? null)) ?>" class="btn ca-btn-post btn-lg px-4">View your listing</a>
    <?php endif; ?>
    <p class="mt-4 mb-0"><a href="/omr-classified-ads/" class="text-muted">Back to browse</a></p>
  </div>
</div>
</main>

<?php omr_footer(); ?>
<?php include __DIR__ . '/includes/foot-scripts.php'; ?>
</body>
</html>
