<?php
/**
 * Jobs in Sholinganallur OMR - SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$location = 'Sholinganallur';
$location_jobs = [];
$location_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $locationQuery = "SELECT j.*, e.company_name 
                     FROM job_postings j
                     LEFT JOIN employers e ON j.employer_id = e.id
                     WHERE j.status = 'approved' 
                     AND (j.location LIKE '%Sholinganallur%' OR j.location LIKE '%sholinganallur%')
                     ORDER BY j.featured DESC, j.created_at DESC
                     LIMIT 12";
    $locationResult = $conn->query($locationQuery);
    if ($locationResult && $locationResult->num_rows > 0) {
        $location_jobs = $locationResult->fetch_all(MYSQLI_ASSOC);
    }
    
    $countQuery = "SELECT COUNT(*) as total FROM job_postings 
                   WHERE status = 'approved' 
                   AND (location LIKE '%Sholinganallur%' OR location LIKE '%sholinganallur%')";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $location_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title = "Jobs in Sholinganallur OMR - Find Local Opportunities | MyOMR";
$page_description = "Find jobs in Sholinganallur OMR Chennai. IT companies, schools, healthcare & more. Free job listings. Apply directly to employers. Local jobs in Sholinganallur area.";
$canonical_url = "https://myomr.in/jobs-in-sholinganallur-omr.php";
$page_keywords = "jobs in Sholinganallur OMR, Sholinganallur jobs, jobs Sholinganallur Chennai, IT jobs Sholinganallur, teaching jobs Sholinganallur";
$location_name = "Sholinganallur";
$location_description = "Sholinganallur is a major IT hub in OMR Chennai, home to numerous IT parks, software companies, schools, and healthcare facilities. Find local job opportunities in Sholinganallur without commuting to the city.";
$hero_title = "Find Jobs in Sholinganallur OMR";
$hero_subtitle = "Discover local job opportunities in Sholinganallur OMR. IT companies, schools, healthcare facilities, and more. Connect with employers in your area.";
$breadcrumb_name = "Jobs in Sholinganallur";

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

