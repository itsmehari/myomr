<?php
/**
 * Superadmin auth — hardcoded credentials + PHP session.
 * Edit SUPERADMIN_USERNAME and SUPERADMIN_PASSWORD below to change login.
 */

if (defined('SUPERADMIN_AUTH_LOADED')) {
    return;
}
define('SUPERADMIN_AUTH_LOADED', true);

const SUPERADMIN_USERNAME = 'harikrishnanhk1988@gmail.com';
const SUPERADMIN_PASSWORD = 'Mylapore@21g';
const SUPERADMIN_FULL_NAME = 'Harikrishnan';
const SUPERADMIN_ROLE = 'super_admin';

function superadmin_dashboard_url(): string {
    return '/superadmin/index.php';
}

/** Redirect even when BOM or prior output already sent headers. */
function superadmin_redirect(string $url, int $code = 302): void {
    if (!headers_sent()) {
        header('Location: ' . $url, true, $code);
        exit;
    }
    $safe = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">';
    echo '<meta http-equiv="refresh" content="0;url=' . $safe . '">';
    echo '<title>Redirecting…</title></head><body style="font-family:sans-serif;text-align:center;padding:3rem;">';
    echo '<p>Redirecting…</p><p><a href="' . $safe . '">Continue to dashboard</a></p></body></html>';
    exit;
}

function superadmin_normalize_redirect(string $target): string {
    $target = trim($target);
    if ($target === '' || str_starts_with($target, '//')) {
        return superadmin_dashboard_url();
    }

    $parts = parse_url($target);
    if (isset($parts['scheme']) || isset($parts['host'])) {
        return superadmin_dashboard_url();
    }

    $path = $parts['path'] ?? '';
    if ($path === '/superadmin' || $path === '/superadmin/') {
        return superadmin_dashboard_url();
    }

    $isAdminPath = str_starts_with($path, '/superadmin/')
        || preg_match('#^/omr-[a-z0-9-]+/admin/#', $path);

    if (!$isAdminPath) {
        return superadmin_dashboard_url();
    }

    $query = isset($parts['query']) ? '?' . $parts['query'] : '';
    return $path . $query;
}

if (!function_exists('superadmin_start_session')) {
    function superadmin_start_session(): void {
        if (session_status() !== PHP_SESSION_NONE) {
            return;
        }

        $savePath = dirname(__DIR__) . '/storage/sessions';
        if (!is_dir($savePath)) {
            @mkdir($savePath, 0700, true);
        }
        if (is_dir($savePath) && is_writable($savePath)) {
            session_save_path($savePath);
        }

        $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
        $isMyomrHost = ($host === 'myomr.in' || substr($host, -8) === '.myomr.in');
        $secure = $isMyomrHost
            || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || ((int) ($_SERVER['SERVER_PORT'] ?? 0) === 443)
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

        if (PHP_VERSION_ID >= 70300) {
            if (!headers_sent()) {
                session_set_cookie_params([
                    'lifetime' => 0,
                    'path'     => '/',
                    'domain'   => '',
                    'secure'   => $secure,
                    'httponly' => true,
                    'samesite' => 'Lax',
                ]);
            }
        } elseif (!headers_sent()) {
            session_set_cookie_params(0, '/; samesite=Lax', '', $secure, true);
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }
    }
}

superadmin_start_session();

function isAdminLoggedIn(): bool {
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdmin(): void {
    if (!isAdminLoggedIn()) {
        $target = superadmin_normalize_redirect((string) ($_SERVER['REQUEST_URI'] ?? superadmin_dashboard_url()));
        superadmin_redirect('/superadmin/login.php?redirect=' . rawurlencode($target));
    }
}

function requireRole(array $allowedRoles): void {
    requireAdmin();
    $role = (string) ($_SESSION['admin_role'] ?? '');
    if (!in_array($role, $allowedRoles, true)) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
}

function attemptAdminLogin(string $username, string $password): bool {
    $username = trim($username);
    if ($username === '' || $password === '') {
        return false;
    }

    if ($username !== SUPERADMIN_USERNAME || $password !== SUPERADMIN_PASSWORD) {
        return false;
    }

    if (!headers_sent()) {
        session_regenerate_id(true);
    }

    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_login_time'] = time();
    $_SESSION['admin_username'] = SUPERADMIN_USERNAME;
    $_SESSION['admin_full_name'] = SUPERADMIN_FULL_NAME;
    $_SESSION['admin_role'] = SUPERADMIN_ROLE;

    return true;
}

function adminLogout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
}
