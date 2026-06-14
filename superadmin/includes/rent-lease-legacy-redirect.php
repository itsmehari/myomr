<?php
/**
 * Legacy flat rent-lease routes → superadmin/rent-lease/ folder (301).
 */
declare(strict_types=1);

$map = [
    '/superadmin/rent-lease.php' => '/superadmin/rent-lease/index.php',
    '/superadmin/rent-lease-manage.php' => '/superadmin/rent-lease/manage-properties-omr.php',
    '/superadmin/rent-lease-photos.php' => '/superadmin/rent-lease/upload-property-images-omr.php',
];

$self = basename($_SERVER['SCRIPT_NAME'] ?? '');
$targets = [
    'rent-lease.php' => '/superadmin/rent-lease/index.php',
    'rent-lease-manage.php' => '/superadmin/rent-lease/manage-properties-omr.php',
    'rent-lease-photos.php' => '/superadmin/rent-lease/upload-property-images-omr.php',
];

if (isset($targets[$self])) {
    $q = $_SERVER['QUERY_STRING'] ?? '';
    header('Location: ' . $targets[$self] . ($q !== '' ? '?' . $q : ''), true, 301);
    exit;
}
