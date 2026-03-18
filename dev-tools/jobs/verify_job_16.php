<?php
if (php_sapi_name() !== 'cli') {
    exit(1);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli('myomr.in:3306', 'metap8ok_myomr_admin', 'myomr@123', 'metap8ok_myomr');
$conn->set_charset('utf8mb4');

$result = $conn->query("
    SELECT j.id, j.title, j.category, j.job_type, j.work_segment, j.location, j.salary_range, j.status, j.application_deadline,
           e.contact_person, e.phone
    FROM job_postings j
    LEFT JOIN employers e ON j.employer_id = e.id
    WHERE j.id = 16
    LIMIT 1
");

$row = $result->fetch_assoc();
echo json_encode($row, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;

