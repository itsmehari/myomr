<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';

$page_title       = 'Everything OMR. One Trusted Portal. | MyOMR Chennai';
$page_description = 'MyOMR is OMR Chennai\'s local community portal. Trusted for local news, jobs, events, business listings, hostels, coworking spaces and more across the entire OMR corridor.';
$canonical_url    = 'https://myomr.in/discover-myomr/overview.php';
$og_image         = 'https://myomr.in/My-OMR-Logo.png';
$og_title         = $page_title;
$og_description   = $page_description;
$og_url           = $canonical_url;
$breadcrumbs      = [['https://myomr.in/','Home'],['https://myomr.in/discover-myomr/overview.php','Overview']];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include '../components/meta.php'; ?>
<?php include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; }
    :root { --brand: #14532d; --brand-mid: #166534; --brand-accent: #22c55e; --brand-light: #f0fdf4; }

    .overview-hero {
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-mid) 100%);
      color: #fff; padding: 80px 0 60px;
    }
    .overview-hero h1 { font-weight: 700; }
    .hero-badge-pill {
      display: inline-block; background: rgba(255,255,255,.15);
      border: 1px solid rgba(255,255,255,.3); border-radius: 20px;
      padding: 5px 18px; font-size: .84rem; margin-bottom: 1.25rem;
    }
    .stat-chip {
      background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.25);
      border-radius: 10px; padding: 18px; text-align: center;
    }
    .stat-chip .num { font-size: 1.9rem; font-weight: 700; }
    .stat-chip .lbl { font-size: .78rem; opacity: .85; }

    .feature-card {
      border: 1px solid #e5e7eb; border-radius: 14px;
      padding: 28px 24px; height: 100%;
      transition: transform .2s, box-shadow .2s;
    }
    .feature-card:hover { transform: translateY(-5px); box-shadow: 0 12px 32px rgba(20,83,45,.1); }
    .feature-icon {
      width: 52px; height: 52px; border-radius: 12px;
      background: var(--brand-light); display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; color: var(--brand); margin-bottom: 1rem;
    }
    .feature-title { font-weight: 600; color: #1f2937; margin-bottom: .4rem; }
    .feature-desc { font-size: .875rem; color: #6b7280; }

    .areas-strip { background: #f9fafb; border-radius: 12px; }
    .area-pill {
      display: inline-block; background: #fff; border: 1px solid #d1fae5;
      color: var(--brand); font-size: .78rem; font-weight: 500;
      padding: 4px 14px; border-radius: 20px; margin: 3px;
    }
    .cta-block {
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-mid) 100%);
      border-radius: 16px; color: #fff;
    }
    .cta-block h2 { font-weight: 700; }
    .section-heading { font-weight: 700; color: var(--brand); }
    .how-step { text-align: center; }
    .step-num {
      width: 48px; height: 48px; border-radius: 50%; background: var(--brand);
      color: #fff; font-weight: 700; font-size: 1.2rem;
      display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;
    }
  </style>
</head>
<body>

<?php include ROOT_PATH . '/components/main-nav.php'; ?>

<div class="container py-3"><?php omr_ad_slot('discover-top', '728x90'); ?></div>

<!-- HERO -->
<section class="overview-hero">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <div class="hero-badge-pill"><i class="fas fa-map-marker-alt me-1"></i> OMR Chennai's Local Community Portal</div>
        <h1 class="display-5 mb-3">Everything OMR.<br>One Trusted Portal.</h1>
        <p class="lead mb-4" style="opacity:.92;">
          From Perungudi to Kelambakkam — MyOMR is where OMR residents, professionals, and businesses
          connect, discover, and grow. Local news, real jobs, community events, verified businesses, and much more.
        </p>
        <div class="d-flex gap-3 flex-wrap">
          <a href="/omr-local-job-listings/" class="btn btn-light btn-lg fw-semibold">
            <i class="fas fa-briefcase me-2 text-success"></i>Browse Jobs
          </a>
          <a href="/omr-local-events/" class="btn btn-outline-light btn-lg fw-semibold">
            <i class="fas fa-calendar me-2"></i>See Events
          </a>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="row g-3">
          <div class="col-6">
            <div class="stat-chip">
              <div class="num">10K+</div>
              <div class="lbl">Monthly Visitors</div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-chip">
              <div class="num">500+</div>
              <div class="lbl">Businesses Listed</div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-chip">
              <div class="num">1K+</div>
              <div class="lbl">Jobs Posted</div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-chip">
              <div class="num">15+</div>
              <div class="lbl">Localities Covered</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES GRID -->
<section class="py-5">
  <div class="container">
    <h2 class="section-heading text-center mb-2">What You Can Do on MyOMR</h2>
    <p class="text-center text-muted mb-5">Everything you need to live, work, and thrive in OMR — in one place.</p>
    <div class="row g-4">

      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-newspaper"></i></div>
          <div class="feature-title">Local News &amp; Updates</div>
          <div class="feature-desc">Stay informed with OMR-specific news — road works, infrastructure projects, business openings, community developments.</div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-briefcase"></i></div>
          <div class="feature-title">Jobs in OMR</div>
          <div class="feature-desc">Browse and apply to local job opportunities — IT, teaching, healthcare, hospitality, and more. Employers post free.</div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-calendar-alt"></i></div>
          <div class="feature-title">Events &amp; Activities</div>
          <div class="feature-desc">Discover festivals, workshops, community meetups, and local activities happening near you across OMR.</div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-store"></i></div>
          <div class="feature-title">Business Directory</div>
          <div class="feature-desc">Find verified local restaurants, schools, hospitals, IT companies, banks, ATMs, and service providers in OMR.</div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-home"></i></div>
          <div class="feature-title">Hostels &amp; PG Listings</div>
          <div class="feature-desc">Browse verified PG accommodations, hostels, and rental properties along OMR with direct contact details.</div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-laptop"></i></div>
          <div class="feature-title">Coworking Spaces</div>
          <div class="feature-desc">Find coworking spaces, serviced offices, and hot-desking options available in the OMR corridor.</div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="section-heading text-center mb-5">How MyOMR Works</h2>
    <div class="row g-4">
      <div class="col-md-3 col-sm-6 how-step">
        <div class="step-num">1</div>
        <h5 class="fw-600 mb-2">Browse Freely</h5>
        <p class="text-muted small">Explore news, jobs, events, and the business directory — no account required, always free.</p>
      </div>
      <div class="col-md-3 col-sm-6 how-step">
        <div class="step-num">2</div>
        <h5 class="fw-600 mb-2">Find What You Need</h5>
        <p class="text-muted small">Search by locality, category, or keyword to find the most relevant local content for you.</p>
      </div>
      <div class="col-md-3 col-sm-6 how-step">
        <div class="step-num">3</div>
        <h5 class="fw-600 mb-2">Post &amp; List</h5>
        <p class="text-muted small">Employers post jobs free. Businesses get listed. Community members submit events — all easy.</p>
      </div>
      <div class="col-md-3 col-sm-6 how-step">
        <div class="step-num">4</div>
        <h5 class="fw-600 mb-2">Stay Connected</h5>
        <p class="text-muted small">Subscribe to our weekly OMR events digest and stay on top of what's happening in your community.</p>
      </div>
    </div>
  </div>
</section>

<!-- AREAS COVERED -->
<section class="py-4">
  <div class="container">
    <div class="areas-strip p-4">
      <h5 class="section-heading mb-3"><i class="fas fa-map-marker-alt me-2"></i>Covering the Entire OMR Corridor</h5>
      <div>
        <?php
        $areas = ['Perungudi','Thoraipakkam','Karapakkam','Kandhanchavadi','Sholinganallur',
                  'Navalur','Thazhambur','Kelambakkam','Siruseri','Mettukuppam',
                  'Semmancheri','Perumbakkam','Egattur','Padur','Tidel Park area','Siruseri IT Park'];
        foreach ($areas as $a): ?>
          <span class="area-pill"><?= htmlspecialchars($a) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-5">
  <div class="container">
    <div class="cta-block p-5 text-center">
      <h2 class="mb-3">Start Exploring OMR Today</h2>
      <p class="mb-4" style="opacity:.9;max-width:520px;margin-inline:auto;">
        Everything in OMR, in one place. Free to browse. Easy to use. Built for the community.
      </p>
      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="/omr-local-job-listings/" class="btn btn-light btn-lg fw-semibold">
          <i class="fas fa-briefcase me-2 text-success"></i>Browse Jobs
        </a>
        <a href="/omr-local-events/" class="btn btn-outline-light btn-lg fw-semibold">
          <i class="fas fa-calendar me-2"></i>Discover Events
        </a>
        <a href="/local-news/" class="btn btn-outline-light btn-lg fw-semibold">
          <i class="fas fa-newspaper me-2"></i>Read Local News
        </a>
      </div>
    </div>
  </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
