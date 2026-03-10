<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// $browser = get_browser(null, true); // Disabled for stability

require '../core/omr-connect.php';

//echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";

$locality = isset($_GET['locality']) ? trim($_GET['locality']) : '';
$sql = "SELECT slno, parkname, location, area, features, timings FROM omrparkslist";
if ($locality !== '') {
  $safe = '%' . $conn->real_escape_string($locality) . '%';
  $sql .= " WHERE location LIKE '" . $safe . "'";
}
$result = $conn->query($sql);

?>
<?php include '../weblog/log.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
$page_title          = 'Parks in OMR, Chennai | MyOMR';
$page_description    = 'Explore parks and green spaces along Old Mahabalipuram Road (OMR), Chennai. Find park names, locations, features, and timings for recreation.';
$canonical_url       = 'https://myomr.in/parks';
$og_title            = 'Parks in OMR, Chennai | MyOMR';
$og_description      = 'Explore parks and green spaces along Old Mahabalipuram Road (OMR), Chennai. Find park names, locations, features, and timings for recreation.';
$og_image            = 'https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg';
$og_url              = 'https://myomr.in/parks';
$twitter_title       = 'Parks in OMR, Chennai | MyOMR';
$twitter_description = 'Explore parks and green spaces along Old Mahabalipuram Road (OMR), Chennai. Find park names, locations, features, and timings for recreation.';
$twitter_image       = 'https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg';
?>
<?php $breadcrumbs = [
  ['https://myomr.in/','Home'],
  ['https://myomr.in/parks','Parks']
]; ?>
<?php include '../components/meta.php'; ?>
<?php $ga_custom_params = ['listing_type' => 'park']; include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>

<meta name=”robots” content=”index, follow”>
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
<link rel="stylesheet" href="../discover-myomr/pricing.css">
<script src="../discover-myomr/pricing.js"></script>
<link rel="canonical" href="https://myomr.in/parks" />
</head>
<body>
<?php include '../components/skip-link.php'; ?>
<?php include '../components/skip-link.php'; ?>
    <a href="https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi" class="float" target="_blank">
 <i class="fa fa-whatsapp my-float"></i>
</a>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v14.0" nonce="brAi0ji4"></script>

    <div class ="container maxw-1280" id="main-content" role="main">
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

<div class="container maxw-1280" id="main-content">
  <h1 class="text-center text-primary-omr">OMR Parks List - in and around OMR neighbourhood</h1>
  <form class="form-inline my-3" method="get" action="">
    <select name="locality" class="form-control mr-2">
      <option value="">All localities</option>
      <?php foreach (["Perungudi","Kandhanchavadi","Thoraipakkam","Karapakkam","Mettukuppam","Sholinganallur","Semmancheri","Navalur","Siruseri","Kelambakkam","Egattur","Perumbakkam"] as $loc): ?>
        <option value="<?php echo htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $locality===$loc?'selected':''; ?>><?php echo htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-primary">Filter</button>
  </form>
  <?php

if ($result->num_rows > 0) {
    echo "<div class='container'>";
    echo "<h2 style='text-align:center; margin-bottom:20px;'>Parks Along OMR Road, Chennai</h2>";
    $itemList = [];

    // Output data for each row
    while ($row = $result->fetch_assoc()) {
        echo "<div class='row row-item'>";
        
        // Serial Number
        echo "<div class='col-sm-1 col-serial'>";
        echo "<strong>" . $row["slno"] . "</strong>";
        echo "</div>";

        // Park Name and Location
        echo "<div class='col-sm-3 bg-primary-omr' style='font-weight:bold; padding:10px;'>";
        $nm = $row["parkname"];
        $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/','-',$nm));
        $slugBase = trim($slugBase, '-');
        $detail = '/parks/' . $slugBase . '-' . (int)$row['slno'];
        echo '<a style="color:#fff; text-decoration:underline;" href="' . htmlspecialchars($detail, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($nm, ENT_QUOTES, 'UTF-8') . '</a>';
        $itemList[] = [
          '@type' => 'ListItem',
          'position' => (int)$row['slno'],
          'url' => 'https://myomr.in' . $detail,
          'name' => $nm
        ];
        echo "<br><span class='muted-note'>" . $row["location"] . "</span>";
        echo "</div>";

        // Area
        echo "<div class='col-sm-2 bg-secondary-omr' style='padding:10px;'>";
        echo (!empty($row["area"])) ? $row["area"] : "N/A";
        echo "</div>";

        // Features
        echo "<div class='col-sm-4 bg-primary-omr' style='padding:10px;'>";
        echo $row["features"];
        echo "</div>";

        // Timings
        echo "<div class='col-sm-2 bg-secondary-omr' style='padding:10px;'>";
        echo $row["timings"];
        echo "</div>";

        echo "</div>"; // Close row
    }

    echo "</div>"; // Close container
    echo "<script type=\"application/ld+json\">";
    echo json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'ItemList',
      'name' => 'Parks on OMR, Chennai',
      'itemListElement' => $itemList,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    echo "</script>";
} else {
    echo "<p style='text-align:center;'>No parks found along OMR Road, Chennai.</p>";
}

// Close connection
$conn->close();
?>

    
    
    
 
</div>

<?php include '../discover-myomr/pricing.php'; ?>

    <!--footer section begins -->
    <?php include '../components/footer.php'; ?>
    <!--footer section ends -->
    
    <!-- UN SDG Floating Badges -->
    <?php include '../components/sdg-badge.php'; ?>
</body>
</html>
