<?php
// Central admin config: username + hashed password
// Uses PHP password_hash at runtime for the provided static password.

if (!defined('MYOMR_ADMIN_USERNAME')) {
    define('MYOMR_ADMIN_USERNAME', 'admin');
}

if (!defined('MYOMR_ADMIN_PASSWORD_HASH')) {
    // IMPORTANT: Change this for production. For now: admin / password
    define('MYOMR_ADMIN_PASSWORD_HASH', password_hash('password', PASSWORD_DEFAULT));
}

// Optional: default role assigned on successful login
if (!defined('MYOMR_ADMIN_DEFAULT_ROLE')) {
    define('MYOMR_ADMIN_DEFAULT_ROLE', 'super_admin');
}

// Optional secondary editor account (set both to enable)
if (!defined('MYOMR_EDITOR_USERNAME')) {
    define('MYOMR_EDITOR_USERNAME', 'events');
}
if (!defined('MYOMR_EDITOR_PASSWORD_HASH')) {
    define('MYOMR_EDITOR_PASSWORD_HASH', password_hash('password', PASSWORD_DEFAULT));
}


