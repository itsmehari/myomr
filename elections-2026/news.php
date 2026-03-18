<?php
/**
 * Election news index – Articles from local-news (election-related slugs/tags).
 */
require_once __DIR__ . '/includes/bootstrap.php';

$page_nav = 'main';
$page_title = 'Election 2026 News – OMR & Tamil Nadu | MyOMR';
$page_description = 'Latest election-related news: Tamil Nadu 2026 dates, BLO, Chennai South, OMR constituencies.';
$page_keywords = 'election 2026 news, Tamil Nadu election news, OMR election updates';
$canonical_url = 'https://myomr.in/elections-2026/news.php';
$og_type = 'website';
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/elections-2026/', 'Elections 2026'],
    [$canonical_url, 'News'],
];

$articles = [];
if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $stmt = $conn->prepare("
        SELECT slug, title, summary, published_date, image_path
        FROM articles
        WHERE status = 'published' AND slug NOT LIKE '%-tamil'
          AND (slug LIKE '%election%' OR slug LIKE '%blo%' OR slug LIKE '%constituency%' OR tags LIKE '%election%' OR tags LIKE '%Tamil Nadu election%')
        ORDER BY published_date DESC
        LIMIT 15
    ");
    if ($stmt) {
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $articles[] = $row;
        }
        $stmt->close();
    }
}

// Fallback curated slugs if query returns nothing
if (empty($articles)) {
    $fallback_slugs = [
        'tamil-nadu-assembly-election-2026-dates-ec-omr-constituencies',
        'find-your-blo-officer-shozhinganallur-electoral-roll-revision',
    ];
    foreach ($fallback_slugs as $slug) {
        $stmt = $conn->prepare("SELECT slug, title, summary, published_date, image_path FROM articles WHERE slug = ? AND status = 'published'");
        if ($stmt) {
            $stmt->bind_param('s', $slug);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $row = $res->fetch_assoc()) {
                $articles[] = $row;
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once ROOT_PATH . '/components/meta.php'; ?>
    <?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
    <link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="modern-page">
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav(); ?>

<main id="main-content">
    <header class="bg-light py-4 border-bottom">
        <div class="container">
            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="https://myomr.in/">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/">Elections 2026</a></li>
                    <li class="breadcrumb-item active" aria-current="page">News</li>
                </ol>
            </nav>
            <h1 class="h2 mb-2">Election news</h1>
            <p class="text-muted mb-0">Election-related articles from MyOMR local news.</p>
        </div>
    </header>

    <section class="py-4 py-md-5">
        <div class="container">
            <?php if (!empty($articles)): ?>
                <ul class="list-unstyled">
                    <?php foreach ($articles as $a): ?>
                        <li class="mb-4 pb-3 border-bottom">
                            <h2 class="h6 mb-1"><a href="https://myomr.in/local-news/<?php echo htmlspecialchars($a['slug']); ?>"><?php echo htmlspecialchars($a['title']); ?></a></h2>
                            <p class="small text-muted mb-0"><?php echo htmlspecialchars(substr($a['summary'], 0, 160)); ?>…</p>
                            <span class="small text-muted"><?php echo date('d M Y', strtotime($a['published_date'])); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No election articles found. <a href="https://myomr.in/local-news/">Browse all local news</a>.</p>
            <?php endif; ?>
            <p class="mt-4"><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/">Back to Elections 2026</a></p>
        </div>
    </section>
</main>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
</body>
</html>
