<?php
declare(strict_types=1);

if (!defined('HOSTELS_ADMIN_INDEX_URL')) {
    define('HOSTELS_ADMIN_INDEX_URL', '/superadmin/hostels/index.php');
    define('HOSTELS_ADMIN_PROPERTIES_URL', '/superadmin/hostels/manage-properties.php');
    define('HOSTELS_ADMIN_OWNERS_URL', '/superadmin/hostels/manage-owners.php');
    define('HOSTELS_ADMIN_INQUIRIES_URL', '/superadmin/hostels/view-all-inquiries.php');
    define('HOSTELS_ADMIN_FEATURED_URL', '/superadmin/hostels/featured-management.php');
}

function hostels_admin_action_url(string $script): string
{
    return '/superadmin/hostels/' . ltrim($script, '/');
}
