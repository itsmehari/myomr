<?php
/**
 * Employer Authentication Helpers
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only require DB connection if not already loaded
if (!isset($conn) || !$conn) {
    require_once __DIR__ . '/../../core/omr-connect.php';
}

/**
 * Log in employer by email (creates or verifies employer)
 */
function employerLogin(string $email): bool {
    global $conn;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    $stmt = $conn->prepare("SELECT id, email, company_name, status FROM employers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $employer = $result->fetch_assoc();

    if (!$employer) {
        // Create a minimal employer record in pending state
        $pendingName = 'Employer';
        $empty = '';
        $stmt = $conn->prepare("INSERT INTO employers (company_name, contact_person, email, phone, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("ssss", $pendingName, $empty, $email, $empty);
        $stmt->execute();
        $employerId = $conn->insert_id;
        $employer = [ 'id' => $employerId, 'email' => $email, 'company_name' => $pendingName, 'status' => 'pending' ];
    }

    $_SESSION['employer_id'] = (int)$employer['id'];
    $_SESSION['employer_email'] = $employer['email'];
    $_SESSION['employer_company'] = $employer['company_name'] ?? 'Employer';
    $_SESSION['employer_status'] = $employer['status'] ?? 'pending';

    return true;
}

/**
 * Check if employer is logged in
 */
function isEmployerLoggedIn(): bool {
    return !empty($_SESSION['employer_id']);
}

/**
 * Require employer authentication (redirects to login)
 */
function requireEmployerAuth(): void {
    if (!isEmployerLoggedIn()) {
        header('Location: employer-login-omr.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

/**
 * Log out employer
 */
function employerLogout(): void {
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
