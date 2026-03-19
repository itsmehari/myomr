<?php
/**
 * Key dates – Tamil Nadu Assembly election 2026 timeline.
 */
require_once __DIR__ . '/includes/bootstrap.php';

$page_nav = 'main';
$page_title = 'Tamil Nadu Election 2026 – Key Dates & Timeline | MyOMR';
$page_description = 'Tamil Nadu Assembly election 2026: notification 30 March, nominations by 6 April, poll 23 April, counting 4 May. Full timeline for OMR voters.';
$page_keywords = 'Tamil Nadu election 2026 dates, poll date April 23, counting May 4, OMR election timeline';
$canonical_url = 'https://myomr.in/elections-2026/dates.php';
$og_type = 'website';
$og_title = $page_title;
$og_description = $page_description;
$og_url = $canonical_url;
$twitter_title = $page_title;
$twitter_description = $page_description;
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/elections-2026/', 'Elections 2026'],
    [$canonical_url, 'Key Dates'],
];

$dates = [
    ['label' => 'Issue of notification', 'date' => '30 March 2026'],
    ['label' => 'Last date for filing nominations', 'date' => '6 April 2026'],
    ['label' => 'Scrutiny of nominations', 'date' => '7 April 2026'],
    ['label' => 'Last date for withdrawal of candidature', 'date' => '9 April 2026'],
    ['label' => 'Date of poll', 'date' => '23 April 2026'],
    ['label' => 'Counting', 'date' => '4 May 2026'],
    ['label' => 'Term of current Assembly ends', 'date' => '10 May 2026'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once ROOT_PATH . '/components/meta.php'; ?>
    <?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
    <link rel="stylesheet" href="/assets/css/footer.css">
    <script type="application/ld+json">
    <?php
    $dates_schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Event',
                'name' => 'Tamil Nadu Assembly Election 2026 – Poll',
                'description' => 'Date of poll for all 234 assembly constituencies. OMR: Sholinganallur, Velachery, Thiruporur.',
                'startDate' => '2026-04-23T07:00:00+05:30',
                'endDate' => '2026-04-23T18:00:00+05:30',
                'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
                'eventStatus' => 'https://schema.org/EventScheduled',
                'location' => ['@type' => 'Place', 'name' => 'Tamil Nadu'],
                'url' => 'https://myomr.in/elections-2026/',
            ],
            [
                '@type' => 'Event',
                'name' => 'Tamil Nadu Assembly Election 2026 – Counting',
                'description' => 'Counting of votes and declaration of results.',
                'startDate' => '2026-05-04T08:00:00+05:30',
                'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
                'eventStatus' => 'https://schema.org/EventScheduled',
                'location' => ['@type' => 'Place', 'name' => 'Tamil Nadu'],
                'url' => 'https://myomr.in/elections-2026/results-2026.php',
            ],
        ],
    ];
    echo json_encode($dates_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    ?>
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
                    <li class="breadcrumb-item active" aria-current="page">Key Dates</li>
                </ol>
            </nav>
            <h1 class="h2 mb-2">Key dates</h1>
            <p class="text-muted mb-0">Tamil Nadu Assembly election 2026 – timeline for OMR and all constituencies.</p>
        </div>
    </header>

    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($dates as $d): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><?php echo htmlspecialchars($d['label']); ?></span>
                                <strong><?php echo htmlspecialchars($d['date']); ?></strong>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <p class="mt-4 small text-muted">Model Code of Conduct is in effect from the date of announcement (15 March 2026).</p>
                    <p class="mt-3"><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/dates-2026.ics.php" class="btn btn-outline-primary btn-sm">Add to calendar</a> (downloads poll day and counting date for Outlook/Google Calendar.)</p>
                    <p><a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/">Back to Elections 2026</a></p>
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
