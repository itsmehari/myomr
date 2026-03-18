<?php
/**
 * OMR Buy-Sell — Main Browse Page
 */
require_once __DIR__ . '/includes/bootstrap.php';
global $conn;

$filters = [];
$allowed = ['search', 'category_id', 'locality', 'price_min', 'price_max', 'sort'];
foreach ($allowed as $k) {
    if (isset($_GET[$k]) && $_GET[$k] !== '') {
        $val = $_GET[$k];
        if (in_array($k, ['category_id'])) $val = (int)$val;
    if ($k === 'sort' && !in_array($val, ['newest', 'price_asc', 'price_desc'], true)) continue;
        $filters[$k] = $k === 'category_id' ? $val : sanitizeInputBuySell($val);
    }
}

$per_page = 9;
$current_page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($current_page - 1) * $per_page;

$total = 0;
$listings = [];
$total_pages = 1;

try {
    $total = getBuySellCount($filters);
    $listings = getBuySellListings($filters, $per_page, $offset);
    $total_pages = max(1, (int)ceil($total / $per_page));
} catch (Throwable $e) {
    if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) {
        error_log('omr-buy-sell/index.php: ' . $e->getMessage());
    }
}

require_once ROOT_PATH . '/core/omr-localities-buy-sell.php';
$categories = getBuySellCategories();
$localities = getBuySellLocalities();

$page_title = 'Buy & Sell in OMR Chennai | Used Items, Electronics, Furniture | MyOMR';
$page_desc = 'Buy and sell used items in OMR Chennai. Electronics, furniture, vehicles, books and more. Local C2C classifieds for Old Mahabalipuram Road.';
$canonical_url = $current_page > 1 ? ('https://myomr.in/omr-buy-sell/?page=' . $current_page) : 'https://myomr.in/omr-buy-sell/';
$page_nav = 'main';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>
<meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
<link rel="canonical" href="<?= htmlspecialchars($canonical_url) ?>">
<meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
<meta property="og:description" content="<?= htmlspecialchars($page_desc) ?>">
<meta property="og:url" content="<?= htmlspecialchars($canonical_url) ?>">
<meta property="og:type" content="website">
<meta name="geo.region" content="IN-TN">
<meta name="geo.placename" content="Chennai, OMR">
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<?php if (!empty($listings)): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "itemListElement": [
    <?php foreach (array_slice($listings, 0, 10) as $i => $item): ?>
    {
      "@type": "ListItem",
      "position": <?= $i + 1 ?>,
      "url": "<?= htmlspecialchars(getListingDetailUrl((int)$item['id'], $item['title'] ?? null)) ?>",
      "name": <?= json_encode($item['title']) ?>
    }<?= $i < min(count($listings), 10) - 1 ? ',' : '' ?>
    <?php endforeach; ?>
  ]
}
</script>
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
<section class="bs-hero py-5" style="background:linear-gradient(135deg,#0d7a42,#0b4d2c);color:#fff;">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <h1 class="h2 mb-2">Buy & Sell in OMR</h1>
        <p class="mb-4 opacity-90">Local classifieds for Old Mahabalipuram Road. Electronics, furniture, vehicles, books and more.</p>
        <form method="get" action="" class="d-flex flex-wrap gap-2" id="bs-search-form">
          <input type="search" name="search" class="form-control" style="max-width:220px" placeholder="Search..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
          <select name="category_id" class="form-select" style="max-width:160px">
            <option value="">All Categories</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= ($filters['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
          <input type="number" name="price_min" class="form-control" style="max-width:100px" placeholder="Min ₹" value="<?= htmlspecialchars($filters['price_min'] ?? '') ?>">
          <input type="number" name="price_max" class="form-control" style="max-width:100px" placeholder="Max ₹" value="<?= htmlspecialchars($filters['price_max'] ?? '') ?>">
          <select name="sort" class="form-select" style="max-width:140px">
            <option value="newest" <?= ($filters['sort'] ?? 'newest') === 'newest' ? 'selected' : '' ?>>Newest first</option>
            <option value="price_asc" <?= ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
            <option value="price_desc" <?= ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
          </select>
          <button type="submit" class="btn btn-light"><i class="fas fa-search me-1"></i> Search</button>
        </form>
        <div class="mt-3 d-flex gap-3 flex-wrap align-items-center">
          <strong><?= number_format($total) ?></strong> Listings
          <a href="post-listing-omr.php" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i> Post Ad</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-4">
  <div class="container">
    <div class="d-flex flex-wrap gap-2 mb-3">
      <a href="/omr-buy-sell/" class="btn btn-outline-secondary btn-sm <?= empty($filters) ? 'active' : '' ?>">All</a>
      <?php foreach ($localities as $loc): ?>
      <a href="?<?= http_build_query(array_merge($filters, ['locality' => $loc, 'page' => 1])) ?>" class="btn btn-outline-secondary btn-sm <?= ($filters['locality'] ?? '') === $loc ? 'active' : '' ?>"><?= htmlspecialchars($loc) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if (empty($listings)): ?>
    <div class="text-center py-5">
      <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
      <h2 class="h4">No listings yet</h2>
      <p class="text-muted">Be the first to post an ad. Buy and sell locally in OMR.</p>
      <a href="post-listing-omr.php" class="btn btn-success">Post Your Ad</a>
    </div>
    <?php else: ?>
    <div class="row g-3">
      <?php foreach ($listings as $item): ?>
      <?php
      $img = null;
      if (!empty($item['images'])) {
          $imgs = is_string($item['images']) ? json_decode($item['images'], true) : $item['images'];
          $img = is_array($imgs) && !empty($imgs) ? $imgs[0] : null;
      }
      $detailPath = getListingDetailPath((int)$item['id'], $item['title'] ?? null);
      ?>
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
          <?php if ($img): ?>
          <a href="/omr-buy-sell/<?= htmlspecialchars($detailPath) ?>"><img src="/omr-buy-sell/<?= htmlspecialchars($img) ?>" class="card-img-top bs-card-img" alt="Listing: <?= htmlspecialchars($item['title']) ?>" loading="lazy"></a>
          <?php else: ?>
          <div class="card-img-top bg-light d-flex align-items-center justify-content-center bs-card-img"><i class="fas fa-image fa-3x text-muted"></i></div>
          <?php endif; ?>
          <div class="card-body">
            <span class="badge bg-secondary mb-2"><?= htmlspecialchars($item['category_name'] ?? 'Other') ?></span>
            <h3 class="h6 card-title"><a href="/omr-buy-sell/<?= htmlspecialchars($detailPath) ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($item['title']) ?></a></h3>
            <p class="card-text small text-muted mb-1"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($item['locality']) ?></p>
            <?php if (isset($item['price']) && $item['price'] > 0): ?>
            <p class="mb-0"><strong>₹<?= number_format($item['price']) ?></strong><?= !empty($item['price_negotiable']) ? ' (negotiable)' : '' ?></p>
            <?php else: ?>
            <p class="mb-0 text-muted">Price on request</p>
            <?php endif; ?>
            <a href="/omr-buy-sell/<?= htmlspecialchars($detailPath) ?>" class="btn btn-outline-success btn-sm mt-2">View Details</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if ($total_pages > 1): ?>
    <nav class="mt-4" aria-label="Pagination">
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= min($total_pages, 10); $i++): ?>
        <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
          <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
      </ul>
    </nav>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
</main>

<?php include ROOT_PATH . '/components/omr-topic-hubs.php'; ?>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('bs-search-form')?.addEventListener('submit', function() {
  var inp = this.querySelector('input[name=search]');
  if (typeof gtag === 'function') gtag('event', 'search', { event_category: 'buy_sell', search_term: (inp && inp.value ? inp.value : '').slice(0, 100) });
});
</script>
</body>
</html>
