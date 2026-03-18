<?php
/**
 * OMR Buy-Sell — Guidelines & Prohibited Items
 */
require_once __DIR__ . '/includes/bootstrap.php';

$page_title = 'Guidelines & Safety | Buy & Sell OMR | MyOMR';
$canonical_url = 'https://myomr.in/omr-buy-sell/guidelines.php';
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="/omr-buy-sell/assets/buy-sell-omr.css">
<link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="buy-sell-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<div class="container py-4">
  <h1 class="h2 mb-4">Guidelines & Safety</h1>

  <div class="alert alert-warning mb-4">
    <h2 class="h5"><i class="fas fa-exclamation-triangle me-2"></i>Meet in person. Never pay in advance.</h2>
    <p class="mb-0">Always meet the buyer or seller in person to complete the transaction. Do not send advance payment or share bank details before meeting.</p>
  </div>

  <section class="mb-4">
    <h2 class="h5">Prohibited Items</h2>
    <p>The following items are not allowed on OMR Buy & Sell:</p>
    <ul>
      <li>Weapons, ammunition, or explosives</li>
      <li>Illegal or stolen items</li>
      <li>Counterfeit goods</li>
      <li>Drugs or controlled substances</li>
      <li>Live animals (except as per applicable policy)</li>
      <li>Hazardous materials</li>
    </ul>
  </section>

  <section class="mb-4">
    <h2 class="h5">Posting Rules</h2>
    <ul>
      <li>Provide accurate descriptions and real photos of the item</li>
      <li>Use clear, appropriate language</li>
      <li>One listing per item</li>
      <li>Listings are subject to moderation before going live</li>
    </ul>
  </section>

  <section class="mb-4">
    <h2 class="h5">Contact Safety Tips</h2>
    <ul>
      <li>Meet in a safe, public place</li>
      <li>Inspect the item before paying</li>
      <li>Be cautious of deals that seem too good to be true</li>
      <li>Report suspicious listings using the Report button</li>
    </ul>
  </section>

  <p><a href="/omr-buy-sell/" class="btn btn-outline-secondary">Back to Buy & Sell</a> <a href="post-listing-omr.php" class="btn btn-success ms-2">Post Your Ad</a></p>
</div>
</main>

<?php omr_footer(); ?>
</body>
</html>
