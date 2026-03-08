<?php
/**
 * IT Jobs in OMR Chennai - SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$category = 'it';
$category_jobs = [];
$category_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $categoryQuery = "SELECT j.*, e.company_name 
                     FROM job_postings j
                     LEFT JOIN employers e ON j.employer_id = e.id
                     WHERE j.status = 'approved' 
                     AND (j.category = 'it' OR j.category LIKE '%it%' OR j.title LIKE '%software%' OR j.title LIKE '%developer%' OR j.title LIKE '%engineer%')
                     ORDER BY j.featured DESC, j.created_at DESC
                     LIMIT 12";
    $categoryResult = $conn->query($categoryQuery);
    if ($categoryResult && $categoryResult->num_rows > 0) {
        $category_jobs = $categoryResult->fetch_all(MYSQLI_ASSOC);
    }
    
    $countQuery = "SELECT COUNT(*) as total FROM job_postings 
                   WHERE status = 'approved' 
                   AND (category = 'it' OR category LIKE '%it%' OR title LIKE '%software%' OR title LIKE '%developer%' OR title LIKE '%engineer%')";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $category_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title = "IT Jobs in OMR Chennai - Software Developer, Engineer Positions | MyOMR";
$page_description = "Find IT jobs in OMR Chennai. Software developer, engineer, data analyst, and tech positions. Free job listings. Apply directly to IT companies. OMR IT corridor jobs.";
$canonical_url = "https://myomr.in/it-jobs-omr-chennai.php";
$page_keywords = "IT jobs OMR, software developer jobs OMR, IT jobs Chennai OMR, engineer jobs OMR, tech jobs OMR, IT companies OMR";
$hero_title = "IT Jobs in OMR Chennai";
$hero_subtitle = "Discover IT job opportunities in OMR Chennai. Software developer, engineer, data analyst, and tech positions. Work with top IT companies in OMR IT corridor.";
$breadcrumb_name = "IT Jobs";
$content_section = '<h2 class="h3 mb-4">IT Jobs in OMR Chennai</h2>
<p class="lead text-muted">OMR Chennai is one of the largest IT corridors in India, home to hundreds of IT companies, software development firms, and technology startups. Find your next IT career opportunity right here in OMR.</p>
<h3 class="h5 mt-4 mb-3">Popular IT Job Roles:</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> Software Developer / Engineer</li>
    <li><i class="fas fa-check text-success me-2"></i> Data Analyst / Data Scientist</li>
    <li><i class="fas fa-check text-success me-2"></i> Full Stack Developer</li>
    <li><i class="fas fa-check text-success me-2"></i> UI/UX Designer</li>
    <li><i class="fas fa-check text-success me-2"></i> QA / Testing Engineer</li>
    <li><i class="fas fa-check text-success me-2"></i> DevOps Engineer</li>
    <li><i class="fas fa-check text-success me-2"></i> Business Analyst</li>
    <li><i class="fas fa-check text-success me-2"></i> Project Manager</li>
</ul>';

$location_jobs = $category_jobs;
$location_job_count = $category_job_count;
$filter_url = "/omr-local-job-listings/?category=it";

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

