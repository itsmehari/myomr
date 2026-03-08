<?php
/**
 * Security Helper Functions for MyOMR.in
 * This file contains common security functions for input validation and sanitization
 */

/**
 * Sanitize user input for database queries
 * @param string $input The input to sanitize
 * @param mysqli $conn Database connection
 * @return string Sanitized input
 */
function sanitize_input($input, $conn) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    $input = $conn->real_escape_string($input);
    return $input;
}

/**
 * Validate email address
 * @param string $email Email address to validate
 * @return bool True if valid, false otherwise
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Indian format)
 * @param string $phone Phone number to validate
 * @return bool True if valid, false otherwise
 */
function validate_phone($phone) {
    // Remove spaces and special characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Check if it's 10 digits (Indian mobile) or 11 digits (with country code)
    return preg_match('/^[6-9]\d{9}$/', $phone) || preg_match('/^91[6-9]\d{9}$/', $phone);
}

/**
 * Validate URL
 * @param string $url URL to validate
 * @return bool True if valid, false otherwise
 */
function validate_url($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Generate CSRF token
 * @return string CSRF token
 */
function generate_csrf_token() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * @param string $token Token to verify
 * @return bool True if valid, false otherwise
 */
function verify_csrf_token($token) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitize filename for file uploads
 * @param string $filename Original filename
 * @return string Sanitized filename
 */
function sanitize_filename($filename) {
    // Remove any path information
    $filename = basename($filename);
    
    // Remove special characters
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
    
    // Limit length
    if (strlen($filename) > 255) {
        $filename = substr($filename, 0, 255);
    }
    
    return $filename;
}

/**
 * Validate file upload
 * @param array $file File array from $_FILES
 * @param array $allowed_types Allowed MIME types
 * @param int $max_size Maximum file size in bytes
 * @return array ['valid' => bool, 'error' => string]
 */
function validate_file_upload($file, $allowed_types = [], $max_size = 5242880) {
    $result = ['valid' => false, 'error' => ''];
    
    // Check if file was uploaded
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        $result['error'] = 'No file was uploaded';
        return $result;
    }
    
    // Check file size
    if ($file['size'] > $max_size) {
        $result['error'] = 'File size exceeds maximum allowed size';
        return $result;
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!empty($allowed_types) && !in_array($mime_type, $allowed_types)) {
        $result['error'] = 'File type not allowed';
        return $result;
    }
    
    $result['valid'] = true;
    return $result;
}

/**
 * Hash password securely
 * @param string $password Plain text password
 * @return string Hashed password
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verify password
 * @param string $password Plain text password
 * @param string $hash Hashed password
 * @return bool True if valid, false otherwise
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Generate random string
 * @param int $length Length of string
 * @return string Random string
 */
function generate_random_string($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Rate limit check
 * @param string $key Unique key for rate limiting (e.g., IP address)
 * @param int $max_attempts Maximum attempts allowed
 * @param int $time_window Time window in seconds
 * @return bool True if allowed, false if rate limited
 */
function check_rate_limit($key, $max_attempts = 5, $time_window = 300) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $rate_key = 'rate_limit_' . md5($key);
    
    if (!isset($_SESSION[$rate_key])) {
        $_SESSION[$rate_key] = ['count' => 0, 'start_time' => time()];
    }
    
    $rate_data = $_SESSION[$rate_key];
    
    // Reset if time window has passed
    if (time() - $rate_data['start_time'] > $time_window) {
        $_SESSION[$rate_key] = ['count' => 1, 'start_time' => time()];
        return true;
    }
    
    // Check if limit exceeded
    if ($rate_data['count'] >= $max_attempts) {
        return false;
    }
    
    // Increment counter
    $_SESSION[$rate_key]['count']++;
    return true;
}

/**
 * Sanitize HTML output
 * @param string $html HTML content to sanitize
 * @return string Sanitized HTML
 */
function sanitize_html($html) {
    // Remove potentially dangerous tags
    $html = strip_tags($html, '<p><br><strong><em><u><a><ul><ol><li><h1><h2><h3><h4><h5><h6>');
    
    // Sanitize attributes
    $html = preg_replace('/<a[^>]*href=["\']javascript:[^"\']*["\'][^>]*>/i', '', $html);
    $html = preg_replace('/on\w+=["\'][^"\']*["\']/i', '', $html);
    
    return $html;
}

/**
 * Log security event
 * @param string $event Event description
 * @param array $data Additional data
 */
function log_security_event($event, $data = []) {
    $log_file = '../weblog/security.log';
    $log_dir = dirname($log_file);
    
    // Create log directory if it doesn't exist
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $log_entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'event' => $event,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'data' => $data
    ];
    
    $log_line = json_encode($log_entry) . PHP_EOL;
    file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX);
}

/**
 * Check if user is admin (session-based)
 * @return bool True if admin, false otherwise
 */
function is_admin() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Require admin access (redirect if not admin)
 */
function require_admin() {
    if (!is_admin()) {
        log_security_event('Unauthorized admin access attempt');
        header('Location: /admin/login.php');
        exit;
    }
}

/**
 * Validate and sanitize pagination parameters
 * @param int $page Page number
 * @param int $items_per_page Items per page
 * @return array ['page' => int, 'offset' => int, 'limit' => int]
 */
function sanitize_pagination($page, $items_per_page = 10) {
    $page = max(1, intval($page));
    $items_per_page = max(1, min(100, intval($items_per_page))); // Max 100 items per page
    $offset = ($page - 1) * $items_per_page;
    
    return [
        'page' => $page,
        'offset' => $offset,
        'limit' => $items_per_page
    ];
}

?>
