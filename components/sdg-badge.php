<?php
/**
 * UN SDG Floating Badge Component
 * 
 * Auto-detects page type and displays relevant SDG badges.
 * Can be manually overridden by setting $sdg_badges array before including.
 * 
 * Usage:
 *   // Auto-detect (recommended)
 *   <?php include 'components/sdg-badge.php'; ?>
 * 
 *   // Manual override
 *   <?php 
 *     $sdg_badges = [1, 11]; // Array of SDG numbers
 *     $sdg_layout = 'stacked'; // 'stacked' or 'side-by-side'
 *     include 'components/sdg-badge.php'; 
 *   ?>
 */

/**
 * Auto-detect SDG badges based on current page
 */
if (!function_exists('autoDetectSDGBadges')) {
function autoDetectSDGBadges() {
    $path = $_SERVER['REQUEST_URI'] ?? '';
    $path = parse_url($path, PHP_URL_PATH);
    $path = rtrim($path, '/');
    
    // Feature-to-SDG mapping (from implementation plan)
    $mappings = [
        // Hostels & PGs
        '/omr-hostels-pgs' => [1, 11],
        '/omr-hostels-pgs/index.php' => [1, 11],
        '/omr-hostels-pgs/property-detail.php' => [1, 11],
        
        // Coworking Spaces
        '/omr-coworking-spaces' => [8, 9],
        '/omr-coworking-spaces/index.php' => [8, 9],
        '/omr-coworking-spaces/space-detail.php' => [8, 9],
        
        // Job Listings
        '/omr-local-job-listings' => [8, 1],
        '/omr-local-job-listings/index.php' => [8, 1],
        '/listings' => [8, 1],
        
        // Local News
        '/local-news' => [11, 16],
        '/local-news/index.php' => [11, 16],
        
        // Events
        '/omr-local-events' => [11, 17],
        '/omr-local-events/index.php' => [11, 17],
        '/events' => [11, 17],
        
        // Schools
        '/omr-listings/schools.php' => [4],
        '/omr-listings/schools' => [4],
        
        // Hospitals
        '/omr-listings/hospitals.php' => [3],
        '/omr-listings/hospitals' => [3],
        
        // Restaurants
        '/omr-listings/restaurants.php' => [2],
        '/omr-listings/restaurants' => [2],
        
        // Banks & ATMs
        '/omr-listings/banks.php' => [1],
        '/omr-listings/banks' => [1],
        '/omr-listings/atms.php' => [1],
        '/omr-listings/atms' => [1],
        
        // IT Companies
        '/omr-listings/it-companies.php' => [9, 8],
        '/omr-listings/it-companies' => [9, 8],
        '/omr-listings/it-company.php' => [9, 8],
        
        // Parks
        '/omr-listings/parks.php' => [15, 11],
        '/omr-listings/parks' => [15, 11],
        
        // Government Offices
        '/omr-listings/government-offices.php' => [16],
        '/omr-listings/government-offices' => [16],
        
        // Homepage
        '/' => [11],
        '/index.php' => [11],
        
        // SDG Page itself
        '/discover-myomr/sustainable-development-goals.php' => [], // No badges on SDG page itself
    ];
    
    // Check exact match first
    if (isset($mappings[$path])) {
        return $mappings[$path];
    }
    
    // Check partial match (e.g., /omr-hostels-pgs/property-detail.php?id=123)
    foreach ($mappings as $pattern => $sdgs) {
        if (strpos($path, $pattern) === 0) {
            return $sdgs;
        }
    }
    
    // Default: No badges
    return [];
}
}

// SDG Badge Configuration
// If $sdg_badges is not set, auto-detect based on current page
if (!isset($sdg_badges) || empty($sdg_badges)) {
    $sdg_badges = autoDetectSDGBadges();
}

// Layout: 'stacked' (vertical) or 'side-by-side' (horizontal)
if (!isset($sdg_layout)) {
    $sdg_layout = count($sdg_badges) > 1 ? 'stacked' : 'single';
}

// Position: 'bottom-left' (default), 'bottom-right', 'bottom-center'
if (!isset($sdg_position)) {
    $sdg_position = 'bottom-left';
}

// Only render if badges are configured
if (!empty($sdg_badges) && is_array($sdg_badges)) {
    // Include CSS (only once)
    static $css_included = false;
    if (!$css_included) {
        echo '<link rel="stylesheet" href="/assets/css/sdg-badge.css">';
        $css_included = true;
    }
    
    // Determine container class
    $container_class = 'sdg-badge-container';
    if ($sdg_layout === 'stacked' && count($sdg_badges) > 1) {
        $container_class .= ' stacked';
    } elseif ($sdg_layout === 'side-by-side' && count($sdg_badges) > 1) {
        $container_class .= ' side-by-side';
    }
    
    // Position class
    if ($sdg_position === 'bottom-right') {
        $container_class .= ' position-right';
        echo '<style>.sdg-badge-container.position-right { left: auto; right: 40px; }</style>';
    } elseif ($sdg_position === 'bottom-center') {
        $container_class .= ' position-center';
        echo '<style>.sdg-badge-container.position-center { left: 50%; transform: translateX(-50%); }</style>';
    }
    
    // Render badges
    echo '<div class="' . htmlspecialchars($container_class) . '" role="group" aria-label="UN Sustainable Development Goals">';
    
    foreach ($sdg_badges as $sdg_number) {
        $sdg_number = (int)$sdg_number;
        if ($sdg_number >= 1 && $sdg_number <= 17) {
            $badge_url = '/discover-myomr/sustainable-development-goals.php';
            echo '<a href="' . htmlspecialchars($badge_url) . '" ';
            echo 'class="sdg-badge sdg-' . $sdg_number . '" ';
            echo 'data-sdg="' . $sdg_number . '" ';
            echo 'title="SDG ' . $sdg_number . '">';
            echo '<span aria-hidden="true">' . $sdg_number . '</span>';
            echo '</a>';
        }
    }
    
    echo '</div>';
    
    // Include JavaScript (only once)
    static $js_included = false;
    if (!$js_included) {
        echo '<script src="/assets/js/sdg-badge.js" defer></script>';
        $js_included = true;
    }
}
?>
