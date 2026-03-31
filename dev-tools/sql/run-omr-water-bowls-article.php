<?php
/**
 * Apply ADD-OMR-WATER-BOWLS-FOR-BIRDS-AND-ANIMALS-ARTICLE.sql to production articles table.
 * Usage (PowerShell): $env:DB_HOST='myomr.in'; php dev-tools/sql/run-omr-water-bowls-article.php
 */
declare(strict_types=1);

require_once __DIR__ . '/../../core/omr-connect.php';

// Intentionally no pre-delete.
// The SQL file uses INSERT ... ON DUPLICATE KEY UPDATE for smooth idempotent updates.
// This preserves row continuity (id/history relationships) while updating content safely.

$sqlFile = __DIR__ . '/ADD-OMR-WATER-BOWLS-FOR-BIRDS-AND-ANIMALS-ARTICLE.sql';
if (!is_readable($sqlFile)) {
    fwrite(STDERR, 'Cannot read: ' . $sqlFile . PHP_EOL);
    exit(1);
}

$raw = file_get_contents($sqlFile);
if ($raw === false) {
    fwrite(STDERR, 'Failed to read SQL file.' . PHP_EOL);
    exit(1);
}

$lines = preg_split('/\R/', $raw);
$kept = [];
foreach ($lines as $line) {
    if (preg_match('/^\s*--/', $line)) {
        continue;
    }
    $kept[] = $line;
}
$sql = trim(implode("\n", $kept));
if ($sql === '') {
    fwrite(STDERR, 'No SQL left after stripping comment lines.' . PHP_EOL);
    exit(1);
}

if (!$conn->multi_query($sql)) {
    fwrite(STDERR, 'SQL error: ' . $conn->error . PHP_EOL);
    exit(1);
}

do {
    if ($result = $conn->store_result()) {
        $result->free();
    }
    if (!$conn->more_results()) {
        break;
    }
    if (!$conn->next_result()) {
        fwrite(STDERR, 'Batch error: ' . $conn->error . PHP_EOL);
        exit(1);
    }
} while (true);

echo 'OK: article SQL applied.' . PHP_EOL;

$verifySql = "SELECT slug, status, DATE_FORMAT(published_date, '%Y-%m-%d %H:%i:%s') AS published_date
              FROM articles
              WHERE slug IN ('omr-apartment-community-water-bowls-for-birds-and-stray-animals','omr-apartment-community-water-bowls-for-birds-and-stray-animals-tamil')
              ORDER BY slug";
$verify = $conn->query($verifySql);
if (!$verify) {
    fwrite(STDERR, 'Verify query failed: ' . $conn->error . PHP_EOL);
    exit(1);
}

echo 'VERIFY:' . PHP_EOL;
while ($row = $verify->fetch_assoc()) {
    echo ' - ' . $row['slug'] . ' | ' . $row['status'] . ' | ' . $row['published_date'] . PHP_EOL;
}
