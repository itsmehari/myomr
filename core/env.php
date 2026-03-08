<?php
// Global environment flags (override module defaults)
// Set to false on production deployments
if (!defined('DEVELOPMENT_MODE')) {
    define('DEVELOPMENT_MODE', true);
}

function omr_env(): string {
    if (isset($_SERVER['MYOMR_ENV']) && $_SERVER['MYOMR_ENV']) return (string)$_SERVER['MYOMR_ENV'];
    if (getenv('MYOMR_ENV')) return getenv('MYOMR_ENV');
    return 'production';
}
function omr_is_production(): bool { return omr_env() === 'production'; }
function omr_is_development(): bool { return omr_env() !== 'production'; }


