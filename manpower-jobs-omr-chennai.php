<?php
/**
 * Manpower Jobs in OMR Chennai - SEO Landing Page
 */

require_once __DIR__ . '/omr-local-job-listings/includes/error-reporting.php';
require_once __DIR__ . '/omr-local-job-listings/includes/job-functions-omr.php';
require_once __DIR__ . '/core/omr-connect.php';
global $conn;

if (!function_exists('jobPostingsHasColumn')) {
    function jobPostingsHasColumn(string $column): bool
    {
        global $conn;
        if (!($conn instanceof mysqli) || $conn->connect_error) {
            return false;
        }
        $column = trim($column);
        if ($column === '') {
            return false;
        }
        $stmt = $conn->prepare("
            SELECT 1
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'job_postings'
              AND COLUMN_NAME = ?
            LIMIT 1
        ");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('s', $column);
        $stmt->execute();
        return (bool) $stmt->get_result()->fetch_assoc();
    }
}

if (!function_exists('inferJobSegmentFromData')) {
    function inferJobSegmentFromData(array $job): string
    {
        $haystack = strtolower(
            trim(
                ($job['category_name'] ?? '') . ' ' .
                ($job['category'] ?? '') . ' ' .
                ($job['title'] ?? '') . ' ' .
                ($job['description'] ?? '')
            )
        );
        $manpowerKeywords = [
            'driver', 'delivery', 'helper', 'loader', 'warehouse', 'tea master', 'cook',
            'cleaner', 'housekeeping', 'electrician', 'plumber', 'welder', 'security',
            'technician', 'mechanic', 'labour', 'labor', 'waiter', 'kitchen', 'billing'
        ];
        foreach ($manpowerKeywords as $kw) {
            if ($kw !== '' && strpos($haystack, $kw) !== false) {
                return 'manpower';
            }
        }
        return 'office';
    }
}

$location_jobs = [];
$location_job_count = 0;
$work_segment = 'manpower';

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    if (jobPostingsHasColumn('work_segment')) {
        $query = "SELECT j.*, e.company_name
                  FROM job_postings j
                  LEFT JOIN employers e ON j.employer_id = e.id
                  WHERE j.status = 'approved' AND j.work_segment = 'manpower'
                  ORDER BY j.featured DESC, j.created_at DESC
                  LIMIT 12";
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            $location_jobs = $result->fetch_all(MYSQLI_ASSOC);
        }

        $countResult = $conn->query("SELECT COUNT(*) AS total FROM job_postings WHERE status = 'approved' AND work_segment = 'manpower'");
        if ($countResult) {
            $location_job_count = (int) (($countResult->fetch_assoc()['total'] ?? 0));
        }
    } else {
        // Fallback before migration: infer manpower from categories/titles.
        $query = "SELECT j.*, e.company_name
                  FROM job_postings j
                  LEFT JOIN employers e ON j.employer_id = e.id
                  WHERE j.status = 'approved'
                  ORDER BY j.featured DESC, j.created_at DESC
                  LIMIT 200";
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            $all = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($all as $row) {
                if (inferJobSegmentFromData($row) === 'manpower') {
                    $location_jobs[] = $row;
                }
            }
            $location_jobs = array_slice($location_jobs, 0, 12);
            $location_job_count = count($location_jobs);
        }
    }
}

$page_title = "Manpower Jobs in OMR Chennai - Field, Hospitality & Labour Roles | MyOMR";
$page_description = "Find manpower jobs in OMR Chennai including tea master, cook, helper, delivery, hospitality, labour and field operations roles. Apply directly with local employers.";
$canonical_url = "https://myomr.in/manpower-jobs-omr-chennai.php";
$page_keywords = "manpower jobs OMR, blue collar jobs OMR, labour jobs OMR Chennai, helper jobs OMR, hospitality jobs OMR";
$hero_title = "Find Manpower Jobs in OMR Chennai";
$hero_subtitle = "Discover blue-collar and field-based opportunities in hospitality, operations, service, delivery and labour roles across OMR.";
$breadcrumb_name = "Manpower Jobs in OMR";
$content_section = '
<h2 class="h4 mb-3">Who should apply for manpower jobs?</h2>
<p class="text-muted">These jobs are best suited for candidates looking for practical on-ground work in hotels, restaurants, delivery, maintenance, operations and support services.</p>
<p class="text-muted mb-0">Employers can post urgent manpower requirements and quickly connect with local candidates in OMR.</p>
';
$filter_url = "/omr-local-job-listings/?work_segment=manpower";
$current_page_type = 'type';

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

