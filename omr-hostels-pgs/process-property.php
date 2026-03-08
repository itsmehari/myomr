<?php
/**
 * Process Property Add/Edit Submission
 */

require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/owner-auth.php';
requireOwnerAuth();

require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/includes/property-functions.php';

global $conn;

$owner_id = (int)($_SESSION['owner_id'] ?? 0);

if ($owner_id <= 0) {
    header('Location: owner-login.php');
    exit;
}

$errors = [];
$property_id = isset($_POST['property_id']) ? intval($_POST['property_id']) : 0;
$is_edit = $property_id > 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['property_errors'] = ['Security validation failed. Please try again.'];
        $_SESSION['property_form_data'] = $_POST;
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'add-property.php'));
        exit;
    }
    // Basic Information
    $property_name = isset($_POST['property_name']) ? sanitizeInput($_POST['property_name']) : '';
    $property_type = isset($_POST['property_type']) ? sanitizeInput($_POST['property_type']) : '';
    $address = isset($_POST['address']) ? sanitizeInput($_POST['address']) : '';
    $locality = isset($_POST['locality']) ? sanitizeInput($_POST['locality']) : '';
    $landmark = isset($_POST['landmark']) ? sanitizeInput($_POST['landmark']) : '';
    $pincode = isset($_POST['pincode']) ? sanitizeInput($_POST['pincode']) : '';
    $nearby_metro = isset($_POST['nearby_metro']) ? sanitizeInput($_POST['nearby_metro']) : '';
    $nearby_bus_stand = isset($_POST['nearby_bus_stand']) ? sanitizeInput($_POST['nearby_bus_stand']) : '';
    
    // Description
    $brief_overview = isset($_POST['brief_overview']) ? sanitizeInput($_POST['brief_overview']) : '';
    $full_description = isset($_POST['full_description']) ? sanitizeInput($_POST['full_description']) : '';
    $house_rules = isset($_POST['house_rules']) ? sanitizeInput($_POST['house_rules']) : '';
    
    // Details
    $total_beds = isset($_POST['total_beds']) ? intval($_POST['total_beds']) : null;
    $gender_preference = isset($_POST['gender_preference']) ? sanitizeInput($_POST['gender_preference']) : '';
    $monthly_rent_single = isset($_POST['monthly_rent_single']) ? floatval($_POST['monthly_rent_single']) : null;
    $monthly_rent_double = isset($_POST['monthly_rent_double']) ? floatval($_POST['monthly_rent_double']) : null;
    $monthly_rent_triple = isset($_POST['monthly_rent_triple']) ? floatval($_POST['monthly_rent_triple']) : null;
    $security_deposit = isset($_POST['security_deposit']) ? floatval($_POST['security_deposit']) : null;
    $food_options = isset($_POST['food_options']) ? sanitizeInput($_POST['food_options']) : '';
    $facilities_input = isset($_POST['facilities']) ? trim($_POST['facilities']) : '';
    $is_student_friendly = isset($_POST['is_student_friendly']) ? 1 : 0;
    
    // Contact Information
    $contact_person = isset($_POST['contact_person']) ? sanitizeInput($_POST['contact_person']) : '';
    $contact_email = isset($_POST['contact_email']) ? sanitizeInput($_POST['contact_email']) : '';
    $contact_phone = isset($_POST['contact_phone']) ? sanitizeInput($_POST['contact_phone']) : '';
    $contact_whatsapp = isset($_POST['contact_whatsapp']) ? sanitizeInput($_POST['contact_whatsapp']) : '';
    
    // Validate required fields
    if (empty($property_name)) {
        $errors[] = 'Property name is required.';
    }
    if (empty($property_type)) {
        $errors[] = 'Property type is required.';
    }
    if (empty($address)) {
        $errors[] = 'Address is required.';
    }
    if (empty($locality)) {
        $errors[] = 'Locality is required.';
    }
    if (empty($gender_preference)) {
        $errors[] = 'Gender preference is required.';
    }
    if (empty($brief_overview)) {
        $errors[] = 'Brief overview is required.';
    }
    if (empty($full_description)) {
        $errors[] = 'Full description is required.';
    }
    
    // Process facilities into JSON
    $facilities = [];
    if (!empty($facilities_input)) {
        $facilities = array_map('trim', explode(',', $facilities_input));
        $facilities = array_filter($facilities);
    }
    $facilities_json = json_encode($facilities);
    
    // Generate slug
    $slug = createSlug($property_name . ' ' . $locality);
    
    // Check for duplicate slug
    $slugCheck = $conn->prepare("SELECT id FROM hostels_pgs WHERE slug = ? AND id != ?");
    $slugCheck->bind_param("si", $slug, $property_id);
    $slugCheck->execute();
    if ($slugCheck->get_result()->num_rows > 0) {
        $slug = $slug . '-' . time();
    }
    $slugCheck->close();
    
    // If no errors, save to database
    if (empty($errors)) {
        if ($is_edit) {
            // Update existing property
            $stmt = $conn->prepare("UPDATE hostels_pgs SET 
                property_name = ?, slug = ?, property_type = ?, address = ?, locality = ?, 
                landmark = ?, pincode = ?, nearby_metro = ?, nearby_bus_stand = ?,
                brief_overview = ?, full_description = ?, house_rules = ?, total_beds = ?,
                gender_preference = ?, monthly_rent_single = ?, monthly_rent_double = ?,
                monthly_rent_triple = ?, security_deposit = ?, facilities = ?, food_options = ?,
                is_student_friendly = ?, contact_person = ?, contact_email = ?, contact_phone = ?, contact_whatsapp = ?,
                updated_at = NOW()
                WHERE id = ? AND owner_id = ?");
            
            if ($stmt) {
                $stmt->bind_param("ssssssssssssissssssssssiii", 
                    $property_name, $slug, $property_type, $address, $locality,
                    $landmark, $pincode, $nearby_metro, $nearby_bus_stand,
                    $brief_overview, $full_description, $house_rules, $total_beds,
                    $gender_preference, $monthly_rent_single, $monthly_rent_double,
                    $monthly_rent_triple, $security_deposit, $facilities_json, $food_options,
                    $is_student_friendly, $contact_person, $contact_email, $contact_phone, $contact_whatsapp,
                    $property_id, $owner_id
                );
                
                if ($stmt->execute()) {
                    header('Location: property-added-success.php?id=' . $property_id);
                    exit;
                } else {
                    $errors[] = 'Failed to update property. Please try again.';
                }
                $stmt->close();
            } else {
                $errors[] = 'Database error. Please try again.';
            }
        } else {
            // Insert new property
            $stmt = $conn->prepare("INSERT INTO hostels_pgs (
                owner_id, property_name, slug, property_type, address, locality,
                landmark, pincode, nearby_metro, nearby_bus_stand, brief_overview,
                full_description, house_rules, total_beds, gender_preference,
                monthly_rent_single, monthly_rent_double, monthly_rent_triple,
                security_deposit, facilities, food_options, is_student_friendly,
                contact_person, contact_email, contact_phone, contact_whatsapp,
                status, verification_status, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', NOW())");
            
            if ($stmt) {
                $stmt->bind_param("issssssssssssissssssssssii", 
                    $owner_id, $property_name, $slug, $property_type, $address, $locality,
                    $landmark, $pincode, $nearby_metro, $nearby_bus_stand,
                    $brief_overview, $full_description, $house_rules, $total_beds,
                    $gender_preference, $monthly_rent_single, $monthly_rent_double,
                    $monthly_rent_triple, $security_deposit, $facilities_json,
                    $food_options, $is_student_friendly, $contact_person, $contact_email, $contact_phone, $contact_whatsapp
                );
                
                if ($stmt->execute()) {
                    $property_id = $conn->insert_id;
                    header('Location: property-added-success.php?id=' . $property_id);
                    exit;
                } else {
                    $errors[] = 'Failed to save property. Please try again.';
                }
                $stmt->close();
            } else {
                $errors[] = 'Database error. Please try again.';
            }
        }
    }
    
    // If there are errors, redirect back
    if (!empty($errors)) {
        $_SESSION['property_errors'] = $errors;
        $_SESSION['property_form_data'] = $_POST;
        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? 'add-property.php');
        exit;
    }
} else {
    header('Location: add-property.php');
    exit;
}

