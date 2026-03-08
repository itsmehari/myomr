<?php
/**
 * Part-Time Jobs in OMR Chennai - SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$job_type = 'part-time';
$parttime_jobs = [];
$parttime_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $ptQuery = "SELECT j.*, e.company_name 
                FROM job_postings j
                LEFT JOIN employers e ON j.employer_id = e.id
                WHERE j.status = 'approved' 
                AND (j.job_type = 'part-time' OR j.job_type = 'Part-time' OR j.job_type = 'Part Time' OR j.title LIKE '%part time%' OR j.title LIKE '%part-time%')
                ORDER BY j.featured DESC, j.created_at DESC
                LIMIT 12";
    $ptResult = $conn->query($ptQuery);
    if ($ptResult && $ptResult->num_rows > 0) {
        $parttime_jobs = $ptResult->fetch_all(MYSQLI_ASSOC);
    }
    
    $countQuery = "SELECT COUNT(*) as total FROM job_postings 
                   WHERE status = 'approved' 
                   AND (job_type = 'part-time' OR job_type = 'Part-time' OR job_type = 'Part Time' OR title LIKE '%part time%' OR title LIKE '%part-time%')";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $parttime_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title = "Part-Time Jobs in OMR Chennai - Flexible Hours | MyOMR";
$page_description = "Find part-time jobs in OMR Chennai. Flexible hours for students and part-time workers. Free job listings. Apply directly to employers. Perfect for work-life balance.";
$canonical_url = "https://myomr.in/part-time-jobs-omr-chennai.php";
$page_keywords = "part time jobs OMR, part-time jobs OMR, flexible jobs OMR, part time jobs Chennai OMR, student jobs OMR";
$hero_title = "Part-Time Jobs in OMR Chennai";
$hero_subtitle = "Discover part-time job opportunities in OMR Chennai. Flexible hours perfect for students, homemakers, and those seeking work-life balance. Work part-time in OMR.";
$breadcrumb_name = "Part-Time Jobs";
$content_section = '<h2 class="h3 mb-4">Part-Time Jobs in OMR Chennai</h2>
<p class="lead text-muted">Looking for flexible work hours? OMR Chennai offers numerous part-time opportunities perfect for students, homemakers, and those seeking work-life balance.</p>
<h3 class="h5 mt-4 mb-3">Why Choose Part-Time Jobs in OMR?</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> Flexible hours - work around your schedule</li>
    <li><i class="fas fa-check text-success me-2"></i> Perfect for students and homemakers</li>
    <li><i class="fas fa-check text-success me-2"></i> Work-life balance</li>
    <li><i class="fas fa-check text-success me-2"></i> Earn while studying or managing home</li>
    <li><i class="fas fa-check text-success me-2"></i> Gain work experience</li>
</ul>
<h3 class="h5 mt-4 mb-3">Popular Part-Time Job Roles:</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> Part-Time Sales Associate</li>
    <li><i class="fas fa-check text-success me-2"></i> Part-Time Customer Service</li>
    <li><i class="fas fa-check text-success me-2"></i> Part-Time Tutor / Teacher</li>
    <li><i class="fas fa-check text-success me-2"></i> Part-Time Data Entry</li>
    <li><i class="fas fa-check text-success me-2"></i> Part-Time Restaurant Staff</li>
</ul>';

$location_jobs = $parttime_jobs;
$location_job_count = $parttime_job_count;
$filter_url = "/omr-local-job-listings/?job_type=part-time";

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

