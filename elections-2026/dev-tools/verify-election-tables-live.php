<?php
/**
 * Verify Elections 2026 tables on the database (local or remote).
 * Use to confirm tables exist and see row counts after running the migration.
 *
 * From repo root:
 *   Local:  php elections-2026/dev-tools/verify-election-tables-live.php
 *   Remote: DB_HOST=myomr.in php elections-2026/dev-tools/verify-election-tables-live.php
 *   PowerShell: $env:DB_HOST='myomr.in'; php elections-2026/dev-tools/verify-election-tables-live.php
 */
$root = dirname(__DIR__, 2);

$db_host = getenv('DB_HOST');
$db_port = getenv('DB_PORT') ?: '3306';
$db_user = getenv('DB_USER') ?: null;
$db_pass = getenv('DB_PASS') ?: null;
$db_name = getenv('DB_NAME') ?: null;

if ($db_host) {
    // Remote: use env or same fallbacks as run-election-2026-migration.php (do not require omr-connect – it would connect to localhost).
    $db_user = $db_user !== null && $db_user !== '' ? $db_user : 'metap8ok_myomr_admin';
    $db_pass = $db_pass !== null && $db_pass !== '' ? $db_pass : 'myomr@123';
    $db_name = $db_name !== null && $db_name !== '' ? $db_name : 'metap8ok_myomr';
    $conn = @new mysqli($db_host, $db_user, $db_pass, $db_name, (int) $db_port);
} else {
    require_once $root . '/core/omr-connect.php';
}

if (!isset($conn) || !($conn instanceof mysqli) || $conn->connect_error) {
    fwrite(STDERR, "Database connection failed: " . ($conn->connect_error ?? 'unknown') . "\n");
    exit(1);
}

$conn->set_charset('utf8mb4');

$tables = ['election_2026_candidates', 'election_2026_announcements'];
$ok = true;

foreach ($tables as $table) {
    $r = $conn->query("SHOW TABLES LIKE '" . $conn->real_escape_string($table) . "'");
    if ($r && $r->num_rows > 0) {
        $count = $conn->query("SELECT COUNT(*) AS n FROM `" . $conn->real_escape_string($table) . "`");
        $n = $count ? (int) $count->fetch_assoc()['n'] : 0;
        echo "[OK] $table exists, rows: $n\n";
    } else {
        echo "[MISSING] $table does not exist.\n";
        $ok = false;
    }
}

if (!$ok) {
    fwrite(STDERR, "Run the migration to create and seed tables: php elections-2026/dev-tools/run-election-2026-migration.php\n");
    exit(1);
}

echo "Verification done. Election 2026 tables are present on this database.\n";
