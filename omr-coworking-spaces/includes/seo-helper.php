<?php
/**
 * SEO Helper Functions
 * Centralized SEO functions for Coworking Spaces portal
 */

/**
 * Generate comprehensive meta tags for space pages
 */
function generateSpaceSEOMeta($title, $description, $url, $image = '', $type = 'website') {
    $image = $image ?: 'https://myomr.in/My-OMR-Logo.png';
    
    $meta = '';
    
    // Basic meta tags
    $meta .= '<meta name="description" content="' . htmlspecialchars($description) . '">' . "\n";
    $meta .= '<link rel="canonical" href="' . htmlspecialchars($url) . '">' . "\n";
    
    // Open Graph
    $meta .= '<meta property="og:title" content="' . htmlspecialchars($title) . '">' . "\n";
    $meta .= '<meta property="og:description" content="' . htmlspecialchars($description) . '">' . "\n";
    $meta .= '<meta property="og:url" content="' . htmlspecialchars($url) . '">' . "\n";
    $meta .= '<meta property="og:type" content="' . htmlspecialchars($type) . '">' . "\n";
    $meta .= '<meta property="og:image" content="' . htmlspecialchars($image) . '">' . "\n";
    $meta .= '<meta property="og:site_name" content="MyOMR">' . "\n";
    
    // Twitter Card
    $meta .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
    $meta .= '<meta name="twitter:title" content="' . htmlspecialchars($title) . '">' . "\n";
    $meta .= '<meta name="twitter:description" content="' . htmlspecialchars($description) . '">' . "\n";
    $meta .= '<meta name="twitter:image" content="' . htmlspecialchars($image) . '">' . "\n";
    
    return $meta;
}

/**
 * Generate structured data for space listing
 */
function generateSpaceSchema($space) {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "LocalBusiness",
        "name" => htmlspecialchars($space['space_name']),
        "description" => strip_tags($space['full_description'] ?? $space['brief_overview'] ?? ''),
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => htmlspecialchars($space['locality'] ?? ''),
            "addressRegion" => "Tamil Nadu",
            "addressCountry" => "IN",
            "streetAddress" => htmlspecialchars($space['address'] ?? '')
        ],
        "image" => []
    ];
    
    // Add featured image if available
    if (!empty($space['featured_image'])) {
        $schema["image"][] = "https://myomr.in/omr-coworking-spaces/" . htmlspecialchars($space['featured_image']);
    }
    
    // Geo coordinates
    if (!empty($space['latitude']) && !empty($space['longitude'])) {
        $schema["geo"] = [
            "@type" => "GeoCoordinates",
            "latitude" => (float)$space['latitude'],
            "longitude" => (float)$space['longitude']
        ];
    }
    
    // Pricing information
    if (!empty($space['hot_desk_monthly']) || !empty($space['day_pass_price'])) {
        $schema["priceRange"] = "₹";
        $minPrice = PHP_INT_MAX;
        $maxPrice = 0;
        
        if (!empty($space['day_pass_price'])) $minPrice = min($minPrice, $space['day_pass_price']);
        if (!empty($space['hot_desk_monthly'])) $minPrice = min($minPrice, $space['hot_desk_monthly']);
        if (!empty($space['dedicated_desk_monthly'])) $minPrice = min($minPrice, $space['dedicated_desk_monthly']);
        if (!empty($space['private_cabin_monthly'])) $minPrice = min($minPrice, $space['private_cabin_monthly']);
        if (!empty($space['team_space_monthly'])) $minPrice = min($minPrice, $space['team_space_monthly']);
        
        if (!empty($space['day_pass_price'])) $maxPrice = max($maxPrice, $space['day_pass_price']);
        if (!empty($space['hot_desk_monthly'])) $maxPrice = max($maxPrice, $space['hot_desk_monthly']);
        if (!empty($space['dedicated_desk_monthly'])) $maxPrice = max($maxPrice, $space['dedicated_desk_monthly']);
        if (!empty($space['private_cabin_monthly'])) $maxPrice = max($maxPrice, $space['private_cabin_monthly']);
        if (!empty($space['team_space_monthly'])) $maxPrice = max($maxPrice, $space['team_space_monthly']);
        
        if ($minPrice != PHP_INT_MAX && $maxPrice > 0) {
            $schema["priceRange"] .= number_format($minPrice) . "-₹" . number_format($maxPrice);
        }
    }
    
    // Star rating (if available)
    if (!empty($space['rating_average']) && $space['rating_average'] > 0) {
        $schema["aggregateRating"] = [
            "@type" => "AggregateRating",
            "ratingValue" => (float)$space['rating_average'],
            "reviewCount" => (int)($space['reviews_count'] ?? 0)
        ];
    }
    
    // Operating hours
    if (!empty($space['operating_hours'])) {
        $schema["openingHours"] = htmlspecialchars($space['operating_hours']);
    }
    
    // Amenities
    $amenities = json_decode($space['amenities'] ?? '[]', true);
    if (!empty($amenities) && is_array($amenities)) {
        $schema["amenityFeature"] = [];
        foreach ($amenities as $amenity) {
            $schema["amenityFeature"][] = [
                "@type" => "LocationFeatureSpecification",
                "name" => htmlspecialchars($amenity)
            ];
        }
    }
    
    // Contact information
    if (!empty($space['contact_phone'])) {
        $schema["telephone"] = htmlspecialchars($space['contact_phone']);
    }
    if (!empty($space['contact_email'])) {
        $schema["email"] = htmlspecialchars($space['contact_email']);
    }
    
    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
}

/**
 * Generate breadcrumb schema
 */
function generateBreadcrumbSchema($items) {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => []
    ];
    
    $position = 1;
    foreach ($items as $item) {
        $schema["itemListElement"][] = [
            "@type" => "ListItem",
            "position" => $position,
            "name" => htmlspecialchars($item['name']),
            "item" => htmlspecialchars($item['url'])
        ];
        $position++;
    }
    
    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

/**
 * Generate ItemList schema for space listings page
 */
function generateItemListSchema($spaces, $baseUrl) {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "ItemList",
        "name" => "Coworking Spaces in OMR",
        "itemListElement" => []
    ];
    
    $position = 1;
    foreach ($spaces as $space) {
        $schema["itemListElement"][] = [
            "@type" => "ListItem",
            "position" => $position,
            "name" => htmlspecialchars($space['space_name']),
            "url" => $baseUrl . "space-detail.php?id=" . $space['id']
        ];
        $position++;
    }
    
    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

