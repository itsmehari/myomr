<?php
/**
 * Print one canonical HTTPS job detail URL per line (approved jobs only).
 * Uses same URL logic as the job sitemap (getJobDetailUrl).
 *
 * Usage:
 *   php dev-tools/jobs/export_approved_job_urls.php
 *   php dev-tools/jobs/export_approved_job_urls.php > dev-tools/jobs/approved-job-urls.txt
 *
 * Env: DB_HOST, DB_PORT, DB_USER, DB_PASS, DB_NAME (optional; defaults match other dev-tools/jobs scripts).
 */

if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "CLI only.\n");
    exit(1);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$root = realpath(__DIR__ . '/../..') ?: (__DIR__ . '/../..');

function connectJobDbForExport(): mysqli
{
    $username = getenv('DB_USER') ?: 'metap8ok_myomr_admin';
    $password = getenv('DB_PASS') ?: 'myomr@123';
    $database = getenv('DB_NAME') ?: 'metap8ok_myomr';
    $hosts = ['localhost:3306', '127.0.0.1:3307', 'myomr.in:3306'];
    $dbHost = getenv('DB_HOST');
    if ($dbHost && $dbHost !== '') {
        array_unshift($hosts, $dbHost . ':' . (getenv('DB_PORT') ?: '3306'));
    }

    foreach ($hosts as $host) {
        try {
            $conn = new mysqli($host, $username, $password, $database);
            $conn->set_charset('utf8mb4');
            return $conn;
        } catch (mysqli_sql_exception $e) {
            // try next host
        }
    }

    throw new RuntimeException('Unable to connect to database. Set DB_HOST or run on server with MySQL.');
}

require_once $root . '/omr-local-job-listings/includes/job-functions-omr.php';

try {
    $conn = connectJobDbForExport();
} catch (Throwable $e) {
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}

$result = $conn->query("SELECT id, title FROM job_postings WHERE status = 'approved' ORDER BY id ASC");
if (!$result) {
    fwrite(STDERR, "Query failed.\n");
    exit(1);
}

while ($row = $result->fetch_assoc()) {
    $id = (int) $row['id'];
    $title = $row['title'] ?? null;
    echo getJobDetailUrl($id, $title) . "\n";
}

$conn->close();
exit(0);
