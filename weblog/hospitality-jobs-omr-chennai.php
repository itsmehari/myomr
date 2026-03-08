<?php
/**
 * Hospitality Jobs in OMR Chennai - SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$category = 'hospitality';
$category_jobs = [];
$category_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $categoryQuery = "SELECT j.*, e.company_name 
                     FROM job_postings j
                     LEFT JOIN employers e ON j.employer_id = e.id
                     WHERE j.status = 'approved' 
                     AND (j.category = 'hospitality' OR j.category LIKE '%hospitality%' OR j.title LIKE '%hotel%' OR j.title LIKE '%restaurant%' OR j.title LIKE '%chef%')
                     ORDER BY j.featured DESC, j.created_at DESC
                     LIMIT 12";
    $categoryResult = $conn->query($categoryQuery);
    if ($categoryResult && $categoryResult->num_rows > 0) {
        $category_jobs = $categoryResult->fetch_all(MYSQLI_ASSOC);
    }
    
    $countQuery = "SELECT COUNT(*) as total FROM job_postings 
                   WHERE status = 'approved' 
                   AND (category = 'hospitality' OR category LIKE '%hospitality%' OR title LIKE '%hotel%' OR title LIKE '%restaurant%' OR title LIKE '%chef%')";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $category_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title = "Hospitality Jobs in OMR Chennai - Hotel, Restaurant Positions | MyOMR";
$page_description = "Find hospitality jobs in OMR Chennai. Hotel staff, restaurant jobs, chef, waiter positions. Free job listings. Apply directly to hotels and restaurants in OMR.";
$canonical_url = "https://myomr.in/hospitality-jobs-omr-chennai.php";
$page_keywords = "hospitality jobs OMR, hotel jobs OMR, restaurant jobs OMR, chef jobs OMR, hospitality jobs Chennai OMR";
$hero_title = "Hospitality Jobs in OMR Chennai";
$hero_subtitle = "Discover hospitality job opportunities in OMR Chennai. Hotel staff, restaurant jobs, chef, waiter, and hospitality positions. Work with hotels and restaurants in OMR.";
$breadcrumb_name = "Hospitality Jobs";
$content_section = '<h2 class="h3 mb-4">Hospitality Jobs in OMR Chennai</h2>
<p class="lead text-muted">OMR Chennai has numerous hotels, restaurants, and hospitality establishments. Find hospitality positions in hotels, restaurants, and food service.</p>
<h3 class="h5 mt-4 mb-3">Popular Hospitality Job Roles:</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> Chef / Cook</li>
    <li><i class="fas fa-check text-success me-2"></i> Waiter / Server</li>
    <li><i class="fas fa-check text-success me-2"></i> Hotel Receptionist</li>
    <li><i class="fas fa-check text-success me-2"></i> Housekeeping Staff</li>
    <li><i class="fas fa-check text-success me-2"></i> Restaurant Manager</li>
    <li><i class="fas fa-check text-success me-2"></i> Event Coordinator</li>
</ul>';

$location_jobs = $category_jobs;
$location_job_count = $category_job_count;
$filter_url = "/omr-local-job-listings/?category=hospitality";

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

