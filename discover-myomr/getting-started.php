<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$page_nav = 'main';
$omr_css_bootstrap5 = true;
$omr_css_megamenu = false;

$page_title       = 'Get Started with MyOMR | OMR Chennai Community Portal';
$page_description = 'New to MyOMR? Learn how to explore local news, post listings, find jobs, and connect with the OMR community in just a few easy steps.';
$canonical_url    = 'https://myomr.in/discover-myomr/getting-started.php';
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
    :root { --brand: #14532d; --brand-mid: #166534; --brand-accent: #22c55e; --brand-light: #f0fdf4; }
    .discover-hero {
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-mid) 100%);
      color: #fff; padding: 3rem 0 2.5rem;
    }
    .step-card {
      border: 1px solid #e5e7eb; border-radius: 14px; padding: 1.5rem; height: 100%;
      background: #fff; text-align: center;
    }
    .step-num { font-size: 2rem; font-weight: 700; color: var(--brand); line-height: 1; margin-bottom: .5rem; }
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
      <h1 class="display-5 mb-3">Get started with MyOMR</h1>
      <p class="lead mb-0 mx-auto" style="max-width: 36rem; opacity: .92;">
        Join the OMR community in a few easy steps: local news, jobs, events, and more.
      </p>
    </div>
  </section>

  <div class="container py-5">
    <div class="row g-4 mb-5">
      <div class="col-md-4">
        <div class="step-card">
          <div class="step-num">1</div>
          <h3 class="h5 text-success mb-2">Explore</h3>
          <p class="text-muted small mb-0">Browse news, jobs, events, and listings. Most of MyOMR is free without an account.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="step-card">
          <div class="step-num">2</div>
          <h3 class="h5 text-success mb-2">Discover features</h3>
          <p class="text-muted small mb-0">Use the directory, job portal, events calendar, and locality filters for OMR.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="step-card">
          <div class="step-num">3</div>
          <h3 class="h5 text-success mb-2">Engage</h3>
          <p class="text-muted small mb-0">Post jobs, list your business, submit events, and connect with the community.</p>
        </div>
      </div>
    </div>

    <section class="text-center p-5 rounded-3 border mb-5" style="background: var(--brand-light); border-color: #bbf7d0 !important;">
      <h2 class="section-heading h4 mb-3">Ready to go deeper?</h2>
      <p class="text-muted mb-4">See pricing for verified and featured business listings.</p>
      <a href="/discover-myomr/pricing.php" class="btn btn-success btn-lg">View plans and pricing</a>
    </section>

    <section class="cta-strip p-4">
      <h2 class="section-heading h5 mb-2">Areas we cover</h2>
      <p class="text-muted small mb-0">Perungudi through Kelambakkam and the full IT corridor. <a href="/discover-myomr/areas-covered.php">Full list</a>.</p>
    </section>
  </div>
</main>

<?php omr_footer(); ?>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
</body>
</html>
