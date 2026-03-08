<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// $browser = get_browser(null, true); // Disabled for stability

require '../core/omr-connect.php';

//echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";

$sql = "SELECT slno, schoolname, address, contact, landmark FROM omrschoolslist";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
<?php
$page_title          = 'Best Schools in OMR, Chennai | MyOMR';
$page_description    = 'Discover the best schools along Old Mahabalipuram Road (OMR), Chennai. Find top-rated institutions, addresses, contact details, and more.';
$canonical_url       = 'https://myomr.in/best-schools';
$og_title            = 'Best Schools in OMR, Chennai | MyOMR';
$og_description      = 'Discover the best schools along Old Mahabalipuram Road (OMR), Chennai. Find top-rated institutions, addresses, contact details, and more.';
$og_image            = 'https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg';
$og_url              = 'https://myomr.in/best-schools';
$twitter_title       = 'Best Schools in OMR, Chennai | MyOMR';
$twitter_description = 'Discover the best schools along Old Mahabalipuram Road (OMR), Chennai. Find top-rated institutions, addresses, contact details, and more.';
$twitter_image       = 'https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg';
?>
<?php $breadcrumbs = [
  ['https://myomr.in/','Home'],
  ['https://myomr.in/omr-listings/best-schools.php','Best Schools']
]; ?>
<?php include '../components/meta.php'; ?>
<?php include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>

<!-- Popper JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
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
</head>
<body>
<?php include '../components/main-nav.php'; ?>
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

<div class="container">
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

<?php

if ($result->num_rows > 0) {
  echo "";
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<div class='row'><div class='col-sm-1 hover-me' style='background-color:#b8e3ff;  border-top: 2px solid white;border-bottom: 2px solid white;'><br>";
    echo $row["slno"];
    echo "<br></div>";
    echo "<div class='col-sm-7 hover-me' style='font-weight: bold;background-color:#0583D2;color:#fff;  border-top: 2px solid white;border-bottom: 2px solid white;'><br>";
    echo $row["schoolname"];
    echo "<br>";
    echo "<span style='color:#cccccc;font-weight: normal;'>";
    echo $row["address"];
    echo "<br></span>";
    echo "<br></div>";
    echo "<div class='col-sm-4 hover-me' style='background-color:#386FA4;color:#fff;  border-top: 2px solid white;border-bottom: 2px solid white;'><br>";
    echo $row["contact"];
    echo "<br></div>";
    echo "</div>";
  }
  echo "</table>";
} else {
  echo "0 results";
}
$conn->close();
?>

    
    
    
 
</div>
    <!--footer section begins -->
    <?php include '../components/footer.php'; ?>
    <!--footer section ends -->
    
    <!-- UN SDG Floating Badges -->
    <?php include '../components/sdg-badge.php'; ?>
</body>
</html>
