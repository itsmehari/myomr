<?php 
include 'weblog/log.php';
include '../core/omr-connect.php';
$sql = "SELECT `Areas` FROM `List of Areas`";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
$page_title          = 'OMR Road Areas Covered by MyOMR News | List of Localities';
$page_description    = 'Complete list of areas, localities, and zones covered by MyOMR news and community portal along Old Mahabalipuram Road, Chennai.';
$canonical_url       = 'https://myomr.in/local-news/omr-news-list-of-areas-covered.php';
$og_title            = 'OMR Road Areas Covered by MyOMR News | List of Localities';
$og_description      = 'Complete list of areas, localities, and zones covered by MyOMR news and community portal along Old Mahabalipuram Road, Chennai.';
$og_image            = 'https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg';
$og_url              = 'https://myomr.in/local-news/omr-news-list-of-areas-covered.php';
$twitter_title       = 'OMR Road Areas Covered by MyOMR News | List of Localities';
$twitter_description = 'Complete list of areas, localities, and zones covered by MyOMR news and community portal along Old Mahabalipuram Road, Chennai.';
$twitter_image       = 'https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg';
?>
<?php include '../components/meta.php'; ?>
<?php include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>

<meta name=”robots” content=”index, follow”>
<link rel="stylesheet" href="assets/css/main.css">

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

.h2-news
{
font-size: 120%;
color:grey;
}
</style>

<!-- Area Styles -->
<style>
.areas{
display:inline-block;
width: 120px; 
padding: 5px; 
background: rgb(2,0,36);
background: linear-gradient(90deg, #9ebd13 0%, #008552 100%);
color:white; 
border-right: 2px solid white; 
border-top: 2px solid white; 
margin: 3 px; 
text-align:center;
cursor: pointer;
}

.areas:hover
{
background-image: linear-gradient(120deg, #d4fc79 0%, #96e6a1 100%);
cursor:pointer;
color:black;
}
</style>
<!-- Area Styles End-->
<body>
<!-- Navigation Bar -->    
<?php include 'myomr-nav-bar.html'; ?>
<!-- Navigation Bar Ends -->

  

  <a href="https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi" class="float" target="_blank">
 <i class="fa fa-whatsapp my-float"></i>
</a>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v14.0" nonce="brAi0ji4"></script>

    <a href="commonlogin.php">Login to your admin panel</a>
 


<!-- Advertisment Pointer Section -->
<div class="container-fluid">
    <div class="row row-no-gutters" style="background-color: #F77F00;">
    <div class="col-sm-4" style="padding:0 px; margin: 0px;  border-right: 2px solid white;  text-align:center;background-color: #F77F33;"><button class="button button1"><a href="listings/sell-rent-property-house-plot-omr-chennai.php" style="text-decoration:none;">Looking to Rent or Sell your Property <br> do it here..</a></button></div>
    <div class="col-sm-4" style="padding:0 px; margin: 0px; border-right: 2px solid white;  text-align:center;background-color: #F77F33;"><button class="button button1"><a href="listings/search-and-post-jobs-job-vacancy-employment-platform-for-omr-chennai.php" style="text-decoration:none;">Looking for job or looking to hire candidates  <br> do it here..</a></button></div>
    <div class="col-sm-4" style="padding:0 px; margin: 0px;  border-right: 2px solid white;  text-align:center;background-color: #F77F33;"><button class="button button1"><a href="listings/tutions-classes-courses-training-centers-in-omr-chennai.php" style="text-decoration:none;">Home Tutions <br>Learn Programming & Designing..</a></button></div>
  </div>
</div>
<!-- Advertisment Pointer Section Ends-->

<div class="container" style="margin-top:30px;">
  <div class="row">
    <div class="col-sm-4">
      <h2>My OMR</h2>
      <h5>Local Community News Portal</h5>
      <div><img src="My-OMR-Idhu-Namma-OMR-Logo.jpg" style="width:inherit; position:relative;max-width: 280px; padding-bottom:10px;" alt="MyOMR - Idhu Namma OMR community portal"></div>
      
      <!--<p>
      
      
      igate to News Section</p>-->
      <!--<h3>recent news</h3>-->
      <!--<p>top news of the week</p>-->
      <ul class="nav nav-pills flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Home</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link active" href="About-My-OMR-Old-Mahabalipuram-Road-Local-News.php">About My OMR</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="local-news/news-highlights-from-omr-road.php">Highlights</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="old-mahabalipuram-road-news-image-video-gallery.php">Gallery</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="contact-my-omr-team.php">Contact</a>
        </li>
          </ul>

            <img src="5th-12th-Tutions-All-Subjects-In-OMR.jpg" style="width:inherit; position:relative;max-width: 280px; padding-bottom:10px;" width="250px" / alt="5th to 12th grade tuitions all subjects - OMR Road advertisement">
            <img src="DTP-TYPING-Advertisement-MyOMR.jpg" style="width:inherit; position:relative;max-width: 280px; padding-bottom:10px;" width="250px" / alt="DTP and typing services advertisement - MyOMR">
            <img src="Place-Ads-myomr-Website-OMRnews.jpg" style="width:inherit; position:relative;max-width: 280px; padding-bottom:10px;" width="250px" / alt="Advertise on MyOMR website - OMR Road">

      <hr class="d-sm-none">
    </div>
    
    
    <div class="col-sm-8">
       

       <!-- Areas Covered Section -->    
    <div style="width:95%; margin: auto;">
        <?php
    while($row = $result->fetch_assoc()) {
        ?>
        
    
    <span class="areas">
    <?php echo $row["Areas"];?>
    </span>
   
<?php    }
    ?>
     </div>
<!-- Areas Covered Section Ends -->  

 
    </div>
  </div>
</div>

<!--footer section begins -->
<?php include '../components/footer.php'; ?>
<!--footer section ends -->
<?php include '../components/sidebar.php'; ?>
<?php include '../components/action-buttons.php'; ?>
<?php include '../components/social-icons.php'; ?>
</body>
</html>

