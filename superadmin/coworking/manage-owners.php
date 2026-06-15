<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/module-router.php';
myomr_module_router([
    'constant' => 'COWORKING_ADMIN_ROUTED',
    'urls' => array (
  'COWORKING_ADMIN_INDEX_URL' => '/superadmin/coworking/index.php',
  'COWORKING_ADMIN_SPACES_URL' => '/superadmin/coworking/manage-spaces.php',
  'COWORKING_ADMIN_OWNERS_URL' => '/superadmin/coworking/manage-owners.php',
  'COWORKING_ADMIN_INQUIRIES_URL' => '/superadmin/coworking/view-all-inquiries.php',
  'COWORKING_ADMIN_FEATURED_URL' => '/superadmin/coworking/featured-management.php',
),
    'target' => dirname(__DIR__, 2) . '/omr-coworking-spaces/admin/manage-owners.php',
    'activeNav' => '/superadmin/coworking/',
]);
