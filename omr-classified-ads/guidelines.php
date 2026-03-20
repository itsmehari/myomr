<?php
require_once __DIR__ . '/includes/bootstrap.php';

$page_title = 'OMR Classified Ads — Guidelines | MyOMR';
$page_desc = 'What you can post on OMR Classified Ads: services, wanted ads, community notices. Banned: full-time jobs and property rent/sale.';
$canonical_url = 'https://myomr.in/omr-classified-ads/guidelines.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>
<meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
<link rel="canonical" href="<?= htmlspecialchars($canonical_url) ?>">
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="classified-ads-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content" class="container py-4">
  <h1 class="h2 mb-3">OMR Classified Ads — வழிகாட்டி / Guidelines</h1>
  <div class="row">
    <div class="col-lg-8">
      <h2 class="h5">What belongs here</h2>
      <ul>
        <li>Local <strong>services</strong> (tuition, repairs, moving help, event help, pet care, cleaning, etc.)</li>
        <li><strong>Wanted</strong> ads (looking to buy items)</li>
        <li><strong>Community</strong> notices: lost &amp; found, giveaways, alerts, recommendations</li>
      </ul>
      <h2 class="h5 mt-4">Not allowed (use other hubs)</h2>
      <ul>
        <li><strong>Full-time / formal job postings</strong> → <a href="/omr-local-job-listings/">Jobs on MyOMR</a></li>
        <li><strong>Property rent, lease, or sale</strong> → <a href="/omr-rent-lease/">Rent &amp; Lease</a></li>
        <li>Illegal items, scams, hate, adult content, MLM/crypto schemes</li>
      </ul>
      <h2 class="h5 mt-4">Listing life &amp; moderation</h2>
      <p>Ads are <strong>reviewed before publishing</strong>. Approved listings stay live for <strong>10 days</strong>, then expire unless renewed.</p>
      <h2 class="h5 mt-4">Contact &amp; safety</h2>
      <p>Phone numbers are shown after <strong>Reveal</strong> (logged-in users) to reduce spam. Meet safely; never pay strangers in advance.</p>
      <p class="mt-4"><a href="/omr-classified-ads/" class="btn btn-primary">Browse ads</a>
      <a href="/omr-classified-ads/post-listing-omr.php" class="btn btn-outline-primary ms-2">Post an ad</a></p>
    </div>
  </div>
</main>

<?php omr_footer(); ?>
</body>
</html>
