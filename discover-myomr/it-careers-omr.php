<?php
/**
 * IT Careers & Jobs on OMR — Content hub
 * Lists IT/career articles and links to job listing and landing pages.
 */
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';
require_once ROOT_PATH . '/core/omr-connect.php';

$page_nav = 'main';
$omr_css_bootstrap5 = true;
$omr_css_megamenu = false;

$per_page = 12;
$cur_page = max(1, (int)($_GET['page'] ?? 1));
$offset   = ($cur_page - 1) * $per_page;

// IT / career categories and tag patterns
$it_categories = ['IT Career', 'Career Tips', 'OMR Jobs', 'Jobs', 'IT'];
$where_parts   = ["status = 'published'", "slug NOT LIKE '%-tamil'"];
$bind_types    = '';
$bind_params   = [];

$placeholders = implode(',', array_fill(0, count($it_categories), '?'));
$where_parts[] = "(category IN ($placeholders) OR tags LIKE ? OR tags LIKE ?)";
$bind_types   .= str_repeat('s', count($it_categories)) . 'ss';
$bind_params   = array_merge($bind_params, $it_categories, ['%it-corridor%', '%it-career%']);
$where_sql    = 'WHERE ' . implode(' AND ', $where_parts);

$count_sql = "SELECT COUNT(*) AS c FROM articles $where_sql";
$stmt = $conn->prepare($count_sql);
if ($stmt) {
    $stmt->bind_param($bind_types, ...$bind_params);
    $stmt->execute();
    $total_rows = (int)$stmt->get_result()->fetch_assoc()['c'];
    $stmt->close();
} else {
    $total_rows = 0;
}
$total_pages = max(1, (int)ceil($total_rows / $per_page));
$cur_page    = min($cur_page, $total_pages);
$offset      = ($cur_page - 1) * $per_page;

$data_sql = "SELECT id, slug, title, summary, category, author, image_path, published_date
             FROM articles $where_sql
             ORDER BY published_date DESC
             LIMIT ? OFFSET ?";
$stmt2 = $conn->prepare($data_sql);
$articles = [];
if ($stmt2) {
    $stmt2->bind_param($bind_types . 'ii', ...array_merge($bind_params, [$per_page, $offset]));
    $stmt2->execute();
    $res = $stmt2->get_result();
    while ($row = $res->fetch_assoc()) $articles[] = $row;
    $stmt2->close();
}

$page_title     = 'IT Careers & Jobs on OMR — Chennai IT Corridor | MyOMR';
$page_description = 'Career tips, IT job market updates, and hiring news for Old Mahabalipuram Road (OMR), Chennai. Find IT jobs, fresher jobs, and employer resources.';
$canonical_url  = 'https://myomr.in/discover-myomr/it-careers-omr.php';
$og_image       = 'https://myomr.in/My-OMR-Logo.png';
$og_title       = $page_title;
$og_description = $page_description;
$og_url         = $canonical_url;
$breadcrumbs    = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/omr-local-job-listings/', 'Jobs in OMR'],
    [$canonical_url, 'IT Career News'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once ROOT_PATH . '/components/meta.php'; ?>
<?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; }
.maxw-1280 { max-width: 1280px; margin: 0 auto; }
.hub-hero { background: linear-gradient(135deg, #14532d 0%, #166534 100%); color: #fff; padding: 3rem 0 2rem; }
.hub-hero h1 { font-weight: 700; margin-bottom: 0.5rem; }
.hub-hero .lead { opacity: 0.95; font-size: 1.1rem; }
.job-cta-strip { background: rgba(255,255,255,0.12); border-radius: 12px; padding: 1.25rem; margin-top: 1.5rem; }
.job-cta-strip a { color: #fff; font-weight: 600; margin-right: 1rem; margin-bottom: 0.5rem; display: inline-block; }
.job-cta-strip a:hover { color: #dcfce7; }
.article-card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem; transition: box-shadow 0.2s; }
.article-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
.article-card a { color: inherit; text-decoration: none; }
.article-card h3 { font-size: 1.1rem; margin-bottom: 0.35rem; color: #14532d; }
.article-card .meta { font-size: 0.85rem; color: #6b7280; }
.pagination-wrap { margin-top: 2rem; }
</style>
<script type="application/ld+json">
<?php echo json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => 'IT Careers & Jobs on OMR',
    'description' => $page_description,
    'url' => $canonical_url,
    'publisher' => ['@type' => 'Organization', 'name' => 'MyOMR.in'],
    'mainEntity' => [
        '@type' => 'ItemList',
        'numberOfItems' => count($articles),
        'itemListElement' => array_map(function ($a, $i) {
            return [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'url' => 'https://myomr.in/local-news/' . $a['slug'],
                'name' => $a['title'],
            ];
        }, $articles, array_keys($articles)),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
<script type="application/ld+json">
<?php echo json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => ['@id' => 'https://myomr.in/']],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Jobs in OMR', 'item' => ['@id' => 'https://myomr.in/omr-local-job-listings/']],
        ['@type' => 'ListItem', 'position' => 3, 'name' => 'IT Career News', 'item' => ['@id' => $canonical_url]],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
</head>
<body>
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
<section class="hub-hero">
  <div class="container maxw-1280">
    <h1>IT Careers &amp; Jobs on OMR</h1>
    <p class="lead">Career tips, IT job market updates, and hiring news for Chennai&rsquo;s IT corridor. Read, then find your next role.</p>
    <div class="job-cta-strip">
      <strong>Find jobs:</strong>
      <a href="/omr-local-job-listings/">All jobs in OMR</a>
      <a href="/it-jobs-omr-chennai.php">IT jobs in OMR</a>
      <a href="/fresher-jobs-omr-chennai.php">Fresher jobs</a>
      <a href="/jobs-in-perungudi-omr.php">Jobs in Perungudi</a>
      <a href="/jobs-in-sholinganallur-omr.php">Jobs in Sholinganallur</a>
      <a href="/jobs-in-navalur-omr.php">Jobs in Navalur</a>
      <a href="/omr-local-job-listings/candidate-profile-omr.php?utm_source=it_careers_hub&utm_medium=strip&utm_campaign=job_seeker_profile">Job seeker profile (CV)</a>
    </div>
  </div>
</section>

<div class="container maxw-1280 py-4">
  <?php if (!empty($articles)): ?>
    <div class="row">
      <div class="col-lg-8">
        <h2 class="h4 mb-3">Career &amp; IT hiring articles</h2>
        <?php foreach ($articles as $a): ?>
          <article class="article-card">
            <a href="/local-news/<?php echo htmlspecialchars($a['slug']); ?>">
              <h3><?php echo htmlspecialchars($a['title']); ?></h3>
              <?php if (!empty($a['summary'])): ?>
                <p class="small mb-1"><?php echo htmlspecialchars(mb_substr($a['summary'], 0, 160)); ?><?php echo mb_strlen($a['summary']) > 160 ? '…' : ''; ?></p>
              <?php endif; ?>
              <p class="meta mb-0">
                <?php echo htmlspecialchars($a['category'] ?? 'News'); ?>
                <?php if (!empty($a['published_date'])): ?>
                  · <?php echo date('M j, Y', strtotime($a['published_date'])); ?>
                <?php endif; ?>
              </p>
            </a>
          </article>
        <?php endforeach; ?>

        <?php if ($total_pages > 1): ?>
          <nav class="pagination-wrap" aria-label="Pagination">
            <ul class="pagination justify-content-center">
              <?php if ($cur_page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $cur_page - 1; ?>">Previous</a></li>
              <?php endif; ?>
              <li class="page-item disabled"><span class="page-link">Page <?php echo $cur_page; ?> of <?php echo $total_pages; ?></span></li>
              <?php if ($cur_page < $total_pages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $cur_page + 1; ?>">Next</a></li>
              <?php endif; ?>
            </ul>
          </nav>
        <?php endif; ?>
      </div>
      <aside class="col-lg-4">
        <div class="p-3 rounded mb-3" style="background:#e8f4fc;border:1px solid #bae6fd;">
          <h3 class="h6 mb-2">Build your profile</h3>
          <p class="small text-muted mb-2">Upload your résumé once for OMR roles.</p>
          <a class="btn btn-sm btn-primary w-100" href="/omr-local-job-listings/candidate-profile-omr.php?utm_source=it_careers_aside&utm_medium=cta&utm_campaign=job_seeker_profile">Résumé &amp; profile</a>
        </div>
        <div class="p-3 rounded" style="background:#f1f5f9;border:1px solid #e2e8f0;">
          <h3 class="h6 mb-2">Browse jobs</h3>
          <?php
          $link_type = 'all';
          $show_title = false;
          $section_title = 'Jobs by location & industry';
          include ROOT_PATH . '/components/job-landing-page-links.php';
          ?>
        </div>
      </aside>
    </div>
  <?php else: ?>
    <p class="lead">No IT career articles yet. Check back soon — we publish tips and hiring news for OMR.</p>
    <p><a href="/omr-local-job-listings/">Browse all jobs in OMR</a> · <a href="/it-jobs-omr-chennai.php">IT jobs in OMR</a></p>
  <?php endif; ?>
</div>
</main>

<?php omr_footer(); ?>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
</body>
</html>
