<?php
/**
 * Secure resume download — employer must own the job
 * GET ?id=APPLICATION_ID
 */
require_once __DIR__ . '/includes/error-reporting.php';

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/includes/employer-auth.php';
require_once __DIR__ . '/../core/omr-connect.php';
global $conn;

requireEmployerAuth();

$app_id = (int)($_GET['id'] ?? 0);
if ($app_id <= 0) {
    http_response_code(400);
    exit('Invalid application ID');
}

$stmt = $conn->prepare(
    "SELECT a.applicant_resume, a.applicant_name, j.employer_id
     FROM job_applications a
     INNER JOIN job_postings j ON a.job_id = j.id
     WHERE a.id = ? AND j.employer_id = ? LIMIT 1"
);
$eid = (int)$_SESSION['employer_id'];
$stmt->bind_param('ii', $app_id, $eid);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row || empty($row['applicant_resume'])) {
    http_response_code(404);
    exit('Resume not found or not available');
}

$path = __DIR__ . '/' . $row['applicant_resume'];
if (!is_file($path) || !is_readable($path)) {
    http_response_code(404);
    exit('Resume file not found');
}

$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$mimes = ['pdf' => 'application/pdf', 'doc' => 'application/msword', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
$mime = $mimes[$ext] ?? 'application/octet-stream';
$filename = preg_replace('/[^a-zA-Z0-9\-_.]/', '_', $row['applicant_name']) . '-resume.' . $ext;

header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($path));
header('Cache-Control: private, no-cache');
readfile($path);
exit;
