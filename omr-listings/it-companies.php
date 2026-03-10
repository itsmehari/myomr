<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// $browser = get_browser(null, true); // Disabled for stability

require '../core/omr-connect.php';
require_once __DIR__ . '/components/generic-list-renderer.php';
require_once __DIR__ . '/directory-config.php';
require_once '../core/cache-helpers.php';
omr_output_cache_start(omr_cache_key_from_request('it_companies:'), 300);
// Create featured table if missing (for sponsored slots)
$conn->query("CREATE TABLE IF NOT EXISTS omr_it_companies_featured (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_slno INT NOT NULL,
  rank_position INT NOT NULL DEFAULT 1,
  blurb VARCHAR(400) DEFAULT NULL,
  cta_text VARCHAR(80) DEFAULT NULL,
  cta_url VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(company_slno)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

//echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";

// Search and pagination (9 per page), with prepared statements
$searchQueryRaw = isset($_GET['q']) ? trim($_GET['q']) : '';
$searchQuery = $searchQueryRaw;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 9;
$offset = ($page - 1) * $perPage;

// Locality filter (derived from address) and sorting
$allowedLocalities = [
    'Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Mettukuppam','Sholinganallur',
    'Semmancheri','Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam'
];
$localityRaw = isset($_GET['locality']) ? trim($_GET['locality']) : '';
$locality = in_array($localityRaw, $allowedLocalities, true) ? $localityRaw : '';

$sortRaw = isset($_GET['sort']) ? trim($_GET['sort']) : 'az';
$allowedSorts = ['az','newest'];
$sort = in_array($sortRaw, $allowedSorts, true) ? $sortRaw : 'az';
$orderSql = ($sort === 'newest') ? ' ORDER BY slno DESC ' : ' ORDER BY company_name ASC ';

// Count total
$whereSql = '';
$params = [];
$types = '';
if ($searchQuery !== '') {
    $whereSql = " WHERE (company_name LIKE ? OR address LIKE ? OR industry_type LIKE ?) ";
    $like = "%{$searchQuery}%";
    $params = [$like, $like, $like];
    $types = 'sss';
}
if ($locality !== '') {
    $whereSql .= ($whereSql === '' ? ' WHERE ' : ' AND ') . " address LIKE ? ";
    $params[] = "%{$locality}%";
    $types .= 's';
}

$countSql = "SELECT COUNT(*) AS total FROM omr_it_companies" . $whereSql;
$countStmt = $conn->prepare($countSql);
if ($countStmt) {
    if ($types !== '') { $countStmt->bind_param($types, ...$params); }
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalRow = $countResult ? $countResult->fetch_assoc() : ['total' => 0];
    $totalResults = (int)$totalRow['total'];
    $countStmt->close();
} else {
    $totalResults = 0;
}

// Generic renderer result (authoritative for list + pagination)
$cfg = get_directory_config('it-companies');
$res = render_directory_list($cfg, ['q'=>$searchQueryRaw,'locality'=>$locality,'sort'=>$sort], $page, $perPage);
$totalResults = $res['total'];
$totalPages = $res['pages'];
$page = $res['page'];
$offset = ($page - 1) * $perPage;

// Fetch paginated results
$dataSql = "SELECT slno, company_name, address, contact, industry_type, verified 
            FROM omr_it_companies" . $whereSql . $orderSql . " LIMIT ? OFFSET ?";
$dataStmt = $conn->prepare($dataSql);
if ($dataStmt) {
    if ($types !== '') {
        // add limit and offset bindings
        $typesWithLimits = $types . 'ii';
        $paramsWithLimits = array_merge($params, [$perPage, $offset]);
        $dataStmt->bind_param($typesWithLimits, ...$paramsWithLimits);
    } else {
        $dataStmt->bind_param('ii', $perPage, $offset);
    }
    $dataStmt->execute();
    $result = $dataStmt->get_result();
} else {
    $result = false;
}

?>
<?php include 'weblog/log.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
$page_title          = 'IT Companies in OMR, Chennai | MyOMR';
$page_description    = 'Find top IT companies along Old Mahabalipuram Road (OMR), Chennai. Explore company names, addresses, industry types, and contact details for your business needs.';
$canonical_url       = 'https://myomr.in/it-companies';
$og_title            = 'IT Companies in OMR, Chennai | MyOMR';
$og_description      = 'Find top IT companies along Old Mahabalipuram Road (OMR), Chennai. Explore company names, addresses, industry types, and contact details for your business needs.';
$og_image            = 'https://myomr.in/My-OMR-Logo.png';
$og_url              = 'https://myomr.in/it-companies';
$twitter_title       = 'IT Companies in OMR, Chennai | MyOMR';
$twitter_description = 'Find top IT companies along Old Mahabalipuram Road (OMR), Chennai. Explore company names, addresses, industry types, and contact details for your business needs.';
$twitter_image       = 'https://myomr.in/My-OMR-Logo.png';
?>
<?php $breadcrumbs = [
  ['https://myomr.in/','Home'],
  ['https://myomr.in/it-companies','IT Companies']
]; ?>
<?php include '../components/meta.php'; ?>
<?php $ga_custom_params = ['listing_type' => 'it_company']; include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
<meta name="robots" content="index, follow">
<style>
.hover-me:hover
{
background-color:#0583D2;
cursor: pointer;
opacity: 0.5;

}
</style>
<style>
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333;
}

li {
  float: left;
}

li a {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

li a:hover:not(.active) {
  background-color: #111;
}

.active {
  background-color: #04AA6D;
}
</style>
<script async defer data-pin-hover="true" data-pin-tall="true" data-pin-round="true" src="//assets.pinterest.com/js/pinit.js"></script>

<link rel="stylesheet" href="social-style.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&display=swap" rel="stylesheet">
  <style>
  .fakeimg {
    height: 200px;
    background: #aaa;
  }
  .jumbotron
  {
    background-color:#CCFF33;
    font-family: 'Libre Baskerville', serif;
  }
  .button {
  background-color: #D62828; /* Reddish Shade */
  border: none;
  color: #EAE287;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
}
.button1 {background-color: #F77F00;color: #EAE287;} /* Dark Yellowish Scheme */
.button1 a {color: #EAE287;} /* Dark Yellowish Scheme */
.button2 {background-color: #008CBA;} /* Blue */
.button3 {background-color: #f44336;} /* Red */
.button4 {background-color: #e7e7e7; color: black;} /* Gray */
.button5 {background-color: #555555;} /* Black */
  </style>
  <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
  
  <!-- Font Awesome -->
<!-- Font, Bootstrap, MDB, FA v6 already included by components/head-resources.php -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400&display=swap" rel="stylesheet">
<style>
h2{
font-family: 'Playfair Display', serif;
color: #4c516D;
}
body { font-family: 'Poppins', sans-serif; }
</style>
<!-- Duplicate Font Awesome (v4) removed; FA v6 loaded via head-resources -->
<?php if ($page > 1): ?>
<link rel="prev" href="https://myomr.in/it-companies?page=<?php echo ($page - 1); ?>">
<?php endif; ?>
<?php if ($page < $totalPages): ?>
<link rel="next" href="https://myomr.in/it-companies?page=<?php echo ($page + 1); ?>">
<?php endif; ?>
</head>
<style>
    .float{
	position:fixed;
	width:60px;
	height:60px;
	bottom:40px;
	right:40px;
	background-color:#25d366;
	color:#FFF;
	border-radius:50px;
	text-align:center;
  font-size:30px;
	box-shadow: 2px 2px 3px #999;
  z-index:100;
  animation: myAnim 2s ease 0s 1 normal forwards;
}

.my-float{
	margin-top:16px;
	
}

@keyframes myAnim {
	0% {
		animation-timing-function: ease-in;
		opacity: 1;
		transform: translateY(-45px);
	}

	24% {
		opacity: 1;
	}

	40% {
		animation-timing-function: ease-in;
		transform: translateY(-24px);
	}

	65% {
		animation-timing-function: ease-in;
		transform: translateY(-12px);
	}

	82% {
		animation-timing-function: ease-in;
		transform: translateY(-6px);
	}

	93% {
		animation-timing-function: ease-in;
		transform: translateY(-4px);
	}

	25%,
	55%,
	75%,
	87% {
		animation-timing-function: ease-out;
		transform: translateY(0px);
	}

	100% {
		animation-timing-function: ease-out;
		opacity: 1;
		transform: translateY(0px);
	}
}

</style>
<body>
<?php include '../components/skip-link.php'; ?>
    <a href="https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi" class="float" target="_blank">
 <i class="fa fa-whatsapp my-float"></i>
</a>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v14.0" nonce="brAi0ji4"></script>

    <div class ="container">
<div class ="row">
<!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container">
  <div class="tradingview-widget-container__widget"></div>
  <div class="tradingview-widget-copyright"><a href="https://in.tradingview.com/markets/" rel="noopener" target="_blank"><span class="blue-text">Markets</span></a> by TradingView</div>
  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
  {
  "symbols": [
    {
      "proName": "FOREXCOM:SPXUSD",
      "title": "S&P 500"
    },
    {
      "proName": "FOREXCOM:NSXUSD",
      "title": "US 100"
    },
    {
      "proName": "FX_IDC:EURUSD",
      "title": "EUR/USD"
    },
    {
      "proName": "BITSTAMP:BTCUSD",
      "title": "Bitcoin"
    },
    {
      "proName": "BITSTAMP:ETHUSD",
      "title": "Ethereum"
    }
  ],
  "showSymbolLogo": true,
  "colorTheme": "light",
  "isTransparent": false,
  "displayMode": "adaptive",
  "locale": "in"
}
  </script>
</div>
<!-- TradingView Widget END -->
</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/components/omr-listings-nav.php'; ?>

<div class="container maxw-1280">
  <h1 class="text-center text-primary-omr">IT Companies on OMR, Chennai</h1>
  <p style="text-align:center; margin-bottom:18px;">Discover IT firms along Old Mahabalipuram Road. Search, filter and connect.</p>
  <?php if (!empty($locality)): ?>
    <div class="text-center mb-2">
      <small>Nearby IT Parks: <a href="/omr-listings/it-parks-in-omr.php?locality=<?php echo urlencode($locality); ?>">Browse parks in <?php echo htmlspecialchars($locality, ENT_QUOTES, 'UTF-8'); ?></a> or <a href="/it-parks">see all</a>.</small>
    </div>
  <?php endif; ?>

  <form id="search-form" method="get" action="" class="mb-3">
    <div class="form-row">
      <div class="col-12 col-sm-6 mb-2">
        <input type="text" name="q" value="<?php echo htmlspecialchars($searchQueryRaw, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" placeholder="Search by company, address or industry type">
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
      <a href="/omr-listings/get-listed.php" class="btn btn-sm btn-success">Get your company listed</a>
      <a href="/omr-listings/get-listed.php#pricing" class="btn btn-sm btn-outline-success ml-2">Get Verified</a>
    </div>
  </div>

  <?php
  // Featured slots (sponsored)
  $featuredSql = "SELECT f.id, f.rank_position, f.blurb, f.cta_text, f.cta_url, f.start_at, f.end_at, c.slno, c.company_name, c.address
                  FROM omr_it_companies_featured f
                  JOIN omr_it_companies c ON c.slno = f.company_slno
                  WHERE (f.start_at IS NULL OR f.start_at <= NOW())
                    AND (f.end_at IS NULL OR f.end_at >= NOW())
                  ORDER BY f.rank_position ASC, f.start_at DESC
                  LIMIT 6";
  $featuredRes = $conn->query($featuredSql);
  if ($featuredRes && $featuredRes->num_rows > 0) {
      echo "<div class='mb-3'><h3 style='color:#0583D2;'>Featured IT Companies</h3>";
      echo "<div class='row'>";
      while ($fr = $featuredRes->fetch_assoc()) {
          $fname = htmlspecialchars($fr['company_name'], ENT_QUOTES, 'UTF-8');
          $faddr = htmlspecialchars($fr['address'], ENT_QUOTES, 'UTF-8');
          $fblurb = htmlspecialchars($fr['blurb'] ?? '', ENT_QUOTES, 'UTF-8');
          $fctaText = htmlspecialchars($fr['cta_text'] ?? 'Learn more', ENT_QUOTES, 'UTF-8');
          $fslugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $fr['company_name']));
          $fslugBase = trim($fslugBase, '-');
          $fdetail = '/it-companies/' . $fslugBase . '-' . ((int)$fr['slno']);
          $fctaUrl = htmlspecialchars($fr['cta_url'] ?? $fdetail, ENT_QUOTES, 'UTF-8');
          echo "<div class='col-md-4 mb-3'>";
          echo "<div class='card h-100'>";
          echo "<div class='card-body'>";
          echo "<h5 class='card-title'>{$fname}</h5>";
          echo "<p class='card-text'><small class='text-muted'>{$faddr}</small></p>";
          if ($fblurb !== '') { echo "<p class='card-text'>{$fblurb}</p>"; }
          echo "<a class='btn btn-primary' href='{$fctaUrl}' target='_blank' rel='noopener'>{$fctaText}</a>";
          echo "</div></div></div>";
      }
      echo "</div></div>";
  }
  ?>

  <?php
if (!empty($res['items'])) {
    echo "<div class='container'>";
    echo "<h2 style='text-align:center; margin-bottom:20px;'>IT Companies Along OMR Road, Chennai</h2>";
    $itemList = [];
    $positionCounter = $offset + 1;

    // Output data for each row
    foreach ($res['items'] as $row) {
        $companyId = (int)$row['slno'];
        $companyName = $row['company_name'];
        $companyAddress = $row['address'];
        $mapsQuery = urlencode($companyName . ' ' . $companyAddress);
        $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . $mapsQuery;
        $itemUrl = 'https://myomr.in/omr-listings/it-companies.php#company-' . $companyId;
        $itemList[] = [
            '@type' => 'ListItem',
            'position' => $positionCounter++,
            'url' => $itemUrl,
            'name' => $companyName,
        ];

        echo "<div id='company-{$companyId}' class='row row-item'>";

        // Serial Number
        echo "<div class='col-sm-1 col-serial'>";
        echo "<strong>" . $row["slno"] . "</strong>";
        echo "</div>";

        // Company Name and Address
        echo "<div class='col-sm-5 bg-primary-omr' style='font-weight:bold; padding:10px;'>";
        $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $companyName));
        $slugBase = trim($slugBase, '-');
        $detailUrl = '/it-companies/' . $slugBase . '-' . $companyId;
        echo "<a style='color:#fff; text-decoration:underline;' href='" . $detailUrl . "'>" . htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') . "</a>";
        if (!empty($row['verified'])) { echo " <span class='badge badge-verified'>Verified</span>"; }
        // locality badge
        $badgeLocality = '';
        foreach (['Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Mettukuppam','Sholinganallur','Semmancheri','Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam'] as $locBadge) {
            if (stripos($companyAddress, $locBadge) !== false) { $badgeLocality = $locBadge; break; }
        }
        if ($badgeLocality !== '') {
            echo " <span class='badge badge-locality'>" . htmlspecialchars($badgeLocality, ENT_QUOTES, 'UTF-8') . "</span>";
        }
        echo "<br><span class='muted-note'>" . htmlspecialchars($companyAddress, ENT_QUOTES, 'UTF-8') . "</span>";
        echo "<div class='mt-2'>";
        echo "<a class='btn btn-sm btn-light js-map-click' aria-label='View " . htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') . " on Google Maps' data-company='" . htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') . "' href='" . $mapsUrl . "' target='_blank' rel='noopener'>View on Map</a> ";
        $enquireSubject = urlencode('Listing Enquiry: ' . $companyName . ' (OMR IT Companies)');
        echo "<a class='btn btn-sm btn-warning js-enquire-click' aria-label='Enquire about " . htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') . "' data-company='" . htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') . "' href='/contact-my-omr-team.php?subject=" . $enquireSubject . "'>Enquire</a>";
        echo "</div>";
        echo "</div>";

        // Contact
        echo "<div class='col-sm-3 bg-secondary-omr' style='padding:10px;'>";
        echo (!empty($row["contact"])) ? htmlspecialchars($row["contact"], ENT_QUOTES, 'UTF-8') : "N/A";
        echo "</div>";

        // Industry Type
        echo "<div class='col-sm-3 bg-primary-omr' style='padding:10px;'>";
        echo htmlspecialchars($row[$cfg['fields']['industry_type']] ?? '', ENT_QUOTES, 'UTF-8');
        echo "</div>";

        echo "</div>"; // Close row
    }
    
    // Pagination
    // Build pagination prefix preserving filters
    $queryParams = [];
    if ($searchQueryRaw !== '') { $queryParams['q'] = $searchQueryRaw; }
    if ($locality !== '') { $queryParams['locality'] = $locality; }
    if ($sort !== '') { $queryParams['sort'] = $sort; }
    $built = http_build_query($queryParams);
    $queryPrefix = ($built !== '' ? ('?' . htmlspecialchars($built, ENT_QUOTES, 'UTF-8') . '&') : '?');
    echo "<nav aria-label='Pagination'><ul class='pagination justify-content-center mt-3'>";
    $prevDisabled = ($page <= 1) ? ' disabled' : '';
    $nextDisabled = ($page >= $totalPages) ? ' disabled' : '';
    $prevPage = max(1, $page - 1);
    $nextPage = min($totalPages, $page + 1);
    echo "<li class='page-item{$prevDisabled}'><a class='page-link js-page-link' href='{$queryPrefix}page=1' aria-label='First'>&laquo;</a></li>";
    echo "<li class='page-item{$prevDisabled}'><a class='page-link js-page-link' href='{$queryPrefix}page={$prevPage}' aria-label='Previous'>&lsaquo;</a></li>";
    // Current page indicator
    echo "<li class='page-item active'><span class='page-link'>{$page} / {$totalPages}</span></li>";
    echo "<li class='page-item{$nextDisabled}'><a class='page-link js-page-link' href='{$queryPrefix}page={$nextPage}' aria-label='Next'>&rsaquo;</a></li>";
    echo "<li class='page-item{$nextDisabled}'><a class='page-link js-page-link' href='{$queryPrefix}page={$totalPages}' aria-label='Last'>&raquo;</a></li>";
    echo "</ul></nav>";

    // JSON-LD ItemList schema
    echo "<script type=\"application/ld+json\">";
    echo json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'IT Companies on OMR, Chennai',
        'itemListElement' => $itemList,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    echo "</script>";

    echo "</div>"; // Close container
} else {
    echo "<p style='text-align:center;'>No IT companies found along OMR Road, Chennai.</p>";
}

// Close connection
$conn->close();
?>
    
    
 
</div>

<div class="container maxw-1280">
  <?php include '../components/subscribe.php'; ?>
  <hr>
  <div class="row">
    <div class="col-12">
      <h3 class="mt-2" style="color:#0583D2;">Explore more OMR listings</h3>
      <ul class="list-inline">
        <li class="list-inline-item"><a href="/omr-listings/banks.php">Banks</a></li>
        <li class="list-inline-item"><a href="/omr-listings/hospitals.php">Hospitals</a></li>
        <li class="list-inline-item"><a href="/omr-listings/restaurants.php">Restaurants</a></li>
        <li class="list-inline-item"><a href="/omr-listings/schools.php">Schools</a></li>
        <li class="list-inline-item"><a href="/omr-listings/parks.php">Parks</a></li>
        <li class="list-inline-item"><a href="/omr-listings/industries.php">Industries</a></li>
      </ul>
    </div>
  </div>
  <div class="mb-3"></div>
</div>

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

  document.querySelectorAll('.js-map-click').forEach(function(el) {
    el.addEventListener('click', function() {
      sendEvent('map_click', {
        category: 'listings_it_companies',
        label: this.dataset.company || ''
      });
    });
  });

  document.querySelectorAll('.js-enquire-click').forEach(function(el) {
    el.addEventListener('click', function() {
      sendEvent('enquire_click', {
        category: 'listings_it_companies',
        label: this.dataset.company || ''
      });
    });
  });

  document.querySelectorAll('.js-page-link').forEach(function(el) {
    el.addEventListener('click', function() {
      sendEvent('pagination_click', {
        category: 'listings_it_companies',
        label: this.getAttribute('href') || ''
      });
    });
  });

  var form = document.getElementById('search-form');
  if (form) {
    form.addEventListener('submit', function() {
      var q = (form.querySelector('[name="q"]').value || '').trim();
      var locality = form.querySelector('[name="locality"]').value || '';
      var sort = form.querySelector('[name="sort"]').value || '';
      sendEvent('search_submit', {
        category: 'listings_it_companies',
        search_term: q,
        locality: locality,
        sort: sort
      });
    });
  }
});
</script>

<script type="application/ld+json">
<?php
// BreadcrumbList for IT Companies list
$breadcrumbs = [
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
    ]
  ]
];
echo json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
</script>

<script type="application/ld+json">
<?php
$faq = [
  '@context' => 'https://schema.org',
  '@type' => 'FAQPage',
  'mainEntity' => [
    [
      '@type' => 'Question',
      'name' => 'Which IT companies are on OMR, Chennai?',
      'acceptedAnswer' => [
        '@type' => 'Answer',
        'text' => 'OMR hosts a wide range of IT companies from startups to MNCs across Perungudi, Kandhanchavadi, Thoraipakkam, Sholinganallur, Navalur, Siruseri and beyond. Use the search and filters to find specific companies.'
      ]
    ],
    [
      '@type' => 'Question',
      'name' => 'How do I contact an IT company listed here?',
      'acceptedAnswer' => [
        '@type' => 'Answer',
        'text' => 'Click “View on Map” for directions or “Enquire” to reach our team with your request. You can also use the contact details shown in each row when available.'
      ]
    ],
    [
      '@type' => 'Question',
      'name' => 'How can my company get listed or updated on this page?',
      'acceptedAnswer' => [
        '@type' => 'Answer',
        'text' => 'Click “Get your company listed” and share your details. Our team will verify and publish your listing. Featured placement is also available.'
      ]
    ]
  ]
];
echo json_encode($faq, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
</script>

<!--footer section begins -->
<?php include '../components/footer.php'; ?>
<!--footer section ends -->

<!-- UN SDG Floating Badges -->
<?php include '../components/sdg-badge.php'; ?>
</body>
</html>
