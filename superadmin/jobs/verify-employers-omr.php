<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/includes/module-router.php';
myomr_module_router([
    'constant' => 'JOBS_ADMIN_ROUTED',
    'urls' => array (
  'JOBS_ADMIN_INDEX_URL' => '/superadmin/jobs/index.php',
  'JOBS_ADMIN_MANAGE_URL' => '/superadmin/jobs/manage-jobs-omr.php',
  'JOBS_ADMIN_VERIFY_EMPLOYERS_URL' => '/superadmin/jobs/verify-employers-omr.php',
  'JOBS_ADMIN_APPLICATIONS_URL' => '/superadmin/jobs/view-all-applications-omr.php',
  'JOBS_ADMIN_PACKAGE_SUBSCRIBERS_URL' => '/superadmin/jobs/package-subscribers-omr.php',
),
    'target' => dirname(__DIR__, 2) . '/omr-local-job-listings/admin/verify-employers-omr.php',
    'activeNav' => '/superadmin/jobs/',
]);
