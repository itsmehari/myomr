<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$page_nav = 'main';
$omr_css_bootstrap5 = true;
$omr_css_megamenu = false;

$page_title       = 'MyOMR Features | News, Jobs, Events, Listings & More - OMR Chennai';
$page_description = 'Explore all features of MyOMR: local news, job listings, events, business directories, real estate, hostels, and coworking spaces in the OMR corridor.';
$canonical_url    = 'https://myomr.in/discover-myomr/features.php';
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
    .discover-hero h1 { font-weight: 700; }
    .feature-tile {
      border: 1px solid #e5e7eb; border-radius: 14px; padding: 1.5rem; height: 100%;
      background: #fff; transition: transform .2s, box-shadow .2s;
    }
    .feature-tile:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(20,83,45,.1); }
    .feature-tile h3 { font-weight: 600; color: var(--brand); font-size: 1.1rem; margin-bottom: .5rem; }
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
      <h1 class="display-5 mb-3">Discover MyOMR Features</h1>
      <p class="lead mb-0 mx-auto" style="max-width: 36rem; opacity: .92;">
        Everything you need to connect, discover, and thrive in the OMR corridor.
      </p>
    </div>
  </section>

  <div class="container py-5">
    <section class="cta-strip p-4 mb-5">
      <h2 class="section-heading h4 mb-2">Areas we cover</h2>
      <p class="text-muted small mb-3">MyOMR serves the entire OMR corridor, including Perungudi, Thuraipakkam, Karapakkam, Kandhanchavadi, Mettukuppam, Sholinganallur, Navalur, Kelambakkam, and more.</p>
      <a href="/discover-myomr/areas-covered.php" class="btn btn-success btn-sm">See all areas</a>
    </section>

    <div class="row g-4 mb-5">
      <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
          <h3>Local news and updates</h3>
          <p class="text-muted small mb-0">Stay up to date with breaking news, local stories, and community updates.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
          <h3>Job search and local jobs</h3>
          <p class="text-muted small mb-0">Find and post jobs, connect with local employers, and grow your career in OMR. Job seekers can <a href="/omr-local-job-listings/candidate-profile-omr.php?utm_source=features&utm_medium=link&utm_campaign=job_seeker_profile">upload a résumé and create a profile</a>.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
          <h3>Events and activities</h3>
          <p class="text-muted small mb-0">Discover festivals, workshops, and local activities across the corridor.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
          <h3>Business directory</h3>
          <p class="text-muted small mb-0">Explore local businesses, services, and opportunities in the OMR area.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
          <h3>Real estate and rentals</h3>
          <p class="text-muted small mb-0">Browse rent, buy-sell, hostels, PGs, and coworking along OMR.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
          <h3>Targeted advertising</h3>
          <p class="text-muted small mb-0">Promote your business or event to a highly engaged local audience.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
          <h3>Community engagement</h3>
          <p class="text-muted small mb-0">Join discussions, share your voice, and help shape the OMR community.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
          <h3>Civic issues and feedback</h3>
          <p class="text-muted small mb-0">Report civic issues, share feedback, and contribute to local improvements.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
          <h3>Mobile access</h3>
          <p class="text-muted small mb-0">Access MyOMR anytime, anywhere, on any device.</p>
        </div>
      </div>
    </div>

    <section class="text-center p-5 rounded-3 border" style="background: var(--brand-light); border-color: #bbf7d0 !important;">
      <h2 class="section-heading h4 mb-3">Ready to experience it all?</h2>
      <p class="text-muted mb-4">Browse free. Upgrade listings when you need more visibility.</p>
      <a href="/discover-myomr/pricing.php" class="btn btn-success btn-lg">View plans and pricing</a>
    </section>
  </div>
</main>

<?php omr_footer(); ?>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
</body>
</html>
