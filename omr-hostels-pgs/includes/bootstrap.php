<?php
/**
 * Hostels & PGs module bootstrap — path setup, root components, DB, module helpers.
 * Use from any page in omr-hostels-pgs (or subdirs): require_once __DIR__ . '/includes/bootstrap.php';
 * For subdirs (e.g. admin): require_once __DIR__ . '/../includes/bootstrap.php';
 *
 * Sets ROOT_PATH, loads core include-path + page-bootstrap, DB, error-reporting, property-functions.
 * @see index.php (root) pattern: core/include-path.php + components/page-bootstrap.php
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
require_once __DIR__ . '/property-functions.php';

if (!defined('HOSTELS_PGS_PATH')) {
    define('HOSTELS_PGS_PATH', dirname(__DIR__));
}

if (!defined('HOSTELS_PGS_BASE_URL')) {
    define('HOSTELS_PGS_BASE_URL', '/omr-hostels-pgs');
}
