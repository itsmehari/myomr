<?php
/**
 * Experienced Jobs in OMR Chennai - SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$experienced_jobs = [];
$experienced_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $expQuery = "SELECT j.*, e.company_name 
                 FROM job_postings j
                 LEFT JOIN employers e ON j.employer_id = e.id
                 WHERE j.status = 'approved' 
                 AND (j.title LIKE '%senior%' OR j.title LIKE '%experienced%' OR j.title LIKE '%lead%' OR j.title LIKE '%manager%' OR j.description LIKE '%years experience%' OR j.description LIKE '%2+ years%' OR j.description LIKE '%3+ years%')
                 ORDER BY j.featured DESC, j.created_at DESC
                 LIMIT 12";
    $expResult = $conn->query($expQuery);
    if ($expResult && $expResult->num_rows > 0) {
        $experienced_jobs = $expResult->fetch_all(MYSQLI_ASSOC);
    }
    
    $countQuery = "SELECT COUNT(*) as total FROM job_postings 
                   WHERE status = 'approved' 
                   AND (title LIKE '%senior%' OR title LIKE '%experienced%' OR title LIKE '%lead%' OR title LIKE '%manager%' OR description LIKE '%years experience%' OR description LIKE '%2+ years%' OR description LIKE '%3+ years%')";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $experienced_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title = "Experienced Jobs in OMR Chennai - Senior Positions | MyOMR";
$page_description = "Find experienced jobs in OMR Chennai. Senior positions for experienced professionals. Manager, lead, and senior roles. Free job listings. Apply directly to employers.";
$canonical_url = "https://myomr.in/experienced-jobs-omr-chennai.php";
$page_keywords = "experienced jobs OMR, senior jobs OMR, manager jobs OMR, experienced jobs Chennai OMR, professional jobs OMR";
$hero_title = "Experienced Jobs in OMR Chennai";
$hero_subtitle = "Discover senior job opportunities in OMR Chennai. Manager, lead, and senior positions for experienced professionals. Advance your career in OMR.";
$breadcrumb_name = "Experienced Jobs";
$content_section = '<h2 class="h3 mb-4">Experienced Jobs in OMR Chennai</h2>
<p class="lead text-muted">Looking for senior positions? OMR Chennai offers numerous opportunities for experienced professionals across IT, management, and various industries.</p>
<h3 class="h5 mt-4 mb-3">Why Choose Experienced Jobs in OMR?</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> Senior positions with competitive salaries</li>
    <li><i class="fas fa-check text-success me-2"></i> Leadership and management opportunities</li>
    <li><i class="fas fa-check text-success me-2"></i> Work with top companies in OMR</li>
    <li><i class="fas fa-check text-success me-2"></i> Career growth and advancement</li>
    <li><i class="fas fa-check text-success me-2"></i> Work-life balance - no long commutes</li>
</ul>
<h3 class="h5 mt-4 mb-3">Popular Experienced Job Roles:</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> Senior Software Developer</li>
    <li><i class="fas fa-check text-success me-2"></i> Project Manager</li>
    <li><i class="fas fa-check text-success me-2"></i> Team Lead</li>
    <li><i class="fas fa-check text-success me-2"></i> Department Manager</li>
    <li><i class="fas fa-check text-success me-2"></i> Business Analyst</li>
</ul>';

$location_jobs = $experienced_jobs;
$location_job_count = $experienced_job_count;
$filter_url = "/omr-local-job-listings/?search=senior";

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

