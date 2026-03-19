<?php
/**
 * MyOMR Job Portal — Job Detail Page (v3.0 2026 Overhaul)
 *
 * Highlights bar · WhatsApp Apply · Resume Upload · Social Proof
 */

require_once __DIR__ . '/includes/error-reporting.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
}
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';

require_once __DIR__ . '/includes/job-functions-omr.php';
require_once __DIR__ . '/includes/seo-helper.php';
require_once ROOT_PATH . '/core/omr-connect.php';
global $conn;

if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
    http_response_code(500);
    exit('<h1>Database Error — Please try again shortly.</h1>');
}

/* ── Load job ─────────────────────────────────────────────────── */
$job_id = max(0, (int)($_GET['id'] ?? 0));
if ($job_id <= 0) {
    require_once ROOT_PATH . '/core/serve-404.php';
    exit;
}

$job = getJobById($job_id);
$preview_mode = false;

if (!$job) {
    if (!empty($_SESSION['employer_id'])) {
        $ps = $conn->prepare(
            "SELECT j.*, e.company_name, e.contact_person,
                    e.email AS employer_email, e.phone AS employer_phone,
                    e.address AS company_address,
                    c.name AS category_name
             FROM job_postings j
             LEFT JOIN employers e ON j.employer_id = e.id
             LEFT JOIN job_categories c ON j.category = c.slug
             WHERE j.id = ? AND j.employer_id = ?"
        );
        if ($ps) {
            $eid = (int)$_SESSION['employer_id'];
            $ps->bind_param('ii', $job_id, $eid);
            $ps->execute();
            $job = $ps->get_result()->fetch_assoc() ?: null;
            if ($job) $preview_mode = true;
        }
    }

    if (!$job) {
        require_once ROOT_PATH . '/core/serve-404.php';
        exit;
    }
}

/* ── Increment views ──────────────────────────────────────────── */
incrementJobViews($job_id);

/* ── Related jobs ────────────────────────────────────────────── */
$related_jobs = getRelatedJobs($job_id, $job['category'], 4);

/* ── Related IT/career articles (for "Read" block) ────────────── */
$related_articles = [];
$it_cats = ['IT Career', 'Career Tips', 'OMR Jobs', 'Jobs', 'IT'];
$placeholders = implode(',', array_fill(0, count($it_cats), '?'));
$art_sql = "SELECT slug, title, published_date FROM articles WHERE status = 'published' AND slug NOT LIKE '%-tamil' AND (category IN ($placeholders) OR tags LIKE ? OR tags LIKE ?) ORDER BY published_date DESC LIMIT 3";
$art_stmt = $conn->prepare($art_sql);
if ($art_stmt) {
    $art_types = str_repeat('s', count($it_cats)) . 'ss';
    $art_params = array_merge($it_cats, ['%it-corridor%', '%it-career%']);
    $art_stmt->bind_param($art_types, ...$art_params);
    $art_stmt->execute();
    $art_res = $art_stmt->get_result();
    while ($row = $art_res->fetch_assoc()) $related_articles[] = $row;
    $art_stmt->close();
}

/* ── Application state ───────────────────────────────────────── */
$already_applied = false;
if (!empty($_COOKIE['applicant_email'])) {
    $already_applied = hasUserApplied($job_id, $_COOKIE['applicant_email']);
}
$app_errors = $_SESSION['application_errors'] ?? [];
unset($_SESSION['application_errors']);
$form_data = $_SESSION['application_form_data'] ?? [];
if (empty($app_errors)) unset($_SESSION['application_form_data']);

/* ── SEO: canonical (user-friendly /job/15/slug) and geo-friendly title ─ */
$canonical    = getJobDetailUrl($job_id, $job['title'] ?? null);
$request_path = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$qs           = $_SERVER['QUERY_STRING'] ?? '';
$redirect_url = $canonical . ($qs ? '?' . $qs : '');

// 301 from old job-detail-omr.php?id= URL
if (isset($_GET['id']) && (strpos($_SERVER['REQUEST_URI'] ?? '', 'job-detail-omr.php') !== false)) {
    header('Location: ' . $redirect_url, true, 301);
    exit;
}
// 301 from job/15 (no slug) to job/15/slug when slug is available
if (preg_match('#/omr-local-job-listings/job/' . (int)$job_id . '$#', $request_path)) {
    $slug = createSlug($job['title'] ?? '');
    if ($slug !== '') {
        header('Location: ' . $redirect_url, true, 301);
        exit;
    }
}
$page_title   = htmlspecialchars($job['title']) . " at " . htmlspecialchars($job['company_name'] ?? '') . " – OMR Chennai | MyOMR";
$clean_desc   = trim(strip_tags($job['description'] ?? ''));
$page_desc    = mb_strimwidth($clean_desc, 0, 155, '…');
$location_seo = htmlspecialchars($job['location'] ?? 'OMR Chennai');

/* ── WhatsApp link ───────────────────────────────────────────── */
$phone_clean = preg_replace('/\D/', '', $job['employer_phone'] ?? '');
$wa_msg      = rawurlencode(
    "Hi, I found the \"{$job['title']}\" role at {$job['company_name']} on MyOMR.in and I'd like to apply. Could you share more details? Job link: $canonical"
);
$wa_href = $phone_clean
    ? "https://wa.me/{$phone_clean}?text={$wa_msg}"
    : "https://wa.me/?text={$wa_msg}";

/* ── Salary formatted ────────────────────────────────────────── */
$salary_display = (!empty($job['salary_range']) && $job['salary_range'] !== 'Not Disclosed')
    ? formatSalary($job['salary_range']) : 'Not Disclosed';

$apps_count = (int)($job['applications_count'] ?? 0);
$raw_work_segment = (string)($job['work_segment'] ?? '');
if ($raw_work_segment === '' && function_exists('inferJobSegmentFromData')) {
    $raw_work_segment = (string) inferJobSegmentFromData($job);
}
if ($raw_work_segment === '') {
    $raw_work_segment = 'office';
}
if (function_exists('normalizeJobSegment')) {
    $work_segment = normalizeJobSegment($raw_work_segment);
} else {
    // Deployment-safe fallback to avoid fatal errors when helper updates lag behind page updates.
    $v = strtolower(trim($raw_work_segment));
    $v = str_replace(['_', ' '], '-', $v);
    $fallback_segment_map = [
        'office'       => 'office',
        'white-collar' => 'office',
        'whitecollar'  => 'office',
        'manpower'     => 'manpower',
        'labour'       => 'manpower',
        'labor'        => 'manpower',
        'blue-collar'  => 'manpower',
        'bluecollar'   => 'manpower',
        'hybrid'       => 'hybrid',
    ];
    $work_segment = $fallback_segment_map[$v] ?? $v;
}
$posted_label = timeAgo($job['created_at'] ?? '');
$posted_date = date('d M Y', strtotime($job['created_at'] ?? 'now'));
$application_deadline_label = (!empty($job['application_deadline']) && $job['application_deadline'] !== '0000-00-00')
    ? date('d M Y', strtotime($job['application_deadline']))
    : 'Open until filled';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $page_title ?></title>
<meta name="description" content="<?= $page_desc ?>">
<meta name="keywords" content="<?= htmlspecialchars($job['title']) ?>, <?= $location_seo ?>, jobs in OMR Chennai, Old Mahabalipuram Road jobs, <?= htmlspecialchars($job['category_name'] ?? $job['category'] ?? '') ?>">
<meta name="geo.region" content="IN-TN">
<link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">

<meta property="og:title"       content="<?= htmlspecialchars($job['title']) ?> at <?= htmlspecialchars($job['company_name'] ?? '') ?> – OMR Chennai">
<meta property="og:description" content="<?= $page_desc ?>">
<meta property="og:url"         content="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:type"        content="website">
<meta property="og:image"       content="https://myomr.in/My-OMR-Logo.png">
<meta property="og:locale"      content="en_IN">
<meta property="og:site_name"   content="MyOMR – Jobs in OMR Chennai">
<meta name="twitter:card"       content="summary_large_image">
<meta name="twitter:title"      content="<?= htmlspecialchars($job['title']) ?> – OMR Chennai | MyOMR">
<meta name="twitter:description" content="<?= $page_desc ?>">
<meta name="twitter:image"      content="https://myomr.in/My-OMR-Logo.png">

<?php $ga_custom_params = [
  'job_category'     => $job['category_name'] ?? $job['category'] ?? '',
  'job_type'         => $job['job_type'] ?? '',
  'work_segment'     => $work_segment,
  'locality'         => $job['location'] ?? '',
  'experience_level' => $job['experience_level'] ?? '',
]; include ROOT_PATH . '/components/analytics.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="/omr-local-job-listings/assets/job-portal-2026.css">
<link rel="stylesheet" href="/assets/css/footer.css">

<!-- Structured Data -->
<?php echo generateJobPostingSchema($job); ?>
<script type="application/ld+json">
{
  "@context":"https://schema.org","@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"Home","item":"https://myomr.in/"},
    {"@type":"ListItem","position":2,"name":"Jobs in OMR","item":"https://myomr.in/omr-local-job-listings/"},
    {"@type":"ListItem","position":3,"name":<?= json_encode($job['title']) ?>,"item":<?= json_encode($canonical) ?>}
  ]
}
</script>
</head>
<body class="job-portal-page" data-job-id="<?= (int)$job['id'] ?>" data-job-title="<?= htmlspecialchars($job['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-company-name="<?= htmlspecialchars($job['company_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<!-- Preview banner -->
<?php if ($preview_mode): ?>
<div class="alert alert-warning text-center fw-semibold border-0 rounded-0 mb-0 py-2" role="alert">
  <i class="fas fa-eye me-2"></i>
  Preview — this job is <strong><?= htmlspecialchars(ucfirst($job['status'])) ?></strong> and not yet visible to the public.
  <?php if ($job['status'] === 'pending'): ?>
    It will go live once approved.
  <?php endif; ?>
</div>
<?php endif; ?>

<!-- Breadcrumb -->
<nav class="jp-breadcrumb" aria-label="Breadcrumb">
  <div class="container">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i> Home</a></li>
      <li class="breadcrumb-item"><a href="/omr-local-job-listings/">Jobs in OMR</a></li>
      <li class="breadcrumb-item">
        <a href="/omr-local-job-listings/?category=<?= urlencode($job['category'] ?? '') ?>">
          <?= htmlspecialchars($job['category_name'] ?? $job['category'] ?? 'General') ?>
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">
        <?= htmlspecialchars(mb_strimwidth($job['title'], 0, 40, '…')) ?>
      </li>
    </ol>
  </div>
</nav>

<!-- Application errors (server-side redirect back) -->
<?php if (!empty($app_errors)): ?>
<div class="container mt-3">
  <div class="alert alert-danger" role="alert">
    <h5 class="alert-heading"><i class="fas fa-triangle-exclamation me-2"></i>Couldn't submit your application</h5>
    <ul class="mb-0 ps-3">
      <?php foreach ($app_errors as $e): ?>
      <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php endif; ?>

<!-- ── JOB HERO ───────────────────────────────────────────────── -->
<section class="jp-detail-hero">
  <div class="container">
    <div class="row align-items-start gy-3">
      <div class="col-lg-8">
        <!-- Company logo + title -->
        <div class="d-flex align-items-center gap-3 mb-2">
          <?php if (!empty($job['company_logo'])): ?>
            <img src="<?= htmlspecialchars($job['company_logo']) ?>"
                 alt="<?= htmlspecialchars($job['company_name'] ?? '') ?> logo"
                 class="jp-detail__company-logo">
          <?php else: ?>
            <div class="jp-detail__company-logo">
              <?= htmlspecialchars(mb_substr($job['company_name'] ?? 'C', 0, 1)) ?>
            </div>
          <?php endif; ?>
          <div>
            <p class="jp-detail__company-kicker">
              <?= htmlspecialchars($job['company_name'] ?? '') ?>
            </p>
          </div>
        </div>

        <h1 class="jp-detail__title"><?= htmlspecialchars($job['title']) ?></h1>

        <!-- Highlights bar -->
        <div class="jp-highlights">
          <span class="jp-highlight">
            <i class="fas fa-map-marker-alt"></i>
            <?= htmlspecialchars($job['location'] ?? 'OMR, Chennai') ?>
          </span>
          <span class="jp-highlight">
            <i class="fas fa-briefcase"></i>
            <?= getJobTypeLabel($job['job_type'] ?? 'full-time') ?>
          </span>
          <span class="jp-highlight">
            <i class="fas fa-users"></i>
            <?= htmlspecialchars(getJobSegmentLabel($work_segment)) ?>
          </span>
          <?php if ($salary_display !== 'Not Disclosed'): ?>
          <span class="jp-highlight">
            <i class="fas fa-rupee-sign"></i>
            <?= htmlspecialchars($salary_display) ?>
          </span>
          <?php endif; ?>
          <?php if (!empty($job['experience_level']) && $job['experience_level'] !== 'Any'): ?>
          <span class="jp-highlight">
            <i class="fas fa-user-clock"></i>
            <?= htmlspecialchars($job['experience_level']) ?>
          </span>
          <?php endif; ?>
          <?php if (!empty($job['is_remote'])): ?>
          <span class="jp-highlight">
            <i class="fas fa-home"></i>
            Remote OK
          </span>
          <?php endif; ?>
          <?php if (!empty($job['openings_count'])): ?>
          <span class="jp-highlight">
            <i class="fas fa-user-plus"></i>
            <?= (int)$job['openings_count'] ?> opening<?= $job['openings_count'] > 1 ? 's' : '' ?>
          </span>
          <?php endif; ?>
          <?php if (!empty($job['application_deadline']) && $job['application_deadline'] !== '0000-00-00'): ?>
          <span class="jp-highlight jp-highlight-deadline">
            <i class="fas fa-calendar-times"></i>
            Apply by <?= date('d M Y', strtotime($job['application_deadline'])) ?>
          </span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Desktop apply panel (inside hero) -->
      <div class="col-lg-4 d-none d-lg-block">
        <div class="jp-hero-cta-panel">
          <?php if ($already_applied): ?>
          <button class="jp-btn-apply-main jp-btn-applied" disabled>
            <i class="fas fa-check-circle"></i> Already Applied
          </button>
          <?php else: ?>
          <button class="jp-btn-hero-apply" data-bs-toggle="modal" data-bs-target="#applyModal">
            <i class="fas fa-paper-plane"></i> Apply Now — It's Free
          </button>
          <a href="<?= $wa_href ?>" target="_blank" rel="noopener"
             class="jp-btn-hero-wa">
            <i class="fab fa-whatsapp"></i> Apply via WhatsApp
          </a>
          <?php endif; ?>
          <!-- Social proof -->
          <div class="jp-hero-proof-row">
            <span class="jp-hero-proof-item">
              <i class="fas fa-eye me-1"></i><?= number_format((int)($job['views'] ?? 0)) ?> views
            </span>
            <?php if ($apps_count > 0): ?>
            <span class="jp-hero-proof-item">
              <i class="fas fa-users me-1"></i><?= $apps_count ?> applied
            </span>
            <?php endif; ?>
            <span class="jp-hero-proof-item">
              <i class="fas fa-clock me-1"></i><?= $posted_label ?>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="jp-conversion-strip" aria-label="Quick apply summary">
  <div class="container">
    <div class="jp-conversion-grid">
      <div class="jp-conversion-metric">
        <span class="jp-metric-label">Posted</span>
        <strong><?= htmlspecialchars($posted_date) ?></strong>
      </div>
      <div class="jp-conversion-metric">
        <span class="jp-metric-label">Applications</span>
        <strong><?= number_format($apps_count) ?></strong>
      </div>
      <div class="jp-conversion-metric">
        <span class="jp-metric-label">Apply before</span>
        <strong><?= htmlspecialchars($application_deadline_label) ?></strong>
      </div>
      <div class="jp-conversion-actions">
        <?php if (!$already_applied): ?>
        <button class="jp-btn-apply-main" data-bs-toggle="modal" data-bs-target="#applyModal">
          <i class="fas fa-paper-plane"></i> Quick Apply
        </button>
        <a href="<?= $wa_href ?>" target="_blank" rel="noopener" class="jp-btn-wa-apply">
          <i class="fab fa-whatsapp"></i> WhatsApp
        </a>
        <?php else: ?>
        <span class="jp-conversion-applied"><i class="fas fa-check-circle"></i> You already applied</span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- ── MAIN CONTENT ────────────────────────────────────────────── -->
<main id="main-content">
  <div class="container jp-detail-main-wrap">
    <div class="row g-4">

      <!-- ── LEFT: Job Content ──────────────────────────────────── -->
      <div class="col-lg-8">

        <!-- Mobile sticky apply bar -->
        <?php if (!$already_applied): ?>
        <div class="d-lg-none jp-mobile-sticky-apply">
          <button class="jp-btn-view flex-fill" data-bs-toggle="modal" data-bs-target="#applyModal">
            <i class="fas fa-paper-plane"></i> Apply Now
          </button>
          <a href="<?= $wa_href ?>" target="_blank" rel="noopener" class="jp-btn-wa flex-fill justify-content-center">
            <i class="fab fa-whatsapp"></i> WhatsApp
          </a>
        </div>
        <?php endif; ?>

        <!-- Share this job (WhatsApp) -->
        <?php
        $job_page_url = getJobDetailUrl($job_id, $job['title'] ?? null);
        $share_wa_text = rawurlencode('Check this job on MyOMR: ' . ($job['title'] ?? 'Job') . ' — ' . $job_page_url);
        ?>
        <div class="mb-2">
          <a href="https://wa.me/?text=<?= $share_wa_text ?>" target="_blank" rel="noopener" class="btn btn-outline-success btn-sm">
            <i class="fab fa-whatsapp me-1"></i> Share this job
          </a>
        </div>

        <!-- Social proof strip -->
        <div class="jp-social-proof">
          <span class="jp-social-proof-item">
            <i class="fas fa-eye"></i> <?= number_format((int)($job['views'] ?? 0)) ?> views
          </span>
          <?php if ($apps_count > 0): ?>
          <span class="jp-social-proof-item">
            <i class="fas fa-users"></i> <?= $apps_count ?> applied this week
          </span>
          <?php endif; ?>
          <span class="jp-social-proof-item">
            <i class="fas fa-clock"></i> Posted <?= timeAgo($job['created_at'] ?? '') ?>
          </span>
          <?php if (!empty($job['category_name'])): ?>
          <span class="jp-social-proof-item">
            <i class="fas fa-tag"></i> <?= htmlspecialchars($job['category_name']) ?>
          </span>
          <?php endif; ?>
        </div>

        <?php omr_ad_slot('jobs-detail-mid', '336x280'); ?>

        <div class="jp-content-block jp-quick-facts">
          <h2><i class="fas fa-bolt"></i> Quick Job Facts</h2>
          <div class="jp-facts-grid">
            <div class="jp-fact-item">
              <span>Role type</span>
              <strong><?= htmlspecialchars(getJobTypeLabel($job['job_type'] ?? 'full-time')) ?></strong>
            </div>
            <div class="jp-fact-item">
              <span>Segment</span>
              <strong><?= htmlspecialchars(getJobSegmentLabel($work_segment)) ?></strong>
            </div>
            <div class="jp-fact-item">
              <span>Salary</span>
              <strong><?= htmlspecialchars($salary_display) ?></strong>
            </div>
            <div class="jp-fact-item">
              <span>Location</span>
              <strong><?= htmlspecialchars($job['location'] ?? 'OMR, Chennai') ?></strong>
            </div>
          </div>
        </div>

        <!-- Job Description -->
        <div class="jp-content-block">
          <h2><i class="fas fa-file-alt"></i> Job Description</h2>
          <div class="job-description">
            <?= nl2br(htmlspecialchars($job['description'] ?? '')) ?>
          </div>
        </div>

        <!-- Requirements -->
        <?php if (!empty($job['requirements'])): ?>
        <div class="jp-content-block">
          <h2><i class="fas fa-clipboard-check"></i> Requirements</h2>
          <div class="job-requirements">
            <?= nl2br(htmlspecialchars($job['requirements'])) ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Benefits -->
        <?php if (!empty($job['benefits'])): ?>
        <div class="jp-content-block">
          <h2><i class="fas fa-gift"></i> Benefits &amp; Perks</h2>
          <div class="job-benefits">
            <?= nl2br(htmlspecialchars($job['benefits'])) ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Deadline warning -->
        <?php if (!empty($job['application_deadline']) && $job['application_deadline'] !== '0000-00-00'): ?>
        <div class="jp-warn-box">
          <h5><i class="fas fa-calendar-times me-1"></i> Application Deadline</h5>
          <p class="mb-0">Apply before <strong><?= date('d F Y', strtotime($job['application_deadline'])) ?></strong>. Don't miss this opportunity!</p>
        </div>
        <?php endif; ?>

        <!-- Trust info -->
        <div class="jp-info-box">
          <h5><i class="fas fa-shield-alt me-1"></i> Application Guidelines</h5>
          <ul class="mb-0 ps-3 jp-guidelines-list">
            <li>Your application goes directly to the employer — no intermediary fee.</li>
            <li>Keep your phone and email accessible. Employers will reach you directly.</li>
            <li>Follow up if you haven't heard within 2 weeks.</li>
            <li>Never pay a fee to apply for any job on MyOMR. <strong>Report suspicious listings.</strong></li>
          </ul>
        </div>

        <!-- Related Jobs -->
        <?php if (!empty($related_jobs)): ?>
        <div class="jp-content-block">
          <h2><i class="fas fa-briefcase"></i> Similar Jobs</h2>
          <?php foreach ($related_jobs as $r): ?>
          <a href="<?= getJobDetailUrl((int)$r['id'], $r['title'] ?? null) ?>" class="jp-related-card">
            <div class="jp-related-card__initial">
              <?= htmlspecialchars(mb_substr($r['company_name'] ?? 'C', 0, 1)) ?>
            </div>
            <div>
              <h4><?= htmlspecialchars($r['title']) ?></h4>
              <p>
                <i class="fas fa-building me-1"></i><?= htmlspecialchars($r['company_name'] ?? '') ?>
                &nbsp;·&nbsp;
                <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($r['location'] ?? '') ?>
              </p>
            </div>
          </a>
          <?php endforeach; ?>
          <div class="text-center mt-3">
            <a href="/omr-local-job-listings/<?= !empty($job['category']) ? '?category=' . urlencode($job['category']) : '' ?>" class="jp-btn-view">
              <i class="fas fa-th-list me-1"></i> Browse All <?= htmlspecialchars($job['category_name'] ?? '') ?> Jobs
            </a>
          </div>
        </div>
        <?php endif; ?>

        <!-- Read: Career tips for OMR -->
        <?php if (!empty($related_articles)): ?>
        <div class="jp-content-block">
          <h2><i class="fas fa-newspaper"></i> Career tips &amp; hiring news</h2>
          <p class="text-muted small mb-2">Read the latest from OMR&rsquo;s IT corridor.</p>
          <ul class="list-unstyled mb-0">
            <?php foreach ($related_articles as $ra): ?>
            <li class="mb-2">
              <a href="/local-news/<?= htmlspecialchars($ra['slug']) ?>"><?= htmlspecialchars($ra['title']) ?></a>
              <?php if (!empty($ra['published_date'])): ?><span class="text-muted small"> · <?= date('M j, Y', strtotime($ra['published_date'])) ?></span><?php endif; ?>
            </li>
            <?php endforeach; ?>
          </ul>
          <a href="/discover-myomr/it-careers-omr.php" class="btn btn-outline-success btn-sm mt-2">More career articles</a>
        </div>
        <?php endif; ?>

      </div><!-- /col-lg-8 -->

      <!-- ── RIGHT: Sidebar ─────────────────────────────────────── -->
      <div class="col-lg-4">
        <div class="jp-apply-panel">

          <!-- Apply buttons (desktop sidebar) -->
          <?php if (!$already_applied): ?>
          <div class="jp-sidebar-detail jp-sidebar-detail-center">
            <button class="jp-btn-apply-main" data-bs-toggle="modal" data-bs-target="#applyModal">
              <i class="fas fa-paper-plane"></i> Apply Now — It's Free
            </button>
            <div class="jp-cta-or">— or —</div>
            <a href="<?= $wa_href ?>" target="_blank" rel="noopener" class="jp-btn-wa-apply">
              <i class="fab fa-whatsapp"></i> Apply via WhatsApp
            </a>
            <p class="jp-cta-note">
              Quick apply in under 60 seconds
            </p>
          </div>
          <?php else: ?>
          <div class="jp-sidebar-detail text-center">
            <div class="jp-applied-icon"><i class="fas fa-check-circle"></i></div>
            <p class="jp-applied-title">You've already applied!</p>
            <p class="jp-applied-subtitle">The employer will contact you directly if selected.</p>
          </div>
          <?php endif; ?>

          <!-- Job Details table -->
          <div class="jp-sidebar-detail">
            <h3><i class="fas fa-info-circle"></i> Job Details</h3>
            <table class="jp-details-table">
              <tr>
                <td>Category</td>
                <td><?= htmlspecialchars($job['category_name'] ?? $job['category'] ?? 'General') ?></td>
              </tr>
              <tr>
                <td>Type</td>
                <td><?= getJobTypeLabel($job['job_type'] ?? '') ?></td>
              </tr>
              <tr>
                <td>Segment</td>
                <td><?= htmlspecialchars(getJobSegmentLabel($work_segment)) ?></td>
              </tr>
              <tr>
                <td>Location</td>
                <td><?= htmlspecialchars($job['location'] ?? '') ?></td>
              </tr>
              <tr>
                <td>Salary</td>
                <td><?= htmlspecialchars($salary_display) ?></td>
              </tr>
              <?php if (!empty($job['experience_level']) && $job['experience_level'] !== 'Any'): ?>
              <tr>
                <td>Experience</td>
                <td><?= htmlspecialchars($job['experience_level']) ?></td>
              </tr>
              <?php endif; ?>
              <?php if (!empty($job['openings_count'])): ?>
              <tr>
                <td>Openings</td>
                <td><?= (int)$job['openings_count'] ?></td>
              </tr>
              <?php endif; ?>
              <tr>
                <td>Posted</td>
                <td><?= date('d M Y', strtotime($job['created_at'] ?? 'now')) ?></td>
              </tr>
            </table>
          </div>

          <!-- Company info -->
          <div class="jp-sidebar-detail">
            <h3><i class="fas fa-building"></i> About the Employer</h3>
            <p class="jp-employer-name">
              <?= htmlspecialchars($job['company_name'] ?? '') ?>
            </p>
            <?php if (!empty($job['company_address'])): ?>
            <p class="jp-employer-meta">
              <i class="fas fa-map-marker-alt me-1 text-success"></i>
              <?= htmlspecialchars($job['company_address']) ?>
            </p>
            <?php endif; ?>
            <?php if (!empty($job['employer_email'])): ?>
            <p class="jp-employer-meta">
              <i class="fas fa-envelope me-1 text-success"></i>
              <?= htmlspecialchars($job['employer_email']) ?>
            </p>
            <?php endif; ?>
            <?php if (!empty($job['employer_phone'])): ?>
            <p class="jp-employer-meta jp-employer-meta-last">
              <i class="fas fa-phone me-1 text-success"></i>
              <?= htmlspecialchars($job['employer_phone']) ?>
            </p>
            <?php endif; ?>
          </div>

          <!-- Share -->
          <div class="jp-sidebar-detail">
            <h3><i class="fas fa-share-alt"></i> Share This Job</h3>
            <div class="d-flex flex-column gap-2">
              <a href="https://wa.me/?text=<?= rawurlencode('Check out this job on MyOMR: ' . $job['title'] . ' at ' . ($job['company_name'] ?? '') . ' — ' . $canonical) ?>"
                 target="_blank" rel="noopener" class="jp-share-btn jp-share-wa">
                <i class="fab fa-whatsapp"></i> Share on WhatsApp
              </a>
              <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= rawurlencode($canonical) ?>"
                 target="_blank" rel="noopener" class="jp-share-btn jp-share-li">
                <i class="fab fa-linkedin"></i> Share on LinkedIn
              </a>
              <button type="button" class="jp-share-btn jp-share-mail"
                      onclick="window.location.href='mailto:?subject=<?= rawurlencode('Job: ' . $job['title']) ?>&body=<?= rawurlencode("Hi,\n\nCheck out this job opportunity I found:\n\n{$job['title']} at {$job['company_name']}\n\n$canonical") ?>'">
                <i class="fas fa-envelope"></i> Share via Email
              </button>
            </div>
          </div>

          <!-- Join our WhatsApp group (community) -->
          <div class="jp-sidebar-detail jp-community-card jp-community-card-wa">
            <h3><i class="fab fa-whatsapp jp-community-icon-wa"></i> Join Our WhatsApp Group</h3>
            <p class="jp-community-copy jp-community-copy-wa">Get job alerts, local updates &amp; connect with the OMR community.</p>
            <a href="<?= htmlspecialchars(defined('MYOMR_WHATSAPP_GROUP_URL') ? MYOMR_WHATSAPP_GROUP_URL : 'https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi') ?>" target="_blank" rel="noopener" class="jp-share-btn jp-share-wa jp-share-centered">
              <i class="fab fa-whatsapp"></i> Join WhatsApp Group
            </a>
          </div>

          <!-- Join our Facebook group (community) -->
          <div class="jp-sidebar-detail jp-community-card jp-community-card-fb">
            <h3><i class="fab fa-facebook-f jp-community-icon-fb"></i> Join Our Facebook Group</h3>
            <p class="jp-community-copy jp-community-copy-fb">Stay connected with OMR updates, discussions, and local opportunities.</p>
            <a href="<?= htmlspecialchars(defined('MYOMR_FACEBOOK_GROUP_URL') ? MYOMR_FACEBOOK_GROUP_URL : 'https://www.facebook.com/groups/416854920508620') ?>" target="_blank" rel="noopener" class="jp-share-btn jp-share-fb jp-share-centered" aria-label="Join our Facebook group for OMR job updates" title="Join our Facebook group">
              <i class="fab fa-facebook-f"></i> Join Facebook Group
            </a>
          </div>

          <!-- Back link -->
          <div class="text-center mt-2">
            <a href="/omr-local-job-listings/" class="jp-back-link">
              <i class="fas fa-arrow-left me-1"></i> Back to All Jobs
            </a>
          </div>
        </div>
      </div><!-- /col-lg-4 -->

    </div><!-- /.row -->
  </div><!-- /.container -->
</main>

<!-- ── APPLY MODAL ─────────────────────────────────────────────── -->
<div class="modal fade jp-modal" id="applyModal" tabindex="-1"
     aria-labelledby="applyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="applyModalLabel">
          <i class="fas fa-paper-plane me-2"></i>
          Apply for: <?= htmlspecialchars($job['title']) ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="/omr-local-job-listings/process-application-omr.php" method="POST"
            enctype="multipart/form-data" id="applyForm" novalidate>
        <div class="modal-body">
          <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
          <?php if (function_exists('generateCsrfToken')): ?>
          <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
          <?php endif; ?>

          <div id="applyFormErrors" class="alert alert-danger d-none" role="alert" tabindex="-1"></div>

          <!-- Row: name + email -->
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label" for="applicant_name">Full Name *</label>
              <input type="text" class="form-control" id="applicant_name" name="applicant_name"
                     value="<?= htmlspecialchars($form_data['applicant_name'] ?? '') ?>"
                     required autocomplete="name">
            </div>
            <div class="col-md-6">
              <label class="form-label" for="applicant_email">Email Address *</label>
              <input type="email" class="form-control" id="applicant_email" name="applicant_email"
                     value="<?= htmlspecialchars($form_data['applicant_email'] ?? ($_COOKIE['applicant_email'] ?? '')) ?>"
                     required autocomplete="email">
            </div>
          </div>

          <!-- Row: phone + experience -->
          <div class="row g-3 mt-0">
            <div class="col-md-6">
              <label class="form-label" for="applicant_phone">Phone Number *</label>
              <input type="tel" class="form-control" id="applicant_phone" name="applicant_phone"
                     value="<?= htmlspecialchars($form_data['applicant_phone'] ?? '') ?>"
                     pattern="(\+91)?[0-9]{10}" required autocomplete="tel"
                     placeholder="10-digit mobile number">
            </div>
            <div class="col-md-6">
              <label class="form-label" for="experience_years">Years of Experience</label>
              <input type="number" class="form-control" id="experience_years" name="experience_years"
                     min="0" max="50"
                     value="<?= htmlspecialchars((string)($form_data['experience_years'] ?? '')) ?>"
                     placeholder="0">
            </div>
          </div>

          <!-- Resume upload -->
          <div class="mt-3">
            <label class="form-label" for="resume">
              Resume / CV <small class="text-muted">(PDF, DOC, DOCX — max 2 MB)</small>
            </label>
            <div class="jp-resume-upload" id="resumeDropZone"
                 onclick="document.getElementById('resume').click()">
              <i class="fas fa-cloud-upload-alt d-block"></i>
              <p id="resumeLabel">Click to upload or drag &amp; drop your resume</p>
              <input type="file" id="resume" name="resume"
                     accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                     onchange="handleResumeChange(this)">
            </div>
          </div>

          <!-- Cover letter -->
          <div class="mt-3">
            <label class="form-label" for="cover_letter">
              Message / Cover Letter <small class="text-muted">(optional)</small>
            </label>
            <textarea class="form-control" id="cover_letter" name="cover_letter" rows="3"
                      placeholder="Why are you a good fit for this role?"><?= htmlspecialchars($form_data['cover_letter'] ?? '') ?></textarea>
          </div>

          <div class="alert alert-light border mt-3 mb-0 jp-apply-hint">
            <i class="fas fa-info-circle text-success me-1"></i>
            Your application is sent directly to the employer. MyOMR never charges applicants.
          </div>
        </div>

        <div class="modal-footer jp-modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <a href="<?= $wa_href ?>" target="_blank" rel="noopener"
             class="btn btn-success jp-modal-btn-wa">
            <i class="fab fa-whatsapp me-1"></i> Apply via WhatsApp
          </a>
          <button type="submit" class="btn btn-primary jp-modal-btn-apply" id="submitBtn">
            <i class="fas fa-paper-plane me-1"></i> Submit Application
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php omr_footer(); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/omr-local-job-listings/assets/job-analytics-events.js"></script>

<script>
/* Resume file selection label */
function handleResumeChange(input) {
  const label = document.getElementById('resumeLabel');
  const zone  = document.getElementById('resumeDropZone');
  if (input.files && input.files[0]) {
    const f = input.files[0];
    const maxMB = 2;
    if (f.size > maxMB * 1024 * 1024) {
      alert('File is too large. Maximum size is 2 MB.');
      input.value = '';
      label.textContent = 'Click to upload or drag & drop your resume';
      return;
    }
    label.innerHTML = '<i class="fas fa-check-circle" style="color:#008552"></i> ' + f.name;
    zone.style.borderColor = '#008552';
    zone.style.background  = '#e8f5ed';
  }
}

/* Drag-and-drop on resume zone */
(function() {
  const zone = document.getElementById('resumeDropZone');
  if (!zone) return;
  zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor = '#008552'; });
  zone.addEventListener('dragleave', () => { zone.style.borderColor = ''; });
  zone.addEventListener('drop', e => {
    e.preventDefault();
    const fi = document.getElementById('resume');
    fi.files = e.dataTransfer.files;
    handleResumeChange(fi);
  });
})();

/* Client-side validation */
(function() {
  const form = document.getElementById('applyForm');
  const errBox = document.getElementById('applyFormErrors');
  if (!form) return;
  const phoneRx = /^(\+91)?[0-9]{10}$/;

  form.addEventListener('submit', function(e) {
    const errs = [];
    const name  = form.querySelector('#applicant_name').value.trim();
    const email = form.querySelector('#applicant_email');
    const phone = form.querySelector('#applicant_phone').value.trim();
    const exp   = form.querySelector('#experience_years');

    if (!name)                              errs.push('Full name is required.');
    if (!email.value.trim())                errs.push('Email address is required.');
    else if (!email.checkValidity())        errs.push('Please enter a valid email address.');
    if (!phone)                             errs.push('Phone number is required.');
    else if (!phoneRx.test(phone))          errs.push('Phone must be 10 digits (optionally +91).');
    if (exp.value && (parseInt(exp.value) < 0 || parseInt(exp.value) > 50))
                                            errs.push('Experience must be between 0 and 50 years.');

    if (errs.length) {
      e.preventDefault();
      errBox.classList.remove('d-none');
      errBox.innerHTML = '<ul class="mb-0 ps-3"><li>' + errs.join('</li><li>') + '</li></ul>';
      errBox.focus();
    } else {
      errBox.classList.add('d-none');
      document.getElementById('submitBtn').innerHTML =
        '<i class="fas fa-spinner fa-spin me-1"></i> Submitting…';
    }
  });
})();

/* Reopen modal on errors */
<?php if (!empty($app_errors)): ?>
document.addEventListener('DOMContentLoaded', function() {
  new bootstrap.Modal(document.getElementById('applyModal')).show();
});
<?php endif; ?>
</script>

</body>
</html>
