<?php
/**
 * BLO Page Subscription Handler
 * Sends subscription requests to harikrishnanhk1988@gmail.com
 */

require_once '../core/email.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subscribe_email'])) {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $source = htmlspecialchars(trim($_POST['source'] ?? 'BLO Page'));
    
    // Honeypot field (spam protection)
    if (!empty($_POST['website'])) {
        // Bot detected, silently fail
        header('Location: find-blo-officer.php?subscribed=1');
        exit;
    }
    
    if (empty($email)) {
        $error = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Prepare email
        $to = 'harikrishnanhk1988@gmail.com';
        $subject = 'New MyOMR Subscription - BLO Page';
        
        $body = '<h2>New Subscription Request</h2>';
        $body .= '<p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>';
        if (!empty($name)) {
            $body .= '<p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>';
        }
        $body .= '<p><strong>Source:</strong> ' . htmlspecialchars($source) . '</p>';
        $body .= '<p><strong>Date:</strong> ' . date('Y-m-d H:i:s') . '</p>';
        $body .= '<p><strong>Page:</strong> ' . (isset($_POST['page_url']) ? htmlspecialchars($_POST['page_url']) : 'BLO Search Page') . '</p>';
        
        $htmlBody = renderEmailTemplate('New MyOMR Subscription', $body);
        
        // Send email
        if (sendEmail($to, $subject, $htmlBody, 'no-reply@myomr.in', 'MyOMR Subscription')) {
            $success = true;
            
            // Also save to file for backup
            $logFile = __DIR__ . '/../weblog/blo-subscriptions.csv';
            $logDir = dirname($logFile);
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0755, true);
            }
            $logEntry = date('Y-m-d H:i:s') . ',' . str_replace(["\n", "\r", ","], ' ', $email) . ',' . str_replace(["\n", "\r", ","], ' ', $name) . ',' . $source . "\n";
            @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        } else {
            $error = 'Sorry, there was an error processing your subscription. Please try again later.';
        }
    }
}

// Redirect back with status
$redirectUrl = 'find-blo-officer.php';
if (isset($_POST['page_url'])) {
    $redirectUrl = htmlspecialchars($_POST['page_url']);
}

if ($success) {
    header('Location: ' . $redirectUrl . '?subscribed=1');
} else {
    $errorParam = $error ? '&error=' . urlencode($error) : '';
    header('Location: ' . $redirectUrl . '?subscribe_error=1' . $errorParam);
}
exit;

