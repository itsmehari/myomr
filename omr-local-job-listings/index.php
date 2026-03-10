<?php
/**
 * MyOMR Job Portal — Main Listings Page (v3.0 2026 Overhaul)
 *
 * Layout: sticky quick-filter pills + desktop sidebar + job cards + WhatsApp CTA
 */

require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/job-functions-omr.php';
require_once __DIR__ . '/includes/seo-helper.php';

// Bootstrap: central path + component helpers
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';

require_once ROOT_PATH . '/core/omr-connect.php';
global $conn;

/* ── Sanitize & collect filters ─────────────────────────────── */
$filters = [];
$allowed  = ['search','category','location','job_type','experience_level','is_remote','salary_min','salary_max'];
foreach ($allowed as $k) {
    if (isset($_GET[$k]) && $_GET[$k] !== '') {
        $filters[$k] = sanitizeInput($_GET[$k]);
    }
}

// Walk-in shortcut
if (!empty($_GET['walk_in'])) {
    $filters['job_type'] = 'walk-in';
}

/* ── Pagination ─────────────────────────────────────────────── */
$per_page     = 12;
$current_page = max(1, (int)($_GET['page'] ?? 1));
$offset       = ($current_page - 1) * $per_page;

$total_jobs  = getJobCount($filters);
$jobs        = getJobListings($filters, $per_page, $offset);
$total_pages = (int)ceil($total_jobs / $per_page);

// Featured jobs (separate spotlight query — top 3 featured)
$featured_jobs = getJobListings(['is_featured' => 1] + $filters, 3, 0);

$categories = getJobCategories();

/* ── Quick counts for pills ─────────────────────────────────── */
$count_wfh     = getJobCount(['is_remote' => 1]);
$count_fresher = getJobCount(['experience_level' => 'Fresher']);
$count_pt      = getJobCount(['job_type' => 'part-time']);

/* ── SEO ─────────────────────────────────────────────────────── */
$page_title = "Jobs in OMR Chennai – Find Local Opportunities | MyOMR";
$page_desc  = "Find " . number_format($total_jobs) . "+ jobs in OMR Chennai – IT, Teaching, Healthcare, BPO, Hospitality & more. Apply directly. Updated daily.";
$canonical  = "https://myomr.in/omr-local-job-listings/";

$filter_qs  = http_build_query(array_filter($filters));
$base_url   = "/omr-local-job-listings/" . ($filter_qs ? "?$filter_qs" : '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $page_title ?></title>
<meta name="description" content="<?= $page_desc ?>">
<meta name="keywords" content="jobs in OMR Chennai, IT jobs OMR, teaching jobs, healthcare jobs OMR, fresher jobs, part time jobs, walk-in jobs, Old Mahabalipuram Road jobs">
<link rel="canonical" href="<?= $canonical ?>">

<?php if ($current_page > 1): ?>
<link rel="prev" href="<?= htmlspecialchars($base_url . (str_contains($base_url,'?') ? '&' : '?') . 'page=' . ($current_page - 1)) ?>">
<?php endif; ?>
<?php if ($current_page < $total_pages): ?>
<link rel="next" href="<?= htmlspecialchars($base_url . (str_contains($base_url,'?') ? '&' : '?') . 'page=' . ($current_page + 1)) ?>">
<?php endif; ?>

<!-- OG / Twitter -->
<meta property="og:title"       content="<?= $page_title ?>">
<meta property="og:description" content="<?= $page_desc ?>">
<meta property="og:url"         content="<?= $canonical ?>">
<meta property="og:type"        content="website">
<meta property="og:image"       content="https://myomr.in/My-OMR-Logo.png">
<meta name="twitter:card"       content="summary_large_image">
<meta name="twitter:title"      content="<?= $page_title ?>">
<meta name="twitter:description" content="<?= $page_desc ?>">
<meta name="twitter:image"      content="https://myomr.in/My-OMR-Logo.png">

<?php
$ga_custom_params = [];
if (!empty($filters['category']))        $ga_custom_params['job_category']     = $filters['category'];
if (!empty($filters['job_type']))        $ga_custom_params['job_type']         = $filters['job_type'];
if (!empty($filters['location']))        $ga_custom_params['locality']         = $filters['location'];
if (!empty($filters['experience_level'])) $ga_custom_params['experience_level'] = $filters['experience_level'];
include ROOT_PATH . '/components/analytics.php'; ?>
<?php if (!empty($filters['search'])): ?>
<script>
(function() {
  if (typeof gtag !== 'function') return;
  gtag('event', 'search', {
    'search_term': <?= json_encode($filters['search'], _GA_JSON_FLAGS) ?>,
    'job_category': <?= json_encode($filters['category'] ?? '', _GA_JSON_FLAGS) ?>,
    'locality':     <?= json_encode($filters['location'] ?? '', _GA_JSON_FLAGS) ?>
  });
})();
</script>
<?php endif; ?>

<!-- Bootstrap + Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="assets/job-portal-2026.css">
<link rel="stylesheet" href="/assets/css/footer.css">

<!-- Structured Data -->
<script type="application/ld+json">
{
  "@context":"https://schema.org","@type":"WebSite",
  "name":"MyOMR Job Portal","url":"<?= $canonical ?>",
  "description":"<?= $page_desc ?>",
  "potentialAction":{"@type":"SearchAction","target":"<?= $canonical ?>?search={q}","query-input":"required name=q"}
}
</script>
<script type="application/ld+json">
{
  "@context":"https://schema.org","@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"Home","item":"https://myomr.in/"},
    {"@type":"ListItem","position":2,"name":"Jobs in OMR","item":"<?= $canonical ?>"}
  ]
}
</script>
</head>
<body class="job-portal-page">

<?php omr_nav('main'); ?>

<!-- ── HERO ───────────────────────────────────────────────────── -->
<section class="jp-hero">
  <div class="container">
    <div class="row align-items-center gy-4">
      <div class="col-lg-8">
        <p class="mb-1" style="color:rgba(255,255,255,.7);font-size:.88rem;font-weight:500;letter-spacing:.05em;text-transform:uppercase;">
          <i class="fas fa-map-marker-alt me-1"></i> Old Mahabalipuram Road, Chennai
        </p>
        <h1 class="jp-hero__headline">Find Your Next Job in OMR</h1>
        <p class="jp-hero__sub">Connect with top local employers. Browse <strong id="hero-job-count"><?= number_format($total_jobs) ?></strong>+ opportunities across IT, Teaching, Healthcare &amp; more.</p>

        <!-- Search Form -->
        <form method="GET" action="/omr-local-job-listings/" id="main-search-form" data-ajax-search>
          <div class="jp-search-box">
            <div style="flex:2;min-width:160px">
              <label for="search" class="visually-hidden">Job title or keywords</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="border:1.5px solid #e5e7eb;border-right:none;border-radius:8px 0 0 8px;color:#9ca3af">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" id="search" name="search" class="form-control border-start-0"
                       style="border:1.5px solid #e5e7eb;border-left:none;border-radius:0 8px 8px 0"
                       placeholder="Job title, company or keywords"
                       value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
              </div>
            </div>
            <div style="flex:1.5;min-width:130px">
              <label for="location" class="visually-hidden">Location</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="border:1.5px solid #e5e7eb;border-right:none;border-radius:8px 0 0 8px;color:#9ca3af">
                  <i class="fas fa-map-marker-alt"></i>
                </span>
                <input type="text" id="location" name="location" class="form-control border-start-0"
                       style="border:1.5px solid #e5e7eb;border-left:none;border-radius:0 8px 8px 0"
                       placeholder="Location in OMR"
                       value="<?= htmlspecialchars($filters['location'] ?? '') ?>">
              </div>
            </div>
            <div style="flex:1.2;min-width:120px">
              <label for="experience_level" class="visually-hidden">Experience</label>
              <select id="experience_level" name="experience_level" class="form-select">
                <option value="">Experience</option>
                <option value="Fresher"   <?= ($filters['experience_level'] ?? '') === 'Fresher'   ? 'selected' : '' ?>>Fresher</option>
                <option value="Junior"    <?= ($filters['experience_level'] ?? '') === 'Junior'    ? 'selected' : '' ?>>1–3 years</option>
                <option value="Mid-level" <?= ($filters['experience_level'] ?? '') === 'Mid-level' ? 'selected' : '' ?>>3–5 years</option>
                <option value="Senior"    <?= ($filters['experience_level'] ?? '') === 'Senior'    ? 'selected' : '' ?>>5+ years</option>
                <option value="Lead"      <?= ($filters['experience_level'] ?? '') === 'Lead'      ? 'selected' : '' ?>>Lead / Manager</option>
                <option value="Any"       <?= ($filters['experience_level'] ?? '') === 'Any'       ? 'selected' : '' ?>>Any</option>
              </select>
            </div>
            <div>
              <button type="submit" class="btn-search">
                <i class="fas fa-search me-1"></i> Search
              </button>
            </div>
          </div>
        </form>

        <!-- Hero Stats -->
        <div class="jp-hero-stats mt-3">
          <div class="jp-stat">
            <strong id="hero-job-count-stat"><?= number_format($total_jobs) ?>+</strong>
            Active Jobs
          </div>
          <div class="jp-stat">
            <strong><?= number_format($count_wfh) ?>+</strong>
            Work from Home
          </div>
          <div class="jp-stat">
            <strong><?= number_format($count_fresher) ?>+</strong>
            Fresher Roles
          </div>
        </div>
      </div>

      <div class="col-lg-4 d-none d-lg-block">
        <!-- Employer CTA card in hero -->
        <div style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:14px;padding:1.5rem;color:#fff;text-align:center">
          <div style="font-size:2rem;margin-bottom:.5rem">🏢</div>
          <h3 style="font-size:1.05rem;font-weight:700;margin-bottom:.4rem">Are you an Employer?</h3>
          <p style="font-size:.85rem;opacity:.85;margin-bottom:1rem">Post a job and reach thousands of OMR job seekers instantly.</p>
          <a href="post-job-omr.php" style="background:#fff;color:#005c39;border-radius:8px;padding:.6rem 1.5rem;font-weight:700;font-size:.9rem;text-decoration:none;display:inline-block">
            <i class="fas fa-plus me-1"></i> Post a Job – Free
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── QUICK FILTER PILLS ──────────────────────────────────────── -->
<nav class="jp-quick-filters" aria-label="Quick job filters">
  <div class="container">
    <span style="font-size:.8rem;font-weight:600;color:#6b7280;white-space:nowrap">Browse:</span>

    <a href="/omr-local-job-listings/" class="jp-pill <?= empty($filters) ? 'active' : '' ?>">
      <i class="fas fa-th-list"></i> All Jobs
      <span class="jp-pill-count"><?= number_format($total_jobs) ?></span>
    </a>
    <a href="?is_remote=1" class="jp-pill pill-wfh <?= ($filters['is_remote'] ?? '') === '1' ? 'active' : '' ?>">
      <i class="fas fa-home"></i> Work from Home
      <span class="jp-pill-count"><?= number_format($count_wfh) ?></span>
    </a>
    <a href="?experience_level=Fresher" class="jp-pill <?= ($filters['experience_level'] ?? '') === 'Fresher' ? 'active' : '' ?>">
      <i class="fas fa-user-graduate"></i> Fresher
      <span class="jp-pill-count"><?= number_format($count_fresher) ?></span>
    </a>
    <a href="?job_type=part-time" class="jp-pill <?= ($filters['job_type'] ?? '') === 'part-time' ? 'active' : '' ?>">
      <i class="fas fa-clock"></i> Part-Time
      <span class="jp-pill-count"><?= number_format($count_pt) ?></span>
    </a>
    <a href="?category=it" class="jp-pill <?= ($filters['category'] ?? '') === 'it' ? 'active' : '' ?>">
      <i class="fas fa-laptop-code"></i> IT Jobs
    </a>
    <a href="?category=healthcare" class="jp-pill <?= ($filters['category'] ?? '') === 'healthcare' ? 'active' : '' ?>">
      <i class="fas fa-user-md"></i> Healthcare
    </a>
    <a href="?category=hospitality" class="jp-pill <?= ($filters['category'] ?? '') === 'hospitality' ? 'active' : '' ?>">
      <i class="fas fa-utensils"></i> Hospitality
    </a>
    <a href="?category=education" class="jp-pill <?= ($filters['category'] ?? '') === 'education' ? 'active' : '' ?>">
      <i class="fas fa-chalkboard-teacher"></i> Teaching
    </a>
    <a href="?job_type=walk-in" class="jp-pill pill-urgent <?= ($filters['job_type'] ?? '') === 'walk-in' ? 'active' : '' ?>">
      <i class="fas fa-walking"></i> Walk-In
    </a>
  </div>
</nav>

<!-- Mobile sidebar overlay -->
<div class="jp-sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
<div class="jp-sidebar-drawer" id="sidebarDrawer">
  <button class="jp-sidebar-drawer-close" onclick="closeSidebar()" aria-label="Close filters">
    <i class="fas fa-times"></i>
  </button>
  <h2 style="font-size:1rem;font-weight:700;margin-bottom:1.25rem">Filter Jobs</h2>
  <?php include __DIR__ . '/includes/sidebar-filters.php'; ?>
</div>

<!-- ── MAIN LAYOUT ─────────────────────────────────────────────── -->
<main id="main-content">
  <div class="container">
    <div class="jp-layout">

      <!-- ── SIDEBAR (desktop) ─────────────────────────────────── -->
      <aside class="jp-sidebar" id="desktopSidebar" aria-label="Job filters">
        <?php include __DIR__ . '/includes/sidebar-filters.php'; ?>

        <!-- Job Alert signup -->
        <div class="jp-alert-box">
          <h4><i class="fas fa-bell me-1"></i> Get Job Alerts</h4>
          <p>Get notified when new jobs matching your search are posted.</p>
          <form id="job-alert-form" method="POST" action="includes/save-job-alert.php">
            <input type="hidden" name="alert_keyword" value="<?= htmlspecialchars($filters['search'] ?? 'OMR jobs') ?>">
            <input type="hidden" name="alert_location" value="<?= htmlspecialchars($filters['location'] ?? 'OMR') ?>">
            <div class="jp-alert-input-row">
              <input type="email" name="alert_email" placeholder="your@email.com" required>
              <button type="submit"><i class="fas fa-bell me-1"></i> Alert Me</button>
            </div>
          </form>
        </div>
      </aside>

      <!-- ── MAIN CONTENT ──────────────────────────────────────── -->
      <section aria-label="Job listings">

        <!-- Active filter tags -->
        <?php if (!empty($filters)): ?>
        <div class="jp-active-filters" id="active-filter-tags">
          <?php foreach ($filters as $key => $val):
            $label = match($key) {
              'search'           => "\"$val\"",
              'category'         => ucfirst($val),
              'location'         => $val,
              'job_type'         => ucfirst(str_replace('-',' ',$val)),
              'experience_level' => $val,
              'is_remote'        => 'Remote',
              'salary_min'       => '₹' . number_format((int)$val) . '+ min',
              'salary_max'       => '₹' . number_format((int)$val) . ' max',
              default            => $val,
            };
          ?>
          <span class="jp-filter-tag">
            <?= htmlspecialchars($label) ?>
            <button type="button" title="Remove filter"
              onclick="window.location='<?= htmlspecialchars('/omr-local-job-listings/?' . http_build_query(array_diff_key($filters, [$key => '']))) ?>'">
              <i class="fas fa-times"></i>
            </button>
          </span>
          <?php endforeach; ?>
          <a href="/omr-local-job-listings/" class="jp-filter-tag" style="background:#fef2f2;border-color:#fca5a5;color:#991b1b;text-decoration:none">
            <i class="fas fa-trash-alt"></i> Clear all
          </a>
        </div>
        <?php endif; ?>

        <!-- Results header -->
        <div class="jp-results-header">
          <div>
            <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:.1rem">
              Job Opportunities
            </h2>
            <p class="jp-results-count" id="results-count">
              Showing <strong id="filter-job-count"><?= number_format(count($jobs)) ?></strong>
              of <strong><?= number_format($total_jobs) ?></strong> jobs
              <?php if ($current_page > 1): ?> &mdash; Page <?= $current_page ?> of <?= $total_pages ?><?php endif; ?>
            </p>
          </div>
          <div class="d-flex align-items-center gap-2">
            <!-- Mobile filter button -->
            <button class="jp-mobile-filter-btn" onclick="openSidebar()" type="button">
              <i class="fas fa-sliders-h"></i> Filters
              <?php if (!empty($filters)): ?>
              <span class="badge bg-danger" style="font-size:.7rem"><?= count($filters) ?></span>
              <?php endif; ?>
            </button>
            <!-- Sort -->
            <div class="jp-sort-group btn-group" role="group" aria-label="Sort options">
              <button type="button" class="btn btn-outline-secondary btn-sm active" data-sort="newest">
                <i class="fas fa-clock me-1"></i> Newest
              </button>
              <button type="button" class="btn btn-outline-secondary btn-sm" data-sort="featured">
                <i class="fas fa-star me-1"></i> Featured
              </button>
            </div>
          </div>
        </div>

        <!-- Featured Spotlight (only on page 1 with no search) -->
        <?php if (!empty($featured_jobs) && $current_page === 1 && empty($filters['search'])): ?>
        <div class="jp-spotlight">
          <div class="jp-spotlight__header">
            <span class="jp-spotlight__title">
              ⭐ Featured Jobs
              <span class="badge ms-2">Promoted</span>
            </span>
            <div class="jp-spotlight__nav d-flex gap-1">
              <button id="spotPrev" aria-label="Previous featured jobs"><i class="fas fa-chevron-left"></i></button>
              <button id="spotNext" aria-label="Next featured jobs"><i class="fas fa-chevron-right"></i></button>
            </div>
          </div>
          <div class="row g-2" id="spotlight-track">
            <?php foreach ($featured_jobs as $fj): ?>
            <div class="col-md-4">
              <a href="job-detail-omr.php?id=<?= $fj['id'] ?>" class="jp-related-card" style="height:100%">
                <div class="jp-related-card__initial">
                  <?= htmlspecialchars(mb_substr($fj['company_name'] ?? 'C', 0, 1)) ?>
                </div>
                <div>
                  <h4><?= htmlspecialchars($fj['title']) ?></h4>
                  <p><i class="fas fa-building me-1"></i><?= htmlspecialchars($fj['company_name'] ?? '') ?></p>
                  <p><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($fj['location'] ?? '') ?>
                  <?php if (!empty($fj['salary_range']) && $fj['salary_range'] !== 'Not Disclosed'): ?>
                    &nbsp;·&nbsp; <i class="fas fa-rupee-sign me-1"></i><?= htmlspecialchars(formatSalary($fj['salary_range'])) ?>
                  <?php endif; ?>
                  </p>
                </div>
              </a>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Loading Spinner -->
        <div class="jp-spinner-wrap" id="jobs-spinner" aria-live="polite">
          <div class="spinner-border text-success" role="status" style="width:2.5rem;height:2.5rem">
            <span class="visually-hidden">Loading jobs…</span>
          </div>
        </div>

        <!-- Job Cards Grid -->
        <div id="jobs-grid">
          <?php if (!empty($jobs)): ?>
            <?php foreach ($jobs as $job):
              $loc      = htmlspecialchars($job['location'] ?? '');
              $type     = ucfirst(str_replace('-', ' ', $job['job_type'] ?? 'Full-time'));
              $cat      = htmlspecialchars($job['category_name'] ?? $job['category'] ?? 'General');
              $salary   = (!empty($job['salary_range']) && $job['salary_range'] !== 'Not Disclosed')
                          ? formatSalary($job['salary_range']) : '';
              $posted   = timeAgo($job['created_at'] ?? '');
              $initial  = mb_substr($job['company_name'] ?? 'C', 0, 1);
              $desc     = htmlspecialchars(mb_substr(strip_tags($job['description'] ?? ''), 0, 140));
              $jid      = (int)$job['id'];
              $exp      = $job['experience_level'] ?? '';
              $remote   = !empty($job['is_remote']);
              $apps     = (int)($job['applications_count'] ?? 0);
              $featured = !empty($job['featured']);

              // Build WhatsApp message
              $wa_msg = rawurlencode("Hi, I'm interested in the {$job['title']} role at {$job['company_name']} that I found on MyOMR.in. Can you share more details? Job link: https://myomr.in/omr-local-job-listings/job-detail-omr.php?id=$jid");
              $wa_href = "https://wa.me/" . preg_replace('/\D/', '', $job['employer_phone'] ?? '') . "?text=$wa_msg";
            ?>
            <article class="jp-card<?= $featured ? ' featured' : '' ?>"
                     itemscope itemtype="https://schema.org/JobPosting">

              <?php if ($featured): ?>
              <span class="jp-featured-badge"><i class="fas fa-star me-1"></i>Featured</span>
              <?php endif; ?>

              <!-- Header: logo + title + company -->
              <div class="jp-card__header">
                <?php if (!empty($job['company_logo'])): ?>
                  <img src="<?= htmlspecialchars($job['company_logo']) ?>"
                       alt="<?= htmlspecialchars($job['company_name'] ?? '') ?>"
                       class="jp-card__logo">
                <?php else: ?>
                  <div class="jp-card__logo-initial" aria-hidden="true"><?= $initial ?></div>
                <?php endif; ?>

                <div class="jp-card__meta">
                  <a href="job-detail-omr.php?id=<?= $jid ?>"
                     class="jp-card__title" itemprop="title">
                    <?= htmlspecialchars($job['title']) ?>
                  </a>
                  <p class="jp-card__company" itemprop="hiringOrganization" itemscope itemtype="https://schema.org/Organization">
                    <span itemprop="name"><?= htmlspecialchars($job['company_name'] ?? 'Company') ?></span>
                  </p>
                  <p class="jp-card__location">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    <span itemprop="jobLocation"><?= $loc ?></span>
                    <?php if ($remote): ?>
                    &nbsp;<span class="jp-badge jp-badge-remote"><i class="fas fa-home"></i> Remote OK</span>
                    <?php endif; ?>
                  </p>
                </div>
              </div>

              <!-- Badges -->
              <div class="jp-badge-row">
                <span class="jp-badge jp-badge-type" itemprop="employmentType">
                  <i class="fas fa-briefcase"></i> <?= $type ?>
                </span>
                <span class="jp-badge jp-badge-cat">
                  <i class="fas fa-tag"></i> <?= $cat ?>
                </span>
                <?php if ($salary): ?>
                <span class="jp-badge jp-badge-salary">
                  <i class="fas fa-rupee-sign"></i> <?= $salary ?>
                </span>
                <?php endif; ?>
                <?php if ($exp && $exp !== 'Any'): ?>
                <span class="jp-badge jp-badge-exp">
                  <i class="fas fa-user-clock"></i> <?= htmlspecialchars($exp) ?>
                </span>
                <?php endif; ?>
              </div>

              <!-- Description snippet -->
              <p class="jp-card__desc" itemprop="description"><?= $desc ?>…</p>

              <!-- Footer: time + actions -->
              <footer class="jp-card__footer">
                <div class="jp-card__time">
                  <i class="fas fa-clock"></i>
                  <span itemprop="datePosted" content="<?= date('Y-m-d', strtotime($job['created_at'] ?? 'now')) ?>">
                    <?= $posted ?>
                  </span>
                  <?php if ($apps > 0): ?>
                  &nbsp;·&nbsp; <i class="fas fa-users me-1"></i> <?= $apps ?> applied
                  <?php endif; ?>
                </div>
                <div class="jp-card__actions">
                  <!-- Save job (session-based) -->
                  <button type="button"
                          class="jp-btn-save <?= !empty($_SESSION['saved_jobs'][$jid]) ? 'saved' : '' ?>"
                          onclick="toggleSave(this, <?= $jid ?>)"
                          aria-label="Save job"
                          title="Save job">
                    <i class="<?= !empty($_SESSION['saved_jobs'][$jid]) ? 'fas' : 'far' ?> fa-heart"></i>
                  </button>
                  <!-- WhatsApp quick-apply (only if phone available) -->
                  <?php if (!empty($job['employer_phone'])): ?>
                  <a href="<?= $wa_href ?>" target="_blank" rel="noopener"
                     class="jp-btn-wa" title="Apply via WhatsApp">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                  </a>
                  <?php endif; ?>
                  <!-- View details -->
                  <a href="job-detail-omr.php?id=<?= $jid ?>" class="jp-btn-view">
                    View <i class="fas fa-arrow-right"></i>
                  </a>
                </div>
              </footer>

            </article>
            <?php endforeach; ?>

          <?php else: ?>
            <div class="jp-no-results">
              <i class="fas fa-search-minus"></i>
              <h3>No Jobs Found</h3>
              <p class="text-muted">
                <?php if (!empty($filters)): ?>
                  Try adjusting your filters or <a href="/omr-local-job-listings/">browse all jobs</a>.
                <?php else: ?>
                  No listings available right now. Check back soon!
                <?php endif; ?>
              </p>
              <a href="/omr-local-job-listings/" class="jp-btn-view mt-3">
                <i class="fas fa-refresh me-1"></i> View All Jobs
              </a>
            </div>
          <?php endif; ?>
        </div><!-- /#jobs-grid -->

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav class="jp-pagination" aria-label="Pagination" id="jobs-pagination">
          <?php if ($current_page > 1): ?>
          <a class="jp-page-btn" href="<?= htmlspecialchars($base_url . (str_contains($base_url,'?') ? '&' : '?') . 'page=' . ($current_page - 1)) ?>">
            <i class="fas fa-chevron-left"></i>
          </a>
          <?php endif; ?>

          <?php for ($p = max(1, $current_page - 2); $p <= min($total_pages, $current_page + 2); $p++): ?>
          <a class="jp-page-btn<?= $p === $current_page ? ' active' : '' ?>"
             href="<?= htmlspecialchars($base_url . (str_contains($base_url,'?') ? '&' : '?') . 'page=' . $p) ?>"
             <?= $p === $current_page ? 'aria-current="page"' : '' ?>>
            <?= $p ?>
          </a>
          <?php endfor; ?>

          <?php if ($current_page < $total_pages): ?>
          <a class="jp-page-btn" href="<?= htmlspecialchars($base_url . (str_contains($base_url,'?') ? '&' : '?') . 'page=' . ($current_page + 1)) ?>">
            <i class="fas fa-chevron-right"></i>
          </a>
          <?php endif; ?>
        </nav>
        <?php endif; ?>

        <!-- Employer CTA banner -->
        <div class="jp-employer-banner">
          <h2>Hiring in OMR? Post a Job — It's Free</h2>
          <p>Connect with thousands of skilled local candidates instantly. Reach job seekers across Perungudi, Sholinganallur, Thoraipakkam &amp; more.</p>
          <a href="post-job-omr.php" class="jp-btn-white me-2">
            <i class="fas fa-plus"></i> Post a Job Free
          </a>
          <a href="employer-register-omr.php" class="jp-btn-outline-white">
            <i class="fas fa-building"></i> Employer Register
          </a>
        </div>

      </section><!-- /main content -->
    </div><!-- /.jp-layout -->
  </div><!-- /.container -->
</main>

<?php omr_footer(); ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/job-portal-2026.js"></script>
<?php include ROOT_PATH . '/components/sdg-badge.php'; ?>

<script>
/* Mobile sidebar */
function openSidebar() {
  document.getElementById('sidebarDrawer').classList.add('open');
  document.getElementById('sidebarOverlay').classList.add('show');
  document.body.style.overflow = 'hidden';
}
function closeSidebar() {
  document.getElementById('sidebarDrawer').classList.remove('open');
  document.getElementById('sidebarOverlay').classList.remove('show');
  document.body.style.overflow = '';
}

/* Save job (session via fetch) */
function toggleSave(btn, jobId) {
  const saved = btn.classList.contains('saved');
  fetch('includes/save-job.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({job_id: jobId, action: saved ? 'remove' : 'save'})
  }).then(r => r.json()).then(d => {
    if (d.ok) {
      btn.classList.toggle('saved', !saved);
      btn.querySelector('i').className = (!saved ? 'fas' : 'far') + ' fa-heart';
    }
  });
}

/* Job alert form */
document.getElementById('job-alert-form')?.addEventListener('submit', function(e) {
  e.preventDefault();
  const fd = new FormData(this);
  fetch(this.action, {method:'POST', body: fd})
    .then(r => r.json())
    .then(d => {
      if (d.ok) {
        this.innerHTML = '<p style="color:#1d4ed8;font-weight:600"><i class="fas fa-check-circle me-1"></i> You\'re subscribed! We\'ll notify you of new matches.</p>';
      }
    });
});
</script>

</body>
</html>
