<?php
require_once __DIR__ . '/../core/admin-auth.php';
adminLogout();
header('Location: /admin/login.php');
exit; 