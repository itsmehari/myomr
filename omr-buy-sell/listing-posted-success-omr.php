<?php
/**
 * OMR Buy-Sell — Listing Posted Success
 */
require_once __DIR__ . '/includes/bootstrap.php';

$page_title = 'Ad Submitted | Buy & Sell OMR | MyOMR';
$canonical_url = 'https://myomr.in/omr-buy-sell/listing-posted-success-omr.php';
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
<?php if (isset($_GET['ga']) && $_GET['ga'] === 'post_listing_submit'): ?>
<script>if (typeof gtag === 'function') gtag('event', 'post_listing_submit', { event_category: 'buy_sell' });</script>
<?php endif; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="/omr-buy-sell/assets/buy-sell-omr.css">
<link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="buy-sell-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<div class="container py-5 text-center">
  <div class="mb-4"><i class="fas fa-check-circle text-success fa-4x"></i></div>
  <h1 class="h2 mb-3">Your ad has been submitted</h1>
  <p class="lead text-muted mb-4">We'll review it and it will go live within 24 hours. You'll be notified once approved.</p>
  <a href="/omr-buy-sell/" class="btn btn-success me-2">Browse Listings</a>
  <a href="post-listing-omr.php" class="btn btn-outline-success">Post Another Ad</a>
</div>
</main>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
