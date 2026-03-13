<?php
/**
 * SEO Helper Functions
 * Centralized SEO functions for job portal
 */

/**
 * Generate comprehensive meta tags for job pages
 */
function generateJobSEOMeta($title, $description, $url, $image = '', $type = 'website') {
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
 * Sanitize text for schema output
 */
function sanitizeSchemaText($value, $maxLength = 250) {
    $clean = trim(strip_tags((string)$value));
    if ($clean === '') {
        return '';
    }
    if (function_exists('mb_substr')) {
        return mb_substr($clean, 0, $maxLength);
    }
    return substr($clean, 0, $maxLength);
}

/**
 * Map OMR localities to postal codes and regions
 */
function getOmrPostalMap() {
    return [
        'okkiyam' => ['locality' => 'Okkiyam Thoraipakkam', 'postalCode' => '600097', 'region' => 'Tamil Nadu'],
        'thoraipakkam' => ['locality' => 'Thoraipakkam', 'postalCode' => '600097', 'region' => 'Tamil Nadu'],
        'karapakkam' => ['locality' => 'Karapakkam', 'postalCode' => '600097', 'region' => 'Tamil Nadu'],
        'perungudi' => ['locality' => 'Perungudi', 'postalCode' => '600096', 'region' => 'Tamil Nadu'],
        'sholinganallur' => ['locality' => 'Sholinganallur', 'postalCode' => '600119', 'region' => 'Tamil Nadu'],
        'semmencherry' => ['locality' => 'Semmencherry', 'postalCode' => '600119', 'region' => 'Tamil Nadu'],
        'navalur' => ['locality' => 'Navalur', 'postalCode' => '603103', 'region' => 'Tamil Nadu'],
        'siruseri' => ['locality' => 'Siruseri', 'postalCode' => '603103', 'region' => 'Tamil Nadu'],
        'padur' => ['locality' => 'Padur', 'postalCode' => '603103', 'region' => 'Tamil Nadu'],
        'kelambakkam' => ['locality' => 'Kelambakkam', 'postalCode' => '603103', 'region' => 'Tamil Nadu'],
        'egattur' => ['locality' => 'Egattur', 'postalCode' => '603103', 'region' => 'Tamil Nadu'],
        'kazhipattur' => ['locality' => 'Kazhipattur', 'postalCode' => '603103', 'region' => 'Tamil Nadu'],
        'uthandi' => ['locality' => 'Uthandi', 'postalCode' => '600119', 'region' => 'Tamil Nadu'],
        'uttandi' => ['locality' => 'Uthandi', 'postalCode' => '600119', 'region' => 'Tamil Nadu'],
        'medavakkam' => ['locality' => 'Medavakkam', 'postalCode' => '600100', 'region' => 'Tamil Nadu'],
        'perumbakkam' => ['locality' => 'Perumbakkam', 'postalCode' => '600100', 'region' => 'Tamil Nadu'],
        'pallikaranai' => ['locality' => 'Pallikaranai', 'postalCode' => '600100', 'region' => 'Tamil Nadu'],
        'thiruporur' => ['locality' => 'Thiruporur', 'postalCode' => '603110', 'region' => 'Tamil Nadu'],
        'muttukadu' => ['locality' => 'Muttukadu', 'postalCode' => '603103', 'region' => 'Tamil Nadu'],
        'old mahabalipuram' => ['locality' => 'OMR, Chennai', 'postalCode' => '600097', 'region' => 'Tamil Nadu'],
        'mahabalipuram road' => ['locality' => 'OMR, Chennai', 'postalCode' => '600097', 'region' => 'Tamil Nadu'],
        'chennai' => ['locality' => 'Chennai', 'postalCode' => '600097', 'region' => 'Tamil Nadu'],
    ];
}

/**
 * Resolve postal address components for a job
 */
function resolveJobPostalAddress(array $job) {
    $defaults = [
        'streetAddress' => 'Old Mahabalipuram Road, Chennai',
        'addressLocality' => 'Chennai',
        'addressRegion' => 'Tamil Nadu',
        'postalCode' => '600097',
        'addressCountry' => 'IN',
    ];
    
    $companyAddress = sanitizeSchemaText($job['company_address'] ?? '', 300);
    $locationText = sanitizeSchemaText($job['location'] ?? '', 150);
    
    if ($companyAddress !== '') {
        $defaults['streetAddress'] = $companyAddress;
    }
    
    if ($locationText !== '') {
        if ($defaults['streetAddress'] === '') {
            $defaults['streetAddress'] = $locationText;
        }
        $parts = array_values(array_filter(array_map('trim', preg_split('/[,|-]+/', $locationText))));
        if (!empty($parts)) {
            $defaults['addressLocality'] = $parts[0];
        }
    }
    
    $map = getOmrPostalMap();
    $haystacks = [
        strtolower($locationText),
        strtolower($companyAddress),
    ];
    
    foreach ($map as $needle => $data) {
        foreach ($haystacks as $haystack) {
            if ($haystack !== '' && strpos($haystack, $needle) !== false) {
                $defaults['addressLocality'] = $data['locality'];
                if (!empty($data['postalCode'])) {
                    $defaults['postalCode'] = $data['postalCode'];
                }
                if (!empty($data['region'])) {
                    $defaults['addressRegion'] = $data['region'];
                }
                break 2;
            }
        }
    }
    
    return $defaults;
}

/**
 * Determine the validThrough date for the job
 */
function resolveJobValidThrough(array $job) {
    if (!empty($job['application_deadline']) && $job['application_deadline'] !== '0000-00-00') {
        $deadline = strtotime($job['application_deadline']);
        if ($deadline !== false) {
            return date('Y-m-d', $deadline);
        }
    }
    
    $baseDate = !empty($job['created_at']) ? strtotime($job['created_at']) : time();
    if ($baseDate === false) {
        $baseDate = time();
    }
    
    return date('Y-m-d', strtotime('+45 days', $baseDate));
}

/**
 * Parse salary range for schema quantitative value
 */
function parseSalaryRangeForSchema($salaryRange) {
    if (empty($salaryRange) || stripos($salaryRange, 'not disclosed') !== false) {
        return null;
    }
    
    $text = strtolower($salaryRange);
    $unitText = 'MONTH';
    if (strpos($text, 'year') !== false || strpos($text, 'annum') !== false) {
        $unitText = 'YEAR';
    } elseif (strpos($text, 'week') !== false) {
        $unitText = 'WEEK';
    } elseif (strpos($text, 'day') !== false) {
        $unitText = 'DAY';
    }
    
    if (!preg_match_all('/\d+(?:,\d{3})*(?:\.\d+)?/', $salaryRange, $matches)) {
        return null;
    }
    
    $values = array_map(function ($number) {
        return (float)str_replace([','], '', $number);
    }, $matches[0]);
    
    $values = array_filter($values, function ($value) {
        return $value > 0;
    });
    
    if (empty($values)) {
        return null;
    }
    
    sort($values);
    $result = [
        'unitText' => $unitText,
    ];
    
    if (count($values) === 1) {
        $result['value'] = $values[0];
    } else {
        $result['minValue'] = $values[0];
        $result['maxValue'] = end($values);
    }
    
    return $result;
}

/**
 * Build a schema.org baseSalary block from structured INT columns (preferred)
 * or fall back to the legacy salary_range VARCHAR regex parse.
 *
 * @param array $job  Job row with optional salary_min, salary_max, salary_range
 * @return array|null  Schema fragment or null if no salary data
 */
function buildSalarySchema(array $job): ?array
{
    $min = isset($job['salary_min']) ? (int)$job['salary_min'] : 0;
    $max = isset($job['salary_max']) ? (int)$job['salary_max'] : 0;

    if ($min > 0 || $max > 0) {
        $value = ['@type' => 'QuantitativeValue', 'unitText' => 'MONTH'];
        if ($min > 0 && $max > 0) {
            $value['minValue'] = $min;
            $value['maxValue'] = $max;
        } elseif ($min > 0) {
            $value['value'] = $min;
        } else {
            $value['value'] = $max;
        }
        return ['@type' => 'MonetaryAmount', 'currency' => 'INR', 'value' => $value];
    }

    // Legacy fallback: parse salary_range string
    $parsed = parseSalaryRangeForSchema($job['salary_range'] ?? '');
    if (!$parsed) {
        return null;
    }
    $value = ['@type' => 'QuantitativeValue', 'unitText' => $parsed['unitText']];
    if (isset($parsed['value'])) {
        $value['value'] = $parsed['value'];
    } else {
        $value['minValue'] = $parsed['minValue'];
        $value['maxValue'] = $parsed['maxValue'];
    }
    return ['@type' => 'MonetaryAmount', 'currency' => 'INR', 'value' => $value];
}

/**
 * Generate structured data for job listing
 */
function generateJobPostingSchema($job) {
    $address = resolveJobPostalAddress($job);
    $validThrough = resolveJobValidThrough($job);
    $salary = buildSalarySchema($job);
    $jobUrl = function_exists('getJobDetailUrl') ? getJobDetailUrl((int)($job['id'] ?? 0), $job['title'] ?? null) : '';
    
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "JobPosting",
        "title" => sanitizeSchemaText($job['title'], 150),
        "description" => sanitizeSchemaText($job['description'], 5000),
        "datePosted" => !empty($job['created_at']) ? date('Y-m-d', strtotime($job['created_at'])) : date('Y-m-d'),
        "validThrough" => $validThrough,
        "employmentType" => sanitizeSchemaText($job['job_type'], 50),
        "directApply" => true,
        "hiringOrganization" => array_filter([
            "@type" => "Organization",
            "name" => sanitizeSchemaText($job['company_name'] ?? 'MyOMR Employer', 200),
            "email" => sanitizeSchemaText($job['employer_email'] ?? ''),
            "telephone" => sanitizeSchemaText($job['employer_phone'] ?? ''),
            "address" => array_filter([
                "@type" => "PostalAddress",
                "streetAddress" => $address['streetAddress'],
                "addressLocality" => $address['addressLocality'],
                "addressRegion" => $address['addressRegion'],
                "postalCode" => $address['postalCode'],
                "addressCountry" => $address['addressCountry'],
            ], function ($value) {
                return $value !== null && $value !== '';
            }),
        ], function ($value) {
            return $value !== null && $value !== '' && $value !== [];
        }),
        "jobLocation" => [
            "@type" => "Place",
            "address" => array_filter([
                "@type" => "PostalAddress",
                "streetAddress" => $address['streetAddress'],
                "addressLocality" => $address['addressLocality'],
                "addressRegion" => $address['addressRegion'],
                "postalCode" => $address['postalCode'],
                "addressCountry" => $address['addressCountry'],
            ], function ($value) {
                return $value !== null && $value !== '';
            }),
        ],
    ];
    
    if ($jobUrl !== '') {
        $schema["url"] = $jobUrl;
    }
    
    if (!empty($job['category_name'])) {
        $schema["industry"] = sanitizeSchemaText($job['category_name'], 120);
    }
    
    if ($salary) {
        $schema["baseSalary"] = $salary;
    }
    
    if (!empty($job['benefits'])) {
        $schema["jobBenefits"] = sanitizeSchemaText($job['benefits'], 500);
    }
    
    if (!empty($job['requirements'])) {
        $schema["qualifications"] = sanitizeSchemaText($job['requirements'], 500);
    }
    
    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
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
