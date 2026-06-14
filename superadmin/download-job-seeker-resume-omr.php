<?php
/**
 * Secure download of job seeker résumé (admin only). GET ?id= profile id
 */
require_once __DIR__ . '/_bootstrap.php';

require_once dirname(__DIR__) . '/core/omr-connect.php';

$pid = (int)($_GET['id'] ?? 0);
if ($pid <= 0) {
    http_response_code(400);
    exit('Invalid profile ID');
}

$stmt = $conn->prepare('SELECT full_name, resume_path FROM job_seeker_profiles WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $pid);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row || empty($row['resume_path'])) {
    http_response_code(404);
    exit('Profile or résumé not found');
}

$rel = $row['resume_path'];
if (strpos($rel, 'uploads/resumes/') !== 0) {
    http_response_code(400);
    exit('Invalid file path');
}

$path = dirname(__DIR__) . '/omr-local-job-listings/' . $rel;
if (!is_file($path) || !is_readable($path)) {
    http_response_code(404);
    exit('File not found');
}

$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$mimes = [
    'pdf' => 'application/pdf',
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
];
$mime = $mimes[$ext] ?? 'application/octet-stream';
$filename = preg_replace('/[^a-zA-Z0-9\-_.]/', '_', $row['full_name'] ?? 'candidate') . '-resume.' . $ext;

header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($path));
header('Cache-Control: private, no-cache');
readfile($path);
exit;
