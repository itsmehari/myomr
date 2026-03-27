<?php
/**
 * Apply 2026-03-26-draft-articles-tn-news-mar24-25.sql to the articles table.
 * Remote: set DB_HOST (e.g. myomr.in). See README-REMOTE-DATABASE.md.
 *
 * Usage: DB_HOST=myomr.in php dev-tools/sql/run-2026-03-26-draft-articles.php
 */
declare(strict_types=1);

require_once __DIR__ . '/../../core/omr-connect.php';

$slugs = [
    'chennai-evm-vvpat-randomisation-2026-assembly-elections',
    'chennai-evm-vvpat-randomisation-2026-assembly-elections-tamil',
    'aiadmk-first-candidate-list-2026-tn-assembly-elections',
    'aiadmk-first-candidate-list-2026-tn-assembly-elections-tamil',
    'ntk-2026-manifesto-five-capitals-state-rail-supreme-court-bench',
    'ntk-2026-manifesto-five-capitals-state-rail-supreme-court-bench-tamil',
    'omr-restaurants-lpg-crisis-chennai-march-2026',
    'omr-restaurants-lpg-crisis-chennai-march-2026-tamil',
];

$escaped = array_map(static function (string $s) use ($conn): string {
    return "'" . $conn->real_escape_string($s) . "'";
}, $slugs);

$deleteSql = 'DELETE FROM articles WHERE slug IN (' . implode(',', $escaped) . ')';
if (!$conn->query($deleteSql)) {
    fwrite(STDERR, 'DELETE failed: ' . $conn->error . PHP_EOL);
    exit(1);
}
echo 'DELETE: removed ' . $conn->affected_rows . ' existing row(s) for these slugs.' . PHP_EOL;

$sqlFile = __DIR__ . '/2026-03-26-draft-articles-tn-news-mar24-25.sql';
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

echo 'OK: INSERT batch finished.' . PHP_EOL;
