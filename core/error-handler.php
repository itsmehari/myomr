<?php
require_once __DIR__ . '/env.php';

// Configure error display/logging based on environment
if (omr_is_production()) {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
} else {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
}
error_reporting(E_ALL);

// Ensure logs directory exists
$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) { @mkdir($logDir, 0775, true); }

function omr_log_path(): string {
    $date = date('Y-m-d');
    return __DIR__ . '/../logs/app-' . $date . '.log';
}

function omr_log(string $level, string $message, array $context = []): void {
    $line = sprintf('%s	%s	%s	%s%s',
        date('c'),
        strtoupper($level),
        $_SERVER['REQUEST_URI'] ?? '-',
        $message,
        empty($context) ? '' : ("\t" . json_encode($context))
    );
    @file_put_contents(omr_log_path(), $line . "\n", FILE_APPEND | LOCK_EX);
}

set_error_handler(function($severity, $message, $file, $line) {
    // Respect @-silence
    if (!(error_reporting() & $severity)) { return false; }
    omr_log('error', $message, ['file'=>$file, 'line'=>$line, 'severity'=>$severity]);
    return false; // allow PHP's internal handler as well
});

set_exception_handler(function($ex){
    omr_log('exception', $ex->getMessage(), [
        'file' => $ex->getFile(),
        'line' => $ex->getLine(),
        'trace' => $ex->getTraceAsString()
    ]);
    if (!headers_sent()) { http_response_code(500); }
    if (!omr_is_production()) {
        echo '<pre style="padding:16px;white-space:pre-wrap;">' . htmlspecialchars($ex->__toString(), ENT_QUOTES, 'UTF-8') . '</pre>';
    } else {
        echo '<!DOCTYPE html><html><body><h1>Something went wrong</h1><p>Please try again later.</p></body></html>';
    }
});

register_shutdown_function(function(){
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        omr_log('fatal', $err['message'], ['file'=>$err['file'], 'line'=>$err['line']]);
    }
});


