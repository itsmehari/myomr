<?php
require 'omr-connect.php';

//echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";

$browser = get_browser(null, true);
//print_r($browser);

$sql = "SELECT slno, schoolname, address, contact, landmark FROM omrschoolslist";
$result = $conn->query($sql);
?>
<?php include 'weblog/log.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../components/analytics.php'; ?>

<title>Search for Local Businesses, Shops, Offices, Schools, ATMS in OMR road | Search | Business | Commercial Portal of OMR - Old Mahabalipuram Road</title>
<link rel="canonical" href="https://myomr.in/omr-road-database-list.php" />

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="News, Events, Happenings in and around Old Mahabalipuram Road, You can find news articles, event coverages, search for businesses, post advertisments related to companies, businesses, events and happenings in our website.">
<meta name="keywords" content="Old Mahabalipuram Road, OMR Road, OMR News, My OMR, Perungudi, SRP Tools, Kandhanchavadi, Thuraipakkam, Karapakkam, Mettukuppam, Dollar Stop, Sholinganallur, Navalur, Kelambakkam.">
<meta name="author" content="Krishnan">

<meta property="og:type" content="article" />
<meta name=”robots” content=”index, follow”>
<meta property="og:title" content="Old Mahabalipuram Road news, Search, Events, Happenings, Photographs" />
<meta property="og:description" content="home page of old mahabalipuram road, OMR website,which hosts several features for its user base, especially from chennai, Tamilnadu. We offer special coverage on the News, Happenings, Events, Businesses and people of old mahabalipuram road and its
neighbouring Community" />
<meta property="og:image" content="https://myomr.in/My-OMR-Logo.png" />
<meta property="og:url" content="https://myomr.in/" />
<meta property="og:site_name" content="My OMR Old Mahabalipuram Road." />
<meta property="og:locale" content="en_US" />
<meta property="og:locale:alternate" content="ta_IN" />

<meta name="twitter:title" content="My OMR - Old Mahabalipuram Road News, Events, Images, Happenings, Search, Business Website">
<meta name="twitter:description" content="in this page you can find news, events, images, happenings, updates, local business information of OMR Road, Old Mahabalipuram Road and its Surroundings">
<meta name="twitter:image" content="https://myomr.in/My-OMR-Logo.png">
<meta name="twitter:site" content="@MyomrNews">
<meta name="twitter:creator" content="@MyomrNews">
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="social-style.css">
    
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

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
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
  rel="stylesheet"
/>
<!-- Google Fonts -->
<link
  href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
  rel="stylesheet"
/>
<!-- MDB -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.4.0/mdb.min.css"
  rel="stylesheet"
/>

<!-- MDB -->
<script
  type="text/javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.4.0/mdb.min.js"
></script>
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

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
    <?php include '../components/main-nav.php'; ?>
    <a href="https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi" class="float" target="_blank">
 <i class="fa fa-whatsapp my-float"></i>
</a>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v14.0" nonce="brAi0ji4"></script>

    <a href="commonlogin.php">Login to your Admin panel</a>
    
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

<div class="container">
    <div class="row">
<ul>
  <li><a class="active" href="index.php">Home</a></li>
  <li><a href="omr-listings/schools.php">Schools</a></li>
  <li><a href="omr-listings/hospitals.php">Hospitals</a></li>
  <li><a href="omr-listings/banks.php">Banks</a></li>
  <li><a href="omr-listings/parks.php">Parks</a></li>
  <li><a href="omr-listings/atms.php">ATMs</a></li>
  <li><a href="omr-listings/government-offices.php">Government Offices</a></li>
  <li><a href="omr-listings/restaurants.php">Restaurants</a></li>
  <li><a href="omr-listings/industries.php">Industries</a></li>
  <li><a href="omr-listings/it-companies.php">IT Companies</a></li>
  <li><a href="omr-listings/best-schools.php">Best Schools</a></li>
</ul>

</div></div>
<div class="container">
  <h1 style="text-align:center; color:#0583D2;">OMR Schools List - in and around OMR neighbourhood</h1>
  

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
<?php include 'components/footer.php'; ?>
<!--footer section ends -->
</body>
</html>