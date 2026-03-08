<?php
// File-based admin audit logging for Events
// Usage: eventAdminAudit('approve', ['submission_id'=>1,'listing_id'=>10]);

if (!function_exists('eventAdminAudit')) {
    function eventAdminAudit(string $action, array $payload = []): void {
        try {
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            $adminUser = $_SESSION['admin_logged_in'] ? 'admin' : 'guest';
            $record = [
                'ts' => date('c'),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
                'admin' => $adminUser,
                'action' => $action,
                'payload' => $payload,
                'path' => $_SERVER['REQUEST_URI'] ?? ''
            ];
            $dir = __DIR__ . '/../../weblog';
            if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
            $file = $dir . '/events-audit.log';
            @file_put_contents($file, json_encode($record, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND | LOCK_EX);
        } catch (Throwable $e) {
            // best-effort; do not break admin
            error_log('Events audit error: ' . $e->getMessage());
        }
    }
}


