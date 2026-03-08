<?php
/**
 * MyOMR Job Portal - Location-Specific Landing Page
 * Jobs in Perungudi OMR - SEO Landing Page
 * 
 * @package MyOMR Job Portal
 * @version 1.0.0
 * Phase 2: SEO-Optimized Landing Pages
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

// Get jobs filtered by location
$location = 'Perungudi';
$location_jobs = [];
$location_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $locationQuery = "SELECT j.*, e.company_name 
                     FROM job_postings j
                     LEFT JOIN employers e ON j.employer_id = e.id
                     WHERE j.status = 'approved' 
                     AND (j.location LIKE '%Perungudi%' OR j.location LIKE '%perungudi%')
                     ORDER BY j.featured DESC, j.created_at DESC
                     LIMIT 12";
    $locationResult = $conn->query($locationQuery);
    if ($locationResult && $locationResult->num_rows > 0) {
        $location_jobs = $locationResult->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get count
    $countQuery = "SELECT COUNT(*) as total FROM job_postings 
                   WHERE status = 'approved' 
                   AND (location LIKE '%Perungudi%' OR location LIKE '%perungudi%')";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $location_job_count = (int)($row['total'] ?? 0);
    }
}

// SEO Meta
$page_title = "Jobs in Perungudi OMR - Find Local Opportunities | MyOMR";
$page_description = "Find jobs in Perungudi OMR Chennai. IT, Teaching, Healthcare & more. Free job listings. Apply directly to employers. Local jobs in Perungudi area.";
$canonical_url = "https://myomr.in/jobs-in-perungudi-omr.php";
$location_name = "Perungudi";
$location_description = "Perungudi is a rapidly developing area in OMR Chennai, home to many IT companies, schools, and businesses. Find local job opportunities in Perungudi without the long commute.";
$hero_title = "Find Jobs in Perungudi OMR";
$hero_subtitle = "Discover local job opportunities in Perungudi OMR. IT companies, schools, healthcare, and service businesses. Work close to home.";
$breadcrumb_name = "Jobs in Perungudi";
$page_keywords = "jobs in Perungudi OMR, Perungudi jobs, jobs Perungudi Chennai, IT jobs Perungudi";

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

