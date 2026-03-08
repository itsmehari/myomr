<?php
require_once __DIR__ . '/includes/employer-auth.php';
employerLogout();
header('Location: employer-login-omr.php?logged_out=1');
exit;
