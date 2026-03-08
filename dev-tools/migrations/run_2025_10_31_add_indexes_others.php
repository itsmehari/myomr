<?php
// Safe index adder for other directory tables
// Usage: run via CLI or browser in a protected environment

require_once __DIR__ . '/../../core/omr-connect.php';

function column_exists($conn, $table, $column) {
    $sql = "SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $table, $column);
    $stmt->execute();
    $res = $stmt->get_result();
    $ok = $res && $res->num_rows > 0;
    $stmt->close();
    return $ok;
}

function index_exists($conn, $table, $index) {
    $sql = "SHOW INDEX FROM `{$table}` WHERE Key_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $index);
    $stmt->execute();
    $res = $stmt->get_result();
    $ok = $res && $res->num_rows > 0;
    $stmt->close();
    return $ok;
}

function add_index_if_missing($conn, $table, $indexName, $cols) {
    if (index_exists($conn, $table, $indexName)) return [true, 'exists'];
    $colsSql = implode(',', array_map(function($c){ return "`{$c}`"; }, $cols));
    $sql = "ALTER TABLE `{$table}` ADD INDEX `{$indexName}` ({$colsSql})";
    $ok = $conn->query($sql);
    return [$ok, $ok ? 'added' : $conn->error];
}

$targets = [
    // table => [candidate name columns], always tries locality if present
    'omr_banks' => ['bank_name','name','company_name'],
    'omr_hospitals' => ['hospital_name','name','company_name'],
    'omr_restaurants' => ['restaurant_name','name','company_name'],
    'omr_schools' => ['school_name','name','company_name'],
    'omr_parks' => ['park_name','name','company_name'],
    'omr_industries' => ['industry_name','name','company_name'],
    'omr_atms' => ['atm_name','name','company_name']
];

$report = [];
foreach ($targets as $table => $nameCandidates) {
    // detect present name column
    $nameCol = null;
    foreach ($nameCandidates as $cand) {
        if (column_exists($conn, $table, $cand)) { $nameCol = $cand; break; }
    }
    $localityCol = column_exists($conn, $table, 'locality') ? 'locality' : null;
    $industryCol = column_exists($conn, $table, 'industry_type') ? 'industry_type' : (column_exists($conn, $table, 'category') ? 'category' : null);

    if (!$nameCol && !$localityCol && !$industryCol) {
        $report[] = [$table, 'skipped', 'no expected columns'];
        continue;
    }

    if ($nameCol) {
        list($ok, $msg) = add_index_if_missing($conn, $table, 'idx_name', [$nameCol]);
        $report[] = [$table, 'idx_name', $msg];
    }
    if ($industryCol) {
        list($ok, $msg) = add_index_if_missing($conn, $table, 'idx_industry', [$industryCol]);
        $report[] = [$table, 'idx_industry', $msg];
    }
    if ($localityCol) {
        list($ok, $msg) = add_index_if_missing($conn, $table, 'idx_locality', [$localityCol]);
        $report[] = [$table, 'idx_locality', $msg];
    }
    if ($nameCol && $localityCol) {
        list($ok, $msg) = add_index_if_missing($conn, $table, 'idx_name_locality', [$nameCol, $localityCol]);
        $report[] = [$table, 'idx_name_locality', $msg];
    }
}

header('Content-Type: text/plain');
foreach ($report as $r) {
    echo implode("\t", $r) . "\n";
}


