<?php
/**
 * Apply dev-tools/migrations/2026-03-31-job-seeker-profiles.sql (+ optional consent columns).
 * Usage: from repo root, with DB env or core/omr-connect credentials:
 *   php dev-tools/sql/run-job-seeker-profiles-migration.php
 */
declare(strict_types=1);

require_once __DIR__ . '/../../core/omr-connect.php';

$sqlFile = __DIR__ . '/../migrations/2026-03-31-job-seeker-profiles.sql';
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

// Idempotent consent columns (safe if already present)
$alters = [
    "ALTER TABLE job_seeker_profiles ADD COLUMN consent_contact TINYINT(1) NOT NULL DEFAULT 0",
    "ALTER TABLE job_seeker_profiles ADD COLUMN consent_at DATETIME DEFAULT NULL",
];
foreach ($alters as $alter) {
    if ($conn->query($alter)) {
        echo 'OK: ' . substr($alter, 0, 60) . '...' . PHP_EOL;
    } else {
        // 1060 = duplicate column name
        if ($conn->errno === 1060) {
            echo 'Skip (column exists): ' . $conn->error . PHP_EOL;
        } else {
            fwrite(STDERR, 'ALTER note: ' . $conn->error . PHP_EOL);
        }
    }
}

echo 'OK: job_seeker_profiles migration applied.' . PHP_EOL;

$v = $conn->query("SHOW CREATE TABLE job_seeker_profiles");
if ($v && $row = $v->fetch_assoc()) {
    echo $row['Create Table'] . PHP_EOL;
}
