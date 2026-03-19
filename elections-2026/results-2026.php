<?php
/**
 * Results 2026 – Winners for OMR constituencies after counting (4 May 2026).
 * Placeholder until official results are available; then update with ECI/CEO TN data.
 */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/constituency-data.php';

$page_nav = 'main';
$page_title = 'Tamil Nadu Election 2026 Results – OMR Constituencies | MyOMR';
$page_description = 'Assembly election 2026 results for Sholinganallur, Velachery and Thiruporur. Winners and links to ECI official results.';
$page_keywords = 'election 2026 results, OMR results, Sholinganallur Velachery Thiruporur winner';
$canonical_url = 'https://myomr.in/elections-2026/results-2026.php';
$og_type = 'website';
$og_title = $page_title;
$og_description = $page_description;
$og_url = $canonical_url;
$twitter_title = $page_title;
$twitter_description = $page_description;
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/elections-2026/', 'Elections 2026'],
    [$canonical_url, 'Results 2026'],
];

$counting_date = new DateTime('2026-05-04');
$today = new DateTime('today');
$results_available = ($today >= $counting_date);
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
                    <li class="breadcrumb-item active" aria-current="page">Results 2026</li>
                </ol>
            </nav>
            <h1 class="h2 mb-2">Results 2026</h1>
            <p class="text-muted mb-0">Tamil Nadu Assembly election – OMR constituencies: Sholinganallur, Velachery, Thiruporur.</p>
        </div>
    </header>

    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <?php if (!$results_available): ?>
                    <div class="alert alert-info">
                        <p class="mb-0"><strong>Counting:</strong> 4 May 2026. Results for OMR constituencies will be updated here after official declaration. For live results, check the <a href="https://results.eci.gov.in" target="_blank" rel="noopener noreferrer">Election Commission of India</a> or <a href="https://www.elections.tn.gov.in" target="_blank" rel="noopener noreferrer">Tamil Nadu CEO</a> website.</p>
                    </div>
                    <p><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/dates.php">Key dates</a> · <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/">Elections 2026</a></p>
                    <?php else: ?>
                    <p class="text-muted small">Official results from ECI/CEO TN. Last updated after counting 4 May 2026.</p>
                    <ul class="list-group list-group-flush mb-4">
                        <?php
                        $ac_labels = ['sholinganallur' => 'Sholinganallur (AC 27)', 'velachery' => 'Velachery (AC 26)', 'thiruporur' => 'Thiruporur'];
                        foreach ($ac_labels as $slug => $label):
                            $ac = $elections_2026_constituencies[$slug] ?? null;
                            if (!$ac) continue;
                            $winner_2026 = null; // Populate from DB or static after 4 May 2026
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?php echo htmlspecialchars($label); ?></span>
                            <?php if ($winner_2026): ?>
                            <strong><?php echo htmlspecialchars($winner_2026); ?></strong>
                            <?php else: ?>
                            <span class="text-muted">—</span>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <p><a href="https://results.eci.gov.in" target="_blank" rel="noopener noreferrer">ECI results</a> · <a href="https://www.elections.tn.gov.in" target="_blank" rel="noopener noreferrer">TN CEO</a> · <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/">Elections 2026</a></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
</body>
</html>
