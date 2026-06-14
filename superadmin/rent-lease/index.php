<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/includes/module-router.php';
myomr_module_router([
    'constant' => 'RENT_LEASE_ADMIN_ROUTED',
    'urls' => array (
  'RENT_LEASE_ADMIN_INDEX_URL' => '/superadmin/rent-lease/index.php',
  'RENT_LEASE_ADMIN_MANAGE_URL' => '/superadmin/rent-lease/manage-properties-omr.php',
  'RENT_LEASE_ADMIN_PHOTOS_URL' => '/superadmin/rent-lease/upload-property-images-omr.php',
),
    'target' => dirname(__DIR__, 2) . '/omr-rent-lease/admin/index.php',
    'activeNav' => '/superadmin/rent-lease/',
]);
