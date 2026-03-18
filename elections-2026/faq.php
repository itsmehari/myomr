<?php
/**
 * FAQ – Election 2026: dates, ID, EVM, postal ballot, OMR constituencies. FAQPage schema for rich results.
 */
require_once __DIR__ . '/includes/bootstrap.php';

$page_nav = 'main';
$page_title = 'Election 2026 FAQ – Dates, ID, EVM, OMR Constituencies | MyOMR';
$page_description = 'Frequently asked questions: When is Tamil Nadu election 2026? Which ID to carry? Which constituencies cover OMR? Postal ballot, EVM and more.';
$page_keywords = 'election 2026 FAQ, TN election dates, voter ID, EVM, OMR constituencies';
$canonical_url = 'https://myomr.in/elections-2026/faq.php';
$og_type = 'website';
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/elections-2026/', 'Elections 2026'],
    [$canonical_url, 'FAQ'],
];

$faqs = [
    ['q' => 'When is the Tamil Nadu Assembly election 2026?', 'a' => 'Polling will be held on 23 April 2026 in a single phase. All 234 assembly constituencies will go to the polls on that day.'],
    ['q' => 'When will the results be declared?', 'a' => 'Counting of votes will take place on 4 May 2026. Results will be declared the same day.'],
    ['q' => 'Which assembly constituencies cover OMR (Old Mahabalipuram Road)?', 'a' => 'Chennai South includes Velachery (AC 26), which covers Perungudi, Taramani and Adambakkam, and Sholinganallur (AC 27), the main OMR IT corridor. Thiruporur in Chengalpattu district is also part of the OMR corridor.'],
    ['q' => 'What ID do I need to vote?', 'a' => 'Carry your EPIC (Voter ID) or any ECI-approved photo ID such as passport, driving licence, or PAN card. Original document is required.'],
    ['q' => 'How do I find my polling station?', 'a' => 'You can find your Block Level Officer (BLO) who can confirm your polling station and part number. MyOMR has a BLO search for Sholinganallur AC.'],
    ['q' => 'What is EVM and VVPAT?', 'a' => 'EVM (Electronic Voting Machine) is used to record votes. VVPAT (Voter Verifiable Paper Audit Trail) prints a slip so you can verify your vote was recorded correctly.'],
    ['q' => 'Can I use postal ballot?', 'a' => 'Postal ballot is available for eligible categories (e.g. senior citizens above 85, persons with disabilities, essential services, etc.) as per ECI rules. Check with your BLO or the election office.'],
    ['q' => 'How do I know which assembly constituency I am in?', 'a' => 'Use the Know Your Constituency tool on this site: enter your locality or area (e.g. Thoraipakkam, Perungudi) to see if you are in Sholinganallur, Velachery or Thiruporur. You can also check your EPIC card or the electoral roll.'],
    ['q' => 'Where can I report Model Code of Conduct violations?', 'a' => 'You can report MCC violations to the Election Commission via the toll-free number 1800-425-7012 or through the CVigil mobile app.'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once ROOT_PATH . '/components/meta.php'; ?>
    <?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
    <link rel="stylesheet" href="/assets/css/footer.css">
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        <?php
        $items = [];
        foreach ($faqs as $f) {
            $items[] = json_encode([
                '@type' => 'Question',
                'name' => $f['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']]
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        echo implode(",\n        ", $items);
        ?>
      ]
    }
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
                    <li class="breadcrumb-item active" aria-current="page">FAQ</li>
                </ol>
            </nav>
            <h1 class="h2 mb-2">FAQ</h1>
            <p class="text-muted mb-0">Frequently asked questions about the 2026 election for OMR voters.</p>
        </div>
    </header>

    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <?php foreach ($faqs as $i => $f): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button <?php echo $i === 0 ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?php echo $i; ?>" aria-expanded="<?php echo $i === 0 ? 'true' : 'false'; ?>" aria-controls="faq<?php echo $i; ?>">
                                        <?php echo htmlspecialchars($f['q']); ?>
                                    </button>
                                </h2>
                                <div id="faq<?php echo $i; ?>" class="accordion-collapse collapse <?php echo $i === 0 ? 'show' : ''; ?>" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body"><?php echo nl2br(htmlspecialchars($f['a'])); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
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
