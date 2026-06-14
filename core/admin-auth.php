<?php
// Master admin auth for MyOMR

require_once __DIR__ . '/admin-config.php';

if (!function_exists('admin_auth_session_save_path')) {
    function admin_auth_session_save_path(): ?string {
        $candidates = [
            dirname(__DIR__) . '/storage/sessions',
            sys_get_temp_dir() . '/myomr-sessions',
        ];
        foreach ($candidates as $dir) {
            if (!is_dir($dir)) {
                @mkdir($dir, 0700, true);
            }
            if (is_dir($dir) && is_writable($dir)) {
                return $dir;
            }
        }
        return null;
    }
}

if (!function_exists('admin_auth_start_session')) {
    /**
     * Start PHP session with consistent cookie params (HTTPS, SameSite=Lax).
     * Must run before session_start(); safe to call multiple times.
     */
    function admin_auth_start_session(): void {
        if (session_status() !== PHP_SESSION_NONE) {
            return;
        }

        $savePath = admin_auth_session_save_path();
        if ($savePath !== null) {
            session_save_path($savePath);
        }

        $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
        $isMyomrHost = ($host === 'myomr.in' || substr($host, -8) === '.myomr.in');
        $secure = $isMyomrHost
            || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || ((int) ($_SERVER['SERVER_PORT'] ?? 0) === 443)
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

        if (PHP_VERSION_ID >= 70300) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'domain'   => '',
                'secure'   => $secure,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        } else {
            session_set_cookie_params(0, '/; samesite=Lax', '', $secure, true);
        }

        session_start();
    }
}

if (!function_exists('admin_login_csrf_issue')) {
    /** Stateless signed CSRF token for superadmin login (no session required to validate). */
    function admin_login_csrf_issue(): string {
        $time = (string) time();
        $nonce = bin2hex(random_bytes(16));
        $payload = $time . '.' . $nonce;
        $sig = hash_hmac('sha256', $payload, MYOMR_ADMIN_LOGIN_CSRF_KEY);
        return $payload . '.' . $sig;
    }
}

if (!function_exists('admin_login_csrf_verify')) {
    function admin_login_csrf_verify(string $token, int $maxAgeSeconds = 7200): bool {
        $token = trim($token);
        if ($token === '') {
            return false;
        }
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }
        [$time, $nonce, $sig] = $parts;
        if (!ctype_digit($time) || !ctype_xdigit($nonce)) {
            return false;
        }
        $age = time() - (int) $time;
        if ($age < 0 || $age > $maxAgeSeconds) {
            return false;
        }
        $payload = $time . '.' . $nonce;
        $expected = hash_hmac('sha256', $payload, MYOMR_ADMIN_LOGIN_CSRF_KEY);
        return hash_equals($expected, $sig);
    }
}

admin_auth_start_session();

function isAdminLoggedIn(): bool {
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdmin(): void {
    if (!isAdminLoggedIn()) {
        $redirect = urlencode($_SERVER['REQUEST_URI'] ?? '/');
        header('Location: /superadmin/login.php?redirect=' . $redirect);
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

/**
 * Fetch one assoc row from a prepared statement (works without mysqlnd).
 */
function admin_auth_stmt_fetch_assoc(mysqli_stmt $stmt): ?array {
    $result = $stmt->get_result();
    if ($result !== false) {
        $row = $result->fetch_assoc();
        return $row ?: null;
    }
    if (!$stmt->store_result()) {
        return null;
    }
    $meta = $stmt->result_metadata();
    if (!$meta) {
        return null;
    }
    $row = [];
    $bind = [];
    while ($field = $meta->fetch_field()) {
        $bind[] = &$row[$field->name];
    }
    if ($bind !== []) {
        call_user_func_array([$stmt, 'bind_result'], $bind);
    }
    if (!$stmt->fetch()) {
        $meta->free();
        return null;
    }
    $copy = [];
    foreach ($row as $key => $value) {
        $copy[$key] = $value;
    }
    $meta->free();
    return $copy;
}

function attemptAdminLogin(string $username, string $password): bool {
    require_once __DIR__ . '/omr-connect.php';
    global $conn;

    if (!($conn instanceof mysqli)) {
        error_log('Admin login: database connection unavailable');
        return false;
    }

    $username = trim($username);
    if ($username === '' || $password === '') {
        return false;
    }

    $stmt = $conn->prepare('SELECT username, password, role, full_name FROM admin_users WHERE username = ? OR email = ? LIMIT 1');
    if (!$stmt) {
        error_log('Admin login prepare failed: ' . $conn->error);
        return false;
    }

    $stmt->bind_param('ss', $username, $username);
    if (!$stmt->execute()) {
        error_log('Admin login execute failed: ' . $stmt->error);
        $stmt->close();
        return false;
    }

    $admin = null;
    $dbUsername = null;
    $dbPassword = null;
    $dbRole = null;
    $dbFullName = null;

    // Prefer mysqlnd get_result(); fall back to bind_result (typical on cPanel without mysqlnd).
    $result = $stmt->get_result();
    if ($result instanceof mysqli_result) {
        $row = $result->fetch_assoc();
        if (is_array($row)) {
            $admin = $row;
        }
    } else {
        $stmt->bind_result($dbUsername, $dbPassword, $dbRole, $dbFullName);
        if ($stmt->fetch()) {
            $admin = [
                'username'  => (string) $dbUsername,
                'password'  => (string) $dbPassword,
                'role'      => (string) $dbRole,
                'full_name' => (string) $dbFullName,
            ];
        }
    }
    $stmt->close();

    if ($admin === null) {
        error_log('Admin login: user not found');
        return false;
    }

    if (!password_verify($password, (string) $admin['password'])) {
        error_log('Admin login: password_verify failed for ' . ($admin['username'] ?? 'unknown'));
        return false;
    }

    if (!headers_sent()) {
        session_regenerate_id(true);
    }
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_login_time'] = time();
    $_SESSION['admin_username'] = (string) $admin['username'];
    $_SESSION['admin_full_name'] = (string) ($admin['full_name'] ?: $admin['username']);
    $_SESSION['admin_role'] = (string) ($admin['role'] ?: 'admin');

    $update = $conn->prepare('UPDATE admin_users SET last_login = NOW() WHERE username = ?');
    if ($update) {
        $update->bind_param('s', $admin['username']);
        $update->execute();
        $update->close();
    }

    return true;
}

function adminLogout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}


