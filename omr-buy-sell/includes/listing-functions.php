<?php
/**
 * OMR Buy-Sell — Listing Helper Functions
 */
function sanitizeInputBuySell($data) {
    return htmlspecialchars(trim($data ?? ''), ENT_QUOTES, 'UTF-8');
}

function createSlugBuySell(string $text): string {
    $text = mb_strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

function getListingDetailUrl(int $id, ?string $title = null): string {
    $base = 'https://myomr.in/omr-buy-sell/listing/' . (int)$id;
    if (!empty($title)) {
        $slug = createSlugBuySell($title);
        if ($slug !== '') $base .= '/' . $slug;
    }
    return $base;
}

function getListingDetailPath(int $id, ?string $title = null): string {
    $base = 'listing/' . (int)$id;
    if (!empty($title)) {
        $slug = createSlugBuySell($title);
        if ($slug !== '') $base .= '/' . $slug;
    }
    return $base;
}

function getBuySellListings(array $filters = [], int $limit = 9, int $offset = 0): array {
    global $conn;
    if (!$conn instanceof mysqli || $conn->connect_error) return [];

    $check = $conn->query("SHOW TABLES LIKE 'omr_buy_sell_listings'");
    if (!$check || $check->num_rows === 0) return [];

    $conditions = ["l.status = 'approved'"];
    $params = [];
    $types = '';

    if (!empty($filters['category_id'])) {
        $conditions[] = 'l.category_id = ?';
        $params[] = (int)$filters['category_id'];
        $types .= 'i';
    }
    if (!empty($filters['locality'])) {
        $conditions[] = 'l.locality LIKE ?';
        $params[] = '%' . $filters['locality'] . '%';
        $types .= 's';
    }
    if (isset($filters['price_min']) && $filters['price_min'] !== '' && $filters['price_min'] !== null) {
        $conditions[] = 'l.price >= ?';
        $params[] = (float)$filters['price_min'];
        $types .= 'd';
    }
    if (isset($filters['price_max']) && $filters['price_max'] !== '' && $filters['price_max'] !== null) {
        $conditions[] = 'l.price <= ?';
        $params[] = (float)$filters['price_max'];
        $types .= 'd';
    }
    if (!empty($filters['search'])) {
        $conditions[] = '(l.title LIKE ? OR l.description LIKE ?)';
        $s = '%' . $filters['search'] . '%';
        $params[] = $s;
        $params[] = $s;
        $types .= 'ss';
    }

    $where = implode(' AND ', $conditions);
    $params[] = (int)$limit;
    $params[] = (int)$offset;
    $types .= 'ii';

    $sort = $filters['sort'] ?? 'newest';
    $order = 'l.featured DESC, l.created_at DESC';
    if ($sort === 'price_asc') $order = 'l.featured DESC, (l.price IS NULL), l.price ASC, l.created_at DESC';
    elseif ($sort === 'price_desc') $order = 'l.featured DESC, (l.price IS NULL), l.price DESC, l.created_at DESC';

    $sql = "SELECT l.*, c.name as category_name, c.slug as category_slug, s.name as seller_name
            FROM omr_buy_sell_listings l
            LEFT JOIN omr_buy_sell_categories c ON l.category_id = c.id
            LEFT JOIN omr_buy_sell_sellers s ON l.seller_id = s.id
            WHERE $where
            ORDER BY $order
            LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) return [];
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getBuySellCount(array $filters = []): int {
    global $conn;
    if (!$conn instanceof mysqli || $conn->connect_error) return 0;

    $check = $conn->query("SHOW TABLES LIKE 'omr_buy_sell_listings'");
    if (!$check || $check->num_rows === 0) return 0;

    $conditions = ["status = 'approved'"];
    $params = [];
    $types = '';

    if (!empty($filters['category_id'])) {
        $conditions[] = 'category_id = ?';
        $params[] = (int)$filters['category_id'];
        $types .= 'i';
    }
    if (!empty($filters['locality'])) {
        $conditions[] = 'locality LIKE ?';
        $params[] = '%' . $filters['locality'] . '%';
        $types .= 's';
    }
    if (isset($filters['price_min']) && $filters['price_min'] !== '' && $filters['price_min'] !== null) {
        $conditions[] = 'price >= ?';
        $params[] = (float)$filters['price_min'];
        $types .= 'd';
    }
    if (isset($filters['price_max']) && $filters['price_max'] !== '' && $filters['price_max'] !== null) {
        $conditions[] = 'price <= ?';
        $params[] = (float)$filters['price_max'];
        $types .= 'd';
    }
    if (!empty($filters['search'])) {
        $conditions[] = '(title LIKE ? OR description LIKE ?)';
        $s = '%' . $filters['search'] . '%';
        $params[] = $s;
        $params[] = $s;
        $types .= 'ss';
    }

    $where = implode(' AND ', $conditions);
    $sql = "SELECT COUNT(*) as n FROM omr_buy_sell_listings WHERE $where";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return 0;
    if (!empty($params)) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return (int)($row['n'] ?? 0);
}

function getBuySellListingById(int $id): ?array {
    global $conn;
    if (!$conn instanceof mysqli || $conn->connect_error) return null;

    $stmt = $conn->prepare("SELECT l.*, c.name as category_name, c.slug as category_slug, s.name as seller_name
            FROM omr_buy_sell_listings l
            LEFT JOIN omr_buy_sell_categories c ON l.category_id = c.id
            LEFT JOIN omr_buy_sell_sellers s ON l.seller_id = s.id
            WHERE l.id = ? AND l.status IN ('approved','sold','expired')");
    if (!$stmt) return null;
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc() ?: null;
}

function incrementListingViewCount(int $id): void {
    global $conn;
    if (!$conn instanceof mysqli || $conn->connect_error) return;
    $stmt = $conn->prepare("UPDATE omr_buy_sell_listings SET view_count = view_count + 1 WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}

function getRelatedListings(int $listing_id, int $category_id, string $locality, int $limit = 4): array {
    global $conn;
    if (!$conn instanceof mysqli || $conn->connect_error) return [];

    $stmt = $conn->prepare("SELECT l.*, c.name as category_name, c.slug as category_slug
            FROM omr_buy_sell_listings l
            LEFT JOIN omr_buy_sell_categories c ON l.category_id = c.id
            WHERE l.status = 'approved' AND l.id != ? AND l.category_id = ? AND l.locality = ?
            ORDER BY l.created_at DESC LIMIT ?");
    if (!$stmt) return [];

    $stmt->bind_param('iisi', $listing_id, $category_id, $locality, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    if (count($rows) < $limit) {
        $stmt2 = $conn->prepare("SELECT l.*, c.name as category_name, c.slug as category_slug
                FROM omr_buy_sell_listings l
                LEFT JOIN omr_buy_sell_categories c ON l.category_id = c.id
                WHERE l.status = 'approved' AND l.id != ? AND l.category_id = ?
                ORDER BY l.created_at DESC LIMIT ?");
        if ($stmt2) {
            $remaining = $limit - count($rows);
            $stmt2->bind_param('iii', $listing_id, $category_id, $remaining);
            $stmt2->execute();
            $r2 = $stmt2->get_result();
            $ids = array_column($rows, 'id');
            while ($row = $r2->fetch_assoc()) {
                if (!in_array($row['id'], $ids)) $rows[] = $row;
            }
        }
    }
    return array_slice($rows, 0, $limit);
}

function getBuySellCategories(): array {
    global $conn;
    if (!$conn instanceof mysqli || $conn->connect_error) return [];

    $check = $conn->query("SHOW TABLES LIKE 'omr_buy_sell_categories'");
    if (!$check || $check->num_rows === 0) return [];

    $res = $conn->query("SELECT id, name, slug, parent_id, sort_order FROM omr_buy_sell_categories ORDER BY sort_order ASC, name ASC");
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}
