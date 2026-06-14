<?php
require_once __DIR__ . '/_auth.php';
adminLogout();
superadmin_redirect('/superadmin/login.php');
