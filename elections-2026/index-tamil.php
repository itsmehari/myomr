<?php
/**
 * Elections 2026 – Tamil hub (தமிழ்).
 * Tamil version of the main hub; hreflang en/ta for bilingual reach.
 */
require_once __DIR__ . '/includes/bootstrap.php';

$page_nav = 'main';
$page_title = 'தேர்தல் 2026 – ஓஎம்ஆர் மற்றும் சென்னை தெற்கு வழிகாட்டி | MyOMR';
$page_description = 'தமிழ்நாடு சட்டமன்ற தேர்தல் 2026: வாக்களிப்பு 23 ஏப்ரல், எண்ணிக்கை 4 மே. உங்கள் தொகுதி, BLO, வேட்பாளர்கள் – சோழிங்கநல்லூர், வேளச்சேரி, திருப்போரூர்.';
$page_keywords = 'தேர்தல் 2026 ஓஎம்ஆர், தமிழ்நாடு தேர்தல் 2026, சென்னை தெற்கு, சோழிங்கநல்லூர், வேளச்சேரி, திருப்போரூர்';
$canonical_url = 'https://myomr.in/elections-2026/index-tamil.php';
$og_type = 'website';
$og_title = $page_title;
$og_description = $page_description;
$og_url = $canonical_url;
$twitter_title = $page_title;
$twitter_description = $page_description;
$english_hub_url = 'https://myomr.in/elections-2026/';
$breadcrumbs = [
    ['https://myomr.in/', 'முகப்பு'],
    [$canonical_url, 'தேர்தல் 2026'],
];
?>
<!DOCTYPE html>
<html lang="ta">
<head>
    <?php require_once ROOT_PATH . '/components/meta.php'; ?>
    <link rel="alternate" hreflang="en" href="<?php echo htmlspecialchars($english_hub_url); ?>">
    <link rel="alternate" hreflang="x-default" href="<?php echo htmlspecialchars($english_hub_url); ?>">
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
                    <li class="breadcrumb-item"><a href="https://myomr.in/">முகப்பு</a></li>
                    <li class="breadcrumb-item active" aria-current="page">தேர்தல் 2026</li>
                </ol>
            </nav>
            <h1 class="h2 mb-2">தேர்தல் 2026 – ஓஎம்ஆர் மற்றும் அருகிலுள்ள பகுதிகள்</h1>
            <p class="lead text-muted mb-0">பழைய மகாபலிபுரம் சாலை மற்றும் சென்னை தெற்கு தொகுதிகளுக்கான தமிழ்நாடு சட்டமன்ற தேர்தல் வழிகாட்டி.</p>
            <?php
            $poll_date = new DateTime('2026-04-23');
            $today = new DateTime('today');
            $days_to_poll = $today->diff($poll_date)->days;
            $is_past_poll = ($today > $poll_date);
            ?>
            <?php if (!$is_past_poll): ?>
            <p class="mb-0 mt-2"><strong>வாக்களிப்பு வரை <?php echo $days_to_poll; ?> நாட்கள்</strong> (23 ஏப்ரல் 2026)</p>
            <?php else: ?>
            <p class="mb-0 mt-2 text-muted">வாக்களிப்பு 23 ஏப்ரல் 2026. <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/results-2026.php">முடிவுகள்</a></p>
            <?php endif; ?>
            <p class="mt-2 small"><a href="<?php echo htmlspecialchars($english_hub_url); ?>" hreflang="en">Read in English</a></p>
        </div>
    </header>

    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h2 class="h5 mb-3">முக்கிய தேதிகள்</h2>
                            <ul class="mb-0">
                                <li><strong>வாக்களிப்பு:</strong> 23 ஏப்ரல் 2026</li>
                                <li><strong>எண்ணிக்கை:</strong> 4 மே 2026</li>
                                <li>அறிவிப்பு 30 மார்ச் · பரிந்துரைகள் 6 ஏப்ரல் வரை · திரும்பப்பெற 9 ஏப்ரல் வரை</li>
                            </ul>
                            <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/dates.php" class="btn btn-outline-primary btn-sm mt-3">முழு நேரக்கோடு</a>
                        </div>
                    </div>

                    <h2 class="h5 mb-3">இந்த வழிகாட்டியில் என்ன உள்ளது</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 card-title">உங்கள் தொகுதியை அறிக</h3>
                                    <p class="card-text small">பகுதி அல்லது பெயரால் எந்த சட்டமன்றத் தொகுதியில் (AC) நீங்கள் வருகிறீர்கள் என்பதைக் கண்டறியுங்கள்.</p>
                                    <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/know-your-constituency.php" class="card-link">என் தொகுதியைக் கண்டுபிடி</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 card-title">BLO கண்டுபிடி</h3>
                                    <p class="card-text small">வாக்காளர் பட்டியல் மற்றும் வாக்குப்புள்ளி விவரங்களுக்கான தொகுதி நிலை அலுவலர் (சோழிங்கநல்லூர் AC).</p>
                                    <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/find-blo.php" class="card-link">BLO தேடு</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 card-title">தொகுதிகள்</h3>
                                    <p class="card-text small">சோழிங்கநல்லூர், வேளச்சேரி, திருப்போரூர் – எம்எல்ஏக்கள், முந்தைய முடிவுகள், 2026 வேட்பாளர்கள்.</p>
                                    <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/constituency/sholinganallur.php" class="card-link">ஓஎம்ஆர் தொகுதிகள்</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 card-title">வேட்பாளர்கள் 2026</h3>
                                    <p class="card-text small">ஓஎம்ஆர் தொகுதிகளுக்கான அறிவிக்கப்பட்ட மற்றும் எதிர்பார்க்கப்படும் வேட்பாளர்கள்.</p>
                                    <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/candidates.php" class="card-link">வேட்பாளர்களைப் பார்</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 card-title">எப்படி வாக்களிப்பது</h3>
                                    <p class="card-text small">எபிக், அடையாளம், வாக்குப்புள்ளி நிலையம் மற்றும் வாக்காளர் பட்டியல்.</p>
                                    <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/how-to-vote.php" class="card-link">பட்டியலைப் பார்</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 card-title">அடிக்கடி கேட்கப்படும் கேள்விகள்</h3>
                                    <p class="card-text small">தேதிகள், அடையாளம், EVM, அஞ்சல் வாக்கு, ஓஎம்ஆர் தொகுதிகள் பற்றிய கேள்விகள்.</p>
                                    <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/faq.php" class="card-link">FAQ படி</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body p-4">
                            <h2 class="h5 mb-3">செய்திகள் மற்றும் புதுப்பிப்புகள்</h2>
                            <p class="small text-muted mb-2">மைஓஎம்ஆர் உள்ளூர் செய்திகளிலிருந்து தேர்தல் தொடர்பான கட்டுரைகள்.</p>
                            <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/news.php" class="btn btn-outline-secondary btn-sm">தேர்தல் செய்திகள்</a>
                            <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/announcements.php" class="btn btn-outline-secondary btn-sm ms-2">அறிவிப்புகள்</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card bg-primary text-white border-0">
                        <div class="card-body p-4">
                            <h2 class="h6 card-title">ஓஎம்ஆர் தேர்தல் புதுப்பிப்புகள்</h2>
                            <p class="small mb-3">தேதிகள், வேட்பாளர்கள் மற்றும் நினைவூட்டல்கள் உங்கள் இன்பாக்ஸில்.</p>
                            <a href="<?php echo ELECTIONS_2026_BASE_URL; ?>/newsletter.php" class="btn btn-light btn-sm">பதிவு செய்</a>
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
