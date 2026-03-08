<?php
/**
 * SEO Helper Functions
 * Centralized SEO functions for Hostels & PGs portal
 */

/**
 * Generate comprehensive meta tags for property pages
 */
function generatePropertySEOMeta($title, $description, $url, $image = '', $type = 'website') {
    $image = $image ?: 'https://myomr.in/My-OMR-Logo.jpg';
    
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
 * Generate structured data for property listing
 */
function generatePropertySchema($property) {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "LodgingBusiness",
        "name" => htmlspecialchars($property['property_name']),
        "description" => strip_tags($property['full_description'] ?? $property['brief_overview'] ?? ''),
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => htmlspecialchars($property['locality'] ?? ''),
            "addressRegion" => "Tamil Nadu",
            "addressCountry" => "IN",
            "streetAddress" => htmlspecialchars($property['address'] ?? '')
        ],
        "image" => []
    ];
    
    // Add featured image if available
    if (!empty($property['featured_image'])) {
        $schema["image"][] = "https://myomr.in/omr-hostels-pgs/" . htmlspecialchars($property['featured_image']);
    }
    
    // Geo coordinates
    if (!empty($property['latitude']) && !empty($property['longitude'])) {
        $schema["geo"] = [
            "@type" => "GeoCoordinates",
            "latitude" => (float)$property['latitude'],
            "longitude" => (float)$property['longitude']
        ];
    }
    
    // Pricing information
    if (!empty($property['monthly_rent_single']) || !empty($property['monthly_rent_double'])) {
        $schema["priceRange"] = "₹";
        $minPrice = PHP_INT_MAX;
        $maxPrice = 0;
        
        if (!empty($property['monthly_rent_single'])) $minPrice = min($minPrice, $property['monthly_rent_single']);
        if (!empty($property['monthly_rent_double'])) $minPrice = min($minPrice, $property['monthly_rent_double']);
        if (!empty($property['monthly_rent_triple'])) $minPrice = min($minPrice, $property['monthly_rent_triple']);
        if (!empty($property['monthly_rent_dormitory'])) $minPrice = min($minPrice, $property['monthly_rent_dormitory']);
        
        if (!empty($property['monthly_rent_single'])) $maxPrice = max($maxPrice, $property['monthly_rent_single']);
        if (!empty($property['monthly_rent_double'])) $maxPrice = max($maxPrice, $property['monthly_rent_double']);
        if (!empty($property['monthly_rent_triple'])) $maxPrice = max($maxPrice, $property['monthly_rent_triple']);
        if (!empty($property['monthly_rent_dormitory'])) $maxPrice = max($maxPrice, $property['monthly_rent_dormitory']);
        
        if ($minPrice != PHP_INT_MAX && $maxPrice > 0) {
            $schema["priceRange"] .= number_format($minPrice) . "-₹" . number_format($maxPrice);
        }
    }
    
    // Star rating (if available)
    if (!empty($property['rating_average']) && $property['rating_average'] > 0) {
        $schema["aggregateRating"] = [
            "@type" => "AggregateRating",
            "ratingValue" => (float)$property['rating_average'],
            "reviewCount" => (int)($property['reviews_count'] ?? 0)
        ];
    }
    
    // Amenities
    $facilities = json_decode($property['facilities'] ?? '[]', true);
    if (!empty($facilities) && is_array($facilities)) {
        $schema["amenityFeature"] = [];
        foreach ($facilities as $facility) {
            $schema["amenityFeature"][] = [
                "@type" => "LocationFeatureSpecification",
                "name" => htmlspecialchars($facility)
            ];
        }
    }
    
    // Contact information
    if (!empty($property['contact_phone'])) {
        $schema["telephone"] = htmlspecialchars($property['contact_phone']);
    }
    if (!empty($property['contact_email'])) {
        $schema["email"] = htmlspecialchars($property['contact_email']);
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
 * Generate ItemList schema for property listings page
 */
function generateItemListSchema($properties, $baseUrl) {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "ItemList",
        "name" => "Hostels & PGs in OMR",
        "itemListElement" => []
    ];
    
    $position = 1;
    foreach ($properties as $property) {
        $schema["itemListElement"][] = [
            "@type" => "ListItem",
            "position" => $position,
            "name" => htmlspecialchars($property['property_name']),
            "url" => $baseUrl . "property-detail.php?id=" . $property['id']
        ];
        $position++;
    }
    
    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

