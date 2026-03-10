<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// $browser = get_browser(null, true); // Disabled for stability

require '../core/omr-connect.php';

// Pagination (9 per page)
$perPage = 9;
$page    = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset  = ($page - 1) * $perPage;

// Total count
$countResult = $conn->query("SELECT COUNT(*) AS total FROM omrschoolslist");
$totalResults = $countResult ? (int)$countResult->fetch_assoc()['total'] : 0;
$totalPages = max(1, (int)ceil($totalResults / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

// Paginated query
$stmt = $conn->prepare("SELECT slno, schoolname, address, contact, landmark FROM omrschoolslist ORDER BY slno ASC LIMIT ? OFFSET ?");
$stmt->bind_param('ii', $perPage, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>
<?php include 'weblog/log.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
$page_title          = 'Schools in OMR, Chennai | MyOMR';
$page_description    = 'Find schools along Old Mahabalipuram Road (OMR), Chennai. Get school names, addresses, contacts, and landmarks for your child's education.';
$canonical_url       = 'https://myomr.in/schools';
$og_title            = 'Schools in OMR, Chennai | MyOMR';
$og_description      = 'Find schools along Old Mahabalipuram Road (OMR), Chennai. Get school names, addresses, contacts, and landmarks for your child's education.';
$og_image            = 'https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg';
$og_url              = 'https://myomr.in/schools';
$twitter_title       = 'Schools in OMR, Chennai | MyOMR';
$twitter_description = 'Find schools along Old Mahabalipuram Road (OMR), Chennai. Get school names, addresses, contacts, and landmarks for your child's education.';
$twitter_image       = 'https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg';
?>
<?php $breadcrumbs = [
  ['https://myomr.in/','Home'],
  ['https://myomr.in/schools','Schools']
]; ?>
<?php include '../components/meta.php'; ?>
<?php $ga_custom_params = ['listing_type' => 'school']; include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
<meta name="robots" content="index, follow">
<link rel="stylesheet" href="/assets/css/footer.css">
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
  <!-- Font, Bootstrap, MDB, FA v6 already included by components/head-resources.php -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
<style>
h2{
font-family: 'Playfair Display', serif;
color: #4c516D;
}
</style>
<!-- Duplicate Font Awesome (v4) removed; FA v6 loaded via head-resources -->
<?php if ($page > 1): ?>
<link rel="prev" href="https://myomr.in/schools?page=<?php echo ($page - 1); ?>">
<?php endif; ?>
<?php if ($page < $totalPages): ?>
<link rel="next" href="https://myomr.in/schools?page=<?php echo ($page + 1); ?>">
<?php endif; ?>

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

<script src="modal.js" defer></script>
<link rel="stylesheet" href="modal.css">
<link rel="stylesheet" href="subscribe.css">

</head>
<body>
    <!--<button onclick="showModal()">Open Modal</button>-->
    
    <!-- Modal Section -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Unlock Exclusive Access to MyOMR</h2>
        <ul>
            <li>✔ Get the latest OMR news & updates</li>
            <li>✔ Find local job listings easily</li>
            <li>✔ Discover events & activities near you</li>
            <li>✔ Browse business directories & real estate listings</li>
            <li>✔ Boost your brand with targeted advertising</li>
            <li>✔ Connect with the OMR community in one platform</li>
        </ul>
        <button onclick="window.location.href='tel:+919884785845'">Call Us for Discussion</button>
    </div>
</div>
<!-- Modal Section Ends -->


    <a href="https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi" class="float" target="_blank">
 <i class="fa fa-whatsapp my-float"></i>
</a>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v14.0" nonce="brAi0ji4"></script>

    
<div class ="container maxw-1280">
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
  <h1 style="text-align:center; color:#0583D2;">OMR Schools List - in and around OMR neighbourhood</h1>
  
  <!-- Introductory Content Start -->
  <div class="row">
    <div class="col-12">
      <p class="mt-4" style="text-align:justify;">
        Old Mahabalipuram Road (OMR), often referred to as the IT Corridor of Chennai, has experienced significant growth over the past decade, evolving into a vibrant hub that seamlessly blends technological advancement with residential serenity. This rapid development has led to the establishment of numerous esteemed educational institutions, catering to the diverse needs of the community.
      </p>
      <p style="text-align:justify;">
        The schools along OMR are renowned for their commitment to academic excellence, holistic development, and state-of-the-art facilities. They offer a variety of curricula, including CBSE, ICSE, and international programs, ensuring that parents have a wide array of choices to best suit their children's educational requirements.
      </p>
      <p style="text-align:justify;">
        Whether you're seeking institutions with a strong emphasis on traditional values or those that incorporate innovative teaching methodologies, the schools in the OMR region provide an ideal environment for nurturing young minds.
      </p>
    </div>
  </div>
  <!-- Introductory Content End -->
  
  <!-- SDG Education Initiative Callout -->
  <div class="row mt-4 mb-4">
    <div class="col-12">
      <div class="alert alert-success" style="background: linear-gradient(135deg, #1e3a1e 0%, #3d7a3d 50%, #4CAF50 100%); border: none; border-radius: 15px; padding: 30px;">
        <div class="row align-items-center">
          <div class="col-md-2 text-center mb-3 mb-md-0">
            <i class="fas fa-seedling fa-4x text-white"></i>
          </div>
          <div class="col-md-8">
            <h4 class="text-white mb-2"><i class="fas fa-star"></i> Join MyOMR's SDG Education Initiative</h4>
            <p class="text-white mb-0" style="font-size: 1.1rem;">
              <strong>Making OMR India's First SDG-Aware Community!</strong> Help spread UN Sustainable Development Goals awareness in schools. 
              Teachers, parents, and students—everyone has a role to play.
            </p>
          </div>
          <div class="col-md-2 text-center mt-3 mt-md-0">
            <a href="/discover-myomr/sdg-education-schools.php" class="btn btn-light btn-lg" style="background: white; color: #2e7d32; font-weight: 600; border-radius: 25px;">
              Learn More <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- SDG Education Initiative Callout End -->
  

<?php

$itemList = [];
$positionCounter = $offset + 1;
if ($result->num_rows > 0) {
  echo "";
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<div class='row row-item'><div class='col-sm-1 col-serial'><br>";
    echo $row["slno"];
    echo "<br></div>";
    echo "<div class='col-sm-7 bg-primary-omr' style='font-weight:bold; padding:10px;'><br>";
    $nm = $row["schoolname"];
    $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/','-',$nm));
    $slugBase = trim($slugBase, '-');
    $detail = '/schools/' . $slugBase . '-' . (int)$row['slno'];
    $itemList[] = ['@type' => 'ListItem', 'position' => $positionCounter++, 'url' => 'https://myomr.in' . $detail, 'name' => $nm];
    echo '<a style="color:#fff; text-decoration:underline;" href="' . htmlspecialchars($detail, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($nm, ENT_QUOTES, 'UTF-8') . '</a>';
    echo "<br>";
    echo "<span class='muted-note'>";
    echo $row["address"];
    echo "<br></span>";
    echo "<br></div>";
    echo "<div class='col-sm-4 bg-secondary-omr' style='padding:10px;'><br>";
    echo $row["contact"];
    echo "<br></div>";
    echo "</div>";
  }
} else {
  echo "<p style='text-align:center;'>No schools found.</p>";
}
$stmt->close();

// ItemList JSON-LD schema
if (!empty($itemList)):
?>
<script type="application/ld+json">
<?php echo json_encode(['@context'=>'https://schema.org','@type'=>'ItemList','name'=>'Schools in OMR, Chennai','itemListElement'=>$itemList], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
<?php endif; ?>

<?php
// Pagination UI
if ($totalPages > 1):
    $prevPage = max(1, $page - 1);
    $nextPage = min($totalPages, $page + 1);
?>
<nav aria-label="Schools pagination" class="mt-4">
  <ul class="pagination justify-content-center">
    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
      <a class="page-link" href="?page=1" aria-label="First">&laquo;</a>
    </li>
    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
      <a class="page-link" href="?page=<?php echo $prevPage; ?>" aria-label="Previous">&lsaquo;</a>
    </li>
    <li class="page-item active">
      <span class="page-link"><?php echo $page; ?> / <?php echo $totalPages; ?></span>
    </li>
    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
      <a class="page-link" href="?page=<?php echo $nextPage; ?>" aria-label="Next">&rsaquo;</a>
    </li>
    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
      <a class="page-link" href="?page=<?php echo $totalPages; ?>" aria-label="Last">&raquo;</a>
    </li>
  </ul>
</nav>
<?php
endif;
$conn->close();
?>

    
    
 <!-- Subscribe Section -->
<section class="subscribe-section">
    <h2>Subscribe</h2>
    <form action="subscribe.php" method="POST" class="subscribe-container">
        <input type="email" name="email" placeholder="you@email.com" class="subscribe-input" required>
        <button type="submit" class="subscribe-button">Submit</button>
    </form>
    <p class="subscribe-text">
        By clicking "Subscribe" you agree to MyOMR Privacy Policy and consent to MyOMR using your contact data for newsletter purposes.
    </p>
</section>   
 
</div>

    <!--footer section begins -->
    <?php include '../components/footer.php'; ?>
    <!--footer section ends -->
    
    <!-- UN SDG Floating Badges -->
    <?php include '../components/sdg-badge.php'; ?>
</body>
</html>
