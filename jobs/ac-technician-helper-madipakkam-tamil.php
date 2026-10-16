<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__);
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';
require_once ROOT_PATH . '/jobs/includes/unique-page-views.php';

$en_url = 'https://myomr.in/jobs/ac-technician-helper-madipakkam';
$ta_url = 'https://myomr.in/jobs/ac-technician-helper-madipakkam-tamil';
$canonical_url = $ta_url;
$date_posted = '2026-10-16';
$valid_through = '2027-01-14';
$wa = 'https://wa.me/919176797745';
$phone_display = '91767 97745';

$page_title = 'மடிப்பாக்கத்தில் ஏசி டெக்னீஷன் மற்றும் ஹெல்பர் வேலை';
$page_description = 'மடிப்பாக்கத்தில் ஏசி டெக்னீஷன், ஹெல்பர் வேலை. மாதம் ₹15,000–₹30,000. அறை மற்றும் உணவு உண்டு. அழைப்பு 91767 97745.';
$og_title = $page_title;
$og_description = $page_description;
$og_url = $canonical_url;
$og_type = 'website';
$twitter_title = $page_title;
$twitter_description = $page_description;

$view_count = myomr_unique_page_views('ac-technician-helper-madipakkam-ta');

$faqs = [
    [
        'q' => 'மாத ஊதியம் எவ்வளவு?',
        'a' => 'மொத்தம் 30 காலி இடங்களுக்கும் மாத வரம்பு ₹15,000 முதல் ₹30,000 வரை. எந்தப் பொறுப்புக்கு எந்த முனை என்று பிரித்துச் சொல்லப்படவில்லை.',
    ],
    [
        'q' => 'தங்குமிடம் கிடைக்குமா?',
        'a' => 'கிடைக்கும். முழுநேர, இடத்திலேயே செய்யும் இந்த வேலையுடன் அறையும் சாப்பாடும் சேர்க்கப்பட்டுள்ளன.',
    ],
    [
        'q' => 'விண்ணப்பம் எப்படி அனுப்புவது?',
        'a' => '91767 97745 என்ற எண்ணுக்கு போன் செய்யுங்கள் அல்லது வாட்ஸ்அப் அனுப்புங்கள். 14 ஜனவரி 2027 வரை வாய்ப்பு திறந்திருக்கும்.',
    ],
    [
        'q' => 'யார் ஆட்களை எடுக்கிறார்கள், எந்தப் பகுதியில்?',
        'a' => 'VV Placement Services, சென்னை மடிப்பாக்கத்தில் ஆட்களைத் தேடுகிறது. தெரு முகவரி இந்த அறிவிப்பில் இல்லை.',
    ],
];

$job_ld = [
    '@context' => 'https://schema.org',
    '@type' => 'JobPosting',
    'title' => 'ஏசி டெக்னீஷன் மற்றும் ஹெல்பர்',
    'description' => 'VV Placement Services, சென்னை மடிப்பாக்கத்தில் ஏசி டெக்னீஷன் மற்றும் ஹெல்பர் பணிகளுக்கு முழுநேர ஆன்சைட்டாக மொத்தம் 30 காலி இடங்களை நிரப்புகிறது. மாத ஊதியம் ₹15,000–₹30,000. அறையும் உணவும் உண்டு. 91767 97745 என்ற எண்ணில் போன் அல்லது வாட்ஸ்அப் மூலம் தொடர்பு கொள்ளுங்கள். தெரு முகவரி தரப்படவில்லை.',
    'datePosted' => $date_posted,
    'validThrough' => $valid_through,
    'hiringOrganization' => [
        '@type' => 'Organization',
        'name' => 'VV Placement Services',
    ],
    'jobLocation' => [
        '@type' => 'Place',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Madipakkam',
            'addressRegion' => 'Tamil Nadu',
            'addressCountry' => 'IN',
        ],
    ],
    'employmentType' => 'FULL_TIME',
    'totalJobOpenings' => 30,
    'baseSalary' => [
        '@type' => 'MonetaryAmount',
        'currency' => 'INR',
        'value' => [
            '@type' => 'QuantitativeValue',
            'minValue' => 15000,
            'maxValue' => 30000,
            'unitText' => 'MONTH',
        ],
    ],
    'jobBenefits' => 'அறை மற்றும் உணவு',
    'inLanguage' => 'ta-IN',
    'identifier' => [
        '@type' => 'PropertyValue',
        'name' => 'VV Placement Services',
        'value' => 'vv-placement-ac-technician-helper-madipakkam-ta',
    ],
    'directApply' => false,
    'url' => $ta_url,
    'potentialAction' => [
        '@type' => 'ApplyAction',
        'name' => 'வாட்ஸ்அப்பில் விண்ணப்பிக்க',
        'target' => $wa,
    ],
];

$faq_ld = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'inLanguage' => 'ta-IN',
    'mainEntity' => [],
];
foreach ($faqs as $item) {
    $faq_ld['mainEntity'][] = [
        '@type' => 'Question',
        'name' => $item['q'],
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => $item['a'],
        ],
    ];
}

$json_flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
?>
<!DOCTYPE html>
<html lang="ta">
<head>
<?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
<?php require_once ROOT_PATH . '/components/meta.php'; ?>
<link rel="alternate" hreflang="en" href="<?php echo htmlspecialchars($en_url, ENT_QUOTES, 'UTF-8'); ?>">
<link rel="alternate" hreflang="ta" href="<?php echo htmlspecialchars($ta_url, ENT_QUOTES, 'UTF-8'); ?>">
<link rel="alternate" hreflang="x-default" href="<?php echo htmlspecialchars($en_url, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:locale" content="ta_IN">
<meta property="og:locale:alternate" content="en_IN">
<script type="application/ld+json"><?php echo json_encode($job_ld, $json_flags); ?></script>
<script type="application/ld+json"><?php echo json_encode($faq_ld, $json_flags); ?></script>
<style>
.vv-job { max-width: 760px; margin: 2rem auto 3rem; padding: 0 1rem; font-family: Poppins, sans-serif; color: #1c2430; }
.vv-job h1 { font-size: 1.75rem; line-height: 1.3; margin-bottom: 0.75rem; }
.vv-lang { margin: 0 0 1.25rem; }
.vv-lang a { font-weight: 600; }
.vv-job p { line-height: 1.65; }
.vv-facts { padding-left: 1.1rem; }
.vv-facts li { margin: 0.35rem 0; }
.vv-faq details { border-top: 1px solid #e4e7ec; padding: 0.75rem 0; }
.vv-faq summary { cursor: pointer; font-weight: 600; }
.vv-views { margin-top: 1.5rem; color: #445; font-size: 0.95rem; }
</style>
</head>
<body>
<?php omr_nav(); ?>
<main class="vv-job">
    <p class="vv-lang"><a href="<?php echo htmlspecialchars($en_url, ENT_QUOTES, 'UTF-8'); ?>" hreflang="en" lang="en">Read in English</a></p>
    <h1>மடிப்பாக்கத்தில் ஏசி டெக்னீஷன், ஹெல்பர் காலி இடங்கள்</h1>
    <p>VV Placement Services, சென்னை மடிப்பாக்கத்தில் ஏசி டெக்னீஷன் மற்றும் ஹெல்பர் பணிகளுக்கு மொத்தம் 30 பேரைத் தேடுகிறது. மாத ஊதியம் ₹15,000 முதல் ₹30,000 வரை; அறையும் உணவும் சேரும். தொடர்பு எண் <?php echo htmlspecialchars($phone_display, ENT_QUOTES, 'UTF-8'); ?>.</p>
    <ul class="vv-facts">
        <li>நிறுவனம்: VV Placement Services</li>
        <li>பணியிடங்கள்: ஏசி டெக்னீஷன் மற்றும் ஹெல்பர் (மொத்தம் 30 காலி இடங்கள்)</li>
        <li>பகுதி: மடிப்பாக்கம், சென்னை</li>
        <li>ஊதியம்: மாதம் ₹15,000–₹30,000</li>
        <li>தங்கும் வசதி: அறை மற்றும் உணவு உண்டு</li>
        <li>வகை: முழுநேரம், ஆன்சைட்</li>
        <li>விண்ணப்பம்: போன் அல்லது வாட்ஸ்அப் <?php echo htmlspecialchars($phone_display, ENT_QUOTES, 'UTF-8'); ?></li>
        <li>கடைசி நாள்: 14 ஜனவரி 2027</li>
    </ul>
    <p><a href="tel:+919176797745">+91 <?php echo htmlspecialchars($phone_display, ENT_QUOTES, 'UTF-8'); ?></a> என்ற எண்ணில் அழையுங்கள், அல்லது <a href="<?php echo htmlspecialchars($wa, ENT_QUOTES, 'UTF-8'); ?>" rel="noopener">வாட்ஸ்அப்</a> அனுப்புங்கள். இந்தப் பக்கமே விண்ணப்பப் படிவம் அல்ல.</p>
    <section class="vv-faq" aria-labelledby="faq-heading">
        <h2 id="faq-heading">இந்த அறிவிப்பு பற்றிய கேள்விகள்</h2>
        <?php foreach ($faqs as $item): ?>
        <details>
            <summary><?php echo htmlspecialchars($item['q'], ENT_QUOTES, 'UTF-8'); ?></summary>
            <p><?php echo htmlspecialchars($item['a'], ENT_QUOTES, 'UTF-8'); ?></p>
        </details>
        <?php endforeach; ?>
    </section>
    <p class="vv-views">தனித்துவமான பக்கப் பார்வைகள்: <?php echo (int) $view_count; ?></p>
</main>
<?php omr_footer(); ?>
</body>
</html>
