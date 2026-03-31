<?php
/**
 * SEO landing page — MyOMR WhatsApp community (OMR, Chennai).
 */
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__;
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/core/community-links.php';
require_once ROOT_PATH . '/components/component-includes.php';

$canonical_url = 'https://myomr.in/join-omr-whatsapp-group-myomr.php';
$page_title = 'Join MyOMR WhatsApp Group — OMR Chennai Community & Local Updates';
$page_description = 'Join the official MyOMR WhatsApp community for Old Mahabalipuram Road: local news, jobs, events, neighbourhood help, and OMR corridor updates. Free to request — moderated for quality.';
$page_keywords = 'OMR WhatsApp group, Old Mahabalipuram Road community, Chennai OMR updates, Perungudi Sholinganallur community, IT corridor Chennai WhatsApp, MyOMR community';

$whatsapp_invite_tracked = myomr_whatsapp_invite_url_with_utm([
    'utm_source'   => 'myomr_site',
    'utm_medium'   => 'landing_page',
    'utm_campaign' => 'join_omr_whatsapp',
]);

$ga_content_group = 'Community & WhatsApp';
?>
<!DOCTYPE html>
<html lang="en-IN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:image" content="https://myomr.in/My-OMR-Logo.png">
    <meta property="og:locale" content="en_IN">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:image" content="https://myomr.in/My-OMR-Logo.png">

    <?php include ROOT_PATH . '/components/analytics.php'; ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/core.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <style>
        .wa-landing { font-family: 'Poppins', system-ui, sans-serif; }
        .wa-landing .hero-wa {
            background: linear-gradient(135deg, #075e54 0%, #128c7e 45%, #25d366 100%);
            color: #fff;
            padding: 3rem 0 3.5rem;
        }
        .wa-landing .hero-wa h1 { font-weight: 700; letter-spacing: -0.02em; }
        .wa-landing .btn-wa {
            background: #25d366;
            border: none;
            color: #075e54;
            font-weight: 600;
            padding: 0.75rem 1.75rem;
            border-radius: 2rem;
        }
        .wa-landing .btn-wa:hover { background: #20bd5a; color: #054d4a; }
        .wa-landing .btn-outline-light { border-width: 2px; }
        .wa-landing .use-card {
            border: 1px solid #e8ecef;
            border-radius: 0.75rem;
            padding: 1.25rem;
            height: 100%;
            background: #fafbfc;
        }
        .wa-landing .use-card h3 { font-size: 1.05rem; font-weight: 600; margin-bottom: 0.5rem; }
        .wa-landing section { padding: 2.5rem 0; }
        .wa-landing .section-muted { background: #f8f9fa; }
        .wa-landing .faq-item { border-bottom: 1px solid #e8ecef; padding: 1rem 0; }
        .wa-landing .faq-item:last-child { border-bottom: none; }
        .wa-landing .maxw { max-width: 1280px; }
    </style>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": <?php echo json_encode($page_title, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>,
        "description": <?php echo json_encode($page_description, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>,
        "url": <?php echo json_encode($canonical_url, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>,
        "inLanguage": "en-IN",
        "isPartOf": {
            "@type": "WebSite",
            "name": "MyOMR",
            "url": "https://myomr.in"
        }
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://myomr.in/"},
            {"@type": "ListItem", "position": 2, "name": "WhatsApp community", "item": <?php echo json_encode($canonical_url, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>}
        ]
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "Who can join the MyOMR WhatsApp community?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Anyone who lives, works, or studies along Old Mahabalipuram Road (OMR) in Chennai—or is actively moving to the corridor—can request to join. We focus on local relevance: residents, commuters, parents, job seekers, and small businesses serving OMR."
                }
            },
            {
                "@type": "Question",
                "name": "Is the MyOMR WhatsApp group free?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes. Joining is free. MyOMR runs the group as a community channel alongside our website; there is no fee to request access."
                }
            },
            {
                "@type": "Question",
                "name": "What topics are shared in the group?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Typical topics include local alerts and discussion, new job listings and career chatter (especially for the IT corridor), weekend events, recommendations (services, clinics, schools), housing and PG questions, lost-and-found, and civic or safety notes relevant to OMR neighbourhoods."
                }
            },
            {
                "@type": "Question",
                "name": "How is the group moderated?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Admins aim to keep the group useful and respectful: no hate speech, harassment, or illegal content; reduce spam and irrelevant forwards; and remove bad-faith members. Rules are enforced at admin discretion to protect the community."
                }
            },
            {
                "@type": "Question",
                "name": "How do I join on WhatsApp?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Tap the invite button on this page to open WhatsApp, then submit a join request. If the group uses admin approval, wait until an admin accepts you. WhatsApp is a third-party app; your use is subject to Meta/WhatsApp terms and your own privacy settings."
                }
            },
            {
                "@type": "Question",
                "name": "Will MyOMR read my personal WhatsApp messages?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "This page only links you to WhatsApp. MyOMR does not control WhatsApp’s app or encryption. Messages in the group are visible to other members per WhatsApp’s product rules. Read our website privacy policy for how myomr.in handles data on this site."
                }
            },
            {
                "@type": "Question",
                "name": "Is this the same as the MyOMR job portal or news site?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "The group complements myomr.in: you may see pointers to new jobs, articles, and events, but the website remains the place for full listings, search, and archives. Use the job portal and news sections for structured browsing."
                }
            },
            {
                "@type": "Question",
                "name": "What areas along OMR does the community cover?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Discussion often touches Perungudi, Kandhanchavadi, Thoraipakkam, Sholinganallur, Navalur, Kelambakkam, and other neighbourhoods along Old Mahabalipuram Road—plus connections to Tidel Park, Madhya Kailash, and the wider Chennai IT corridor when relevant."
                }
            }
        ]
    }
    </script>
</head>
<body class="wa-landing">
<?php omr_nav('main'); ?>

<main id="main-content">
    <section class="hero-wa" aria-labelledby="wa-hero-title">
        <div class="container maxw">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h1 id="wa-hero-title">Join the MyOMR WhatsApp community for OMR, Chennai</h1>
                    <p class="lead mb-4">A moderated space for people who live and work along Old Mahabalipuram Road: local pulse, jobs chatter, events, and neighbour-to-neighbour help—without losing the depth of our <a class="text-white fw-semibold" href="/">website</a> for news and listings.</p>
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <a class="btn btn-wa btn-lg omr-wa-cta"
                           href="<?php echo htmlspecialchars($whatsapp_invite_tracked, ENT_QUOTES, 'UTF-8'); ?>"
                           target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-whatsapp me-2" aria-hidden="true"></i> Request to join on WhatsApp
                        </a>
                        <a class="btn btn-outline-light btn-lg" href="/omr-local-job-listings/">Browse jobs on MyOMR</a>
                        <a class="btn btn-outline-light btn-lg" href="/omr-local-job-listings/candidate-profile-omr.php?utm_source=whatsapp_landing&utm_medium=hero&utm_campaign=job_seeker_profile">Résumé &amp; profile</a>
                    </div>
                    <p class="small mt-3 mb-0 opacity-75">Free to request · Admin approval may apply · Be kind and local-first</p>
                </div>
                <div class="col-lg-5 text-center">
                    <img src="/My-OMR-Logo.png" alt="MyOMR logo — OMR Chennai local community and news" class="img-fluid" style="max-height: 140px;" width="280" height="140" loading="eager">
                </div>
            </div>
        </div>
    </section>

    <section aria-labelledby="use-cases-title">
        <div class="container maxw">
            <h2 id="use-cases-title" class="h3 fw-bold mb-4">What you’ll use this group for</h2>
            <div class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <div class="use-card">
                        <h3><i class="fas fa-bolt text-warning me-2" aria-hidden="true"></i>Local pulse</h3>
                        <p class="small text-muted mb-0">Short alerts, crowdsourced notes, and “what’s happening on OMR” threads—useful when you want signal, not noise.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="use-card">
                        <h3><i class="fas fa-briefcase text-primary me-2" aria-hidden="true"></i>Jobs &amp; careers</h3>
                        <p class="small text-muted mb-0">New openings, referrals, and hiring chatter along the IT corridor. For full search and applications, use our <a href="/jobs-in-omr-chennai.php">OMR jobs hub</a>. Upload your CV and save a <a href="/omr-local-job-listings/candidate-profile-omr.php?utm_source=whatsapp_landing&utm_medium=use_case&utm_campaign=job_seeker_profile">free job seeker profile</a> on the website.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="use-card">
                        <h3><i class="fas fa-calendar-alt text-success me-2" aria-hidden="true"></i>Events &amp; weekend</h3>
                        <p class="small text-muted mb-0">Meetups, school or college events, and weekend ideas around Chennai’s OMR stretch.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="use-card">
                        <h3><i class="fas fa-hands-helping text-info me-2" aria-hidden="true"></i>Neighbour help</h3>
                        <p class="small text-muted mb-0">Recommendations (plumbers, clinics), lost &amp; found, and safety tips relevant to residents.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="use-card">
                        <h3><i class="fas fa-home text-secondary me-2" aria-hidden="true"></i>Housing &amp; area life</h3>
                        <p class="small text-muted mb-0">PG, hostel, and rent questions come up often—always verify details independently. See also <a href="/omr-hostels-pgs/">hostels &amp; PGs</a> and <a href="/omr-rent-lease/">rent &amp; lease</a>.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="use-card">
                        <h3><i class="fas fa-newspaper text-danger me-2" aria-hidden="true"></i>News pointers</h3>
                        <p class="small text-muted mb-0">Pointers to big stories; deep reads stay on <a href="/local-news/news-highlights-from-omr-road.php">News Highlights</a> and the rest of myomr.in.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-muted" aria-labelledby="areas-title">
        <div class="container maxw">
            <h2 id="areas-title" class="h3 fw-bold mb-3">Areas we talk about</h2>
            <p class="text-muted">The corridor from SRP Tools and Kandhanchavadi through Perungudi, Thoraipakkam, Sholinganallur, Navalur, and Kelambakkam—plus links to Tidel Park, Madhya Kailash, and the wider Chennai tech belt when it matters to OMR residents.</p>
        </div>
    </section>

    <section aria-labelledby="how-title">
        <div class="container maxw">
            <h2 id="how-title" class="h3 fw-bold mb-3">How to join</h2>
            <ol class="ps-3">
                <li class="mb-2">Tap <strong>Request to join on WhatsApp</strong> below.</li>
                <li class="mb-2">Submit the join request in WhatsApp (approval may be required).</li>
                <li class="mb-2">Read the group description and pinned notes when you get in.</li>
            </ol>
            <a class="btn btn-success btn-lg omr-wa-cta mt-2"
               href="<?php echo htmlspecialchars($whatsapp_invite_tracked, ENT_QUOTES, 'UTF-8'); ?>"
               target="_blank" rel="noopener noreferrer">
                <i class="fab fa-whatsapp me-2" aria-hidden="true"></i> Open WhatsApp invite
            </a>
        </div>
    </section>

    <section class="section-muted" aria-labelledby="rules-title">
        <div class="container maxw">
            <h2 id="rules-title" class="h3 fw-bold mb-3">Rules &amp; etiquette</h2>
            <ul class="mb-0">
                <li>Be respectful—no harassment, hate, or illegal content.</li>
                <li>Keep it relevant to OMR / Chennai local life; avoid random forwards and spam.</li>
                <li>No guaranteed job placement; verify employers and offers yourself.</li>
                <li>Admins may remove posts or members to keep the group healthy.</li>
            </ul>
        </div>
    </section>

    <section id="faq" aria-labelledby="faq-title">
        <div class="container maxw">
            <h2 id="faq-title" class="h3 fw-bold mb-4">Frequently asked questions</h2>
            <div class="faq-item">
                <h3 class="h6 fw-semibold">Who can join the MyOMR WhatsApp community?</h3>
                <p class="small text-muted mb-0">Anyone who lives, works, or studies along Old Mahabalipuram Road (OMR) in Chennai—or is actively moving to the corridor—can request to join. We focus on local relevance: residents, commuters, parents, job seekers, and small businesses serving OMR.</p>
            </div>
            <div class="faq-item">
                <h3 class="h6 fw-semibold">Is the MyOMR WhatsApp group free?</h3>
                <p class="small text-muted mb-0">Yes. Joining is free. MyOMR runs the group as a community channel alongside our website; there is no fee to request access.</p>
            </div>
            <div class="faq-item">
                <h3 class="h6 fw-semibold">What topics are shared in the group?</h3>
                <p class="small text-muted mb-0">Typical topics include local alerts and discussion, new job listings and career chatter (especially for the IT corridor), weekend events, recommendations (services, clinics, schools), housing and PG questions, lost-and-found, and civic or safety notes relevant to OMR neighbourhoods.</p>
            </div>
            <div class="faq-item">
                <h3 class="h6 fw-semibold">How is the group moderated?</h3>
                <p class="small text-muted mb-0">Admins aim to keep the group useful and respectful: no hate speech, harassment, or illegal content; reduce spam and irrelevant forwards; and remove bad-faith members. Rules are enforced at admin discretion to protect the community.</p>
            </div>
            <div class="faq-item">
                <h3 class="h6 fw-semibold">How do I join on WhatsApp?</h3>
                <p class="small text-muted mb-0">Tap the invite button on this page to open WhatsApp, then submit a join request. If the group uses admin approval, wait until an admin accepts you. WhatsApp is a third-party app; your use is subject to Meta/WhatsApp terms and your own privacy settings.</p>
            </div>
            <div class="faq-item">
                <h3 class="h6 fw-semibold">Will MyOMR read my personal WhatsApp messages?</h3>
                <p class="small text-muted mb-0">This page only links you to WhatsApp. MyOMR does not control WhatsApp’s app or encryption. Messages in the group are visible to other members per WhatsApp’s product rules. Read our <a href="/website-privacy-policy-of-my-omr.php">website privacy policy</a> for how myomr.in handles data on this site.</p>
            </div>
            <div class="faq-item">
                <h3 class="h6 fw-semibold">Is this the same as the MyOMR job portal or news site?</h3>
                <p class="small text-muted mb-0">The group complements myomr.in: you may see pointers to new jobs, articles, and events, but the website remains the place for full listings, search, and archives. Use the <a href="/omr-local-job-listings/">job portal</a> and <a href="/local-news/">news</a> sections for structured browsing.</p>
            </div>
            <div class="faq-item">
                <h3 class="h6 fw-semibold">What areas along OMR does the community cover?</h3>
                <p class="small text-muted mb-0">Discussion often touches Perungudi, Kandhanchavadi, Thoraipakkam, Sholinganallur, Navalur, Kelambakkam, and other neighbourhoods along Old Mahabalipuram Road—plus connections to Tidel Park, Madhya Kailash, and the wider Chennai IT corridor when relevant.</p>
            </div>
        </div>
    </section>

    <section class="section-muted" aria-labelledby="more-title">
        <div class="container maxw">
            <h2 id="more-title" class="h3 fw-bold mb-3">More from MyOMR</h2>
            <p class="text-muted mb-3">Use the site for structured search and archives; use WhatsApp for timely, local conversation.</p>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-primary" href="/omr-local-job-listings/">Job listings</a>
                <a class="btn btn-outline-primary" href="/omr-local-events/">Events</a>
                <a class="btn btn-outline-primary" href="/local-news/news-highlights-from-omr-road.php">News highlights</a>
                <a class="btn btn-outline-primary" href="/omr-listings/index.php">Explore places</a>
                <a class="btn btn-outline-primary" href="/contact-my-omr-team.php">Contact</a>
            </div>
        </div>
    </section>
</main>

<?php omr_footer(); ?>

<script>
(function () {
  function trackWaCta(el) {
    if (typeof gtag !== 'function') return;
    gtag('event', 'whatsapp_community_invite_click', {
      link_url: el.getAttribute('href') || '',
      page_location: window.location.href,
      page_path: window.location.pathname
    });
  }
  document.querySelectorAll('.omr-wa-cta').forEach(function (a) {
    a.addEventListener('click', function () { trackWaCta(a); });
  });
})();
</script>
</body>
</html>
