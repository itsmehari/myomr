<?php
declare(strict_types=1);

/**
 * Flat superadmin URLs for Hostels & PGs (production-safe; no /superadmin/hostels/ subfolder).
 */
if (!defined('HOSTELS_ADMIN_INDEX_URL')) {
    define('HOSTELS_ADMIN_INDEX_URL', '/superadmin/hostels.php');
    define('HOSTELS_ADMIN_PROPERTIES_URL', '/superadmin/hostels-manage.php');
    define('HOSTELS_ADMIN_OWNERS_URL', '/superadmin/hostels/manage-owners.php');
    define('HOSTELS_ADMIN_INQUIRIES_URL', '/superadmin/hostels/view-all-inquiries.php');
    define('HOSTELS_ADMIN_FEATURED_URL', '/superadmin/hostels/featured-management.php');
}

if (!function_exists('hostels_admin_action_url')) {
    function hostels_admin_action_url(string $script): string
    {
        $map = [
            'approve-property.php' => '/superadmin/hostels-approve-property.php',
            'reject-property.php' => '/superadmin/hostels-reject-property.php',
            'verify-property.php' => '/superadmin/hostels-verify-property.php',
            'unlist-property.php' => '/superadmin/hostels-unlist-property.php',
            'delete-property.php' => '/superadmin/hostels-delete-property.php',
            'bulk-update-properties.php' => '/superadmin/hostels-bulk-update.php',
        ];

        return $map[$script] ?? HOSTELS_ADMIN_PROPERTIES_URL;
    }
}

if (!function_exists('hostels_flat_register_shell')) {
    function hostels_flat_register_shell(): void
    {
        if (!defined('MYOMR_ADMIN_SHELL')) {
            define('MYOMR_ADMIN_SHELL', true);
        }
        if (!function_exists('myomr_module_using_shell')) {
            function myomr_module_using_shell(): bool
            {
                return defined('MYOMR_ADMIN_SHELL') && MYOMR_ADMIN_SHELL;
            }
            function myomr_module_shell_open(string $pageTitle, string $activeNav = '', array $breadcrumbs = []): void
            {
                extract([
                    'pageTitle' => $pageTitle,
                    'activeNav' => $activeNav,
                    'breadcrumbs' => $breadcrumbs,
                ], EXTR_SKIP);
                include __DIR__ . '/includes/admin-shell-open.php';
            }
            function myomr_module_shell_close(): void
            {
                include __DIR__ . '/includes/admin-shell-close.php';
                exit;
            }
        }
    }
}
