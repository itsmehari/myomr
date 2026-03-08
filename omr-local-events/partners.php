<?php
require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/../core/omr-connect.php';

$page_title = 'Partner with MyOMR Events — Badges, Embeds &amp; Free Listings for OMR Organisations';
$page_desc  = 'RWAs, colleges, NGOs and local organisations on OMR can embed the MyOMR Events badge, list events for free, and help their community stay connected.';
$og_image   = 'https://myomr.in/My-OMR-Logo.jpg';

// Pull live event count for social proof
$total_events  = 0;
$total_partners = 0;
if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $r = $conn->query("SELECT COUNT(*) AS c FROM omr_events WHERE status = 'approved'");
    if ($r) { $total_events = (int)($r->fetch_assoc()['c'] ?? 0); }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
  <link rel="canonical" href="https://myomr.in/omr-local-events/partners.php">
  <meta property="og:title"       content="<?= htmlspecialchars($page_title) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_desc) ?>">
  <meta property="og:url"         content="https://myomr.in/omr-local-events/partners.php">
  <meta property="og:type"        content="website">
  <meta property="og:image"       content="<?= $og_image ?>">
  <meta property="og:site_name"   content="MyOMR">
  <meta name="twitter:card"       content="summary_large_image">
  <meta name="twitter:title"      content="<?= htmlspecialchars($page_title) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($page_desc) ?>">
  <meta name="twitter:image"      content="<?= $og_image ?>">
  <?php include __DIR__ . '/../components/analytics.php'; ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; }
    :root { --brand: #14532d; --brand-mid: #166534; --brand-accent: #22c55e; --brand-light: #f0fdf4; }

    .partner-hero {
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-mid) 100%);
      color: #fff; padding: 72px 0 56px;
    }
    .partner-hero h1 { font-weight: 700; }
    .hero-badge-pill {
      display: inline-block; background: rgba(255,255,255,.15);
      border: 1px solid rgba(255,255,255,.3); border-radius: 20px;
      padding: 5px 18px; font-size: .84rem; margin-bottom: 1.25rem;
    }
    .stat-chip {
      background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.25);
      border-radius: 10px; padding: 18px; text-align: center;
    }
    .stat-chip .num { font-size: 1.7rem; font-weight: 700; }
    .stat-chip .lbl { font-size: .78rem; opacity: .85; }

    .benefit-card {
      border: 1px solid #e5e7eb; border-radius: 14px;
      padding: 28px 24px; height: 100%; transition: transform .2s, box-shadow .2s;
    }
    .benefit-card:hover { transform: translateY(-5px); box-shadow: 0 12px 32px rgba(20,83,45,.1); }
    .benefit-icon {
      width: 52px; height: 52px; border-radius: 12px;
      background: var(--brand-light); display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; color: var(--brand); margin-bottom: 1rem;
    }
    .benefit-title { font-weight: 600; color: #1f2937; margin-bottom: .4rem; }
    .benefit-desc { font-size: .875rem; color: #6b7280; }

    .embed-card { border-radius: 14px; border: 1px solid #e5e7eb; }
    .embed-card pre { background: #f8fafc; border-radius: 8px; font-size: .82rem; }

    .org-types-strip { background: #f9fafb; border-radius: 12px; }
    .org-pill {
      display: inline-block; background: #fff; border: 1px solid #d1fae5;
      color: var(--brand); font-size: .8rem; font-weight: 500;
      padding: 5px 16px; border-radius: 20px; margin: 3px;
    }
    .cta-block {
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-mid) 100%);
      border-radius: 16px; color: #fff;
    }
    .cta-block h2 { font-weight: 700; }
    .section-heading { font-weight: 700; color: var(--brand); }
    .copy-btn { font-size: .8rem; }
  </style>
</head>
<body class="modern-page">

<?php include __DIR__ . '/../components/main-nav.php'; ?>

<!-- HERO -->
<section class="partner-hero">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <div class="hero-badge-pill"><i class="fas fa-handshake me-1"></i> Community Partnership Programme</div>
        <h1 class="display-5 mb-3">Help OMR Discover<br>Your Events</h1>
        <p class="lead mb-4" style="opacity:.92;">
          RWAs, colleges, NGOs, cultural orgs and local businesses — embed the MyOMR Events badge
          on your website and list your events for free. Help your community stay connected to what's happening in OMR.
        </p>
        <div class="d-flex gap-3 flex-wrap">
          <a href="/omr-local-events/post-event-omr.php" class="btn btn-light btn-lg fw-semibold">
            <i class="fas fa-plus me-2 text-success"></i>List an Event Free
          </a>
          <a href="#embed-code" class="btn btn-outline-light btn-lg fw-semibold">
            <i class="fas fa-code me-2"></i>Get Badge Code
          </a>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="row g-3">
          <div class="col-6">
            <div class="stat-chip">
              <div class="num"><?= $total_events > 0 ? $total_events . '+' : '100+' ?></div>
              <div class="lbl">Events Listed</div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-chip">
              <div class="num">Free</div>
              <div class="lbl">Always Free to List</div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-chip">
              <div class="num">15+</div>
              <div class="lbl">OMR Localities</div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-chip">
              <div class="num">10K+</div>
              <div class="lbl">Monthly Visitors</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- WHY PARTNER -->
<section class="py-5">
  <div class="container">
    <h2 class="section-heading text-center mb-2">Why Partner with MyOMR Events?</h2>
    <p class="text-center text-muted mb-5">Zero cost. Maximum community reach across OMR.</p>
    <div class="row g-4">

      <div class="col-lg-4 col-md-6">
        <div class="benefit-card">
          <div class="benefit-icon"><i class="fas fa-users"></i></div>
          <div class="benefit-title">Reach OMR Residents Directly</div>
          <div class="benefit-desc">Your event is seen by 10,000+ monthly visitors who live and work in the OMR corridor — your exact audience.</div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="benefit-card">
          <div class="benefit-icon"><i class="fas fa-rupee-sign"></i></div>
          <div class="benefit-title">Completely Free</div>
          <div class="benefit-desc">No listing fees, no commissions, no account required. Submit your event, get it verified, and it goes live within 24 hours.</div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="benefit-card">
          <div class="benefit-icon"><i class="fas fa-code"></i></div>
          <div class="benefit-title">Embed Our Badge on Your Site</div>
          <div class="benefit-desc">Add a one-line HTML snippet to your website or newsletter and show your community you're connected to MyOMR Events.</div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- WHO CAN PARTNER -->
<section class="py-4">
  <div class="container">
    <div class="org-types-strip p-4">
      <h5 class="section-heading mb-3"><i class="fas fa-building me-2"></i>Who Can Partner</h5>
      <div>
        <?php
        $orgs = ['Resident Welfare Associations (RWAs)','Engineering Colleges','Arts & Science Colleges',
                 'Schools','Cultural Organisations','NGOs & Trusts','Temples & Religious Orgs',
                 'Fitness Centres & Gyms','Restaurants & Cafes','Corporate Offices (CSR events)','Hospitals & Clinics'];
        foreach ($orgs as $o): ?>
          <span class="org-pill"><?= htmlspecialchars($o) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- BADGE PREVIEW & EMBED CODE -->
<section class="py-5 bg-light" id="embed-code">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card embed-card p-4 p-md-5">
          <h2 class="section-heading mb-1">Event Partner Badge</h2>
          <p class="text-muted mb-4">Place this badge on your website, app, or email footer to show your community you list events on MyOMR.</p>

          <h6 class="fw-600 mb-2">Badge Preview</h6>
          <div class="mb-4 p-3 bg-white border rounded d-inline-block">
            <img src="/assets/img/myomr-events-badge.svg" alt="MyOMR Events Partner Badge" width="180" height="44"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='inline-block';">
            <span style="display:none;background:#14532d;color:#fff;padding:8px 20px;border-radius:6px;font-family:Poppins,sans-serif;font-size:.85rem;font-weight:600;">
              <i class="fas fa-calendar-alt me-1"></i> MyOMR Events
            </span>
          </div>

          <h6 class="fw-600 mb-2">Embed Code <small class="text-muted fw-normal">(copy &amp; paste into your website)</small></h6>
          <div class="position-relative">
            <pre class="p-3 mb-3" id="embedSnippet">&lt;a href="https://myomr.in/omr-local-events/?utm_source=partner&amp;utm_medium=badge&amp;utm_campaign=events_partners" target="_blank" rel="noopener"&gt;
  &lt;img src="https://myomr.in/assets/img/myomr-events-badge.svg" alt="MyOMR Events" width="180" height="44" /&gt;
&lt;/a&gt;</pre>
            <button class="btn btn-sm btn-outline-secondary copy-btn position-absolute top-0 end-0 m-2"
                    onclick="navigator.clipboard.writeText(document.getElementById('embedSnippet').innerText);this.textContent='Copied!';setTimeout(()=>this.textContent='Copy',2000);">
              <i class="fas fa-copy me-1"></i>Copy
            </button>
          </div>

          <div class="d-flex gap-3 flex-wrap mt-2">
            <a href="/omr-local-events/post-event-omr.php" class="btn btn-success btn-lg fw-semibold">
              <i class="fas fa-plus me-2"></i>List an Event
            </a>
            <a href="/omr-local-events/" class="btn btn-outline-secondary btn-lg fw-semibold">
              <i class="fas fa-eye me-2"></i>Browse Events
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-5">
  <div class="container">
    <div class="cta-block p-5 text-center">
      <h2 class="mb-3">Ready to Connect Your Community?</h2>
      <p class="mb-4" style="opacity:.9;max-width:500px;margin-inline:auto;">
        Submit your first event today — it's free, quick, and goes live within 24 hours.
      </p>
      <a href="/omr-local-events/post-event-omr.php" class="btn btn-light btn-lg fw-semibold">
        <i class="fas fa-calendar-plus me-2 text-success"></i>List Your Event Now
      </a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
