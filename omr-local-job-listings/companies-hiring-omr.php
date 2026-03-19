<?php
/**
 * Companies hiring on OMR — directory of employers with active job count.
 * Links to job listing filtered by employer_id.
 */
require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/job-functions-omr.php';
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
}
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';
require_once ROOT_PATH . '/core/omr-connect.php';
global $conn;

$companies = [];
if ($conn instanceof mysqli && !$conn->connect_error) {
    $sql = "SELECT e.id, e.company_name, e.website,
            COUNT(j.id) AS job_count
            FROM employers e
            INNER JOIN job_postings j ON j.employer_id = e.id AND j.status = 'approved'
            GROUP BY e.id, e.company_name, e.website
            HAVING job_count > 0
            ORDER BY job_count DESC, e.company_name ASC";
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) $companies[] = $row;
    }
}

$page_title = 'Companies Hiring on OMR — Employers Directory | MyOMR';
$page_desc  = 'Browse companies hiring on Old Mahabalipuram Road, Chennai. Find employers with active job openings and apply directly.';
$canonical  = 'https://myomr.in/omr-local-job-listings/companies-hiring-omr.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>
<meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
<link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
<meta property="og:description" content="<?= htmlspecialchars($page_desc) ?>">
<meta property="og:url" content="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:type" content="website">
<?php include ROOT_PATH . '/components/analytics.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/core.css">
<link rel="stylesheet" href="/assets/css/footer.css">
<link rel="stylesheet" href="assets/job-portal-2026.css">
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"ItemList","name":"Companies hiring on OMR","description":"<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>","numberOfItems":<?= count($companies) ?>,"itemListElement":[<?php
$first = true;
foreach ($companies as $i => $c) {
    if (!$first) echo ',';
    $url = 'https://myomr.in/omr-local-job-listings/?employer_id=' . (int)$c['id'];
    echo '{"@type":"ListItem","position":' . ($i+1) . ',"name":' . json_encode($c['company_name']) . ',"url":"' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '"}';
    $first = false;
}
?>]}
</script>
</head>
<body class="job-portal-page">
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
  <div class="container py-4">
    <nav aria-label="Breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="/omr-local-job-listings/">Jobs in OMR</a></li>
        <li class="breadcrumb-item active" aria-current="page">Companies hiring</li>
      </ol>
    </nav>
    <h1 class="h2 mb-2">Companies hiring on OMR</h1>
    <p class="text-muted mb-4">Employers with active job openings. Click to see their listings.</p>

    <?php if (!empty($companies)): ?>
      <div class="row g-3">
        <?php foreach ($companies as $c): ?>
          <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
              <div class="card-body d-flex flex-column">
                <h2 class="h6 card-title"><?= htmlspecialchars($c['company_name']) ?></h2>
                <p class="text-muted small mb-2">
                  <strong><?= (int)$c['job_count'] ?></strong> open position<?= (int)$c['job_count'] !== 1 ? 's' : '' ?>
                </p>
                <a href="/omr-local-job-listings/?employer_id=<?= (int)$c['id'] ?>" class="btn btn-success btn-sm mt-auto">
                  View jobs <i class="fas fa-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="lead text-muted">No companies with active listings yet. Check back soon or <a href="/omr-local-job-listings/post-job-omr.php">post a job</a>.</p>
    <?php endif; ?>

    <div class="mt-4">
      <a href="/omr-local-job-listings/" class="btn btn-outline-primary">Back to all jobs</a>
      <a href="/it-jobs-omr-chennai.php" class="btn btn-outline-success ms-2">IT jobs in OMR</a>
    </div>
  </div>
</main>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
