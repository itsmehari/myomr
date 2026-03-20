<?php
/**
 * OMR Classified Ads — session auth (email/password MVP).
 */
if (!function_exists('ca_session_start')) {
    function ca_session_start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}

if (!function_exists('ca_user_id')) {
    function ca_user_id(): ?int {
        ca_session_start();
        $id = $_SESSION['ca_user_id'] ?? null;
        return $id !== null ? (int) $id : null;
    }
}

if (!function_exists('ca_require_login')) {
    function ca_require_login(string $redirect = '/omr-classified-ads/login-omr.php'): void {
        if (ca_user_id() === null) {
            $_SESSION['ca_login_redirect'] = $_SERVER['REQUEST_URI'] ?? '/omr-classified-ads/';
            header('Location: ' . $redirect);
            exit;
        }
    }
}

if (!function_exists('ca_require_verified_email')) {
    function ca_require_verified_email(): void {
        ca_require_login();
        global $conn;
        $uid = ca_user_id();
        if ($uid === null) {
            return;
        }
        if (!$conn instanceof mysqli || $conn->connect_error) {
            return;
        }
        $stmt = $conn->prepare('SELECT email_verified FROM omr_classified_ads_users WHERE id = ? LIMIT 1');
        if (!$stmt) {
            return;
        }
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (!$row || empty($row['email_verified'])) {
            header('Location: /omr-classified-ads/login-omr.php?need_verify=1');
            exit;
        }
    }
}

if (!function_exists('ca_current_user')) {
    function ca_current_user(): ?array {
        global $conn;
        $uid = ca_user_id();
        if ($uid === null || !$conn instanceof mysqli || $conn->connect_error) {
            return null;
        }
        $stmt = $conn->prepare(
            'SELECT id, email, display_name, email_verified, google_sub, phone_e164, phone_verified FROM omr_classified_ads_users WHERE id = ? LIMIT 1'
        );
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }
}

if (!function_exists('ca_logout')) {
    function ca_logout(): void {
        ca_session_start();
        unset($_SESSION['ca_user_id'], $_SESSION['ca_user_email'], $_SESSION['ca_user_name']);
    }
}
