<?php
/**
 * Development Error Reporting
 * Enable detailed error reporting during development
 */

// Start output buffering to ensure error messages can be displayed
ob_start();

if (!defined('DEVELOPMENT_MODE')) {
    define('DEVELOPMENT_MODE', true); // Set to false in production
}

if (DEVELOPMENT_MODE) {
    // Display all errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    // Log errors to file
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../../weblog/hostels-pgs-errors.log');
    
    // Show PHP errors in browser
    ini_set('html_errors', 1);
} else {
    // Production mode - hide errors
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('log_errors', 1);
}

// Custom error handler for better error display
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    if (DEVELOPMENT_MODE && (error_reporting() & $errno)) {
        echo "<div style='background:#fee; border:2px solid #f00; padding:15px; margin:10px; border-radius:5px;'>";
        echo "<strong>PHP Error:</strong><br>";
        echo "<strong>Type:</strong> " . $errno . "<br>";
        echo "<strong>Message:</strong> " . htmlspecialchars($errstr) . "<br>";
        echo "<strong>File:</strong> " . htmlspecialchars($errfile) . "<br>";
        echo "<strong>Line:</strong> " . $errline . "<br>";
        echo "</div>";
    }
    return false; // Let PHP's default error handler run too
}

set_error_handler("customErrorHandler");

// Exception handler
function customExceptionHandler($exception) {
    if (DEVELOPMENT_MODE) {
        echo "<div style='background:#fee; border:2px solid #f00; padding:15px; margin:10px; border-radius:5px;'>";
        echo "<strong>Uncaught Exception:</strong><br>";
        echo "<strong>Message:</strong> " . htmlspecialchars($exception->getMessage()) . "<br>";
        echo "<strong>File:</strong> " . htmlspecialchars($exception->getFile()) . "<br>";
        echo "<strong>Line:</strong> " . $exception->getLine() . "<br>";
        echo "<strong>Stack Trace:</strong><pre>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
        echo "</div>";
    }
}

set_exception_handler("customExceptionHandler");

// Check for fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && DEVELOPMENT_MODE) {
        if (in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            echo "<div style='background:#fee; border:2px solid #f00; padding:15px; margin:10px; border-radius:5px;'>";
            echo "<strong>Fatal Error:</strong><br>";
            echo "<strong>Message:</strong> " . htmlspecialchars($error['message']) . "<br>";
            echo "<strong>File:</strong> " . htmlspecialchars($error['file']) . "<br>";
            echo "<strong>Line:</strong> " . $error['line'] . "<br>";
            echo "</div>";
        }
    }
});

