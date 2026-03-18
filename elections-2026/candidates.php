<?php
/**
 * Candidates 2026 – Declared and likely candidates for OMR constituencies. Uses DB if table exists.
 */
require_once __DIR__ . '/includes/bootstrap.php';

$page_nav = 'main';
$page_title = 'Election 2026 Candidates – OMR Constituencies | MyOMR';
$page_description = 'Declared and likely candidates for Sholinganallur, Velachery and Thiruporur. NTK list, DMK and AIADMK updates for Tamil Nadu election 2026.';
$page_keywords = 'election 2026 candidates, Sholinganallur candidates, Velachery MLA 2026, Thiruporur candidates, OMR';
$canonical_url = 'https://myomr.in/elections-2026/candidates.php';
$og_type = 'website';
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/elections-2026/', 'Elections 2026'],
    [$canonical_url, 'Candidates'],
];

$candidates_by_ac = [];
if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $tables = $conn->query("SHOW TABLES LIKE 'election_2026_candidates'");
    if ($tables && $tables->num_rows > 0) {
        $res = $conn->query("SELECT ac_slug, party, candidate_name, bio, announced_at FROM election_2026_candidates ORDER BY ac_slug, sort_order, party");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $ac = $row['ac_slug'];
                if (!isset($candidates_by_ac[$ac])) {
                    $candidates_by_ac[$ac] = [];
                }
                $candidates_by_ac[$ac][] = $row;
            }
        }
    }
}

// Fallback: no table or empty – show known info (NTK announced 234; others TBA)
$ntk_announced = (empty($candidates_by_ac) || array_sum(array_map('count', $candidates_by_ac)) === 0);
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
                    <li class="breadcrumb-item active" aria-current="page">Candidates</li>
                </ol>
            </nav>
            <h1 class="h2 mb-2">Candidates 2026</h1>
            <p class="text-muted mb-0">Declared and likely candidates for OMR and vicinity constituencies.</p>
        </div>
    </header>

    <section class="py-4 py-md-5">
        <div class="container">
            <?php if (!empty($candidates_by_ac)): ?>
                <?php
                $ac_names = ['sholinganallur' => 'Sholinganallur (AC 27)', 'velachery' => 'Velachery (AC 26)', 'thiruporur' => 'Thiruporur'];
                foreach ($ac_names as $slug => $label):
                    if (empty($candidates_by_ac[$slug])) continue;
                ?>
                    <h2 class="h5 mb-3"><?php echo htmlspecialchars($label); ?></h2>
                    <ul class="list-group mb-4">
                        <?php foreach ($candidates_by_ac[$slug] as $c): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div>
                                    <strong><?php echo htmlspecialchars($c['candidate_name']); ?></strong> – <?php echo htmlspecialchars($c['party']); ?>
                                    <?php if (!empty($c['announced_at'])): ?>
                                        <span class="text-muted small">(announced <?php echo htmlspecialchars($c['announced_at']); ?>)</span>
                                    <?php endif; ?>
                                    <?php if (!empty($c['bio'])): ?>
                                        <p class="small mb-0 mt-1"><?php echo nl2br(htmlspecialchars($c['bio'])); ?></p>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            <?php else: ?>
                <p>As of March 2026, <strong>Naam Tamilar Katchi (NTK)</strong> is the only party to have announced a full list of 234 candidates (21 February 2026). NTK chief Seeman will contest from Karaikudi. DMK and AIADMK are yet to announce their full candidate lists for Chennai and OMR seats.</p>
                <p>Declared candidates per constituency will be listed here as data is updated. For the full TN 2026 dates and OMR analysis, see <a href="https://myomr.in/local-news/tamil-nadu-assembly-election-2026-dates-ec-omr-constituencies">Tamil Nadu Assembly Election 2026: Poll Dates, OMR Constituencies &amp; Key Facts</a>.</p>
                <p><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/constituency/sholinganallur.php">Sholinganallur</a> · <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/constituency/velachery.php">Velachery</a> · <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/constituency/thiruporur.php">Thiruporur</a></p>
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
