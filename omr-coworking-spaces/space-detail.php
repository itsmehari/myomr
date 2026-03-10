<?php
/**
 * MyOMR Coworking Spaces Portal - Space Detail Page
 * Individual space listing view with inquiry form
 * 
 * @package MyOMR Coworking Spaces
 * @version 1.0.0
 */

// Enable error reporting for development
require_once __DIR__ . '/includes/error-reporting.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include helper functions
require_once __DIR__ . '/includes/space-functions.php';

// Load database connection directly
require_once __DIR__ . '/../core/omr-connect.php';
global $conn;

// Verify connection
if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
    header("HTTP/1.0 500 Internal Server Error");
    echo "<h1>500 - Database Error</h1>";
    echo "<p>Database connection failed. Please try again later.</p>";
    exit;
}

// Get space ID from URL
$space_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($space_id <= 0) {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 - Space Not Found</h1>";
    echo "<p>Invalid space ID.</p>";
    exit;
}

// Get space details using direct query
$space = null;

// Try direct query first
$directQuery = "SELECT s.*, o.full_name as owner_name, o.email as owner_email, o.phone as owner_phone
                FROM coworking_spaces s
                LEFT JOIN space_owners o ON s.owner_id = o.id
                WHERE s.id = {$space_id} AND s.status = 'active'";

$directResult = $conn->query($directQuery);

if ($directResult && $directResult->num_rows > 0) {
    $space = $directResult->fetch_assoc();
} else {
    // Fallback: Try without status check
    $fallbackQuery = "SELECT s.*, o.full_name as owner_name, o.email as owner_email, o.phone as owner_phone
                      FROM coworking_spaces s
                      LEFT JOIN space_owners o ON s.owner_id = o.id
                      WHERE s.id = {$space_id}";
    
    $fallbackResult = $conn->query($fallbackQuery);
    if ($fallbackResult && $fallbackResult->num_rows > 0) {
        $space = $fallbackResult->fetch_assoc();
    } else {
        // Last fallback
        $lastQuery = "SELECT s.*, o.full_name as owner_name, o.email as owner_email, o.phone as owner_phone
                      FROM coworking_spaces s
                      LEFT JOIN space_owners o ON s.owner_id = o.id
                      WHERE s.id = {$space_id} AND LOWER(TRIM(s.status)) = 'active'";
        
        $lastResult = $conn->query($lastQuery);
        if ($lastResult && $lastResult->num_rows > 0) {
            $space = $lastResult->fetch_assoc();
        }
    }
}

if (!$space) {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 - Space Not Found</h1>";
    echo "<p>The workspace you're looking for doesn't exist or has been removed.</p>";
    exit;
}

// Increment view count
incrementSpaceViews($space_id);

// Get space photos
$photos = getSpacePhotos($space_id);

// Get related spaces
$related_spaces = getRelatedSpaces($space_id, $space['locality'], 3);

// SEO Meta
$page_title = htmlspecialchars($space['space_name']) . " in " . htmlspecialchars($space['locality']) . " | MyOMR";
$clean_description = trim(strip_tags($space['brief_overview'] ?? $space['full_description'] ?? ''));
$page_description = strlen($clean_description) > 155
    ? substr($clean_description, 0, 155) . '...'
    : $clean_description;
$canonical_url = "https://myomr.in/omr-coworking-spaces/space-detail.php?id=" . $space_id;
$og_image = "https://myomr.in/omr-coworking-spaces/" . htmlspecialchars($space['featured_image'] ?? 'My-OMR-Logo.png');

// Parse JSON fields
$space_types = json_decode($space['space_types'] ?? '[]', true);
$amenities = json_decode($space['amenities'] ?? '[]', true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($space['space_name']); ?>, <?php echo htmlspecialchars($space['locality']); ?>, coworking OMR, shared workspace OMR">
    <link rel="canonical" href="<?php echo $canonical_url; ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars($space['space_name']); ?>">
    <meta property="og:description" content="<?php echo $page_description; ?>">
    <meta property="og:url" content="<?php echo $canonical_url; ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?php echo $og_image; ?>">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($space['space_name']); ?>">
    <meta name="twitter:description" content="<?php echo $page_description; ?>">
    <meta name="twitter:image" content="<?php echo $og_image; ?>">
    
    <!-- Google Analytics -->
    <?php include '../components/analytics.php'; ?>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/core.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/coworking-spaces.css">
    <!-- Universal Footer Styles -->
    <link rel="stylesheet" href="/assets/css/footer.css">
    
    <!-- Structured Data -->
    <?php echo generateSpaceSchema($space); ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://myomr.in/"},
        {"@type": "ListItem", "position": 2, "name": "Coworking Spaces in OMR", "item": "https://myomr.in/omr-coworking-spaces/"},
        {"@type": "ListItem", "position": 3, "name": <?php echo json_encode($space['space_name']); ?>, "item": <?php echo json_encode($canonical_url); ?>}
      ]
    }
    </script>
    
    <style>
        .space-header-section {
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        .space-header-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .space-logo {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .space-info-badge {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            display: inline-block;
            margin-right: 1rem;
            margin-bottom: 0.5rem;
        }
        .space-content-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .space-content-section h2 {
            color: #008552;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
        }
        .breadcrumb {
            background: #f3f4f6;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        .breadcrumb-item a {
            color: #008552;
            text-decoration: none;
        }
        .breadcrumb-item a:hover {
            text-decoration: underline;
        }
        .inquiry-btn-container {
            position: sticky;
            top: 20px;
        }
        .inquiry-btn-container .btn {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .info-box {
            background: #e7f5e7;
            border-left: 4px solid #22c55e;
            padding: 1.5rem;
            border-radius: 5px;
            margin: 1.5rem 0;
        }
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            background: #f3f4f6;
            border-radius: 6px;
        }
        .amenity-item i {
            color: #008552;
        }
        .price-table {
            width: 100%;
            border-collapse: collapse;
        }
        .price-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .price-table td:first-child {
            font-weight: 600;
            color: #6b7280;
        }
        .price-table td:last-child {
            font-weight: 700;
            color: #008552;
            text-align: right;
        }
        .gallery-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .gallery-img:hover {
            transform: scale(1.05);
        }
        @media (max-width: 768px) {
            .inquiry-btn-container {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                padding: 1rem;
                box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
                z-index: 1000;
            }
        }
    </style>
</head>
<body class="modern-page">

<!-- Navigation -->
<?php require_once '../components/main-nav.php'; ?>

<!-- Skip Link -->
<a href="#main-content" class="skip-link">Skip to main content</a>

<!-- Breadcrumb -->
<div class="breadcrumb-container">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="index.php">Coworking Spaces in OMR</a></li>
                <li class="breadcrumb-item"><a href="#"><?php echo htmlspecialchars($space['locality']); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($space['space_name']); ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Space Header -->
<section class="space-header-section hero-modern">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="space-logo">
                    <i class="fas fa-building"></i>
                </div>
                <h1><?php echo htmlspecialchars($space['space_name']); ?></h1>
                <h2 class="h4 mb-4">Coworking Space in <?php echo htmlspecialchars($space['locality']); ?></h2>
                
                <div class="space-meta">
                    <span class="space-info-badge">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <?php echo htmlspecialchars($space['locality']); ?>
                    </span>
                    <?php if (!empty($space['hot_desk_monthly'])): ?>
                    <span class="space-info-badge">
                        <i class="fas fa-rupee-sign me-2"></i>
                        Starting from ₹<?php echo number_format($space['hot_desk_monthly']); ?>/month
                    </span>
                    <?php endif; ?>
                    <?php if (!empty($space['verification_status']) && $space['verification_status'] === 'verified'): ?>
                    <span class="space-info-badge badge-verified">
                        <i class="fas fa-check-circle me-1"></i> Verified
                    </span>
                    <?php endif; ?>
                    <span class="space-info-badge">
                        <i class="fas fa-eye me-2"></i>
                        <?php echo number_format($space['views_count']); ?> views
                    </span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="inquiry-btn-container">
                    <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                        <i class="fas fa-paper-plane me-2"></i>Send Inquiry
                    </button>
                    <p class="text-center mt-2 mb-0 small">Contact owner directly</p>
                    <hr class="my-3">
                    <div class="text-center">
                        <small class="text-white-50 d-block mb-2">Share this space</small>
                        <button class="btn btn-sm btn-outline-light me-2" onclick="shareOnWhatsApp()">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-light me-2" onclick="shareViaEmail()">
                            <i class="fas fa-envelope"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-light" onclick="shareViaFacebook()">
                            <i class="fab fa-facebook"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<main id="main-content">
    <div class="container">
        <div class="row">
            <!-- Space Content -->
            <div class="col-lg-8">

                <!-- Overview Section -->
                <div class="space-content-section">
                    <h2><i class="fas fa-file-alt me-2"></i>Overview</h2>
                    <div class="space-description">
                        <?php if (!empty($space['brief_overview'])): ?>
                            <p class="lead mb-3"><?php echo nl2br(htmlspecialchars($space['brief_overview'])); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($space['full_description'])): ?>
                            <p><?php echo nl2br(htmlspecialchars($space['full_description'])); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Photos Gallery -->
                <?php if (!empty($photos)): ?>
                <div class="space-content-section">
                    <h2><i class="fas fa-images me-2"></i>Photos</h2>
                    <div class="row g-3">
                        <?php foreach ($photos as $index => $photo): ?>
                            <div class="col-md-6 col-lg-4">
                                <img src="<?php echo htmlspecialchars($photo['photo_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($photo['caption'] ?? $space['space_name']); ?>"
                                     class="gallery-img"
                                     loading="lazy"
                                     decoding="async"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#galleryModal"
                                     onclick="openGallery(<?php echo $index; ?>)">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Amenities -->
                <?php if (!empty($amenities) && is_array($amenities)): ?>
                <div class="space-content-section">
                    <h2><i class="fas fa-check-circle me-2"></i>Amenities & Facilities</h2>
                    <div class="amenities-grid">
                        <?php foreach ($amenities as $amenity): ?>
                            <div class="amenity-item">
                                <i class="fas fa-check-circle"></i>
                                <span><?php echo htmlspecialchars($amenity); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Pricing -->
                <?php if (!empty($space['hot_desk_monthly'])): ?>
                <div class="space-content-section">
                    <h2><i class="fas fa-rupee-sign me-2"></i>Pricing</h2>
                    <table class="price-table">
                        <?php if (!empty($space['day_pass_price'])): ?>
                        <tr>
                            <td>Day Pass:</td>
                            <td>₹<?php echo number_format($space['day_pass_price']); ?>/day</td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($space['hot_desk_monthly'])): ?>
                        <tr>
                            <td>Hot Desk (Monthly):</td>
                            <td>₹<?php echo number_format($space['hot_desk_monthly']); ?>/month</td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($space['dedicated_desk_monthly'])): ?>
                        <tr>
                            <td>Dedicated Desk (Monthly):</td>
                            <td>₹<?php echo number_format($space['dedicated_desk_monthly']); ?>/month</td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($space['private_cabin_monthly'])): ?>
                        <tr>
                            <td>Private Cabin (Monthly):</td>
                            <td>₹<?php echo number_format($space['private_cabin_monthly']); ?>/month</td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($space['team_space_monthly'])): ?>
                        <tr>
                            <td>Team Space (Monthly):</td>
                            <td>₹<?php echo number_format($space['team_space_monthly']); ?>/month</td>
                        </tr>
                        <?php endif; ?>
                    </table>
                    
                    <?php if (!empty($space['special_offers'])): ?>
                        <div class="mt-3 p-3 bg-light rounded">
                            <h5>Special Offers:</h5>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($space['special_offers'])); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Operating Hours -->
                <?php if (!empty($space['operating_hours'])): ?>
                <div class="space-content-section">
                    <h2><i class="fas fa-clock me-2"></i>Operating Hours</h2>
                    <div class="space-operating-hours">
                        <?php echo nl2br(htmlspecialchars($space['operating_hours'])); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Location -->
                <?php if (!empty($space['address'])): ?>
                <div class="space-content-section">
                    <h2><i class="fas fa-map-marker-alt me-2"></i>Location</h2>
                    <p class="mb-3">
                        <i class="fas fa-location-dot me-2 text-danger"></i>
                        <?php echo nl2br(htmlspecialchars($space['address'])); ?>
                    </p>
                    
                    <?php if (!empty($space['landmark'])): ?>
                        <p class="mb-3"><strong>Landmark:</strong> <?php echo htmlspecialchars($space['landmark']); ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($space['latitude']) && !empty($space['longitude'])): ?>
                        <div class="mt-3">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3886.9488!2d<?php echo $space['longitude']; ?>!3d<?php echo $space['latitude']; ?>!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z!5e0!3m2!1sen!2sin!4v1234567890"
                                width="100%" 
                                height="300" 
                                style="border:0; border-radius: 8px;" 
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Important Information -->
                <div class="info-box">
                    <h5><i class="fas fa-info-circle me-2"></i>Important Information</h5>
                    <ul class="mb-0">
                        <li>This inquiry goes directly to the space owner</li>
                        <li>The owner will contact you to discuss details and arrange a visit</li>
                        <li>Always visit the workspace in person before making a decision</li>
                        <li>Verify all amenities and facilities during your visit</li>
                    </ul>
                </div>
                
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                
                <!-- Inquiry Button (Mobile) -->
                <div class="inquiry-btn-container d-block d-lg-none mb-4">
                    <button class="btn btn-success btn-lg w-100" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                        <i class="fas fa-paper-plane me-2"></i>Send Inquiry
                    </button>
                </div>
                
                <!-- Owner Information -->
                <div class="space-content-section">
                    <h3 class="h5 mb-3"><i class="fas fa-user me-2"></i>Space Owner</h3>
                    <p class="fw-semibold mb-2"><?php echo htmlspecialchars($space['owner_name']); ?></p>
                    
                    <?php if (!empty($space['contact_phone'])): ?>
                    <p class="text-muted mb-2">
                        <i class="fas fa-phone me-2"></i>
                        <a href="tel:<?php echo htmlspecialchars($space['contact_phone']); ?>" class="text-decoration-none">
                            <?php echo htmlspecialchars($space['contact_phone']); ?>
                        </a>
                    </p>
                    <?php endif; ?>
                    
                    <?php if (!empty($space['contact_email'])): ?>
                    <p class="text-muted mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        <a href="mailto:<?php echo htmlspecialchars($space['contact_email']); ?>" class="text-decoration-none">
                            <?php echo htmlspecialchars($space['contact_email']); ?>
                        </a>
                    </p>
                    <?php endif; ?>
                    
                    <?php if (!empty($space['contact_whatsapp'])): ?>
                    <p class="text-muted mb-0">
                        <i class="fab fa-whatsapp me-2"></i>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $space['contact_whatsapp']); ?>" 
                           class="text-decoration-none text-success" target="_blank">
                            <?php echo htmlspecialchars($space['contact_whatsapp']); ?>
                        </a>
                    </p>
                    <?php endif; ?>
                </div>
                
                <!-- Space Details -->
                <div class="space-content-section">
                    <h3 class="h5 mb-3"><i class="fas fa-info-circle me-2"></i>Space Details</h3>
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">Location:</td>
                            <td class="fw-semibold"><?php echo htmlspecialchars($space['locality']); ?></td>
                        </tr>
                        <?php if (!empty($space['total_capacity'])): ?>
                        <tr>
                            <td class="text-muted">Capacity:</td>
                            <td class="fw-semibold"><?php echo $space['total_capacity']; ?> seats</td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($space['operating_hours'])): ?>
                        <tr>
                            <td class="text-muted">Operating Hours:</td>
                            <td class="fw-semibold"><?php echo htmlspecialchars($space['operating_hours']); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
                
                <!-- Share Buttons -->
                <div class="space-content-section">
                    <h3 class="h5 mb-3"><i class="fas fa-share-alt me-2"></i>Share This Space</h3>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-success" onclick="shareOnWhatsApp()">
                            <i class="fab fa-whatsapp me-2"></i>Share on WhatsApp
                        </button>
                        <button class="btn btn-outline-primary" onclick="shareViaEmail()">
                            <i class="fas fa-envelope me-2"></i>Share via Email
                        </button>
                        <button class="btn btn-outline-secondary" onclick="shareViaFacebook()">
                            <i class="fab fa-facebook me-2"></i>Share on Facebook
                        </button>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Related Spaces -->
        <?php if (!empty($related_spaces)): ?>
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="h3 mb-4"><i class="fas fa-building me-2"></i>Similar Workspaces in <?php echo htmlspecialchars($space['locality']); ?></h2>
                
                <div class="row">
                    <?php foreach ($related_spaces as $related): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="space-card card-modern h-100 position-relative">
                            <?php if (!empty($related['featured'])): ?>
                                <span class="badge-verified position-absolute top-0 end-0 m-2">
                                    <i class="fas fa-star me-1"></i> Featured
                                </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($related['featured_image'])): ?>
                                <img src="<?php echo htmlspecialchars($related['featured_image']); ?>" 
                                     alt="<?php echo htmlspecialchars($related['space_name']); ?>" 
                                     class="space-card-image"
                                     loading="lazy"
                                     decoding="async">
                            <?php else: ?>
                                <div class="space-card-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-building fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            
                            <header class="space-card-header">
                                <h3 class="h6 mb-1">
                                    <a href="space-detail.php?id=<?php echo $related['id']; ?>" 
                                       class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($related['space_name']); ?>
                                    </a>
                                </h3>
                                <div class="space-card-location small">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?php echo htmlspecialchars($related['locality']); ?>
                                </div>
                            </header>
                            
                            <div class="space-card-body">
                                <div class="space-card-price mb-3">
                                    ₹<?php echo number_format($related['hot_desk_monthly'] ?? 0); ?>/month
                                </div>
                                <a href="space-detail.php?id=<?php echo $related['id']; ?>" 
                                   class="btn btn-sm btn-primary w-100">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-4">
                    <a href="index.php?locality=<?php echo urlencode($space['locality']); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>View All in <?php echo htmlspecialchars($space['locality']); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
    </div>
</main>

<!-- Inquiry Modal -->
<div class="modal fade" id="inquiryModal" tabindex="-1" aria-labelledby="inquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inquiryModalLabel">
                    <i class="fas fa-paper-plane me-2"></i>Send Inquiry to Owner
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="inquiryForm" method="POST" action="process-inquiry.php">
                    <input type="hidden" name="space_id" value="<?php echo $space_id; ?>">
                    <?php
                    if (session_status() === PHP_SESSION_NONE) { session_start(); }
                    if (empty($_SESSION['csrf_token'])) {
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    }
                    ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="user_name" class="form-label required-field">Your Name</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="user_email" class="form-label required-field">Email</label>
                            <input type="email" class="form-control" id="user_email" name="user_email" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="user_phone" class="form-label required-field">Phone</label>
                            <input type="tel" class="form-control" id="user_phone" name="user_phone" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="user_location" class="form-label">Current Location</label>
                            <input type="text" class="form-control" id="user_location" name="user_location">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="interested_space_type" class="form-label">Interested Space Type</label>
                        <select class="form-select" id="interested_space_type" name="interested_space_type">
                            <option value="">Select</option>
                            <option value="Day Pass">Day Pass</option>
                            <option value="Hot Desk">Hot Desk</option>
                            <option value="Dedicated Desk">Dedicated Desk</option>
                            <option value="Private Cabin">Private Cabin</option>
                            <option value="Team Space">Team Space</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="duration" class="form-label">Duration</label>
                            <select class="form-select" id="duration" name="duration">
                                <option value="">Select</option>
                                <option value="1 day">1 day</option>
                                <option value="1 week">1 week</option>
                                <option value="1 month">1 month</option>
                                <option value="3 months">3 months</option>
                                <option value="6 months">6 months</option>
                                <option value="1 year">1 year</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="seats_required" class="form-label">Number of Seats</label>
                        <input type="number" class="form-control" id="seats_required" name="seats_required" min="1" placeholder="1">
                    </div>
                    
                    <div class="mb-3">
                        <label for="special_requirements" class="form-label">Special Requirements</label>
                        <textarea class="form-control" id="special_requirements" name="special_requirements" rows="3" 
                                  placeholder="Any specific requirements or questions for the owner..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        The space owner will contact you directly via email or phone to discuss details and arrange a visit.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="inquiryForm" class="btn btn-success">
                    <i class="fas fa-paper-plane me-2"></i>Send Inquiry
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="galleryModalLabel">Photo Gallery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="galleryImage" src="" alt="" class="img-fluid" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php require_once '../components/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sharing Functions -->
<script>
function shareOnWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('<?php echo htmlspecialchars($space['space_name']); ?> - Check out this workspace!');
    window.open(`https://wa.me/?text=${text}%20${url}`, '_blank');
    if (typeof gtag !== 'undefined') {
        gtag('event', 'share_whatsapp', {
            'method': 'WhatsApp',
            'content_type': 'space'
        });
    }
}

function shareViaEmail() {
    const subject = encodeURIComponent('<?php echo htmlspecialchars($space['space_name']); ?>');
    const body = encodeURIComponent(`I found this workspace and thought you might be interested:\n\n${window.location.href}`);
    window.location.href = `mailto:?subject=${subject}&body=${body}`;
    if (typeof gtag !== 'undefined') {
        gtag('event', 'share_email', {
            'method': 'Email',
            'content_type': 'space'
        });
    }
}

function shareViaFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
    if (typeof gtag !== 'undefined') {
        gtag('event', 'share_facebook', {
            'method': 'Facebook',
            'content_type': 'space'
        });
    }
}

function openGallery(index) {
    const photos = <?php echo json_encode($photos); ?>;
    if (photos && photos[index]) {
        document.getElementById('galleryImage').src = photos[index].photo_url;
        document.getElementById('galleryImage').alt = photos[index].caption || '';
    }
}

// Track inquiry form submission
document.getElementById('inquiryForm')?.addEventListener('submit', function() {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'space_inquiry_submit', {
            'event_category': 'Coworking_Spaces',
            'event_label': '<?php echo htmlspecialchars($space['space_name']); ?>'
        });
    }
});
</script>

</body>
</html>

