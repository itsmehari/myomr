<?php
/**
 * Are you ready to vote? – 3–5 question quiz; result with links to BLO, EPIC, FAQ.
 */
require_once __DIR__ . '/includes/bootstrap.php';

$page_nav = 'main';
$page_title = 'Are You Ready to Vote? – Election 2026 Quiz | MyOMR';
$page_description = 'Quick quiz: Do you know your constituency, ID, and poll day? Get ready for Tamil Nadu election 2026.';
$page_keywords = 'election 2026 quiz, voter readiness, OMR election';
$canonical_url = 'https://myomr.in/elections-2026/quiz.php';
$og_type = 'website';
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/elections-2026/', 'Elections 2026'],
    [$canonical_url, 'Quiz'],
];

$questions = [
    ['id' => 'q1', 'q' => 'Do you know which assembly constituency (AC) you belong to?', 'ok' => 'Sholinganallur, Velachery or Thiruporur for OMR.'],
    ['id' => 'q2', 'q' => 'Will you carry a valid photo ID (EPIC, passport, licence, or PAN) on poll day?', 'ok' => 'Original document is required.'],
    ['id' => 'q3', 'q' => 'Do you know the poll date for Tamil Nadu Assembly election 2026?', 'ok' => '23 April 2026.'],
    ['id' => 'q4', 'q' => 'Do you know how to find your BLO or polling station?', 'ok' => 'Use our BLO search or ask your BLO.'],
];

$submitted = isset($_POST['quiz_submit']);
$all_yes = $submitted && (
    (!empty($_POST['q1']) && $_POST['q1'] === 'yes') &&
    (!empty($_POST['q2']) && $_POST['q2'] === 'yes') &&
    (!empty($_POST['q3']) && $_POST['q3'] === 'yes') &&
    (!empty($_POST['q4']) && $_POST['q4'] === 'yes')
);
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
                    <li class="breadcrumb-item active" aria-current="page">Quiz</li>
                </ol>
            </nav>
            <h1 class="h2 mb-2">Are you ready to vote?</h1>
            <p class="text-muted mb-0">Quick check – constituency, ID, poll day, BLO.</p>
        </div>
    </header>

    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <?php if (!$submitted): ?>
                    <form method="post" action="">
                        <ol class="list-unstyled">
                            <?php foreach ($questions as $i => $item): ?>
                            <li class="mb-4">
                                <p class="fw-bold mb-2"><?php echo ($i + 1); ?>. <?php echo htmlspecialchars($item['q']); ?></p>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="<?php echo $item['id']; ?>" id="<?php echo $item['id']; ?>-yes" value="yes" required>
                                    <label class="form-check-label" for="<?php echo $item['id']; ?>-yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="<?php echo $item['id']; ?>" id="<?php echo $item['id']; ?>-no" value="no">
                                    <label class="form-check-label" for="<?php echo $item['id']; ?>-no">No</label>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ol>
                        <button type="submit" name="quiz_submit" class="btn btn-primary">See my result</button>
                    </form>
                    <?php else: ?>
                    <?php if ($all_yes): ?>
                    <div class="alert alert-success">
                        <h2 class="h5 alert-heading">You&apos;re ready</h2>
                        <p class="mb-0">You know your constituency, will carry ID, know the poll date (23 April 2026), and how to find your BLO. Don&apos;t forget to vote!</p>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning">
                        <h2 class="h5 alert-heading">Do this next</h2>
                        <p>Review the items you answered &quot;No&quot; to. Use the links below to get ready:</p>
                        <ul class="mb-0">
                            <li><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/know-your-constituency.php">Know your constituency</a> – find your AC</li>
                            <li><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/how-to-vote.php">How to vote</a> – EPIC and ID checklist</li>
                            <li><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/dates.php">Key dates</a> – poll 23 April, counting 4 May</li>
                            <li><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/find-blo.php">Find your BLO</a> – Sholinganallur AC</li>
                            <li><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/faq.php">FAQ</a> – common questions</li>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <p class="mt-3"><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/quiz.php" class="btn btn-outline-secondary btn-sm">Retake quiz</a> · <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/">Elections 2026</a></p>
                    <?php endif; ?>
                    <p class="mt-4"><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/">Back to Elections 2026</a></p>
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
