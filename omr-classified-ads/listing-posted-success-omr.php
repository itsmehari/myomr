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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="classified-ads-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<div class="container py-5 text-center">
  <div class="mb-4 text-success"><i class="fas fa-check-circle fa-4x"></i></div>
  <h1 class="h2 mb-3">Thanks — your ad was submitted</h1>
  <p class="text-muted mb-4">It is <strong>pending approval</strong>. We usually review within a short time. You can open your listing below (only visible to you until approved).</p>
  <?php if ($listing): ?>
  <a href="/omr-classified-ads/<?= htmlspecialchars(getClassifiedAdsDetailPath($id, $listing['title'] ?? null)) ?>" class="btn btn-primary btn-lg">View your listing</a>
  <?php endif; ?>
  <p class="mt-4"><a href="/omr-classified-ads/">Back to browse</a></p>
</div>
</main>

<?php omr_footer(); ?>
</body>
</html>
