<?php
// Master admin auth for MyOMR

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/admin-config.php';

function isAdminLoggedIn(): bool {
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdmin(): void {
    if (!isAdminLoggedIn()) {
        $redirect = urlencode($_SERVER['REQUEST_URI'] ?? '/');
        header('Location: /admin/login.php?redirect=' . $redirect);
        exit;
    }
}

function requireRole(array $allowedRoles): void {
    requireAdmin();
    $role = $_SESSION['admin_role'] ?? '';
    if (!in_array($role, $allowedRoles, true)) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
}

function attemptAdminLogin(string $username, string $password): bool {
    // Super admin
    if (hash_equals((string)MYOMR_ADMIN_USERNAME, (string)$username) && password_verify($password, (string)MYOMR_ADMIN_PASSWORD_HASH)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_login_time'] = time();
        $_SESSION['admin_role'] = (string)MYOMR_ADMIN_DEFAULT_ROLE; // super_admin
        return true;
    }
    // Editor events (optional)
    if (!empty(MYOMR_EDITOR_USERNAME) && hash_equals((string)MYOMR_EDITOR_USERNAME, (string)$username) && !empty(MYOMR_EDITOR_PASSWORD_HASH) && password_verify($password, (string)MYOMR_EDITOR_PASSWORD_HASH)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_login_time'] = time();
        $_SESSION['admin_role'] = 'editor_events';
        return true;
    }
    return false;
}

function adminLogout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}


