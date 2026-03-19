<?php
/**
 * Elections 2026 – Hub / landing page.
 * One place for OMR and vicinity: key dates, constituency finder, BLO, candidates, news.
 */
require_once __DIR__ . '/includes/bootstrap.php';

$page_nav = 'main';
$page_title = 'Elections 2026 – OMR & Chennai South Guide | MyOMR';
$page_description = 'Tamil Nadu Assembly election 2026: poll 23 April, counting 4 May. Find your constituency, BLO, candidates and key dates for Sholinganallur, Velachery, Thiruporur and OMR.';
$page_keywords = 'elections 2026 OMR, Tamil Nadu election 2026, Chennai South, Sholinganallur election, Velachery MLA, Thiruporur constituency, OMR polling, MyOMR';
$canonical_url = 'https://myomr.in/elections-2026/';
$og_type = 'website';
$og_title = $page_title;
$og_description = $page_description;
$og_url = $canonical_url;
$twitter_title = $page_title;
$twitter_description = $page_description;
$tamil_hub_url = 'https://myomr.in/elections-2026/index-tamil.php';
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    [$canonical_url, 'Elections 2026'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once ROOT_PATH . '/components/meta.php'; ?>
    <link rel="alternate" hreflang="ta" href="<?php echo htmlspecialchars($tamil_hub_url); ?>">
    <meta property="og:locale:alternate" content="ta_IN">
    <script type="application/ld+json">
    <?php
    $hub_schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebPage',
                '@id' => $canonical_url . '#webpage',
                'url' => $canonical_url,
                'name' => $page_title,
                'description' => $page_description,
                'isPartOf' => ['@id' => 'https://myomr.in/#website'],
                'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => 'https://myomr.in/My-OMR-Logo.png'],
            ],
            [
                '@type' => 'Event',
                'name' => 'Tamil Nadu Assembly Election 2026 – Poll',
                'description' => 'Voting for all 234 assembly constituencies including Sholinganallur, Velachery, Thiruporur (OMR).',
                'startDate' => '2026-04-23T07:00:00+05:30',
                'endDate' => '2026-04-23T18:00:00+05:30',
                'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
                'eventStatus' => 'https://schema.org/EventScheduled',
                'location' => ['@type' => 'Place', 'name' => 'Tamil Nadu', 'address' => ['@type' => 'PostalAddress', 'addressRegion' => 'Tamil Nadu']],
                'url' => $canonical_url,
            ],
            [
                '@type' => 'Event',
                'name' => 'Tamil Nadu Assembly Election 2026 – Counting',
                'description' => 'Counting of votes and declaration of results for Tamil Nadu Assembly election 2026.',
                'startDate' => '2026-05-04T08:00:00+05:30',
                'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
                'eventStatus' => 'https://schema.org/EventScheduled',
                'location' => ['@type' => 'Place', 'name' => 'Tamil Nadu'],
                'url' => $canonical_url,
            ],
        ],
    ];
    echo json_encode($hub_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    ?>
    </script>
    <?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
    <link rel="stylesheet" href="/assets/css/footer.css">
</head>
<body class="modern-page">
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav(); ?>

<main id="main-content">
    <header class="bg-light py-4 py-md-5 border-bottom">
        <div class="container">
            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="https://myomr.in/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Elections 2026</li>
                </ol>
            </nav>
            <h1 class="h2 mb-2">Elections 2026 – OMR &amp; Vicinity</h1>
            <p class="lead text-muted mb-0">Your guide to the Tamil Nadu Assembly election for Old Mahabalipuram Road and Chennai South constituencies.</p>
            <?php
            $poll_date = new DateTime('2026-04-23');
            $today = new DateTime('today');
            $days_to_poll = $today->diff($poll_date)->days;
            $is_past_poll = ($today > $poll_date);
            ?>
            <?php if (!$is_past_poll): ?>
            <p class="mb-0 mt-2"><strong><?php echo $days_to_poll; ?> days to poll</strong> (23 April 2026)</p>
            <?php else: ?>
            <p class="mb-0 mt-2 text-muted">Poll was on 23 April 2026. <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/results-2026.php">See results</a></p>
            <?php endif; ?>
            <p class="mt-2 small"><a href="<?php echo htmlspecialchars($tamil_hub_url); ?>" hreflang="ta">தமிழில்</a></p>
        </div>
    </header>

    <div class="container py-3"><?php omr_ad_slot('elections-top', '728x90'); ?></div>

    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-4">
                            <h2 class="h6 text-muted mb-2">Share this guide</h2>
                            <p class="small mb-2">Help neighbours find dates, BLO and constituencies.</p>
                            <a href="https://wa.me/?text=<?php echo rawurlencode('OMR election 2026 – dates, BLO, constituencies: ' . $canonical_url); ?>" class="btn btn-success btn-sm me-2" target="_blank" rel="noopener noreferrer" aria-label="Share on WhatsApp">WhatsApp</a>
                            <a href="https://twitter.com/intent/tweet?text=<?php echo rawurlencode('OMR election 2026 – dates, BLO, constituencies on MyOMR ' . $canonical_url); ?>" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer" aria-label="Share on Twitter">Twitter / X</a>
                        </div>
                    </div>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h2 class="h5 mb-3">Key dates</h2>
                            <ul class="mb-0">
                                <li><strong>Poll:</strong> 23 April 2026</li>
                                <li><strong>Counting:</strong> 4 May 2026</li>
                                <li>Notification: 30 March 2026 · Nominations by 6 April · Withdrawal by 9 April</li>
                            </ul>
                            <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/dates.php" class="btn btn-outline-primary btn-sm mt-3">Full timeline</a>
                        </div>
                    </div>

                    <h2 class="h5 mb-3">What&apos;s in this guide</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 card-title">Know your constituency</h3>
                                    <p class="card-text small">Find which assembly segment (AC) you belong to by locality or area.</p>
                                    <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/know-your-constituency.php" class="card-link">Find my AC</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 card-title">Find your BLO</h3>
                                    <p class="card-text small">Block Level Officer for electoral roll and polling station details (Sholinganallur AC).</p>
                                    <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/find-blo.php" class="card-link">Search BLO</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 card-title">Constituencies</h3>
                                    <p class="card-text small">Sholinganallur, Velachery, Thiruporur – MLAs, past results, 2026 candidates.</p>
                                    <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/constituency/sholinganallur.php" class="card-link">OMR ACs</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 card-title">Candidates 2026</h3>
                                    <p class="card-text small">Declared and likely candidates for OMR constituencies.</p>
                                    <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/candidates.php" class="card-link">View candidates</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 card-title">How to vote</h3>
                                    <p class="card-text small">EPIC, ID, polling station and voter checklist.</p>
                                    <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/how-to-vote.php" class="card-link">Checklist</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 card-title">FAQ</h3>
                                    <p class="card-text small">Common questions on dates, ID, EVM, postal ballot, OMR constituencies.</p>
                                    <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/faq.php" class="card-link">Read FAQ</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 card-title">Are you ready to vote?</h3>
                                    <p class="card-text small">Quick quiz: constituency, ID, poll day.</p>
                                    <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/quiz.php" class="card-link">Take the quiz</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php omr_ad_slot('elections-mid', '336x280'); ?>

                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body p-4">
                            <h2 class="h5 mb-3">News &amp; updates</h2>
                            <p class="small text-muted mb-2">Election-related articles from MyOMR local news.</p>
                            <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/news.php" class="btn btn-outline-secondary btn-sm">Election news</a>
                            <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/announcements.php" class="btn btn-outline-secondary btn-sm ms-2">Announcements</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-3">
                            <h2 class="h6 card-title">Results 2026</h2>
                            <p class="small mb-2">Winners for OMR constituencies after counting.</p>
                            <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/results-2026.php" class="btn btn-outline-secondary btn-sm">View results</a>
                        </div>
                    </div>
                    <div class="card bg-primary text-white border-0">
                        <div class="card-body p-4">
                            <h2 class="h6 card-title">Get OMR election updates</h2>
                            <p class="small mb-3">Dates, candidates and reminders in your inbox.</p>
                            <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/newsletter.php" class="btn btn-light btn-sm">Subscribe</a>
                        </div>
                    </div>
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
