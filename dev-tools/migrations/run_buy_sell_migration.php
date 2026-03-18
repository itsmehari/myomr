<?php
/**
 * Run buy-sell migration
 */
require_once __DIR__ . '/../../core/omr-connect.php';
$sql = file_get_contents(__DIR__ . '/run_2026_03_14_create_omr_buy_sell_tables.sql');
$parts = array_filter(array_map('trim', explode(';', $sql)));
foreach ($parts as $q) {
    if (empty($q) || strpos($q, '--') === 0) continue;
    if ($conn->query($q)) echo "OK\n";
    else echo "ERR: " . $conn->error . "\n";
}
echo "Migration complete.\n";
