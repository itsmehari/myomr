<?php
// Get the slug from the URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
if (empty($slug)) {
    http_response_code(400);
    echo "<h1>400 Bad Request</h1>";
    echo "<p>No article specified.</p>";
    exit;
}

// Bootstrap: central path + component helpers (omr_nav, omr_footer)
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';

require_once ROOT_PATH . '/core/omr-connect.php';

// Prepare and execute the query to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM articles WHERE slug = ? AND status = 'published'");
if ($stmt === false) {
    error_log('MySQL prepare statement failed: ' . $conn->error);
    http_response_code(500);
    echo "<h1>500 Internal Server Error</h1>";
    exit;
}

$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();

$stmt->close();
// Don't close connection here - we'll need it for related articles

// If no article is found, serve branded 404
if (!$article) {
    require_once ROOT_PATH . '/core/serve-404.php';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once ROOT_PATH . '/components/head-resources.php'; ?>
    <?php $ga_custom_params = [
      'article_category' => $article['category'] ?? 'Local News',
      'article_author'   => $article['author']   ?? 'MyOMR Editorial Team',
    ]; include ROOT_PATH . '/components/analytics.php'; ?>
    <?php 
    // Set variables for SEO meta file
    $article_title = htmlspecialchars($article['title']);
    $article_desc = htmlspecialchars($article['summary']);
    $article_content = strip_tags(htmlspecialchars($article['content']));
    $article_url = 'https://myomr.in/local-news/' . $article['slug'];
    // Only prepend domain for relative paths; full URLs used as-is
    $img = $article['image_path'] ?? '/My-OMR-Logo.png';
    $article_image = (strpos($img, 'http://') === 0 || strpos($img, 'https://') === 0)
        ? $img
        : 'https://myomr.in' . (strpos($img, '/') === 0 ? $img : '/' . $img);
    $article_date = $article['published_date'];
    $article_author = htmlspecialchars($article['author'] ?? 'MyOMR Editorial Team');
    $article_category = htmlspecialchars($article['category'] ?? 'Local News');
    
    // Include SEO meta file
    require_once ROOT_PATH . '/core/article-seo-meta.php'; 
    
    // Check if this is a BLO article to add additional structured data
    $is_blo_article = (strpos($article['slug'], 'blo') !== false || strpos(strtolower($article['title']), 'blo') !== false);
    
    // Generic community awareness - show for all articles
    $show_community_section = true;
    
    // Detect sports articles for additional SEO
    $is_sports_article = (
        strtolower($article_category) === 'sports' ||
        strpos(strtolower($article['title']), 'sport') !== false ||
        strpos(strtolower($article['title']), 'athlete') !== false ||
        strpos(strtolower($article['title']), 'kabaddi') !== false ||
        strpos(strtolower($article['title']), 'medal') !== false ||
        strpos(strtolower($article['tags'] ?? ''), 'sport') !== false ||
        strpos(strtolower($article['tags'] ?? ''), 'kabaddi') !== false
    );
    // LPG / crisis-type articles: add FAQPage schema for rich results (English slug only)
    $is_lpg_article = (strpos($article['slug'], 'lpg-shortage') !== false && substr($article['slug'], -6) !== '-tamil');
    // Fuel panic / petrol crisis articles: FAQPage schema (English slug only)
    $is_fuel_panic_article = (strpos($article['slug'], 'fuel-panic') !== false && substr($article['slug'], -6) !== '-tamil');
    // Tamil Nadu Assembly Election 2026 article: FAQPage schema for rich results (English slug only)
    $is_tn_election_2026_article = (strpos($article['slug'], 'tamil-nadu-assembly-election-2026') !== false && substr($article['slug'], -6) !== '-tamil');
    ?>
    
    <?php 
    // Include additional SEO for sports articles
    if ($is_sports_article):
        require_once ROOT_PATH . '/local-news/article-sports-seo-enhancement.php';
    endif;
    
    // FAQPage schema for LPG shortage article (English) - supports FAQ rich results in search
    if ($is_lpg_article): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "Why is there an LPG shortage in Chennai and India?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The shortage is a direct ripple effect of the ongoing Iran-Israel-USA conflict in the Middle East, which has disrupted global energy supply chains and LPG shipments. India imports a large portion of its LPG from Gulf countries; tensions in the region have slowed shipments and increased fuel prices. The government prioritises domestic and essential services, reducing availability for commercial users like restaurants."
          }
        },
        {
          "@type": "Question",
          "name": "How does the LPG shortage affect OMR restaurants and street vendors?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Restaurants and roadside food vendors (thallu vandi) on OMR rely on 19-kg commercial LPG cylinders. Medium-sized restaurants use 5-10 cylinders per day; many keep only 2-3 days stock. Street vendors typically have one or two cylinders with no buffer. A prolonged shortage can force temporary closures, reduced menus, and shorter hours, especially along the IT corridor where cafeterias and food stalls serve thousands daily."
          }
        },
        {
          "@type": "Question",
          "name": "What is Tiruppur doing to save LPG?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The Tiruppur Hotel Owners Association has announced that dosa and omelette will not be prepared temporarily, as these items consume more LPG. Hotels are instead serving only variety rice items such as lemon rice, tamarind rice, coconut rice, tomato rice, and curd rice to reduce gas consumption until supply stabilises."
          }
        },
        {
          "@type": "Question",
          "name": "Who is affected by the commercial LPG shortage?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Hotel associations representing thousands of eateries in Chennai, Bengaluru, Mumbai and other cities are affected. The Chennai Hotels Association represents 10,000+ hotels and eateries. Street food vendors, IT park cafeterias, and corporate catering kitchens on OMR are also at risk. Up to 50% of restaurants may temporarily shut kitchens if the supply disruption continues."
          }
        }
      ]
    }
    </script>
    <?php endif;
    
    // FAQPage schema for fuel panic / petrol bunk article (English) - supports FAQ rich results
    if ($is_fuel_panic_article): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "Is there a real petrol shortage in Chennai?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "No. Fuel dealers have clarified that there is no actual shortage of petrol or diesel in Tamil Nadu. Supply chains are functioning normally. The rush at petrol bunks was triggered by rumours on social media about possible disruptions due to geopolitical tensions in West Asia."
          }
        },
        {
          "@type": "Question",
          "name": "Where were long queues reported in Chennai?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Long queues were reported in Velachery, Perungudi, OMR Road (IT corridor), and the SRP Tools junction area. Residents along the IT corridor reported unusually long waits during early morning hours as commuters stopped to refuel before heading to offices."
          }
        },
        {
          "@type": "Question",
          "name": "Why did panic buying happen at petrol bunks?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Speculation on social media about fuel supply disruptions due to West Asia tensions triggered the rush. When people see others stocking up, they tend to follow the same behaviour, leading to sudden spikes in demand. Experts say such situations are driven more by psychological behaviour than real supply shortages."
          }
        },
        {
          "@type": "Question",
          "name": "Is it safe to store petrol in containers?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "No. Officials have warned that filling fuel in plastic cans or containers is dangerous. Unnecessary hoarding can cause logistical pressure even when overall fuel availability is stable. Fuel stations maintain multiple days of inventory and receive regular supplies from depots."
          }
        }
      ]
    }
    </script>
    <?php endif;
    
    // FAQPage schema for Tamil Nadu Assembly Election 2026 article (English) - supports FAQ rich results
    if ($is_tn_election_2026_article): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "When is the Tamil Nadu Assembly election 2026?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Polling for the Tamil Nadu Assembly election 2026 will be held on 23 April 2026 in a single phase. All 234 assembly constituencies will go to the polls on that day."
          }
        },
        {
          "@type": "Question",
          "name": "When will Tamil Nadu election 2026 results be declared?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Counting of votes will take place on 4 May 2026. Results will be declared the same day."
          }
        },
        {
          "@type": "Question",
          "name": "Which assembly constituencies cover OMR (Old Mahabalipuram Road)?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Chennai South Lok Sabha constituency includes Velachery (AC 26), which covers Perungudi, Taramani and Adambakkam, and Sholinganallur (AC 27), the main OMR IT corridor. Thiruporur in Chengalpattu district is also part of the OMR corridor."
          }
        },
        {
          "@type": "Question",
          "name": "Which party has announced candidates for Tamil Nadu election 2026?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "As of March 2026, Naam Tamilar Katchi (NTK) is the only party to have announced a full list of 234 candidates. NTK chief Seeman will contest from Karaikudi. DMK and AIADMK are yet to announce their full candidate lists for Chennai and other seats."
          }
        }
      ]
    }
    </script>
    <?php endif;
    
    if ($is_blo_article): ?>
    <!-- Additional Structured Data for BLO Article - FAQPage -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What is a Block Level Officer (BLO)?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Block Level Officers (BLOs) are Election Commission officials responsible for maintaining accurate electoral rolls in their assigned polling areas. They assist voters with new voter registration, corrections to existing voter details, transfers, deletions, address verification, and election-related information."
          }
        },
        {
          "@type": "Question",
          "name": "How can I find my BLO in Shozhinganallur Assembly Constituency?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "You can search for your BLO using the online search tool at MyOMR.in by selecting your location or polling station from the dropdown menus. The search will display all BLO officers matching your criteria with their contact details including mobile number, polling station address, and part number."
          }
        },
        {
          "@type": "Question",
          "name": "What services can I get from my BLO?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Your BLO can help you with new voter registration (for 18+ years), updating address changes, correcting name spellings or details, submitting claims and objections, and verifying your voter status during the Special Summary Revision (SSR) period."
          }
        },
        {
          "@type": "Question",
          "name": "Which areas are covered under AC Shozhinganallur?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Assembly Constituency 27 (Shozhinganallur) covers areas including Ullagaram, Puzhithivakkam, Madipakkam, Perungudi, Palavakkam, Kottivakkam, Neelangarai, Thuraipakkam, Karapakkam, Sholinganallur, Jalladianpet, Medavakkam, Kovilambakkam, Nanmangalam, S.Kolathur, Vengaivasal, Sithalapakkam, Semmenjery, Perumbakkam, Ottiyambakkam, Uthandi, and many more localities along Old Mahabalipuram Road (OMR)."
          }
        },
        {
          "@type": "Question",
          "name": "When is the Special Summary Revision (SSR) period?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The Special Summary Revision (SSR) is an ongoing process conducted by the Election Commission of India to update electoral rolls. During this period, citizens can register as new voters, update their details, or make corrections. Contact your BLO for specific dates and deadlines."
          }
        }
      ]
    }
    </script>
    
    <!-- HowTo Schema for Finding BLO -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "HowTo",
      "name": "How to Find Your Block Level Officer (BLO) in Shozhinganallur",
      "description": "Step-by-step guide to find your Block Level Officer contact details for electoral roll revision",
      "image": "<?php echo $article_image; ?>",
      "totalTime": "PT5M",
      "estimatedCost": {
        "@type": "MonetaryAmount",
        "currency": "INR",
        "value": "0"
      },
      "step": [
        {
          "@type": "HowToStep",
          "position": 1,
          "name": "Visit the BLO Search Page",
          "text": "Go to https://myomr.in/info/find-blo-officer.php on your web browser",
          "url": "https://myomr.in/info/find-blo-officer.php"
        },
        {
          "@type": "HowToStep",
          "position": 2,
          "name": "Select Your Location",
          "text": "Choose your area from the 'Filter by Location' dropdown menu. This will show all BLO officers in your locality."
        },
        {
          "@type": "HowToStep",
          "position": 3,
          "name": "Or Select Your Polling Station",
          "text": "Alternatively, select your polling station name from the 'Filter by Polling Station' dropdown if you know it."
        },
        {
          "@type": "HowToStep",
          "position": 4,
          "name": "Click Search",
          "text": "Click the Search button to view all matching BLO records with their contact details."
        },
        {
          "@type": "HowToStep",
          "position": 5,
          "name": "Contact Your BLO",
          "text": "Find your BLO from the results and use the Call or WhatsApp button to contact them directly for voter registration or electoral roll queries."
        }
      ],
      "tool": [
        {
          "@type": "HowToTool",
          "name": "Computer or Mobile Device"
        },
        {
          "@type": "HowToTool",
          "name": "Internet Connection"
        }
      ]
    }
    </script>
    
    <!-- GovernmentService Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "GovernmentService",
      "name": "Block Level Officer Search - AC Shozhinganallur",
      "description": "Search for Block Level Officer (BLO) contact details in Assembly Constituency 27 (Shozhinganallur) for electoral roll revision and voter services",
      "serviceType": "Election Services",
      "areaServed": {
        "@type": "City",
        "name": "Chennai",
        "containedIn": {
          "@type": "State",
          "name": "Tamil Nadu"
        }
      },
      "provider": {
        "@type": "GovernmentOrganization",
        "name": "Election Commission of India",
        "url": "https://eci.gov.in"
      },
      "audience": {
        "@type": "Audience",
        "audienceType": "Voters"
      },
      "availableChannel": {
        "@type": "ServiceChannel",
        "serviceUrl": "https://myomr.in/info/find-blo-officer.php",
        "serviceType": "Online"
      }
    }
    </script>
    <?php endif; ?>
    
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        :root {
            --myomr-green: #14532d;
            --myomr-light-green: #22c55e;
        }
        
        .article-container { 
            max-width: 900px; 
            margin: auto; 
            padding: 2rem 1rem; 
            font-family: 'Roboto', sans-serif;
            line-height: 1.8;
        }
        
        .article-header img { 
            width: 100%; 
            height: auto; 
            margin-bottom: 1.5rem; 
            border-radius: 8px; 
        }
        
        .article-content { 
            line-height: 1.8; 
        }
        
        .article-content img { 
            max-width: 100%; 
            height: auto; 
            border-radius: 4px; 
        }
        
        .article-meta { 
            font-size: 0.9rem; 
            color: #6c757d; 
            margin-bottom: 1rem; 
        }
        
        .article-content h2 {
            font-family: 'Playfair Display', serif;
            color: var(--myomr-green);
            font-size: 2rem;
            margin: 2rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--myomr-light-green);
        }

        .article-content h3 {
            font-family: 'Playfair Display', serif;
            color: var(--myomr-green);
            font-size: 1.5rem;
            margin: 1.5rem 0 1rem;
        }

        .info-box {
            background: #e7f5e7;
            border-left: 4px solid var(--myomr-light-green);
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 5px;
        }

        .alert-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 5px;
        }
        
        /* Share bar */
        .article-share-bar {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin: 1.5rem 0;
            padding: 1rem 0;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }
        .article-share-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--myomr-green);
            margin-right: 0.5rem;
        }
        .article-share-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: #fff;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .article-share-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); color: #fff; }
        .article-share-wa { background: #25d366; }
        .article-share-fb { background: #1877f2; }
        .article-share-tw { background: #000; }
        .article-share-copy { background: #6b7280; border: 0; cursor: pointer; font-size: 1rem; }
        .article-share-copy.copied { background: var(--myomr-light-green); }
        
        /* Table of contents */
        .article-toc {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.25rem 1.5rem;
            margin: 1.5rem 0;
        }
        .article-toc-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            color: var(--myomr-green);
            margin: 0 0 0.75rem 0;
            padding: 0;
            border: none;
        }
        .article-toc-list {
            margin: 0;
            padding-left: 1.25rem;
            list-style: decimal;
        }
        .article-toc-list a {
            color: var(--myomr-green);
            text-decoration: none;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .article-toc-list a:hover { text-decoration: underline; }
        .article-content h2[id] { scroll-margin-top: 1.5rem; }
    </style>
</head>
<body>

<?php omr_nav('main'); ?>

<div class="container article-container">
    <article>
        <header class="article-header">
            <h1><?php echo htmlspecialchars($article['title']); ?></h1>
            <p class="article-meta">
                Published on <?php echo date('F j, Y', strtotime($article['published_date'])); ?>
                <?php if (!empty($article['author'])): ?>
                    | By <?php echo htmlspecialchars($article['author']); ?>
                <?php endif; ?>
            </p>
            <?php if (!empty($article['image_path'])): ?>
                <img src="<?php echo htmlspecialchars($article['image_path']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="img-fluid">
            <?php endif; ?>
        </header>
        
        <!-- Language Switch Link (if Tamil version exists) -->
        <?php
        // Check if Tamil version exists (slug ends with -tamil)
        $tamil_slug = $slug . '-tamil';
        $tamil_check_sql = "SELECT slug, title FROM articles WHERE slug = ? AND status = 'published' LIMIT 1";
        $tamil_check_stmt = $conn->prepare($tamil_check_sql);
        $tamil_version_exists = false;
        $tamil_article = null;
        
        if ($tamil_check_stmt) {
            $tamil_check_stmt->bind_param("s", $tamil_slug);
            $tamil_check_stmt->execute();
            $tamil_result = $tamil_check_stmt->get_result();
            $tamil_article = $tamil_result->fetch_assoc();
            $tamil_check_stmt->close();
            
            if ($tamil_article) {
                $tamil_version_exists = true;
            }
        }
        
        // Also check if current article is English version (not ending in -tamil)
        $is_english = (substr($slug, -6) !== '-tamil');
        $english_slug = str_replace('-tamil', '', $slug);
        
        if ($is_english && $tamil_version_exists):
        ?>
            <div style="background: #f0fdf4; border: 2px solid #22c55e; padding: 1.5rem; margin: 2rem 0; border-radius: 8px; text-align: center;">
                <p style="margin: 0 0 1rem 0; font-weight: 600; color: #14532d;">
                    <i class="fas fa-language"></i> This article is also available in Tamil
                </p>
                <a href="/local-news/<?php echo htmlspecialchars($tamil_slug); ?>" 
                   style="display: inline-block; background: #14532d; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 6px; font-weight: 600;">
                    <i class="fas fa-book"></i> தமிழில் படிக்க
                </a>
            </div>
        <?php elseif (!$is_english): 
            // Check if English version exists
            $english_check_sql = "SELECT slug, title FROM articles WHERE slug = ? AND status = 'published' LIMIT 1";
            $english_check_stmt = $conn->prepare($english_check_sql);
            $english_version_exists = false;
            
            if ($english_check_stmt) {
                $english_check_stmt->bind_param("s", $english_slug);
                $english_check_stmt->execute();
                $english_result = $english_check_stmt->get_result();
                $english_article = $english_result->fetch_assoc();
                $english_check_stmt->close();
                
                if ($english_article) {
                    $english_version_exists = true;
                }
            }
            
            if ($english_version_exists):
        ?>
            <div style="background: #eff6ff; border: 2px solid #3b82f6; padding: 1.5rem; margin: 2rem 0; border-radius: 8px; text-align: center;">
                <p style="margin: 0 0 1rem 0; font-weight: 600; color: #1e40af;">
                    <i class="fas fa-language"></i> This article is also available in English
                </p>
                <a href="/local-news/<?php echo htmlspecialchars($english_slug); ?>" 
                   style="display: inline-block; background: #1e40af; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 6px; font-weight: 600;">
                    <i class="fas fa-book"></i> Read in English
                </a>
            </div>
        <?php 
            endif;
        endif; 
        ?>
        
        <!-- Share this article -->
        <?php 
        $article_share_url = 'https://myomr.in/local-news/' . $slug;
        $article_share_title = rawurlencode($article['title']);
        $article_share_text = rawurlencode($article['summary'] ?? $article['title']);
        ?>
        <div class="article-share-bar" aria-label="Share this article">
            <span class="article-share-label"><i class="fas fa-share-alt"></i> Share</span>
            <a href="https://wa.me/?text=<?php echo rawurlencode($article['title'] . ' ' . $article_share_url); ?>" target="_blank" rel="noopener noreferrer" class="article-share-btn article-share-wa" title="Share on WhatsApp" aria-label="Share on WhatsApp"><i class="fab fa-whatsapp"></i></a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode($article_share_url); ?>" target="_blank" rel="noopener noreferrer" class="article-share-btn article-share-fb" title="Share on Facebook" aria-label="Share on Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode($article_share_url); ?>&text=<?php echo $article_share_title; ?>" target="_blank" rel="noopener noreferrer" class="article-share-btn article-share-tw" title="Share on X (Twitter)" aria-label="Share on X"><i class="fab fa-x-twitter"></i></a>
            <button type="button" class="article-share-btn article-share-copy" title="Copy link" aria-label="Copy link" data-url="<?php echo htmlspecialchars($article_share_url); ?>"><i class="fas fa-link"></i></button>
        </div>
        <p class="article-whatsapp-cta" style="margin:0.75rem 0 0;font-size:0.9rem;">
            <a href="<?php echo htmlspecialchars(defined('MYOMR_WHATSAPP_GROUP_URL') ? MYOMR_WHATSAPP_GROUP_URL : 'https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi'); ?>" target="_blank" rel="noopener noreferrer" style="color:#25d366;font-weight:600;text-decoration:none;" aria-label="Join our WhatsApp group for more connectivity and updates"><i class="fab fa-whatsapp" style="margin-right:0.35rem;"></i>Join our WhatsApp group for more connectivity and updates</a>
            <span style="color:#9ca3af;margin:0 0.4rem;" aria-hidden="true">|</span>
            <a href="<?php echo htmlspecialchars(defined('MYOMR_FACEBOOK_GROUP_URL') ? MYOMR_FACEBOOK_GROUP_URL : 'https://www.facebook.com/groups/416854920508620'); ?>" target="_blank" rel="noopener noreferrer" style="color:#1877f2;font-weight:600;text-decoration:none;" aria-label="Join our Facebook group for OMR updates" title="Join our Facebook group"><i class="fab fa-facebook-f" style="margin-right:0.35rem;"></i>Join our Facebook group</a>
        </p>

        <?php omr_ad_slot('article-top', '728x90'); ?>

        <!-- In this article (table of contents) - populated by JS from h2s -->
        <nav id="article-toc" class="article-toc" aria-label="In this article" style="display: none;">
            <h2 class="article-toc-title">In this article</h2>
            <ol class="article-toc-list"></ol>
        </nav>
        
        <section class="article-content">
            <?php echo $article['content']; ?>
        </section>

        <?php omr_ad_slot('article-mid', '336x280'); ?>
        <?php omr_amazon_affiliate_spotlight([
            'seed' => $article['slug'] ?? $slug,
            'article_title' => $article['title'] ?? 'News Article',
            'article_category' => $article['category'] ?? '',
            'article_tags' => $article['tags'] ?? '',
        ]); ?>

        <!-- Related Articles Section -->
        <?php
        $related_articles = [];
        $related_sql = "SELECT slug, title, summary, image_path, published_date FROM articles WHERE status = 'published' AND slug != ? AND slug NOT LIKE '%-tamil' ORDER BY published_date DESC LIMIT 6";
        $related_stmt = $conn->prepare($related_sql);
        if ($related_stmt) {
            $related_stmt->bind_param("s", $slug);
            $related_stmt->execute();
            $related_result = $related_stmt->get_result();
            while ($row = $related_result->fetch_assoc()) {
                $related_articles[] = $row;
            }
            $related_stmt->close();
        }
        if ($conn) $conn->close();
        $article_count = count($related_articles);
        ?>
        <?php if ($article_count > 0): ?>
        <script type="application/ld+json">
        {"@context":"https://schema.org","@type":"ItemList","name":"More OMR News","description":"Related local news articles","numberOfItems":<?php echo $article_count; ?>,"itemListElement":[<?php
        foreach ($related_articles as $i => $rel) {
            if ($i > 0) echo ',';
            $url = 'https://myomr.in/local-news/' . $rel['slug'];
            echo '{"@type":"ListItem","position":' . ($i + 1) . ',"url":"' . addslashes($url) . '","name":' . json_encode($rel['title']) . '}';
        }
        ?>]}
        </script>
        <?php endif; ?>
        <div style="margin-top: 3rem; padding: 2rem; background: #f8f9fa; border-radius: 8px;">
            <h3 style="color: var(--myomr-green); margin-bottom: 1.5rem; font-family: 'Playfair Display', serif; font-size: 1.75rem;">More Articles</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                <?php
                if ($article_count > 0) {
                    foreach ($related_articles as $related) {
                        $related_image = !empty($related['image_path']) ? htmlspecialchars($related['image_path']) : '/My-OMR-Logo.png';
                        $related_summary = !empty($related['summary']) ? htmlspecialchars(substr($related['summary'], 0, 120)) . '...' : '';
                        $related_date = !empty($related['published_date']) ? date('M j, Y', strtotime($related['published_date'])) : '';
                        ?>
                        <div style="padding: 1rem; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)';">
                        <a href="/local-news/<?php echo htmlspecialchars($related['slug']); ?>" style="color: inherit; text-decoration: none; display: block;">
                        <?php if (!empty($related['image_path'])): ?>
                        <img src="<?php echo htmlspecialchars($related['image_path']); ?>" alt="<?php echo htmlspecialchars($related['title']); ?>" style="width: 100%; height: 180px; object-fit: cover; border-radius: 5px; margin-bottom: 0.75rem;">
                        <?php endif; ?>
                        <h4 style="color: var(--myomr-green); font-size: 1.1rem; font-weight: 600; margin: 0 0 0.5rem 0; line-height: 1.4;"><?php echo htmlspecialchars($related['title']); ?></h4>
                        <?php if ($related_date): ?><p style="color: #6c757d; font-size: 0.85rem; margin: 0 0 0.5rem 0;"><?php echo $related_date; ?></p><?php endif; ?>
                        <?php if ($related_summary): ?><p style="color: #555; font-size: 0.9rem; line-height: 1.5; margin: 0;"><?php echo $related_summary; ?></p><?php endif; ?>
                        </a>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: #6c757d;"><p>No other articles available at the moment. Check back soon for more news!</p></div>';
                }
                ?>
            </div>
            <?php if ($article_count > 0): ?>
            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="/local-news/" style="display: inline-block; padding: 0.75rem 2rem; background: var(--myomr-green); color: white; text-decoration: none; border-radius: 5px; font-weight: 600; transition: background 0.3s;" onmouseover="this.style.background='#22c55e';" onmouseout="this.style.background='var(--myomr-green)';">View All Articles</a>
            </div>
            <?php endif; ?>
        </div>

        <?php
        $art_cat = isset($article['category']) ? trim((string)$article['category']) : '';
        $art_tags = isset($article['tags']) ? strtolower((string)$article['tags']) : '';
        $is_it_career = in_array($art_cat, ['IT Career', 'Career Tips', 'OMR Jobs', 'Jobs', 'IT'], true)
            || strpos($art_tags, 'it-corridor') !== false
            || strpos($art_tags, 'it-career') !== false;
        if ($is_it_career): ?>
        <div class="mt-4 p-4 rounded" style="background:#f0fdf4;border:1px solid #bbf7d0;">
            <h3 class="h5 mb-2"><i class="fas fa-briefcase me-1"></i> Find jobs on OMR</h3>
            <p class="mb-2 small text-muted">Browse local opportunities in Chennai&rsquo;s IT corridor.</p>
            <a href="/omr-local-job-listings/" class="btn btn-success btn-sm me-2 mb-1">All jobs in OMR</a>
            <a href="/it-jobs-omr-chennai.php" class="btn btn-outline-success btn-sm me-2 mb-1">IT jobs</a>
            <a href="/fresher-jobs-omr-chennai.php" class="btn btn-outline-success btn-sm me-2 mb-1">Fresher jobs</a>
            <a href="/discover-myomr/it-careers-omr.php" class="btn btn-outline-success btn-sm mb-1">More career articles</a>
        </div>
        <?php endif; ?>
        
        <?php if ($show_community_section): ?>
        <!-- MyOMR Community Awareness Section (All Articles) -->
        <div class="community-awareness-section" style="background: linear-gradient(135deg, #14532d 0%, #22c55e 100%); padding: 3rem 2rem; border-radius: 12px; margin: 3rem 0; color: white; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
            <div class="container">
                <!-- WhatsApp Group CTA -->
                <div style="text-align: center; margin-bottom: 2rem;">
                    <a href="<?php echo htmlspecialchars(defined('MYOMR_WHATSAPP_GROUP_URL') ? MYOMR_WHATSAPP_GROUP_URL : 'https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi'); ?>" target="_blank" rel="noopener noreferrer" 
                       style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.5rem; background: #25d366; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 1.1rem; box-shadow: 0 4px 12px rgba(37,211,102,0.4); transition: transform 0.2s, box-shadow 0.2s;"
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(37,211,102,0.5)';"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(37,211,102,0.4)';"
                       aria-label="Join our WhatsApp group for OMR local news">
                        <i class="fab fa-whatsapp" style="font-size: 1.5rem;"></i>
                        Join our WhatsApp Group for more local news related to OMR and vicinity
                    </a>
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-7 mb-4 mb-lg-0">
                        <h2 style="color: white; font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 1.5rem; font-weight: 700;">
                            <i class="fas fa-users"></i> Join the MyOMR Community
                        </h2>
                        <p style="font-size: 1.2rem; line-height: 1.8; margin-bottom: 1.5rem; color: #f0fdf4;">
                            <strong>MyOMR.in</strong> is your local news portal and community website for Old Mahabalipuram Road (OMR), Chennai. We bring you the latest news, events, job opportunities, and community updates from the OMR region.
                        </p>
                        <div style="margin: 2rem 0;">
                            <h3 style="color: white; font-size: 1.5rem; margin-bottom: 1rem;">
                                <i class="fas fa-star"></i> What You'll Get:
                            </h3>
                            <ul style="list-style: none; padding: 0; font-size: 1.1rem; line-height: 2;">
                                <li style="margin-bottom: 0.75rem;">
                                    <i class="fas fa-check-circle" style="color: #dcfce7; margin-right: 0.5rem;"></i>
                                    <strong>Latest Local News:</strong> Stay informed about happenings in OMR
                                </li>
                                <li style="margin-bottom: 0.75rem;">
                                    <i class="fas fa-check-circle" style="color: #dcfce7; margin-right: 0.5rem;"></i>
                                    <strong>Event Updates:</strong> Never miss community events and activities
                                </li>
                                <li style="margin-bottom: 0.75rem;">
                                    <i class="fas fa-check-circle" style="color: #dcfce7; margin-right: 0.5rem;"></i>
                                    <strong>Job Opportunities:</strong> Discover local job openings in OMR
                                </li>
                                <li style="margin-bottom: 0.75rem;">
                                    <i class="fas fa-check-circle" style="color: #dcfce7; margin-right: 0.5rem;"></i>
                                    <strong>Community Features:</strong> Access to listings, directories, and more
                                </li>
                                <li style="margin-bottom: 0.75rem;">
                                    <i class="fas fa-check-circle" style="color: #dcfce7; margin-right: 0.5rem;"></i>
                                    <strong>Election & Civic Info:</strong> Important updates like BLO details, voter information
                                </li>
                            </ul>
                        </div>
                        <p style="font-size: 1.1rem; color: #f0fdf4; margin-top: 1.5rem;">
                            <i class="fas fa-info-circle"></i> <strong>Subscribe now</strong> to receive regular updates delivered straight to your inbox!
                        </p>
                    </div>
                    <div class="col-lg-5">
                        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                            <h3 style="color: var(--myomr-green); font-size: 1.75rem; margin-bottom: 1rem; text-align: center;">
                                <i class="fas fa-bell"></i> Subscribe for Updates
                            </h3>
                            
                            <?php if (isset($_GET['subscribed']) && $_GET['subscribed'] == '1'): ?>
                                <div class="alert alert-success" role="alert" style="background: #dcfce7; border: 2px solid #22c55e; color: #14532d; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                                    <i class="fas fa-check-circle"></i> <strong>Thank you!</strong> You've successfully subscribed. Check your email for confirmation.
                                </div>
                            <?php elseif (isset($_GET['subscribe_error'])): ?>
                                <div class="alert alert-danger" role="alert" style="background: #fee2e2; border: 2px solid #ef4444; color: #991b1b; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                                    <i class="fas fa-exclamation-circle"></i> 
                                    <?php echo isset($_GET['error']) ? htmlspecialchars(urldecode($_GET['error'])) : 'There was an error. Please try again.'; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="../info/process-blo-subscription.php" style="margin-top: 1.5rem;">
                                <input type="hidden" name="source" value="News Article - <?php echo htmlspecialchars($article['title']); ?>">
                                <input type="hidden" name="page_url" value="<?php echo htmlspecialchars($article_url); ?>">
                                
                                <!-- Honeypot field for spam protection -->
                                <input type="text" name="website" style="display: none;" tabindex="-1" autocomplete="off">
                                
                                <div class="form-group mb-3">
                                    <label for="sub_name_article" style="color: #14532d; font-weight: 600; margin-bottom: 0.5rem; display: block;">
                                        <i class="fas fa-user"></i> Name (Optional)
                                    </label>
                                    <input type="text" 
                                           id="sub_name_article" 
                                           name="name" 
                                           class="form-control" 
                                           placeholder="Your name"
                                           style="padding: 0.75rem; border: 2px solid #d1fae5; border-radius: 6px; width: 100%;">
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="sub_email_article" style="color: #14532d; font-weight: 600; margin-bottom: 0.5rem; display: block;">
                                        <i class="fas fa-envelope"></i> Email Address <span style="color: #ef4444;">*</span>
                                    </label>
                                    <input type="email" 
                                           id="sub_email_article" 
                                           name="email" 
                                           class="form-control" 
                                           placeholder="your.email@example.com"
                                           required
                                           style="padding: 0.75rem; border: 2px solid #d1fae5; border-radius: 6px; width: 100%;">
                                </div>
                                
                                <button type="submit" 
                                        name="subscribe_email" 
                                        class="btn btn-lg w-100" 
                                        style="background: var(--myomr-green); color: white; padding: 0.875rem; border: none; border-radius: 6px; font-weight: 600; font-size: 1.1rem; transition: background 0.3s;">
                                    <i class="fas fa-paper-plane"></i> Subscribe Now
                                </button>
                                
                                <p style="font-size: 0.85rem; color: #6b7280; margin-top: 1rem; text-align: center; line-height: 1.5;">
                                    By subscribing, you agree to receive updates from MyOMR.in. 
                                    <br>We respect your privacy and won't spam you.
                                </p>
                            </form>
                            
                            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb; text-align: center;">
                                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.75rem;">
                                    <strong>Follow us on:</strong>
                                </p>
                                <div style="display: flex; justify-content: center; gap: 1rem;">
                                    <a href="https://www.facebook.com/MyOMR.in" target="_blank" style="color: #1877f2; font-size: 1.5rem;" title="Facebook">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                    <a href="https://www.instagram.com/myomr.in" target="_blank" style="color: #e4405f; font-size: 1.5rem;" title="Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a href="https://x.com/MyomrNews" target="_blank" style="color: #000000; font-size: 1.5rem;" title="Twitter/X">
                                        <i class="fab fa-x-twitter"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </article>
</div>

<?php omr_footer(); ?>
<link rel="stylesheet" href="/assets/css/footer.css">
<script>
(function() {
    var content = document.querySelector('.article-content');
    if (!content) return;
    var h2s = content.querySelectorAll('h2');
    if (h2s.length < 3) return;
    var toc = document.getElementById('article-toc');
    var list = toc ? toc.querySelector('.article-toc-list') : null;
    if (!toc || !list) return;
    h2s.forEach(function(h2, i) {
        var id = 'section-' + (i + 1);
        if (!h2.id) h2.id = id;
        var li = document.createElement('li');
        var a = document.createElement('a');
        a.href = '#' + h2.id;
        a.textContent = h2.textContent;
        li.appendChild(a);
        list.appendChild(li);
    });
    toc.style.display = 'block';
})();
document.querySelectorAll('.article-share-copy').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var url = this.getAttribute('data-url');
        if (!url) return;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(function() {
                btn.classList.add('copied');
                btn.setAttribute('title', 'Copied!');
                setTimeout(function() { btn.classList.remove('copied'); btn.setAttribute('title', 'Copy link'); }, 2000);
            });
        } else {
            var ta = document.createElement('textarea');
            ta.value = url;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            btn.classList.add('copied');
            btn.setAttribute('title', 'Copied!');
            setTimeout(function() { btn.classList.remove('copied'); btn.setAttribute('title', 'Copy link'); }, 2000);
        }
    });
});

document.querySelectorAll('.js-affiliate-click').forEach(function(link) {
    link.addEventListener('click', function() {
        if (typeof gtag !== 'function') return;
        gtag('event', 'affiliate_link_click', {
            event_category: 'Affiliate',
            event_label: this.getAttribute('data-affiliate-id') || 'amazon-affiliate',
            affiliate_network: this.getAttribute('data-affiliate-network') || 'amazon',
            affiliate_position: this.getAttribute('data-affiliate-position') || '',
            article_title: this.getAttribute('data-affiliate-article') || ''
        });
    });
});
</script>
</body>
</html>

