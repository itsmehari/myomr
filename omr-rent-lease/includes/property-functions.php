<?php
/**
 * Rent & Lease Property Helper Functions
 * @package MyOMR Rent & Lease
 * @version 1.0.0
 */

function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Get property listings with filters.
 * @param array $filters listing_type, locality, search
 * @param int $limit
 * @param int $offset
 * @return array
 */
function getRentLeaseProperties($filters = [], $limit = 20, $offset = 0) {
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;

    if (!$conn instanceof mysqli || $conn->connect_error) {
        return [];
    }

    // Check table exists
    $check = $conn->query("SHOW TABLES LIKE 'rent_lease_properties'");
    if (!$check || $check->num_rows === 0) {
        return [];
    }

    $conditions = ["status = 'approved'"];
    $params = [];
    $types = '';

    if (!empty($filters['listing_type'])) {
        $conditions[] = "listing_type = ?";
        $params[] = $filters['listing_type'];
        $types .= 's';
    }
    if (!empty($filters['locality'])) {
        $conditions[] = "locality LIKE ?";
        $params[] = '%' . $filters['locality'] . '%';
        $types .= 's';
    }
    if (!empty($filters['search'])) {
        $conditions[] = "(title LIKE ? OR property_details LIKE ?)";
        $s = '%' . $filters['search'] . '%';
        $params[] = $s;
        $params[] = $s;
        $types .= 'ss';
    }

    $where = implode(' AND ', $conditions);
    $params[] = (int)$limit;
    $params[] = (int)$offset;
    $types .= 'ii';

    $sql = "SELECT p.*, o.name as owner_name, o.phone as owner_phone
            FROM rent_lease_properties p
            LEFT JOIN rent_lease_owners o ON p.owner_id = o.id
            WHERE $where
            ORDER BY p.featured DESC, p.created_at DESC
            LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) return [];
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

/**
 * Get total count.
 */
function getRentLeaseCount($filters = []) {
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;

    if (!$conn instanceof mysqli || $conn->connect_error) return 0;
    $check = $conn->query("SHOW TABLES LIKE 'rent_lease_properties'");
    if (!$check || $check->num_rows === 0) return 0;

    $conditions = ["status = 'approved'"];
    $params = [];
    $types = '';
    if (!empty($filters['listing_type'])) {
        $conditions[] = "listing_type = ?";
        $params[] = $filters['listing_type'];
        $types .= 's';
    }
    if (!empty($filters['locality'])) {
        $conditions[] = "locality LIKE ?";
        $params[] = '%' . $filters['locality'] . '%';
        $types .= 's';
    }
    if (!empty($filters['search'])) {
        $conditions[] = "(title LIKE ? OR property_details LIKE ?)";
        $s = '%' . $filters['search'] . '%';
        $params[] = $s;
        $params[] = $s;
        $types .= 'ss';
    }

    $where = implode(' AND ', $conditions);
    $sql = "SELECT COUNT(*) as total FROM rent_lease_properties WHERE $where";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return 0;
    if (!empty($params)) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return (int)($row['total'] ?? 0);
}

/**
 * Get single property by ID.
 */
function getRentLeasePropertyById($id) {
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;

    if (!$conn instanceof mysqli || $conn->connect_error) return null;
    $id = (int)$id;
    if ($id <= 0) return null;

    $stmt = $conn->prepare("SELECT p.*, o.name as owner_name, o.phone as owner_phone, o.email as owner_email
                            FROM rent_lease_properties p
                            LEFT JOIN rent_lease_owners o ON p.owner_id = o.id
                            WHERE p.id = ? AND p.status = 'approved'");
    if (!$stmt) return null;
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ?: null;
}
