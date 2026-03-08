<?php
/**
 * Jobs in Navalur OMR - SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$location = 'Navalur';
$location_jobs = [];
$location_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $locationQuery = "SELECT j.*, e.company_name 
                     FROM job_postings j
                     LEFT JOIN employers e ON j.employer_id = e.id
                     WHERE j.status = 'approved' 
                     AND (j.location LIKE '%Navalur%' OR j.location LIKE '%navalur%')
                     ORDER BY j.featured DESC, j.created_at DESC
                     LIMIT 12";
    $locationResult = $conn->query($locationQuery);
    if ($locationResult && $locationResult->num_rows > 0) {
        $location_jobs = $locationResult->fetch_all(MYSQLI_ASSOC);
    }
    
    $countQuery = "SELECT COUNT(*) as total FROM job_postings 
                   WHERE status = 'approved' 
                   AND (location LIKE '%Navalur%' OR location LIKE '%navalur%')";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $location_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title = "Jobs in Navalur OMR - Find Local Opportunities | MyOMR";
$page_description = "Find jobs in Navalur OMR Chennai. IT companies, retail, hospitality & more. Free job listings. Apply directly to employers. Local jobs in Navalur area.";
$canonical_url = "https://myomr.in/jobs-in-navalur-omr.php";
$page_keywords = "jobs in Navalur OMR, Navalur jobs, jobs Navalur Chennai, IT jobs Navalur, retail jobs Navalur";
$location_name = "Navalur";
$location_description = "Navalur is a developing residential and commercial area in OMR Chennai, with growing IT infrastructure, retail outlets, and service businesses. Find local job opportunities in Navalur.";
$hero_title = "Find Jobs in Navalur OMR";
$hero_subtitle = "Discover local job opportunities in Navalur OMR. IT companies, retail stores, hospitality, and service businesses. Work close to home.";
$breadcrumb_name = "Jobs in Navalur";

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

