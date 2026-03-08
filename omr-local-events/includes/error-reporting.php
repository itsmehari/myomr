<?php
/**
 * Events Module - Development Error Reporting
 */

// Start output buffering to ensure error messages can be displayed
ob_start();

// Allow global env override
$envFile = __DIR__ . '/../../core/env.php';
if (file_exists($envFile)) { include_once $envFile; }

if (!defined('DEVELOPMENT_MODE')) {
    define('DEVELOPMENT_MODE', true); // Set to false in production
}

if (DEVELOPMENT_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    // Suppress deprecation spam while developing
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
    ini_set('log_errors', 1);
    // Log to weblog specific to events module
    ini_set('error_log', __DIR__ . '/../../weblog/events-errors.log');
    ini_set('html_errors', 1);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('log_errors', 1);
}

function eventsCustomErrorHandler($errno, $errstr, $errfile, $errline) {
    if (DEVELOPMENT_MODE && (error_reporting() & $errno)) {
        echo "<div style='background:#fee; border:2px solid #f00; padding:15px; margin:10px; border-radius:5px;'>";
        echo "<strong>PHP Error (Events):</strong><br>";
        echo "<strong>Type:</strong> " . $errno . "<br>";
        echo "<strong>Message:</strong> " . htmlspecialchars($errstr) . "<br>";
        echo "<strong>File:</strong> " . htmlspecialchars($errfile) . "<br>";
        echo "<strong>Line:</strong> " . $errline . "<br>";
        echo "</div>";
    }
    return false;
}

set_error_handler('eventsCustomErrorHandler');

function eventsCustomExceptionHandler($exception) {
    if (DEVELOPMENT_MODE) {
        echo "<div style='background:#fee; border:2px solid #f00; padding:15px; margin:10px; border-radius:5px;'>";
        echo "<strong>Uncaught Exception (Events):</strong><br>";
        echo "<strong>Message:</strong> " . htmlspecialchars($exception->getMessage()) . "<br>";
        echo "<strong>File:</strong> " . htmlspecialchars($exception->getFile()) . "<br>";
        echo "<strong>Line:</strong> " . $exception->getLine() . "<br>";
        echo "<strong>Stack Trace:</strong><pre>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
        echo "</div>";
    }
}

set_exception_handler('eventsCustomExceptionHandler');

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL) {
        if (in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            if (DEVELOPMENT_MODE) {
                echo "<div style='background:#fee; border:2px solid #f00; padding:15px; margin:10px; border-radius:5px;'>";
                echo "<strong>Fatal Error (Events):</strong><br>";
                echo "<strong>Message:</strong> " . htmlspecialchars($error['message']) . "<br>";
                echo "<strong>File:</strong> " . htmlspecialchars($error['file']) . "<br>";
                echo "<strong>Line:</strong> " . $error['line'] . "<br>";
                echo "</div>";
            } else {
                // Friendly error page in production
                http_response_code(500);
                $friendly = __DIR__ . '/../../errors/500.php';
                if (file_exists($friendly)) { include $friendly; }
            }
        }
    }
});


