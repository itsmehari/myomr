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
<?php include __DIR__ . '/includes/head-assets.php'; ?>
</head>
<body class="classified-ads-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<section class="ca-hero py-5" aria-labelledby="ca-hub-title">
  <div class="container">
    <div class="row align-items-start">
      <div class="col-lg-10 col-xl-9">
        <p class="ca-hero__kicker">MyOMR · Chennai OMR</p>
        <h1 id="ca-hub-title" class="ca-hero__title">OMR Classified Ads <span class="ca-ta d-block d-md-inline fs-5 fw-normal mt-1 mt-md-0 ms-md-2">உள்ளூர் விளம்பரங்கள்</span></h1>
        <p class="ca-hero__lede">Services, wanted posts, and neighbourhood notices — posted in minutes. For jobs and property, use our <a href="/omr-local-job-listings/" class="text-white text-decoration-underline">Jobs</a> and <a href="/omr-rent-lease/" class="text-white text-decoration-underline">Rent &amp; Lease</a> hubs.</p>
        <form method="get" action="" class="ca-filter-bar" id="ca-search-form" role="search" aria-label="Search classified ads">
          <input type="search" name="search" class="form-control" style="max-width:220px;flex:1 1 160px" placeholder="Search…" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" aria-label="Keywords">
          <select name="category_id" class="form-select" style="max-width:200px" aria-label="Category">
            <option value="">All categories</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?= (int) $c['id'] ?>" <?= ($filters['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
          <input type="number" name="price_min" class="form-control" style="max-width:100px" inputmode="numeric" placeholder="Min ₹" value="<?= htmlspecialchars($filters['price_min'] ?? '') ?>" aria-label="Minimum price">
          <input type="number" name="price_max" class="form-control" style="max-width:100px" inputmode="numeric" placeholder="Max ₹" value="<?= htmlspecialchars($filters['price_max'] ?? '') ?>" aria-label="Maximum price">
          <select name="sort" class="form-select" style="max-width:160px" aria-label="Sort order">
            <option value="newest" <?= ($filters['sort'] ?? 'newest') === 'newest' ? 'selected' : '' ?>>Newest first</option>
            <option value="price_asc" <?= ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price: Low → High</option>
            <option value="price_desc" <?= ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High → Low</option>
          </select>
          <button type="submit" class="btn ca-btn-search"><i class="fas fa-search me-1" aria-hidden="true"></i> Search</button>
        </form>
        <div class="ca-hero-actions">
          <span class="ca-stat"><strong><?= number_format($total) ?></strong> live <?= $total === 1 ? 'ad' : 'ads' ?></span>
          <a href="/omr-classified-ads/post-listing-omr.php" class="btn btn-sm ca-btn-post"><i class="fas fa-plus me-1" aria-hidden="true"></i> Post ad — இடுகையிடு</a>
          <?php if (ca_user_id() === null): ?>
          <a href="/omr-classified-ads/login-omr.php" class="btn btn-sm ca-btn-ghost-light">Log in</a>
          <a href="/omr-classified-ads/register-omr.php" class="btn btn-sm ca-btn-ghost-light">Register</a>
          <?php else: ?>
          <span class="small text-white-50"><?= htmlspecialchars(ca_current_user()['display_name'] ?? 'You') ?></span>
          <a href="/omr-classified-ads/account-omr.php" class="btn btn-sm ca-btn-ghost-light">Account</a>
          <a href="/omr-classified-ads/logout-omr.php" class="btn btn-sm ca-btn-ghost-light">Log out</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container ca-ad-wrap"><?php omr_ad_slot('classified-ads-index-top', '728x90'); ?></div>

<section class="py-4">
  <div class="container">
    <div class="ca-chip-row mb-4">
      <a href="/omr-classified-ads/" class="ca-chip <?= empty($filters['locality']) ? 'active' : '' ?>">All areas</a>
      <?php foreach ($localities as $loc): ?>
      <a href="?<?= http_build_query(array_merge($filters, ['locality' => $loc, 'page' => 1])) ?>" class="ca-chip <?= ($filters['locality'] ?? '') === $loc ? 'active' : '' ?>"><?= htmlspecialchars($loc) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if (empty($listings)): ?>
    <div class="py-4">
      <div class="ca-empty">
        <div class="ca-empty__icon" aria-hidden="true"><i class="fas fa-newspaper"></i></div>
        <h2>No listings yet</h2>
        <p class="text-muted mb-4">Be the first to post — free for the OMR community.</p>
        <a href="/omr-classified-ads/post-listing-omr.php" class="btn ca-btn-post">Post your ad</a>
      </div>
    </div>
    <?php else: ?>
    <div class="row g-4">
      <?php foreach ($listings as $item): ?>
      <?php $detailPath = getClassifiedAdsDetailPath((int) $item['id'], $item['title'] ?? null); ?>
      <div class="col-md-6 col-lg-4">
        <article class="ca-listing-card">
          <div class="ca-listing-card__media" aria-hidden="true"><i class="fas fa-bullhorn"></i></div>
          <div class="ca-listing-card__body">
            <?php if (!empty($item['category_name'])): ?><span class="ca-cat-pill"><?= htmlspecialchars($item['category_name']) ?></span><?php endif; ?>
            <h3 class="ca-listing-card__title"><a href="/omr-classified-ads/<?= htmlspecialchars($detailPath) ?>"><?= htmlspecialchars($item['title']) ?></a></h3>
            <p class="ca-listing-card__meta mb-0"><i class="fas fa-map-marker-alt me-1" aria-hidden="true"></i><?= htmlspecialchars($item['locality']) ?></p>
            <?php if (isset($item['price']) && $item['price'] > 0): ?>
            <p class="ca-listing-card__price mb-0">₹<?= number_format($item['price']) ?></p>
            <?php else: ?>
            <p class="ca-listing-card__price ca-listing-card__price--muted mb-0">Contact for details</p>
            <?php endif; ?>
            <a href="/omr-classified-ads/<?= htmlspecialchars($detailPath) ?>" class="ca-card-cta">View listing</a>
          </div>
        </article>
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
<?php include __DIR__ . '/includes/foot-scripts.php'; ?>
<script>
document.getElementById('ca-search-form')?.addEventListener('submit', function() {
  var inp = this.querySelector('input[name=search]');
  if (typeof gtag === 'function') gtag('event', 'search', { event_category: 'classified_ads', search_term: (inp && inp.value ? inp.value : '').slice(0, 100) });
});
</script>
</body>
</html>
