<?php
/**
 * Owner Logout
 */

require_once __DIR__ . '/includes/owner-auth.php';
ownerLogout();

header('Location: ../omr-hostels-pgs/owner-login.php?loggedout=1');
exit;

