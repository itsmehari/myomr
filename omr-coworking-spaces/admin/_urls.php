<?php
declare(strict_types=1);

if (!defined('COWORKING_ADMIN_INDEX_URL')) {
    define('COWORKING_ADMIN_INDEX_URL', '/superadmin/coworking/index.php');
    define('COWORKING_ADMIN_SPACES_URL', '/superadmin/coworking/manage-spaces.php');
    define('COWORKING_ADMIN_OWNERS_URL', '/superadmin/coworking/manage-owners.php');
    define('COWORKING_ADMIN_INQUIRIES_URL', '/superadmin/coworking/view-all-inquiries.php');
    define('COWORKING_ADMIN_FEATURED_URL', '/superadmin/coworking/featured-management.php');
}

function coworking_admin_action_url(string $script): string
{
    return '/superadmin/coworking/' . ltrim($script, '/');
}
