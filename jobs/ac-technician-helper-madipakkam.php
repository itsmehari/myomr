<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__);
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';
require_once ROOT_PATH . '/jobs/includes/unique-page-views.php';

$en_url = 'https://myomr.in/jobs/ac-technician-helper-madipakkam';
$ta_url = 'https://myomr.in/jobs/ac-technician-helper-madipakkam-tamil';
$canonical_url = $en_url;
$date_posted = '2026-10-16';
$valid_through = '2027-01-14';
$wa = 'https://wa.me/919176797745';
$phone_display = '91767 97745';

$page_title = 'AC Technician and Helper Jobs in Madipakkam';
$page_description = 'AC technician and helper jobs in Madipakkam pay ₹15,000–₹30,000 a month, with room and food. Call 91767 97745.';
$og_title = $page_title;
$og_description = $page_description;
$og_url = $canonical_url;
$og_type = 'website';
$twitter_title = $page_title;
$twitter_description = $page_description;

$view_count = myomr_unique_page_views('ac-technician-helper-madipakkam-en');

$faqs = [
    [
        'q' => 'What monthly pay is offered?',
        'a' => 'All 30 vacancies share one monthly band of ₹15,000 to ₹30,000. The notice does not say which role sits at which end of that band.',
    ],
    [
        'q' => 'Does the job include a place to stay?',
        'a' => 'Yes. Room and food come with the full-time onsite work.',
    ],
    [
        'q' => 'How can someone apply?',
        'a' => 'Phone or WhatsApp 91767 97745. Applications stay open through 14 January 2027.',
    ],
    [
        'q' => 'Which organisation is hiring, and in which area?',
        'a' => 'VV Placement Services is hiring in Madipakkam, Chennai. No street address was supplied with this notice.',
    ],
];

$job_ld = [
    '@context' => 'https://schema.org',
    '@type' => 'JobPosting',
    'title' => 'AC Technician and Helper',
    'description' => 'VV Placement Services is hiring 30 full-time onsite vacancies in Madipakkam, Chennai, for AC technician and helper roles. Pay is ₹15,000–₹30,000 per month. Room and food are included. Apply by phone or WhatsApp at 91767 97745. No street address was given.',
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
    'jobBenefits' => 'Room and food',
    'inLanguage' => 'en-IN',
    'identifier' => [
        '@type' => 'PropertyValue',
        'name' => 'VV Placement Services',
        'value' => 'vv-placement-ac-technician-helper-madipakkam-en',
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
    <h1>AC technician and helper openings in Madipakkam</h1>
    <p>VV Placement Services is hiring 30 people in Madipakkam, Chennai, for AC technician and helper work. Monthly pay runs from ₹15,000 to ₹30,000, room and food are included, and the contact number is <?php echo htmlspecialchars($phone_display, ENT_QUOTES, 'UTF-8'); ?>.</p>
    <ul class="vv-facts">
        <li>Employer: VV Placement Services</li>
        <li>Roles: AC technician and helper (30 vacancies in total)</li>
        <li>Area: Madipakkam, Chennai</li>
        <li>Pay: ₹15,000–₹30,000 per month</li>
        <li>Stay: room and food included</li>
        <li>Type: full time, onsite</li>
        <li>Apply: call or WhatsApp <?php echo htmlspecialchars($phone_display, ENT_QUOTES, 'UTF-8'); ?></li>
        <li>Apply by: 14 January 2027</li>
    </ul>
    <p>Reach the employer on <a href="tel:+919176797745">+91 <?php echo htmlspecialchars($phone_display, ENT_QUOTES, 'UTF-8'); ?></a> or <a href="<?php echo htmlspecialchars($wa, ENT_QUOTES, 'UTF-8'); ?>" rel="noopener">WhatsApp</a>. This page is not an application form.</p>
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
