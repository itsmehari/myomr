<?php
/**
 * Fresher Jobs in OMR Chennai - SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$job_type = 'entry-level';
$fresher_jobs = [];
$fresher_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $fresherQuery = "SELECT j.*, e.company_name 
                     FROM job_postings j
                     LEFT JOIN employers e ON j.employer_id = e.id
                     WHERE j.status = 'approved' 
                     AND (j.title LIKE '%fresher%' OR j.title LIKE '%entry%' OR j.title LIKE '%junior%' OR j.description LIKE '%fresher%' OR j.description LIKE '%0 years%' OR j.description LIKE '%no experience%')
                     ORDER BY j.featured DESC, j.created_at DESC
                     LIMIT 12";
    $fresherResult = $conn->query($fresherQuery);
    if ($fresherResult && $fresherResult->num_rows > 0) {
        $fresher_jobs = $fresherResult->fetch_all(MYSQLI_ASSOC);
    }
    
    $countQuery = "SELECT COUNT(*) as total FROM job_postings 
                   WHERE status = 'approved' 
                   AND (title LIKE '%fresher%' OR title LIKE '%entry%' OR title LIKE '%junior%' OR description LIKE '%fresher%' OR description LIKE '%0 years%' OR description LIKE '%no experience%')";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $fresher_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title = "Fresher Jobs in OMR Chennai - Entry Level Positions | MyOMR";
$page_description = "Find fresher jobs in OMR Chennai. Entry-level positions for recent graduates. No experience required. Free job listings. Apply directly to employers in OMR.";
$canonical_url = "https://myomr.in/fresher-jobs-omr-chennai.php";
$page_keywords = "fresher jobs OMR, entry level jobs OMR, fresher jobs Chennai OMR, jobs for freshers OMR, no experience jobs OMR";
$hero_title = "Fresher Jobs in OMR Chennai";
$hero_subtitle = "Discover entry-level job opportunities in OMR Chennai. Perfect for recent graduates and freshers. No experience required. Start your career in OMR.";
$breadcrumb_name = "Fresher Jobs";
$content_section = '<h2 class="h3 mb-4">Fresher Jobs in OMR Chennai</h2>
<p class="lead text-muted">Starting your career? OMR Chennai offers numerous opportunities for freshers and recent graduates. Find entry-level positions across IT, retail, hospitality, and more.</p>
<h3 class="h5 mt-4 mb-3">Why Choose Fresher Jobs in OMR?</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> No experience required - perfect for recent graduates</li>
    <li><i class="fas fa-check text-success me-2"></i> Learn on the job with training programs</li>
    <li><i class="fas fa-check text-success me-2"></i> Career growth opportunities</li>
    <li><i class="fas fa-check text-success me-2"></i> Work close to home - no long commutes</li>
    <li><i class="fas fa-check text-success me-2"></i> Competitive starting salaries</li>
</ul>
<h3 class="h5 mt-4 mb-3">Popular Fresher Job Roles:</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> Junior Software Developer</li>
    <li><i class="fas fa-check text-success me-2"></i> Trainee / Intern</li>
    <li><i class="fas fa-check text-success me-2"></i> Customer Service Representative</li>
    <li><i class="fas fa-check text-success me-2"></i> Sales Executive</li>
    <li><i class="fas fa-check text-success me-2"></i> Data Entry Operator</li>
</ul>';

$location_jobs = $fresher_jobs;
$location_job_count = $fresher_job_count;
$filter_url = "/omr-local-job-listings/?search=fresher";

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

