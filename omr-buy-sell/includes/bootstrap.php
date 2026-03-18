<?php
/**
 * OMR Buy-Sell module bootstrap
 * Use from any page: require_once __DIR__ . '/includes/bootstrap.php';
 * For admin subdir: require_once __DIR__ . '/../includes/bootstrap.php';
 */
if (!defined('ROOT_PATH')) {
    $root = $_SERVER['DOCUMENT_ROOT'] ?? dirname(dirname(__DIR__));
    define('ROOT_PATH', $root);
}
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$core_file = ROOT_PATH . '/core/omr-connect.php';
if (file_exists($core_file)) {
    require_once $core_file;
}

require_once __DIR__ . '/error-reporting.php';
require_once __DIR__ . '/listing-functions.php';

if (!defined('BUY_SELL_PATH')) {
    define('BUY_SELL_PATH', dirname(__DIR__));
}
if (!defined('BUY_SELL_BASE_URL')) {
    define('BUY_SELL_BASE_URL', '/omr-buy-sell');
}
