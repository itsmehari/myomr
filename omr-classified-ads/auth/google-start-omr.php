<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/oauth-google.php';

ca_session_start();
$state = bin2hex(random_bytes(16));
$_SESSION['ca_google_oauth_state'] = $state;
$url = ca_google_auth_url($state);
if (!$url) {
    header('Location: /omr-classified-ads/login-omr.php?oauth=missing_config');
    exit;
}
header('Location: ' . $url);
exit;
