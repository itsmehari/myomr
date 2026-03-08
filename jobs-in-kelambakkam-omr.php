<?php
/**
 * Jobs in Kelambakkam OMR - SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$location = 'Kelambakkam';
$location_jobs = [];
$location_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $locationQuery = "SELECT j.*, e.company_name 
                     FROM job_postings j
                     LEFT JOIN employers e ON j.employer_id = e.id
                     WHERE j.status = 'approved' 
                     AND (j.location LIKE '%Kelambakkam%' OR j.location LIKE '%kelambakkam%')
                     ORDER BY j.featured DESC, j.created_at DESC
                     LIMIT 12";
    $locationResult = $conn->query($locationQuery);
    if ($locationResult && $locationResult->num_rows > 0) {
        $location_jobs = $locationResult->fetch_all(MYSQLI_ASSOC);
    }
    
    $countQuery = "SELECT COUNT(*) as total FROM job_postings 
                   WHERE status = 'approved' 
                   AND (location LIKE '%Kelambakkam%' OR location LIKE '%kelambakkam%')";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $location_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title = "Jobs in Kelambakkam OMR - Find Local Opportunities | MyOMR";
$page_description = "Find jobs in Kelambakkam OMR Chennai. Schools, healthcare, retail & more. Free job listings. Apply directly to employers. Local jobs in Kelambakkam area.";
$canonical_url = "https://myomr.in/jobs-in-kelambakkam-omr.php";
$page_keywords = "jobs in Kelambakkam OMR, Kelambakkam jobs, jobs Kelambakkam Chennai, teaching jobs Kelambakkam";
$location_name = "Kelambakkam";
$location_description = "Kelambakkam is a developing area in OMR Chennai with schools, healthcare facilities, and local businesses. Find local job opportunities in Kelambakkam.";
$hero_title = "Find Jobs in Kelambakkam OMR";
$hero_subtitle = "Discover local job opportunities in Kelambakkam OMR. Schools, healthcare, retail, and service businesses. Work close to home.";
$breadcrumb_name = "Jobs in Kelambakkam";

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

