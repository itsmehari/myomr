<?php
/**
 * Run OMR Classified Ads migrations (core tables + optional auth-linking tables).
 * Remote: DB_HOST=myomr.in php dev-tools/migrations/run_classified_ads_migration.php
 */
$root = dirname(__DIR__, 2);
require_once $root . '/core/omr-connect.php';

function run_sql_file(mysqli $conn, string $path): void {
    if (!is_readable($path)) {
        echo "Skip (not found): $path\n";
        return;
    }
    $sql = file_get_contents($path);
    // Strip one-line comments that start a line
    $lines = explode("\n", $sql);
    $buf = '';
    foreach ($lines as $line) {
        $t = trim($line);
        if ($t === '' || strpos($t, '--') === 0) {
            continue;
        }
        $buf .= $line . "\n";
    }
    $parts = array_filter(array_map('trim', explode(';', $buf)));
    foreach ($parts as $q) {
        if ($q === '') {
            continue;
        }
        try {
            $ok = $conn->query($q);
        } catch (mysqli_sql_exception $e) {
            $errno = (int) $conn->errno;
            if ($errno === 0) {
                $errno = (int) $e->getCode();
            }
            if (in_array($errno, [1060, 1061, 1091], true)) {
                echo "SKIP (already applied): " . $e->getMessage() . "\n";
            } else {
                throw $e;
            }
            continue;
        }
        if (!$ok) {
            $errno = $conn->errno;
            if (in_array($errno, [1060, 1061, 1091], true)) {
                echo "SKIP (already applied): " . $conn->error . "\n";
            } else {
                echo "ERR: " . $conn->error . "\n---\n" . substr($q, 0, 200) . "...\n";
            }
        } else {
            echo "OK (" . substr(str_replace(["\n", "\r"], ' ', $q), 0, 72) . "…)\n";
        }
    }
}

echo "Connected to database.\n";
run_sql_file($conn, __DIR__ . '/2026_03_20_create_omr_classified_ads.sql');
run_sql_file($conn, __DIR__ . '/2026_03_21_classified_ads_auth_linking.sql');
echo "Done.\n";
