<?php
/**
 * Admin - Verify Owner Handler
 */

require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();

require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/mailer.php';

// Handle status changes via GET with action parameter
if (isset($_GET['id']) && isset($_GET['action'])) {
    $owner_id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($owner_id > 0 && in_array($action, ['verify', 'suspend', 'pending'], true)) {
        $newStatus = $action === 'verify' ? 'verified' : ($action === 'suspend' ? 'suspended' : 'pending');
        $stmt = $conn->prepare("UPDATE property_owners SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $owner_id);
        $stmt->execute();

        // Get owner email for notification
        $infoStmt = $conn->prepare("SELECT full_name, email FROM property_owners WHERE id = ? LIMIT 1");
        $infoStmt->bind_param("i", $owner_id);
        if ($infoStmt->execute()) {
            $infoRes = $infoStmt->get_result();
            $info = $infoRes ? $infoRes->fetch_assoc() : null;
            
            // Send email notification
            if ($info && !empty($info['email'])) {
                $subject = 'Your Property Owner Account Status Updated';
                $html = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">';
                $html .= '<h2 style="color: #14532d;">Account Status Updated</h2>';
                $html .= '<p>Dear ' . htmlspecialchars($info['full_name']) . ',</p>';
                $html .= '<p>Your property owner account status on MyOMR has been updated to <strong>' . ucfirst($newStatus) . '</strong>.</p>';
                if ($newStatus === 'verified') {
                    $html .= '<p>You can now manage your properties and respond to inquiries with full access.</p>';
                }
                if ($newStatus === 'suspended') {
                    $html .= '<p>If you believe this is a mistake, please reply to this email for assistance.</p>';
                }
                $html .= '<p>Best regards,<br>MyOMR Team</p>';
                $html .= '</div>';
                @myomrSendMail($info['email'], $subject, $html);
            }
        }

        header('Location: manage-owners.php?success=1');
        exit;
    }
}

header('Location: manage-owners.php');
exit;
