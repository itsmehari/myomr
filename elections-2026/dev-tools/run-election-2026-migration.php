<?php
/**
 * Run Elections 2026 DB migration: create tables and seed data.
 * Execute from repo root: php elections-2026/dev-tools/run-election-2026-migration.php
 *
 * For remote DB (cPanel): set DB_HOST to your MySQL server hostname, then run:
 *   PHP: putenv('DB_HOST','myomr.in'); or CLI: set DB_HOST=myomr.in && php ...
 *   PowerShell: $env:DB_HOST='myomr.in'; php elections-2026/dev-tools/run-election-2026-migration.php
 */
$root = dirname(__DIR__, 2);

$db_host = getenv('DB_HOST');
$db_port = getenv('DB_PORT') ?: '3306';
$db_user = getenv('DB_USER') ?: 'metap8ok_myomr_admin';
$db_pass = getenv('DB_PASS') ?: 'myomr@123';
$db_name = getenv('DB_NAME') ?: 'metap8ok_myomr';

if ($db_host) {
    $conn = @new mysqli($db_host, $db_user, $db_pass, $db_name, (int) $db_port);
} else {
    require_once $root . '/core/omr-connect.php';
}

if (!isset($conn) || !($conn instanceof mysqli) || $conn->connect_error) {
    fwrite(STDERR, "Database connection failed: " . ($conn->connect_error ?? 'unknown') . "\n");
    exit(1);
}

$devTools = __DIR__;
$conn->set_charset('utf8mb4');

function run_sql_file($conn, $path) {
    $sql = file_get_contents($path);
    if ($sql === false) {
        fwrite(STDERR, "Could not read: $path\n");
        return false;
    }
    // Remove line comments (-- to EOL) and blank lines; keep semicolons for multi_query
    $sql = preg_replace('/\s*--[^\n]*\n/', "\n", $sql);
    $sql = trim($sql);
    if (empty($sql)) {
        return true;
    }
    if (!$conn->multi_query($sql)) {
        fwrite(STDERR, "Multi-query error: " . $conn->error . "\n");
        return false;
    }
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    if ($conn->errno) {
        fwrite(STDERR, "Query error: " . $conn->error . "\n");
        return false;
    }
    return true;
}

echo "Running create-election-2026-tables.sql ...\n";
if (!run_sql_file($conn, $devTools . '/create-election-2026-tables.sql')) {
    exit(1);
}
echo "Tables created.\n";

echo "Running seed-election-2026.sql ...\n";
if (!run_sql_file($conn, $devTools . '/seed-election-2026.sql')) {
    exit(1);
}
echo "Seed data loaded.\n";

// Quick check
$r = $conn->query("SELECT COUNT(*) AS n FROM election_2026_candidates");
$c = $r ? (int) $r->fetch_assoc()['n'] : 0;
$r = $conn->query("SELECT COUNT(*) AS n FROM election_2026_announcements");
$a = $r ? (int) $r->fetch_assoc()['n'] : 0;
echo "Done. election_2026_candidates: $c rows, election_2026_announcements: $a rows.\n";
