<?php
/**
 * Admin - Verify/Suspend Owner Handler
 */

require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();

require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && isset($_GET['action'])) {
    $owner_id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($owner_id > 0) {
        if ($action === 'verify') {
            $stmt = $conn->prepare("UPDATE space_owners SET status = 'verified', verified_at = NOW() WHERE id = ?");
            $stmt->bind_param("i", $owner_id);
            $stmt->execute();
            
            // Send email notification
            $emailQuery = "SELECT email FROM space_owners WHERE id = ?";
            $emailStmt = $conn->prepare($emailQuery);
            $emailStmt->bind_param("i", $owner_id);
            $emailStmt->execute();
            $emailRes = $emailStmt->get_result();
            $emailData = $emailRes ? $emailRes->fetch_assoc() : null;
            
            if ($emailData && !empty($emailData['email'])) {
                $subject = 'Your Coworking Space Owner Account Has Been Verified';
                $html = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">';
                $html .= '<h2 style="color: #14532d;">Account Verified!</h2>';
                $html .= '<p>Congratulations! Your owner account has been verified by the MyOMR team.</p>';
                $html .= '<p>You can now add and manage your coworking space listings.</p>';
                $html .= '<p><a href="https://myomr.in/omr-coworking-spaces/owner-login.php" style="background: #008552; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Login to Dashboard</a></p>';
                $html .= '<p>Best regards,<br>MyOMR Team</p>';
                $html .= '</div>';
                @myomrSendMail($emailData['email'], $subject, $html);
            }
        } elseif ($action === 'suspend') {
            $stmt = $conn->prepare("UPDATE space_owners SET status = 'suspended' WHERE id = ?");
            $stmt->bind_param("i", $owner_id);
            $stmt->execute();
            
            // Send email notification
            $emailQuery = "SELECT email FROM space_owners WHERE id = ?";
            $emailStmt = $conn->prepare($emailQuery);
            $emailStmt->bind_param("i", $owner_id);
            $emailStmt->execute();
            $emailRes = $emailStmt->get_result();
            $emailData = $emailRes ? $emailRes->fetch_assoc() : null;
            
            if ($emailData && !empty($emailData['email'])) {
                $subject = 'Your Coworking Space Owner Account Has Been Suspended';
                $html = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">';
                $html .= '<h2 style="color: #dc2626;">Account Suspended</h2>';
                $html .= '<p>Your owner account has been suspended due to a policy violation.</p>';
                $html .= '<p>If you believe this is an error, please contact us at support@myomr.in</p>';
                $html .= '<p>Best regards,<br>MyOMR Team</p>';
                $html .= '</div>';
                @myomrSendMail($emailData['email'], $subject, $html);
            }
        }
    }
}

header('Location: manage-owners.php?success=1');
exit;

