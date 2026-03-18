<?php
/**
 * Legacy subscribe handler (flat-file). Prefer components/subscribe.php (DB + source_url).
 * Redirects use site root; site is PHP (no .html).
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $dir = dirname(__DIR__);
        $file = $dir . '/subscribers.txt';
        if (is_writable($dir) || (file_exists($file) && is_writable($file))) {
            file_put_contents($file, $email . "\n", FILE_APPEND);
        }
        header('Location: /?subscribed=1');
        exit;
    }
    header('Location: /?subscribe_error=invalid');
    exit;
}
