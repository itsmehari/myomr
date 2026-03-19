<?php
/**
 * MyOMR Rent & Lease — Main Listings Page
 * Browse houses, apartments, land for rent/lease in OMR
 */

require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/property-functions.php';

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
}
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';

require_once ROOT_PATH . '/core/omr-connect.php';
global $conn;

$filters = [];
$allowed = ['search', 'listing_type', 'locality'];
foreach ($allowed as $k) {
    if (isset($_GET[$k]) && $_GET[$k] !== '') {
        $filters[$k] = sanitizeInput($_GET[$k]);
    }
}

$per_page = 12;
$current_page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($current_page - 1) * $per_page;

$total = 0;
$properties = [];
$total_pages = 1;

try {
    $total = getRentLeaseCount($filters);
    $properties = getRentLeaseProperties($filters, $per_page, $offset);
    $total_pages = max(1, (int)ceil($total / $per_page));
} catch (Throwable $e) {
    if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) {
        error_log('omr-rent-lease/index.php: ' . $e->getMessage());
    }
}

$page_title = "Rent & Lease Property in OMR Chennai | Houses, Apartments, Land | MyOMR";
$page_desc = "Find houses, apartments, and land for rent or lease in OMR Chennai. Browse verified listings in Perungudi, Sholinganallur, Navalur, Kelambakkam.";
$canonical = "https://myomr.in/omr-rent-lease/";

$localities = ['Perungudi', 'Sholinganallur', 'Thoraipakkam', 'Navalur', 'Kelambakkam', 'Siruseri', 'Karapakkam'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $page_title ?></title>
<meta name="description" content="<?= $page_desc ?>">
<meta name="keywords" content="rent house OMR, rent apartment Chennai, lease land OMR, property for rent OMR Chennai">
<link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:title" content="<?= $page_title ?>">
<meta property="og:description" content="<?= $page_desc ?>">
<meta property="og:url" content="<?= $canonical ?>">
<meta property="og:type" content="website">
<meta property="og:image" content="https://myomr.in/My-OMR-Logo.png">
<meta name="twitter:card" content="summary_large_image">

<?php include ROOT_PATH . '/components/analytics.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="assets/rent-lease-omr.css">
<link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="rent-lease-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<section class="rl-hero py-5" style="background:linear-gradient(135deg,#0d7a42,#0b4d2c);color:#fff;">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <h1 class="h2 mb-2">Rent & Lease Property in OMR</h1>
        <p class="mb-4 opacity-90">Houses, apartments, and land for rent or lease along Old Mahabalipuram Road, Chennai.</p>
        <form method="get" action="" class="d-flex flex-wrap gap-2">
          <input type="search" name="search" class="form-control" style="max-width:240px" placeholder="Search..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
          <select name="listing_type" class="form-select" style="max-width:180px">
            <option value="">All Types</option>
            <option value="rent-house" <?= ($filters['listing_type'] ?? '') === 'rent-house' ? 'selected' : '' ?>>House</option>
            <option value="rent-apartment" <?= ($filters['listing_type'] ?? '') === 'rent-apartment' ? 'selected' : '' ?>>Apartment</option>
            <option value="rent-land" <?= ($filters['listing_type'] ?? '') === 'rent-land' ? 'selected' : '' ?>>Land</option>
          </select>
          <button type="submit" class="btn btn-light"><i class="fas fa-search me-1"></i> Search</button>
        </form>
        <div class="mt-3 d-flex gap-3 flex-wrap">
          <strong><?= number_format($total) ?></strong> Listings
          <a href="add-property-omr.php" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i> List Your Property</a>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container py-3"><?php omr_ad_slot('rent-lease-index-top', '728x90'); ?></div>

<section class="py-4">
  <div class="container">
    <div class="d-flex flex-wrap gap-2 mb-3">
      <a href="/omr-rent-lease/" class="btn btn-outline-secondary btn-sm <?= empty($filters) ? 'active' : '' ?>">All</a>
      <?php foreach ($localities as $loc): ?>
      <a href="?locality=<?= urlencode($loc) ?>" class="btn btn-outline-secondary btn-sm <?= ($filters['locality'] ?? '') === $loc ? 'active' : '' ?>"><?= htmlspecialchars($loc) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if (empty($properties)): ?>
    <div class="text-center py-5">
      <i class="fas fa-home fa-4x text-muted mb-3"></i>
      <h2 class="h4">No properties yet</h2>
      <p class="text-muted">Be the first to list your property for rent or lease in OMR.</p>
      <a href="add-property-omr.php" class="btn btn-success">List Your Property</a>
    </div>
    <?php else: ?>
    <div class="row g-3">
      <?php foreach ($properties as $p): ?>
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <span class="badge bg-success mb-2"><?= htmlspecialchars(ucfirst(str_replace('-',' ',$p['listing_type'] ?? ''))) ?></span>
            <h3 class="h6 card-title"><?= htmlspecialchars($p['title']) ?></h3>
            <p class="card-text small text-muted mb-1"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($p['locality']) ?></p>
            <?php if (!empty($p['monthly_rent'])): ?>
            <p class="mb-0"><strong>₹<?= number_format($p['monthly_rent']) ?>/mo</strong></p>
            <?php endif; ?>
            <a href="property-detail-omr.php?id=<?= (int)$p['id'] ?>" class="btn btn-outline-success btn-sm mt-2">View Details</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if ($total_pages > 1): ?>
    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= min($total_pages, 5); $i++): ?>
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

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
