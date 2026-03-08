<?php
// Application secrets/config for token generation (do not expose publicly)
if (!defined('MYOMR_EVENTS_MANAGE_SECRET')) {
    define('MYOMR_EVENTS_MANAGE_SECRET', 'change-this-strong-secret-please-rotate');
}

// Mailer defaults (can be moved to env/config later)
if (!defined('MYOMR_MAIL_FROM')) {
    define('MYOMR_MAIL_FROM', 'no-reply@myomr.in');
}
if (!defined('MYOMR_MAIL_FROM_NAME')) {
    define('MYOMR_MAIL_FROM_NAME', 'MyOMR');
}
if (!defined('MYOMR_ADMIN_ALERT_EMAIL')) {
    define('MYOMR_ADMIN_ALERT_EMAIL', 'myomrnews@gmail.com');
}


