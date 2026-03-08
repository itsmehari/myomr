<?php
/**
 * Teaching Jobs in OMR Chennai - SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$category = 'teaching';
$category_jobs = [];
$category_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $categoryQuery = "SELECT j.*, e.company_name 
                     FROM job_postings j
                     LEFT JOIN employers e ON j.employer_id = e.id
                     WHERE j.status = 'approved' 
                     AND (j.category = 'teaching' OR j.category LIKE '%teaching%' OR j.title LIKE '%teacher%' OR j.title LIKE '%faculty%')
                     ORDER BY j.featured DESC, j.created_at DESC
                     LIMIT 12";
    $categoryResult = $conn->query($categoryQuery);
    if ($categoryResult && $categoryResult->num_rows > 0) {
        $category_jobs = $categoryResult->fetch_all(MYSQLI_ASSOC);
    }
    
    $countQuery = "SELECT COUNT(*) as total FROM job_postings 
                   WHERE status = 'approved' 
                   AND (category = 'teaching' OR category LIKE '%teaching%' OR title LIKE '%teacher%' OR title LIKE '%faculty%')";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $category_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title = "Teaching Jobs in OMR Chennai - Teacher, Faculty Positions | MyOMR";
$page_description = "Find teaching jobs in OMR Chennai. Teacher, faculty, lecturer positions in schools and colleges. Free job listings. Apply directly to educational institutions in OMR.";
$canonical_url = "https://myomr.in/teaching-jobs-omr-chennai.php";
$page_keywords = "teaching jobs OMR, teacher jobs OMR, faculty jobs OMR, teaching jobs Chennai OMR, school jobs OMR";
$hero_title = "Teaching Jobs in OMR Chennai";
$hero_subtitle = "Discover teaching job opportunities in OMR Chennai. Teacher, faculty, lecturer positions in schools, colleges, and coaching centers. Shape young minds in OMR.";
$breadcrumb_name = "Teaching Jobs";
$content_section = '<h2 class="h3 mb-4">Teaching Jobs in OMR Chennai</h2>
<p class="lead text-muted">OMR Chennai has numerous schools, colleges, and educational institutions. Find teaching positions across all subjects and grade levels.</p>
<h3 class="h5 mt-4 mb-3">Popular Teaching Job Roles:</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> Primary School Teacher</li>
    <li><i class="fas fa-check text-success me-2"></i> High School Teacher</li>
    <li><i class="fas fa-check text-success me-2"></i> College Lecturer / Faculty</li>
    <li><i class="fas fa-check text-success me-2"></i> Subject Specialist Teacher</li>
    <li><i class="fas fa-check text-success me-2"></i> Pre-School Teacher</li>
    <li><i class="fas fa-check text-success me-2"></i> Special Education Teacher</li>
</ul>';

$location_jobs = $category_jobs;
$location_job_count = $category_job_count;
$filter_url = "/omr-local-job-listings/?category=teaching";

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

