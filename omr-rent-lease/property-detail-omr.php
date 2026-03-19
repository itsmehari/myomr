<?php
/**
 * MyOMR Rent & Lease — Property Detail
 */

require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/property-functions.php';

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
}
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';

$id = max(0, (int)($_GET['id'] ?? 0));
$property = $id > 0 ? getRentLeasePropertyById($id) : null;

if (!$property) {
    require_once ROOT_PATH . '/core/serve-404.php';
    exit;
}

$page_title = htmlspecialchars($property['title']) . ' | Rent & Lease OMR | MyOMR';
$canonical = 'https://myomr.in/omr-rent-lease/property-detail-omr.php?id=' . $id;
$wa_msg = rawurlencode("Hi, I'm interested in: " . $property['title'] . " - " . $canonical);
$wa_href = "https://wa.me/" . preg_replace('/\D/', '', $property['contact_phone'] ?? $property['owner_phone'] ?? '') . "?text=" . $wa_msg;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $page_title ?></title>
<meta name="description" content="<?= htmlspecialchars(mb_strimwidth(strip_tags($property['property_details'] ?? ''), 0, 155, '…')) ?>">
<link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="assets/rent-lease-omr.css">
<link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="rent-lease-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<div class="container py-4">
  <nav aria-label="Breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/omr-rent-lease/">Rent & Lease</a></li>
      <li class="breadcrumb-item active"><?= htmlspecialchars(mb_strimwidth($property['title'], 0, 40, '…')) ?></li>
    </ol>
  </nav>

  <div class="row">
    <div class="col-lg-8">
      <h1 class="h2 mb-2"><?= htmlspecialchars($property['title']) ?></h1>
      <p class="text-muted"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($property['locality']) ?></p>
      <?php if (!empty($property['monthly_rent'])): ?>
      <p class="h4 text-success mb-3">₹<?= number_format($property['monthly_rent']) ?>/month</p>
      <?php endif; ?>
      <div class="mb-4"><?= nl2br(htmlspecialchars($property['property_details'] ?? '')) ?></div>
      <?php omr_ad_slot('rent-lease-detail-mid', '336x280'); ?>
      <?php if (!empty($property['amenities'])): ?>
      <h3 class="h6">Amenities</h3>
      <p><?= nl2br(htmlspecialchars($property['amenities'])) ?></p>
      <?php endif; ?>
    </div>
    <div class="col-lg-4">
      <div class="card">
        <div class="card-body">
          <h3 class="h6">Contact</h3>
          <p class="mb-1"><?= htmlspecialchars($property['owner_name'] ?? 'Owner') ?></p>
          <?php if (!empty($property['contact_phone']) || !empty($property['owner_phone'])): ?>
          <a href="<?= htmlspecialchars($wa_href) ?>" class="btn btn-success w-100" target="_blank"><i class="fab fa-whatsapp me-2"></i>WhatsApp</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
</main>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
