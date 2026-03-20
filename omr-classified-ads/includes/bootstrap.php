<?php
/**
 * OMR Classified Ads module bootstrap
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

$err_report = __DIR__ . '/error-reporting.php';
if (file_exists($err_report)) {
    require_once $err_report;
}
require_once __DIR__ . '/listing-functions.php';
require_once __DIR__ . '/auth-functions.php';

if (!defined('CLASSIFIED_ADS_PATH')) {
    define('CLASSIFIED_ADS_PATH', dirname(__DIR__));
}
if (!defined('CLASSIFIED_ADS_BASE_URL')) {
    define('CLASSIFIED_ADS_BASE_URL', '/omr-classified-ads');
}
