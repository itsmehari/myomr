<?php
/**
 * Elections 2026 module bootstrap — path setup, root components, DB.
 * Use from any page in elections-2026: require_once __DIR__ . '/includes/bootstrap.php';
 * From subdirs (e.g. constituency/): require_once __DIR__ . '/../includes/bootstrap.php';
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

if (!defined('ELECTIONS_2026_PATH')) {
    define('ELECTIONS_2026_PATH', dirname(__DIR__));
}

if (!defined('ELECTIONS_2026_BASE_URL')) {
    define('ELECTIONS_2026_BASE_URL', '/elections-2026');
}
