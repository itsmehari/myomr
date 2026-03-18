<?php
/**
 * How to vote – EPIC, ID, polling station, voter checklist.
 */
require_once __DIR__ . '/includes/bootstrap.php';

$page_nav = 'main';
$page_title = 'How to Vote – Election 2026 Voter Checklist | MyOMR';
$page_description = 'Voter checklist for Tamil Nadu election 2026: EPIC card, ID, polling station, dos and don\'ts for OMR voters.';
$page_keywords = 'how to vote 2026, EPIC card, voter ID, polling station OMR, election checklist';
$canonical_url = 'https://myomr.in/elections-2026/how-to-vote.php';
$og_type = 'website';
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/elections-2026/', 'Elections 2026'],
    [$canonical_url, 'How to Vote'],
];
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
                    <li class="breadcrumb-item active" aria-current="page">How to Vote</li>
                </ol>
            </nav>
            <h1 class="h2 mb-2">How to vote</h1>
            <p class="text-muted mb-0">Voter checklist for poll day – 23 April 2026.</p>
        </div>
    </header>

    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="h5 mb-3">Before you go</h2>
                    <ul>
                        <li>Check your name in the electoral roll. <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/find-blo.php">Find your BLO</a> (Sholinganallur AC) for roll and polling station.</li>
                        <li>Carry your <strong>EPIC (Voter ID)</strong> or any of the approved photo IDs (passport, driving licence, PAN, etc.) as per ECI guidelines.</li>
                        <li>Know your <strong>polling station</strong> and part number. Your BLO or the voter slip can help.</li>
                    </ul>

                    <h2 class="h5 mb-3 mt-4">On poll day (23 April 2026)</h2>
                    <ul>
                        <li>Polling hours: typically 7 AM to 6 PM (confirm with ECI/SEC).</li>
                        <li>Carry original ID; photocopies may not be accepted.</li>
                        <li>Do not carry mobile phones or cameras inside the polling booth where prohibited.</li>
                    </ul>

                    <h2 class="h5 mb-3 mt-4">Dos and don&apos;ts</h2>
                    <ul>
                        <li>Do verify your vote on the VVPAT slip after pressing the button.</li>
                        <li>Do not reveal whom you voted for; it is your right to secrecy.</li>
                        <li>Do not take a selfie or photograph inside the booth.</li>
                    </ul>

                    <p><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/faq.php">FAQ</a> · <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/">Elections 2026</a></p>
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
