<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'components/meta.php'; ?>
<?php include 'components/analytics.php'; ?>
<?php include 'components/head-resources.php'; ?>

<title>Old Mahabalipuram Road - (MY OMR Website) - Common User Login Page</title>
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
<meta property="og:image" content="https://myomr.in/My-OMR-Logo.jpg" />
<meta property="og:url" content="https://myomr.in/" />
<meta property="og:site_name" content="My OMR Old Mahabalipuram Road." />
<meta property="og:locale" content="en_US" />
<meta property="og:locale:alternate" content="ta_IN" />

<meta name="twitter:title" content="My OMR - Old Mahabalipuram Road News, Events, Images, Happenings, Search, Business Website">
<meta name="twitter:description" content="in this page you can find news, events, images, happenings, updates, local business information of OMR Road, Old Mahabalipuram Road and its Surroundings">
<meta name="twitter:image" content="https://myomr.in/My-OMR-Logo.jpg">
<meta name="twitter:site" content="@MyomrNews">
<meta name="twitter:creator" content="@MyomrNews">
<link rel="stylesheet" href="assets/css/main.css">

<?php include 'components/social-icons.php'; ?>

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
<style>
    .divider:after,
.divider:before {
content: "";
flex: 1;
height: 1px;
background: #eee;
}
</style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <section class="vh-100">
  <div class="container py-5 h-100">
    <div class="row d-flex align-items-center justify-content-center h-100">
      <div class="col-md-8 col-lg-7 col-xl-6">
        <img src="MyOmr-Login-Page.png"
          class="img-fluid" alt="Phone image">
      </div>
      <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
        <form>
          <!-- Email input -->
          <div class="form-outline mb-4">
            <input type="email" id="form1Example13" class="form-control form-control-lg" />
            <label class="form-label" for="form1Example13">Email address</label>
          </div>

          <!-- Password input -->
          <div class="form-outline mb-4">
            <input type="password" id="form1Example23" class="form-control form-control-lg" />
            <label class="form-label" for="form1Example23">Password</label>
          </div>

          <div class="d-flex justify-content-around align-items-center mb-4">
            <!-- Checkbox -->
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="form1Example3" checked />
              <label class="form-check-label" for="form1Example3"> Remember me </label>
            </div>
            <a href="#!">Forgot password?</a>
          </div>

          <!-- Submit button -->
          <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>

          <div class="divider d-flex align-items-center my-4">
            <p class="text-center fw-bold mx-3 mb-0 text-muted">OR</p>
          </div>

          <a class="btn btn-primary btn-lg btn-block" style="background-color: #3b5998" href="#!"
            role="button">
            <i class="fab fa-facebook-f me-2"></i>Continue with Facebook
          </a>
          <a class="btn btn-primary btn-lg btn-block" style="background-color: #BB001B" href="#!"
            role="button">
            <i class="fab fa-google me-2"></i>Continue with Gmail</a>

        </form>
      </div>
    </div>
  </div>
</section>
<!--footer section begins -->
<?php include 'components/footer.php'; ?>
<!--footer section ends -->
</body>
</html>
