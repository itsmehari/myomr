<?php include '../weblog/log.php'; ?>
<?php
$page_title          = 'Mushroom Scarcity in OMR Perungudi During Puratasi Due to High Demand | MyOMR';
$page_description    = 'Surge in demand for mushrooms during Puratasi month (No Meat Month) in Chennai OMR. Local shops face scarcity near Perungudi and Karapakkam.';
$canonical_url       = 'https://myomr.in/local-news/Demand-for-Mushroom-in-Local-Shops-On-Occassion-Of-Purataasi.php';
$og_title            = 'Shortage of Mushrooms in OMR, Perungudi, Karapakkam, Thuraipakkam | MyOMR';
$og_description      = 'Surge in demand for Mushrooms: No Meat Month (Puratasi) - Consumers move towards mushrooms for a healthy alternative.';
$og_image            = 'https://myomr.in/local-news/omr-news-images/Mushrooms-in-High-Demand-Due-to-Purataasi-Month-in-Chennai.webp';
$og_url              = 'https://myomr.in/local-news/Demand-for-Mushroom-in-Local-Shops-On-Occassion-Of-Purataasi.php';
$twitter_title       = 'Shortage of Mushrooms in OMR During Puratasi | MyOMR';
$twitter_description = 'Surge in demand for Mushrooms during Puratasi No-Meat month - Consumers move towards mushrooms as a healthy alternate.';
$twitter_image       = 'https://myomr.in/local-news/omr-news-images/Mushrooms-in-High-Demand-Due-to-Purataasi-Month-in-Chennai.webp';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include '../components/meta.php'; ?>
<?php include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
<meta name="robots" content="index, follow">
<link rel="stylesheet" href="/assets/css/footer.css">

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
  "image": ["<?php echo htmlspecialchars($og_image ?? 'https://myomr.in/My-OMR-Logo.png', ENT_QUOTES); ?>"],
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
      <h2>My OMR</h2>
      <h2>Local Community News Portal for OMR Road</h2>
<div><a href="https://myomr.in/"></a><img src="../My-OMR-Idhu-Namma-OMR-Logo.jpg" style="width:inherit; position:relative;max-width: 280px; padding-bottom:10px;" alt="MyOMR - Idhu Namma OMR community portal"></a></div>
<nav>
      <ul class="nav nav-pills flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Home</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link active" href="/discover-myomr/overview.php">About My OMR</a>
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
</nav>
            <img src="../5th-12th-Tutions-All-Subjects-In-OMR.jpg" style="width:inherit; position:relative;max-width: 280px; padding-bottom:10px;" width="250px" / alt="5th to 12th grade tuitions all subjects - OMR Road advertisement">
            <img src="../DTP-TYPING-Advertisement-MyOMR.jpg" style="width:inherit; position:relative;max-width: 280px; padding-bottom:10px;" width="250px" / alt="DTP and typing services advertisement - MyOMR">
            <img src="../Place-Ads-myomr-Website-OMRnews.jpg" style="width:inherit; position:relative;max-width: 280px; padding-bottom:10px;" width="250px" / alt="Advertise on MyOMR website - OMR Road">

      <hr class="d-sm-none">
    </div>
    <div class="col-sm-8">
<!-- ------news article------ -->
        <!-- ---------------------------------------------------news article--------------------------------------------- -->
<a href="https://www.facebook.com/myomrCommunity" class="fa" target="_blank"><img src="https://cdn3.iconfinder.com/data/icons/social-media-2169/24/social_media_social_media_logo_facebook-128.png" width="30px;"  alt="Facebook"></a>
<a href="https://www.instagram.com/myomrcommunity/" class="fa" target="_blank"><img src="https://cdn2.iconfinder.com/data/icons/social-media-applications/64/social_media_applications_3-instagram-128.png" width="30px" alt="Instagram"></a>
<a href="https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi" class="fa" target="_blank"><img src="https://cdn2.iconfinder.com/data/icons/social-media-2285/512/1_Whatsapp2_colored_svg-512.png" width="30px" alt="WhatsApp"></a>
<a href="https://www.youtube.com/channel/UCyFrgbaQht7C-17m_prn0Rg" class="fa" target="_blank"><img src="https://cdn1.iconfinder.com/data/icons/logotypes/32/youtube-128.png" width="30px;" alt="YouTube"></a>
    
  <!-- ---------------------------------------------------news article--------------------------------------------- -->

<h1>## Surge in demand for Mushrooms: No Meat Month (purasttasi) </h1>
<h2>Shortage of mushrooms in OMR, Perungudi, Karaapakkam, Thuraipakkam, Chennai shops during Puratasi month due to increased demand as locals avoid non-vegetarian food.</h2>

<section class="news">
    <p style="font-weight: bold; font-size:1.5em">Consumers Move towards "Mushrooms" for a healthy alternate</p>
  
<img src="omr-news-images/Mushrooms-in-High-Demand-Due-to-Purataasi-Month-in-Chennai.webp" alt=" " style="max-width: 720px;" class="responsive">


  <h5>OMR News, Sept 29, 2023</h5>
  <p>The city of Chennai, with its rich traditions and diverse culinary landscape, is currently witnessing an unprecedented shortage of mushrooms in local stores. The scenario arises primarily due to the arrival of Puratasi, a sacred month in the Tamil calendar where many Hindus abstain from consuming meat and opt for vegetarian alternatives. Mushrooms, revered as a nutritious and versatile substitute for meat, are soaring in demand, leading to scarcity and non-availability in local markets.
</p>

<h3>Surge in Demand for Mushroom</h3>

<p>During Puratasi, the consumption of vegetarian food significantly increases as the populace adheres to traditional norms and spiritual practices, creating a heightened demand for mushrooms. People flock to local stores and supermarkets to incorporate mushrooms into their diet, contributing to the depletion of stocks and a sharp rise in prices</p>

<h3>Impact on Local Stores and Consumers:</h3>
<p>Local grocery stores and supermarkets are grappling with the mushroom deficit, struggling to meet the escalating demand. The shortage has led to inconvenience and disappointment among consumers, many of whom rely on mushrooms as a primary source of nutrition during this period. The scarcity is making it difficult for residents to adhere to their traditional vegetarian diets, affecting their observance of the sacred month.</p>

<h3>Exploring Alternatives:</h3>
<p>With the mushroom shortage plaguing the city, residents are exploring other vegetarian protein sources like tofu and paneer (cottage cheese) to sustain themselves through the month of Puratasi. This shift in dietary preferences is reshaping the culinary choices in Chennai, leading to a diversification in vegetarian cuisines.</p>

<h3>Efforts should be taken to Mitigate the Shortage during high demand months:</h3>
<p>The local government and suppliers should work in tandem to address the shortage by enhancing the supply chains and exploring additional sources for mushrooms. Efforts are underway to stabilize the mushroom market and ensure availability to meet the needs of the local population during this significant month.</p>

<h2>news summary</h2>
<p>The mushroom shortage in Chennai during Puratasi month is a manifestation of the city's deeply ingrained cultural and culinary traditions. The scarcity is affecting not just the culinary landscape but also the economic and social aspects of life in Chennai. It is imperative to address the mushroom shortage promptly to uphold the city's traditions and ensure the well-being of its residents during this sacred month. The confluence of tradition, culinary preferences, and supply chain dynamics in this scenario reflects the complex interplay between cultural practices and food availability in modern urban settings.</p>

<div class="fb-share-button" 
data-href="https://myomr.in/local-news/Demand-for-Mushroom-in-Local-Shops-On-Occassion-Of-Purataasi.php" 
data-layout="button_count">
</div>

        <a href="https://www.facebook.com/My-omr-116575274367970"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
        <br>
  <hr>
  <a href="https://wa.me/?text=Check%20out%20this%20news%20article:%20https://myomr.in/local-news/Demand-for-Mushroom-in-Local-Shops-On-Occassion-Of-Purataasi.php" target="_blank" rel="noopener noreferrer">
    Share on WhatsApp
</a>

  <hr>
</section>

<!-- ---------------------------------------------------news article--------------------------------------------- -->
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
<footer class="footer-section">
        <div class="container">
            <div class="footer-cta pt-5 pb-5">
                <div class="row">
                    <div class="col-xl-4 col-md-4 mb-30">
                        <div class="single-cta">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="cta-text">
                                <h4>Find us</h4>
                                <span>Old Mahabalipuram Road, Chennai</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 mb-30">
                        <div class="single-cta">
                            <i class="fas fa-phone"></i>
                            <div class="cta-text">
                                <h4>Call us</h4>
                                <span>9445088028</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 mb-30">
                        <div class="single-cta">
                            <i class="far fa-envelope-open"></i>
                            <div class="cta-text">
                                <h4>Mail us</h4>
                                <span>myomrnews@gmail.com</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-content pt-5 pb-5">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 mb-50">
                        <div class="footer-widget">
                            <div class="footer-logo">
                                <a href="index.html"><img src="../My-OMR-Idhu-Namma-OMR-Logo.jpg" class="img-fluid" alt="My-Omr-Idhu-Namma-OMR-Logo" width="100" alt="MyOMR - Idhu Namma OMR community portal"></a>
                            </div>
                            <div class="footer-text">
                                <p> online news portal and community website of OMR (old Mahabalipuram Road). We present you with news, events and happening in and around OMR. Areas covered are perungudi, thuraipakkam, karappakam, kandhanchavadi, mettukuppam, sholinganallur, Dollor Stop OMR, IT corridor, TidelPark , Madhya kailash, navalur, thazhambur and kelambakkam. 
</p>
                            </div>
                            <div class="footer-social-icon">
                                <span>Follow us</span>
                                <a href="https://www.instagram.com/myomrcommunity/"><i class="fab fa-facebook-f facebook-bg"></i></a>
                                <a href="https://twitter.com/MyomrNews"><i class="fab fa-twitter twitter-bg"></i></a>
                                <a href="#"><i class="fab fa-instagram instagram-bg"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 mb-30">
                        <div class="footer-widget">
                            <div class="footer-widget-heading">
                                <h3>Useful Links</h3>
                            </div>
                            <ul>
                                <li><a href="index.php">Home</a></li>
                                <li><a href="omr-road-database-list.php">OMR Database</a></li>
                                <li><a href="contact-my-omr-team.php">Contact us</a></li>
                                <li><a href="local-news/news-highlights-from-omr-road.php">Latest News</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 mb-50">
                        <div class="footer-widget">
                            <div class="footer-widget-heading">
                                <h3>Subscribe</h3>
                            </div>
                            <div class="footer-text mb-25">
                                <p>Don't miss to subscribe to our new feeds, kindly fill the form below.</p>
                            </div>
                            <div class="subscribe-form">
                                <form action="#">
                                    <input type="text" placeholder="Email Address">
                                    <button><i class="fab fa-telegram-plane"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-area">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 text-center text-lg-left">
                        <div class="copyright-text">
                            <p>Copyright &copy; 2022, All Right Reserved <a href="http//www.myomr.in">My OMR</a></p>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 d-none d-lg-block text-right">
                        <div class="footer-menu">
                            <ul>
                                <li><a href="terms-and-conditions-my-omr.php">Terms</a></li>
                                <li><a href="website-privacy-policy-of-my-omr.php">Privacy</a></li>
                                <li><a href="general-data-policy-of-my-omr.php">Policy</a></li>
                                <li><a href="webmaster-contact-my-omr.php">Contact Webmaster</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--footer section ends -->
</body>
</html>
