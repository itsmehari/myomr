<?php
// Run migration 2025-10-31: Directory schema standardization (IT companies)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../core/omr-connect.php';

function column_exists(mysqli $conn, string $table, string $column): bool {
    $stmt = $conn->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
    $stmt->bind_param('s', $column);
    $stmt->execute();
    $res = $stmt->get_result();
    $exists = $res && $res->num_rows > 0;
    $stmt->close();
    return $exists;
}

function index_exists(mysqli $conn, string $table, string $index): bool {
    $stmt = $conn->prepare("SHOW INDEX FROM `$table` WHERE Key_name = ?");
    $stmt->bind_param('s', $index);
    $stmt->execute();
    $res = $stmt->get_result();
    $exists = $res && $res->num_rows > 0;
    $stmt->close();
    return $exists;
}

function add_column_if_missing(mysqli $conn, string $table, string $ddl): bool {
    return $conn->query($ddl) === true;
}

echo "=== Migration 2025-10-31: Directory schema (IT) ===\n";

$table = 'omr_it_companies';

// Columns
if (!column_exists($conn, $table, 'locality')) {
    echo "Adding column: locality...\n";
    $conn->query("ALTER TABLE `$table` ADD COLUMN `locality` VARCHAR(100) NULL AFTER `address`");
}
if (!column_exists($conn, $table, 'slug')) {
    echo "Adding column: slug...\n";
    $conn->query("ALTER TABLE `$table` ADD COLUMN `slug` VARCHAR(260) NULL AFTER `locality`");
}
if (!column_exists($conn, $table, 'verified')) {
    echo "Adding column: verified...\n";
    $conn->query("ALTER TABLE `$table` ADD COLUMN `verified` TINYINT(1) NOT NULL DEFAULT 0 AFTER `slug`");
}

// Indexes
if (!index_exists($conn, $table, 'uniq_it_slug')) {
    echo "Adding unique index: uniq_it_slug (slug)...\n";
    $conn->query("ALTER TABLE `$table` ADD UNIQUE KEY `uniq_it_slug` (`slug`)");
}
if (!index_exists($conn, $table, 'idx_it_name')) {
    echo "Adding index: idx_it_name (company_name)...\n";
    $conn->query("CREATE INDEX `idx_it_name` ON `$table` (`company_name`)");
}
if (!index_exists($conn, $table, 'idx_it_industry')) {
    echo "Adding index: idx_it_industry (industry_type)...\n";
    $conn->query("CREATE INDEX `idx_it_industry` ON `$table` (`industry_type`)");
}
if (!index_exists($conn, $table, 'idx_it_locality')) {
    echo "Adding index: idx_it_locality (locality)...\n";
    $conn->query("CREATE INDEX `idx_it_locality` ON `$table` (`locality`)");
}
if (!index_exists($conn, $table, 'idx_it_name_locality')) {
    echo "Adding index: idx_it_name_locality (company_name, locality)...\n";
    $conn->query("CREATE INDEX `idx_it_name_locality` ON `$table` (`company_name`, `locality`)");
}

echo "Done.\n";


