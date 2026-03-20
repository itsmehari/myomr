<?php
/**
 * OMR Classified Ads — browse
 */
require_once __DIR__ . '/includes/bootstrap.php';
global $conn;

$filters = [];
$allowed = ['search', 'category_id', 'locality', 'price_min', 'price_max', 'sort'];
foreach ($allowed as $k) {
    if (isset($_GET[$k]) && $_GET[$k] !== '') {
        $val = $_GET[$k];
        if ($k === 'category_id') {
            $val = (int) $val;
        }
        if ($k === 'sort' && !in_array($val, ['newest', 'price_asc', 'price_desc'], true)) {
            continue;
        }
        $filters[$k] = $k === 'category_id' ? $val : sanitizeClassifiedAdsInput($val);
    }
}

$per_page = 9;
$current_page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($current_page - 1) * $per_page;

$total = 0;
$listings = [];
$total_pages = 1;

try {
    $total = getClassifiedAdsCount($filters);
    $listings = getClassifiedAdsListings($filters, $per_page, $offset);
    $total_pages = max(1, (int) ceil($total / $per_page));
} catch (Throwable $e) {
    if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) {
        error_log('omr-classified-ads/index.php: ' . $e->getMessage());
    }
}

require_once ROOT_PATH . '/core/omr-localities-classified-ads.php';
$categories = getClassifiedAdsCategories();
$localities = getClassifiedAdsLocalities();

$page_title = 'OMR Classified Ads | Local notices, services & wanted | MyOMR';
$page_desc = 'Post and browse local classifieds for OMR Chennai — services, wanted ads, community notices. Free listings.';
$canonical_url = $current_page > 1
    ? ('https://myomr.in/omr-classified-ads/?page=' . $current_page)
    : 'https://myomr.in/omr-classified-ads/';
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
<?php
$oauth_ok = $_GET['oauth'] ?? '';
if ($oauth_ok === 'google_ok' || $oauth_ok === 'phone_ok') {
    $m = $oauth_ok === 'google_ok' ? 'Google' : 'Phone';
    echo '<script>if(typeof gtag===\'function\'){gtag(\'event\',\'login\',{method:\'' . htmlspecialchars($m, ENT_QUOTES, 'UTF-8') . '\',event_category:\'classified_ads\'});}</script>';
}
if (isset($_GET['registered'])) {
    echo '<script>if(typeof gtag===\'function\'){gtag(\'event\',\'sign_up\',{method:\'email\',event_category:\'classified_ads\'});}</script>';
}
?>
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
      "url": "<?= htmlspecialchars(getClassifiedAdsDetailUrl((int) $item['id'], $item['title'] ?? null)) ?>",
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
<link rel="stylesheet" href="/omr-classified-ads/assets/classified-ads-omr.css">
<link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="classified-ads-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<section class="ca-hero py-5" style="background:linear-gradient(135deg,#0e7490,#155e75);color:#fff;">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <h1 class="h2 mb-2">OMR Classified Ads <span class="d-block d-md-inline fs-6 fw-normal opacity-90">உள்ளூர் விளம்பரங்கள்</span></h1>
        <p class="mb-4 opacity-90">Have something to offer or need? Post in 2 minutes. Services, wanted ads, and community notices — not full-time jobs or property rent/sale (use our Jobs and Rent hubs).</p>
        <form method="get" action="" class="d-flex flex-wrap gap-2" id="ca-search-form">
          <input type="search" name="search" class="form-control" style="max-width:220px" placeholder="Search…" value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
          <select name="category_id" class="form-select" style="max-width:200px">
            <option value="">All categories</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?= (int) $c['id'] ?>" <?= ($filters['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
          <input type="number" name="price_min" class="form-control" style="max-width:100px" placeholder="Min ₹" value="<?= htmlspecialchars($filters['price_min'] ?? '') ?>">
          <input type="number" name="price_max" class="form-control" style="max-width:100px" placeholder="Max ₹" value="<?= htmlspecialchars($filters['price_max'] ?? '') ?>">
          <select name="sort" class="form-select" style="max-width:150px">
            <option value="newest" <?= ($filters['sort'] ?? 'newest') === 'newest' ? 'selected' : '' ?>>Newest first</option>
            <option value="price_asc" <?= ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price: Low → High</option>
            <option value="price_desc" <?= ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High → Low</option>
          </select>
          <button type="submit" class="btn btn-light"><i class="fas fa-search me-1"></i> Search</button>
        </form>
        <div class="mt-3 d-flex gap-3 flex-wrap align-items-center">
          <strong><?= number_format($total) ?></strong> live ads
          <a href="/omr-classified-ads/post-listing-omr.php" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i> Post ad — இடுகையிடு</a>
          <?php if (ca_user_id() === null): ?>
          <a href="/omr-classified-ads/login-omr.php" class="btn btn-outline-light btn-sm">Log in</a>
          <a href="/omr-classified-ads/register-omr.php" class="btn btn-outline-light btn-sm">Register</a>
          <?php else: ?>
          <span class="small opacity-90"><?= htmlspecialchars(ca_current_user()['display_name'] ?? 'You') ?></span>
          <a href="/omr-classified-ads/account-omr.php" class="btn btn-outline-light btn-sm">Account</a>
          <a href="/omr-classified-ads/logout-omr.php" class="btn btn-outline-light btn-sm">Log out</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container py-3"><?php omr_ad_slot('classified-ads-index-top', '728x90'); ?></div>

<section class="py-4">
  <div class="container">
    <div class="d-flex flex-wrap gap-2 mb-3">
      <a href="/omr-classified-ads/" class="btn btn-outline-secondary btn-sm <?= empty($filters['locality']) ? 'active' : '' ?>">All areas</a>
      <?php foreach ($localities as $loc): ?>
      <a href="?<?= http_build_query(array_merge($filters, ['locality' => $loc, 'page' => 1])) ?>" class="btn btn-outline-secondary btn-sm <?= ($filters['locality'] ?? '') === $loc ? 'active' : '' ?>"><?= htmlspecialchars($loc) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if (empty($listings)): ?>
    <div class="text-center py-5">
      <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
      <h2 class="h4">No listings yet</h2>
      <p class="text-muted">Be the first to post — free for the OMR community.</p>
      <a href="/omr-classified-ads/post-listing-omr.php" class="btn btn-success">Post your ad</a>
    </div>
    <?php else: ?>
    <div class="row g-3">
      <?php foreach ($listings as $item): ?>
      <?php $detailPath = getClassifiedAdsDetailPath((int) $item['id'], $item['title'] ?? null); ?>
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
          <div class="card-img-top bg-light d-flex align-items-center justify-content-center ca-card-placeholder"><i class="fas fa-bullhorn fa-3x text-muted"></i></div>
          <div class="card-body">
            <span class="badge bg-secondary mb-2"><?= htmlspecialchars($item['category_name'] ?? '') ?></span>
            <h3 class="h6 card-title"><a href="/omr-classified-ads/<?= htmlspecialchars($detailPath) ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($item['title']) ?></a></h3>
            <p class="card-text small text-muted mb-1"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($item['locality']) ?></p>
            <?php if (isset($item['price']) && $item['price'] > 0): ?>
            <p class="mb-0"><strong>₹<?= number_format($item['price']) ?></strong></p>
            <?php else: ?>
            <p class="mb-0 text-muted small">Price optional / contact</p>
            <?php endif; ?>
            <a href="/omr-classified-ads/<?= htmlspecialchars($detailPath) ?>" class="btn btn-outline-primary btn-sm mt-2">View</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if ($total_pages > 1): ?>
    <nav class="mt-4" aria-label="Pagination">
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= min($total_pages, 15); $i++): ?>
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
document.getElementById('ca-search-form')?.addEventListener('submit', function() {
  var inp = this.querySelector('input[name=search]');
  if (typeof gtag === 'function') gtag('event', 'search', { event_category: 'classified_ads', search_term: (inp && inp.value ? inp.value : '').slice(0, 100) });
});
</script>
</body>
</html>
