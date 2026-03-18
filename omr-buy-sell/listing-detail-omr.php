<?php
/**
 * OMR Buy-Sell — Listing Detail Page
 */
require_once __DIR__ . '/includes/bootstrap.php';

$id = max(0, (int)($_GET['id'] ?? 0));
$listing = $id > 0 ? getBuySellListingById($id) : null;

if (!$listing) {
    require_once ROOT_PATH . '/core/serve-404.php';
    exit;
}

incrementListingViewCount($id);

$canonical_url = getListingDetailUrl($id, $listing['title'] ?? null);
$request_path = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$qs = $_SERVER['QUERY_STRING'] ?? '';
if (preg_match('#/omr-buy-sell/listing/' . (int)$id . '$#', $request_path)) {
    $slug = createSlugBuySell($listing['title'] ?? '');
    if ($slug !== '') {
        header('Location: ' . $canonical_url . ($qs ? '?' . $qs : ''), true, 301);
        exit;
    }
}

$related = getRelatedListings($id, (int)$listing['category_id'], $listing['locality'] ?? '', 4);

$phone = preg_replace('/\D/', '', $listing['contact_phone'] ?? '');
$wa_msg = rawurlencode("Hi, I'm interested in: " . ($listing['title'] ?? '') . " - " . $canonical_url);
$wa_href = $phone ? ("https://wa.me/91" . $phone . "?text=" . $wa_msg) : "https://wa.me/?text=" . $wa_msg;

$page_title = htmlspecialchars($listing['title']) . ' | Buy & Sell OMR | MyOMR';
$page_desc = mb_strimwidth(strip_tags($listing['description'] ?? ''), 0, 155, '…');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $page_title ?></title>
<meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
<link rel="canonical" href="<?= htmlspecialchars($canonical_url) ?>">
<meta property="og:title" content="<?= htmlspecialchars($listing['title']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($page_desc) ?>">
<meta property="og:url" content="<?= htmlspecialchars($canonical_url) ?>">
<meta property="og:type" content="website">
<?php
$imgs_for_og = [];
if (!empty($listing['images'])) {
    $tmp = is_string($listing['images']) ? json_decode($listing['images'], true) : $listing['images'];
    $imgs_for_og = is_array($tmp) ? $tmp : [];
}
$og_image = !empty($imgs_for_og) ? ('https://myomr.in/omr-buy-sell/' . $imgs_for_og[0]) : 'https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg';
?>
<meta property="og:image" content="<?= htmlspecialchars($og_image) ?>">
<meta name="geo.region" content="IN-TN">
<meta name="geo.placename" content="Chennai, OMR">
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<script>if (typeof gtag === 'function') gtag('event', 'listing_view', { event_category: 'buy_sell', listing_id: <?= (int)$id ?>, listing_title: <?= json_encode($listing['title']) ?> });</script>
<script type="application/ld+json">
<?php
$schema = [
  '@context' => 'https://schema.org',
  '@type' => 'Product',
  'name' => $listing['title'],
  'description' => mb_strimwidth(strip_tags($listing['description'] ?? ''), 0, 300),
  'url' => $canonical_url,
  'offers' => [
    '@type' => 'Offer',
    'priceCurrency' => 'INR',
    'availability' => 'https://schema.org/' . (($listing['status'] ?? '') === 'sold' ? 'SoldOut' : 'InStock')
  ],
  'areaServed' => ['@type' => 'Place', 'name' => $listing['locality'] ?? 'OMR Chennai']
];
if (isset($listing['price']) && $listing['price'] > 0) {
    $schema['offers']['price'] = (float)$listing['price'];
}
echo json_encode($schema, JSON_UNESCAPED_SLASHES);
?>
</script>
<script type="application/ld+json">
<?php
$breadcrumb = [
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://myomr.in/'],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Buy & Sell', 'item' => 'https://myomr.in/omr-buy-sell/'],
    ['@type' => 'ListItem', 'position' => 3, 'name' => $listing['title'], 'item' => $canonical_url]
  ]
];
echo json_encode($breadcrumb, JSON_UNESCAPED_SLASHES);
?>
</script>
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
<div class="container py-4">
  <nav aria-label="Breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/omr-buy-sell/">Buy & Sell</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars(mb_strimwidth($listing['title'], 0, 50, '…')) ?></li>
    </ol>
  </nav>

  <div class="row">
    <div class="col-lg-8">
      <?php if (($listing['status'] ?? '') === 'sold'): ?>
      <div class="alert alert-secondary mb-3"><i class="fas fa-check-circle me-2"></i>This item has been sold.</div>
      <?php elseif (($listing['status'] ?? '') === 'expired'): ?>
      <div class="alert alert-warning mb-3"><i class="fas fa-clock me-2"></i>This listing has expired.</div>
      <?php endif; ?>

      <?php
      $imgs = [];
      if (!empty($listing['images'])) {
          $imgs = is_string($listing['images']) ? json_decode($listing['images'], true) : $listing['images'];
          $imgs = is_array($imgs) ? $imgs : [];
      }
      ?>
      <?php if (!empty($imgs)): ?>
      <div id="listingCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner rounded">
          <?php foreach ($imgs as $i => $img): ?>
          <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
            <img src="/omr-buy-sell/<?= htmlspecialchars($img) ?>" class="d-block w-100" alt="Listing image <?= $i + 1 ?>" style="max-height:400px;object-fit:contain;background:#f5f5f5">
          </div>
          <?php endforeach; ?>
        </div>
        <?php if (count($imgs) > 1): ?>
        <button class="carousel-control-prev" type="button" data-bs-target="#listingCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#listingCarousel" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>
        <?php endif; ?>
      </div>
      <?php else: ?>
      <div class="bg-light rounded d-flex align-items-center justify-content-center mb-4" style="height:280px"><i class="fas fa-image fa-4x text-muted"></i></div>
      <?php endif; ?>

      <h1 class="h2 mb-2"><?= htmlspecialchars($listing['title']) ?></h1>
      <p class="text-muted"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($listing['locality']) ?> · <span class="badge bg-secondary"><?= htmlspecialchars($listing['category_name'] ?? '') ?></span></p>

      <?php if (isset($listing['price']) && $listing['price'] > 0): ?>
      <p class="h4 text-success mb-3">₹<?= number_format($listing['price']) ?><?= !empty($listing['price_negotiable']) ? ' <small class="text-muted fw-normal">(negotiable)</small>' : '' ?></p>
      <?php else: ?>
      <p class="h5 text-muted mb-3">Price on request</p>
      <?php endif; ?>

      <div class="mb-4"><?= nl2br(htmlspecialchars($listing['description'] ?? '')) ?></div>

      <?php if (!empty($related)): ?>
      <h2 class="h5 mb-3">Similar Listings</h2>
      <div class="row g-3">
        <?php foreach ($related as $r): ?>
        <div class="col-6 col-md-3">
          <div class="card h-100">
            <?php
            $rimg = null;
            if (!empty($r['images'])) {
                $ra = is_string($r['images']) ? json_decode($r['images'], true) : $r['images'];
                $rimg = is_array($ra) && !empty($ra) ? $ra[0] : null;
            }
            ?>
            <?php if ($rimg): ?>
            <a href="/omr-buy-sell/<?= htmlspecialchars(getListingDetailPath((int)$r['id'], $r['title'])) ?>"><img src="/omr-buy-sell/<?= htmlspecialchars($rimg) ?>" class="card-img-top bs-card-img" alt="<?= htmlspecialchars($r['title']) ?>" loading="lazy"></a>
            <?php endif; ?>
            <div class="card-body p-2">
              <a href="/omr-buy-sell/<?= htmlspecialchars(getListingDetailPath((int)$r['id'], $r['title'])) ?>" class="text-decoration-none text-dark small"><?= htmlspecialchars(mb_strimwidth($r['title'], 0, 40, '…')) ?></a>
              <?php if (isset($r['price']) && $r['price'] > 0): ?><p class="mb-0 small text-success">₹<?= number_format($r['price']) ?></p><?php endif; ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <div class="col-lg-4">
      <div class="alert alert-warning mb-3">
        <strong>Meet in person. Never pay in advance.</strong> Do not send advance payment to sellers.
      </div>
      <div class="card sticky-top">
        <div class="card-body">
          <h3 class="h6">Contact Seller</h3>
          <?php if (!empty($listing['contact_phone'])): ?>
          <a href="tel:<?= htmlspecialchars($listing['contact_phone']) ?>" class="btn btn-outline-primary w-100 mb-2 bs-contact-btn" data-ga-contact="call" data-listing-name="<?= htmlspecialchars($listing['title']) ?>" aria-label="Call seller"><i class="fas fa-phone me-2"></i>Call</a>
          <a href="<?= htmlspecialchars($wa_href) ?>" class="btn btn-success w-100 mb-2 bs-contact-btn" data-ga-contact="whatsapp" data-listing-name="<?= htmlspecialchars($listing['title']) ?>" target="_blank" rel="noopener" aria-label="Contact via WhatsApp"><i class="fab fa-whatsapp me-2"></i>WhatsApp</a>
          <?php endif; ?>
          <?php if (!empty($listing['contact_email'])): ?>
          <a href="mailto:<?= htmlspecialchars($listing['contact_email']) ?>" class="btn btn-outline-secondary w-100 mb-2 bs-contact-btn" data-ga-contact="email" data-listing-name="<?= htmlspecialchars($listing['title']) ?>" aria-label="Email seller"><i class="fas fa-envelope me-2"></i>Email</a>
          <?php endif; ?>
          <a href="report-listing-omr.php?listing_id=<?= (int)$id ?>" class="btn btn-link w-100 text-muted small" aria-label="Report this listing"><i class="fas fa-flag me-1"></i>Report</a>
        </div>
      </div>
    </div>
  </div>
</div>
</main>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('click', function(e) {
  var btn = e.target.closest('[data-ga-contact]');
  if (btn && typeof gtag === 'function') {
    gtag('event', 'contact_click', { event_category: 'buy_sell', method: btn.getAttribute('data-ga-contact'), listing_name: btn.getAttribute('data-listing-name') || '' });
  }
});
</script>
</body>
</html>
