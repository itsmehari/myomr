<?php
/**
 * Process Space Inquiry Submission
 * Handles booking inquiries from users to space owners
 */

// Enable error reporting
require_once __DIR__ . '/includes/error-reporting.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load database connection
require_once __DIR__ . '/../core/omr-connect.php';
global $conn;

// Include helper functions
require_once __DIR__ . '/includes/space-functions.php';
require_once __DIR__ . '/../core/mailer.php';

// Verify connection
if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
    $_SESSION['inquiry_error'] = 'Database connection failed. Please try again later.';
    header('Location: ' . $_SERVER['HTTP_REFERER'] ?? 'index.php');
    exit;
}

$errors = [];

// Validate and sanitize form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['inquiry_error'] = 'Security validation failed. Please try again.';
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
        exit;
    }
    
    $space_id = isset($_POST['space_id']) ? intval($_POST['space_id']) : 0;
    $user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $user_email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
    $user_phone = isset($_POST['user_phone']) ? trim($_POST['user_phone']) : '';
    $user_location = isset($_POST['user_location']) ? sanitizeInput($_POST['user_location']) : '';
    $interested_space_type = isset($_POST['interested_space_type']) ? sanitizeInput($_POST['interested_space_type']) : '';
    $start_date = isset($_POST['start_date']) ? sanitizeInput($_POST['start_date']) : null;
    $duration = isset($_POST['duration']) ? sanitizeInput($_POST['duration']) : '';
    $seats_required = isset($_POST['seats_required']) ? intval($_POST['seats_required']) : 1;
    $special_requirements = isset($_POST['special_requirements']) ? sanitizeInput($_POST['special_requirements']) : '';
    
    // Rate limiting: 5 inquiries/hour per email (using session)
    $rateLimitKey = 'inquiry_' . md5($user_email);
    $hourAgo = time() - 3600;
    if (!empty($_SESSION[$rateLimitKey])) {
        $inquiryHistory = $_SESSION[$rateLimitKey];
        $recentInquiries = array_filter($inquiryHistory, function($timestamp) use ($hourAgo) {
            return $timestamp > $hourAgo;
        });
        if (count($recentInquiries) >= 5) {
            $_SESSION['inquiry_error'] = 'Rate limit exceeded. Please try again in an hour.';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
            exit;
        }
    }
    
    // Validate space_id
    if ($space_id <= 0) {
        $errors[] = 'Invalid space selected.';
    } else {
        // Verify space exists and is active
        $spaceCheck = $conn->prepare("SELECT id FROM coworking_spaces WHERE id = ? AND status = 'active'");
        $spaceCheck->bind_param("i", $space_id);
        $spaceCheck->execute();
        if ($spaceCheck->get_result()->num_rows === 0) {
            $errors[] = 'Workspace not found or not available.';
        }
        $spaceCheck->close();
    }
    
    // Validate required fields
    if (empty($user_name)) {
        $errors[] = 'Please provide your full name.';
    } elseif (strlen($user_name) > 100) {
        $errors[] = 'Name is too long (max 100 characters).';
    }
    
    if (empty($user_email)) {
        $errors[] = 'Please provide your email address.';
    } elseif (!validateEmail($user_email)) {
        $errors[] = 'Please provide a valid email address.';
    }
    
    if (empty($user_phone)) {
        $errors[] = 'Please provide your phone number.';
    } elseif (!validatePhone($user_phone)) {
        $errors[] = 'Please provide a valid phone number (10 digits).';
    }
    
    // If no errors, save to database
    if (empty($errors)) {
        // Clean null date
        if (empty($start_date) || $start_date === '0000-00-00') {
            $start_date = null;
        }
        
        // Insert inquiry
        $stmt = $conn->prepare("INSERT INTO space_inquiries 
                                (space_id, user_name, user_email, user_phone, user_location, 
                                 interested_space_type, start_date, duration, seats_required, special_requirements, 
                                 status, created_at) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'new', NOW())");
        
        if ($stmt) {
            $stmt->bind_param("issssssiss", 
                $space_id, 
                $user_name, 
                $user_email, 
                $user_phone,
                $user_location,
                $interested_space_type, 
                $start_date, 
                $duration,
                $seats_required,
                $special_requirements
            );
            
            if ($stmt->execute()) {
                // Increment inquiry count
                $updateStmt = $conn->prepare("UPDATE coworking_spaces SET inquiries_count = inquiries_count + 1 WHERE id = ?");
                $updateStmt->bind_param("i", $space_id);
                $updateStmt->execute();
                $updateStmt->close();
                
                // Track in rate limiting
                if (!isset($_SESSION[$rateLimitKey])) {
                    $_SESSION[$rateLimitKey] = [];
                }
                $_SESSION[$rateLimitKey][] = time();
                $_SESSION[$rateLimitKey] = array_slice($_SESSION[$rateLimitKey], -10); // Keep last 10
                
                // Get space and owner details for email
                $spaceQuery = $conn->prepare("SELECT s.space_name, s.locality, s.hot_desk_monthly, s.contact_email, s.contact_phone FROM coworking_spaces s WHERE s.id = ?");
                $spaceQuery->bind_param("i", $space_id);
                $spaceQuery->execute();
                $spaceResult = $spaceQuery->get_result();
                $spaceDetails = $spaceResult->fetch_assoc();
                $spaceQuery->close();
                
                // Send email notification to owner
                if (!empty($spaceDetails) && !empty($spaceDetails['contact_email'])) {
                    $ownerEmail = $spaceDetails['contact_email'];
                    $subject = 'New Inquiry for Your Space: ' . htmlspecialchars($spaceDetails['space_name']);
                    $html = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">';
                    $html .= '<h2 style="color: #14532d;">New Workspace Inquiry</h2>';
                    $html .= '<p>You have received a new inquiry for your workspace:</p>';
                    $html .= '<div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 20px 0;">';
                    $html .= '<h3 style="margin-top: 0;">' . htmlspecialchars($spaceDetails['space_name']) . '</h3>';
                    $html .= '<p><strong>Location:</strong> ' . htmlspecialchars($spaceDetails['locality']) . '</p>';
                    if (!empty($spaceDetails['hot_desk_monthly'])) {
                        $html .= '<p><strong>Starting Price:</strong> ₹' . number_format($spaceDetails['hot_desk_monthly']) . '/month</p>';
                    }
                    $html .= '</div>';
                    $html .= '<h3>Inquiry Details:</h3>';
                    $html .= '<ul>';
                    $html .= '<li><strong>Name:</strong> ' . htmlspecialchars($user_name) . '</li>';
                    $html .= '<li><strong>Email:</strong> <a href="mailto:' . htmlspecialchars($user_email) . '">' . htmlspecialchars($user_email) . '</a></li>';
                    $html .= '<li><strong>Phone:</strong> <a href="tel:' . htmlspecialchars($user_phone) . '">' . htmlspecialchars($user_phone) . '</a></li>';
                    if (!empty($user_location)) {
                        $html .= '<li><strong>Current Location:</strong> ' . htmlspecialchars($user_location) . '</li>';
                    }
                    if (!empty($interested_space_type)) {
                        $html .= '<li><strong>Interested Space Type:</strong> ' . htmlspecialchars($interested_space_type) . '</li>';
                    }
                    if (!empty($start_date)) {
                        $html .= '<li><strong>Start Date:</strong> ' . date('M j, Y', strtotime($start_date)) . '</li>';
                    }
                    if (!empty($duration)) {
                        $html .= '<li><strong>Duration:</strong> ' . htmlspecialchars($duration) . '</li>';
                    }
                    if (!empty($seats_required)) {
                        $html .= '<li><strong>Seats Required:</strong> ' . $seats_required . '</li>';
                    }
                    if (!empty($special_requirements)) {
                        $html .= '<li><strong>Special Requirements:</strong> ' . nl2br(htmlspecialchars($special_requirements)) . '</li>';
                    }
                    $html .= '</ul>';
                    $html .= '<p style="margin-top: 20px;"><strong>Please contact the inquirer directly to discuss details and arrange a visit.</strong></p>';
                    $html .= '<p>Best regards,<br>MyOMR Team</p>';
                    $html .= '</div>';
                    @myomrSendMail($ownerEmail, $subject, $html);
                }
                
                // Send confirmation email to user
                if (!empty($user_email)) {
                    $userSubject = 'Your Inquiry Has Been Sent Successfully';
                    $userHtml = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">';
                    $userHtml .= '<h2 style="color: #14532d;">Thank You for Your Interest!</h2>';
                    $userHtml .= '<p>Hi ' . htmlspecialchars($user_name) . ',</p>';
                    $userHtml .= '<p>Your inquiry for <strong>' . htmlspecialchars($spaceDetails['space_name'] ?? 'this workspace') . '</strong> has been sent successfully to the space owner.</p>';
                    $userHtml .= '<div style="background: #e7f5e7; padding: 15px; border-left: 4px solid #22c55e; border-radius: 5px; margin: 20px 0;">';
                    $userHtml .= '<p style="margin: 0;"><strong>What happens next?</strong></p>';
                    $userHtml .= '<ul style="margin: 10px 0 0 0;">';
                    $userHtml .= '<li>The workspace owner will contact you within 24-48 hours</li>';
                    $userHtml .= '<li>They will discuss the workspace details and answer your questions</li>';
                    $userHtml .= '<li>You can arrange a time to visit the workspace in person</li>';
                    $userHtml .= '</ul>';
                    $userHtml .= '</div>';
                    $userHtml .= '<p>If you have any questions, feel free to contact us.</p>';
                    $userHtml .= '<p>Best regards,<br>MyOMR Team</p>';
                    $userHtml .= '</div>';
                    @myomrSendMail($user_email, $userSubject, $userHtml);
                }
                
                header('Location: inquiry-confirmation.php?id=' . $space_id);
                exit;
            } else {
                $errors[] = 'Failed to submit inquiry. Please try again.';
            }
            $stmt->close();
        } else {
            $errors[] = 'Database error. Please try again.';
        }
    }
    
    // If there are errors, redirect back with error messages
    if (!empty($errors)) {
        $_SESSION['inquiry_errors'] = $errors;
        $_SESSION['inquiry_form_data'] = $_POST;
        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? 'index.php');
        exit;
    }
} else {
    // Not a POST request
    header('Location: index.php');
    exit;
}

