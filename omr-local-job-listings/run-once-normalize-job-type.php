<?php
/**
 * One-time migration: normalize job_postings.job_type to canonical values.
 * Canonical: full-time, part-time, contract, internship, walk-in (lowercase, hyphen).
 *
 * Run once via browser or CLI, then delete or rename this file.
 * Browser: https://myomr.in/omr-local-job-listings/run-once-normalize-job-type.php?key=myomr_job_diag_2026
 */
$key = 'myomr_job_diag_2026';
if (php_sapi_name() !== 'cli' && (!isset($_GET['key']) || $_GET['key'] !== $key)) {
    header('HTTP/1.0 403 Forbidden');
    exit('Forbidden. Add ?key=' . $key . ' to run.');
}

require_once __DIR__ . '/../core/omr-connect.php';
global $conn;

if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
    exit('Database connection failed.');
}

// Map current DB values to canonical (case-sensitive match for existing data)
$sql = "UPDATE job_postings SET job_type = CASE
    WHEN job_type IN ('Full-time', 'Full-Time', 'full time') THEN 'full-time'
    WHEN job_type IN ('Part-time', 'Part-Time', 'part time') THEN 'part-time'
    WHEN job_type IN ('Contract') THEN 'contract'
    WHEN job_type IN ('Internship') THEN 'internship'
    WHEN job_type IN ('Walk-in', 'Walk-In', 'walk-in', 'walk in') THEN 'walk-in'
    ELSE LOWER(TRIM(REPLACE(job_type, ' ', '-')))
END
WHERE job_type IS NOT NULL AND job_type != ''";

$affected = 0;
if ($conn->query($sql)) {
    $affected = $conn->affected_rows;
}

$summary = [
    'status'   => 'ok',
    'message'  => 'Normalized job_type to canonical values (full-time, part-time, contract, internship, walk-in).',
    'affected' => $affected,
];

if (php_sapi_name() === 'cli') {
    echo json_encode($summary, JSON_PRETTY_PRINT) . "\n";
} else {
    header('Content-Type: application/json');
    echo json_encode($summary, JSON_PRETTY_PRINT);
}
