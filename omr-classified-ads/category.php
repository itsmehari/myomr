<?php
/**
 * OMR Classified Ads — category browse
 */
require_once __DIR__ . '/includes/bootstrap.php';
global $conn;

$slug = sanitizeClassifiedAdsInput($_GET['slug'] ?? '');
$category = null;
$categories = getClassifiedAdsCategories();
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

$filters = ['category_id' => (int) $category['id']];
if (!empty($_GET['locality'])) {
    $filters['locality'] = sanitizeClassifiedAdsInput($_GET['locality']);
}
$per_page = 9;
$current_page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($current_page - 1) * $per_page;

$total = getClassifiedAdsCount($filters);
$listings = getClassifiedAdsListings($filters, $per_page, $offset);
$total_pages = max(1, (int) ceil($total / $per_page));

require_once ROOT_PATH . '/core/omr-localities-classified-ads.php';
$localities = getClassifiedAdsLocalities();

$page_title = htmlspecialchars($category['name']) . ' | OMR Classified Ads | MyOMR';
$page_desc = 'Browse ' . htmlspecialchars(strtolower($category['name'])) . ' classifieds on MyOMR — OMR Chennai.';
$canonical_url = 'https://myomr.in/omr-classified-ads/category/' . rawurlencode($slug) . '/';
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
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<?php include __DIR__ . '/includes/head-assets.php'; ?>
</head>
<body class="classified-ads-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<section class="ca-hero py-4" aria-labelledby="ca-cat-title">
  <div class="container">
    <p class="ca-hero__kicker"><a href="/omr-classified-ads/" class="text-white-50 text-decoration-none">Classified Ads</a> · Category</p>
    <h1 id="ca-cat-title" class="ca-hero__title mb-2"><?= htmlspecialchars($category['name']) ?></h1>
    <p class="ca-hero__lede mb-0"><?= number_format($total) ?> <?= $total === 1 ? 'listing' : 'listings' ?></p>
  </div>
</section>

<section class="py-4">
  <div class="container">
    <div class="ca-chip-row mb-4">
      <a href="/omr-classified-ads/category/<?= htmlspecialchars($slug) ?>/" class="ca-chip <?= empty($filters['locality']) ? 'active' : '' ?>">All areas</a>
      <?php foreach ($localities as $loc): ?>
      <a href="?locality=<?= urlencode($loc) ?>" class="ca-chip <?= ($filters['locality'] ?? '') === $loc ? 'active' : '' ?>"><?= htmlspecialchars($loc) ?></a>
      <?php endforeach; ?>
    </div>
    <?php if (empty($listings)): ?>
    <div class="py-4">
      <div class="ca-empty">
        <div class="ca-empty__icon" aria-hidden="true"><i class="fas fa-folder-open"></i></div>
        <h2>No listings in this category yet</h2>
        <p class="text-muted mb-4">Start the board for <?= htmlspecialchars($category['name']) ?>.</p>
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
</body>
</html>
