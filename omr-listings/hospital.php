<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../core/omr-connect.php';

function extract_id_from_slug($slug) {
    if (!is_string($slug) || $slug === '') { return 0; }
    $parts = explode('-', $slug);
    $last = end($parts);
    return ctype_digit($last) ? (int)$last : 0;
}

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$id = extract_id_from_slug($slug);

if ($id <= 0) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><body><h1>Not Found</h1></body></html>';
    exit;
}

$stmt = $conn->prepare('SELECT slno, hospitalname, address, contact, landmark, locality, verified, about, services, careers_url FROM omrhospitalslist WHERE slno = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$hospital = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$hospital) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><body><h1>Hospital not found</h1></body></html>';
    exit;
}

$name = $hospital['hospitalname'];
$address = $hospital['address'];
$contact = $hospital['contact'];
$landmark = $hospital['landmark'];
$isVerified = !empty($hospital['verified']);
$aboutTextDb = $hospital['about'] ?? '';
$servicesTextDb = $hospital['services'] ?? '';
$careersUrlDb = $hospital['careers_url'] ?? '';
$mapsQuery = urlencode($name . ' ' . $address);
$canonical = 'https://myomr.in/hospitals/' . $slug;
$canonical_url = $canonical; $page_title = htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' | Hospital on OMR | MyOMR'; $page_description = htmlspecialchars($name . ' - Hospital on Chennai OMR. Address: ' . $address, ENT_QUOTES, 'UTF-8'); $og_title = $page_title; $og_description = $page_description; $og_url = $canonical; $og_image = 'https://myomr.in/My-OMR-Logo.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $breadcrumbs = [
  ['https://myomr.in/','Home'],
  ['https://myomr.in/hospitals','Hospitals'],
  [$canonical, $name]
]; ?>
<?php include '../components/meta.php'; ?>
<?php $ga_custom_params = ['listing_type' => 'hospital', 'locality' => $hospital['locality'] ?? '']; include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
</head>
<body>
<div class="container maxw-1280">
  <nav aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/hospitals">Hospitals</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></li>
    </ol>
  </nav>

  <h1 style="color:#0583D2;"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
    <?php if ($isVerified): ?>
      <span class="badge bg-success" style="font-size:0.6em; vertical-align:middle;">Verified</span>
    <?php endif; ?>
  </h1>
  <?php if (!empty($landmark)): ?><p><strong>Landmark:</strong> <?php echo htmlspecialchars($landmark, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
  <p><strong>Address:</strong> <?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></p>
  <p><strong>Contact:</strong> <?php echo htmlspecialchars($contact, ENT_QUOTES, 'UTF-8'); ?></p>
  <div class="mb-3">
    <a id="btnMap" data-entity-name="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary" href="https://www.google.com/maps/search/?api=1&query=<?php echo $mapsQuery; ?>" target="_blank" rel="noopener">View on Google Maps</a>
    <a class="btn btn-warning" href="/contact-my-omr-team.php?subject=<?php echo urlencode('Listing Enquiry: ' . $name); ?>">Enquire</a>
  </div>

  <?php
    $entityName = $name;
    $industryOrType = 'Hospital';
    $localitiesList = ['Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Mettukuppam','Sholinganallur','Semmancheri','Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam'];
    include __DIR__ . '/components/detail-profile-blocks.php';
  ?>

  <?php
  // JSON-LD LocalBusiness (Hospital)
  $jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'Hospital',
    'name' => $name,
    'address' => [
      '@type' => 'PostalAddress',
      'streetAddress' => $address,
      'addressLocality' => 'Chennai',
      'addressRegion' => 'TN',
      'addressCountry' => 'IN'
    ],
    'url' => $canonical,
  ];
  if (!empty($contact)) { $jsonLd['telephone'] = $contact; }
  echo '<script type="application/ld+json">' . json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
  ?>

  <?php
  // Related hospitals in same locality (detected from address)
  $allowedLocalities = ['Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Mettukuppam','Sholinganallur','Semmancheri','Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam'];
  $detectedLocality = '';
  foreach ($allowedLocalities as $loc) {
    if (stripos($address, $loc) !== false) { $detectedLocality = $loc; break; }
  }
  if ($detectedLocality !== '') {
    $like = '%' . $detectedLocality . '%';
    $rel = $conn->prepare('SELECT slno, hospitalname, address FROM omrhospitalslist WHERE slno <> ? AND address LIKE ? ORDER BY hospitalname ASC LIMIT 6');
    $rel->bind_param('is', $id, $like);
    if ($rel->execute()) {
      $relRes = $rel->get_result();
      if ($relRes && $relRes->num_rows > 0) {
        echo '<hr>';
        echo '<h3>More hospitals in ' . htmlspecialchars($detectedLocality, ENT_QUOTES, 'UTF-8') . '</h3>';
        $relatedItems = [];
        while ($r = $relRes->fetch_assoc()) {
          $nm = $r['hospitalname'];
          $rid = (int)$r['slno'];
          $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $nm));
          $slugBase = trim($slugBase, '-');
          $url = '/hospitals/' . $slugBase . '-' . $rid;
          $relatedItems[] = [
            'name' => $nm,
            'address' => $r['address'],
            'url' => $url,
            'imageCandidates' => [
              '/assets/img/hospitals/' . $slugBase . '-' . $rid . '.webp',
              '/assets/img/hospitals/' . $slugBase . '-' . $rid . '.jpg',
              '/assets/img/hospitals/' . $slugBase . '-' . $rid . '.png',
            ]
          ];
        }
        $fallbackImage = '/My-OMR-Logo.jpg';
        include __DIR__ . '/components/related-cards.php';
      }
    }
    $rel->close();
  }
  ?>

  <div class="my-4"></div>
  <?php include '../components/subscribe.php'; ?>
</div>

<?php include '../components/footer.php'; ?>
</body>
</html>
<?php exit; // Prevent second legacy template from rendering ?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../core/omr-connect.php';

function extract_id_from_slug($slug) {
    if (!is_string($slug) || $slug === '') { return 0; }
    $parts = explode('-', $slug);
    $last = end($parts);
    return ctype_digit($last) ? (int)$last : 0;
}

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$id = extract_id_from_slug($slug);
if ($id <= 0) { http_response_code(404); echo '<!DOCTYPE html><html><body><h1>Not Found</h1></body></html>'; exit; }

$stmt = $conn->prepare('SELECT slno, hospitalname, address, contact, landmark FROM omrhospitalslist WHERE slno = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$item = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$item) { http_response_code(404); echo '<!DOCTYPE html><html><body><h1>Hospital not found</h1></body></html>'; exit; }

$name = $item['hospitalname'];
$address = $item['address'];
$contact = $item['contact'];
$canonical = 'https://myomr.in/hospitals/' . $slug;
$canonical_url = $canonical; $page_title = htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' | Hospital on OMR | MyOMR'; $page_description = htmlspecialchars($name . ' - Hospital on Chennai OMR. Address: ' . $address, ENT_QUOTES, 'UTF-8'); $og_title = $page_title; $og_description = $page_description; $og_url = $canonical; $og_image = 'https://myomr.in/My-OMR-Logo.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $breadcrumbs = [ ['https://myomr.in/','Home'], ['https://myomr.in/hospitals','Hospitals'], [$canonical, $name] ]; ?>
<?php include '../components/meta.php'; ?>
<?php $ga_custom_params = ['listing_type' => 'hospital']; include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
<meta name="robots" content="index, follow">
</head>
<body>
<?php include '../components/main-nav.php'; ?>
<div id="main-content" class="container" style="max-width:1280px;">
  <nav aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/hospitals">Hospitals</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></li>
    </ol>
  </nav>

  <h2 style="color:#0583D2;"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h2>
  <p><strong>Address:</strong> <?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></p>
  <?php if (!empty($contact)): ?><p><strong>Contact:</strong> <?php echo htmlspecialchars($contact, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>

  <?php $mapsQuery = urlencode($name . ' ' . $address); $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . $mapsQuery; ?>
  <a class="btn btn-outline-primary" href="<?php echo $mapsUrl; ?>" target="_blank" rel="noopener">View on Google Maps</a>

  <?php
  $jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => $name,
    'address' => [
      '@type' => 'PostalAddress',
      'streetAddress' => $address,
      'addressLocality' => 'Chennai',
      'addressRegion' => 'TN',
      'postalCode' => '600097',
      'addressCountry' => 'IN'
    ],
    'url' => $canonical,
  ];
  if (!empty($contact)) { $jsonLd['telephone'] = $contact; }
  echo '<script type="application/ld+json">' . json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
  ?>

  <div class="my-4"></div>
  <?php include '../components/subscribe.php'; ?>
</div>
<?php include '../components/footer.php'; ?>
</body>
</html>


