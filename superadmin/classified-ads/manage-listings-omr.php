<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/module-router.php';
myomr_module_router([
    'constant' => 'CLASSIFIED_ADS_ADMIN_ROUTED',
    'urls' => array (
  'CLASSIFIED_ADS_ADMIN_INDEX_URL' => '/superadmin/classified-ads/index.php',
  'CLASSIFIED_ADS_ADMIN_MANAGE_URL' => '/superadmin/classified-ads/manage-listings-omr.php',
  'CLASSIFIED_ADS_ADMIN_REPORTS_URL' => '/superadmin/classified-ads/view-reports-omr.php',
),
    'target' => dirname(__DIR__, 2) . '/omr-classified-ads/admin/manage-listings-omr.php',
    'activeNav' => '/superadmin/classified-ads/',
]);
