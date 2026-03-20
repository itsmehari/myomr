<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$page_nav = 'main';
$omr_css_bootstrap5 = true;
$omr_css_megamenu = false;

$page_title       = 'Support & Help | MyOMR OMR Chennai Community Portal';
$page_description = 'Need help with MyOMR? Find answers to common questions or contact our support team for assistance with listings, events, and more.';
$canonical_url    = 'https://myomr.in/discover-myomr/support.php';
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
    .section-heading { font-weight: 700; color: var(--brand); }
    .help-card { border: 1px solid #e5e7eb; border-radius: 14px; background: #fff; }
    .cta-strip { background: var(--brand-light); border-radius: 12px; border: 1px solid #bbf7d0; }
  </style>
</head>
<body class="bg-light">
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav(); ?>

<main id="main-content" class="container py-5">
  <div class="text-center mb-5">
    <h1 class="section-heading display-6 mb-3">Support &amp; help</h1>
    <p class="text-muted mx-auto mb-0" style="max-width: 32rem;">
      We are here to help with listings, events, jobs, and using MyOMR.in.
    </p>
  </div>

  <section class="cta-strip p-4 mb-5">
    <h2 class="section-heading h6 mb-2">Areas we cover</h2>
    <p class="text-muted small mb-0">Full OMR corridor — <a href="/discover-myomr/areas-covered.php">see all localities</a>.</p>
  </section>

  <div class="row justify-content-center mb-5">
    <div class="col-lg-8">
      <div class="help-card p-4 mb-4">
        <h2 class="h5 text-success mb-3">Frequently asked questions</h2>
        <ul class="text-muted small mb-0 ps-3">
          <li class="mb-2">How do I post a job or business listing?</li>
          <li class="mb-2">How can I submit a community event?</li>
          <li class="mb-2">How do I report incorrect information on a listing?</li>
          <li class="mb-2">Who do I contact for verified or featured listings?</li>
        </ul>
      </div>
      <div class="help-card p-4">
        <h2 class="h5 text-success mb-3">Contact</h2>
        <p class="text-muted small mb-2">For listing enquiries, corrections, and partnerships:</p>
        <ul class="text-muted small mb-0 ps-3">
          <li class="mb-1">Email: <a href="mailto:myomrnews@gmail.com">myomrnews@gmail.com</a></li>
          <li class="mb-1">Phone: <a href="tel:9445088028">94450 88028</a></li>
          <li class="mb-0"><a href="/webmaster-contact-my-omr.php">Webmaster contact form</a></li>
        </ul>
      </div>
    </div>
  </div>

  <section class="text-center p-4 rounded-3 border bg-white">
    <h2 class="h6 text-muted mb-3">Discover MyOMR</h2>
    <a href="/discover-myomr/overview.php" class="btn btn-success">Overview</a>
    <a href="/discover-myomr/getting-started.php" class="btn btn-outline-success ms-2">Get started</a>
  </section>
</main>

<?php omr_footer(); ?>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
</body>
</html>
