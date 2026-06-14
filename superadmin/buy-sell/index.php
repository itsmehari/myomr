<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/includes/module-router.php';
myomr_module_router([
    'constant' => 'BUY_SELL_ADMIN_ROUTED',
    'urls' => array (
  'BUY_SELL_ADMIN_INDEX_URL' => '/superadmin/buy-sell/index.php',
  'BUY_SELL_ADMIN_MANAGE_URL' => '/superadmin/buy-sell/manage-listings-omr.php',
  'BUY_SELL_ADMIN_CATEGORIES_URL' => '/superadmin/buy-sell/manage-categories-omr.php',
  'BUY_SELL_ADMIN_REPORTS_URL' => '/superadmin/buy-sell/view-reports-omr.php',
),
    'target' => dirname(__DIR__, 2) . '/omr-buy-sell/admin/index.php',
    'activeNav' => '/superadmin/buy-sell/',
]);
