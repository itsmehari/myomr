<?php
require_once __DIR__ . '/includes/bootstrap.php';
ca_session_start();
ca_logout();
header('Location: /omr-classified-ads/');
exit;
