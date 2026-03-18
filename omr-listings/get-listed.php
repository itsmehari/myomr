<?php
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE);

require '../core/omr-connect.php';
require_once '../core/security-helpers.php';

// Create submissions table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS omr_it_company_submissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_name VARCHAR(255) NOT NULL,
  address TEXT NOT NULL,
  locality VARCHAR(100) DEFAULT NULL,
  website VARCHAR(255) DEFAULT NULL,
  contact_name VARCHAR(150) DEFAULT NULL,
  email VARCHAR(255) DEFAULT NULL,
  phone VARCHAR(30) DEFAULT NULL,
  industry_type VARCHAR(150) DEFAULT NULL,
  message TEXT DEFAULT NULL,
  featured_requested TINYINT(1) DEFAULT 0,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  approved_company_slno INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$title = 'Get Your IT Company Listed on OMR';
$success = '';
$error = '';

$allowedLocalities = [
  'Perungudi','Kandhanchavadi','Thoraipakkam','Karapakkam','Mettukuppam','Sholinganallur',
  'Semmancheri','Navalur','Siruseri','Kelambakkam','Egattur','Perumbakkam'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  session_start();
  $csrf_ok = verify_csrf_token($_POST['csrf_token'] ?? '');
  $honeypot = trim($_POST['website_url'] ?? '');
  $rate_ok = check_rate_limit($_SERVER['REMOTE_ADDR'] ?? 'unknown', 5, 300);

  if (!$csrf_ok || $honeypot !== '' || !$rate_ok) {
    $error = 'Submission blocked. Please try again later.';
  } else {
    $company_name = trim($_POST['company_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $locality = trim($_POST['locality'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $contact_name = trim($_POST['contact_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $industry_type = trim($_POST['industry_type'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $featured_requested = isset($_POST['featured_requested']) ? 1 : 0;
    $desired_tier = in_array(($_POST['desired_tier'] ?? 'free'), ['free','verified','featured'], true) ? $_POST['desired_tier'] : 'free';
    $logo_url = trim($_POST['logo_url'] ?? '');
    if ($desired_tier === 'featured') { $featured_requested = 1; }

    // Basic validation
    if ($company_name === '' || $address === '' || ($email === '' && $phone === '')) {
      $error = 'Please provide company name, address, and at least one contact (email or phone).';
    } elseif ($email !== '' && !validate_email($email)) {
      $error = 'Please enter a valid email address.';
    } elseif ($phone !== '' && !validate_phone($phone)) {
      $error = 'Please enter a valid phone number.';
    } elseif ($website !== '' && !validate_url($website)) {
      $error = 'Please enter a valid website URL (with https://).';
    } else {
      // Insert submission
      $stmt = $conn->prepare('INSERT INTO omr_it_company_submissions (company_name, address, locality, website, contact_name, email, phone, industry_type, message, featured_requested, desired_tier, logo_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
      $stmt->bind_param(
        'sssssssssis',
        $company_name,
        $address,
        $locality,
        $website,
        $contact_name,
        $email,
        $phone,
        $industry_type,
        $message,
        $featured_requested,
        $desired_tier,
        $logo_url
      );
      if ($stmt->execute()) {
        $success = 'Thanks! Your submission has been received. Our team will verify and publish shortly.';
        // Email notify
        $to = 'info@myomr.in';
        $subject = 'New IT Company Listing Submission - MyOMR';
        $body = "Company: {$company_name}\nAddress: {$address}\nLocality: {$locality}\nWebsite: {$website}\nContact: {$contact_name}\nEmail: {$email}\nPhone: {$phone}\nIndustry: {$industry_type}\nFeatured? " . ($featured_requested ? 'Yes' : 'No') . "\n\nMessage:\n{$message}";
        @mail($to, $subject, $body, 'From: noreply@myomr.in');
        // Clear POST values on success
        $_POST = [];
      } else {
        $error = 'There was an error submitting your details. Please try again.';
      }
      $stmt->close();
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
$page_title          = 'Get Listed | IT Companies on OMR, Chennai | MyOMR';
$page_description    = 'Get your IT company listed on MyOMR directory. Submit your details for verification and optional featured placement.';
$canonical_url       = 'https://myomr.in/omr-listings/get-listed.php';
$og_title            = 'Get Listed | IT Companies on OMR, Chennai | MyOMR';
$og_description      = 'Submit your IT company details to appear in the OMR IT directory.';
$og_image            = 'https://myomr.in/My-OMR-Logo.png';
$og_url              = 'https://myomr.in/omr-listings/get-listed.php';
?>
<?php $breadcrumbs = [
  ['https://myomr.in/','Home'],
  ['https://myomr.in/omr-listings/it-companies.php','IT Companies'],
  ['https://myomr.in/omr-listings/get-listed.php','Get Listed']
]; ?>
<?php include '../components/meta.php'; ?>
<?php include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
<meta name="robots" content="index, follow">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; }
</style>
</head>
<body>
<?php include '../components/main-nav.php'; ?>
<div class="container" style="max-width:1280px;">
  <h1 class="mt-4" style="color:#14532d;">Get Your IT Company Listed</h1>
  <p>Submit your details to appear in the OMR IT directory. We verify every entry. Featured placement is available.</p>

  <?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
    <script>
    (function() {
      if (typeof gtag !== 'function') return;
      gtag('event', 'generate_lead', {
        'conversion_type': 'listing_submitted',
        'company_name':    <?= json_encode($company_name ?? '') ?>,
        'locality':        <?= json_encode($locality ?? '') ?>
      });
    })();
    </script>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
  <?php endif; ?>

  <form id="get-listed-form" method="post" action="">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
    <input type="text" name="website_url" value="" style="display:none">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Company Name</label>
        <input type="text" class="form-control" name="company_name" value="<?php echo htmlspecialchars($_POST['company_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Industry Type</label>
        <input type="text" class="form-control" name="industry_type" value="<?php echo htmlspecialchars($_POST['industry_type'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="IT Services, Product, SaaS, etc.">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label">Address</label>
      <textarea class="form-control" name="address" rows="2" required><?php echo htmlspecialchars($_POST['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
    </div>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Locality</label>
        <select name="locality" class="form-select">
          <option value="">Select locality</option>
          <?php foreach ($allowedLocalities as $loc): ?>
            <option value="<?php echo htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?>" <?php echo (($_POST['locality'] ?? '') === $loc ? 'selected' : ''); ?>><?php echo htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Website</label>
        <input type="url" class="form-control" name="website" placeholder="https://" value="<?php echo htmlspecialchars($_POST['website'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Contact Person</label>
        <input type="text" class="form-control" name="contact_name" value="<?php echo htmlspecialchars($_POST['contact_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
      </div>
    </div>
    <div class="row g-3 mt-0">
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
      </div>
    </div>
    <div class="mb-3 mt-3">
      <label class="form-label">Anything else to share?</label>
      <textarea class="form-control" name="message" rows="3"><?php echo htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
    </div>
    
    <hr>
    <h4>Choose your plan</h4>
    <div class="row">
      <div class="col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">Free</h5>
            <div class="fw-bold text-muted mb-2">₹0 — Always free</div>
            <ul class="mb-2 small">
              <li>Standard directory listing</li>
              <li>Contact info displayed</li>
              <li>Search-visible entry</li>
            </ul>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="desired_tier" id="tier_free" value="free" <?php echo (($_POST['desired_tier'] ?? 'free') === 'free') ? 'checked' : ''; ?>>
              <label class="form-check-label fw-semibold" for="tier_free">Select Free</label>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100" style="border-color:#14532d;">
          <div class="card-body">
            <h5 class="card-title" style="color:#14532d;">Verified</h5>
            <div class="fw-bold mb-2" style="color:#14532d;">₹999 / year</div>
            <ul class="mb-2 small">
              <li>Verified trust badge on listing</li>
              <li>Priority in locality filter results</li>
              <li>Annual review &amp; update by our team</li>
              <li>Google Maps link integration</li>
            </ul>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="desired_tier" id="tier_verified" value="verified" <?php echo (($_POST['desired_tier'] ?? '') === 'verified') ? 'checked' : ''; ?>>
              <label class="form-check-label fw-semibold" for="tier_verified">Select Verified</label>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100" style="border-color:#f59e0b;">
          <div class="card-body">
            <h5 class="card-title" style="color:#92400e;">Featured</h5>
            <div class="fw-bold mb-2" style="color:#92400e;">₹2,499 / year</div>
            <ul class="mb-2 small">
              <li>Featured card on directory homepage</li>
              <li>Custom business blurb &amp; logo</li>
              <li>Sponsored placement in search</li>
              <li>Promoted in OMR community posts</li>
            </ul>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="desired_tier" id="tier_featured" value="featured" <?php echo (($_POST['desired_tier'] ?? '') === 'featured') ? 'checked' : ''; ?>>
              <label class="form-check-label fw-semibold" for="tier_featured">Select Featured</label>
            </div>
            <div class="mt-2">
              <label class="form-label small">Logo URL (optional)</label>
              <input type="url" class="form-control form-control-sm" name="logo_url" value="<?php echo htmlspecialchars($_POST['logo_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://...">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group form-check mt-3">
      <input class="form-check-input" type="checkbox" id="featured_requested" name="featured_requested" <?php echo (($_POST['featured_requested'] ?? '') ? 'checked' : ''); ?>>
      <label class="form-check-label" for="featured_requested">I'm interested in featured placement</label>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="/omr-listings/it-companies.php" class="btn btn-link">Back to IT companies</a>
  </form>

  <div class="my-4"></div>
  <?php include '../components/subscribe.php'; ?>
</div>

<?php include '../components/footer.php'; ?>
<script>
  (function(){
    var form = document.getElementById('get-listed-form');
    if (!form) return;
    form.addEventListener('submit', function(){
      if (typeof window.gtag !== 'function') return;
      var tierEl = document.querySelector('input[name="desired_tier"]:checked');
      var tier = tierEl ? tierEl.value : 'free';
      var name = (document.querySelector('input[name="company_name"]')||{}).value || '';
      window.gtag('event', 'listing_submission_start', {
        event_category: 'it_company_submit',
        event_label: name,
        desired_tier: tier
      });
    });
  })();
</script>
<?php if (!empty($success) && isset($desired_tier, $company_name)): ?>
<script>
  if (typeof window.gtag === 'function') {
    window.gtag('event', 'listing_submission_success', {
      event_category: 'it_company_submit',
      event_label: <?php echo json_encode($company_name, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>,
      desired_tier: <?php echo json_encode($desired_tier, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
    });
  }
</script>
<?php endif; ?>
</body>
</html>


