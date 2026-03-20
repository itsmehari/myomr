<?php
/**
 * OMR Classified Ads — locality browse
 */
require_once __DIR__ . '/includes/bootstrap.php';
global $conn;

$slug = sanitizeClassifiedAdsInput($_GET['slug'] ?? '');
require_once ROOT_PATH . '/core/omr-localities-classified-ads.php';
$locality = classifiedAdsSlugToLocality($slug);

if ($locality === null) {
    require_once ROOT_PATH . '/core/serve-404.php';
    exit;
}

$filters = ['locality' => $locality];
$per_page = 9;
$current_page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($current_page - 1) * $per_page;

$total = getClassifiedAdsCount($filters);
$listings = getClassifiedAdsListings($filters, $per_page, $offset);
$total_pages = max(1, (int) ceil($total / $per_page));

$categories = getClassifiedAdsCategories();

$page_title = htmlspecialchars($locality) . ' classifieds | OMR Classified Ads | MyOMR';
$page_desc = 'Local classifieds for ' . htmlspecialchars($locality) . ' — MyOMR OMR Chennai.';
$canonical_url = 'https://myomr.in/omr-classified-ads/locality/' . rawurlencode($slug) . '/';
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="/omr-classified-ads/assets/classified-ads-omr.css">
<link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="classified-ads-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<section class="ca-hero py-4" style="background:linear-gradient(135deg,#0e7490,#155e75);color:#fff;">
  <div class="container">
    <h1 class="h2 mb-2"><?= htmlspecialchars($locality) ?></h1>
    <p class="mb-0 opacity-90"><?= number_format($total) ?> listings</p>
  </div>
</section>

<section class="py-4">
  <div class="container">
    <?php if (empty($listings)): ?>
    <div class="text-center py-5">
      <h2 class="h4">No listings for this area yet</h2>
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
            <?php if (isset($item['price']) && $item['price'] > 0): ?><p class="mb-0"><strong>₹<?= number_format($item['price']) ?></strong></p><?php endif; ?>
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
          <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
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
</body>
</html>
