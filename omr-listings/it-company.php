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

$stmt = $conn->prepare('SELECT slno, company_name, address, contact, industry_type, verified, about, services, careers_url FROM omr_it_companies WHERE slno = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$company = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$company) {
    require_once __DIR__ . '/../core/serve-404.php';
    exit;
}

$companyName = $company['company_name'];
$address = $company['address'];
$contact = $company['contact'];
$industry = $company['industry_type'];
$aboutTextDb = $company['about'] ?? '';
$servicesTextDb = $company['services'] ?? '';
$careersUrlDb = $company['careers_url'] ?? '';
$isVerified = !empty($company['verified']);
$mapsQuery = urlencode($companyName . ' ' . $address);
$mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . $mapsQuery;

// Canonical pretty URL
$canonical = 'https://myomr.in/it-companies/' . $slug;
$canonical_url = $canonical; $page_title = htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') . ' | IT Company on OMR | MyOMR'; $page_description = htmlspecialchars($companyName . ' - IT Company on Chennai OMR. Address: ' . $address, ENT_QUOTES, 'UTF-8'); $og_title = $page_title; $og_description = $page_description; $og_url = $canonical; $og_image = 'https://myomr.in/My-OMR-Logo.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $breadcrumbs = [
  ['https://myomr.in/','Home'],
  ['https://myomr.in/it-companies','IT Companies'],
  [$canonical, $companyName]
]; ?>
<?php include '../components/meta.php'; ?>
<?php $ga_custom_params = ['listing_type' => 'it_company', 'industry_type' => $company['industry_type'] ?? '']; include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
<meta name="robots" content="index, follow">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body>
<?php include '../components/main-nav.php'; ?>
<div id="main-content" class="container" style="max-width:1280px;">
  <nav aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/it-companies">IT Companies</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8'); ?></li>
    </ol>
  </nav>

  <h1 style="color:#0583D2;"><?php echo htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8'); ?>
    <?php if ($isVerified): ?>
      <span class="badge bg-success" style="font-size:0.6em; vertical-align:middle;">Verified</span>
    <?php endif; ?>
  </h1>
  <p><strong>Industry:</strong> <?php echo htmlspecialchars($industry, ENT_QUOTES, 'UTF-8'); ?></p>
  <p><strong>Address:</strong> <?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></p>
  <p><strong>Contact:</strong> <?php echo htmlspecialchars($contact, ENT_QUOTES, 'UTF-8'); ?></p>
  <div class="mb-3">
    <a id="btnMap" data-company-id="<?php echo (int)$id; ?>" data-company-name="<?php echo htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary" href="<?php echo $mapsUrl; ?>" target="_blank" rel="noopener">View on Google Maps</a>
    <a id="btnEnquire" data-company-id="<?php echo (int)$id; ?>" data-company-name="<?php echo htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-warning" href="/contact-my-omr-team.php?subject=<?php echo urlencode('Listing Enquiry: ' . $companyName); ?>">Enquire</a>
  </div>

  <?php
    $entityName = $companyName;
    $industryOrType = $industry;
    $localitiesList = ['Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Mettukuppam','Sholinganallur','Semmancheri','Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam'];
    include __DIR__ . '/components/detail-profile-blocks.php';
  ?>

  <hr>
  <h3>More on OMR</h3>
  <ul class="list-inline">
    <li class="list-inline-item"><a href="/omr-listings/banks.php">Banks</a></li>
    <li class="list-inline-item"><a href="/omr-listings/hospitals.php">Hospitals</a></li>
    <li class="list-inline-item"><a href="/omr-listings/restaurants.php">Restaurants</a></li>
    <li class="list-inline-item"><a href="/omr-listings/schools.php">Schools</a></li>
  </ul>

  <?php
  // JSON-LD LocalBusiness
  $jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => $companyName,
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
  // BreadcrumbList for detail page
  $crumbs = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
      [
        '@type' => 'ListItem',
        'position' => 1,
        'item' => [ '@id' => 'https://myomr.in/', 'name' => 'Home' ]
      ],
      [
        '@type' => 'ListItem',
        'position' => 2,
        'item' => [ '@id' => 'https://myomr.in/it-companies', 'name' => 'IT Companies' ]
      ],
      [
        '@type' => 'ListItem',
        'position' => 3,
        'item' => [ '@id' => $canonical, 'name' => $companyName ]
      ]
    ]
  ];
  echo '<script type="application/ld+json">' . json_encode($crumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
  ?>

  <?php
  // More in this locality (auto-detected from address)
  $allowedLocalities = [
    'Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Mettukuppam','Sholinganallur',
    'Semmancheri','Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam'
  ];
  $detectedLocality = '';
  foreach ($allowedLocalities as $loc) {
    if (stripos($address, $loc) !== false) { $detectedLocality = $loc; break; }
  }
  if ($detectedLocality !== '') {
    $hubSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $detectedLocality));
    echo '<p><a class="btn btn-sm btn-success" href="/omr-listings/locality/' . htmlspecialchars($hubSlug, ENT_QUOTES, 'UTF-8') . '.php">Visit ' . htmlspecialchars($detectedLocality, ENT_QUOTES, 'UTF-8') . ' hub</a></p>';
  }
  if ($detectedLocality !== '') {
    $like = '%' . $detectedLocality . '%';
    $rel = $conn->prepare('SELECT slno, company_name, address FROM omr_it_companies WHERE slno <> ? AND address LIKE ? ORDER BY company_name ASC LIMIT 6');
    $rel->bind_param('is', $id, $like);
    if ($rel->execute()) {
      $relRes = $rel->get_result();
      if ($relRes && $relRes->num_rows > 0) {
        echo '<hr>';
        echo '<h3>More IT companies in ' . htmlspecialchars($detectedLocality, ENT_QUOTES, 'UTF-8') . '</h3>';
        $relatedItems = [];
        while ($r = $relRes->fetch_assoc()) {
          $nm = $r['company_name'];
          $rid = (int)$r['slno'];
          $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $nm));
          $slugBase = trim($slugBase, '-');
          $url = '/it-companies/' . $slugBase . '-' . $rid;
          $relatedItems[] = [
            'name' => $nm,
            'address' => $r['address'],
            'url' => $url,
            'imageCandidates' => [
              '/assets/img/it-companies/' . $slugBase . '-' . $rid . '.webp',
              '/assets/img/it-companies/' . $slugBase . '-' . $rid . '.jpg',
              '/assets/img/it-companies/' . $slugBase . '-' . $rid . '.png',
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

<!-- UN SDG Floating Badges -->
<?php include '../components/sdg-badge.php'; ?>

<script>
  (function(){
    function safeGtag(){ return (typeof window.gtag === 'function') ? window.gtag : null; }
    var g = safeGtag();
    var map = document.getElementById('btnMap');
    var enq = document.getElementById('btnEnquire');
    function send(evtName, el){
      if (!g || !el) return;
      g('event', evtName, {
        event_category: 'it_company_detail',
        event_label: el.getAttribute('data-company-name') || '',
        company_id: el.getAttribute('data-company-id') || ''
      });
    }
    if (map) map.addEventListener('click', function(){ send('map_click', map); });
    if (enq) enq.addEventListener('click', function(){ send('enquire_click', enq); });
  })();
</script>
</body>
</html>


