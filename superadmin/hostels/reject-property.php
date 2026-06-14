<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/includes/module-router.php';
myomr_module_router([
    'constant' => 'HOSTELS_ADMIN_ROUTED',
    'urls' => array (
  'HOSTELS_ADMIN_INDEX_URL' => '/superadmin/hostels/index.php',
  'HOSTELS_ADMIN_PROPERTIES_URL' => '/superadmin/hostels/manage-properties.php',
  'HOSTELS_ADMIN_OWNERS_URL' => '/superadmin/hostels/manage-owners.php',
  'HOSTELS_ADMIN_INQUIRIES_URL' => '/superadmin/hostels/view-all-inquiries.php',
  'HOSTELS_ADMIN_FEATURED_URL' => '/superadmin/hostels/featured-management.php',
),
    'target' => dirname(__DIR__, 2) . '/omr-hostels-pgs/admin/reject-property.php',
    'activeNav' => '/superadmin/hostels/',
]);
