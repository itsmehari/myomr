<?php
/**
 * Job seeker profile — upload résumé, create/update candidate profile for OMR jobs
 */
require_once __DIR__ . '/includes/error-reporting.php';

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
}
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';
require_once ROOT_PATH . '/core/omr-connect.php';
require_once ROOT_PATH . '/core/security-helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = $_SESSION['candidate_profile_errors'] ?? [];
$old = $_SESSION['candidate_profile_old'] ?? [];
unset($_SESSION['candidate_profile_errors'], $_SESSION['candidate_profile_old']);

$success = isset($_GET['success']) && $_GET['success'] === '1';

$profiles_table_ready = false;
if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $tc = $conn->query("SHOW TABLES LIKE 'job_seeker_profiles'");
    $profiles_table_ready = $tc && $tc->num_rows > 0;
}

$page_title = 'Free job seeker profile & CV upload for OMR Chennai | MyOMR';
$page_description = 'Create a free job seeker profile on MyOMR: upload your résumé (PDF/DOC/DOCX), add skills and preferred OMR locality (Perungudi, Sholinganallur, Navalur & more). Browse and apply to Chennai IT corridor jobs.';
$page_keywords = 'OMR Chennai jobs, job seeker profile, upload CV Chennai, résumé OMR, Old Mahabalipuram Road careers, Perungudi jobs, fresher jobs OMR';
$canonical_url = 'https://myomr.in/omr-local-job-listings/candidate-profile-omr.php';
$csrf = generate_csrf_token();
$ga_custom_params = ['content_type' => 'job_seeker_profile'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>
<meta name="description" content="<?= htmlspecialchars($page_description) ?>">
<meta name="keywords" content="<?= htmlspecialchars($page_keywords) ?>">
<link rel="canonical" href="<?= htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
<meta property="og:description" content="<?= htmlspecialchars($page_description) ?>">
<meta property="og:url" content="<?= htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:type" content="website">
<meta property="og:image" content="https://myomr.in/My-OMR-Logo.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($page_description) ?>">
<meta name="twitter:image" content="https://myomr.in/My-OMR-Logo.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="assets/job-portal-2026.css">
<link rel="stylesheet" href="/assets/css/footer.css">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": <?= json_encode($page_title, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
  "description": <?= json_encode($page_description, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
  "url": <?= json_encode($canonical_url, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://myomr.in/"},
    {"@type": "ListItem", "position": 2, "name": "Jobs in OMR", "item": "https://myomr.in/omr-local-job-listings/"},
    {"@type": "ListItem", "position": 3, "name": "Job seeker profile", "item": <?= json_encode($canonical_url, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>}
  ]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {"@type": "Question", "name": "Is the MyOMR job seeker profile free?", "acceptedAnswer": {"@type": "Answer", "text": "Yes. Creating a profile and uploading your résumé is free. You can still browse all OMR job listings and apply to each role at no charge."}},
    {"@type": "Question", "name": "What file types can I upload?", "acceptedAnswer": {"@type": "Answer", "text": "You can upload a résumé in PDF, Microsoft Word (.doc or .docx), up to 2 MB."}},
    {"@type": "Question", "name": "How do I update my profile?", "acceptedAnswer": {"@type": "Answer", "text": "Submit the form again using the same email address. You can upload a new résumé or keep the existing file by leaving the file field empty."}}
  ]
}
</script>
<?php include ROOT_PATH . '/components/analytics.php'; ?>
</head>
<body class="job-portal-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<section class="jp-hero jp-hero--compact">
  <div class="container">
    <div class="row justify-content-center text-center text-white">
      <div class="col-lg-10">
        <p class="mb-2 jp-candidate-hero-kicker"><i class="fas fa-user-tie me-1"></i> For job seekers on OMR</p>
        <h1 class="jp-hero__headline mb-3">Free job seeker profile for OMR Chennai — upload your résumé</h1>
        <p class="jp-hero__sub mb-0">Upload your CV once, add your skills and preferred locality along Old Mahabalipuram Road (Perungudi to Kelambakkam). Browsing and applying to listings stays free. Employers use MyOMR hiring tools separately; optional outreach from MyOMR is only if you agree below.</p>
        <div class="mt-4 d-flex flex-wrap justify-content-center gap-2">
          <a href="/omr-local-job-listings/" class="btn btn-light fw-semibold rounded-pill px-4"><i class="fas fa-search me-1"></i> Browse all jobs</a>
          <a href="#candidate-profile-form" class="btn jp-candidate-cta-btn fw-semibold rounded-pill px-4"><i class="fas fa-file-upload me-1"></i> Upload résumé &amp; create profile</a>
        </div>
      </div>
    </div>
  </div>
</section>

<main class="py-5">
  <div class="container">

    <section class="jp-candidate-benefits mb-5" aria-labelledby="why-profile-heading">
      <h2 id="why-profile-heading" class="h4 text-center mb-4 fw-bold" style="color:var(--omr-dark);">Why create a profile on MyOMR?</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="jp-candidate-step-card h-100">
            <div class="jp-candidate-step-num">1</div>
            <h3 class="h5 fw-bold" style="color:var(--omr-green-dark);"><i class="fas fa-file-alt text-primary me-2"></i>Upload your résumé</h3>
            <p class="text-muted mb-0">Add a PDF or Word CV in seconds. Your file is stored securely and linked to your profile so you are not starting from scratch every time you find a role.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="jp-candidate-step-card h-100">
            <div class="jp-candidate-step-num">2</div>
            <h3 class="h5 fw-bold" style="color:var(--omr-green-dark);"><i class="fas fa-id-card text-primary me-2"></i>Build your candidate profile</h3>
            <p class="text-muted mb-0">Share a short headline, skills, experience level, and the OMR areas you prefer (for example Perungudi or Navalur). That context helps match you with the right local openings.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="jp-candidate-step-card h-100">
            <div class="jp-candidate-step-num">3</div>
            <h3 class="h5 fw-bold" style="color:var(--omr-green-dark);"><i class="fas fa-rocket text-primary me-2"></i>Apply and stay visible</h3>
            <p class="text-muted mb-0">Keep using the job board as usual — apply to any listing in one flow. Your saved profile and CV help you move faster on repeat applications and help MyOMR highlight local talent when employers ask for referrals (only if you opt in below).</p>
          </div>
        </div>
      </div>
    </section>

    <?php if ($success): ?>
    <div class="alert alert-success shadow-sm border-0 mb-4" role="status">
      <strong>Profile saved.</strong> Thank you — your résumé and details are on file. <a href="/omr-local-job-listings/" class="alert-link">Browse jobs</a> and use <em>Apply</em> on any role you like.
    </div>
    <?php endif; ?>

    <?php if (!$profiles_table_ready): ?>
    <div class="alert alert-warning shadow-sm border-0 mb-4" role="alert">
      Candidate profiles are not activated on this server yet. You can still <a href="/omr-local-job-listings/">browse jobs and apply</a> with your résumé on each application. (Administrators: run <code>dev-tools/migrations/2026-03-31-job-seeker-profiles.sql</code>.)
    </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger shadow-sm border-0 mb-4" role="alert">
      <ul class="mb-0 ps-3">
        <?php foreach ($errors as $err): ?>
        <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm jp-candidate-form-card" id="candidate-profile-form">
          <div class="card-body p-4 p-md-5">
            <h2 class="h4 mb-2 fw-bold" style="color:var(--omr-dark);">Your details</h2>
            <p class="text-muted small mb-4">Fields marked <span class="text-danger">*</span> are required. Use the same email if you update your profile later.</p>

            <form method="post" action="process-candidate-profile-omr.php" enctype="multipart/form-data" novalidate>
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
              <div class="visually-hidden" aria-hidden="true">
                <label for="company_website">Leave blank</label>
                <input type="text" name="company_website" id="company_website" value="" tabindex="-1" autocomplete="off">
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold" for="full_name">Full name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="full_name" name="full_name" required maxlength="120"
                         value="<?= htmlspecialchars($old['full_name'] ?? '') ?>"
                         <?= $profiles_table_ready ? '' : 'disabled' ?>>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold" for="email">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" id="email" name="email" required maxlength="190" autocomplete="email"
                         value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                         <?= $profiles_table_ready ? '' : 'disabled' ?>>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold" for="phone">Phone <span class="text-danger">*</span></label>
                  <input type="tel" class="form-control" id="phone" name="phone" required maxlength="32" autocomplete="tel"
                         placeholder="10-digit mobile"
                         value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                         <?= $profiles_table_ready ? '' : 'disabled' ?>>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold" for="preferred_locality">Preferred locality on OMR</label>
                  <input type="text" class="form-control" id="preferred_locality" name="preferred_locality" maxlength="120"
                         placeholder="e.g. Sholinganallur, Navalur"
                         value="<?= htmlspecialchars($old['preferred_locality'] ?? '') ?>"
                         <?= $profiles_table_ready ? '' : 'disabled' ?>>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold" for="experience_level">Experience</label>
                  <select class="form-select" id="experience_level" name="experience_level" <?= $profiles_table_ready ? '' : 'disabled' ?>>
                    <?php
                    $exp_opts = ['' => 'Select…', 'Fresher' => 'Fresher', 'Junior' => '1–3 years', 'Mid-level' => '3–5 years', 'Senior' => '5+ years', 'Lead' => 'Lead / Manager', 'Any' => 'Any / open'];
                    $sel = $old['experience_level'] ?? '';
                    foreach ($exp_opts as $val => $label) {
                        $s = ($sel === $val) ? ' selected' : '';
                        echo '<option value="' . htmlspecialchars($val) . '"' . $s . '>' . htmlspecialchars($label) . '</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold" for="headline">Professional headline</label>
                  <input type="text" class="form-control" id="headline" name="headline" maxlength="255"
                         placeholder="e.g. Accounts executive · BPO team lead"
                         value="<?= htmlspecialchars($old['headline'] ?? '') ?>"
                         <?= $profiles_table_ready ? '' : 'disabled' ?>>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold" for="skills_summary">Skills &amp; highlights</label>
                  <textarea class="form-control" id="skills_summary" name="skills_summary" rows="4" maxlength="4000"
                            placeholder="Short list: tools, languages, certifications, industries…"
                            <?= $profiles_table_ready ? '' : 'disabled' ?>><?= htmlspecialchars($old['skills_summary'] ?? '') ?></textarea>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold" for="resume">Résumé (PDF, DOC, DOCX) <span class="text-danger">*</span></label>
                  <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                         <?= $profiles_table_ready ? '' : 'disabled' ?>>
                  <div class="form-text">Max 2 MB. New uploads replace your previous file if you already have a profile.</div>
                </div>
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="consent_privacy" id="consent_privacy" value="1" required <?= $profiles_table_ready ? '' : 'disabled' ?>>
                    <label class="form-check-label small" for="consent_privacy">I agree to the <a href="/info/website-privacy-policy-of-my-omr.php" target="_blank" rel="noopener">Privacy Policy</a> and to MyOMR storing my details and résumé for this job seeker profile. <span class="text-danger">*</span></label>
                  </div>
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="consent_outreach" id="consent_outreach" value="1" <?= !empty($old['consent_outreach']) ? ' checked' : '' ?><?= $profiles_table_ready ? '' : ' disabled' ?>>
                    <label class="form-check-label small" for="consent_outreach">Optional: MyOMR may contact me about relevant OMR job opportunities or share my profile with verified local employers when it fits my skills (I can withdraw this anytime via <a href="mailto:myomrnews@gmail.com">myomrnews@gmail.com</a>).</label>
                  </div>
                </div>
              </div>

              <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-lg fw-semibold jp-candidate-submit-btn" <?= $profiles_table_ready ? '' : 'disabled' ?>>
                  <i class="fas fa-paper-plane me-2"></i>Save profile &amp; résumé
                </button>
              </div>
            </form>
          </div>
        </div>

        <p class="text-center text-muted small mt-4 mb-0">
          <a href="/info/website-privacy-policy-of-my-omr.php">How we use your data</a> · Employers use <a href="employer-landing-omr.php">MyOMR hiring tools</a> separately. We never charge job seekers to browse or apply. Profiles and résumés are kept while your account data is needed for the service and removed on request where the law allows.
        </p>
      </div>
    </div>
  </div>
</main>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
if (typeof gtag === 'function') {
  gtag('event', 'job_seeker_profile_view', { event_category: 'jobs', event_label: 'candidate_profile_omr' });
}
</script>
<?php if ($success): ?>
<script>
if (typeof gtag === 'function') {
  gtag('event', 'job_seeker_profile_submit', { event_category: 'jobs', event_label: 'success' });
}
</script>
<?php endif; ?>
</body>
</html>
