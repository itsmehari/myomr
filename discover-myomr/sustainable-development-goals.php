<?php
ob_start();
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$page_nav = 'main';
$omr_css_bootstrap5 = true;
$omr_css_megamenu = false;

$page_title = 'UN Sustainable Development Goals | MyOMR Community Commitment | MyOMR.in';
$page_description = "Take action for sustainability in OMR. Make your pledge, find local initiatives in Perungudi, Karapakkam, Thoraipakkam, and connect with OMR businesses committed to SDGs.";
$page_keywords = 'UN SDG, Sustainable Development Goals, OMR community, sustainability, Chennai, MyOMR, community development';
$canonical_url = 'https://myomr.in/discover-myomr/sustainable-development-goals.php';
$og_title = 'UN Sustainable Development Goals | MyOMR Community Commitment';
$og_description = "MyOMR's commitment to UN Sustainable Development Goals. Learn how our OMR community platform supports sustainable development.";
$og_image = 'https://myomr.in/My-OMR-Logo.png';
$og_url = $canonical_url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once ROOT_PATH . '/components/meta.php'; ?>
<?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400&display=swap" rel="stylesheet">
    <link rel="icon" href="/My-OMR-Logo.png" type="image/jpeg">

    <!-- SDG Page Specific Styles -->
    <style>
        /* MyOMR Branding Colors */
        :root {
            --myomr-green: #14532d;
            --myomr-light-green: #22c55e;
            --myomr-orange: #F77F00;
            --myomr-text: #4c516D;
        }

        /* Hero Section */
        .sdg-hero {
            background: linear-gradient(135deg, var(--myomr-green) 0%, var(--myomr-light-green) 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }

        .sdg-hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .sdg-hero p {
            font-family: 'Josefin Sans', sans-serif;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        /* SDG Colors */
        .sdg-1 { background-color: #E5243B; }
        .sdg-2 { background-color: #DDA63A; }
        .sdg-3 { background-color: #4C9F38; }
        .sdg-4 { background-color: #C5192D; }
        .sdg-5 { background-color: #FF3A21; }
        .sdg-6 { background-color: #26BDE2; }
        .sdg-7 { background-color: #FCC30B; }
        .sdg-8 { background-color: #A21942; }
        .sdg-9 { background-color: #FD6925; }
        .sdg-10 { background-color: #DD1367; }
        .sdg-11 { background-color: #FD9D24; }
        .sdg-12 { background-color: #BF8B2E; }
        .sdg-13 { background-color: #3F7E44; }
        .sdg-14 { background-color: #0A97D9; }
        .sdg-15 { background-color: #56C02B; }
        .sdg-16 { background-color: #00689D; }
        .sdg-17 { background-color: #19486A; }

        /* SDG Cards */
        .sdg-card {
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .sdg-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .sdg-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            pointer-events: none;
        }

        .sdg-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .sdg-card p {
            font-family: 'Josefin Sans', sans-serif;
            position: relative;
            z-index: 1;
            margin-bottom: 1rem;
        }

        .sdg-number {
            font-size: 3rem;
            font-weight: bold;
            opacity: 0.3;
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 0;
        }

        /* MyOMR Alignment Section */
        .myomr-alignment {
            background: linear-gradient(90deg, #FDBB2D 0%, #3A1C71 100%);
            color: white;
            padding: 3rem 0;
        }

        .alignment-card {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        /* Interactive SDG Wheel */
        .sdg-wheel {
            text-align: center;
            margin: 3rem 0;
        }

        .sdg-wheel img {
            max-width: 100%;
            height: auto;
            border-radius: 50%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        /* Call to Action */
        .cta-section {
            background: var(--myomr-green);
            color: white;
            padding: 3rem 0;
            text-align: center;
        }

        .cta-button {
            background: var(--myomr-orange);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin: 1rem;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            background: #e66a00;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sdg-hero h1 {
                font-size: 2rem;
            }
            
            .sdg-card {
                padding: 1.5rem;
            }
            
            .sdg-number {
                font-size: 2rem;
            }
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin: 4rem 0 3rem 0;
        }

        .section-header h2 {
            font-family: 'Playfair Display', serif;
            color: var(--myomr-green);
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .section-header p {
            font-family: 'Josefin Sans', sans-serif;
            color: var(--myomr-text);
            font-size: 1.1rem;
        }
    </style>

    <!-- Canonical URL -->
    <link rel="canonical" href="https://myomr.in/discover-myomr/sustainable-development-goals.php">
    
    <!-- Comprehensive JSON-LD Structured Data for Rich Snippets -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "WebPage",
          "@id": "https://myomr.in/discover-myomr/sustainable-development-goals.php",
          "name": "UN Sustainable Development Goals | MyOMR Community Commitment",
          "description": "MyOMR's commitment to UN Sustainable Development Goals. Learn how our OMR community platform supports sustainable development and how you can participate.",
          "url": "https://myomr.in/discover-myomr/sustainable-development-goals.php",
          "inLanguage": "en-IN",
          "isPartOf": {
            "@type": "WebSite",
            "name": "MyOMR",
            "url": "https://myomr.in"
          },
          "primaryImageOfPage": {
            "@type": "ImageObject",
            "url": "https://myomr.in/My-OMR-Logo.png",
            "width": 1200,
            "height": 630
          },
          "datePublished": "2025-01-01T00:00:00+05:30",
          "dateModified": "2025-01-01T00:00:00+05:30",
          "breadcrumb": {
            "@id": "https://myomr.in/discover-myomr/sustainable-development-goals.php#breadcrumb"
          },
          "mainEntity": {
            "@id": "https://myomr.in/discover-myomr/sustainable-development-goals.php#article"
          }
        },
        {
          "@type": "Article",
          "@id": "https://myomr.in/discover-myomr/sustainable-development-goals.php#article",
          "headline": "UN Sustainable Development Goals | MyOMR Community Commitment",
          "description": "MyOMR's commitment to UN Sustainable Development Goals and community sustainability initiatives",
          "image": "https://myomr.in/My-OMR-Logo.png",
          "author": {
            "@type": "Organization",
            "name": "MyOMR Editorial Team",
            "url": "https://myomr.in"
          },
          "publisher": {
            "@type": "Organization",
            "@id": "https://myomr.in/#organization",
            "name": "MyOMR",
            "url": "https://myomr.in",
            "logo": {
              "@type": "ImageObject",
              "url": "https://myomr.in/My-OMR-Logo.png",
              "width": 600,
              "height": 600
            }
          },
          "datePublished": "2025-01-01T00:00:00+05:30",
          "dateModified": "2025-01-01T00:00:00+05:30",
          "inLanguage": "en-IN",
          "articleSection": "Community Development",
          "keywords": "UN SDG, Sustainable Development Goals, OMR community, sustainability, Chennai, MyOMR, community development"
        },
        {
          "@type": "Organization",
          "@id": "https://myomr.in/#organization",
          "name": "MyOMR",
          "url": "https://myomr.in",
          "logo": {
            "@type": "ImageObject",
            "url": "https://myomr.in/My-OMR-Logo.png",
            "width": 600,
            "height": 600
          },
          "description": "Digital community hub for Old Mahabalipuram Road, Chennai",
          "address": {
            "@type": "PostalAddress",
            "addressLocality": "Chennai",
            "addressRegion": "Tamil Nadu",
            "addressCountry": "IN"
          },
          "sameAs": [
            "https://myomr.in"
          ]
        },
        {
          "@type": "BreadcrumbList",
          "@id": "https://myomr.in/discover-myomr/sustainable-development-goals.php#breadcrumb",
          "itemListElement": [
            {
              "@type": "ListItem",
              "position": 1,
              "name": "Home",
              "item": "https://myomr.in"
            },
            {
              "@type": "ListItem",
              "position": 2,
              "name": "Discover MyOMR",
              "item": "https://myomr.in/discover-myomr/"
            },
            {
              "@type": "ListItem",
              "position": 3,
              "name": "Sustainable Development Goals",
              "item": "https://myomr.in/discover-myomr/sustainable-development-goals.php"
            }
          ]
        },
        {
          "@type": "ItemList",
          "name": "The 17 UN Sustainable Development Goals",
          "description": "Complete list of all 17 UN Sustainable Development Goals (SDGs) and how MyOMR supports each goal",
          "numberOfItems": 17,
          "itemListElement": [
            {
              "@type": "ListItem",
              "position": 1,
              "name": "No Poverty",
              "description": "End poverty in all its forms everywhere"
            },
            {
              "@type": "ListItem",
              "position": 2,
              "name": "Zero Hunger",
              "description": "End hunger, achieve food security and improved nutrition"
            },
            {
              "@type": "ListItem",
              "position": 3,
              "name": "Good Health & Well-being",
              "description": "Ensure healthy lives and promote well-being for all"
            },
            {
              "@type": "ListItem",
              "position": 4,
              "name": "Quality Education",
              "description": "Ensure inclusive and equitable quality education"
            },
            {
              "@type": "ListItem",
              "position": 5,
              "name": "Gender Equality",
              "description": "Achieve gender equality and empower all women and girls"
            },
            {
              "@type": "ListItem",
              "position": 6,
              "name": "Clean Water & Sanitation",
              "description": "Ensure availability and sustainable management of water"
            },
            {
              "@type": "ListItem",
              "position": 7,
              "name": "Affordable & Clean Energy",
              "description": "Ensure access to affordable, reliable, sustainable energy"
            },
            {
              "@type": "ListItem",
              "position": 8,
              "name": "Decent Work & Economic Growth",
              "description": "Promote sustained economic growth and employment"
            },
            {
              "@type": "ListItem",
              "position": 9,
              "name": "Industry, Innovation & Infrastructure",
              "description": "Build resilient infrastructure and promote innovation"
            },
            {
              "@type": "ListItem",
              "position": 10,
              "name": "Reduced Inequalities",
              "description": "Reduce inequality within and among countries"
            },
            {
              "@type": "ListItem",
              "position": 11,
              "name": "Sustainable Cities & Communities",
              "description": "Make cities inclusive, safe, resilient and sustainable"
            },
            {
              "@type": "ListItem",
              "position": 12,
              "name": "Responsible Consumption & Production",
              "description": "Ensure sustainable consumption and production patterns"
            },
            {
              "@type": "ListItem",
              "position": 13,
              "name": "Climate Action",
              "description": "Take urgent action to combat climate change"
            },
            {
              "@type": "ListItem",
              "position": 14,
              "name": "Life Below Water",
              "description": "Conserve and sustainably use marine resources"
            },
            {
              "@type": "ListItem",
              "position": 15,
              "name": "Life on Land",
              "description": "Protect and restore terrestrial ecosystems"
            },
            {
              "@type": "ListItem",
              "position": 16,
              "name": "Peace, Justice & Strong Institutions",
              "description": "Promote peaceful and inclusive societies"
            },
            {
              "@type": "ListItem",
              "position": 17,
              "name": "Partnerships for the Goals",
              "description": "Strengthen implementation and partnerships"
            }
          ]
        },
        {
          "@type": "FAQPage",
          "mainEntity": [
            {
              "@type": "Question",
              "name": "What are the UN Sustainable Development Goals?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "The UN Sustainable Development Goals (SDGs) are 17 interlinked global goals designed to achieve a better and more sustainable future for all by 2030. They were adopted by world leaders in 2015 as part of the United Nations 2030 Agenda for Sustainable Development. These goals address pressing global challenges including poverty, inequality, climate change, environmental degradation, peace, and justice."
              }
            },
            {
              "@type": "Question",
              "name": "How many UN Sustainable Development Goals are there?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "There are 17 UN Sustainable Development Goals. They range from ending poverty and hunger to promoting climate action and building peace, justice, and strong institutions."
              }
            },
            {
              "@type": "Question",
              "name": "How does MyOMR support the Sustainable Development Goals?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "MyOMR supports the SDGs through four primary areas: (1) Sustainable Cities & Communities (SDG 11) via local news and civic engagement, (2) Quality Education (SDG 4) through comprehensive school directories, (3) Decent Work & Economic Growth (SDG 8) via job listings and business directories, and (4) Good Health & Well-being (SDG 3) through healthcare facility directories and wellness content."
              }
            },
            {
              "@type": "Question",
              "name": "When were the UN Sustainable Development Goals established?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "The UN Sustainable Development Goals were established in 2015 when world leaders adopted the United Nations 2030 Agenda for Sustainable Development. The goals are designed to be achieved by 2030."
              }
            },
            {
              "@type": "Question",
              "name": "How can I participate in supporting the SDGs through MyOMR?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "You can participate in three ways: (1) Individual Actions - Make sustainable choices, support local businesses, and participate in community initiatives, (2) Business Practices - Adopt sustainable business practices and list your business on MyOMR, and (3) Community Initiatives - Join or start community initiatives focused on sustainability, education, health, and social inclusion."
              }
            },
            {
              "@type": "Question",
              "name": "What is SDG 11 and how does MyOMR support it?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "SDG 11 is Sustainable Cities & Communities, aiming to make cities inclusive, safe, resilient and sustainable. MyOMR supports SDG 11 through community news and civic awareness, local business directories promoting sustainable practices, and community events that build social connections."
              }
            }
          ]
        }
      ]
    }
    </script>
</head>

<body>
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav(); ?>

<main id="main-content">
<!-- Hero Section -->
<section class="sdg-hero">
    <div class="container">
        <h1>Your OMR Community: Making Sustainability Real</h1>
        <p class="lead">From Perungudi to Thoraipakkam - Every action counts. Make your promise today.</p>
        <a href="#pledge-section" class="cta-button">Make Your Sustainability Pledge</a>
        <a href="#sdg-overview" class="cta-button" style="background: rgba(255,255,255,0.2);">Learn About SDGs</a>
    </div>
</section>

<!-- What Are SDGs Section -->
<article id="sdg-overview" class="container py-5">
    <header class="section-header">
        <h2>What Are Sustainable Development Goals?</h2>
        <p>The UN SDGs are 17 interlinked global goals designed to achieve a better and more sustainable future for all by 2030</p>
    </header>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="lead">In 2015, world leaders adopted the United Nations 2030 Agenda for Sustainable Development, committing to create a better future for all. These 17 Sustainable Development Goals address the most pressing global challenges, including poverty, inequality, climate change, environmental degradation, peace, and justice.</p>
                    <p>At MyOMR, we believe that sustainable development starts at the community level. Our platform is designed to support these global goals through local action, community engagement, and sustainable practices.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive SDG Wheel -->
    <div class="sdg-wheel">
        <img src="https://www.un.org/sustainabledevelopment/wp-content/uploads/2019/01/SDG_Wheel_Web_Transparent.png" alt="UN Sustainable Development Goals Wheel" class="img-fluid" loading="lazy" onerror="this.style.display='none';">
    </div>
</article>

<!-- MyOMR's Primary SDG Alignment -->
<section class="myomr-alignment">
    <div class="container">
        <div class="section-header">
            <h2 style="color: white;">How MyOMR Supports the SDGs</h2>
            <p style="color: rgba(255,255,255,0.9);">Our community platform actively contributes to sustainable development through local initiatives</p>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="alignment-card">
                    <h3><i class="fas fa-city"></i> Sustainable Cities & Communities (SDG 11)</h3>
                    <p>Through our local news, civic issue reporting, and community engagement, we're building a more resilient and inclusive OMR community.</p>
                    <ul>
                        <li>Community news and civic awareness</li>
                        <li>Local business directory promoting sustainable practices</li>
                        <li>Community events and social connections</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alignment-card">
                    <h3><i class="fas fa-graduation-cap"></i> Quality Education (SDG 4)</h3>
                    <p>Our comprehensive school directory and educational resources promote inclusive and equitable quality education for all OMR residents.</p>
                    <ul>
                        <li>School listings and educational resources</li>
                        <li>Community learning opportunities</li>
                        <li>Educational event promotion</li>
                    </ul>
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid rgba(255,255,255,0.2);">
                        <a href="/discover-myomr/sdg-education-schools.php" class="btn btn-light btn-lg" style="background: white; color: #2e7d32; font-weight: 600;">
                            <i class="fas fa-school"></i> Spread SDG Awareness in Schools
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alignment-card">
                    <h3><i class="fas fa-briefcase"></i> Decent Work & Economic Growth (SDG 8)</h3>
                    <p>Our job listings and business directory support local economic development and employment opportunities in the OMR corridor.</p>
                    <ul>
                        <li>Local job opportunities and career development</li>
                        <li>Business directory supporting local economy</li>
                        <li>Entrepreneurship and skill development</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alignment-card">
                    <h3><i class="fas fa-heartbeat"></i> Good Health & Well-being (SDG 3)</h3>
                    <p>Our hospital directory and health awareness content ensure healthy lives and promote well-being for all OMR residents.</p>
                    <ul>
                        <li>Healthcare facility directory</li>
                        <li>Health awareness and wellness promotion</li>
                        <li>Community health initiatives</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- All 17 SDGs Section -->
<section class="container py-5">
    <div class="section-header">
        <h2>The 17 Sustainable Development Goals</h2>
        <p>Explore each goal and understand how MyOMR contributes to global sustainability</p>
    </div>
    
    <div class="row">
        <!-- SDG 1: No Poverty -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-1">
                <div class="sdg-number">1</div>
                <h3>No Poverty</h3>
                <p>End poverty in all its forms everywhere. MyOMR supports local economic development through job listings and business promotion.</p>
            </div>
        </div>
        
        <!-- SDG 2: Zero Hunger -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-2">
                <div class="sdg-number">2</div>
                <h3>Zero Hunger</h3>
                <p>End hunger, achieve food security and improved nutrition. We promote local food businesses and community nutrition awareness.</p>
            </div>
        </div>
        
        <!-- SDG 3: Good Health -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-3">
                <div class="sdg-number">3</div>
                <h3>Good Health & Well-being</h3>
                <p>Ensure healthy lives and promote well-being for all. Our healthcare directory and wellness content support community health.</p>
            </div>
        </div>
        
        <!-- SDG 4: Quality Education -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-4">
                <div class="sdg-number">4</div>
                <h3>Quality Education</h3>
                <p>Ensure inclusive and equitable quality education. Our school directory and educational resources promote lifelong learning.</p>
                <a href="/discover-myomr/sdg-education-schools.php" class="btn btn-sm btn-light mt-2" style="color: #c5192d;">
                    <i class="fas fa-school"></i> SDG Education Initiative
                </a>
            </div>
        </div>
        
        <!-- SDG 5: Gender Equality -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-5">
                <div class="sdg-number">5</div>
                <h3>Gender Equality</h3>
                <p>Achieve gender equality and empower all women and girls. We promote inclusive community participation and equal opportunities.</p>
            </div>
        </div>
        
        <!-- SDG 6: Clean Water -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-6">
                <div class="sdg-number">6</div>
                <h3>Clean Water & Sanitation</h3>
                <p>Ensure availability and sustainable management of water. We raise awareness about local water and sanitation issues.</p>
            </div>
        </div>
        
        <!-- SDG 7: Clean Energy -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-7">
                <div class="sdg-number">7</div>
                <h3>Affordable & Clean Energy</h3>
                <p>Ensure access to affordable, reliable, sustainable energy. We promote awareness about renewable energy solutions.</p>
            </div>
        </div>
        
        <!-- SDG 8: Decent Work -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-8">
                <div class="sdg-number">8</div>
                <h3>Decent Work & Economic Growth</h3>
                <p>Promote sustained economic growth and employment. Our job platform supports local employment and economic development.</p>
            </div>
        </div>
        
        <!-- SDG 9: Industry Innovation -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-9">
                <div class="sdg-number">9</div>
                <h3>Industry, Innovation & Infrastructure</h3>
                <p>Build resilient infrastructure and promote innovation. We support local IT companies and technological advancement.</p>
            </div>
        </div>
        
        <!-- SDG 10: Reduced Inequalities -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-10">
                <div class="sdg-number">10</div>
                <h3>Reduced Inequalities</h3>
                <p>Reduce inequality within and among countries. We promote inclusive community participation and equal access to resources.</p>
            </div>
        </div>
        
        <!-- SDG 11: Sustainable Cities -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-11">
                <div class="sdg-number">11</div>
                <h3>Sustainable Cities & Communities</h3>
                <p>Make cities inclusive, safe, resilient and sustainable. Our platform builds community connections and civic engagement.</p>
            </div>
        </div>
        
        <!-- SDG 12: Responsible Consumption -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-12">
                <div class="sdg-number">12</div>
                <h3>Responsible Consumption & Production</h3>
                <p>Ensure sustainable consumption and production patterns. We promote local businesses and sustainable practices.</p>
            </div>
        </div>
        
        <!-- SDG 13: Climate Action -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-13">
                <div class="sdg-number">13</div>
                <h3>Climate Action</h3>
                <p>Take urgent action to combat climate change. We raise awareness about environmental issues and sustainable solutions.</p>
            </div>
        </div>
        
        <!-- SDG 14: Life Below Water -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-14">
                <div class="sdg-number">14</div>
                <h3>Life Below Water</h3>
                <p>Conserve and sustainably use marine resources. We promote awareness about coastal protection and marine conservation.</p>
            </div>
        </div>
        
        <!-- SDG 15: Life on Land -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-15">
                <div class="sdg-number">15</div>
                <h3>Life on Land</h3>
                <p>Protect and restore terrestrial ecosystems. We promote local parks, green spaces, and environmental conservation.</p>
            </div>
        </div>
        
        <!-- SDG 16: Peace & Justice -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-16">
                <div class="sdg-number">16</div>
                <h3>Peace, Justice & Strong Institutions</h3>
                <p>Promote peaceful and inclusive societies. We support civic engagement and community governance.</p>
            </div>
        </div>
        
        <!-- SDG 17: Partnerships -->
        <div class="col-md-6 col-lg-4">
            <div class="sdg-card sdg-17">
                <div class="sdg-number">17</div>
                <h3>Partnerships for the Goals</h3>
                <p>Strengthen implementation and partnerships. We build community partnerships and collaborative initiatives.</p>
            </div>
        </div>
    </div>
</section>

<!-- How You Can Participate - OMR Specific -->
<section class="container py-5">
    <div class="section-header">
        <h2>Three Ways to Take Action in OMR</h2>
        <p>Real steps you can take right now in your neighborhood</p>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-top border-success border-4">
                <div class="card-body text-center">
                    <i class="fas fa-user-friends fa-3x text-success mb-3"></i>
                    <h5 class="card-title">As an OMR Resident</h5>
                    <p class="card-text">Start today: Shop at <a href="/omr-listings/restaurants.php">local OMR restaurants</a>, use our <a href="/omr-listings/omr-road-schools.php">school directory</a> to help neighbors, join <a href="/events/">community events</a> in Perungudi, Karapakkam, or your area.</p>
                    <a href="#pledge-section" class="btn btn-success">Make Your Personal Pledge</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-top border-primary border-4">
                <div class="card-body text-center">
                    <i class="fas fa-building fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">As an OMR Business</h5>
                    <p class="card-text">List your business on MyOMR and commit to sustainable practices. Whether you're a restaurant in Perungudi, an IT company in Karapakkam, or a school in Thoraipakkam - make your promise.</p>
                    <a href="#business-commitment" class="btn btn-primary">See Business Promises</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-top border-warning border-4">
                <div class="card-body text-center">
                    <i class="fas fa-hands-helping fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">As a Community Organizer</h5>
                    <p class="card-text">Start an SDG-focused event in your OMR area. Post it on <a href="/events/">MyOMR Events</a>, invite neighbors from Kandanchavadi, Thoraipakkam, Perungudi - build a sustainable community together.</p>
                    <a href="/events/submit-event.php" class="btn btn-warning">Submit an Event</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- OMR-Specific Impact & Local Actions -->
<section class="container py-5" style="background: #f8f9fa;">
    <div class="section-header">
        <h2>Your Impact Right Here in OMR</h2>
        <p>Real actions you can take today in your neighborhood</p>
    </div>
    
    <div class="row mb-5">
        <div class="col-lg-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h3 class="text-success"><i class="fas fa-map-marker-alt"></i> OMR Areas Making a Difference</h3>
                    <p class="lead">From Perungudi to Thoraipakkam, see how our community is already taking action:</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check-circle text-success"></i> <strong>Perungudi:</strong> Local resident groups organizing beach cleanups (SDG 14 - Life Below Water)</li>
                        <li><i class="fas fa-check-circle text-success"></i> <strong>Kandanchavadi:</strong> Community vegetable gardens reducing food waste (SDG 2 - Zero Hunger)</li>
                        <li><i class="fas fa-check-circle text-success"></i> <strong>Karapakkam:</strong> Solar panel initiatives in apartment complexes (SDG 7 - Clean Energy)</li>
                        <li><i class="fas fa-check-circle text-success"></i> <strong>Thoraipakkam:</strong> Weekend skill-sharing workshops for youth (SDG 4 - Quality Education)</li>
                    </ul>
                    <a href="/omr-listings/" class="btn btn-success mt-3">Explore OMR Listings</a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h3 class="text-primary"><i class="fas fa-hand-holding-heart"></i> Actions You Can Start This Week</h3>
                    <p class="lead">Simple, practical steps for OMR residents:</p>
                    <ol>
                        <li><strong>Shop Local:</strong> Buy from OMR businesses listed on <a href="/omr-listings/restaurants.php">our directory</a> - support local economy (SDG 8)</li>
                        <li><strong>Reduce Plastic:</strong> Carry your own bag when shopping in Perungudi or Karapakkam markets (SDG 12)</li>
                        <li><strong>Use Public Transport:</strong> Take OMR buses or carpool - reduce traffic emissions (SDG 11, SDG 13)</li>
                        <li><strong>Volunteer:</strong> Join community events posted on <a href="/events/">MyOMR Events</a> (SDG 17)</li>
                        <li><strong>Share Knowledge:</strong> Help neighbors find schools via <a href="/omr-listings/omr-road-schools.php">our school directory</a> (SDG 4)</li>
                        <li><strong>Support Healthcare:</strong> Promote awareness using our <a href="/omr-listings/hospitals.php">hospital listings</a> (SDG 3)</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Business Commitment Section -->
<section id="business-commitment" class="container py-5">
    <div class="section-header">
        <h2>For OMR Businesses: Make a Sustainable Promise</h2>
        <p>Join OMR businesses committing to sustainable practices</p>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-left border-success border-4">
                <div class="card-body">
                    <h4 class="text-success"><i class="fas fa-leaf"></i> Restaurant Promise</h4>
                    <p><strong>I promise to:</strong></p>
                    <ul>
                        <li>Reduce food waste by 30% in 2025</li>
                        <li>Use locally-sourced ingredients when possible</li>
                        <li>Eliminate single-use plastic packaging</li>
                        <li>Support OMR local farmers and suppliers</li>
                    </ul>
                    <p class="small text-muted">Supports: SDG 2, SDG 12, SDG 15</p>
                    <a href="/omr-listings/restaurants.php" class="btn btn-outline-success btn-sm">List Your Restaurant</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-left border-primary border-4">
                <div class="card-body">
                    <h4 class="text-primary"><i class="fas fa-building"></i> IT Company Promise</h4>
                    <p><strong>I promise to:</strong></p>
                    <ul>
                        <li>Hire 20% more local OMR residents by 2026</li>
                        <li>Provide skill development training programs</li>
                        <li>Promote diversity and gender equality in hiring</li>
                        <li>Reduce energy consumption through efficient practices</li>
                    </ul>
                    <p class="small text-muted">Supports: SDG 8, SDG 5, SDG 7, SDG 4</p>
                    <a href="/listings/" class="btn btn-outline-primary btn-sm">List Your Business</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-left border-warning border-4">
                <div class="card-body">
                    <h4 class="text-warning"><i class="fas fa-graduation-cap"></i> School Promise</h4>
                    <p><strong>I promise to:</strong></p>
                    <ul>
                        <li>Offer scholarships to underprivileged OMR students</li>
                        <li>Teach sustainability and SDGs in curriculum</li>
                        <li>Create community learning programs</li>
                        <li>Promote gender equality and inclusive education</li>
                    </ul>
                    <p class="small text-muted">Supports: SDG 4, SDG 5, SDG 1, SDG 10</p>
                    <a href="/omr-listings/omr-road-schools.php" class="btn btn-outline-warning btn-sm">List Your School</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <p class="lead">Ready to make your business commitment?</p>
        <a href="/contact-my-omr-team.php" class="btn btn-success btn-lg">Contact Us to Pledge</a>
    </div>
</section>

<!-- Individual Pledge Section -->
<section id="pledge-section" class="py-5" style="background: linear-gradient(135deg, #1e3a1e 0%, #2d5a2d 50%, #3d7a3d 100%);">
    <div class="container">
        <div class="section-header">
            <h2 style="color: #d4edda; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">OMR Community Sustainability Pledge</h2>
            <p style="color: rgba(212,237,218,0.9);">Commit to actions that make OMR a better and more sustainable place</p>
        </div>
        
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card shadow-lg border-0" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-radius: 20px;">
                    <div class="card-body p-4">
                        <?php
                        // Handle form submission
                        $pledge_success = false;
                        $pledge_error = '';
                        
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_pledge'])) {
                            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
                            $pledges = $_POST['pledges'] ?? [];
                            
                            if (empty($email)) {
                                $pledge_error = "Please enter your email address.";
                            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $pledge_error = "Please enter a valid email address.";
                            } elseif (empty($pledges)) {
                                $pledge_error = "Please select at least one pledge commitment.";
                            } else {
                                // Build pledge list
                                $pledge_labels = [
                                    'pledge1' => 'Reduce Waste - Use reusable bags and containers for shopping in OMR',
                                    'pledge2' => 'Support Local - Shop at OMR businesses and restaurants listed on MyOMR',
                                    'pledge3' => 'Share Knowledge - Help neighbors find schools, hospitals, or services via MyOMR',
                                    'pledge4' => 'Get Involved - Attend or organize community events listed on MyOMR',
                                    'pledge5' => 'Save Energy - Use public transport or carpool for OMR commute',
                                    'pledge6' => 'Stay Informed - Read MyOMR local news to stay aware of community issues'
                                ];
                                
                                $selected_pledges = [];
                                foreach ($pledges as $pledge_id) {
                                    if (isset($pledge_labels[$pledge_id])) {
                                        $selected_pledges[] = $pledge_labels[$pledge_id];
                                    }
                                }
                                
                                $to = "harikrishnanhk1988@gmail.com";
                                $subject = "New OMR Community Sustainability Pledge - " . htmlspecialchars($email);
                                $message = "A new OMR Community Sustainability Pledge has been submitted!\n\n";
                                $message .= "Email: " . htmlspecialchars($email) . "\n\n";
                                $message .= "Selected Pledges:\n";
                                $message .= "==================\n\n";
                                foreach ($selected_pledges as $index => $pledge) {
                                    $message .= ($index + 1) . ". " . $pledge . "\n";
                                }
                                $message .= "\n\n";
                                $message .= "Submitted on: " . date('Y-m-d H:i:s') . "\n";
                                $message .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
                                
                                $headers = "From: MyOMR Pledge Form <noreply@myomr.in>\r\n";
                                $headers .= "Reply-To: " . htmlspecialchars($email) . "\r\n";
                                $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
                                
                                if (mail($to, $subject, $message, $headers)) {
                                    $pledge_success = true;
                                } else {
                                    $pledge_error = "There was an error submitting your pledge. Please try again or contact us directly.";
                                }
                            }
                        }
                        
                        if ($pledge_success):
                        ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <h4 class="alert-heading"><i class="fas fa-check-circle"></i> Thank you for your pledge!</h4>
                            <p>Your OMR Community Sustainability Pledge has been successfully submitted. We'll keep you updated on sustainability initiatives in your community.</p>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;                            </button>
                        </div>
                        <?php elseif ($pledge_error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong> <?php echo htmlspecialchars($pledge_error); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;                            </button>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="#pledge-section">
                            <h3 class="mb-4" style="color: #2e7d32;"><i class="fas fa-leaf"></i> I Pledge to Contribute to SDGs by:</h3>
                            
                            <!-- Email Input -->
                            <div class="form-group mb-4">
                                <label for="pledge_email" style="color: #1b5e20; font-weight: bold;"><i class="fas fa-envelope"></i> Your Email Address <span class="text-danger">*</label>
                                <input type="email" class="form-control form-control-lg" id="pledge_email" name="email" 
                                       placeholder="your.email@example.com" required 
                                       style="border: 2px solid #4caf50; border-radius: 10px;"
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                <small class="form-text text-muted">We'll use this to keep you updated on OMR sustainability initiatives and communicate about your pledge.</small>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check pledge-option" style="background: linear-gradient(135deg, #ffffff 0%, #f1f8e9 100%); padding: 20px; border-radius: 12px; border: 2px solid #81c784; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                        <input class="form-check-input" type="checkbox" id="pledge1" name="pledges[]" value="pledge1" style="width: 20px; height: 20px; accent-color: #4caf50;">
                                        <label class="form-check-label" for="pledge1" style="color: #2e7d32; font-weight: 500; margin-left: 10px; cursor: pointer;">
                                            <strong>Reduce Waste:</strong> Use reusable bags and containers for shopping in OMR
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check pledge-option" style="background: linear-gradient(135deg, #ffffff 0%, #f1f8e9 100%); padding: 20px; border-radius: 12px; border: 2px solid #81c784; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                        <input class="form-check-input" type="checkbox" id="pledge2" name="pledges[]" value="pledge2" style="width: 20px; height: 20px; accent-color: #4caf50;">
                                        <label class="form-check-label" for="pledge2" style="color: #2e7d32; font-weight: 500; margin-left: 10px; cursor: pointer;">
                                            <strong>Support Local:</strong> Shop at OMR businesses and restaurants listed on MyOMR
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check pledge-option" style="background: linear-gradient(135deg, #ffffff 0%, #f1f8e9 100%); padding: 20px; border-radius: 12px; border: 2px solid #81c784; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                        <input class="form-check-input" type="checkbox" id="pledge3" name="pledges[]" value="pledge3" style="width: 20px; height: 20px; accent-color: #4caf50;">
                                        <label class="form-check-label" for="pledge3" style="color: #2e7d32; font-weight: 500; margin-left: 10px; cursor: pointer;">
                                            <strong>Share Knowledge:</strong> Help neighbors find schools, hospitals, or services via MyOMR
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check pledge-option" style="background: linear-gradient(135deg, #ffffff 0%, #f1f8e9 100%); padding: 20px; border-radius: 12px; border: 2px solid #81c784; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                        <input class="form-check-input" type="checkbox" id="pledge4" name="pledges[]" value="pledge4" style="width: 20px; height: 20px; accent-color: #4caf50;">
                                        <label class="form-check-label" for="pledge4" style="color: #2e7d32; font-weight: 500; margin-left: 10px; cursor: pointer;">
                                            <strong>Get Involved:</strong> Attend or organize community events listed on MyOMR
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check pledge-option" style="background: linear-gradient(135deg, #ffffff 0%, #f1f8e9 100%); padding: 20px; border-radius: 12px; border: 2px solid #81c784; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                        <input class="form-check-input" type="checkbox" id="pledge5" name="pledges[]" value="pledge5" style="width: 20px; height: 20px; accent-color: #4caf50;">
                                        <label class="form-check-label" for="pledge5" style="color: #2e7d32; font-weight: 500; margin-left: 10px; cursor: pointer;">
                                            <strong>Save Energy:</strong> Use public transport or carpool for OMR commute
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check pledge-option" style="background: linear-gradient(135deg, #ffffff 0%, #f1f8e9 100%); padding: 20px; border-radius: 12px; border: 2px solid #81c784; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                        <input class="form-check-input" type="checkbox" id="pledge6" name="pledges[]" value="pledge6" style="width: 20px; height: 20px; accent-color: #4caf50;">
                                        <label class="form-check-label" for="pledge6" style="color: #2e7d32; font-weight: 500; margin-left: 10px; cursor: pointer;">
                                            <strong>Stay Informed:</strong> Read MyOMR local news to stay aware of community issues
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4 pt-4" style="border-top: 2px solid #81c784;">
                                <p class="mb-3" style="color: #2e7d32; font-weight: 500;">Share your commitment with the OMR community!</p>
                                <button type="submit" name="submit_pledge" class="btn btn-success btn-lg px-5 py-3" 
                                        style="background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%); border: none; border-radius: 25px; font-weight: bold; box-shadow: 0 4px 15px rgba(76,175,80,0.4);">
                                    <i class="fas fa-paper-plane"></i> Submit My Pledge
                                </button>
                                <br>
                                <a href="/components/subscribe.php" class="btn btn-outline-success btn-sm mt-3" 
                                   style="border: 2px solid #4caf50; color: #2e7d32; border-radius: 20px;">
                                    <i class="fas fa-bell"></i> Get Updates on SDG Initiatives
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Connect with OMR Resources -->
<section class="container py-5">
    <div class="section-header">
        <h2>Start Taking Action Today</h2>
        <p>Explore these MyOMR resources to begin your sustainable journey</p>
    </div>
    
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-center h-100 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-school fa-3x text-success mb-3"></i>
                    <h5>Schools Directory</h5>
                    <p class="small">Find quality education options in OMR</p>
                    <a href="/omr-listings/omr-road-schools.php" class="btn btn-sm btn-success">Browse Schools</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-center h-100 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-briefcase fa-3x text-primary mb-3"></i>
                    <h5>Job Listings</h5>
                    <p class="small">Find opportunities in OMR's growing economy</p>
                    <a href="/omr-local-job-listings/" class="btn btn-sm btn-primary">View Jobs</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-center h-100 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-utensils fa-3x text-warning mb-3"></i>
                    <h5>Restaurants</h5>
                    <p class="small">Support local OMR food businesses</p>
                    <a href="/omr-listings/restaurants.php" class="btn btn-sm btn-warning">Find Restaurants</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-center h-100 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-calendar-alt fa-3x text-info mb-3"></i>
                    <h5>Community Events</h5>
                    <p class="small">Join sustainability events in your area</p>
                    <a href="/events/" class="btn btn-sm btn-info">See Events</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Frequently Asked Questions (FAQ) Section -->
<section id="faq" class="container py-5">
    <header class="section-header">
        <h2>Frequently Asked Questions About SDGs</h2>
        <p>Common questions about the UN Sustainable Development Goals and MyOMR's role</p>
    </header>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="accordion" id="sdgFAQAccordion">
                <div class="accordion-item border rounded mb-2 overflow-hidden">
                    <h2 class="accordion-header" id="faq1">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                            What are the UN Sustainable Development Goals?
                        </button>
                    </h2>
                    <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#sdgFAQAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">The UN Sustainable Development Goals (SDGs) are 17 interlinked global goals designed to achieve a better and more sustainable future for all by 2030. They were adopted by world leaders in 2015 as part of the United Nations 2030 Agenda for Sustainable Development. These goals address pressing global challenges including poverty, inequality, climate change, environmental degradation, peace, and justice.</p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item border rounded mb-2 overflow-hidden">
                    <h2 class="accordion-header" id="faq2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                            How many UN Sustainable Development Goals are there?
                        </button>
                    </h2>
                    <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#sdgFAQAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">There are 17 UN Sustainable Development Goals. They range from ending poverty and hunger to promoting climate action and building peace, justice, and strong institutions.</p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item border rounded mb-2 overflow-hidden">
                    <h2 class="accordion-header" id="faq3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                            How does MyOMR support the Sustainable Development Goals?
                        </button>
                    </h2>
                    <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#sdgFAQAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">MyOMR supports the SDGs through four primary areas: (1) Sustainable Cities &amp; Communities (SDG 11) via local news and civic engagement, (2) Quality Education (SDG 4) through comprehensive school directories, (3) Decent Work &amp; Economic Growth (SDG 8) via job listings and business directories, and (4) Good Health &amp; Well-being (SDG 3) through healthcare facility directories and wellness content.</p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item border rounded mb-2 overflow-hidden">
                    <h2 class="accordion-header" id="faq4">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                            When were the UN Sustainable Development Goals established?
                        </button>
                    </h2>
                    <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#sdgFAQAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">The UN Sustainable Development Goals were established in 2015 when world leaders adopted the United Nations 2030 Agenda for Sustainable Development. The goals are designed to be achieved by 2030.</p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item border rounded mb-2 overflow-hidden">
                    <h2 class="accordion-header" id="faq5">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                            How can I participate in supporting the SDGs through MyOMR?
                        </button>
                    </h2>
                    <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#sdgFAQAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">You can participate in three ways: (1) Individual Actions - Make sustainable choices, support local businesses, and participate in community initiatives, (2) Business Practices - Adopt sustainable business practices and list your business on MyOMR, and (3) Community Initiatives - Join or start community initiatives focused on sustainability, education, health, and social inclusion.</p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item border rounded mb-2 overflow-hidden">
                    <h2 class="accordion-header" id="faq6">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                            What is SDG 11 and how does MyOMR support it?
                        </button>
                    </h2>
                    <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="faq6" data-bs-parent="#sdgFAQAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">SDG 11 is Sustainable Cities &amp; Communities, aiming to make cities inclusive, safe, resilient and sustainable. MyOMR supports SDG 11 through community news and civic awareness, local business directories promoting sustainable practices, and community events that build social connections.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
    <div class="container">
        <h2>Ready to Make a Difference?</h2>
        <p>Join the MyOMR community in building a more sustainable future for our neighborhood</p>
        <a href="/contact-my-omr-team.php" class="cta-button">Contact Us</a>
        <a href="/components/subscribe.php" class="cta-button">Subscribe for Updates</a>
    </div>
</section>

</main>

<?php omr_footer(); ?>

<!-- Smooth Scrolling -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            var href = this.getAttribute('href');
            if (href && href !== '#' && href.length > 1) {
                var target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });
});
</script>

</body>
</html>
<?php
// Flush output buffer
ob_end_flush();
?>
