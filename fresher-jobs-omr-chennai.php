<?php
/**
 * Fresher Jobs in OMR Chennai — SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$job_type          = 'fresher';
$category_jobs     = [];
$category_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $categoryQuery = "SELECT j.*, e.company_name
                       FROM job_postings j
                       LEFT JOIN employers e ON j.employer_id = e.id
                      WHERE j.status = 'approved'
                        AND (
                              j.experience_level = 'fresher'
                           OR j.experience_level LIKE '%fresher%'
                           OR j.experience_level LIKE '%entry%'
                           OR j.experience_level = '0'
                           OR j.title LIKE '%fresher%'
                           OR j.title LIKE '%entry level%'
                           OR j.title LIKE '%0 years%'
                           OR j.title LIKE '%0-1 year%'
                           OR j.title LIKE '%trainee%'
                           OR j.title LIKE '%graduate%'
                        )
                      ORDER BY j.featured DESC, j.created_at DESC
                      LIMIT 12";
    $categoryResult = $conn->query($categoryQuery);
    if ($categoryResult && $categoryResult->num_rows > 0) {
        $category_jobs = $categoryResult->fetch_all(MYSQLI_ASSOC);
    }

    $countQuery = "SELECT COUNT(*) AS total
                     FROM job_postings
                    WHERE status = 'approved'
                      AND (
                            experience_level = 'fresher'
                         OR experience_level LIKE '%fresher%'
                         OR experience_level LIKE '%entry%'
                         OR experience_level = '0'
                         OR title LIKE '%fresher%'
                         OR title LIKE '%entry level%'
                         OR title LIKE '%0 years%'
                         OR title LIKE '%0-1 year%'
                         OR title LIKE '%trainee%'
                         OR title LIKE '%graduate%'
                      )";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $category_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title       = 'Fresher Jobs in OMR Chennai — Entry Level & Graduate Positions | MyOMR';
$page_description = 'Find fresher and entry-level jobs in OMR Chennai. Graduate trainee, junior developer, fresher IT, admin, and sales positions across OMR. No experience required. Free to apply.';
$canonical_url    = 'https://myomr.in/fresher-jobs-omr-chennai.php';
$page_keywords    = 'fresher jobs OMR, entry level jobs Chennai OMR, graduate jobs OMR, trainee jobs OMR, first job OMR, 0 experience jobs OMR Chennai';
$hero_title       = 'Fresher Jobs in OMR Chennai';
$hero_subtitle    = 'Starting your career in OMR? Browse entry-level and fresher job openings at IT companies, schools, hospitals, businesses, and startups across the OMR corridor. No experience required.';
$breadcrumb_name  = 'Fresher Jobs';
$content_section  = '<h2 class="h3 mb-4">Fresher Jobs in OMR Chennai</h2>
<p class="lead text-muted">OMR Chennai is one of India\'s fastest-growing employment hubs. With hundreds of IT companies, schools, hospitals, retail outlets, and businesses, there are always opportunities for fresh graduates and entry-level candidates.</p>
<h3 class="h5 mt-4 mb-3">Popular Fresher Job Roles in OMR:</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> Junior Software Developer / Trainee Engineer</li>
    <li><i class="fas fa-check text-success me-2"></i> Graduate Trainee / Management Trainee</li>
    <li><i class="fas fa-check text-success me-2"></i> Customer Support / BPO Executive</li>
    <li><i class="fas fa-check text-success me-2"></i> Sales & Marketing Executive</li>
    <li><i class="fas fa-check text-success me-2"></i> Data Entry Operator / Back Office</li>
    <li><i class="fas fa-check text-success me-2"></i> Accounts Assistant / Finance Trainee</li>
    <li><i class="fas fa-check text-success me-2"></i> Content Writer / Digital Marketing Trainee</li>
    <li><i class="fas fa-check text-success me-2"></i> HR / Admin Assistant</li>
</ul>
<h3 class="h5 mt-4 mb-3">Tips for Freshers Applying in OMR:</h3>
<p class="text-muted">Apply to jobs where the required experience is 0–1 years. Highlight your academic projects, internships, and certifications. Many IT companies in OMR actively hire fresh engineers from local engineering colleges in Siruseri, Kelambakkam, and Navalur.</p>';

$location_jobs      = $category_jobs;
$location_job_count = $category_job_count;
$filter_url         = '/omr-local-job-listings/?experience_level=fresher';

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';
