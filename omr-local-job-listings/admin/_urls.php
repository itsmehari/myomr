<?php
/**
 * Jobs module admin URL constants (superadmin command center routes).
 */
declare(strict_types=1);

if (!defined('JOBS_ADMIN_INDEX_URL')) {
    define('JOBS_ADMIN_INDEX_URL', '/superadmin/jobs/index.php');
    define('JOBS_ADMIN_MANAGE_URL', '/superadmin/jobs/manage-jobs-omr.php');
    define('JOBS_ADMIN_VERIFY_EMPLOYERS_URL', '/superadmin/jobs/verify-employers-omr.php');
    define('JOBS_ADMIN_APPLICATIONS_URL', '/superadmin/jobs/view-all-applications-omr.php');
    define('JOBS_ADMIN_PACKAGE_SUBSCRIBERS_URL', '/superadmin/jobs/package-subscribers-omr.php');
}

function jobs_admin_applications_for_job_url(int $jobId): string
{
    return '/omr-local-job-listings/view-applications-omr.php?id=' . $jobId;
}
