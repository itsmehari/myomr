<?php
/**
 * Jobs in Thoraipakkam OMR - SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$location = 'Thoraipakkam';
$location_jobs = [];
$location_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $locationQuery = "SELECT j.*, e.company_name 
                     FROM job_postings j
                     LEFT JOIN employers e ON j.employer_id = e.id
                     WHERE j.status = 'approved' 
                     AND (j.location LIKE '%Thoraipakkam%' OR j.location LIKE '%thoraipakkam%')
                     ORDER BY j.featured DESC, j.created_at DESC
                     LIMIT 12";
    $locationResult = $conn->query($locationQuery);
    if ($locationResult && $locationResult->num_rows > 0) {
        $location_jobs = $locationResult->fetch_all(MYSQLI_ASSOC);
    }
    
    $countQuery = "SELECT COUNT(*) as total FROM job_postings 
                   WHERE status = 'approved' 
                   AND (location LIKE '%Thoraipakkam%' OR location LIKE '%thoraipakkam%')";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $location_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title = "Jobs in Thoraipakkam OMR - Find Local Opportunities | MyOMR";
$page_description = "Find jobs in Thoraipakkam OMR Chennai. IT companies, schools, healthcare & more. Free job listings. Apply directly to employers. Local jobs in Thoraipakkam area.";
$canonical_url = "https://myomr.in/jobs-in-thoraipakkam-omr.php";
$page_keywords = "jobs in Thoraipakkam OMR, Thoraipakkam jobs, jobs Thoraipakkam Chennai, IT jobs Thoraipakkam";
$location_name = "Thoraipakkam";
$location_description = "Thoraipakkam is a prominent area in OMR Chennai with a mix of IT companies, residential complexes, schools, and healthcare facilities. Find local job opportunities in Thoraipakkam.";
$hero_title = "Find Jobs in Thoraipakkam OMR";
$hero_subtitle = "Discover local job opportunities in Thoraipakkam OMR. IT companies, schools, healthcare, and service businesses. Work in your neighborhood.";
$breadcrumb_name = "Jobs in Thoraipakkam";

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

