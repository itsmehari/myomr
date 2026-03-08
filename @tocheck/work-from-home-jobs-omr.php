<?php
/**
 * Work from Home Jobs in OMR - SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$wfh_jobs = [];
$wfh_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $wfhQuery = "SELECT j.*, e.company_name 
                 FROM job_postings j
                 LEFT JOIN employers e ON j.employer_id = e.id
                 WHERE j.status = 'approved' 
                 AND (j.title LIKE '%work from home%' OR j.title LIKE '%remote%' OR j.title LIKE '%WFH%' OR j.description LIKE '%work from home%' OR j.description LIKE '%remote work%' OR j.location LIKE '%remote%')
                 ORDER BY j.featured DESC, j.created_at DESC
                 LIMIT 12";
    $wfhResult = $conn->query($wfhQuery);
    if ($wfhResult && $wfhResult->num_rows > 0) {
        $wfh_jobs = $wfhResult->fetch_all(MYSQLI_ASSOC);
    }
    
    $countQuery = "SELECT COUNT(*) as total FROM job_postings 
                   WHERE status = 'approved' 
                   AND (title LIKE '%work from home%' OR title LIKE '%remote%' OR title LIKE '%WFH%' OR description LIKE '%work from home%' OR description LIKE '%remote work%' OR location LIKE '%remote%')";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $wfh_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title = "Work from Home Jobs in OMR - Remote Positions | MyOMR";
$page_description = "Find work from home jobs in OMR Chennai. Remote positions for IT, customer service, data entry, and more. Free job listings. Work from home in OMR.";
$canonical_url = "https://myomr.in/work-from-home-jobs-omr.php";
$page_keywords = "work from home jobs OMR, remote jobs OMR, WFH jobs OMR, work from home Chennai OMR, remote work OMR";
$hero_title = "Work from Home Jobs in OMR";
$hero_subtitle = "Discover remote job opportunities in OMR Chennai. Work from home positions in IT, customer service, data entry, and more. Flexible remote work in OMR.";
$breadcrumb_name = "Work from Home Jobs";
$content_section = '<h2 class="h3 mb-4">Work from Home Jobs in OMR Chennai</h2>
<p class="lead text-muted">Looking for remote work opportunities? OMR Chennai offers numerous work from home positions across various industries. Work from the comfort of your home.</p>
<h3 class="h5 mt-4 mb-3">Why Choose Work from Home Jobs in OMR?</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> Work from the comfort of your home</li>
    <li><i class="fas fa-check text-success me-2"></i> No commute - save time and money</li>
    <li><i class="fas fa-check text-success me-2"></i> Flexible schedule</li>
    <li><i class="fas fa-check text-success me-2"></i> Better work-life balance</li>
    <li><i class="fas fa-check text-success me-2"></i> Reduced stress and expenses</li>
</ul>
<h3 class="h5 mt-4 mb-3">Popular Work from Home Job Roles:</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> Remote Software Developer</li>
    <li><i class="fas fa-check text-success me-2"></i> Work from Home Customer Service</li>
    <li><i class="fas fa-check text-success me-2"></i> Remote Data Entry</li>
    <li><i class="fas fa-check text-success me-2"></i> Online Tutor / Teacher</li>
    <li><i class="fas fa-check text-success me-2"></i> Remote Content Writer</li>
</ul>';

$location_jobs = $wfh_jobs;
$location_job_count = $wfh_job_count;
$filter_url = "/omr-local-job-listings/?search=work from home";

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

