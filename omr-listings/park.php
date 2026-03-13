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
    require_once __DIR__ . '/../core/serve-404.php';
    exit;
}

$stmt = $conn->prepare('SELECT slno, parkname, location, area, features, timings FROM omrparkslist WHERE slno = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$row) {
    require_once __DIR__ . '/../core/serve-404.php';
    exit;
}

$name = $row['parkname'];
$location = $row['location'];
$area = $row['area'];
$features = $row['features'];
$timings = $row['timings'];
$mapsQuery = urlencode($name . ' ' . $location);
$canonical = 'https://myomr.in/parks/' . $slug;
$canonical_url = $canonical; $page_title = htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' | Park on OMR | MyOMR'; $page_description = htmlspecialchars($name . ' - Park on Chennai OMR. Address: ' . $address, ENT_QUOTES, 'UTF-8'); $og_title = $page_title; $og_description = $page_description; $og_url = $canonical; $og_image = 'https://myomr.in/My-OMR-Logo.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $breadcrumbs = [
  ['https://myomr.in/','Home'],
  ['https://myomr.in/parks','Parks'],
  [$canonical, $name]
]; ?>
<?php include '../components/meta.php'; ?>
<?php $ga_custom_params = ['listing_type' => 'park']; include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
</head>
<body>
<?php include '../components/main-nav.php'; ?>
<div id="main-content" class="container maxw-1280">
  <nav aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/parks">Parks</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></li>
    </ol>
  </nav>

  <h1 style="color:#0583D2;"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h1>
  <p><strong>Location:</strong> <?php echo htmlspecialchars($location); ?></p>
  <?php if (!empty($area)): ?><p><strong>Area:</strong> <?php echo htmlspecialchars($area); ?></p><?php endif; ?>
  <?php if (!empty($timings)): ?><p><strong>Timings:</strong> <?php echo htmlspecialchars($timings); ?></p><?php endif; ?>
  <?php if (!empty($features)): ?><div class="card mb-3"><div class="card-body"><h5 class="card-title">Features</h5><p class="mb-0"><?php echo htmlspecialchars($features); ?></p></div></div><?php endif; ?>
  <div class="mb-3">
    <a class="btn btn-primary" href="https://www.google.com/maps/search/?api=1&query=<?php echo $mapsQuery; ?>" target="_blank" rel="noopener">View on Google Maps</a>
  </div>

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
if ($id <= 0) { require_once __DIR__ . '/../core/serve-404.php'; exit; }

$stmt = $conn->prepare('SELECT slno, parkname, location, area, features, timings FROM omrparkslist WHERE slno = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$item = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$item) { require_once __DIR__ . '/../core/serve-404.php'; exit; }

$name = $item['parkname'];
$address = $item['location'];
$canonical = 'https://myomr.in/parks/' . $slug;
$canonical_url = $canonical; $page_title = htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' | Park on OMR | MyOMR'; $page_description = htmlspecialchars($name . ' - Park on Chennai OMR. Address: ' . $address, ENT_QUOTES, 'UTF-8'); $og_title = $page_title; $og_description = $page_description; $og_url = $canonical; $og_image = 'https://myomr.in/My-OMR-Logo.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $breadcrumbs = [ ['https://myomr.in/','Home'], ['https://myomr.in/parks','Parks'], [$canonical, $name] ]; ?>
<?php include '../components/meta.php'; ?>
<?php $ga_custom_params = ['listing_type' => 'park']; include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
<meta name="robots" content="index, follow">
</head>
<body>
<?php include '../components/main-nav.php'; ?>
<div id="main-content" class="container maxw-1280">
  <nav aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/parks">Parks</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></li>
    </ol>
  </nav>

  <h2 style="color:#0583D2;"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h2>
  <p><strong>Location:</strong> <?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></p>
  <?php if (!empty($item['features'])): ?><p><strong>Features:</strong> <?php echo htmlspecialchars($item['features'], ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
  <?php if (!empty($item['timings'])): ?><p><strong>Timings:</strong> <?php echo htmlspecialchars($item['timings'], ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>

  <?php $mapsQuery = urlencode($name . ' ' . $address); $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . $mapsQuery; ?>
  <a class="btn btn-outline-primary" href="<?php echo $mapsUrl; ?>" target="_blank" rel="noopener">View on Google Maps</a>

  <?php
    $companyName = $name;
    $industry = 'Park';
    $aboutTextDb = '';
    $servicesTextDb = $item['features'] ?? '';
    $careersUrlDb = '';
    $localitiesList = ['Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Mettukuppam','Sholinganallur','Semmancheri','Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam'];
    include __DIR__ . '/components/detail-profile-blocks.php';
  ?>

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
  echo '<script type="application/ld+json">' . json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
  ?>

  <?php
  // Related parks in same locality (detected from location)
  $allowedLocalities = ['Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Mettukuppam','Sholinganallur','Semmancheri','Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam'];
  $detectedLocality = '';
  foreach ($allowedLocalities as $loc) {
    if (stripos($address, $loc) !== false) { $detectedLocality = $loc; break; }
  }
  if ($detectedLocality !== '') {
    $like = '%' . $detectedLocality . '%';
    $rel = $conn->prepare('SELECT slno, parkname, location FROM omrparkslist WHERE slno <> ? AND location LIKE ? ORDER BY parkname ASC LIMIT 6');
    $rel->bind_param('is', $id, $like);
    if ($rel->execute()) {
      $relRes = $rel->get_result();
      if ($relRes && $relRes->num_rows > 0) {
        echo '<hr>';
        echo '<h3>More parks in ' . htmlspecialchars($detectedLocality, ENT_QUOTES, 'UTF-8') . '</h3>';
        $relatedItems = [];
        while ($r = $relRes->fetch_assoc()) {
          $nm = $r['parkname'];
          $rid = (int)$r['slno'];
          $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $nm));
          $slugBase = trim($slugBase, '-');
          $url = '/parks/' . $slugBase . '-' . $rid;
          $relatedItems[] = [
            'name' => $nm,
            'address' => $r['location'],
            'url' => $url,
            'imageCandidates' => [
              '/assets/img/parks/' . $slugBase . '-' . $rid . '.webp',
              '/assets/img/parks/' . $slugBase . '-' . $rid . '.jpg',
              '/assets/img/parks/' . $slugBase . '-' . $rid . '.png',
            ]
          ];
        }
        $fallbackImage = '/My-OMR-Logo.png';
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


