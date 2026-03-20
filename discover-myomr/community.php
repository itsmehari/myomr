<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$page_nav = 'main';
$omr_css_bootstrap5 = true;
$omr_css_megamenu = false;

$page_title       = 'OMR Community | Connect with Neighbors on Old Mahabalipuram Road';
$page_description = 'Join the MyOMR community. Connect with neighbors, participate in local groups, events, and forums across the OMR corridor, Chennai.';
$canonical_url    = 'https://myomr.in/discover-myomr/community.php';
$og_image         = 'https://myomr.in/My-OMR-Logo.png';
$og_title         = $page_title;
$og_description   = $page_description;
$og_url           = $canonical_url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once ROOT_PATH . '/components/meta.php'; ?>
<?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; }
    :root { --brand: #14532d; --brand-mid: #166534; --brand-light: #f0fdf4; }
    .discover-hero {
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-mid) 100%);
      color: #fff; padding: 3rem 0 2.5rem;
    }
    .feature-tile {
      border: 1px solid #e5e7eb; border-radius: 14px; padding: 1.5rem; height: 100%; background: #fff;
    }
    .feature-tile h3 { font-weight: 600; color: var(--brand); font-size: 1.1rem; }
    .section-heading { font-weight: 700; color: var(--brand); }
    .cta-strip { background: var(--brand-light); border-radius: 12px; border: 1px solid #bbf7d0; }
  </style>
</head>
<body class="bg-light">
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav(); ?>

<main id="main-content">
  <section class="discover-hero">
    <div class="container text-center">
      <h1 class="display-5 mb-3">Join the MyOMR community</h1>
      <p class="lead mb-0 mx-auto" style="max-width: 36rem; opacity: .92;">
        Connect with neighbors, follow local news and events, and help shape the OMR corridor.
      </p>
    </div>
  </section>

  <div class="container py-5">
    <section class="cta-strip p-4 mb-5">
      <h2 class="section-heading h5 mb-2">Areas we cover</h2>
      <p class="text-muted small mb-0">From Perungudi to Kelambakkam. <a href="/discover-myomr/areas-covered.php">See all localities</a>.</p>
    </section>

    <div class="row g-4 mb-5">
      <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
          <h3>Community and forums</h3>
          <p class="text-muted small mb-0">Join discussions and share local knowledge with fellow residents.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
          <h3>Events and meetups</h3>
          <p class="text-muted small mb-0">Discover and submit community events happening near you.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
          <h3>Volunteering</h3>
          <p class="text-muted small mb-0">Get involved in local initiatives and neighborhood projects.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
          <h3>Local groups</h3>
          <p class="text-muted small mb-0">Find groups by interest, locality, or profession along OMR.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
          <h3>Feedback</h3>
          <p class="text-muted small mb-0">Share ideas to improve the platform and local coverage.</p>
        </div>
      </div>
    </div>

    <section class="text-center p-5 rounded-3 border" style="background: var(--brand-light); border-color: #bbf7d0 !important;">
      <h2 class="section-heading h4 mb-3">Ready to get involved?</h2>
      <p class="text-muted mb-4">Start with our quick guide, then explore jobs, events, and listings.</p>
      <a href="/discover-myomr/getting-started.php" class="btn btn-success btn-lg">Get started</a>
    </section>
  </div>
</main>

<?php omr_footer(); ?>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
</body>
</html>
