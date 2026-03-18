<?php
/**
 * Office Jobs in OMR Chennai - SEO Landing Page
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
$work_segment = 'office';

if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    if (jobPostingsHasColumn('work_segment')) {
        $query = "SELECT j.*, e.company_name
                  FROM job_postings j
                  LEFT JOIN employers e ON j.employer_id = e.id
                  WHERE j.status = 'approved' AND j.work_segment = 'office'
                  ORDER BY j.featured DESC, j.created_at DESC
                  LIMIT 12";
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            $location_jobs = $result->fetch_all(MYSQLI_ASSOC);
        }

        $countResult = $conn->query("SELECT COUNT(*) AS total FROM job_postings WHERE status = 'approved' AND work_segment = 'office'");
        if ($countResult) {
            $location_job_count = (int) (($countResult->fetch_assoc()['total'] ?? 0));
        }
    } else {
        // Fallback before migration: infer office jobs from existing data.
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
                if (inferJobSegmentFromData($row) === 'office') {
                    $location_jobs[] = $row;
                }
            }
            $location_jobs = array_slice($location_jobs, 0, 12);
            $location_job_count = count($location_jobs);
        }
    }
}

$page_title = "Office Jobs in OMR Chennai - IT, Admin, Accounts & Executive Roles | MyOMR";
$page_description = "Find office jobs in OMR Chennai including IT, admin, accounts, billing, support and executive roles. Browse local openings and apply directly.";
$canonical_url = "https://myomr.in/office-jobs-omr-chennai.php";
$page_keywords = "office jobs OMR, white collar jobs OMR Chennai, admin jobs OMR, accounts jobs OMR, IT office jobs Chennai";
$hero_title = "Find Office Jobs in OMR Chennai";
$hero_subtitle = "Explore white-collar opportunities in IT, administration, finance, customer support and other office-based roles across OMR.";
$breadcrumb_name = "Office Jobs in OMR";
$content_section = '
<h2 class="h4 mb-3">Who should apply for office jobs?</h2>
<p class="text-muted">This section is for candidates looking for desk-based roles in IT, operations, administration, accounts, customer support and management functions.</p>
<p class="text-muted mb-0">Employers can post office openings and attract qualified local professionals in OMR quickly.</p>
';
$filter_url = "/omr-local-job-listings/?work_segment=office";
$current_page_type = 'type';

require_once __DIR__ . '/omr-local-job-listings/includes/landing-page-template.php';

