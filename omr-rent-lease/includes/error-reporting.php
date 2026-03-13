<?php
/**
 * Development Error Reporting — Rent & Lease
 * Enable detailed error reporting during development
 */

ob_start();

if (!defined('DEVELOPMENT_MODE')) {
    define('DEVELOPMENT_MODE', true); // Set to false in production
}

if (DEVELOPMENT_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../../weblog/rent-lease-errors.log');
    ini_set('html_errors', 1);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('log_errors', 1);
}

function _rent_lease_error_handler($errno, $errstr, $errfile, $errline) {
    if (DEVELOPMENT_MODE && (error_reporting() & $errno)) {
        echo "<div style='background:#fee; border:2px solid #f00; padding:15px; margin:10px; border-radius:5px;'>";
        echo "<strong>PHP Error:</strong><br>" . htmlspecialchars($errstr) . " in " . htmlspecialchars($errfile) . ":" . $errline . "</div>";
    }
    return false;
}

set_error_handler("_rent_lease_error_handler");

function _rent_lease_exception_handler($e) {
    if (DEVELOPMENT_MODE) {
        echo "<div style='background:#fee; border:2px solid #f00; padding:15px; margin:10px;'>";
        echo "<strong>Exception:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

set_exception_handler("_rent_lease_exception_handler");
