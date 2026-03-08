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

$stmt = $conn->prepare('SELECT slno, office_name, address, contact, landmark FROM omr_gov_offices WHERE slno = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$item = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$item) { http_response_code(404); echo '<!DOCTYPE html><html><body><h1>Government office not found</h1></body></html>'; exit; }

$name = $item['office_name'];
$address = $item['address'];
$contact = $item['contact'];
$canonical = 'https://myomr.in/government-offices/' . $slug;

$page_title       = htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' | Government Office on OMR | MyOMR';
$page_description = htmlspecialchars($name . ' - Government office on Chennai OMR. Address: ' . $address, ENT_QUOTES, 'UTF-8');
$canonical_url    = $canonical;
$og_title         = $page_title;
$og_description   = $page_description;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $breadcrumbs = [ ['https://myomr.in/','Home'], ['https://myomr.in/government-offices','Government Offices'], [$canonical, $name] ]; ?>
<?php include '../components/meta.php'; ?>
<?php $ga_custom_params = ['listing_type' => 'govt_office']; include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
<meta name="robots" content="index, follow">
</head>
<body>
<?php include '../components/main-nav.php'; ?>
<div id="main-content" class="container maxw-1280">
  <nav aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/government-offices">Government Offices</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></li>
    </ol>
  </nav>

  <h1 style="color:#0583D2;"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h1>
  <p><strong>Address:</strong> <?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></p>
  <?php if (!empty($contact)): ?><p><strong>Contact:</strong> <?php echo htmlspecialchars($contact, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>

  <?php $mapsQuery = urlencode($name . ' ' . $address); $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . $mapsQuery; ?>
  <a class="btn btn-outline-primary" href="<?php echo $mapsUrl; ?>" target="_blank" rel="noopener">View on Google Maps</a>

  <?php
    $companyName = $name;
    $industry = 'Government Office';
    $aboutTextDb = '';
    $servicesTextDb = '';
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
  if (!empty($contact)) { $jsonLd['telephone'] = $contact; }
  echo '<script type="application/ld+json">' . json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
  ?>

  <?php
  // Related government offices in same locality (detected from address)
  $allowedLocalities = ['Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Mettukuppam','Sholinganallur','Semmancheri','Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam'];
  $detectedLocality = '';
  foreach ($allowedLocalities as $loc) {
    if (stripos($address, $loc) !== false) { $detectedLocality = $loc; break; }
  }
  if ($detectedLocality !== '') {
    $like = '%' . $detectedLocality . '%';
    $rel = $conn->prepare('SELECT slno, office_name, address FROM omr_gov_offices WHERE slno <> ? AND address LIKE ? ORDER BY office_name ASC LIMIT 6');
    $rel->bind_param('is', $id, $like);
    if ($rel->execute()) {
      $relRes = $rel->get_result();
      if ($relRes && $relRes->num_rows > 0) {
        echo '<hr>';
        echo '<h3>More government offices in ' . htmlspecialchars($detectedLocality, ENT_QUOTES, 'UTF-8') . '</h3>';
        $relatedItems = [];
        while ($r = $relRes->fetch_assoc()) {
          $nm = $r['office_name'];
          $rid = (int)$r['slno'];
          $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $nm));
          $slugBase = trim($slugBase, '-');
          $url = '/government-offices/' . $slugBase . '-' . $rid;
          $relatedItems[] = [
            'name' => $nm,
            'address' => $r['address'],
            'url' => $url,
            'imageCandidates' => [
              '/assets/img/government-offices/' . $slugBase . '-' . $rid . '.webp',
              '/assets/img/government-offices/' . $slugBase . '-' . $rid . '.jpg',
              '/assets/img/government-offices/' . $slugBase . '-' . $rid . '.png',
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


