<?php
/**
 * Know Your Constituency – Find which AC (Sholinganallur / Velachery / Thiruporur) by locality.
 */
require_once __DIR__ . '/includes/bootstrap.php';

$page_nav = 'main';
$page_title = 'Know Your Constituency – OMR Election 2026 | MyOMR';
$page_description = 'Find which assembly constituency you belong to: Sholinganallur, Velachery or Thiruporur. Enter your locality or area along OMR and Chennai South.';
$page_keywords = 'know your constituency OMR, Sholinganallur constituency, Velachery AC, Thiruporur assembly, OMR election 2026';
$canonical_url = 'https://myomr.in/elections-2026/know-your-constituency.php';
$og_type = 'website';
$og_title = $page_title;
$og_description = $page_description;
$og_url = $canonical_url;
$twitter_title = $page_title;
$twitter_description = $page_description;
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/elections-2026/', 'Elections 2026'],
    [$canonical_url, 'Know Your Constituency'],
];

// Map of localities/areas to AC (simplified; can be moved to DB later)
$locality_to_ac = [
    'sholinganallur' => ['ac' => 'Sholinganallur', 'ac_no' => 27, 'slug' => 'sholinganallur'],
    'thoraipakkam' => ['ac' => 'Sholinganallur', 'ac_no' => 27, 'slug' => 'sholinganallur'],
    'perumbakkam' => ['ac' => 'Sholinganallur', 'ac_no' => 27, 'slug' => 'sholinganallur'],
    'navalur' => ['ac' => 'Sholinganallur', 'ac_no' => 27, 'slug' => 'sholinganallur'],
    'kelambakkam' => ['ac' => 'Thiruporur', 'ac_no' => 0, 'slug' => 'thiruporur'],
    'velachery' => ['ac' => 'Velachery', 'ac_no' => 26, 'slug' => 'velachery'],
    'perungudi' => ['ac' => 'Velachery', 'ac_no' => 26, 'slug' => 'velachery'],
    'taramani' => ['ac' => 'Velachery', 'ac_no' => 26, 'slug' => 'velachery'],
    'adambakkam' => ['ac' => 'Velachery', 'ac_no' => 26, 'slug' => 'velachery'],
    'thiruporur' => ['ac' => 'Thiruporur', 'ac_no' => 0, 'slug' => 'thiruporur'],
    'kandanchavadi' => ['ac' => 'Sholinganallur', 'ac_no' => 27, 'slug' => 'sholinganallur'],
    'karapakkam' => ['ac' => 'Sholinganallur', 'ac_no' => 27, 'slug' => 'sholinganallur'],
    'okkiyam thuraipakkam' => ['ac' => 'Sholinganallur', 'ac_no' => 27, 'slug' => 'sholinganallur'],
    'saidapet' => ['ac' => 'Saidapet', 'ac_no' => 23, 'slug' => null],
    'mylapore' => ['ac' => 'Mylapore', 'ac_no' => 25, 'slug' => null],
];

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$result = null;
if ($search !== '') {
    $key = strtolower($search);
    $key = preg_replace('/\s+/', ' ', $key);
    if (isset($locality_to_ac[$key])) {
        $result = $locality_to_ac[$key];
    } else {
        foreach ($locality_to_ac as $loc => $ac) {
            if (strpos($loc, $key) !== false || strpos($key, $loc) !== false) {
                $result = $ac;
                break;
            }
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
                    <li class="breadcrumb-item active" aria-current="page">Know Your Constituency</li>
                </ol>
            </nav>
            <h1 class="h2 mb-2">Know Your Constituency</h1>
            <p class="text-muted mb-0">Find which assembly constituency (AC) you belong to – Sholinganallur, Velachery or Thiruporur.</p>
        </div>
    </header>

    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form method="get" action="" class="mb-4">
                        <label for="q" class="form-label">Enter your locality or area (e.g. Thoraipakkam, Perungudi, Navalur)</label>
                        <div class="input-group">
                            <input type="text" id="q" name="q" class="form-control" value="<?php echo htmlspecialchars($search); ?>" placeholder="e.g. Sholinganallur, Velachery">
                            <button type="submit" class="btn btn-primary">Find</button>
                        </div>
                    </form>

                    <?php if ($search !== ''): ?>
                        <?php if ($result): ?>
                            <div class="alert alert-success">
                                <strong>You are in:</strong> <?php echo htmlspecialchars($result['ac']); ?> (AC<?php echo (int)$result['ac_no']; ?>)
                                <?php if (!empty($result['slug'])): ?>
                                    <br><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/constituency/<?php echo htmlspecialchars($result['slug']); ?>.php">View <?php echo htmlspecialchars($result['ac']); ?> details</a>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                No exact match for &ldquo;<?php echo htmlspecialchars($search); ?>&rdquo;. Try: Sholinganallur, Thoraipakkam, Perumbakkam, Navalur, Velachery, Perungudi, Taramani, Adambakkam, Thiruporur, Kelambakkam.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <h2 class="h5 mt-4 mb-2">OMR &amp; Chennai South constituencies</h2>
                    <ul>
                        <li><strong>Sholinganallur (AC 27)</strong> – Sholinganallur, Thoraipakkam, Perumbakkam, Navalur, Karapakkam, Kandanchavadi, Okkiyam Thuraipakkam</li>
                        <li><strong>Velachery (AC 26)</strong> – Velachery, Perungudi, Taramani, Adambakkam</li>
                        <li><strong>Thiruporur</strong> – Thiruporur, Kelambakkam (Chengalpattu district)</li>
                    </ul>
                    <p><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/find-blo.php">Find your BLO</a> (Sholinganallur AC) · <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/">Back to Elections 2026</a></p>
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
