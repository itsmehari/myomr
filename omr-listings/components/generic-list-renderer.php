<?php
require_once __DIR__ . '/../../core/omr-connect.php';

function column_exists(mysqli $conn, string $table, string $column): bool {
    $sql = "SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;
    $stmt->bind_param('ss', $table, $column);
    $stmt->execute();
    $res = $stmt->get_result();
    $ok = $res && $res->num_rows > 0;
    $stmt->close();
    return $ok;
}

function render_directory_list(array $config, array $filters, int $page = 1, int $perPage = 10): array {
    global $conn;
    $page = max(1, $page);
    $perPage = max(1, min(50, $perPage));
    $offset = ($page - 1) * $perPage;

    $table = $config['table'];
    $fields = $config['fields'];
    $idCol = $fields['id'];
    $nameCol = $fields['name'];
    $addressCol = $fields['address'] ?? null;
    $contactCol = $fields['contact'] ?? null;
    $localityCol = $fields['locality'] ?? 'locality';
    $industryCol = $fields['industry_type'] ?? null;
    $verifiedCol = $fields['verified'] ?? null;

    $where = [];
    $params = [];
    $types = '';

    if (!empty($filters['q'])) {
        $like = '%' . $filters['q'] . '%';
        $where[] = "($nameCol LIKE ? OR " . ($addressCol ?: $nameCol) . " LIKE ?)";
        $params[] = $like; $params[] = $like; $types .= 'ss';
    }
    if (!empty($filters['locality'])) {
        $where[] = ($localityCol ?: 'locality') . ' LIKE ?';
        $params[] = '%' . $filters['locality'] . '%';
        $types .= 's';
    }

    // Category-specific filters (implicit support for common columns)
    if (isset($filters['cuisine']) && $filters['cuisine'] !== '' && column_exists($conn, $table, 'cuisine')) {
        $where[] = 'cuisine LIKE ?'; $params[] = '%' . $filters['cuisine'] . '%'; $types .= 's';
    }
    if (isset($filters['rating']) && $filters['rating'] !== '' && column_exists($conn, $table, 'rating')) {
        $where[] = 'rating >= ?'; $params[] = (float)$filters['rating']; $types .= 'd';
    }
    if (isset($filters['cost']) && $filters['cost'] !== '' && column_exists($conn, $table, 'cost_for_two')) {
        $where[] = 'cost_for_two <= ?'; $params[] = (int)$filters['cost']; $types .= 'i';
    }
    $whereSql = count($where) ? (' WHERE ' . implode(' AND ', $where)) : '';

    $countSql = "SELECT COUNT(*) AS total FROM `$table`" . $whereSql;
    $countStmt = $conn->prepare($countSql);
    if ($types !== '' && $countStmt) { $countStmt->bind_param($types, ...$params); }
    $total = 0; $countRes = null;
    if ($countStmt && $countStmt->execute()) {
        $countRes = $countStmt->get_result();
        $row = $countRes ? $countRes->fetch_assoc() : ['total' => 0];
        $total = (int)$row['total'];
    }
    if ($countStmt) { $countStmt->close(); }

    $orderCol = $nameCol;
    if (!empty($filters['sort']) && $filters['sort'] === 'newest') {
        $orderCol = $idCol;
        $orderDir = 'DESC';
    } else {
        $orderDir = 'ASC';
    }

    $selectCols = [$idCol, $nameCol];
    if ($addressCol) $selectCols[] = $addressCol;
    if ($contactCol) $selectCols[] = $contactCol;
    if ($localityCol) $selectCols[] = $localityCol;
    if ($industryCol) $selectCols[] = $industryCol;
    if ($verifiedCol) $selectCols[] = $verifiedCol;

    $sql = 'SELECT ' . implode(', ', $selectCols) . " FROM `$table`" . $whereSql . " ORDER BY `$orderCol` $orderDir LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    if ($types !== '') {
        $types2 = $types . 'ii';
        $params2 = array_merge($params, [$perPage, $offset]);
        $stmt->bind_param($types2, ...$params2);
    } else {
        $stmt->bind_param('ii', $perPage, $offset);
    }
    $items = [];
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) { $items[] = $r; }
    }
    $stmt->close();

    return [
        'items' => $items,
        'total' => $total,
        'page' => $page,
        'perPage' => $perPage,
        'pages' => max(1, (int)ceil($total / $perPage))
    ];
}


