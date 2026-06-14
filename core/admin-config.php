<?php
// Deprecated admin config.
// Admin authentication now uses the admin_users database table via core/admin-auth.php.
// These constants remain only for backwards compatibility with any stale includes.

if (!defined('MYOMR_ADMIN_USERNAME')) {
    define('MYOMR_ADMIN_USERNAME', '');
}

if (!defined('MYOMR_ADMIN_PASSWORD_HASH')) {
    define('MYOMR_ADMIN_PASSWORD_HASH', '');
}

// Optional: default role assigned on successful login
if (!defined('MYOMR_ADMIN_DEFAULT_ROLE')) {
    define('MYOMR_ADMIN_DEFAULT_ROLE', 'super_admin');
}

// Optional secondary editor account (set both to enable)
if (!defined('MYOMR_EDITOR_USERNAME')) {
    define('MYOMR_EDITOR_USERNAME', '');
}
if (!defined('MYOMR_EDITOR_PASSWORD_HASH')) {
    define('MYOMR_EDITOR_PASSWORD_HASH', '');
}

// HMAC key for stateless superadmin login CSRF tokens (override via env on server if desired)
if (!defined('MYOMR_ADMIN_LOGIN_CSRF_KEY')) {
    $csrfEnv = getenv('MYOMR_ADMIN_LOGIN_CSRF_KEY');
    define('MYOMR_ADMIN_LOGIN_CSRF_KEY', ($csrfEnv !== false && $csrfEnv !== '') ? $csrfEnv : 'myomr-superadmin-login-csrf-v1');
}
