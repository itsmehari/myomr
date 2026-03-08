<?php
/**
 * Error Reporting Configuration
 * For MyOMR Coworking Spaces Portal
 */

// Enable error reporting in development
if (defined('APP_ENV') && APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    // Production: log errors but don't display
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../../weblog/error.log');
}

// Custom error handler for better logging
function coworkingSpaceErrorHandler($errno, $errstr, $errfile, $errline) {
    $timestamp = date('Y-m-d H:i:s');
    $errorMsg = "[$timestamp] Error #$errno: $errstr in $errfile on line $errline\n";
    error_log($errorMsg, 3, __DIR__ . '/../../weblog/error.log');
    
    // Don't execute PHP internal error handler
    return true;
}

set_error_handler('coworkingSpaceErrorHandler');

