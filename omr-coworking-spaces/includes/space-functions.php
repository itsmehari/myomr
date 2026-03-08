<?php
/**
 * Coworking Spaces Helper Functions
 * Central functions for the MyOMR Coworking Spaces Portal
 * 
 * @package MyOMR Coworking Spaces
 * @version 1.0.0
 */

/**
 * Get all space listings with filters (OPTIMIZED SQL VERSION)
 * 
 * @param array $filters Array of filters (locality, price_min, price_max, search, amenities)
 * @param int $limit Number of results per page
 * @param int $offset Offset for pagination
 * @return array Array of spaces
 */
function getSpaceListings($filters = [], $limit = 20, $offset = 0) {
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;

    if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
        return [];
    }
    
    $limitInt = max(1, min(100, (int)$limit)); // Limit between 1-100
    $offsetInt = max(0, (int)$offset);
    
    // Build WHERE clause dynamically
    $conditions = ["status = 'active'"];
    $params = [];
    $types = '';
    
    if (!empty($filters['locality'])) {
        $conditions[] = "locality LIKE ?";
        $params[] = '%' . $filters['locality'] . '%';
        $types .= 's';
    }
    
    if (!empty($filters['price_max'])) {
        $conditions[] = "hot_desk_monthly <= ?";
        $params[] = (float)$filters['price_max'];
        $types .= 'd';
    }
    
    if (!empty($filters['search'])) {
        $conditions[] = "(space_name LIKE ? OR brief_overview LIKE ? OR full_description LIKE ?)";
        $searchTerm = '%' . $filters['search'] . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'sss';
    }
    
    $whereClause = implode(' AND ', $conditions);
    
    // Build complete query
    $query = "SELECT * FROM coworking_spaces WHERE {$whereClause} ORDER BY featured DESC, created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limitInt;
    $params[] = $offsetInt;
    $types .= 'ii';
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log('getSpaceListings(): prepare() failed: ' . $conn->error);
        return [];
    }
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get single space by ID
 * 
 * @param int $space_id Space ID
 * @return array|null Space details or null
 */
function getSpaceById($space_id) {
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;
    
    if (!isset($conn) || !$conn instanceof mysqli) {
        error_log('getSpaceById(): $conn is null or invalid');
        return null;
    }
    
    $stmt = $conn->prepare("SELECT s.*, o.full_name as owner_name, o.email as owner_email, o.phone as owner_phone
                            FROM coworking_spaces s
                            LEFT JOIN space_owners o ON s.owner_id = o.id
                            WHERE s.id = ? AND s.status = 'active'");
    if (!$stmt) { 
        error_log('getSpaceById(): prepare() failed: ' . $conn->error); 
        return null; 
    }
    $stmt->bind_param("i", $space_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

/**
 * Get total space count with filters (OPTIMIZED SQL VERSION)
 * 
 * @param array $filters Array of filters
 * @return int Total count
 */
function getSpaceCount($filters = []) {
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;

    if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
        return 0;
    }
    
    // Build WHERE clause dynamically
    $conditions = ["status = 'active'"];
    $params = [];
    $types = '';
    
    if (!empty($filters['locality'])) {
        $conditions[] = "locality LIKE ?";
        $params[] = '%' . $filters['locality'] . '%';
        $types .= 's';
    }
    
    if (!empty($filters['price_max'])) {
        $conditions[] = "hot_desk_monthly <= ?";
        $params[] = (float)$filters['price_max'];
        $types .= 'd';
    }
    
    if (!empty($filters['search'])) {
        $conditions[] = "(space_name LIKE ? OR brief_overview LIKE ? OR full_description LIKE ?)";
        $searchTerm = '%' . $filters['search'] . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'sss';
    }
    
    $whereClause = implode(' AND ', $conditions);
    
    $query = "SELECT COUNT(*) as total FROM coworking_spaces WHERE {$whereClause}";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return 0;
    }
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return (int)($row['total'] ?? 0);
}

/**
 * Create SEO-friendly slug from text
 * 
 * @param string $text Text to slugify
 * @return string Slug
 */
function createSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text;
}

/**
 * Sanitize input to prevent XSS
 * 
 * @param string $data Input data
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email format
 * 
 * @param string $email Email address
 * @return bool True if valid
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Indian format)
 * 
 * @param string $phone Phone number
 * @return bool True if valid
 */
function validatePhone($phone) {
    return preg_match('/^(\+91)?[0-9]{10}$/', $phone);
}

/**
 * Format price for display
 * 
 * @param string $price Price string
 * @return string Formatted price
 */
function formatPrice($price) {
    if (empty($price)) {
        return 'Not Disclosed';
    }
    return '₹' . number_format($price);
}

/**
 * Increment space view count
 * 
 * @param int $space_id Space ID
 */
function incrementSpaceViews($space_id) {
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;
    if (!isset($conn) || !$conn instanceof mysqli) { return; }
    
    $stmt = $conn->prepare("UPDATE coworking_spaces SET views_count = views_count + 1 WHERE id = ?");
    if (!$stmt) { return; }
    $stmt->bind_param("i", $space_id);
    $stmt->execute();
    $stmt->close();
}

/**
 * Get space photos
 * 
 * @param int $space_id Space ID
 * @return array Array of photos
 */
function getSpacePhotos($space_id) {
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;
    if (!isset($conn) || !$conn instanceof mysqli) { return []; }
    
    $stmt = $conn->prepare("SELECT * FROM space_photos WHERE space_id = ? ORDER BY is_featured DESC, display_order ASC");
    if (!$stmt) { return []; }
    $stmt->bind_param("i", $space_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get related spaces (same locality)
 * 
 * @param int $space_id Current space ID
 * @param string $locality Space locality
 * @param int $limit Number of related spaces
 * @return array Array of related spaces
 */
function getRelatedSpaces($space_id, $locality, $limit = 3) {
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;
    if (!isset($conn) || !$conn instanceof mysqli) { return []; }
    
    $stmt = $conn->prepare("SELECT * FROM coworking_spaces 
                            WHERE locality = ? AND id != ? AND status = 'active'
                            ORDER BY created_at DESC
                            LIMIT ?");
    if (!$stmt) { return []; }
    $stmt->bind_param("sii", $locality, $space_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Generate pagination HTML
 * 
 * @param int $current_page Current page number
 * @param int $total_pages Total number of pages
 * @param string $base_url Base URL for pagination
 * @return string HTML pagination
 */
function generatePagination($current_page, $total_pages, $base_url) {
    if ($total_pages <= 1) {
        return '';
    }

    $buildPageUrl = function (int $page) use ($base_url) {
        $trimmedBase = rtrim($base_url, "?&");
        if ($page <= 1) {
            return $trimmedBase;
        }
        $separator = (strpos($trimmedBase, '?') !== false) ? '&' : '?';
        return $trimmedBase . $separator . 'page=' . $page;
    };

    $html = '<nav aria-label="Space listings pagination"><ul class="pagination justify-content-center">';

    // Previous button
    if ($current_page > 1) {
        $previousUrl = htmlspecialchars($buildPageUrl($current_page - 1), ENT_QUOTES, 'UTF-8');
        $html .= '<li class="page-item"><a class="page-link" href="' . $previousUrl . '">Previous</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
    }

    // Page numbers
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            $pageUrl = htmlspecialchars($buildPageUrl($i), ENT_QUOTES, 'UTF-8');
            $html .= '<li class="page-item"><a class="page-link" href="' . $pageUrl . '">' . $i . '</a></li>';
        }
    }

    // Next button
    if ($current_page < $total_pages) {
        $nextUrl = htmlspecialchars($buildPageUrl($current_page + 1), ENT_QUOTES, 'UTF-8');
        $html .= '<li class="page-item"><a class="page-link" href="' . $nextUrl . '">Next</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">Next</span></li>';
    }

    $html .= '</ul></nav>';

    return $html;
}

