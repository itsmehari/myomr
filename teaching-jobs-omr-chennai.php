<?php
/**
 * Teaching Jobs in OMR Chennai — SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$category          = 'teaching';
$category_jobs     = [];
$category_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $categoryQuery = "SELECT j.*, e.company_name
                       FROM job_postings j
                       LEFT JOIN employers e ON j.employer_id = e.id
                      WHERE j.status = 'approved'
                        AND (
                              j.category LIKE '%teach%'
                           OR j.category LIKE '%school%'
                           OR j.category LIKE '%educat%'
                           OR j.title    LIKE '%teacher%'
                           OR j.title    LIKE '%teaching%'
                           OR j.title    LIKE '%lecturer%'
                           OR j.title    LIKE '%faculty%'
                           OR j.title    LIKE '%tutor%'
                           OR j.title    LIKE '%principal%'
                           OR j.title    LIKE '%school%'
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
                            category LIKE '%teach%'
                         OR category LIKE '%school%'
                         OR category LIKE '%educat%'
                         OR title    LIKE '%teacher%'
                         OR title    LIKE '%teaching%'
                         OR title    LIKE '%lecturer%'
                         OR title    LIKE '%faculty%'
                         OR title    LIKE '%tutor%'
                         OR title    LIKE '%principal%'
                         OR title    LIKE '%school%'
                      )";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $category_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title      = 'Teaching Jobs in OMR Chennai — Schools, Colleges & Tutors | MyOMR';
$page_description = 'Find teaching jobs in OMR Chennai. Teacher, lecturer, faculty, and tutor positions at schools, colleges, and coaching centres along OMR. Free to browse and apply.';
$canonical_url   = 'https://myomr.in/teaching-jobs-omr-chennai.php';
$page_keywords   = 'teaching jobs OMR, teacher jobs Chennai OMR, school jobs OMR, lecturer jobs OMR, faculty jobs OMR, education jobs OMR Chennai';
$hero_title      = 'Teaching Jobs in OMR Chennai';
$hero_subtitle   = 'Discover teaching and education job opportunities in OMR Chennai. Teacher, lecturer, faculty, principal, and tutor positions at schools, colleges, and coaching centres.';
$breadcrumb_name = 'Teaching Jobs';
$content_section = '<h2 class="h3 mb-4">Teaching Jobs in OMR Chennai</h2>
<p class="lead text-muted">The OMR corridor is home to hundreds of schools, engineering colleges, arts & science colleges, and coaching centres. Find your next teaching opportunity right here in OMR.</p>
<h3 class="h5 mt-4 mb-3">Popular Teaching Job Roles:</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> Primary & Secondary School Teacher</li>
    <li><i class="fas fa-check text-success me-2"></i> Engineering College Lecturer / Professor</li>
    <li><i class="fas fa-check text-success me-2"></i> Arts & Science College Faculty</li>
    <li><i class="fas fa-check text-success me-2"></i> School Principal / Vice Principal</li>
    <li><i class="fas fa-check text-success me-2"></i> Home / Online Tutor</li>
    <li><i class="fas fa-check text-success me-2"></i> Coaching Centre Faculty</li>
    <li><i class="fas fa-check text-success me-2"></i> Physical Education Teacher</li>
    <li><i class="fas fa-check text-success me-2"></i> Special Needs Educator</li>
</ul>
<h3 class="h5 mt-4 mb-3">Schools & Colleges Hiring in OMR:</h3>
<p class="text-muted">OMR and the Old Mahabalipuram Road corridor has a growing number of CBSE, ICSE, State Board, and international schools, along with several autonomous engineering colleges — all regularly hiring qualified teaching staff.</p>';

$location_jobs      = $category_jobs;
$location_job_count = $category_job_count;
$filter_url         = '/omr-local-job-listings/?category=teaching';

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';
