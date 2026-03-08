<?php
// Adds locality, slug, verified columns to non-IT directory tables if missing
// Safe/idempotent: checks information_schema before altering

require_once __DIR__ . '/../../core/omr-connect.php';

function col_exists($conn, $table, $col) {
    $sql = "SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $table, $col);
    $stmt->execute();
    $res = $stmt->get_result();
    $ok = $res && $res->num_rows > 0;
    $stmt->close();
    return $ok;
}

function index_exists($conn, $table, $name) {
    $stmt = $conn->prepare("SHOW INDEX FROM `{$table}` WHERE Key_name = ?");
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $res = $stmt->get_result();
    $ok = $res && $res->num_rows > 0;
    $stmt->close();
    return $ok;
}

$targets = [
  // table => nameColumn
  'omrbankslist' => 'bankname',
  'omrhospitalslist' => 'hospitalname',
  'omrschoolslist' => 'schoolname',
  'omrparkslist' => 'parkname',
  'omr_industries' => 'industry_name',
  'omr_gov_offices' => 'office_name',
  'omr_restaurants' => 'name'
];

$report = [];
foreach ($targets as $table => $nameCol) {
    $added = [];
    if (!col_exists($conn, $table, 'locality')) {
        $ok = $conn->query("ALTER TABLE `{$table}` ADD COLUMN `locality` VARCHAR(100) NULL DEFAULT NULL AFTER `address`");
        $added[] = $ok ? 'locality' : ('locality(ERROR:' . $conn->error . ')');
    }
    if (!col_exists($conn, $table, 'slug')) {
        $ok = $conn->query("ALTER TABLE `{$table}` ADD COLUMN `slug` VARCHAR(260) NULL DEFAULT NULL");
        $added[] = $ok ? 'slug' : ('slug(ERROR:' . $conn->error . ')');
    }
    if (!col_exists($conn, $table, 'verified')) {
        $ok = $conn->query("ALTER TABLE `{$table}` ADD COLUMN `verified` TINYINT(1) NOT NULL DEFAULT 0");
        $added[] = $ok ? 'verified' : ('verified(ERROR:' . $conn->error . ')');
    }
    if (!index_exists($conn, $table, 'uniq_slug') && col_exists($conn, $table, 'slug')) {
        $conn->query("ALTER TABLE `{$table}` ADD UNIQUE KEY `uniq_slug` (`slug`)");
    }
    if (!index_exists($conn, $table, 'idx_name') && col_exists($conn, $table, $nameCol)) {
        $conn->query("ALTER TABLE `{$table}` ADD INDEX `idx_name` (`{$nameCol}`)");
    }
    if (!index_exists($conn, $table, 'idx_locality') && col_exists($conn, $table, 'locality')) {
        $conn->query("ALTER TABLE `{$table}` ADD INDEX `idx_locality` (`locality`)");
    }
    if (!index_exists($conn, $table, 'idx_name_locality') && col_exists($conn, $table, $nameCol) && col_exists($conn, $table, 'locality')) {
        $conn->query("ALTER TABLE `{$table}` ADD INDEX `idx_name_locality` (`{$nameCol}`, `locality`)");
    }
    $report[] = [$table, empty($added) ? 'no_changes' : implode(',', $added)];
}

header('Content-Type: text/plain');
foreach ($report as $r) {
    echo $r[0] . "\t" . $r[1] . "\n";
}


