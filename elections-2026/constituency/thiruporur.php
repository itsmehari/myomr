<?php
/**
 * Thiruporur – Chengalpattu district, OMR corridor.
 */
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/constituency-data.php';

$ac_slug = 'thiruporur';
$ac = $elections_2026_constituencies[$ac_slug];

$page_nav = 'main';
$page_title = 'Thiruporur Assembly Constituency – Election 2026 | MyOMR';
$page_description = 'Thiruporur constituency: Kelambakkam, OMR corridor in Chengalpattu. 2021 winner S.S. Balaji (VCK). 2026 candidates and election info.';
$page_keywords = 'Thiruporur election 2026, Kelambakkam constituency, Thiruporur MLA, Chengalpattu OMR';
$canonical_url = 'https://myomr.in/elections-2026/constituency/thiruporur.php';
$og_type = 'website';
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/elections-2026/', 'Elections 2026'],
    [$canonical_url, 'Thiruporur'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once ROOT_PATH . '/components/meta.php'; ?>
    <?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
    <link rel="stylesheet" href="/assets/css/footer.css">
    <script type="application/ld+json">
    {"@context":"https://schema.org","@type":"Place","name":"Thiruporur Assembly Constituency","description":"Assembly constituency in Chengalpattu district, part of the OMR corridor: Thiruporur, Kelambakkam.","containedInPlace":{"@type":"Place","name":"Chengalpattu"}}
    </script>
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
                    <li class="breadcrumb-item active" aria-current="page">Thiruporur</li>
                </ol>
            </nav>
            <h1 class="h2 mb-2"><?php echo htmlspecialchars($ac['name']); ?></h1>
            <p class="text-muted mb-0"><?php echo htmlspecialchars($ac['district']); ?> · <?php echo htmlspecialchars(implode(', ', $ac['areas'])); ?></p>
        </div>
    </header>

    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="mb-4 p-3 bg-light rounded small">
                        <strong>Share this guide:</strong>
                        <a href="https://wa.me/?text=<?php echo rawurlencode('Thiruporur – Election 2026: ' . $canonical_url); ?>" target="_blank" rel="noopener noreferrer" class="me-2">WhatsApp</a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo rawurlencode('Thiruporur – Election 2026 on MyOMR ' . $canonical_url); ?>" target="_blank" rel="noopener noreferrer">Twitter / X</a>
                    </div>
                    <h2 class="h5 mb-3">Areas in this constituency</h2>
                    <p><?php echo htmlspecialchars(implode(', ', $ac['areas'])); ?>.</p>

                    <h2 class="h5 mb-3 mt-4">2021 result</h2>
                    <ul class="list-unstyled">
                        <li><strong>Winner:</strong> <?php echo htmlspecialchars($ac['2021']['winner']); ?> (<?php echo htmlspecialchars($ac['2021']['winner_party']); ?>) – <?php echo number_format($ac['2021']['winner_votes']); ?> votes</li>
                        <li><strong>Runner-up:</strong> <?php echo htmlspecialchars($ac['2021']['runner_up']); ?> (<?php echo htmlspecialchars($ac['2021']['runner_party']); ?>) – <?php echo number_format($ac['2021']['runner_votes']); ?> votes</li>
                        <li><strong>Margin:</strong> <?php echo number_format($ac['2021']['margin']); ?></li>
                    </ul>

                    <h2 class="h5 mb-3 mt-4">2026 candidates</h2>
                    <p class="text-muted small">Declared candidates will appear here as parties announce. <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/candidates.php">View all OMR candidates</a>.</p>
                </div>
                <div class="col-lg-4">
                    <p><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/know-your-constituency.php">Know your constituency</a> · <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/">Elections 2026</a></p>
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
