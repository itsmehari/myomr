<?php
/**
 * Rent & Lease admin URL helpers (module vs site /admin/ router).
 */
if (!defined('RENT_LEASE_ADMIN_INDEX_URL')) {
    define('RENT_LEASE_ADMIN_INDEX_URL', '/superadmin/rent-lease/index.php');
    define('RENT_LEASE_ADMIN_MANAGE_URL', '/superadmin/rent-lease/manage-properties-omr.php');
    define('RENT_LEASE_ADMIN_PHOTOS_URL', '/superadmin/rent-lease/upload-property-images-omr.php');
}

function rent_lease_admin_photos_url(int $propertyId): string {
    return RENT_LEASE_ADMIN_PHOTOS_URL . '?id=' . $propertyId;
}
