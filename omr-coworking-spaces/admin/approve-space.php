<?php
/**
 * Admin - Approve Space Handler
 */

require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();

require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $space_id = (int)$_GET['id'];
    
    if ($space_id > 0) {
        // Update status to active
        $stmt = $conn->prepare("UPDATE coworking_spaces SET status = 'active' WHERE id = ?");
        $stmt->bind_param("i", $space_id);
        $stmt->execute();
        
        // Get owner email for notification
        $infoQuery = "SELECT h.space_name, p.email 
                      FROM coworking_spaces h 
                      INNER JOIN space_owners p ON h.owner_id = p.id 
                      WHERE h.id = ?";
        $infoStmt = $conn->prepare($infoQuery);
        $infoStmt->bind_param("i", $space_id);
        $infoStmt->execute();
        $infoRes = $infoStmt->get_result();
        $info = $infoRes ? $infoRes->fetch_assoc() : null;
        
        // Send email notification to owner
        if ($info && !empty($info['email'])) {
            $subject = 'Your Coworking Space Listing Has Been Approved';
            $html = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">';
            $html .= '<h2 style="color: #14532d;">Space Approved!</h2>';
            $html .= '<p>Great news! Your coworking space listing has been approved and is now live on MyOMR.</p>';
            $html .= '<div style="background: #e7f5e7; padding: 15px; border-radius: 5px; margin: 20px 0;">';
            $html .= '<h3 style="margin-top: 0;">' . htmlspecialchars($info['space_name']) . '</h3>';
            $html .= '</div>';
            $html .= '<p>You will now start receiving inquiries from potential users. Make sure to respond promptly!</p>';
            $html .= '<p>Best regards,<br>MyOMR Team</p>';
            $html .= '</div>';
            @myomrSendMail($info['email'], $subject, $html);
        }
    }
}

header('Location: manage-spaces.php?success=1');
exit;

