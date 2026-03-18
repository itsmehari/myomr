<?php
/**
 * Key announcements – ECI, SEC, parties. Uses DB if table exists, else static list.
 */
require_once __DIR__ . '/includes/bootstrap.php';

$page_nav = 'main';
$page_title = 'Election 2026 Announcements – ECI, Parties | MyOMR';
$page_description = 'Key announcements: ECI poll schedule, Model Code, NTK candidate list, and other election 2026 updates for OMR.';
$page_keywords = 'election 2026 announcements, ECI Tamil Nadu, NTK candidates';
$canonical_url = 'https://myomr.in/elections-2026/announcements.php';
$og_type = 'website';
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/elections-2026/', 'Elections 2026'],
    [$canonical_url, 'Announcements'],
];

$announcements = [];
if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $tables = $conn->query("SHOW TABLES LIKE 'election_2026_announcements'");
    if ($tables && $tables->num_rows > 0) {
        $res = $conn->query("SELECT announcement_date, title, source, summary, url FROM election_2026_announcements ORDER BY announcement_date DESC LIMIT 20");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $announcements[] = $row;
            }
        }
    }
}

if (empty($announcements)) {
    $announcements = [
        ['announcement_date' => '2026-03-15', 'title' => 'ECI announces Tamil Nadu Assembly poll schedule', 'source' => 'ECI', 'summary' => 'Poll 23 April 2026, counting 4 May. Model Code of Conduct in effect.', 'url' => 'https://myomr.in/local-news/tamil-nadu-assembly-election-2026-dates-ec-omr-constituencies'],
        ['announcement_date' => '2026-02-21', 'title' => 'NTK announces full list of 234 candidates', 'source' => 'NTK', 'summary' => 'Seeman to contest from Karaikudi. 117 men, 117 women.', 'url' => null],
    ];
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
                    <li class="breadcrumb-item active" aria-current="page">Announcements</li>
                </ol>
            </nav>
            <h1 class="h2 mb-2">Announcements</h1>
            <p class="text-muted mb-0">Key election 2026 updates from ECI, SEC and parties.</p>
        </div>
    </header>

    <section class="py-4 py-md-5">
        <div class="container">
            <ul class="list-unstyled">
                <?php foreach ($announcements as $a): ?>
                    <li class="mb-4 pb-3 border-bottom">
                        <span class="text-muted small"><?php echo date('d M Y', strtotime($a['announcement_date'])); ?></span>
                        <?php if (!empty($a['source'])): ?>
                            <span class="badge bg-secondary"><?php echo htmlspecialchars($a['source']); ?></span>
                        <?php endif; ?>
                        <h2 class="h6 mb-1 mt-1"><?php echo htmlspecialchars($a['title']); ?></h2>
                        <p class="small mb-0"><?php echo htmlspecialchars($a['summary']); ?></p>
                        <?php if (!empty($a['url'])): ?>
                            <a href="<?php echo htmlspecialchars($a['url']); ?>" class="small">Read more</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="mt-4"><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/">Back to Elections 2026</a></p>
        </div>
    </section>
</main>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
</body>
</html>
