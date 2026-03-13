<?php
/**
 * Job Portal — Database Diagnostic Page
 * Use to debug why listings show "No Jobs Found" when count shows jobs exist.
 *
 * SECURITY: Restrict access (e.g. .htaccess, IP allowlist, or remove after debugging).
 * Optional: add ?key=YOUR_SECRET to block casual access.
 */
$diag_key = 'myomr_job_diag_2026';
$key_ok = (!isset($_GET['key']) || $_GET['key'] === $diag_key);

if (!$key_ok) {
    header('HTTP/1.0 403 Forbidden');
    echo '<h1>403 Forbidden</h1><p>Add ?key=' . htmlspecialchars($diag_key) . ' to the URL to run the diagnostic.</p>';
    exit;
}

require_once __DIR__ . '/includes/error-reporting.php';
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
}
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/core/omr-connect.php';
require_once __DIR__ . '/includes/job-functions-omr.php';

global $conn;

$sections = [];
$errors = [];

// ─── 1. Connection ─────────────────────────────────────────────────────────
$sections['Connection'] = [
    'DB connected' => isset($conn) && $conn instanceof mysqli && !$conn->connect_error,
    'Server'       => isset($conn) && $conn instanceof mysqli ? $conn->host_info : 'N/A',
    'Database'     => isset($conn) && $conn instanceof mysqli ? (method_exists($conn, 'get_server_info') ? $conn->get_server_info() : '—') : 'N/A',
];

if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
    $errors[] = 'Database connection failed. Check core/omr-connect.php and DB credentials.';
}

// ─── 2. Raw table counts (no status filter) ─────────────────────────────────
$raw_total = 0;
$by_status = [];
$by_job_type = [];
$distinct_status = [];
$distinct_job_type = [];

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $r = $conn->query("SELECT COUNT(*) AS c FROM job_postings");
    if ($r) {
        $raw_total = (int) $r->fetch_assoc()['c'];
    }

    $r = $conn->query("SELECT status, COUNT(*) AS c FROM job_postings GROUP BY status ORDER BY c DESC");
    if ($r) {
        while ($row = $r->fetch_assoc()) {
            $by_status[$row['status']] = (int) $row['c'];
            $distinct_status[] = $row['status'];
        }
    }

    $r = $conn->query("SELECT job_type, COUNT(*) AS c FROM job_postings GROUP BY job_type ORDER BY c DESC");
    if ($r) {
        while ($row = $r->fetch_assoc()) {
            $val = $row['job_type'];
            if ($val === null || $val === '') {
                $val = '(empty/NULL)';
            }
            $by_job_type[$val] = (int) $row['c'];
            $distinct_job_type[] = $row['job_type'];
        }
    }
}

$sections['Raw table'] = [
    'Total rows in job_postings' => $raw_total,
    'By status'                  => $by_status ?: '(none)',
    'By job_type (exact values in DB)' => $by_job_type ?: '(none)',
    'Distinct status values'     => $distinct_status ?: [],
    'Distinct job_type values'   => $distinct_job_type ?: [],
];

// ─── 3. What index.php would use (current request) ──────────────────────────
$allowed = ['search','category','location','job_type','experience_level','is_remote','salary_min','salary_max'];
$filters_from_get = [];
foreach ($allowed as $k) {
    if (isset($_GET[$k]) && $_GET[$k] !== '') {
        $filters_from_get[$k] = sanitizeInput($_GET[$k]);
    }
}
if (!empty($_GET['walk_in'])) {
    $filters_from_get['job_type'] = 'walk-in';
}

$count_with_filters = 0;
$list_with_filters = [];
if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $count_with_filters = getJobCount($filters_from_get);
    $list_with_filters = getJobListings($filters_from_get, 12, 0);
}

$sections['Current request (simulating index.php)'] = [
    '$_GET (relevant keys)' => array_intersect_key($_GET, array_flip(array_merge($allowed, ['walk_in','page']))),
    'Parsed $filters'       => $filters_from_get,
    'getJobCount($filters)' => $count_with_filters,
    'getJobListings($filters, 12, 0) count' => count($list_with_filters),
];

if ($count_with_filters > 0 && count($list_with_filters) === 0) {
    $errors[] = 'Count says ' . $count_with_filters . ' but list is empty — possible pagination/query bug.';
}

// ─── 4. Approved-only (no filters) ────────────────────────────────────────
$count_approved_only = 0;
$list_approved_only = [];
if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $count_approved_only = getJobCount([]);
    $list_approved_only = getJobListings([], 5, 0);
}

$sections['Approved only (no filters)'] = [
    'getJobCount([])'           => $count_approved_only,
    'getJobListings([], 5, 0)'  => count($list_approved_only) . ' rows',
    'Sample job IDs'            => array_column($list_approved_only, 'id'),
];

if ($raw_total > 0 && $count_approved_only === 0) {
    $errors[] = 'You have jobs in the table but none with status = "approved". Check status values (case-sensitive in code).';
}

// ─── 5. Case / value mismatch check for job_type ───────────────────────────
$sample_job_types = [];
if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error && $raw_total > 0) {
    $r = $conn->query("SELECT id, title, status, job_type FROM job_postings LIMIT 20");
    if ($r) {
        while ($row = $r->fetch_assoc()) {
            $sample_job_types[] = [
                'id' => $row['id'],
                'title' => mb_strimwidth($row['title'] ?? '', 0, 40, '…'),
                'status' => $row['status'],
                'job_type' => $row['job_type'] === null || $row['job_type'] === '' ? '(empty)' : $row['job_type'],
            ];
        }
    }
}

$sections['Sample rows (id, title, status, job_type)'] = [
    'First 20 rows' => $sample_job_types ?: '(none)',
];

// Expected job_type values from UI (sidebar): full-time, part-time, contract, internship, walk-in
$expected_job_types = ['full-time', 'part-time', 'contract', 'internship', 'walk-in'];
$mismatch = array_diff(array_map('strtolower', array_filter($distinct_job_type)), array_map('strtolower', $expected_job_types));
if (!empty($distinct_job_type) && (count(array_intersect(array_map('strtolower', $distinct_job_type), $expected_job_types)) === 0)) {
    $errors[] = 'DB job_type values do not match UI filter values. UI uses: ' . implode(', ', $expected_job_types) . '. Normalize DB (e.g. LOWER(job_type)) or filter logic.';
}

// ─── 6. Categories (for sidebar) ───────────────────────────────────────────
$categories = [];
try {
    $categories = getJobCategories();
} catch (Throwable $e) {
    $errors[] = 'getJobCategories(): ' . $e->getMessage();
}
$sections['Categories (sidebar)'] = [
    'getJobCategories() count' => is_array($categories) ? count($categories) : 0,
    'Sample slugs' => is_array($categories) ? array_column(array_slice($categories, 0, 10), 'slug') : [],
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job DB Diagnostic — MyOMR</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 1rem; max-width: 960px; }
        h1 { color: #14532d; }
        h2 { font-size: 1rem; margin-top: 1.5rem; color: #333; border-bottom: 1px solid #ccc; padding-bottom: 0.25rem; }
        table { border-collapse: collapse; width: 100%; margin: 0.5rem 0; }
        th, td { text-align: left; padding: 0.35rem 0.75rem; border: 1px solid #ddd; }
        th { background: #f5f5f5; }
        .ok { color: #0a0; }
        .err { color: #c00; font-weight: 600; }
        .errors { background: #fdd; padding: 1rem; margin: 1rem 0; border-radius: 6px; }
        pre { background: #f5f5f5; padding: 0.75rem; overflow-x: auto; font-size: 0.9rem; }
        ul { margin: 0.25rem 0; padding-left: 1.25rem; }
    </style>
</head>
<body>
    <h1>Job Portal — Database Diagnostic</h1>
    <p>Use this page to see why listings might show "No Jobs Found" while counts show jobs exist.</p>

    <?php if (!empty($errors)): ?>
    <div class="errors">
        <strong>Possible issues:</strong>
        <ul>
            <?php foreach ($errors as $e): ?>
            <li class="err"><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php foreach ($sections as $title => $data): ?>
    <h2><?= htmlspecialchars($title) ?></h2>
    <table>
        <tbody>
            <?php foreach ($data as $k => $v): ?>
            <tr>
                <td><strong><?= htmlspecialchars($k) ?></strong></td>
                <td>
                    <?php
                    if (is_bool($v)) {
                        echo $v ? '<span class="ok">Yes</span>' : '<span class="err">No</span>';
                    } elseif (is_array($v) && isset($v[0]) && is_array($v[0])) {
                        echo '<pre>' . htmlspecialchars(print_r($v, true)) . '</pre>';
                    } elseif (is_array($v)) {
                        echo '<pre>' . htmlspecialchars(print_r($v, true)) . '</pre>';
                    } else {
                        echo htmlspecialchars((string) $v);
                    }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endforeach; ?>

    <h2>Quick checks</h2>
    <ul>
        <li><strong>If "By status"</strong> has no <code>approved</code> or count 0 for approved → jobs won’t show (listing only shows status = 'approved').</li>
        <li><strong>If "By job_type"</strong> uses different casing or spelling (e.g. "Walk-In" vs "walk-in") → filter may return 0. Normalize stored values to lowercase with hyphen.</li>
        <li><strong>If "Parsed $filters"</strong> contains <code>job_type => walk-in</code> but DB has no job with <code>job_type = 'walk-in'</code> → you’ll see count 0 and no list.</li>
    </ul>

    <p><small>Remove or restrict <code>job-database-diagnostic.php</code> after debugging. Add ?key=<?= htmlspecialchars($diag_key) ?> to access.</small></p>
</body>
</html>
