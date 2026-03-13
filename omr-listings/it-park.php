<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../core/omr-connect.php';
require_once __DIR__ . '/data/it-parks-data.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$id = 0;
if (preg_match('/-(\d+)$/', $slug, $m)) { $id = (int)$m[1]; }

// Try DB first
$park = null;
if ($id > 0) {
  $chk = $conn->query('SHOW TABLES LIKE "omr_it_parks"');
  if ($chk && $chk->num_rows > 0) {
    $stmt = $conn->prepare('SELECT id, name, locality, address, phone, website, inauguration_year, owner, built_up_area, total_area, image, amenity_sez, amenity_parking, amenity_cafeteria, amenity_shuttle FROM omr_it_parks WHERE id=? LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $park = $res ? $res->fetch_assoc() : null;
    $stmt->close();
  }
}
if (!$park) { $park = $id ? omr_it_parks_get_by_id($id) : null; }

if (!$park) {
  require_once __DIR__ . '/../core/serve-404.php';
  exit;
}
?>
<?php
$_canonical = 'https://myomr.in/it-parks/' . $slug;
$canonical_url       = $_canonical;
$page_title          = $park ? htmlspecialchars($park['name'], ENT_QUOTES, 'UTF-8') . ' | IT Park on OMR | MyOMR' : 'IT Park Not Found | MyOMR';
$page_description    = $park ? htmlspecialchars('Explore ' . $park['name'] . ' IT Park on OMR, Chennai - location, map, key tenants and details.', ENT_QUOTES, 'UTF-8') : 'IT Park not found on OMR, Chennai.';
$og_title            = $page_title;
$og_description      = $page_description;
$og_url              = $_canonical;
$og_image            = !empty($park['image']) ? (strpos($park['image'], 'http') === 0 ? $park['image'] : 'https://myomr.in' . $park['image']) : 'https://myomr.in/My-OMR-Logo.png';
?>
<?php include 'weblog/log.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include '../components/meta.php'; ?>
<?php $ga_custom_params = ['listing_type' => 'it_park', 'locality' => $park['locality'] ?? '']; include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>




<style>
body { font-family: 'Poppins', sans-serif; }
.maxw-1280 { max-width: 1280px; }
.section-title { color:#0583D2; }
.muted-note { color:#6c757d; }
</style>

<?php if ($park): ?>
<script type="application/ld+json">
<?php
echo json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'Place',
  'name' => $park['name'],
  'address' => [
    '@type' => 'PostalAddress',
    'streetAddress' => $park['address'] ?? '',
    'addressLocality' => ($park['locality'] ?? $park['location'] ?? 'OMR, Chennai'),
    'addressRegion' => 'TN',
    'addressCountry' => 'IN'
  ],
  'geo' => (!empty($park['lat']) && !empty($park['lng'])) ? [
    '@type' => 'GeoCoordinates',
    'latitude' => (float)$park['lat'],
    'longitude' => (float)$park['lng']
  ] : null,
  'url' => 'https://myomr.in/it-parks/'.($slug),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
</script>
<?php endif; ?>

</head>
<body>
<?php include '../components/skip-link.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/omr-listings-nav.php'; ?>

<div class="container maxw-1280 py-3">
  <?php if (!$park): ?>
    <h1 class="section-title">IT Park not found</h1>
    <p><a href="/it-parks">Back to IT Parks</a></p>
  <?php else: ?>
    <h1 class="section-title"><?php echo htmlspecialchars($park['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
    <?php if (!empty($park['image'])): ?>
      <div class="mb-3">
        <img src="<?php echo htmlspecialchars($park['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($park['name'], ENT_QUOTES, 'UTF-8'); ?>" style="max-width:100%;height:auto;border-radius:6px;" loading="lazy" decoding="async">
      </div>
    <?php endif; ?>
    <?php if (!empty($park['address'])): ?><p class="muted-note mb-2"><?php echo htmlspecialchars($park['address'], ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
    <?php if (!empty($park['amenity_sez']) || !empty($park['amenity_parking']) || !empty($park['amenity_cafeteria']) || !empty($park['amenity_shuttle'])): ?>
      <p>
        <?php if (!empty($park['amenity_sez'])): ?><span class="badge badge-info mr-1">SEZ</span><?php endif; ?>
        <?php if (!empty($park['amenity_parking'])): ?><span class="badge badge-info mr-1">Parking</span><?php endif; ?>
        <?php if (!empty($park['amenity_cafeteria'])): ?><span class="badge badge-info mr-1">Cafeteria</span><?php endif; ?>
        <?php if (!empty($park['amenity_shuttle'])): ?><span class="badge badge-info mr-1">Shuttle</span><?php endif; ?>
      </p>
    <?php endif; ?>
    <ul class="mb-3">
      <?php if (!empty($park['inauguration_year'])): ?><li><strong>Year:</strong> <?php echo htmlspecialchars($park['inauguration_year'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
      <?php if (!empty($park['owner'])): ?><li><strong>Owner:</strong> <?php echo htmlspecialchars($park['owner'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
      <?php if (!empty($park['built_up_area'])): ?><li><strong>Built-up:</strong> <?php echo htmlspecialchars($park['built_up_area'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
      <?php if (!empty($park['total_area'])): ?><li><strong>Total area:</strong> <?php echo htmlspecialchars($park['total_area'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
      <?php if (!empty($park['companies'])): ?><li><strong>Major companies:</strong> <?php echo htmlspecialchars($park['companies'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
      <?php if (!empty($park['phone'])): ?><li><strong>Phone:</strong> <?php echo htmlspecialchars($park['phone'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
      <?php if (!empty($park['website'])): ?><li><strong>Website:</strong> <a href="<?php echo htmlspecialchars($park['website'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Visit</a></li><?php endif; ?>
    </ul>
    <?php $mapQuery = urlencode(($park['name'] ?? '').' '.($park['address'] ?? 'OMR Chennai')); $mapUrl = 'https://www.google.com/maps/search/?api=1&query='.$mapQuery; ?>
    <p>
      <a class="btn btn-sm btn-primary js-map-click" href="<?php echo htmlspecialchars($mapUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">View on Map</a>
      <a class="btn btn-sm btn-success ml-2" href="/contact-my-omr-team.php?subject=Sponsor%20IT%20Park%20Listing:%20<?php echo urlencode($park['name']); ?>">Get Listed / Sponsor</a>
    </p>
    <?php if (!empty($park['lat']) && !empty($park['lng'])): ?>
      <div class="mb-3">
        <iframe style="border:0;width:100%;height:320px" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=<?php echo rawurlencode($park['lat'] . ',' . $park['lng']); ?>&z=16&output=embed"></iframe>
      </div>
    <?php endif; ?>

    <?php $loc = $park['location'] ?? ''; if ($loc !== ''): ?>
      <div class="mb-3">
        <strong>Companies nearby:</strong>
        <a class="btn btn-sm btn-outline-primary ml-2" href="/omr-listings/it-companies.php?locality=<?php echo urlencode($loc); ?>">View IT companies in <?php echo htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?></a>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
  function sendEvent(name, params) {
    try {
      if (typeof gtag === 'function') { gtag('event', name, params); }
      else if (window.dataLayer && Array.isArray(window.dataLayer)) { window.dataLayer.push(Object.assign({ event: name }, params)); }
    } catch (e) {}
  }
  document.querySelectorAll('.js-map-click').forEach(function(el) {
    el.addEventListener('click', function() {
      sendEvent('map_click', { category: 'detail_it_parks', label: '<?php echo htmlspecialchars($park['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>' });
    });
  });
});
</script>

</body>
</html>


