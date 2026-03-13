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

$stmt = $conn->prepare('SELECT slno, bankname, address, contact, landmark, website FROM omrbankslist WHERE slno = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$bank = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$bank) { require_once __DIR__ . '/../core/serve-404.php'; exit; }

$name = $bank['bankname'];
$address = $bank['address'];
$contact = $bank['contact'];
$website = $bank['website'];
$canonical = 'https://myomr.in/banks/' . $slug;
$canonical_url = $canonical; $page_title = htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' | Bank on OMR | MyOMR'; $page_description = htmlspecialchars($name . ' - Bank on Chennai OMR. Address: ' . $address, ENT_QUOTES, 'UTF-8'); $og_title = $page_title; $og_description = $page_description; $og_url = $canonical; $og_image = 'https://myomr.in/My-OMR-Logo.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $breadcrumbs = [ ['https://myomr.in/','Home'], ['https://myomr.in/banks','Banks'], [$canonical, $name] ]; ?>
<?php include '../components/meta.php'; ?>
<?php $ga_custom_params = ['listing_type' => 'bank']; include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
<meta name="robots" content="index, follow">
</head>
<body>
<?php include '../components/main-nav.php'; ?>
<div id="main-content" class="container maxw-1280">
  <nav aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/banks">Banks</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></li>
    </ol>
  </nav>

  <h1 style="color:#0583D2;"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h1>
  <p><strong>Address:</strong> <?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></p>
  <?php if (!empty($contact)): ?><p><strong>Contact:</strong> <?php echo htmlspecialchars($contact, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
  <?php if (!empty($website)): ?><p><a class="btn btn-primary" href="<?php echo htmlspecialchars($website, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Visit Website</a></p><?php endif; ?>

  <?php
  $mapsQuery = urlencode($name . ' ' . $address);
  $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . $mapsQuery;
  ?>
  <a class="btn btn-outline-primary" href="<?php echo $mapsUrl; ?>" target="_blank" rel="noopener">View on Google Maps</a>

  <?php
    $companyName = $name;
    $industry = 'Bank';
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
  if (!empty($website)) { $jsonLd['sameAs'] = [$website]; }
  echo '<script type="application/ld+json">' . json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
  ?>

  <?php
  // Related banks in same locality (detected from address)
  $allowedLocalities = ['Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Mettukuppam','Sholinganallur','Semmancheri','Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam'];
  $detectedLocality = '';
  foreach ($allowedLocalities as $loc) {
    if (stripos($address, $loc) !== false) { $detectedLocality = $loc; break; }
  }
  if ($detectedLocality !== '') {
    $like = '%' . $detectedLocality . '%';
    $rel = $conn->prepare('SELECT slno, bankname, address FROM omrbankslist WHERE slno <> ? AND address LIKE ? ORDER BY bankname ASC LIMIT 6');
    $rel->bind_param('is', $id, $like);
    if ($rel->execute()) {
      $relRes = $rel->get_result();
      if ($relRes && $relRes->num_rows > 0) {
        echo '<hr>';
        echo '<h3>More banks in ' . htmlspecialchars($detectedLocality, ENT_QUOTES, 'UTF-8') . '</h3>';
        $relatedItems = [];
        while ($r = $relRes->fetch_assoc()) {
          $nm = $r['bankname'];
          $rid = (int)$r['slno'];
          $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $nm));
          $slugBase = trim($slugBase, '-');
          $url = '/banks/' . $slugBase . '-' . $rid;
          $relatedItems[] = [
            'name' => $nm,
            'address' => $r['address'],
            'url' => $url,
            'imageCandidates' => [
              '/assets/img/banks/' . $slugBase . '-' . $rid . '.webp',
              '/assets/img/banks/' . $slugBase . '-' . $rid . '.jpg',
              '/assets/img/banks/' . $slugBase . '-' . $rid . '.png',
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


