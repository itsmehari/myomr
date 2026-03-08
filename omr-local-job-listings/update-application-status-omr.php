<?php
/**
 * Update Application Status
 * Simple handler to update application status
 */
require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/employer-auth.php';
require_once __DIR__ . '/includes/job-functions-omr.php';
requireEmployerAuth();

require_once __DIR__ . '/../core/omr-connect.php';
global $conn;

$application_id = isset($_POST['application_id']) ? intval($_POST['application_id']) : 0;
$status = isset($_POST['status']) ? sanitizeInput($_POST['status']) : '';
$job_id = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0;
$employerId = (int)($_SESSION['employer_id'] ?? 0);

if ($application_id <= 0 || empty($status) || $job_id <= 0) {
    header('Location: view-applications-omr.php?id=' . $job_id . '&error=invalid');
    exit;
}

// Verify the application belongs to a job owned by this employer
$verifyQuery = "SELECT a.id FROM job_applications a 
                INNER JOIN job_postings j ON a.job_id = j.id 
                WHERE a.id = {$application_id} AND j.id = {$job_id} AND j.employer_id = {$employerId}";
$verifyResult = $conn->query($verifyQuery);

if ($verifyResult && $verifyResult->num_rows > 0) {
    // Update status
    $updateQuery = "UPDATE job_applications SET status = '" . $conn->real_escape_string($status) . "' WHERE id = {$application_id}";
    if ($conn->query($updateQuery)) {
        header('Location: view-applications-omr.php?id=' . $job_id . '&success=updated');
    } else {
        header('Location: view-applications-omr.php?id=' . $job_id . '&error=update_failed');
    }
} else {
    header('Location: view-applications-omr.php?id=' . $job_id . '&error=unauthorized');
}
exit;

