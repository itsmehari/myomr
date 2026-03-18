<?php
/**
 * Preview/apply migration for work_segment column.
 *
 * Usage:
 *   php dev-tools/jobs/run_work_segment_migration.php           (preview only)
 *   php dev-tools/jobs/run_work_segment_migration.php --apply   (executes SQL)
 */

if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "CLI only.\n");
    exit(1);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function connectDb(): mysqli
{
    $credentials = [
        'username' => 'metap8ok_myomr_admin',
        'password' => 'myomr@123',
        'database' => 'metap8ok_myomr',
    ];
    $hosts = ['localhost:3306', '127.0.0.1:3307', 'myomr.in:3306'];

    foreach ($hosts as $host) {
        try {
            $conn = new mysqli($host, $credentials['username'], $credentials['password'], $credentials['database']);
            $conn->set_charset('utf8mb4');
            echo "Connected to {$host}\n";
            return $conn;
        } catch (mysqli_sql_exception $e) {
            echo "Connection failed on {$host}: {$e->getMessage()}\n";
        }
    }

    throw new RuntimeException('Unable to connect to database.');
}

function getColumnStatus(mysqli $conn): array
{
    $result = $conn->query("
        SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'job_postings'
          AND COLUMN_NAME = 'work_segment'
        LIMIT 1
    ");
    return $result->fetch_assoc() ?: [];
}

function getSegmentCounts(mysqli $conn): array
{
    $counts = [];
    $hasColumn = !empty(getColumnStatus($conn));
    if ($hasColumn) {
        $r = $conn->query("SELECT work_segment, COUNT(*) AS c FROM job_postings GROUP BY work_segment ORDER BY c DESC");
        while ($row = $r->fetch_assoc()) {
            $counts[(string)($row['work_segment'] ?? '(empty)')] = (int)$row['c'];
        }
    }
    return $counts;
}

$apply = in_array('--apply', $argv, true);

try {
    $conn = connectDb();
    $beforeColumn = getColumnStatus($conn);
    $beforeCounts = getSegmentCounts($conn);

    echo "\n== Current status ==\n";
    if (!empty($beforeColumn)) {
        echo "work_segment exists: yes\n";
        echo "type: {$beforeColumn['COLUMN_TYPE']} | nullable: {$beforeColumn['IS_NULLABLE']} | default: " . ($beforeColumn['COLUMN_DEFAULT'] ?? 'NULL') . "\n";
        echo "current counts: " . json_encode($beforeCounts, JSON_UNESCAPED_SLASHES) . "\n";
    } else {
        echo "work_segment exists: no\n";
    }

    if (!$apply) {
        echo "\nPreview only. No DB changes executed.\n";
        echo "Run with --apply to execute migration.\n";
        exit(0);
    }

    echo "\nApplying migration...\n";
    $conn->begin_transaction();

    if (empty($beforeColumn)) {
        $conn->query("ALTER TABLE job_postings ADD COLUMN work_segment ENUM('office', 'manpower', 'hybrid') NULL AFTER job_type");
    }

    $conn->query("
        UPDATE job_postings
        SET work_segment = 'manpower'
        WHERE
            (
                LOWER(IFNULL(category, '')) IN ('hospitality', 'construction', 'housekeeping', 'logistics')
                OR LOWER(IFNULL(title, '')) REGEXP 'tea|cook|helper|delivery|driver|housekeeping|cleaner|security|technician|mechanic|welder|plumber|labou?r|waiter|kitchen'
                OR LOWER(IFNULL(description, '')) REGEXP 'tea|cook|helper|delivery|driver|housekeeping|cleaner|security|technician|mechanic|welder|plumber|labou?r|waiter|kitchen'
            )
    ");

    $conn->query("UPDATE job_postings SET work_segment = 'office' WHERE work_segment IS NULL OR work_segment = ''");
    $conn->query("ALTER TABLE job_postings MODIFY COLUMN work_segment ENUM('office', 'manpower', 'hybrid') NOT NULL DEFAULT 'office'");

    $indexExistsResult = $conn->query("
        SELECT 1
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'job_postings'
          AND INDEX_NAME = 'idx_job_work_segment_status_created'
        LIMIT 1
    ");
    $indexExists = (bool) ($indexExistsResult->fetch_assoc() ?: null);
    if (!$indexExists) {
        $conn->query("CREATE INDEX idx_job_work_segment_status_created ON job_postings (work_segment, status, created_at)");
    }

    $conn->commit();

    $afterColumn = getColumnStatus($conn);
    $afterCounts = getSegmentCounts($conn);

    echo "Migration completed.\n";
    echo "work_segment type: {$afterColumn['COLUMN_TYPE']} | default: " . ($afterColumn['COLUMN_DEFAULT'] ?? 'NULL') . "\n";
    echo "updated counts: " . json_encode($afterCounts, JSON_UNESCAPED_SLASHES) . "\n";
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, "Error: {$e->getMessage()}\n");
    exit(1);
}

