<?php
/**
 * Run Employer Pack plan columns migration.
 * Tries multiple hosts: localhost, SSH tunnel (3307), remote (myomr.in) so you can run from local to live.
 *
 * Usage: php dev-tools/migrations/run_employer_pack_plan_columns.php
 *        php dev-tools/migrations/run_employer_pack_plan_columns.php --apply
 */
if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "CLI only.\n");
    exit(1);
}

$apply = in_array('--apply', $argv ?? [], true);

$credentials = [
    'username' => 'metap8ok_myomr_admin',
    'password' => 'myomr@123',
    'database' => 'metap8ok_myomr',
];
$hosts = ['localhost:3306', '127.0.0.1:3307', '127.0.0.1:3306', 'myomr.in:3306'];

$conn = null;
foreach ($hosts as $host) {
    try {
        $conn = new mysqli($host, $credentials['username'], $credentials['password'], $credentials['database']);
        if (!$conn->connect_error) {
            $conn->set_charset('utf8mb4');
            echo "Connected to {$host}\n";
            break;
        }
        $conn = null;
    } catch (Throwable $e) {
        echo "Skip {$host}: {$e->getMessage()}\n";
        $conn = null;
    }
}

if (!$conn instanceof mysqli) {
    fwrite(STDERR, "Could not connect to database. Tried: " . implode(', ', $hosts) . "\n");
    fwrite(STDERR, "For remote: ensure cPanel Remote MySQL allows your IP, or use SSH tunnel (port 3307).\n");
    exit(1);
}

function employerHasPlanColumn(mysqli $conn, string $col): bool
{
    $stmt = $conn->prepare("
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'employers' AND COLUMN_NAME = ?
        LIMIT 1
    ");
    $stmt->bind_param('s', $col);
    $stmt->execute();
    return (bool) $stmt->get_result()->fetch_assoc();
}

$hasPlanType = employerHasPlanColumn($conn, 'plan_type');
$hasPlanStart = employerHasPlanColumn($conn, 'plan_start_date');
$hasPlanEnd = employerHasPlanColumn($conn, 'plan_end_date');

echo "employers.plan_type:      " . ($hasPlanType ? "exists" : "missing") . "\n";
echo "employers.plan_start_date: " . ($hasPlanStart ? "exists" : "missing") . "\n";
echo "employers.plan_end_date:   " . ($hasPlanEnd ? "exists" : "missing") . "\n";

if ($hasPlanType && $hasPlanStart && $hasPlanEnd) {
    echo "\nAll Employer Pack columns already present. Nothing to do.\n";
    exit(0);
}

if (!$apply) {
    echo "\nPreview only. Run with --apply to execute migration.\n";
    exit(0);
}

echo "\nApplying migration...\n";
$conn->begin_transaction();

try {
    if (!$hasPlanType) {
        $conn->query("ALTER TABLE employers ADD COLUMN plan_type VARCHAR(32) NOT NULL DEFAULT 'free' COMMENT 'free, employer_pack_10, employer_pack_20' AFTER status");
        echo "Added plan_type.\n";
    }
    if (!$hasPlanStart) {
        $conn->query("ALTER TABLE employers ADD COLUMN plan_start_date DATE NULL COMMENT 'Start of current subscription period' AFTER plan_type");
        echo "Added plan_start_date.\n";
    }
    if (!$hasPlanEnd) {
        $conn->query("ALTER TABLE employers ADD COLUMN plan_end_date DATE NULL COMMENT 'End of current subscription period' AFTER plan_start_date");
        echo "Added plan_end_date.\n";
    }
    $conn->commit();
    echo "Migration completed successfully.\n";
} catch (Throwable $e) {
    $conn->rollback();
    fwrite(STDERR, "Error: " . $e->getMessage() . "\n");
    exit(1);
}
