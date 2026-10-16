<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__);
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';
require_once ROOT_PATH . '/jobs/includes/unique-page-views.php';

$ta_slug = 'எச்ஆர்-ரெக்ரூட்டர்-10-காலியிடம்-செம்பாக்கம்-சென்னை';
$ta_slug_encoded = rawurlencode($ta_slug);
$en_url = 'https://myomr.in/jobs/hr-recruiter-10-vacancies-sembakkam-chennai';
$ta_url = 'https://myomr.in/jobs/' . $ta_slug;
$canonical_url = $ta_url;
$date_posted = '2026-10-16';
$valid_through = '2027-01-14';
$wa = 'https://wa.me/919176797745';
$phone_display = '91767 97745';

$page_title = 'செம்பாக்கத்தில் எச்ஆர் ரெக்ரூட்டர், 10 காலியிடங்கள், VV Placement Services. பெண் விண்ணப்பதாரர்கள் விண்ணப்பிக்கலாம். புதியவர்களும் விண்ணப்பிக்கலாம். உடனடியாகப் பணியில் சேரக்கூடியவர்கள் தேவை. மாதம் அதிகபட்சம் ₹15,000. 91767 97745';
$page_description = 'எச்ஆர் ரெக்ரூட்டர், செம்பாக்கம், 10 காலியிடங்கள், VV Placement Services. பெண் விண்ணப்பதாரர்கள் விண்ணப்பிக்கலாம். புதியவர்களும் விண்ணப்பிக்கலாம். உடனடியாகப் பணியில் சேரக்கூடியவர்கள் தேவை. மாதம் அதிகபட்சம் ₹15,000. 91767 97745.';
$og_title = $page_title;
$og_description = $page_description;
$og_url = $canonical_url;
$og_type = 'website';
$twitter_title = $page_title;
$twitter_description = $page_description;

$view_count = myomr_unique_page_views('hr-recruiter-10-sembakkam-ta');

$faqs = [
    [
        'q' => 'யார் விண்ணப்பிக்கலாம்?',
        'a' => 'பெண் விண்ணப்பதாரர்கள் விண்ணப்பிக்கலாம். புதியவர்களும் விண்ணப்பிக்கலாம். உடனடியாகப் பணியில் சேரக்கூடியவர்கள் தேவை. இது பெண்களுக்கு மட்டும் என்று சொல்லவில்லை; ஆண்களைத் தடை செய்வதாகவும் இல்லை.',
    ],
    [
        'q' => 'எத்தனை இடங்கள் உள்ளன?',
        'a' => 'செம்பாக்கத்தில் எச்ஆர் ரெக்ரூட்டர் பணிக்கு 10 காலியிடங்கள் உள்ளன.',
    ],
    [
        'q' => 'ஊதியத்தில் சொல்லியிருப்பது என்ன?',
        'a' => 'மாதம் அதிகபட்சம் ₹15,000 என்று மட்டும் சொல்லப்பட்டுள்ளது. அதற்குக் கீழே ஒரு தொடக்கத் தொகை தரப்படவில்லை.',
    ],
    [
        'q' => 'யார் ஆட்களை எடுக்கிறார்கள், எங்கே?',
        'a' => 'VV Placement Services, சென்னை செம்பாக்கத்தில் எச்ஆர் ரெக்ரூட்டரைத் தேடுகிறது. தெரு முகவரி இந்த அறிவிப்பில் இல்லை.',
    ],
    [
        'q' => 'எப்படித் தொடர்பு கொள்வது?',
        'a' => '91767 97745 என்ற எண்ணுக்கு போன் அல்லது வாட்ஸ்அப் செய்யுங்கள். 14 ஜனவரி 2027 வரை இந்த அறிவிப்பு நிற்கும்.',
    ],
];

$job_ld = [
    '@context' => 'https://schema.org',
    '@type' => 'JobPosting',
    'title' => 'எச்ஆர் ரெக்ரூட்டர்',
    'description' => 'VV Placement Services, சென்னை செம்பாக்கத்தில் எச்ஆர் ரெக்ரூட்டர் பணிக்கு 10 காலியிடங்கள் வைத்துள்ளது. பெண் விண்ணப்பதாரர்கள் விண்ணப்பிக்கலாம். புதியவர்களும் விண்ணப்பிக்கலாம். உடனடியாகப் பணியில் சேரக்கூடியவர்கள் தேவை. மாதம் அதிகபட்சம் ₹15,000. 91767 97745 என்ற எண்ணில் போன் அல்லது வாட்ஸ்அப் மூலம் தொடர்பு கொள்ளுங்கள். தெரு முகவரி தரப்படவில்லை.',
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
            'addressLocality' => 'Sembakkam',
            'addressRegion' => 'Tamil Nadu',
            'addressCountry' => 'IN',
        ],
    ],
    'totalJobOpenings' => 10,
    'inLanguage' => 'ta-IN',
    'identifier' => [
        '@type' => 'PropertyValue',
        'name' => 'VV Placement Services',
        'value' => 'vv-placement-hr-recruiter-sembakkam-ta',
    ],
    'directApply' => false,
    'url' => $ta_url,
    'potentialAction' => [
        '@type' => 'ApplyAction',
        'name' => 'வாட்ஸ்அப்பில் தொடர்பு கொள்ள',
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
    <h1>செம்பாக்கத்தில் எச்ஆர் ரெக்ரூட்டர் — 10 காலியிடங்கள்</h1>
    <p>VV Placement Services, சென்னை செம்பாக்கத்தில் எச்ஆர் ரெக்ரூட்டர் பணிக்கு 10 காலியிடங்கள் அறிவித்துள்ளது. பெண் விண்ணப்பதாரர்கள் விண்ணப்பிக்கலாம். புதியவர்களும் விண்ணப்பிக்கலாம். உடனடியாகப் பணியில் சேரக்கூடியவர்கள் தேவை. மாதம் அதிகபட்சம் ₹15,000. தொடர்பு எண் <?php echo htmlspecialchars($phone_display, ENT_QUOTES, 'UTF-8'); ?>.</p>
    <ul class="vv-facts">
        <li>நிறுவனம்: VV Placement Services</li>
        <li>பணி: எச்ஆர் ரெக்ரூட்டர்</li>
        <li>எண்ணிக்கை: 10 காலியிடங்கள்</li>
        <li>பகுதி: செம்பாக்கம், சென்னை</li>
        <li>பெண் விண்ணப்பதாரர்கள் விண்ணப்பிக்கலாம். பெண்களுக்கு மட்டும் என்றோ, ஆண்கள் விண்ணப்பிக்கக் கூடாது என்றோ சொல்லப்படவில்லை.</li>
        <li>புதியவர்களும் விண்ணப்பிக்கலாம்</li>
        <li>உடனடியாகப் பணியில் சேரக்கூடியவர்கள் தேவை</li>
        <li>ஊதியம்: மாதம் அதிகபட்சம் ₹15,000</li>
        <li>விண்ணப்பம்: போன் அல்லது வாட்ஸ்அப் <?php echo htmlspecialchars($phone_display, ENT_QUOTES, 'UTF-8'); ?></li>
        <li>கடைசி நாள்: 14 ஜனவரி 2027</li>
    </ul>
    <p><a href="tel:+919176797745">+91 <?php echo htmlspecialchars($phone_display, ENT_QUOTES, 'UTF-8'); ?></a> என்ற எண்ணில் அழையுங்கள், அல்லது <a href="<?php echo htmlspecialchars($wa, ENT_QUOTES, 'UTF-8'); ?>" rel="noopener">வாட்ஸ்அப்</a> அனுப்புங்கள். இந்தப் பக்கம் விண்ணப்பப் படிவம் அல்ல.</p>
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
