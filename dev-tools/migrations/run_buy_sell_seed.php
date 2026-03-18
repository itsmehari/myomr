<?php
/**
 * Seed OMR Buy-Sell with 5 sample listings.
 * Run after: php dev-tools/migrations/run_buy_sell_migration.php
 */
require_once __DIR__ . '/../../core/include-path.php';
require_once ROOT_PATH . '/core/omr-connect.php';

$sqlFile = __DIR__ . '/run_2026_03_14_seed_omr_buy_sell_sample.sql';
if (!file_exists($sqlFile)) {
    die("Seed file not found: $sqlFile\n");
}

$sql = file_get_contents($sqlFile);
$parts = array_filter(array_map('trim', explode(';', $sql)));

foreach ($parts as $q) {
    $q = trim($q);
    if (empty($q) || preg_match('/^\s*--/', $q)) continue;
    if ($conn->query($q)) {
        echo "OK: " . substr(str_replace(["\n", "\r"], ' ', $q), 0, 60) . "...\n";
    } else {
        echo "ERR: " . $conn->error . "\n";
    }
}

echo "Seed complete. 5 sample buy-sell listings added.\n";
