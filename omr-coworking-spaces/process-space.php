<?php
/**
 * Process Space Add/Edit Submission
 */

require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/owner-auth.php';
requireOwnerAuth();

require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/includes/space-functions.php';

global $conn;

$owner_id = (int)($_SESSION['owner_id'] ?? 0);

if ($owner_id <= 0) {
    header('Location: owner-login.php');
    exit;
}

$errors = [];
$space_id = isset($_POST['space_id']) ? intval($_POST['space_id']) : 0;
$is_edit = $space_id > 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['space_errors'] = ['Security validation failed. Please try again.'];
        $_SESSION['space_form_data'] = $_POST;
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'add-space.php'));
        exit;
    }
    // Basic Information
    $space_name = isset($_POST['space_name']) ? sanitizeInput($_POST['space_name']) : '';
    $address = isset($_POST['address']) ? sanitizeInput($_POST['address']) : '';
    $locality = isset($_POST['locality']) ? sanitizeInput($_POST['locality']) : '';
    $landmark = isset($_POST['landmark']) ? sanitizeInput($_POST['landmark']) : '';
    $pincode = isset($_POST['pincode']) ? sanitizeInput($_POST['pincode']) : '';
    $total_capacity = isset($_POST['total_capacity']) ? intval($_POST['total_capacity']) : null;
    $operating_hours = isset($_POST['operating_hours']) ? sanitizeInput($_POST['operating_hours']) : '';
    
    // Description
    $brief_overview = isset($_POST['brief_overview']) ? sanitizeInput($_POST['brief_overview']) : '';
    $full_description = isset($_POST['full_description']) ? sanitizeInput($_POST['full_description']) : '';
    
    // Pricing
    $day_pass_price = isset($_POST['day_pass_price']) ? floatval($_POST['day_pass_price']) : null;
    $hot_desk_monthly = isset($_POST['hot_desk_monthly']) ? floatval($_POST['hot_desk_monthly']) : null;
    $dedicated_desk_monthly = isset($_POST['dedicated_desk_monthly']) ? floatval($_POST['dedicated_desk_monthly']) : null;
    $private_cabin_monthly = isset($_POST['private_cabin_monthly']) ? floatval($_POST['private_cabin_monthly']) : null;
    $team_space_monthly = isset($_POST['team_space_monthly']) ? floatval($_POST['team_space_monthly']) : null;
    $special_offers = isset($_POST['special_offers']) ? sanitizeInput($_POST['special_offers']) : '';
    
    // Amenities
    $amenities_input = isset($_POST['amenities']) ? trim($_POST['amenities']) : '';
    
    // Contact Information
    $contact_person = isset($_POST['contact_person']) ? sanitizeInput($_POST['contact_person']) : '';
    $contact_email = isset($_POST['contact_email']) ? sanitizeInput($_POST['contact_email']) : '';
    $contact_phone = isset($_POST['contact_phone']) ? sanitizeInput($_POST['contact_phone']) : '';
    $contact_whatsapp = isset($_POST['contact_whatsapp']) ? sanitizeInput($_POST['contact_whatsapp']) : '';
    
    // Validate required fields
    if (empty($space_name)) {
        $errors[] = 'Workspace name is required.';
    }
    if (empty($address)) {
        $errors[] = 'Address is required.';
    }
    if (empty($locality)) {
        $errors[] = 'Locality is required.';
    }
    if (empty($brief_overview)) {
        $errors[] = 'Brief overview is required.';
    }
    if (empty($full_description)) {
        $errors[] = 'Full description is required.';
    }
    
    // Process amenities into JSON
    $amenities = [];
    if (!empty($amenities_input)) {
        $amenities = array_map('trim', explode(',', $amenities_input));
        $amenities = array_filter($amenities);
    }
    $amenities_json = json_encode($amenities);
    
    // Generate slug
    $slug = createSlug($space_name . ' ' . $locality);
    
    // Check for duplicate slug
    $slugCheck = $conn->prepare("SELECT id FROM coworking_spaces WHERE slug = ? AND id != ?");
    $slugCheck->bind_param("si", $slug, $space_id);
    $slugCheck->execute();
    if ($slugCheck->get_result()->num_rows > 0) {
        $slug = $slug . '-' . time();
    }
    $slugCheck->close();
    
    // If no errors, save to database
    if (empty($errors)) {
        if ($is_edit) {
            // Update existing space
            $stmt = $conn->prepare("UPDATE coworking_spaces SET 
                space_name = ?, slug = ?, address = ?, locality = ?, 
                landmark = ?, pincode = ?, total_capacity = ?, operating_hours = ?,
                brief_overview = ?, full_description = ?, 
                day_pass_price = ?, hot_desk_monthly = ?, dedicated_desk_monthly = ?,
                private_cabin_monthly = ?, team_space_monthly = ?, 
                special_offers = ?, amenities = ?, contact_person = ?, contact_email = ?, contact_phone = ?, contact_whatsapp = ?,
                updated_at = NOW()
                WHERE id = ? AND owner_id = ?");
            
            if ($stmt) {
                $stmt->bind_param("sssssssssssdddddssssssii", 
                    $space_name, $slug, $address, $locality,
                    $landmark, $pincode, $total_capacity, $operating_hours,
                    $brief_overview, $full_description,
                    $day_pass_price, $hot_desk_monthly, $dedicated_desk_monthly,
                    $private_cabin_monthly, $team_space_monthly,
                    $special_offers, $amenities_json, $contact_person, $contact_email, $contact_phone, $contact_whatsapp,
                    $space_id, $owner_id
                );
                
                if ($stmt->execute()) {
                    header('Location: space-added-success.php?id=' . $space_id);
                    exit;
                } else {
                    $errors[] = 'Failed to update workspace. Please try again.';
                }
                $stmt->close();
            } else {
                $errors[] = 'Database error. Please try again.';
            }
        } else {
            // Insert new space
            $stmt = $conn->prepare("INSERT INTO coworking_spaces (
                owner_id, space_name, slug, address, locality,
                landmark, pincode, total_capacity, operating_hours,
                brief_overview, full_description,
                day_pass_price, hot_desk_monthly, dedicated_desk_monthly,
                private_cabin_monthly, team_space_monthly,
                special_offers, amenities, contact_person, contact_email, contact_phone, contact_whatsapp,
                status, verification_status, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', NOW())");
            
            if ($stmt) {
                $stmt->bind_param("issssssisssddddsssssss", 
                    $owner_id, $space_name, $slug, $address, $locality,
                    $landmark, $pincode, $total_capacity, $operating_hours,
                    $brief_overview, $full_description,
                    $day_pass_price, $hot_desk_monthly, $dedicated_desk_monthly,
                    $private_cabin_monthly, $team_space_monthly,
                    $special_offers, $amenities_json, $contact_person, $contact_email, $contact_phone, $contact_whatsapp
                );
                
                if ($stmt->execute()) {
                    $space_id = $conn->insert_id;
                    header('Location: space-added-success.php?id=' . $space_id);
                    exit;
                } else {
                    $errors[] = 'Failed to save workspace. Please try again.';
                }
                $stmt->close();
            } else {
                $errors[] = 'Database error. Please try again.';
            }
        }
    }
    
    // If there are errors, redirect back
    if (!empty($errors)) {
        $_SESSION['space_errors'] = $errors;
        $_SESSION['space_form_data'] = $_POST;
        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? 'add-space.php');
        exit;
    }
} else {
    header('Location: add-space.php');
    exit;
}

