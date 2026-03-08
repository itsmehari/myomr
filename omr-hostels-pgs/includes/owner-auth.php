<?php
/**
 * Property Owner Authentication Helpers
 * For MyOMR Hostels & PGs Portal
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only require DB connection if not already loaded
if (!isset($conn) || !$conn) {
    require_once __DIR__ . '/../../core/omr-connect.php';
}

/**
 * Log in owner by email (creates or verifies owner)
 */
function ownerLogin(string $email): bool {
    global $conn;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    $stmt = $conn->prepare("SELECT id, email, full_name, status FROM property_owners WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $owner = $result->fetch_assoc();

    if (!$owner) {
        // Create a minimal owner record in pending state
        $pendingName = 'Owner';
        $empty = '';
        $tempPasswordHash = password_hash('temp_password_' . $email, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO property_owners (full_name, email, phone, password_hash, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("ssss", $pendingName, $email, $empty, $tempPasswordHash);
        $stmt->execute();
        $ownerId = $conn->insert_id;
        $owner = [ 'id' => $ownerId, 'email' => $email, 'full_name' => $pendingName, 'status' => 'pending' ];
    }

    $_SESSION['owner_id'] = (int)$owner['id'];
    $_SESSION['owner_email'] = $owner['email'];
    $_SESSION['owner_name'] = $owner['full_name'] ?? 'Owner';
    $_SESSION['owner_status'] = $owner['status'] ?? 'pending';

    return true;
}

/**
 * Check if owner is logged in
 */
function isOwnerLoggedIn(): bool {
    return !empty($_SESSION['owner_id']);
}

/**
 * Require owner authentication (redirects to login)
 */
function requireOwnerAuth(): void {
    if (!isOwnerLoggedIn()) {
        header('Location: owner-login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

/**
 * Log out owner
 */
function ownerLogout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

