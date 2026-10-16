<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__);
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';
require_once ROOT_PATH . '/jobs/includes/unique-page-views.php';

$en_url = 'https://myomr.in/jobs/hr-recruiter-10-vacancies-sembakkam-chennai';
$ta_url = 'https://myomr.in/jobs/எச்ஆர்-ரெக்ரூட்டர்-10-காலியிடம்-செம்பாக்கம்-சென்னை';
$canonical_url = $en_url;
$date_posted = '2026-10-16';
$valid_through = '2027-01-14';
$wa = 'https://wa.me/919176797745';
$phone_display = '91767 97745';

$page_title = 'HR recruiter, 10 vacancies in Sembakkam, VV Placement Services. Female candidates and freshers can apply. Immediate joining. Up to ₹15,000. 91767 97745';
$page_description = 'HR recruiter: 10 vacancies in Sembakkam, VV Placement Services. Female candidates and freshers can apply; immediate joining. Up to ₹15,000. 91767 97745.';
$og_title = $page_title;
$og_description = $page_description;
$og_url = $canonical_url;
$og_type = 'website';
$twitter_title = $page_title;
$twitter_description = $page_description;

$view_count = myomr_unique_page_views('hr-recruiter-10-sembakkam-en');

$faqs = [
    [
        'q' => 'Who is invited to apply?',
        'a' => 'Female candidates can apply, and freshers can apply. The notice also asks for people who can join immediately. It does not say the seat is women only, and it does not say men are barred.',
    ],
    [
        'q' => 'How many vacancies are there?',
        'a' => 'VV Placement Services has 10 HR recruiter vacancies in Sembakkam, Chennai.',
    ],
    [
        'q' => 'What does the pay figure mean?',
        'a' => 'The only pay figure given is a ceiling: up to ₹15,000 a month. No minimum salary was stated.',
    ],
    [
        'q' => 'Who is hiring, and in which area?',
        'a' => 'VV Placement Services is hiring in Sembakkam, Chennai. A street address was not provided.',
    ],
    [
        'q' => 'How do I apply?',
        'a' => 'Call or WhatsApp 91767 97745. The notice stays open through 14 January 2027.',
    ],
];

$job_ld = [
    '@context' => 'https://schema.org',
    '@type' => 'JobPosting',
    'title' => 'HR recruiter',
    'description' => 'VV Placement Services is hiring 10 HR recruiters in Sembakkam, Chennai. Female candidates can apply. Freshers can apply. Immediate joiners are needed. Pay is up to ₹15,000 a month. Apply by phone or WhatsApp at 91767 97745. No street address was given.',
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
    'inLanguage' => 'en-IN',
    'identifier' => [
        '@type' => 'PropertyValue',
        'name' => 'VV Placement Services',
        'value' => 'vv-placement-hr-recruiter-sembakkam-en',
    ],
    'directApply' => false,
    'url' => $en_url,
    'potentialAction' => [
        '@type' => 'ApplyAction',
        'name' => 'Apply on WhatsApp',
        'target' => $wa,
    ],
];

$faq_ld = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'inLanguage' => 'en-IN',
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
<html lang="en">
<head>
<?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
<?php require_once ROOT_PATH . '/components/meta.php'; ?>
<link rel="alternate" hreflang="en" href="<?php echo htmlspecialchars($en_url, ENT_QUOTES, 'UTF-8'); ?>">
<link rel="alternate" hreflang="ta" href="<?php echo htmlspecialchars($ta_url, ENT_QUOTES, 'UTF-8'); ?>">
<link rel="alternate" hreflang="x-default" href="<?php echo htmlspecialchars($en_url, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:locale" content="en_IN">
<meta property="og:locale:alternate" content="ta_IN">
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
    <p class="vv-lang"><a href="<?php echo htmlspecialchars($ta_url, ENT_QUOTES, 'UTF-8'); ?>" hreflang="ta" lang="ta">தமிழில் படிக்க</a></p>
    <h1>HR recruiter — 10 vacancies in Sembakkam</h1>
    <p>VV Placement Services has 10 HR recruiter vacancies in Sembakkam, Chennai. Female candidates can apply, freshers can apply, and immediate joining is needed. Pay is up to ₹15,000 a month. Call or WhatsApp <?php echo htmlspecialchars($phone_display, ENT_QUOTES, 'UTF-8'); ?>.</p>
    <ul class="vv-facts">
        <li>Employer: VV Placement Services</li>
        <li>Role: HR recruiter</li>
        <li>Vacancies: 10</li>
        <li>Area: Sembakkam, Chennai</li>
        <li>Female candidates can apply. The notice does not say women only, and it does not bar men.</li>
        <li>Freshers can apply</li>
        <li>Immediate joiners needed</li>
        <li>Pay: up to ₹15,000 a month</li>
        <li>Apply: call or WhatsApp <?php echo htmlspecialchars($phone_display, ENT_QUOTES, 'UTF-8'); ?></li>
        <li>Apply by: 14 January 2027</li>
    </ul>
    <p>Use <a href="tel:+919176797745">+91 <?php echo htmlspecialchars($phone_display, ENT_QUOTES, 'UTF-8'); ?></a> or <a href="<?php echo htmlspecialchars($wa, ENT_QUOTES, 'UTF-8'); ?>" rel="noopener">WhatsApp</a>. This page does not take the application itself.</p>
    <section class="vv-faq" aria-labelledby="faq-heading">
        <h2 id="faq-heading">Questions about this notice</h2>
        <?php foreach ($faqs as $item): ?>
        <details>
            <summary><?php echo htmlspecialchars($item['q'], ENT_QUOTES, 'UTF-8'); ?></summary>
            <p><?php echo htmlspecialchars($item['a'], ENT_QUOTES, 'UTF-8'); ?></p>
        </details>
        <?php endforeach; ?>
    </section>
    <p class="vv-views">Unique page views: <?php echo (int) $view_count; ?></p>
</main>
<?php omr_footer(); ?>
</body>
</html>
