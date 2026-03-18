<?php
/**
 * Inspect live remote job portal schema and counts.
 *
 * Usage:
 *   php dev-tools/jobs/inspect_remote_job_schema.php
 */

if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "Run this script from CLI only.\n");
    exit(1);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function connectJobDb(): mysqli
{
    $credentials = [
        'username' => 'metap8ok_myomr_admin',
        'password' => 'myomr@123',
        'database' => 'metap8ok_myomr',
    ];

    $hosts = [
        'localhost:3306',
        '127.0.0.1:3307',
        'myomr.in:3306',
    ];

    foreach ($hosts as $host) {
        try {
            $conn = new mysqli($host, $credentials['username'], $credentials['password'], $credentials['database']);
            $conn->set_charset('utf8mb4');
            echo "Connected to: {$host}\n";
            return $conn;
        } catch (mysqli_sql_exception $e) {
            echo "Connection failed on {$host}: {$e->getMessage()}\n";
        }
    }

    throw new RuntimeException('All database connection attempts failed.');
}

function printColumns(mysqli $conn, string $table): void
{
    echo "\n=== {$table} columns ===\n";
    $result = $conn->query("SHOW COLUMNS FROM {$table}");
    while ($row = $result->fetch_assoc()) {
        $default = $row['Default'];
        if ($default === null) {
            $default = 'NULL';
        }
        echo sprintf(
            "%-24s | %-30s | Null:%-3s | Key:%-3s | Default:%s\n",
            $row['Field'],
            $row['Type'],
            $row['Null'],
            $row['Key'],
            (string) $default
        );
    }
}

function printCounts(mysqli $conn): void
{
    echo "\n=== job_postings totals ===\n";

    $total = $conn->query("SELECT COUNT(*) AS c FROM job_postings")->fetch_assoc();
    echo "Total jobs: " . (int) ($total['c'] ?? 0) . "\n";

    echo "\nBy status:\n";
    $statusRows = $conn->query("SELECT status, COUNT(*) AS c FROM job_postings GROUP BY status ORDER BY c DESC");
    while ($row = $statusRows->fetch_assoc()) {
        echo "- " . ($row['status'] ?: '(empty)') . ": " . (int) $row['c'] . "\n";
    }

    echo "\nBy category:\n";
    $categoryRows = $conn->query("SELECT category, COUNT(*) AS c FROM job_postings GROUP BY category ORDER BY c DESC");
    while ($row = $categoryRows->fetch_assoc()) {
        echo "- " . ($row['category'] ?: '(empty)') . ": " . (int) $row['c'] . "\n";
    }
}

try {
    $conn = connectJobDb();
    printColumns($conn, 'job_postings');
    printColumns($conn, 'job_categories');
    printColumns($conn, 'employers');
    printCounts($conn);
    echo "\nDone.\n";
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, "Error: {$e->getMessage()}\n");
    exit(1);
}

