<?php
/**
 * Retail Jobs in OMR Chennai - SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$category = 'retail';
$category_jobs = [];
$category_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $categoryQuery = "SELECT j.*, e.company_name 
                     FROM job_postings j
                     LEFT JOIN employers e ON j.employer_id = e.id
                     WHERE j.status = 'approved' 
                     AND (j.category = 'retail' OR j.category LIKE '%retail%' OR j.title LIKE '%retail%' OR j.title LIKE '%sales%')
                     ORDER BY j.featured DESC, j.created_at DESC
                     LIMIT 12";
    $categoryResult = $conn->query($categoryQuery);
    if ($categoryResult && $categoryResult->num_rows > 0) {
        $category_jobs = $categoryResult->fetch_all(MYSQLI_ASSOC);
    }
    
    $countQuery = "SELECT COUNT(*) as total FROM job_postings 
                   WHERE status = 'approved' 
                   AND (category = 'retail' OR category LIKE '%retail%' OR title LIKE '%retail%' OR title LIKE '%sales%')";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $category_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title = "Retail Jobs in OMR Chennai - Sales, Store Associate Positions | MyOMR";
$page_description = "Find retail jobs in OMR Chennai. Sales associate, store manager, cashier, and retail positions. Free job listings. Apply directly to retail stores in OMR.";
$canonical_url = "https://myomr.in/retail-jobs-omr-chennai.php";
$page_keywords = "retail jobs OMR, sales jobs OMR, store jobs OMR, retail jobs Chennai OMR, shop jobs OMR";
$hero_title = "Retail Jobs in OMR Chennai";
$hero_subtitle = "Discover retail job opportunities in OMR Chennai. Sales associate, store manager, cashier, and retail positions. Work with retail stores and shopping malls in OMR.";
$breadcrumb_name = "Retail Jobs";
$content_section = '<h2 class="h3 mb-4">Retail Jobs in OMR Chennai</h2>
<p class="lead text-muted">OMR Chennai has numerous shopping malls, retail stores, and shopping centers. Find retail job opportunities in sales, customer service, and store management.</p>
<h3 class="h5 mt-4 mb-3">Popular Retail Job Roles:</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> Sales Associate</li>
    <li><i class="fas fa-check text-success me-2"></i> Store Manager</li>
    <li><i class="fas fa-check text-success me-2"></i> Cashier</li>
    <li><i class="fas fa-check text-success me-2"></i> Customer Service Representative</li>
    <li><i class="fas fa-check text-success me-2"></i> Visual Merchandiser</li>
    <li><i class="fas fa-check text-success me-2"></i> Inventory Manager</li>
</ul>';

$location_jobs = $category_jobs;
$location_job_count = $category_job_count;
$filter_url = "/omr-local-job-listings/?category=retail";

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

