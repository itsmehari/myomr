<?php
/**
 * Admin - Approve Property Handler
 */

require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();

require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $property_id = (int)$_GET['id'];
    
    if ($property_id > 0) {
        // Update status to active
        $stmt = $conn->prepare("UPDATE hostels_pgs SET status = 'active' WHERE id = ?");
        $stmt->bind_param("i", $property_id);
        $stmt->execute();
        
        // Get owner email for notification
        $infoQuery = "SELECT h.property_name, p.email 
                      FROM hostels_pgs h 
                      INNER JOIN property_owners p ON h.owner_id = p.id 
                      WHERE h.id = ?";
        $infoStmt = $conn->prepare($infoQuery);
        $infoStmt->bind_param("i", $property_id);
        $infoStmt->execute();
        $infoRes = $infoStmt->get_result();
        $info = $infoRes ? $infoRes->fetch_assoc() : null;
        
        // Send email notification to owner
        if ($info && !empty($info['email'])) {
            $subject = 'Your Property Listing Has Been Approved';
            $html = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">';
            $html .= '<h2 style="color: #14532d;">Property Approved!</h2>';
            $html .= '<p>Great news! Your property listing has been approved and is now live on MyOMR.</p>';
            $html .= '<div style="background: #e7f5e7; padding: 15px; border-radius: 5px; margin: 20px 0;">';
            $html .= '<h3 style="margin-top: 0;">' . htmlspecialchars($info['property_name']) . '</h3>';
            $html .= '</div>';
            $html .= '<p>You will now start receiving inquiries from potential tenants. Make sure to respond promptly!</p>';
            $html .= '<p>Best regards,<br>MyOMR Team</p>';
            $html .= '</div>';
            @myomrSendMail($info['email'], $subject, $html);
        }
    }
}

header('Location: manage-properties.php?success=1');
exit;

