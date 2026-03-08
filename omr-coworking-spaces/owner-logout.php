<?php
/**
 * Owner Logout
 */

require_once __DIR__ . '/includes/owner-auth.php';
ownerLogout();

header('Location: ../omr-coworking-spaces/owner-login.php?loggedout=1');
exit;

