<?php
/**
 * Events module bootstrap — path setup, root components, DB, module helpers.
 * Use from any page in omr-local-events: require_once __DIR__ . '/includes/bootstrap.php';
 * For subdirs (e.g. admin): require_once __DIR__ . '/../includes/bootstrap.php';
 *
 * Sets ROOT_PATH, loads core include-path + page-bootstrap, DB, error-reporting, event-functions.
 * @see omr-hostels-pgs/includes/bootstrap.php for pattern
 */
require_once dirname(__DIR__, 2) . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$core_file = ROOT_PATH . '/core/omr-connect.php';
if (file_exists($core_file)) {
    require_once $core_file;
}

$err_report = __DIR__ . '/error-reporting.php';
if (file_exists($err_report)) {
    require_once $err_report;
}
require_once __DIR__ . '/event-functions-omr.php';

if (!defined('EVENTS_PATH')) {
    define('EVENTS_PATH', dirname(__DIR__));
}

if (!defined('EVENTS_BASE_URL')) {
    define('EVENTS_BASE_URL', MYOMR_EVENTS_HUB_PATH);
}
