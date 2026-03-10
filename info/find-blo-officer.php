<?php
/**
 * Election BLO Search Page for AC Shozhinganallur
 * Allows OMR residents to search for their Block Level Officer details
 */

require_once '../core/omr-connect.php';

// Get search parameters
$search_location = isset($_GET['location']) ? trim($_GET['location']) : '';
$search_polling_station = isset($_GET['polling_station']) ? trim($_GET['polling_station']) : '';

// Build query
$where = ["ac_no = 27", "ac_name = 'Shozhinganallur'"];
$params = [];
$types = '';

if (!empty($search_location)) {
    $where[] = "location LIKE ?";
    $params[] = '%' . $search_location . '%';
    $types .= 's';
}

if (!empty($search_polling_station)) {
    $where[] = "polling_station_name LIKE ?";
    $params[] = '%' . $search_polling_station . '%';
    $types .= 's';
}

$where_clause = implode(' AND ', $where);
$sql = "SELECT * FROM omr_election_blo WHERE $where_clause ORDER BY part_no ASC, location ASC LIMIT 100";

// Execute query
$results = [];
if ($conn) {
    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            if (!empty($types)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            $stmt->close();
        }
    } else {
        // No filters - show all
        $result = $conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
        }
    }
}

// Get unique locations for dropdown
$locations_sql = "SELECT DISTINCT location FROM omr_election_blo WHERE ac_no = 27 AND location IS NOT NULL ORDER BY location ASC";
$locations = [];
if ($conn) {
    $loc_result = $conn->query($locations_sql);
    if ($loc_result) {
        while ($row = $loc_result->fetch_assoc()) {
            if (!empty($row['location'])) {
                $locations[] = $row['location'];
            }
        }
    }
}

// Get unique polling stations for dropdown
$polling_stations_sql = "SELECT DISTINCT polling_station_name FROM omr_election_blo WHERE ac_no = 27 AND polling_station_name IS NOT NULL ORDER BY polling_station_name ASC";
$polling_stations = [];
if ($conn) {
    $ps_result = $conn->query($polling_stations_sql);
    if ($ps_result) {
        while ($row = $ps_result->fetch_assoc()) {
            if (!empty($row['polling_station_name'])) {
                $polling_stations[] = $row['polling_station_name'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $page_title = "Find Your BLO Officer - AC Shozhinganallur | MyOMR";
    $page_description = "Search for Block Level Officer (BLO) details in Shozhinganallur Assembly Constituency. Find your polling station BLO contact details for election-related queries.";
    $page_keywords = "BLO search, Shozhinganallur, election officer, polling station, AC 27, OMR election";
    include '../components/meta.php';
    include '../components/analytics.php';
    include '../components/head-resources.php';
    ?>
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://myomr.in/info/find-blo-officer.php">
    
    <!-- Robots Meta -->
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://myomr.in/info/find-blo-officer.php">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="og:image" content="https://myomr.in/My-OMR-Logo.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="MyOMR.in">
    <meta property="og:locale" content="en_IN">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://myomr.in/info/find-blo-officer.php">
    <meta property="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="twitter:image" content="https://myomr.in/My-OMR-Logo.png">
    <meta property="twitter:site" content="@MyomrNews">
    <meta property="twitter:creator" content="@MyomrNews">
    
    <!-- Additional SEO Meta -->
    <meta name="geo.region" content="IN-TN">
    <meta name="geo.placename" content="Chennai">
    <meta name="geo.position" content="12.9352;80.2275">
    <meta name="ICBM" content="12.9352, 80.2275">
    
    <!-- Structured Data (JSON-LD) for Rich Snippets -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "<?php echo addslashes($page_title); ?>",
      "description": "<?php echo addslashes($page_description); ?>",
      "url": "https://myomr.in/info/find-blo-officer.php",
      "inLanguage": "en-IN",
      "isPartOf": {
        "@type": "WebSite",
        "name": "MyOMR.in",
        "url": "https://myomr.in"
      },
      "about": {
        "@type": "Thing",
        "name": "Block Level Officer",
        "description": "Election Commission Block Level Officer for voter registration and electoral roll management"
      },
      "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
          {
            "@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "https://myomr.in/"
          },
          {
            "@type": "ListItem",
            "position": 2,
            "name": "Info",
            "item": "https://myomr.in/info/"
          },
          {
            "@type": "ListItem",
            "position": 3,
            "name": "BLO Search",
            "item": "https://myomr.in/info/find-blo-officer.php"
          }
        ]
      },
      "potentialAction": {
        "@type": "SearchAction",
        "target": {
          "@type": "EntryPoint",
          "urlTemplate": "https://myomr.in/info/find-blo-officer.php?location={search_term_string}"
        },
        "query-input": "required name=search_term_string"
      },
      "mainEntity": {
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
        }
      }
    }
    </script>
    
    <!-- FAQPage Schema for Common Questions -->
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
            "text": "You can search for your BLO using this tool by selecting your location or polling station from the dropdown menus. The search will display all BLO officers matching your criteria with their contact details including mobile number, polling station address, and part number."
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
        }
      ]
    }
    </script>
    
    <!-- Organization Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "MyOMR.in",
      "url": "https://myomr.in",
      "logo": "https://myomr.in/My-OMR-Logo.png",
      "description": "Local news portal and community website for Old Mahabalipuram Road (OMR), Chennai",
      "sameAs": [
        "https://www.facebook.com/MyOMR.in",
        "https://www.instagram.com/myomr.in",
        "https://x.com/MyomrNews"
      ],
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+91-9445088028",
        "contactType": "Customer Service",
        "email": "myomrnews@gmail.com",
        "areaServed": "IN",
        "availableLanguage": ["en", "ta"]
      }
    }
    </script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <style>
        :root {
            --myomr-green: #14532d;
            --myomr-light-green: #22c55e;
        }
        
        .blo-search-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 1rem;
            font-family: 'Poppins', sans-serif;
        }
        
        .search-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .search-box h2 {
            color: var(--myomr-green);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .search-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .search-form .form-group {
            margin-bottom: 0;
        }
        
        .search-form label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--myomr-green);
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .search-form input,
        .search-form select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #d1fae5;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .search-form input:focus,
        .search-form select:focus {
            outline: none;
            border-color: var(--myomr-light-green);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }
        
        .btn-search {
            background: var(--myomr-green);
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            align-self: end;
        }
        
        .btn-search:hover {
            background: var(--myomr-light-green);
        }
        
        .results-section {
            margin-top: 2rem;
        }
        
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .results-count {
            color: var(--myomr-green);
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .blo-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: box-shadow 0.3s, transform 0.2s;
        }
        
        .blo-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .blo-card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .blo-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--myomr-green);
            margin: 0;
        }
        
        .part-badge {
            background: var(--myomr-light-green);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .blo-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .detail-item {
            display: flex;
            align-items: start;
            gap: 0.5rem;
        }
        
        .detail-item i {
            color: var(--myomr-light-green);
            margin-top: 0.25rem;
            min-width: 20px;
        }
        
        .detail-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .detail-value {
            color: #1f2937;
            font-size: 0.95rem;
        }
        
        .contact-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }
        
        .btn-contact {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-call {
            background: var(--myomr-green);
            color: white;
        }
        
        .btn-call:hover {
            background: var(--myomr-light-green);
            color: white;
        }
        
        .no-results {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }
        
        .no-results i {
            font-size: 3rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }
        
        .info-alert {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 2rem;
        }
        
        .community-awareness-section {
            margin: 2rem 0;
        }
        
        @media (max-width: 768px) {
            .blo-search-container {
                padding: 1rem 0.5rem;
            }
            
            .search-box {
                padding: 1.5rem;
            }
            
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .blo-card-header {
                flex-direction: column;
            }
            
            .community-awareness-section {
                padding: 2rem 1rem !important;
            }
            
            .community-awareness-section h2 {
                font-size: 1.75rem !important;
            }
            
            .community-awareness-section p {
                font-size: 1rem !important;
            }
            
            .community-awareness-section ul {
                font-size: 0.95rem !important;
            }
        }
    </style>
</head>
<body>
    <?php include '../components/main-nav.php'; ?>
    
    <div class="blo-search-container">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" style="margin-bottom: 1.5rem;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/info/">Info</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BLO Search</li>
                </ol>
            </nav>
            
            <!-- Page Header -->
            <div class="text-center mb-4">
                <h1 style="color: var(--myomr-green); font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 0.5rem;">
                    Find Your Block Level Officer (BLO)
                </h1>
                <p style="color: #6b7280; font-size: 1.1rem;">
                    Assembly Constituency 27 - Shozhinganallur
                </p>
            </div>
            
            <!-- Info Alert -->
            <div class="info-alert">
                <strong><i class="fas fa-info-circle"></i> Information:</strong> 
                Use this search tool to find your Block Level Officer (BLO) contact details for the Special Summary Revision (SSR) of electoral rolls. 
                Search by location or polling station.
            </div>
            
            <!-- Search Form -->
            <div class="search-box">
                <h2><i class="fas fa-search"></i> Search BLO Details</h2>
                <form method="GET" action="" class="search-form">
                    <div class="form-group">
                        <label for="location"><i class="fas fa-map-marker-alt"></i> Filter by Location</label>
                        <select name="location" id="location" class="form-control">
                            <option value="">All Locations</option>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?php echo htmlspecialchars($loc); ?>" <?php echo ($search_location === $loc) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($loc); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="polling_station"><i class="fas fa-school"></i> Filter by Polling Station</label>
                        <select name="polling_station" id="polling_station" class="form-control">
                            <option value="">All Polling Stations</option>
                            <?php foreach ($polling_stations as $ps): ?>
                                <option value="<?php echo htmlspecialchars($ps); ?>" <?php echo ($search_polling_station === $ps) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ps); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn-search">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <?php if (!empty($search_location) || !empty($search_polling_station)): ?>
                            <a href="find-blo-officer.php" class="btn btn-outline-secondary" style="margin-left: 0.5rem; padding: 0.75rem 1.5rem;">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            
            <!-- Results Section -->
            <div class="results-section">
                <div class="results-header">
                    <div class="results-count">
                        <?php if (count($results) > 0): ?>
                            <i class="fas fa-list"></i> Found <?php echo count($results); ?> BLO record(s)
                        <?php else: ?>
                            <i class="fas fa-info-circle"></i> No results found
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (count($results) > 0): ?>
                    <div class="blo-results">
                        <?php foreach ($results as $blo): ?>
                            <div class="blo-card">
                                <div class="blo-card-header">
                                    <div>
                                        <h3 class="blo-name">
                                            <i class="fas fa-user-tie"></i> <?php echo htmlspecialchars($blo['blo_name']); ?>
                                        </h3>
                                        <?php if (!empty($blo['blo_designation'])): ?>
                                            <small style="color: #6b7280;"><?php echo htmlspecialchars($blo['blo_designation']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($blo['part_no'])): ?>
                                        <span class="part-badge">Part <?php echo (int)$blo['part_no']; ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="blo-details">
                                    <?php if (!empty($blo['polling_station_name'])): ?>
                                        <div class="detail-item">
                                            <i class="fas fa-school"></i>
                                            <div>
                                                <div class="detail-label">Polling Station</div>
                                                <div class="detail-value"><?php echo htmlspecialchars($blo['polling_station_name']); ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($blo['location'])): ?>
                                        <div class="detail-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <div>
                                                <div class="detail-label">Location</div>
                                                <div class="detail-value"><?php echo htmlspecialchars($blo['location']); ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($blo['section_no'])): ?>
                                        <div class="detail-item">
                                            <i class="fas fa-door-open"></i>
                                            <div>
                                                <div class="detail-label">Section</div>
                                                <div class="detail-value"><?php echo htmlspecialchars($blo['section_no']); ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($blo['blo_mobile'])): ?>
                                        <div class="detail-item">
                                            <i class="fas fa-phone"></i>
                                            <div>
                                                <div class="detail-label">Mobile</div>
                                                <div class="detail-value">
                                                    <a href="tel:<?php echo htmlspecialchars($blo['blo_mobile']); ?>" style="color: var(--myomr-green); text-decoration: none;">
                                                        <?php echo htmlspecialchars($blo['blo_mobile']); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($blo['address'])): ?>
                                        <div class="detail-item" style="grid-column: 1 / -1;">
                                            <i class="fas fa-address-card"></i>
                                            <div>
                                                <div class="detail-label">Address</div>
                                                <div class="detail-value"><?php echo htmlspecialchars($blo['address']); ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($blo['blo_mobile'])): ?>
                                    <div class="contact-buttons">
                                        <a href="tel:<?php echo htmlspecialchars($blo['blo_mobile']); ?>" class="btn-contact btn-call">
                                            <i class="fas fa-phone"></i> Call Now
                                        </a>
                                        <a href="https://wa.me/91<?php echo preg_replace('/[^0-9]/', '', $blo['blo_mobile']); ?>" 
                                           target="_blank" 
                                           class="btn-contact" 
                                           style="background: #25D366; color: white;">
                                            <i class="fab fa-whatsapp"></i> WhatsApp
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h3>No BLO records found</h3>
                        <p>Try adjusting your search criteria or <a href="find-blo-officer.php">view all records</a>.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- MyOMR Community Awareness Section -->
            <div class="community-awareness-section" style="background: linear-gradient(135deg, #14532d 0%, #22c55e 100%); padding: 3rem 2rem; border-radius: 12px; margin: 3rem 0; color: white; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
                <div class="container">
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
                                
                                <form method="POST" action="process-blo-subscription.php" style="margin-top: 1.5rem;">
                                    <input type="hidden" name="source" value="BLO Search Page">
                                    <input type="hidden" name="page_url" value="<?php echo htmlspecialchars('https://myomr.in/info/find-blo-officer.php' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')); ?>">
                                    
                                    <!-- Honeypot field for spam protection -->
                                    <input type="text" name="website" style="display: none;" tabindex="-1" autocomplete="off">
                                    
                                    <div class="form-group mb-3">
                                        <label for="sub_name" style="color: #14532d; font-weight: 600; margin-bottom: 0.5rem; display: block;">
                                            <i class="fas fa-user"></i> Name (Optional)
                                        </label>
                                        <input type="text" 
                                               id="sub_name" 
                                               name="name" 
                                               class="form-control" 
                                               placeholder="Your name"
                                               style="padding: 0.75rem; border: 2px solid #d1fae5; border-radius: 6px; width: 100%;">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="sub_email" style="color: #14532d; font-weight: 600; margin-bottom: 0.5rem; display: block;">
                                            <i class="fas fa-envelope"></i> Email Address <span style="color: #ef4444;">*</span>
                                        </label>
                                        <input type="email" 
                                               id="sub_email" 
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
        </div>
    </div>
    
    <?php include '../components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php if (isset($_GET['subscribed']) && $_GET['subscribed'] === '1'): ?>
    <script>
    (function() {
      if (typeof gtag !== 'function') return;
      gtag('event', 'subscribe', {
        'conversion_type': 'blo_page_subscription'
      });
    })();
    </script>
    <?php endif; ?>

</body>
</html>
<?php
if ($conn) {
    $conn->close();
}
?>

