<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Core includes (kept for consistency even if not using DB here)
require '../core/omr-connect.php';
require_once __DIR__ . '/directory-config.php';
require_once '../core/cache-helpers.php';
omr_output_cache_start(omr_cache_key_from_request('it_parks:'), 300);
require_once __DIR__ . '/data/it-parks-data.php';
// Try DB source when available
$useDb = false;
$dbCount = 0;
if ($conn) {
  $chk = $conn->query('SHOW TABLES LIKE "omr_it_parks"');
  if ($chk && $chk->num_rows > 0) {
    $resCnt = $conn->query('SELECT COUNT(*) AS c FROM omr_it_parks');
    if ($resCnt) { $rowCnt = $resCnt->fetch_assoc(); $dbCount = (int)($rowCnt['c'] ?? 0); }
    if ($dbCount > 0) { $useDb = true; }
  }
}

?>
<?php include __DIR__ . '/../weblog/log.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
$page_title          = 'IT Parks in OMR, Chennai | MyOMR';
$page_description    = 'Explore major IT Parks along Old Mahabalipuram Road (OMR), Chennai - locations, addresses, websites, inauguration year, owners, and key companies.';
$canonical_url       = 'https://myomr.in/it-parks';
$og_title            = 'IT Parks in OMR, Chennai | MyOMR';
$og_description      = 'Explore major IT Parks along Old Mahabalipuram Road (OMR), Chennai - locations, addresses, websites, inauguration year, owners, and key companies.';
$og_image            = 'https://myomr.in/My-OMR-Logo.jpg';
$og_url              = 'https://myomr.in/it-parks';
$twitter_title       = 'IT Parks in OMR, Chennai | MyOMR';
$twitter_description = 'Explore major IT Parks along Old Mahabalipuram Road (OMR), Chennai - locations, addresses, websites, inauguration year, owners, and key companies.';
$twitter_image       = 'https://myomr.in/My-OMR-Logo.jpg';
?>
<?php $breadcrumbs = [
  ['https://myomr.in/','Home'],
  ['https://myomr.in/it-parks','IT Parks in OMR']
]; ?>
<?php include '../components/meta.php'; ?>
<?php $ga_custom_params = ['listing_type' => 'it_park']; include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>

<meta name="robots" content="index, follow">
<style>
body { font-family: 'Poppins', sans-serif; }
.maxw-1280 { max-width: 1280px; }
.park-card h5 { color:#0583D2; }
.badge-locality { background:#4c516D; color:#fff; margin-left:6px; }
.muted-note { color:#6c757d; font-size: 0.95rem; }
.list-inline.sources li { margin-right: 10px; }
</style>
</head>
<body>
<?php include '../components/skip-link.php'; ?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/components/omr-listings-nav.php'; ?>

<?php
// Static dataset curated from public sources; please keep fields short and factual.
$parksAll = $omr_it_parks_all;

// Featured slots (sponsored) – table-backed, mapped to in-memory data
// Create featured table if missing
$conn->query("CREATE TABLE IF NOT EXISTS omr_it_parks_featured (
  id INT AUTO_INCREMENT PRIMARY KEY,
  park_id INT NOT NULL,
  rank_position INT NOT NULL DEFAULT 1,
  blurb VARCHAR(400) DEFAULT NULL,
  cta_text VARCHAR(80) DEFAULT NULL,
  cta_url VARCHAR(255) DEFAULT NULL,
  start_at DATETIME NULL,
  end_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(park_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Filters & pagination (mirror it-companies.php style)
$searchQueryRaw = isset($_GET['q']) ? trim($_GET['q']) : '';
$searchQuery = $searchQueryRaw;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 9;

$allowedLocalities = [
  'Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Mettukuppam','Sholinganallur',
  'Semmancheri','Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam','Taramani'
];
$localityRaw = isset($_GET['locality']) ? trim($_GET['locality']) : '';
$locality = in_array($localityRaw, $allowedLocalities, true) ? $localityRaw : '';

$sortRaw = isset($_GET['sort']) ? trim($_GET['sort']) : 'az';
$allowedSorts = ['az','newest'];
$sort = in_array($sortRaw, $allowedSorts, true) ? $sortRaw : 'az';

if ($useDb) {
  // DB-backed filters
  $where = [];
  $params = [];
  $types = '';
  if ($searchQuery !== '') {
    $where[] = '(name LIKE ? OR address LIKE ? OR locality LIKE ? OR owner LIKE ?)';
    $like = '%' . $searchQuery . '%';
    $params[] = $like; $params[] = $like; $params[] = $like; $params[] = $like; $types .= 'ssss';
  }
  if ($locality !== '') {
    $where[] = 'locality = ?';
    $params[] = $locality; $types .= 's';
  }
  $whereSql = empty($where) ? '' : (' WHERE ' . implode(' AND ', $where));
  $orderSql = ($sort === 'newest') ? ' ORDER BY COALESCE(NULLIF(inauguration_year,\'\'),\'0000\') DESC, name ASC ' : ' ORDER BY name ASC ';
  // Count
  $countSql = 'SELECT COUNT(*) AS c FROM omr_it_parks' . $whereSql;
  $stmt = $conn->prepare($countSql);
  if ($stmt) {
    if ($types !== '') { $stmt->bind_param($types, ...$params); }
    $stmt->execute();
    $r = $stmt->get_result();
    $row = $r ? $r->fetch_assoc() : ['c'=>0];
    $totalResults = (int)($row['c'] ?? 0);
    $stmt->close();
  } else { $totalResults = 0; }
  $totalPages = max(1, (int)ceil($totalResults / $perPage));
  $page = min($page, $totalPages);
  $offset = ($page - 1) * $perPage;
  // Data
  $dataSql = 'SELECT id, name, locality, address, phone, website, inauguration_year, owner, built_up_area, total_area, image FROM omr_it_parks' . $whereSql . $orderSql . ' LIMIT ? OFFSET ?';
  $stmt = $conn->prepare($dataSql);
  if ($stmt) {
    $types2 = $types . 'ii';
    $params2 = array_merge($params, [$perPage, $offset]);
    $stmt->bind_param($types2, ...$params2);
    $stmt->execute();
    $rs = $stmt->get_result();
    $parks = [];
    while ($row = $rs->fetch_assoc()) { $parks[] = $row; }
    $stmt->close();
  } else { $parks = []; }
} else {
  // In-memory fallback
  $parks = array_values(array_filter($parksAll, function($p) use ($searchQuery, $locality) {
    $hay = strtolower(($p['name'] ?? '') . ' ' . ($p['address'] ?? '') . ' ' . ($p['location'] ?? ''));
    $okSearch = ($searchQuery === '') || (strpos($hay, strtolower($searchQuery)) !== false);
    $okLoc = ($locality === '') || (stripos($p['location'] ?? '', $locality) !== false) || (stripos($p['address'] ?? '', $locality) !== false);
    return $okSearch && $okLoc;
  }));
  usort($parks, function($a, $b) use ($sort) {
    if ($sort === 'newest') {
      $ya = (int)preg_replace('/[^0-9]/','', $a['inauguration_year'] ?? '0');
      $yb = (int)preg_replace('/[^0-9]/','', $b['inauguration_year'] ?? '0');
      if ($ya === $yb) { return strcasecmp($a['name'] ?? '', $b['name'] ?? ''); }
      return ($yb <=> $ya);
    }
    return strcasecmp($a['name'] ?? '', $b['name'] ?? '');
  });
  $totalResults = count($parks);
  $totalPages = max(1, (int)ceil($totalResults / $perPage));
  $page = min($page, $totalPages);
  $offset = ($page - 1) * $perPage;
  $parks = array_slice($parks, $offset, $perPage);
}

// Build JSON-LD ItemList
$itemList = [];
$pos = 1;
foreach ($parks as $p) {
  $itemList[] = [
    '@type' => 'ListItem',
    'position' => $pos++,
    'name' => $p['name'],
    'url' => 'https://myomr.in/it-parks#'. urlencode(strtolower(preg_replace('/[^a-zA-Z0-9]+/','-',$p['name'])))
  ];
}
?>

<div class="container maxw-1280">
  <h1 style="text-align:center; color:#0583D2;">IT Parks on OMR, Chennai</h1>
  <p style="text-align:center; margin-bottom:18px;">A quick directory of key IT parks across Old Mahabalipuram Road (Rajiv Gandhi Salai) with locations, sizes, and anchor tenants.</p>

  <form id="search-form" method="get" action="" class="mb-3">
    <div class="form-row">
      <div class="col-12 col-sm-6 mb-2">
        <input type="text" name="q" value="<?php echo htmlspecialchars($searchQueryRaw, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" placeholder="Search by park name, address or locality">
      </div>
      <div class="col-6 col-sm-3 mb-2">
        <select name="locality" class="form-control">
          <option value="">All localities</option>
          <?php foreach ($allowedLocalities as $loc): ?>
            <option value="<?php echo htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($locality === $loc ? 'selected' : ''); ?>><?php echo htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-6 col-sm-2 mb-2">
        <select name="sort" class="form-control">
          <option value="az" <?php echo ($sort === 'az' ? 'selected' : ''); ?>>A–Z</option>
          <option value="newest" <?php echo ($sort === 'newest' ? 'selected' : ''); ?>>Newest</option>
        </select>
      </div>
      <div class="col-12 col-sm-1 mb-2">
        <button type="submit" class="btn btn-primary btn-block">Go</button>
      </div>
    </div>
  </form>

  <div class="d-flex justify-content-between align-items-center mb-2">
    <div>
      <?php
        $from = ($totalResults === 0) ? 0 : ($offset + 1);
        $to = min($offset + $perPage, $totalResults);
        echo "<small>Showing {$from}–{$to} of {$totalResults} results</small>";
      ?>
    </div>
    <div>
      <?php if (!empty($locality)) { $hub = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $locality)); echo '<a class="btn btn-sm btn-outline-primary mr-2" href="/omr-listings/locality/' . htmlspecialchars($hub, ENT_QUOTES, 'UTF-8') . '.php">' . htmlspecialchars($locality, ENT_QUOTES, 'UTF-8') . ' hub</a>'; } ?>
      <a href="/omr-listings/get-listed.php" class="btn btn-sm btn-success">Get your park listed</a>
      <a href="/omr-listings/get-listed.php#pricing" class="btn btn-sm btn-outline-success ml-2">Sponsor</a>
    </div>
  </div>

  <div class="alert alert-light" role="note" style="font-size:0.95rem;">
    Data compiled from public sources. Primary reference: <a href="https://www.roofandfloor.com/chennai/blog/it-parks-in-omr" target="_blank" rel="noopener">RoofandFloor: IT Parks in OMR</a>.
  </div>

  <?php
  // Load active featured parks
  $featuredSql = "SELECT id, park_id, rank_position, blurb, cta_text, cta_url
                   FROM omr_it_parks_featured
                   WHERE (start_at IS NULL OR start_at <= NOW())
                     AND (end_at IS NULL OR end_at >= NOW())
                   ORDER BY rank_position ASC, id DESC
                   LIMIT 6";
  $featuredRes = $conn->query($featuredSql);
  if ($featuredRes && $featuredRes->num_rows > 0) {
      echo "<div class='mb-3'><h3 style='color:#0583D2;'>Featured IT Parks</h3>";
      echo "<div class='row'>";
      while ($fr = $featuredRes->fetch_assoc()) {
          $pid = (int)$fr['park_id'];
          $parkRef = null;
          foreach ($omr_it_parks_all as $pp) { if ((int)$pp['id'] === $pid) { $parkRef = $pp; break; } }
          if (!$parkRef) { continue; }
          $pname = htmlspecialchars($parkRef['name'], ENT_QUOTES, 'UTF-8');
          $paddr = htmlspecialchars($parkRef['address'] ?? '', ENT_QUOTES, 'UTF-8');
          $pimage = htmlspecialchars($parkRef['image'] ?? '', ENT_QUOTES, 'UTF-8');
          $fblurb = htmlspecialchars($fr['blurb'] ?? '', ENT_QUOTES, 'UTF-8');
          $fctaText = htmlspecialchars($fr['cta_text'] ?? 'Learn more', ENT_QUOTES, 'UTF-8');
          $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $parkRef['name']));
          $slugBase = trim($slugBase, '-');
          $detail = '/it-parks/' . $slugBase . '-' . $pid;
          $fctaUrl = htmlspecialchars($fr['cta_url'] ?? $detail, ENT_QUOTES, 'UTF-8');
          echo "<div class='col-md-4 mb-3'>";
          echo "<div class='card h-100'>";
          if (!empty($pimage)) { echo "<img class='card-img-top' alt='".$pname."' src='".$pimage."' loading='lazy' decoding='async'>"; }
          echo "<div class='card-body'>";
          echo "<h5 class='card-title'>".$pname."</h5>";
          if ($paddr !== '') { echo "<p class='card-text'><small class='text-muted'>".$paddr."</small></p>"; }
          if ($fblurb !== '') { echo "<p class='card-text'>".$fblurb."</p>"; }
          echo "<a class='btn btn-primary' href='".$fctaUrl."' target='_blank' rel='noopener'>".$fctaText."</a>";
          echo "</div></div></div>";
      }
      echo "</div></div>";
  }
  ?>

  <?php $itemList = []; $posCounter = $offset + 1; ?>
  <div class="row">
    <?php foreach ($parks as $park):
      $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/','-',$park['name']));
      $mapQuery = urlencode($park['name'].' '.$park['address']);
      $mapUrl = 'https://www.google.com/maps/search/?api=1&query='.$mapQuery;
      $pid = (int)($park['id'] ?? 0);
      if ($pid > 0) {
        $itemList[] = [
          '@type' => 'ListItem',
          'position' => $posCounter++,
          'name' => $park['name'],
          'url' => 'https://myomr.in/it-parks/'.$slug.'-'.$pid
        ];
      }
    ?>
    <div id="<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>" class="col-md-6 col-lg-4 mb-4">
      <div class="card h-100 park-card">
        <?php if (!empty($park['image'])): ?>
          <img src="<?php echo htmlspecialchars($park['image'], ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($park['name'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy" decoding="async">
        <?php endif; ?>
        <div class="card-body">
          <h5 class="card-title"><?php echo htmlspecialchars($park['name'], ENT_QUOTES, 'UTF-8'); ?></h5>
          <p class="mb-1">
            <strong>Location:</strong>
            <?php echo htmlspecialchars($park['location'], ENT_QUOTES, 'UTF-8'); ?>
            <span class="badge badge-locality">OMR</span>
          </p>
          <?php if (!empty($park['address'])): ?>
          <p class="muted-note mb-2"><?php echo htmlspecialchars($park['address'], ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>
          <ul class="list-unstyled mb-3">
            <?php if (!empty($park['inauguration_year'])): ?><li><strong>Year:</strong> <?php echo htmlspecialchars($park['inauguration_year'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
            <?php if (!empty($park['owner'])): ?><li><strong>Owner:</strong> <?php echo htmlspecialchars($park['owner'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
            <?php if (!empty($park['built_up_area'])): ?><li><strong>Built-up:</strong> <?php echo htmlspecialchars($park['built_up_area'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
            <?php if (!empty($park['total_area'])): ?><li><strong>Total area:</strong> <?php echo htmlspecialchars($park['total_area'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
            <?php if (!empty($park['companies'])): ?><li><strong>Major companies:</strong> <?php echo htmlspecialchars($park['companies'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
            <?php if (!empty($park['phone'])): ?><li><strong>Phone:</strong> <?php echo htmlspecialchars($park['phone'], ENT_QUOTES, 'UTF-8'); ?></li><?php endif; ?>
          </ul>
          <div>
            <a class="btn btn-sm btn-primary" href="<?php echo htmlspecialchars($mapUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">View on Map</a>
            <?php if (!empty($park['website'])): ?>
              <a class="btn btn-sm btn-outline-primary ml-2" href="<?php echo htmlspecialchars($park['website'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Website</a>
            <?php endif; ?>
            <?php $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/','-', $park['name'])); $slugBase = trim($slugBase, '-'); $id = (int)($park['id'] ?? 0); if ($id > 0): ?>
              <a class="btn btn-sm btn-outline-secondary ml-2" href="/it-parks/<?php echo htmlspecialchars($slugBase.'-'.$id, ENT_QUOTES, 'UTF-8'); ?>">Details</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <?php
    // Pagination controls
    $queryParams = [];
    if ($searchQueryRaw !== '') { $queryParams['q'] = $searchQueryRaw; }
    if ($locality !== '') { $queryParams['locality'] = $locality; }
    if ($sort !== '') { $queryParams['sort'] = $sort; }
    $built = http_build_query($queryParams);
    $queryPrefix = ($built !== '' ? ('?' . htmlspecialchars($built, ENT_QUOTES, 'UTF-8') . '&') : '?');
  ?>
  <nav aria-label="Pagination"><ul class="pagination justify-content-center mt-3">
    <?php $prevDisabled = ($page <= 1) ? ' disabled' : ''; $nextDisabled = ($page >= $totalPages) ? ' disabled' : ''; ?>
    <li class="page-item<?php echo $prevDisabled; ?>"><a class="page-link js-page-link" href="<?php echo $queryPrefix; ?>page=1" aria-label="First">&laquo;</a></li>
    <li class="page-item<?php echo $prevDisabled; ?>"><a class="page-link js-page-link" href="<?php echo $queryPrefix; ?>page=<?php echo max(1, $page - 1); ?>" aria-label="Previous">&lsaquo;</a></li>
    <li class="page-item active"><span class="page-link"><?php echo $page; ?> / <?php echo $totalPages; ?></span></li>
    <li class="page-item<?php echo $nextDisabled; ?>"><a class="page-link js-page-link" href="<?php echo $queryPrefix; ?>page=<?php echo min($totalPages, $page + 1); ?>" aria-label="Next">&rsaquo;</a></li>
    <li class="page-item<?php echo $nextDisabled; ?>"><a class="page-link js-page-link" href="<?php echo $queryPrefix; ?>page=<?php echo $totalPages; ?>" aria-label="Last">&raquo;</a></li>
  </ul></nav>

  <div class="mt-3">
    <small class="text-muted">See an update or missing park? <a href="/contact-my-omr-team.php?subject=Suggest%20an%20IT%20Park%20Update%20for%20OMR">Send a correction</a>.</small>
  </div>
</div>

<div class="container maxw-1280">
  <?php include '../components/subscribe.php'; ?>
  <hr>
  <div class="row">
    <div class="col-12">
      <h3 class="mt-2" style="color:#0583D2;">Explore more OMR listings</h3>
      <ul class="list-inline">
        <li class="list-inline-item"><a href="/omr-listings/it-companies.php">IT Companies</a></li>
        <li class="list-inline-item"><a href="/omr-listings/hospitals.php">Hospitals</a></li>
        <li class="list-inline-item"><a href="/omr-listings/restaurants.php">Restaurants</a></li>
        <li class="list-inline-item"><a href="/omr-listings/schools.php">Schools</a></li>
        <li class="list-inline-item"><a href="/omr-listings/parks.php">Parks</a></li>
      </ul>
    </div>
  </div>
  <div class="mb-3"></div>
</div>

<script type="application/ld+json">
<?php echo json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'ItemList',
  'name' => 'IT Parks on OMR, Chennai',
  'itemListElement' => $itemList,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

<script type="application/ld+json">
<?php echo json_encode([
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
      'item' => [ '@id' => 'https://myomr.in/it-parks', 'name' => 'IT Parks in OMR' ]
    ]
  ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

<!--footer section begins -->
<?php include '../components/footer.php'; ?>
<!--footer section ends -->

<!-- UN SDG Floating Badges -->
<?php include '../components/sdg-badge.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
  function sendEvent(name, params) {
    try {
      if (typeof gtag === 'function') {
        gtag('event', name, params);
      } else if (window.dataLayer && Array.isArray(window.dataLayer)) {
        window.dataLayer.push(Object.assign({ event: name }, params));
      }
    } catch (e) { /* no-op */ }
  }

  document.querySelectorAll('.park-card a.btn.btn-sm.btn-primary').forEach(function(el) {
    el.addEventListener('click', function() {
      var card = this.closest('.park-card');
      var title = card ? (card.querySelector('.card-title')?.textContent || '') : '';
      sendEvent('map_click', { category: 'listings_it_parks', label: title });
    });
  });

  document.querySelectorAll('.park-card a.btn.btn-sm.btn-outline-primary').forEach(function(el) {
    el.addEventListener('click', function() {
      var card = this.closest('.park-card');
      var title = card ? (card.querySelector('.card-title')?.textContent || '') : '';
      sendEvent('website_click', { category: 'listings_it_parks', label: title });
    });
  });

  document.querySelectorAll('.js-page-link').forEach(function(el) {
    el.addEventListener('click', function() {
      sendEvent('pagination_click', { category: 'listings_it_parks', label: this.getAttribute('href') || '' });
    });
  });

  var form = document.getElementById('search-form');
  if (form) {
    form.addEventListener('submit', function() {
      var q = (form.querySelector('[name="q"]').value || '').trim();
      var locality = form.querySelector('[name="locality"]').value || '';
      var sort = form.querySelector('[name="sort"]').value || '';
      sendEvent('search_submit', { category: 'listings_it_parks', search_term: q, locality: locality, sort: sort });
    });
  }
});
</script>

</body>
</html>


