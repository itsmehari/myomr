<?php
/**
 * Centralized configuration for OMR directory pages
 * This file contains all the data and settings for different directory types
 */

// Directory page configurations
$directory_configs = [
    'schools' => [
        'title' => 'Schools in OMR, Chennai',
        'description' => 'Find schools along Old Mahabalipuram Road (OMR), Chennai. Get school names, addresses, contacts, and landmarks for your child\'s education.',
        'table' => 'omrschoolslist',
        'fields' => [
            'id' => 'slno',
            'name' => 'schoolname',
            'address' => 'address',
            'contact' => 'contact',
            'landmark' => 'landmark'
        ],
        'icon' => 'fas fa-graduation-cap',
        'color' => '#0583D2',
        'intro_text' => [
            'Old Mahabalipuram Road (OMR), often referred to as the IT Corridor of Chennai, has experienced significant growth over the past decade, evolving into a vibrant hub that seamlessly blends technological advancement with residential serenity. This rapid development has led to the establishment of numerous esteemed educational institutions, catering to the diverse needs of the community.',
            'The schools along OMR are renowned for their commitment to academic excellence, holistic development, and state-of-the-art facilities. They offer a variety of curricula, including CBSE, ICSE, and international programs, ensuring that parents have a wide array of choices to best suit their children\'s educational requirements.',
            'Whether you\'re seeking institutions with a strong emphasis on traditional values or those that incorporate innovative teaching methodologies, the schools in the OMR region provide an ideal environment for nurturing young minds.'
        ]
    ],
    'hospitals' => [
        'title' => 'Hospitals in OMR, Chennai',
        'description' => 'Find hospitals along Old Mahabalipuram Road (OMR), Chennai. Get hospital names, addresses, contacts, and landmarks for healthcare services.',
        'table' => 'omrhospitalslist',
        'fields' => [
            'id' => 'slno',
            'name' => 'hospitalname',
            'address' => 'address',
            'contact' => 'contact',
            'landmark' => 'landmark'
        ],
        'icon' => 'fas fa-hospital',
        'color' => '#0583D2',
        'intro_text' => [
            'Healthcare accessibility is crucial for any thriving community, and OMR has developed into a well-equipped medical hub serving the growing population along the IT Corridor.',
            'The hospitals and medical facilities along OMR offer comprehensive healthcare services, from emergency care to specialized treatments, ensuring residents have access to quality medical care close to home.',
            'These healthcare institutions are equipped with modern facilities and staffed by experienced medical professionals, providing peace of mind to the OMR community.'
        ]
    ],
    'restaurants' => [
        'title' => 'Vegetarian Restaurants in OMR, Chennai',
        'description' => 'Explore vegetarian restaurants along Old Mahabalipuram Road (OMR), Chennai. Find restaurant names, addresses, cuisines, ratings, and more at MyOMR.in.',
        'table' => 'omr_restaurants',
        'fields' => [
            'id' => 'id',
            'name' => 'name',
            'address' => 'address',
            'contact' => 'contact',
            'landmark' => 'locality',
            'cuisine' => 'cuisine',
            'rating' => 'rating',
            'cost_for_two' => 'cost_for_two'
        ],
        'icon' => 'fas fa-utensils',
        'color' => '#0583D2',
        'intro_text' => [
            'OMR\'s dining scene has flourished alongside its IT growth, offering a diverse range of vegetarian restaurants that cater to every palate and budget.',
            'From traditional South Indian cuisine to North Indian delicacies, the restaurants along OMR provide authentic flavors and modern dining experiences.',
            'Whether you\'re looking for a quick lunch, family dinner, or special occasion dining, OMR\'s restaurant scene has something to satisfy every craving.'
        ],
        'has_filters' => true,
        'filters' => [
            'locality' => 'Locality',
            'cuisine' => 'Cuisine',
            'cost' => 'Max Cost for Two (₹)',
            'rating' => 'Minimum Rating'
        ]
    ],
    'banks' => [
        'title' => 'Banks in OMR, Chennai',
        'description' => 'Find banks and financial institutions along Old Mahabalipuram Road (OMR), Chennai. Get bank names, addresses, contacts, and services.',
        'table' => 'omrbankslist',
        'fields' => [
            'id' => 'slno',
            'name' => 'bankname',
            'address' => 'address',
            'contact' => 'contact',
            'landmark' => 'landmark'
        ],
        'icon' => 'fas fa-university',
        'color' => '#0583D2',
        'intro_text' => [
            'Financial services are essential for any growing community, and OMR hosts a comprehensive network of banks and financial institutions.',
            'From nationalized banks to private sector banks, the OMR corridor offers convenient access to all major banking services.',
            'These institutions provide a full range of services including savings accounts, loans, investment options, and digital banking solutions.'
        ]
    ],
    'atms' => [
        'title' => 'ATMs in OMR, Chennai',
        'description' => 'Find ATM locations along Old Mahabalipuram Road (OMR), Chennai. Get ATM locations, addresses, and bank information.',
        'table' => 'omratmslist',
        'fields' => [
            'id' => 'slno',
            'name' => 'atmname',
            'address' => 'address',
            'contact' => 'contact',
            'landmark' => 'landmark'
        ],
        'icon' => 'fas fa-credit-card',
        'color' => '#0583D2',
        'intro_text' => [
            'Convenient access to cash is essential for daily transactions, and OMR provides numerous ATM locations throughout the corridor.',
            'These ATMs are strategically placed near residential areas, IT parks, and commercial zones for easy access.',
            'Most ATMs offer 24/7 service and support multiple banking networks for maximum convenience.'
        ]
    ],
    'it-companies' => [
        'title' => 'IT Companies in OMR, Chennai',
        'description' => 'Explore IT companies and technology firms along Old Mahabalipuram Road (OMR), Chennai. Find company names, addresses, and contact information.',
        'table' => 'omr_it_companies',
        'fields' => [
            'id' => 'slno',
            'name' => 'company_name',
            'address' => 'address',
            'contact' => 'contact',
            'locality' => 'locality',
            'industry_type' => 'industry_type',
            'slug' => 'slug',
            'verified' => 'verified'
        ],
        'icon' => 'fas fa-laptop-code',
        'color' => '#0583D2',
        'intro_text' => [
            'OMR is renowned as Chennai\'s IT Corridor, hosting numerous technology companies, startups, and multinational corporations.',
            'From established IT giants to innovative startups, the OMR ecosystem provides opportunities for career growth and technological advancement.',
            'These companies offer diverse roles in software development, data analytics, artificial intelligence, and other cutting-edge technologies.'
        ]
    ],
    'industries' => [
        'title' => 'Industries in OMR, Chennai',
        'description' => 'Find industrial units and manufacturing facilities along Old Mahabalipuram Road (OMR), Chennai.',
        'table' => 'omrindustrieslist',
        'fields' => [
            'id' => 'slno',
            'name' => 'industryname',
            'address' => 'address',
            'contact' => 'contact',
            'landmark' => 'landmark'
        ],
        'icon' => 'fas fa-industry',
        'color' => '#0583D2',
        'intro_text' => [
            'OMR hosts a diverse range of industrial units and manufacturing facilities alongside its IT infrastructure.',
            'These industries contribute significantly to the local economy and provide employment opportunities.',
            'From small-scale manufacturing to large industrial units, OMR offers a balanced mix of technology and traditional industries.'
        ]
    ],
    'parks' => [
        'title' => 'Parks in OMR, Chennai',
        'description' => 'Discover parks and recreational spaces along Old Mahabalipuram Road (OMR), Chennai. Find park locations, facilities, and amenities.',
        'table' => 'omrparkslist',
        'fields' => [
            'id' => 'slno',
            'name' => 'parkname',
            'address' => 'address',
            'contact' => 'contact',
            'landmark' => 'landmark'
        ],
        'icon' => 'fas fa-tree',
        'color' => '#0583D2',
        'intro_text' => [
            'Green spaces are essential for community well-being, and OMR offers several parks and recreational areas for residents.',
            'These parks provide spaces for exercise, relaxation, and community gatherings.',
            'From small neighborhood parks to larger recreational facilities, OMR\'s green spaces enhance the quality of life for residents.'
        ]
    ],
    'government-offices' => [
        'title' => 'Government Offices in OMR, Chennai',
        'description' => 'Find government offices and administrative centers along Old Mahabalipuram Road (OMR), Chennai.',
        'table' => 'omrgovernmentofficeslist',
        'fields' => [
            'id' => 'slno',
            'name' => 'officename',
            'address' => 'address',
            'contact' => 'contact',
            'landmark' => 'landmark'
        ],
        'icon' => 'fas fa-building',
        'color' => '#0583D2',
        'intro_text' => [
            'Government services are easily accessible through various administrative offices located along OMR.',
            'These offices provide essential services including civic administration, public utilities, and citizen services.',
            'Residents can access government services without traveling far from their homes or workplaces.'
        ]
    ]
];

/**
 * Get configuration for a specific directory type
 */
function get_directory_config($type) {
    global $directory_configs;
    return isset($directory_configs[$type]) ? $directory_configs[$type] : null;
}

/**
 * Get all available directory types
 */
function get_directory_types() {
    global $directory_configs;
    return array_keys($directory_configs);
}

/**
 * Generate SQL query for directory data
 */
function get_directory_sql($config, $filters = []) {
    $fields = implode(', ', array_values($config['fields']));
    $table = $config['table'];
    
    $sql = "SELECT $fields FROM $table WHERE 1=1";
    
    // Add filters if any
    foreach ($filters as $field => $value) {
        if (!empty($value)) {
            $sql .= " AND $field LIKE '%$value%'";
        }
    }
    
    return $sql;
}

/**
 * Generate structured data for SEO
 */
function generate_structured_data($config, $data) {
    $structured_data = [
        "@context" => "https://schema.org",
        "@type" => "LocalBusiness",
        "name" => $data['name'],
        "address" => [
            "@type" => "PostalAddress",
            "streetAddress" => $data['address'],
            "addressLocality" => "Chennai",
            "addressRegion" => "Tamil Nadu",
            "postalCode" => "600097",
            "addressCountry" => "IN"
        ]
    ];
    
    if (isset($data['contact'])) {
        $structured_data["telephone"] = $data['contact'];
    }
    
    return json_encode($structured_data, JSON_UNESCAPED_SLASHES);
}
?>
