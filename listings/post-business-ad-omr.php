<?php
/**
 * Advertise Your Business in OMR — Inquiry Page
 * Rebuilt from placeholder to full advertising inquiry page
 */

require_once '../core/email.php';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $biz_name  = htmlspecialchars(trim($_POST['biz_name']  ?? ''), ENT_QUOTES, 'UTF-8');
    $contact   = htmlspecialchars(trim($_POST['contact']   ?? ''), ENT_QUOTES, 'UTF-8');
    $phone     = htmlspecialchars(trim($_POST['phone']     ?? ''), ENT_QUOTES, 'UTF-8');
    $email     = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $ad_type   = htmlspecialchars(trim($_POST['ad_type']   ?? ''), ENT_QUOTES, 'UTF-8');
    $locality  = htmlspecialchars(trim($_POST['locality']  ?? ''), ENT_QUOTES, 'UTF-8');
    $message   = htmlspecialchars(trim($_POST['message']   ?? ''), ENT_QUOTES, 'UTF-8');

    if (empty($biz_name) || (empty($phone) && empty($email))) {
        $error = 'Please provide your business name and at least one contact (phone or email).';
    } elseif ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $to      = 'info@myomr.in';
        $subject = 'New Advertising Inquiry — ' . $biz_name . ' | MyOMR';
        $body    = renderEmailTemplate('Advertising Inquiry — MyOMR', "
            <h2 style='color:#14532d;'>New Business Advertising Inquiry</h2>
            <table style='width:100%;border-collapse:collapse;'>
              <tr><td style='padding:6px 0;font-weight:600;width:140px;'>Business Name</td><td>{$biz_name}</td></tr>
              <tr><td style='padding:6px 0;font-weight:600;'>Contact Person</td><td>{$contact}</td></tr>
              <tr><td style='padding:6px 0;font-weight:600;'>Phone</td><td>{$phone}</td></tr>
              <tr><td style='padding:6px 0;font-weight:600;'>Email</td><td>{$email}</td></tr>
              <tr><td style='padding:6px 0;font-weight:600;'>Ad Package</td><td>{$ad_type}</td></tr>
              <tr><td style='padding:6px 0;font-weight:600;'>Locality</td><td>{$locality}</td></tr>
              <tr><td style='padding:6px 0;font-weight:600;vertical-align:top;'>Message</td><td>" . nl2br($message ?: 'No message provided') . "</td></tr>
            </table>
        ");

        if (sendEmail($to, $subject, $body, 'noreply@myomr.in', 'MyOMR Advertising')) {
            $success = 'Your inquiry has been received! Our team will contact you within 24 hours.';
            $_POST   = [];
        } else {
            $error = 'We could not send your message right now. Please WhatsApp us at +91-94450 88028.';
        }
    }
}

$page_title    = 'Advertise Your Business in OMR Chennai | MyOMR';
$page_desc     = 'Reach 10,000+ monthly visitors in OMR Chennai. Advertise your business with banner ads, sponsored listings, or featured directory placement on MyOMR.in.';
$canonical_url = 'https://myomr.in/listings/post-business-ad-omr.php';
$og_image      = 'https://myomr.in/My-OMR-Logo.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
  <link rel="canonical" href="<?= $canonical_url ?>">
  <meta property="og:title"       content="<?= htmlspecialchars($page_title) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_desc) ?>">
  <meta property="og:url"         content="<?= $canonical_url ?>">
  <meta property="og:type"        content="website">
  <meta property="og:image"       content="<?= $og_image ?>">
  <meta property="og:site_name"   content="MyOMR">
  <meta name="twitter:card"       content="summary_large_image">
  <meta name="twitter:title"      content="<?= htmlspecialchars($page_title) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($page_desc) ?>">
  <meta name="twitter:image"      content="<?= $og_image ?>">
  <?php include '../components/analytics.php'; ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; }
    :root { --brand: #14532d; --brand-mid: #166534; --brand-accent: #22c55e; --brand-light: #f0fdf4; }

    .ad-hero {
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-mid) 100%);
      color: #fff; padding: 72px 0 56px;
    }
    .ad-hero h1 { font-weight: 700; }
    .stat-badge {
      background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3);
      border-radius: 8px; padding: 14px 20px; text-align: center;
    }
    .stat-badge .num { font-size: 1.8rem; font-weight: 700; }
    .stat-badge .lbl { font-size: .8rem; opacity: .85; }

    .package-card {
      border-radius: 14px; border: 2px solid #e5e7eb;
      padding: 32px 24px; background: #fff; height: 100%;
      transition: transform .2s, box-shadow .2s; position: relative;
    }
    .package-card:hover { transform: translateY(-5px); box-shadow: 0 14px 36px rgba(20,83,45,.1); }
    .package-card.highlight { border-color: var(--brand); }
    .package-icon { font-size: 2rem; color: var(--brand); margin-bottom: 1rem; }
    .package-name { font-size: 1.05rem; font-weight: 600; color: #1f2937; }
    .package-price { font-size: 1.4rem; font-weight: 700; color: var(--brand); }
    .package-price small { font-size: .85rem; font-weight: 400; color: #6b7280; }
    .package-feature { font-size: .875rem; color: #374151; padding: .25rem 0; }
    .package-feature i { color: var(--brand-accent); width: 18px; }

    .inquiry-form-card { border-radius: 16px; box-shadow: 0 8px 32px rgba(20,83,45,.08); border: none; }
    .form-label { font-size: .875rem; font-weight: 500; color: #374151; }
    .form-control:focus, .form-select:focus { border-color: var(--brand); box-shadow: 0 0 0 .2rem rgba(20,83,45,.15); }
    .btn-submit { background: var(--brand); color: #fff; font-weight: 600; padding: 12px 32px; border-radius: 8px; border: none; font-size: 1rem; transition: background .2s; width: 100%; }
    .btn-submit:hover { background: #0f3d1f; color: #fff; }
    .section-heading { font-weight: 700; color: var(--brand); }
    .trust-strip { background: var(--brand-light); border-radius: 12px; padding: 20px 28px; }
    .trust-item { font-size: .875rem; color: #374151; font-weight: 500; }
    .trust-item i { color: var(--brand); }
  </style>
</head>
<body>

<?php include '../components/main-nav.php'; ?>

<!-- HERO -->
<section class="ad-hero">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <span class="badge bg-success bg-opacity-25 text-white px-3 py-2 mb-3 d-inline-block" style="border:1px solid rgba(255,255,255,.3);border-radius:20px;font-size:.82rem;">
          <i class="fas fa-bullhorn me-1"></i> Local Business Advertising
        </span>
        <h1 class="display-5 mb-3">Advertise Your Business<br>in OMR Chennai</h1>
        <p class="lead mb-4" style="opacity:.92;">
          Reach OMR's most engaged local audience — residents, professionals, and businesses
          across Perungudi, Sholinganallur, Navalur and the entire corridor. Hyper-local. Cost-effective. Effective.
        </p>
        <a href="#inquiry-form" class="btn btn-light btn-lg fw-semibold me-2">
          <i class="fas fa-paper-plane me-2 text-success"></i>Send Inquiry
        </a>
        <a href="https://wa.me/919445088028?text=Hi%2C+I+want+to+advertise+my+business+on+MyOMR.in" target="_blank" rel="noopener" class="btn btn-outline-light btn-lg fw-semibold">
          <i class="fab fa-whatsapp me-2"></i>WhatsApp Us
        </a>
      </div>
      <div class="col-lg-5">
        <div class="row g-3">
          <div class="col-6">
            <div class="stat-badge">
              <div class="num">10K+</div>
              <div class="lbl">Monthly Visitors</div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-badge">
              <div class="num">500+</div>
              <div class="lbl">Local Businesses</div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-badge">
              <div class="num">1K+</div>
              <div class="lbl">Active Job Seekers</div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-badge">
              <div class="num">15+</div>
              <div class="lbl">OMR Localities Covered</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- AD PACKAGES -->
<section class="py-5">
  <div class="container">
    <h2 class="section-heading text-center mb-2">Advertising Packages</h2>
    <p class="text-center text-muted mb-5">Choose the format that fits your goal — or mix and match.</p>
    <div class="row g-4">

      <!-- Banner Ads -->
      <div class="col-lg-4">
        <div class="package-card">
          <div class="package-icon"><i class="fas fa-image"></i></div>
          <div class="package-name mb-1">Banner Advertising</div>
          <div class="package-price mb-3">₹1,500 <small>/ month</small></div>
          <ul class="list-unstyled mb-0">
            <li class="package-feature"><i class="fas fa-check me-2"></i>Homepage banner placement</li>
            <li class="package-feature"><i class="fas fa-check me-2"></i>Sidebar banners across pages</li>
            <li class="package-feature"><i class="fas fa-check me-2"></i>Your logo + tagline + link</li>
            <li class="package-feature"><i class="fas fa-check me-2"></i>Mobile-responsive display</li>
            <li class="package-feature"><i class="fas fa-check me-2"></i>Monthly impression report</li>
          </ul>
        </div>
      </div>

      <!-- Sponsored Listing -->
      <div class="col-lg-4">
        <div class="package-card highlight">
          <div class="package-icon"><i class="fas fa-star"></i></div>
          <div class="package-name mb-1">Sponsored Listing</div>
          <div class="package-price mb-3">₹2,999 <small>/ month</small></div>
          <ul class="list-unstyled mb-0">
            <li class="package-feature"><i class="fas fa-check me-2"></i>"Sponsored" tag at top of directory</li>
            <li class="package-feature"><i class="fas fa-check me-2"></i>Priority position in search results</li>
            <li class="package-feature"><i class="fas fa-check me-2"></i>Custom business description &amp; photos</li>
            <li class="package-feature"><i class="fas fa-check me-2"></i>Click-to-call &amp; WhatsApp button</li>
            <li class="package-feature"><i class="fas fa-check me-2"></i>Promoted in OMR newsletter</li>
          </ul>
        </div>
      </div>

      <!-- Featured Directory -->
      <div class="col-lg-4">
        <div class="package-card">
          <div class="package-icon"><i class="fas fa-trophy"></i></div>
          <div class="package-name mb-1">Featured Directory</div>
          <div class="package-price mb-3">₹4,999 <small>/ year</small></div>
          <ul class="list-unstyled mb-0">
            <li class="package-feature"><i class="fas fa-check me-2"></i>Permanent featured card — homepage</li>
            <li class="package-feature"><i class="fas fa-check me-2"></i>Verified badge &amp; logo display</li>
            <li class="package-feature"><i class="fas fa-check me-2"></i>Priority across all locality pages</li>
            <li class="package-feature"><i class="fas fa-check me-2"></i>Social media post mention (monthly)</li>
            <li class="package-feature"><i class="fas fa-check me-2"></i>Google Business profile support</li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- TRUST STRIP -->
<section class="pb-4">
  <div class="container">
    <div class="trust-strip">
      <div class="row g-3 align-items-center">
        <div class="col-sm-6 col-md-3 text-center">
          <div class="trust-item"><i class="fas fa-map-marker-alt me-2"></i>Hyper-local OMR audience</div>
        </div>
        <div class="col-sm-6 col-md-3 text-center">
          <div class="trust-item"><i class="fas fa-mobile-alt me-2"></i>Mobile-first display</div>
        </div>
        <div class="col-sm-6 col-md-3 text-center">
          <div class="trust-item"><i class="fas fa-chart-line me-2"></i>Monthly performance report</div>
        </div>
        <div class="col-sm-6 col-md-3 text-center">
          <div class="trust-item"><i class="fas fa-headset me-2"></i>Dedicated local support</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- INQUIRY FORM -->
<section class="py-5 bg-light" id="inquiry-form">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card inquiry-form-card p-4 p-md-5">
          <h2 class="section-heading mb-1">Send an Advertising Inquiry</h2>
          <p class="text-muted mb-4">Our team will get back to you within 24 hours with a tailored proposal.</p>

          <?php if ($success): ?>
            <div class="alert alert-success d-flex align-items-center">
              <i class="fas fa-check-circle me-2"></i>
              <?= htmlspecialchars($success) ?>
            </div>
            <script>
              (function() {
                if (typeof gtag !== 'function') return;
                gtag('event', 'generate_lead', { 'conversion_type': 'ad_inquiry' });
              })();
            </script>
          <?php endif; ?>
          <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <?php if (!$success): ?>
          <form method="POST" action="#inquiry-form">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="biz_name">Business Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="biz_name" name="biz_name" required
                  value="<?= htmlspecialchars($_POST['biz_name'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label" for="contact">Contact Person</label>
                <input type="text" class="form-control" id="contact" name="contact"
                  value="<?= htmlspecialchars($_POST['contact'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label" for="phone">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone"
                  value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email"
                  value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label" for="ad_type">Ad Package Interested In</label>
                <select class="form-select" id="ad_type" name="ad_type">
                  <option value="" <?= empty($_POST['ad_type']) ? 'selected' : '' ?>>Select a package</option>
                  <option value="Banner Advertising" <?= (($_POST['ad_type'] ?? '') === 'Banner Advertising') ? 'selected' : '' ?>>Banner Advertising (₹1,500/mo)</option>
                  <option value="Sponsored Listing"  <?= (($_POST['ad_type'] ?? '') === 'Sponsored Listing')  ? 'selected' : '' ?>>Sponsored Listing (₹2,999/mo)</option>
                  <option value="Featured Directory"  <?= (($_POST['ad_type'] ?? '') === 'Featured Directory')  ? 'selected' : '' ?>>Featured Directory (₹4,999/yr)</option>
                  <option value="Not sure yet"        <?= (($_POST['ad_type'] ?? '') === 'Not sure yet')        ? 'selected' : '' ?>>Not sure yet — advise me</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="locality">Your Business Locality</label>
                <select class="form-select" id="locality" name="locality">
                  <option value="">Select locality</option>
                  <?php
                  $locs = ['Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Sholinganallur',
                           'Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam','Mettukuppam','Other'];
                  foreach ($locs as $l):
                    $sel = (($_POST['locality'] ?? '') === $l) ? 'selected' : '';
                  ?>
                    <option value="<?= htmlspecialchars($l) ?>" <?= $sel ?>><?= htmlspecialchars($l) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-12">
                <label class="form-label" for="message">Tell Us About Your Business &amp; Goals</label>
                <textarea class="form-control" id="message" name="message" rows="4"
                  placeholder="What do you want to promote? Who is your target customer?"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn-submit">
                  <i class="fas fa-paper-plane me-2"></i>Send Inquiry — We'll Reply in 24 Hours
                </button>
              </div>
            </div>
          </form>
          <?php endif; ?>

          <p class="text-center text-muted mt-3 mb-0" style="font-size:.83rem;">
            Prefer WhatsApp?
            <a href="https://wa.me/919445088028?text=Hi%2C+I+want+to+advertise+my+business+on+MyOMR.in" target="_blank" rel="noopener" class="text-success fw-semibold">
              <i class="fab fa-whatsapp"></i> Chat with us
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
