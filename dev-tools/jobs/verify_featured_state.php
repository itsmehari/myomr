<?php
if (php_sapi_name() !== 'cli') exit(1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli('myomr.in:3306', 'metap8ok_myomr_admin', 'myomr@123', 'metap8ok_myomr');
$conn->set_charset('utf8mb4');

$r1 = $conn->query("SELECT id, title, job_type, work_segment, featured, status FROM job_postings WHERE id IN (15,16,17) ORDER BY id");
echo "Featured target rows:\n";
while ($row = $r1->fetch_assoc()) {
    echo json_encode($row, JSON_UNESCAPED_UNICODE) . PHP_EOL;
}

$r2 = $conn->query("SELECT COUNT(*) AS c FROM job_postings WHERE featured = 1");
$c = $r2->fetch_assoc();
echo "Total featured count: " . (int)($c['c'] ?? 0) . PHP_EOL;

