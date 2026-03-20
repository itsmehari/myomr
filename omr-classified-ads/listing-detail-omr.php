<?php
/**
 * OMR Classified Ads — listing detail
 */
require_once __DIR__ . '/includes/bootstrap.php';

$id = max(0, (int) ($_GET['id'] ?? 0));
$viewer_id = ca_user_id();
$listing = $id > 0 ? getClassifiedAdsListingForViewer($id, $viewer_id) : null;

if (!$listing) {
    require_once ROOT_PATH . '/core/serve-404.php';
    exit;
}

$is_owner_pending = !empty($listing['owner_pending']);
if (!$is_owner_pending) {
    incrementClassifiedAdsViewCount($id);
}

$canonical_url = getClassifiedAdsDetailUrl($id, $listing['title'] ?? null);
$request_path = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$qs = $_SERVER['QUERY_STRING'] ?? '';
if (preg_match('#/omr-classified-ads/listing/' . (int) $id . '$#', $request_path)) {
    $slug = createSlugClassifiedAds($listing['title'] ?? '');
    if ($slug !== '') {
        header('Location: ' . $canonical_url . ($qs ? '?' . $qs : ''), true, 301);
        exit;
    }
}

$related = [];
if (($listing['status'] ?? '') === 'approved') {
    $related = getRelatedClassifiedAds($id, (int) $listing['category_id'], $listing['locality'] ?? '', 4);
}

$show_phone_plain = $is_owner_pending;
$page_title = htmlspecialchars($listing['title']) . ' | OMR Classified Ads | MyOMR';
$page_desc = mb_strimwidth(strip_tags($listing['description'] ?? ''), 0, 155, '…');

ca_session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];
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
<meta property="og:image" content="https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg">
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<script>if (typeof gtag === 'function') gtag('event', 'listing_view', { event_category: 'classified_ads', listing_id: <?= (int) $id ?> });</script>
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
        'availability' => 'https://schema.org/InStock',
    ],
];
if (isset($listing['price']) && $listing['price'] > 0) {
    $schema['offers']['price'] = (float) $listing['price'];
}
echo json_encode($schema, JSON_UNESCAPED_SLASHES);
?>
</script>
<?php include __DIR__ . '/includes/head-assets.php'; ?>
</head>
<body class="classified-ads-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<div class="container py-4">
  <nav aria-label="Breadcrumb" class="ca-breadcrumb mb-3">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/omr-classified-ads/">OMR Classified Ads</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars(mb_strimwidth($listing['title'], 0, 50, '…')) ?></li>
    </ol>
  </nav>

  <div class="row g-4">
    <div class="col-lg-8">
      <?php if ($is_owner_pending): ?>
      <div class="alert alert-warning mb-3"><i class="fas fa-hourglass-half me-2"></i>This ad is <strong>pending approval</strong>. Only you can see this page until a moderator approves it.</div>
      <?php elseif (($listing['status'] ?? '') === 'sold'): ?>
      <div class="alert alert-secondary mb-3"><i class="fas fa-check-circle me-2"></i>Marked as closed/sold.</div>
      <?php elseif (($listing['status'] ?? '') === 'expired'): ?>
      <div class="alert alert-warning mb-3"><i class="fas fa-clock me-2"></i>This listing has expired (10-day window).</div>
      <?php endif; ?>

      <div class="d-flex align-items-center justify-content-center mb-4 ca-detail-placeholder" aria-hidden="true"><i class="fas fa-bullhorn fa-4x"></i></div>

      <h1 class="ca-article-title"><?= htmlspecialchars($listing['title']) ?></h1>
      <p class="ca-article-meta mb-2"><i class="fas fa-map-marker-alt me-1" aria-hidden="true"></i><?= htmlspecialchars($listing['locality']) ?><?php if (!empty($listing['category_name'])): ?> · <span class="ca-cat-pill d-inline-block"><?= htmlspecialchars($listing['category_name']) ?></span><?php endif; ?></p>
      <?php if (!empty($listing['poster_name'])): ?>
      <p class="small text-muted mb-3">Posted by <?= htmlspecialchars($listing['poster_name']) ?></p>
      <?php endif; ?>

      <?php if (isset($listing['price']) && $listing['price'] > 0): ?>
      <p class="ca-price-tag mb-3">₹<?= number_format($listing['price']) ?></p>
      <?php else: ?>
      <p class="text-muted mb-3 fw-medium">Price not listed — contact poster</p>
      <?php endif; ?>

      <div class="ca-article-body mb-4"><?= nl2br(htmlspecialchars($listing['description'] ?? '')) ?></div>

      <?php omr_ad_slot('classified-ads-detail-mid', '336x280'); ?>

      <?php if (!empty($related)): ?>
      <h2 class="ca-related-heading">More in this category</h2>
      <div class="row g-3">
        <?php foreach ($related as $r): ?>
        <div class="col-6 col-md-3">
          <div class="card h-100 ca-related-card">
            <div class="card-body p-2">
              <a href="/omr-classified-ads/<?= htmlspecialchars(getClassifiedAdsDetailPath((int) $r['id'], $r['title'])) ?>" class="text-decoration-none small fw-semibold ca-related-card__link"><?= htmlspecialchars(mb_strimwidth($r['title'], 0, 40, '…')) ?></a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <div class="col-lg-4">
      <div class="alert ca-safety-note mb-3 small py-2 px-3">
        <strong>Safety:</strong> Meet in person when possible. Do not pay strangers in advance.
      </div>
      <div class="card ca-contact-panel sticky-top border-0">
        <div class="card-body">
          <h3>Contact</h3>
          <?php if ($show_phone_plain && !empty($listing['contact_phone'])): ?>
          <p class="mb-2"><a href="tel:<?= htmlspecialchars($listing['contact_phone']) ?>" class="btn btn-outline-dark w-100 fw-semibold" style="border-radius:10px"><i class="fas fa-phone me-2" aria-hidden="true"></i>Call</a></p>
          <?php
          $phone_digits = preg_replace('/\D/', '', $listing['contact_phone'] ?? '');
            $wa_msg = rawurlencode("Hi, I'm interested in: " . ($listing['title'] ?? '') . " - " . $canonical_url);
            $wa_href = strlen($phone_digits) >= 10 ? ('https://wa.me/91' . substr($phone_digits, -10) . '?text=' . $wa_msg) : ('https://wa.me/?text=' . $wa_msg);
          ?>
          <p class="mb-2"><a href="<?= htmlspecialchars($wa_href) ?>" class="btn w-100 fw-semibold text-white" style="border-radius:10px;background:#25d366;border:none" target="_blank" rel="noopener"><i class="fab fa-whatsapp me-2" aria-hidden="true"></i>WhatsApp</a></p>
          <?php elseif (($listing['status'] ?? '') === 'approved' && !empty($listing['contact_phone'])): ?>
            <?php if ($viewer_id === null): ?>
            <p class="small text-muted">Log in to reveal the phone number (reduces spam).</p>
            <a href="/omr-classified-ads/login-omr.php?redirect=<?= urlencode($_SERVER['REQUEST_URI'] ?? '') ?>" class="btn w-100 mb-2 fw-semibold text-white ca-btn-post border-0">Log in to reveal</a>
            <?php else: ?>
            <div id="ca-phone-reveal-wrap">
              <button type="button" class="btn w-100 mb-2 fw-semibold text-white ca-btn-post border-0" id="ca-reveal-btn" data-listing-id="<?= (int) $id ?>"><i class="fas fa-eye me-2" aria-hidden="true"></i>Reveal phone</button>
              <div id="ca-phone-result" class="d-none">
                <p class="mb-2"><a href="#" class="btn btn-outline-dark w-100 ca-tel-link fw-semibold" style="border-radius:10px" id="ca-tel-btn"><i class="fas fa-phone me-2" aria-hidden="true"></i><span id="ca-phone-text"></span></a></p>
                <p class="mb-0"><a href="#" class="btn w-100 fw-semibold text-white" style="border-radius:10px;background:#25d366;border:none" id="ca-wa-btn" target="_blank" rel="noopener"><i class="fab fa-whatsapp me-2" aria-hidden="true"></i>WhatsApp</a></p>
              </div>
            </div>
            <?php endif; ?>
          <?php endif; ?>

          <?php if (!empty($listing['contact_email']) && ($show_phone_plain || ($listing['status'] ?? '') === 'approved')): ?>
          <a href="mailto:<?= htmlspecialchars($listing['contact_email']) ?>" class="btn btn-outline-secondary w-100 mb-2 fw-semibold" style="border-radius:10px"><i class="fas fa-envelope me-2" aria-hidden="true"></i>Email</a>
          <?php endif; ?>

          <?php if (($listing['status'] ?? '') === 'approved'): ?>
          <a href="/omr-classified-ads/report-listing-omr.php?listing_id=<?= (int) $id ?>" class="btn btn-link w-100 text-muted small"><i class="fas fa-flag me-1" aria-hidden="true"></i>Report</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
</main>

<?php omr_footer(); ?>
<?php include __DIR__ . '/includes/foot-scripts.php'; ?>
<?php if (($listing['status'] ?? '') === 'approved' && !$show_phone_plain && $viewer_id !== null && !empty($listing['contact_phone'])): ?>
<script>
(function(){
  var btn = document.getElementById('ca-reveal-btn');
  if (!btn) return;
  btn.addEventListener('click', function() {
    var fd = new FormData();
    fd.append('listing_id', btn.getAttribute('data-listing-id'));
    fd.append('csrf_token', <?= json_encode($csrf) ?>);
    fetch('/omr-classified-ads/reveal-phone-omr.php', { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function(r){ return r.json(); })
      .then(function(data){
        if (!data.ok) { alert(data.error || 'Could not reveal'); return; }
        if (typeof gtag === 'function') gtag('event', 'reveal_phone', { event_category: 'classified_ads', listing_id: data.listing_id });
        document.getElementById('ca-reveal-btn').classList.add('d-none');
        var tel = data.phone || '';
        document.getElementById('ca-phone-text').textContent = tel;
        document.getElementById('ca-tel-btn').href = 'tel:' + tel.replace(/\s/g,'');
        var digits = tel.replace(/\D/g,'');
        var msg = encodeURIComponent(<?= json_encode("Hi, I'm interested in: " . ($listing['title'] ?? '') . ' - ' . $canonical_url, JSON_HEX_TAG | JSON_HEX_APOS) ?>);
        var wa = digits.length >= 10 ? ('https://wa.me/91' + digits.slice(-10) + '?text=' + msg) : ('https://wa.me/?text=' + msg);
        document.getElementById('ca-wa-btn').href = wa;
        document.getElementById('ca-phone-result').classList.remove('d-none');
      })
      .catch(function(){ alert('Network error'); });
  });
})();
</script>
<?php endif; ?>
</body>
</html>
