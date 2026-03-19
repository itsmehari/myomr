<?php
/**
 * OMR Buy-Sell — Category Listing
 */
require_once __DIR__ . '/includes/bootstrap.php';
global $conn;

$slug = sanitizeInputBuySell($_GET['slug'] ?? '');
$category = null;
$categories = getBuySellCategories();
foreach ($categories as $c) {
    if ($c['slug'] === $slug) {
        $category = $c;
        break;
    }
}

if (!$category) {
    require_once ROOT_PATH . '/core/serve-404.php';
    exit;
}

$filters = ['category_id' => (int)$category['id']];
if (!empty($_GET['locality'])) {
    $filters['locality'] = sanitizeInputBuySell($_GET['locality']);
}
$per_page = 9;
$current_page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($current_page - 1) * $per_page;

$total = getBuySellCount($filters);
$listings = getBuySellListings($filters, $per_page, $offset);
$total_pages = max(1, (int)ceil($total / $per_page));

require_once ROOT_PATH . '/core/omr-localities-buy-sell.php';
$localities = getBuySellLocalities();

$page_title = htmlspecialchars($category['name']) . ' in OMR | Buy & Sell | MyOMR';
$page_desc = 'Buy and sell ' . htmlspecialchars(strtolower($category['name'])) . ' in OMR Chennai. Local classifieds for Old Mahabalipuram Road.';
$canonical_url = 'https://myomr.in/omr-buy-sell/category/' . $slug . '/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $page_title ?></title>
<meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
<link rel="canonical" href="<?= htmlspecialchars($canonical_url) ?>">
<meta name="geo.region" content="IN-TN">
<meta name="geo.placename" content="Chennai, OMR">
<?php include ROOT_PATH . '/components/analytics.php'; ?>
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
<section class="bs-hero py-4" style="background:linear-gradient(135deg,#0d7a42,#0b4d2c);color:#fff;">
  <div class="container">
    <h1 class="h2 mb-2"><?= htmlspecialchars($category['name']) ?> in OMR</h1>
    <p class="mb-0 opacity-90"><?= number_format($total) ?> listings</p>
  </div>
</section>

<div class="container py-3"><?php omr_ad_slot('buy-sell-index-top', '728x90'); ?></div>

<section class="py-4">
  <div class="container">
    <div class="d-flex flex-wrap gap-2 mb-3">
      <a href="/omr-buy-sell/category/<?= htmlspecialchars($slug) ?>/" class="btn btn-outline-secondary btn-sm <?= empty($filters['locality']) ? 'active' : '' ?>">All</a>
      <?php foreach ($localities as $loc): ?>
      <a href="?locality=<?= urlencode($loc) ?>" class="btn btn-outline-secondary btn-sm <?= ($filters['locality'] ?? '') === $loc ? 'active' : '' ?>"><?= htmlspecialchars($loc) ?></a>
      <?php endforeach; ?>
    </div>
    <?php if (empty($listings)): ?>
    <div class="text-center py-5">
      <h2 class="h4">No listings in this category yet</h2>
      <a href="post-listing-omr.php" class="btn btn-success">Post Your Ad</a>
    </div>
    <?php else: ?>
    <div class="row g-3">
      <?php foreach ($listings as $item): ?>
      <?php $img = null; if (!empty($item['images'])) { $imgs = is_string($item['images']) ? json_decode($item['images'], true) : $item['images']; $img = is_array($imgs) && !empty($imgs) ? $imgs[0] : null; } ?>
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
          <?php if ($img): ?><a href="/omr-buy-sell/<?= htmlspecialchars(getListingDetailPath((int)$item['id'], $item['title'])) ?>"><img src="/omr-buy-sell/<?= htmlspecialchars($img) ?>" class="card-img-top bs-card-img" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy"></a><?php else: ?><div class="card-img-top bg-light d-flex align-items-center justify-content-center bs-card-img"><i class="fas fa-image fa-3x text-muted"></i></div><?php endif; ?>
          <div class="card-body">
            <h3 class="h6 card-title"><a href="/omr-buy-sell/<?= htmlspecialchars(getListingDetailPath((int)$item['id'], $item['title'])) ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($item['title']) ?></a></h3>
            <p class="card-text small text-muted"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($item['locality']) ?></p>
            <?php if (isset($item['price']) && $item['price'] > 0): ?><p class="mb-0"><strong>₹<?= number_format($item['price']) ?></strong></p><?php endif; ?>
            <a href="/omr-buy-sell/<?= htmlspecialchars(getListingDetailPath((int)$item['id'], $item['title'])) ?>" class="btn btn-outline-success btn-sm mt-2">View</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php if ($total_pages > 1): ?><nav class="mt-4"><ul class="pagination justify-content-center"><?php for ($i = 1; $i <= min($total_pages, 10); $i++): ?><li class="page-item <?= $i === $current_page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li><?php endfor; ?></ul></nav><?php endif; ?>
    <?php endif; ?>
  </div>
</section>
</main>
<?php include ROOT_PATH . '/components/omr-topic-hubs.php'; ?>
<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
