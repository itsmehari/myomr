<?php
/**
 * MyOMR Job Portal — AJAX Jobs API
 *
 * GET /omr-local-job-listings/api/jobs.php
 *
 * Accepted query params:
 *   search          string  free-text search (title, description, company)
 *   category        string  category slug
 *   location        string  partial location match
 *   job_type        string  full-time | part-time | contract | internship | walk-in
 *   work_segment    string  office | manpower | hybrid
 *   experience_level string Fresher | Junior | Mid-level | Senior | Lead | Any
 *   is_remote       int     0 or 1
 *   salary_min      int     monthly INR
 *   salary_max      int     monthly INR
 *   page            int     1-based page number (default 1)
 *   per_page        int     results per page (default 20, max 50)
 *
 * Returns JSON:
 * {
 *   "jobs":        [...],
 *   "total":       42,
 *   "page":        1,
 *   "per_page":    20,
 *   "total_pages": 3
 * }
 */

// ── Bootstrap ─────────────────────────────────────────────────────────────────
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../includes/job-functions-omr.php';
require_once __DIR__ . '/../../core/omr-connect.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// ── Input sanitisation ────────────────────────────────────────────────────────
$filters = [];

$raw_search   = trim($_GET['search']   ?? '');
$raw_category = trim($_GET['category'] ?? '');
$raw_location = trim($_GET['location'] ?? '');
$raw_job_type = trim($_GET['job_type'] ?? '');
$raw_work_segment = trim($_GET['work_segment'] ?? '');
$raw_exp      = trim($_GET['experience_level'] ?? '');
$raw_remote   = $_GET['is_remote'] ?? '';
$raw_sal_min  = $_GET['salary_min']  ?? '';
$raw_sal_max  = $_GET['salary_max']  ?? '';

$allowed_job_types = ['full-time', 'part-time', 'contract', 'internship', 'walk-in'];
$allowed_work_segments = ['office', 'manpower', 'hybrid'];
$allowed_exp       = ['Fresher', 'Junior', 'Mid-level', 'Senior', 'Lead', 'Any'];

if ($raw_search !== '')                               { $filters['search']           = sanitizeInput($raw_search); }
if ($raw_category !== '')                             { $filters['category']         = sanitizeInput($raw_category); }
if ($raw_location !== '')                             { $filters['location']         = sanitizeInput($raw_location); }
if (in_array(normalizeJobType($raw_job_type), $allowed_job_types, true)) { $filters['job_type'] = normalizeJobType($raw_job_type); }
if (in_array(normalizeJobSegment($raw_work_segment), $allowed_work_segments, true)) { $filters['work_segment'] = normalizeJobSegment($raw_work_segment); }
if (in_array($raw_exp, $allowed_exp, true))           { $filters['experience_level'] = $raw_exp; }
if ($raw_remote !== '' && in_array((int)$raw_remote, [0, 1], true)) {
    $filters['is_remote'] = (int)$raw_remote;
}
if (is_numeric($raw_sal_min) && (int)$raw_sal_min > 0) { $filters['salary_min'] = (int)$raw_sal_min; }
if (is_numeric($raw_sal_max) && (int)$raw_sal_max > 0) { $filters['salary_max'] = (int)$raw_sal_max; }

$per_page = min(50, max(1, (int)($_GET['per_page'] ?? 20)));
$page     = max(1, (int)($_GET['page']     ?? 1));
$offset   = ($page - 1) * $per_page;

// ── Query ─────────────────────────────────────────────────────────────────────
$jobs       = getJobListings($filters, $per_page, $offset);
$total      = getJobCount($filters);
$total_pages = (int) ceil($total / $per_page);

// ── Normalise each job for the JS layer ───────────────────────────────────────
$normalised = array_map(static function (array $job) use ($per_page): array {
    $created_at = $job['created_at'] ?? '';

    // Prefer structured salary columns; fall back to legacy string
    $salary_display = '';
    $sal_min = isset($job['salary_min']) ? (int)$job['salary_min'] : 0;
    $sal_max = isset($job['salary_max']) ? (int)$job['salary_max'] : 0;
    if ($sal_min || $sal_max) {
        $salary_display = formatSalaryStructured($sal_min ?: null, $sal_max ?: null);
    } elseif (!empty($job['salary_range']) && stripos($job['salary_range'], 'not disclosed') === false) {
        $salary_display = formatSalary($job['salary_range']);
    }

    // Truncate description for card preview
    $desc = strip_tags($job['description'] ?? '');
    if (mb_strlen($desc) > 160) {
        $desc = mb_substr($desc, 0, 157) . '…';
    }

    // Company logo — use placeholder letter if none set
    $logo = !empty($job['company_logo']) ? htmlspecialchars($job['company_logo']) : null;

    return [
        'id'               => (int)$job['id'],
        'title'            => $job['title']            ?? '',
        'company_name'     => $job['company_name']     ?? 'Company',
        'company_logo'     => $logo,
        'location'         => $job['location']         ?? '',
        'job_type'         => $job['job_type']         ?? '',
        'work_segment'     => normalizeJobSegment((string)($job['work_segment'] ?? inferJobSegmentFromData($job))),
        'category'         => $job['category']         ?? '',
        'category_name'    => $job['category_name']    ?? '',
        'experience_level' => $job['experience_level'] ?? 'Any',
        'is_remote'        => (bool)($job['is_remote'] ?? false),
        'openings_count'   => (int)($job['openings_count'] ?? 1),
        'salary_display'   => $salary_display,
        'description'      => $desc,
        'featured'         => (bool)$job['featured'],
        'views'            => (int)($job['views'] ?? 0),
        'applications_count' => (int)($job['applications_count'] ?? 0),
        'time_ago'         => $created_at ? timeAgo($created_at) : '',
        'date_posted'      => $created_at ? date('Y-m-d', strtotime($created_at)) : '',
        'deadline'         => (!empty($job['application_deadline']) && $job['application_deadline'] !== '0000-00-00')
                                ? date('M j, Y', strtotime($job['application_deadline']))
                                : null,
        'url'              => function_exists('getJobDetailUrl') ? getJobDetailUrl((int)$job['id'], $job['title'] ?? null) : '/omr-local-job-listings/job-detail-omr.php?id=' . (int)$job['id'],
    ];
}, $jobs);

// ── Build HTML cards for AJAX injection ───────────────────────────────────────
if (!function_exists('formatSalary')) {
    function formatSalary($s) { return $s; }
}

ob_start();
if (!empty($jobs)) {
    foreach ($jobs as $job):
        $loc      = htmlspecialchars($job['location'] ?? '');
        $type     = getJobTypeLabel($job['job_type'] ?? 'full-time');
        $cat      = htmlspecialchars($job['category_name'] ?? $job['category'] ?? 'General');
        $salary   = (!empty($job['salary_range']) && $job['salary_range'] !== 'Not Disclosed')
                    ? formatSalary($job['salary_range']) : '';
        $posted   = timeAgo($job['created_at'] ?? '');
        $initial  = mb_substr($job['company_name'] ?? 'C', 0, 1);
        $desc     = htmlspecialchars(mb_substr(strip_tags($job['description'] ?? ''), 0, 140));
        $jid      = (int)$job['id'];
        $exp      = $job['experience_level'] ?? '';
        $segment  = normalizeJobSegment((string)($job['work_segment'] ?? inferJobSegmentFromData($job)));
        $remote   = !empty($job['is_remote']);
        $apps     = (int)($job['applications_count'] ?? 0);
        $featured = !empty($job['featured']);
        $phone_clean = preg_replace('/\D/', '', $job['employer_phone'] ?? '');
        $wa_msg = rawurlencode("Hi, I'm interested in the {$job['title']} role at {$job['company_name']} from MyOMR.in. Can you share more details? " . (function_exists('getJobDetailUrl') ? getJobDetailUrl($jid, $job['title'] ?? null) : "https://myomr.in/omr-local-job-listings/job-detail-omr.php?id=$jid"));
        $wa_href = $phone_clean ? "https://wa.me/{$phone_clean}?text={$wa_msg}" : "https://wa.me/?text={$wa_msg}";
?>
<article class="jp-card<?= $featured ? ' featured' : '' ?>">
  <?php if ($featured): ?><span class="jp-featured-badge"><i class="fas fa-star me-1"></i>Featured</span><?php endif; ?>
  <div class="jp-card__header">
    <?php if (!empty($job['company_logo'])): ?>
      <img src="<?= htmlspecialchars($job['company_logo']) ?>" alt="" class="jp-card__logo">
    <?php else: ?>
      <div class="jp-card__logo-initial"><?= $initial ?></div>
    <?php endif; ?>
    <div class="jp-card__meta">
      <a href="/omr-local-job-listings/<?= getJobDetailPath($jid, $job['title'] ?? null) ?>" class="jp-card__title"><?= htmlspecialchars($job['title']) ?></a>
      <p class="jp-card__company"><?= htmlspecialchars($job['company_name'] ?? '') ?></p>
      <p class="jp-card__location"><i class="fas fa-map-marker-alt me-1"></i><?= $loc ?><?php if ($remote): ?> <span class="jp-badge jp-badge-remote ms-1"><i class="fas fa-home"></i> Remote</span><?php endif; ?></p>
    </div>
  </div>
  <div class="jp-badge-row">
    <span class="jp-badge jp-badge-type"><i class="fas fa-briefcase"></i> <?= $type ?></span>
    <span class="jp-badge jp-badge-cat"><i class="fas fa-tag"></i> <?= $cat ?></span>
    <?php if ($salary): ?><span class="jp-badge jp-badge-salary"><i class="fas fa-rupee-sign"></i> <?= $salary ?></span><?php endif; ?>
    <?php if ($exp && $exp !== 'Any'): ?><span class="jp-badge jp-badge-exp"><i class="fas fa-user-clock"></i> <?= htmlspecialchars($exp) ?></span><?php endif; ?>
    <span class="jp-badge jp-badge-cat"><i class="fas fa-users"></i> <?= htmlspecialchars(getJobSegmentLabel($segment)) ?></span>
  </div>
  <p class="jp-card__desc"><?= $desc ?>…</p>
  <footer class="jp-card__footer">
    <div class="jp-card__time"><i class="fas fa-clock"></i> <?= $posted ?><?php if ($apps > 0): ?> · <i class="fas fa-users ms-1 me-1"></i><?= $apps ?> applied<?php endif; ?></div>
    <div class="jp-card__actions">
      <button type="button" class="jp-btn-save" onclick="toggleSave(this,<?= $jid ?>)" aria-label="Save"><i class="far fa-heart"></i></button>
      <?php if ($phone_clean): ?><a href="<?= $wa_href ?>" target="_blank" rel="noopener" class="jp-btn-wa"><i class="fab fa-whatsapp"></i> WhatsApp</a><?php endif; ?>
      <a href="/omr-local-job-listings/<?= getJobDetailPath($jid, $job['title'] ?? null) ?>" class="jp-btn-view">View <i class="fas fa-arrow-right"></i></a>
    </div>
  </footer>
</article>
    <?php endforeach;
} else {
    echo '<div class="jp-no-results"><i class="fas fa-search-minus"></i><h3>No Jobs Found</h3><p class="text-muted">Try different keywords or <a href="/omr-local-job-listings/">browse all jobs</a>.</p></div>';
}
$html_output = ob_get_clean();

// Build pagination HTML
ob_start();
$base_url = '/omr-local-job-listings/?';
$pqs = [];
foreach ($filters as $k => $v) $pqs[] = "$k=" . urlencode((string)$v);
$base_url .= implode('&', $pqs);
if ($total_pages > 1) {
    echo '<nav class="jp-pagination">';
    if ($page > 1) echo '<a class="jp-page-btn" href="' . $base_url . '&page=' . ($page - 1) . '"><i class="fas fa-chevron-left"></i></a>';
    for ($p = max(1, $page - 2); $p <= min($total_pages, $page + 2); $p++) {
        $cls = $p === $page ? ' active' : '';
        echo "<a class=\"jp-page-btn{$cls}\" href=\"{$base_url}&page={$p}\">{$p}</a>";
    }
    if ($page < $total_pages) echo '<a class="jp-page-btn" href="' . $base_url . '&page=' . ($page + 1) . '"><i class="fas fa-chevron-right"></i></a>';
    echo '</nav>';
}
$pagination_html = ob_get_clean();

// ── Response ──────────────────────────────────────────────────────────────────
echo json_encode([
    'jobs'        => $normalised,
    'html'        => $html_output,
    'pagination'  => $pagination_html,
    'total'       => $total,
    'page'        => $page,
    'per_page'    => $per_page,
    'total_pages' => $total_pages,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
