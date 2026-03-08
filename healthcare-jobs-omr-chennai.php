<?php
/**
 * Healthcare Jobs in OMR Chennai — SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

$category          = 'healthcare';
$category_jobs     = [];
$category_job_count = 0;

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    $categoryQuery = "SELECT j.*, e.company_name
                       FROM job_postings j
                       LEFT JOIN employers e ON j.employer_id = e.id
                      WHERE j.status = 'approved'
                        AND (
                              j.category LIKE '%health%'
                           OR j.category LIKE '%medical%'
                           OR j.category LIKE '%hospital%'
                           OR j.category LIKE '%pharma%'
                           OR j.title    LIKE '%nurse%'
                           OR j.title    LIKE '%doctor%'
                           OR j.title    LIKE '%physician%'
                           OR j.title    LIKE '%pharmacist%'
                           OR j.title    LIKE '%hospital%'
                           OR j.title    LIKE '%clinic%'
                           OR j.title    LIKE '%medical%'
                           OR j.title    LIKE '%radiolog%'
                           OR j.title    LIKE '%therapist%'
                           OR j.title    LIKE '%dietitian%'
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
                            category LIKE '%health%'
                         OR category LIKE '%medical%'
                         OR category LIKE '%hospital%'
                         OR category LIKE '%pharma%'
                         OR title    LIKE '%nurse%'
                         OR title    LIKE '%doctor%'
                         OR title    LIKE '%physician%'
                         OR title    LIKE '%pharmacist%'
                         OR title    LIKE '%hospital%'
                         OR title    LIKE '%clinic%'
                         OR title    LIKE '%medical%'
                         OR title    LIKE '%radiolog%'
                         OR title    LIKE '%therapist%'
                         OR title    LIKE '%dietitian%'
                      )";
    $countResult = $conn->query($countQuery);
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $category_job_count = (int)($row['total'] ?? 0);
    }
}

$page_title       = 'Healthcare & Medical Jobs in OMR Chennai — Hospitals, Clinics & Pharma | MyOMR';
$page_description = 'Find healthcare and medical jobs in OMR Chennai. Nursing, doctor, pharmacist, and hospital staff positions at hospitals, clinics, and pharmaceutical companies along OMR. Free to apply.';
$canonical_url    = 'https://myomr.in/healthcare-jobs-omr-chennai.php';
$page_keywords    = 'healthcare jobs OMR, medical jobs Chennai OMR, nurse jobs OMR, doctor jobs OMR, hospital jobs OMR Chennai, pharma jobs OMR';
$hero_title       = 'Healthcare & Medical Jobs in OMR Chennai';
$hero_subtitle    = 'Discover healthcare, nursing, medical, and pharmaceutical job opportunities in OMR Chennai. Positions at hospitals, clinics, diagnostic centres, and pharma companies.';
$breadcrumb_name  = 'Healthcare Jobs';
$content_section  = '<h2 class="h3 mb-4">Healthcare Jobs in OMR Chennai</h2>
<p class="lead text-muted">The OMR corridor is home to multi-specialty hospitals, nursing homes, dental clinics, diagnostic centres, and pharmaceutical firms. Find your next healthcare career in OMR.</p>
<h3 class="h5 mt-4 mb-3">Popular Healthcare Job Roles:</h3>
<ul class="list-unstyled">
    <li><i class="fas fa-check text-success me-2"></i> Staff Nurse / Senior Nurse</li>
    <li><i class="fas fa-check text-success me-2"></i> MBBS Doctor / Resident Doctor</li>
    <li><i class="fas fa-check text-success me-2"></i> Pharmacist / Clinical Pharmacist</li>
    <li><i class="fas fa-check text-success me-2"></i> Radiologist / X-Ray Technician</li>
    <li><i class="fas fa-check text-success me-2"></i> Physiotherapist / Occupational Therapist</li>
    <li><i class="fas fa-check text-success me-2"></i> Lab Technician / Medical Laboratory Assistant</li>
    <li><i class="fas fa-check text-success me-2"></i> Hospital Administrator / Front Office</li>
    <li><i class="fas fa-check text-success me-2"></i> Dietitian / Nutritionist</li>
</ul>
<h3 class="h5 mt-4 mb-3">Why Healthcare Professionals Choose OMR:</h3>
<p class="text-muted">OMR Chennai has seen rapid growth in healthcare infrastructure — from MIOT International and Fortis Malar nearby to numerous specialty clinics across Sholinganallur, Navalur, and Kelambakkam — creating consistent demand for qualified healthcare professionals.</p>';

$location_jobs      = $category_jobs;
$location_job_count = $category_job_count;
$filter_url         = '/omr-local-job-listings/?category=healthcare';

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';
