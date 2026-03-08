<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// $browser = get_browser(null, true); // Disabled for stability

require '../core/omr-connect.php';
require_once __DIR__ . '/components/generic-list-renderer.php';
require_once __DIR__ . '/directory-config.php';

//echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";

$sql = "SELECT slno, bankname, address, contact, landmark, website FROM omrbankslist";
$result = $conn->query($sql);

?>
<?php include 'weblog/log.php' ?>
<!DOCTYPE html>
<?php
$page_title          = 'Banks in OMR, Chennai | MyOMR';
$page_description    = 'Find banks along Old Mahabalipuram Road (OMR), Chennai. Get bank names, addresses, contacts, and landmarks for financial services.';
$canonical_url       = 'https://myomr.in/banks';
$og_title            = 'Banks in OMR, Chennai | MyOMR';
$og_description      = 'Find banks along Old Mahabalipuram Road (OMR), Chennai. Get bank names, addresses, contacts, and landmarks for financial services.';
$og_image            = 'https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg';
$og_url              = 'https://myomr.in/banks';
$twitter_title       = 'Banks in OMR, Chennai | MyOMR';
$twitter_description = 'Find banks along Old Mahabalipuram Road (OMR), Chennai. Get bank names, addresses, contacts, and landmarks for financial services.';
$twitter_image       = 'https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg';
?>
<html lang="en">
<head>
<?php $breadcrumbs = [ ['https://myomr.in/','Home'], ['https://myomr.in/omr-listings/banks.php','Banks'] ]; ?>
<?php include '../components/meta.php'; ?>
<?php $ga_custom_params = ['listing_type' => 'bank']; include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
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
  <h1 class="mt-4" style="color:#0583D2;">Banks in OMR, Chennai</h1>
  <?php $cfg = get_directory_config('banks'); $q = isset($_GET['q']) ? trim($_GET['q']) : ''; $locality = isset($_GET['locality']) ? trim($_GET['locality']) : ''; $sort = isset($_GET['sort']) && $_GET['sort']==='newest' ? 'newest' : 'az'; $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1; $res = render_directory_list($cfg, ['q'=>$q,'locality'=>$locality,'sort'=>$sort], $page, 12); ?>
  <form class="form-inline my-3" method="get" action="">
    <input type="text" class="form-control mr-2" name="q" placeholder="Search banks" value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>">
    <select name="locality" class="form-control mr-2">
      <option value="">All localities</option>
      <?php foreach (['Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Mettukuppam','Sholinganallur','Semmancheri','Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam'] as $loc): ?>
        <option value="<?php echo htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $locality===$loc?'selected':''; ?>><?php echo htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?></option>
      <?php endforeach; ?>
    </select>
    <select name="sort" class="form-control mr-2">
      <option value="az" <?php echo $sort==='az'?'selected':''; ?>>A–Z</option>
      <option value="newest" <?php echo $sort==='newest'?'selected':''; ?>>Newest</option>
    </select>
    <button type="submit" class="btn btn-primary">Search</button>
  </form>
  <?php if (!empty($res['items'])): ?>
    <?php $itemList = []; ?>
    <div class="list-group mb-3">
      <?php foreach ($res['items'] as $row): $nm = $row[$cfg['fields']['name']] ?? ''; $id=(int)$row[$cfg['fields']['id']]; $slugBase=strtolower(preg_replace('/[^a-zA-Z0-9]+/','-',$nm)); $slugBase=trim($slugBase,'-'); $detail='/banks/'.$slugBase.'-'.$id; $itemList[]=['@type'=>'ListItem','position'=>$id,'url'=>'https://myomr.in'.$detail,'name'=>$nm]; ?>
        <div class="list-group-item">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="font-weight-bold"><a href="<?php echo htmlspecialchars($detail, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($nm, ENT_QUOTES, 'UTF-8'); ?></a></div>
              <?php if (!empty($row[$cfg['fields']['address']]??'')): ?>
                <div class="text-muted"><?php echo htmlspecialchars($row[$cfg['fields']['address']], ENT_QUOTES, 'UTF-8'); ?></div>
              <?php endif; ?>
              <?php if (!empty($row[$cfg['fields']['contact']]??'')): ?>
                <div class="small">Contact: <?php echo htmlspecialchars($row[$cfg['fields']['contact']], ENT_QUOTES, 'UTF-8'); ?></div>
              <?php endif; ?>
            </div>
            <span class="badge badge-light">#<?php echo (int)$row[$cfg['fields']['id']]; ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php if ($res['pages'] > 1): ?>
      <nav aria-label="Banks pagination">
        <ul class="pagination">
          <?php for ($p=1; $p <= $res['pages']; $p++): $qs = http_build_query(['q'=>$q,'locality'=>$locality,'sort'=>$sort,'page'=>$p]); ?>
            <li class="page-item <?php echo $p===$res['page']?'active':''; ?>">
              <a class="page-link" <?php echo $p===$res['page']?'aria-current="page"':''; ?> href="?<?php echo htmlspecialchars($qs, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $p; ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    <?php endif; ?>
    <script type="application/ld+json">
      <?php echo json_encode(['@context'=>'https://schema.org','@type'=>'ItemList','name'=>'Banks on OMR, Chennai','itemListElement'=>$itemList], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
    </script>
  <?php else: ?>
    <p>No banks found.</p>
  <?php endif; ?>
</div>


<!--footer section begins -->
<?php include '../components/footer.php'; ?>
<!--footer section ends -->

<!-- UN SDG Floating Badges -->
<?php include '../components/sdg-badge.php'; ?>
</body>
</html>
