<?php include 'weblog/log.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
$page_title          = 'Happy Streets OMR Road - What It Is and Who Organises It | MyOMR';
$page_description    = 'Learn about Happy Streets OMR - what it is, who organises it on Old Mahabalipuram Road, and what happens at these events.';
$canonical_url       = 'https://myomr.in/local-news/HappyStreets-OMR-Road.php';
$og_title            = 'Happy Streets OMR Road - What It Is and Who Organises It | MyOMR';
$og_description      = 'Learn about Happy Streets OMR - what it is, who organises it on Old Mahabalipuram Road, and what happens at these events.';
$og_image            = 'https://myomr.in/My-OMR-Logo.jpg';
$og_url              = 'https://myomr.in/local-news/HappyStreets-OMR-Road.php';
$twitter_title       = 'Happy Streets OMR Road - What It Is and Who Organises It | MyOMR';
$twitter_description = 'Learn about Happy Streets OMR - what it is, who organises it on Old Mahabalipuram Road, and what happens at these events.';
$twitter_image       = 'https://myomr.in/My-OMR-Logo.jpg';
?>
<?php include '../components/meta.php'; ?>
<?php include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>

<link rel="stylesheet" href="../footer.css">

<script async defer data-pin-hover="true" data-pin-tall="true" data-pin-round="true" src="//assets.pinterest.com/js/pinit.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../social-style.css">
    
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

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "<?php echo htmlspecialchars($page_title ?? '', ENT_QUOTES); ?>",
  "image": ["<?php echo htmlspecialchars($og_image ?? 'https://myomr.in/My-OMR-Logo.jpg', ENT_QUOTES); ?>"],
  "datePublished": "<?php echo htmlspecialchars($date_published ?? '2022-01-01', ENT_QUOTES); ?>",
  "dateModified": "<?php echo htmlspecialchars($date_modified ?? $date_published ?? '2022-01-01', ENT_QUOTES); ?>",
  "author": [{"@type": "Organization", "name": "MyOMR News"}],
  "publisher": {
    "@type": "Organization",
    "name": "MyOMR.in",
    "logo": {"@type": "ImageObject", "url": "https://myomr.in/My-OMR-Idhu-Namma-OMR-Logo.jpg"}
  },
  "description": "<?php echo htmlspecialchars($page_description ?? '', ENT_QUOTES); ?>",
  "mainEntityOfPage": "<?php echo htmlspecialchars($canonical_url ?? 'https://myomr.in/', ENT_QUOTES); ?>"
}
</script>
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
<body>
    <?php include 'myomr-nav-bar.html' ?>
<a href="https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi" class="float" target="_blank">
 <i class="fa fa-whatsapp my-float"></i>
</a>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v14.0" nonce="brAi0ji4"></script>




<div class="container" style="margin-top:30px;">
  <div class="row">
    <div class="col-sm-4">
      <h1>My OMR</h1>
      <h5>Local Community News Portal</h5>
<div><a href="https://myomr.in/"></a><img src="../My-OMR-Idhu-Namma-OMR-Logo.jpg" style="width:inherit; position:relative;max-width: 280px; padding-bottom:10px;" alt="MyOMR - Idhu Namma OMR community portal"></a></div>

<nav>
      <ul class="nav nav-pills flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Home</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link active" href="About-My-OMR-Old-Mahabalipuram-Road-Local-News.php">About My OMR</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="news-highlights-from-omr-road.php">Highlights</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="old-mahabalipuram-road-news-image-video-gallery.php">Gallery</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="contact-my-omr-team.php">Contact</a>
        </li>
           </ul>
</nav>

            <img src="../5th-12th-Tutions-All-Subjects-In-OMR.jpg" style="width:inherit; position:relative;max-width: 280px; padding-bottom:10px;" width="250px" / alt="5th to 12th grade tuitions all subjects - OMR Road advertisement">
            <img src="../DTP-TYPING-Advertisement-MyOMR.jpg" style="width:inherit; position:relative;max-width: 280px; padding-bottom:10px;" width="250px" / alt="DTP and typing services advertisement - MyOMR">
            <img src="../Place-Ads-myomr-Website-OMRnews.jpg" style="width:inherit; position:relative;max-width: 280px; padding-bottom:10px;" width="250px" / alt="Advertise on MyOMR website - OMR Road">

      <hr class="d-sm-none">
    </div>
    <div class="col-sm-8">
<!-- ------news article------ -->

<section class="news-headlines" style="width:100%;">
    <div class="container" style="margin-top:30px;">
  <div class="row">
    <div class="col-sm-4">
        <img src="../happy-streets-omr-photo-gallery/Happy-Streets-OMR-Road-Photo-Gallery-01.jpg" width="220">
        <img src="../happy-streets-omr-photo-gallery/Happy-Streets-OMR-Road-Photo-Gallery-02.jpg" width="220">
        <img src="../happy-streets-omr-photo-gallery/Happy-Streets-OMR-Road-Photo-Gallery-03.jpg" width="220">
        <img src="../happy-streets-omr-photo-gallery/Happy-Streets-OMR-Road-Photo-Gallery-04.jpg" width="220">
        <img src="../happy-streets-omr-photo-gallery/Happy-Streets-OMR-Road-Photo-Gallery-05.jpg" width="220">
        <img src="../happy-streets-omr-photo-gallery/Happy-Streets-OMR-Road-Photo-Gallery-06.jpg" width="220">
        </div>
        <div class="col-sm-8">
<h2 class="h2-news">HAPPY STREETS, HAPPY FACES, HAPPY PEOPLE - OPEN ROADS, SANS TRAFFIC</h2>
<h3>Imagine this</h3>
      <h5>OMR News, Augt - September Recap, 2022</h5>
      
      <ul>
      <li>A Traffic Free Road Stretch on a usually busyroad junction</li>
      <li>Children Playing hoola hoops</li><li>And cyclist and free skate boarders turningthe streets in to ramps.</li>
      <li>Group of Music Artist enthralling their localaudience with their tamil rap music.</li>
      <li>Martial arts practised and displayed in onecorner</li>
      <li>A bunch of elders playing 'traditionalgames' in one segment of the road.</li>
      <li>Activist groups and social awarenessorganisations doing their part in educating and involving the people fromlocality into their respective motives.</li>
      <li>And so many more scenes happening in tandemin the Busy HIGHWAY.</li>
      </ul>
      
      <p>Could anyone have imagined such a festival likeatmosphere in the open roads sans any motorist, Yes it was made a reality by the Chennaicity traffic police, Greater Chennai Corporation in association with Times ofIndia.</p>
      <p>This is the "HAPPY STREETS" for you.</p> Earlier the first of its kind event was inauguratedby the CM himself in anna nagar.It was extended to all the other parts oftamilnadu, simultaneously.</p>
      <p>In our very OMR Road, We have had thischapter of HAPPY STREETS OMR, running successfully for a couple of weeks. MYOMR wishes to bring you a glimpse into the events and happenings of happystreets omr version.</p>
      <p>We present you with photos & videosfrom the event.</p><p>If you are one among the event participants( organisers, performers, groups, music artiste) you might find your performancesand photos being displayed here in our gallery.</p>
      <p>We are happy to report this news to our Residentsof OMR road and its vicinities.</p>
      
      <summary>This is one of a kind event which saw peoplefrom all walks of life gather in the rather busy junction, grouping together,witnessing each other, being an audience to the cultural events which happenedthere in our very own OMR Road,</summary>
<a href="https://www.youtube.com/channel/UCyFrgbaQht7C-17m_prn0Rg?sub_confirmation=1" target="_blank" style="color:red;font-weight:600;">View Youtube Channel Covering Happy Streets OMR Event</a>
        <br>
        </div>
        <hr>           
    </div>
    </div>
</section>

<!-- ------news article------ -->

    </div>
  </div>
</div>


<div class="container-fluid">
    <div class="row row-no-gutters" style="background-color: #F77F00;">
    <div class="col-sm-4" style="padding:0 px; margin: 0px;  border-right: 2px solid white;  text-align:center;background-color: #F77F33;"><button class="button button1"><a href="sell-rent-property-house-plot-omr-chennai.php" style="text-decoration:none;">Looking to Rent or Sell your Property <br> do it here..</a></button></div>
    <div class="col-sm-4" style="padding:0 px; margin: 0px; border-right: 2px solid white;  text-align:center;background-color: #F77F33;"><button class="button button1"><a href="search-and-post-jobs-job-vacancy-employment-platform-for-omr-chennai.php" style="text-decoration:none;">Looking for job or looking to hire candidates  <br> do it here..</a></button></div>
    <div class="col-sm-4" style="padding:0 px; margin: 0px;  border-right: 2px solid white;  text-align:center;background-color: #F77F33;"><button class="button button1"><a href="search-and-post-jobs-job-vacancy-employment-platform-for-omr-chennai.php" style="text-decoration:none;">Home Tutions <br>Learn Programming & Designing..</a></button></div>
  </div>
</div>

<!--footer section begins -->
<?php include '../components/footer.php'; ?>
<!--footer section ends -->
<?php include '../components/sidebar.php'; ?>
<?php include '../components/action-buttons.php'; ?>
<!-- Social Icons -->
<?php include '../components/social-icons.php'; ?>
</body>
</html>

