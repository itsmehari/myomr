<?php
/**
 * MyOMR Job Portal - Process Job Posting
 * Backend handler for job submission form
 * 
 * @package MyOMR Job Portal
 * @version 1.0.0
 */

// Enable error reporting for development
require_once __DIR__ . '/includes/error-reporting.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include helper functions
require_once __DIR__ . '/includes/job-functions-omr.php';
require_once __DIR__ . '/../core/omr-connect.php';

// Validate CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    die('Security validation failed. Please try again.');
}

// Initialize variables
$errors = [];
$success = false;
$is_update = isset($_POST['action']) && $_POST['action'] === 'update';
$job_id = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0;

// Sanitize and validate input
$company_name = sanitizeInput($_POST['company_name'] ?? '');
$contact_person = sanitizeInput($_POST['contact_person'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$phone = sanitizeInput($_POST['phone'] ?? '');
$address = sanitizeInput($_POST['address'] ?? '');
$website = sanitizeInput($_POST['website'] ?? '');
$title = sanitizeInput($_POST['title'] ?? '');
$category = sanitizeInput($_POST['category'] ?? '');
$job_type = sanitizeInput($_POST['job_type'] ?? '');
$location = sanitizeInput($_POST['location'] ?? '');
$salary_range = sanitizeInput($_POST['salary_range'] ?? 'Not Disclosed');
$description = sanitizeInput($_POST['description'] ?? '');
$requirements = sanitizeInput($_POST['requirements'] ?? '');
$benefits = sanitizeInput($_POST['benefits'] ?? '');
$application_deadline = sanitizeInput($_POST['application_deadline'] ?? null);

// Validation
if (empty($company_name)) {
    $errors[] = 'Company name is required.';
}

if (empty($contact_person)) {
    $errors[] = 'Contact person name is required.';
}

if (empty($email)) {
    $errors[] = 'Email address is required.';
} elseif (!validateEmail($email)) {
    $errors[] = 'Invalid email address.';
}

if (empty($phone)) {
    $errors[] = 'Phone number is required.';
} elseif (!validatePhone($phone)) {
    $errors[] = 'Invalid phone number format.';
}

if (empty($title)) {
    $errors[] = 'Job title is required.';
}

if (empty($category)) {
    $errors[] = 'Category is required.';
}

if (empty($job_type)) {
    $errors[] = 'Job type is required.';
}

if (empty($location)) {
    $errors[] = 'Location is required.';
}

if (empty($description)) {
    $errors[] = 'Job description is required.';
} elseif (strlen($description) < 50) {
    $errors[] = 'Job description must be at least 50 characters.';
}

// If no errors, process the submission
if (empty($errors)) {
    try {
        // Begin transaction
        $conn->begin_transaction();
        
        // Get employer ID from session
        $employer_id = (int)($_SESSION['employer_id'] ?? 0);
        
        // If updating, verify ownership
        if ($is_update && $job_id > 0) {
            $verifyStmt = $conn->prepare("SELECT employer_id FROM job_postings WHERE id = ?");
            $verifyStmt->bind_param("i", $job_id);
            $verifyStmt->execute();
            $verifyResult = $verifyStmt->get_result();
            $existingJob = $verifyResult->fetch_assoc();
            
            if (!$existingJob || $existingJob['employer_id'] != $employer_id) {
                $errors[] = 'You do not have permission to edit this job posting.';
                $conn->rollback();
            } else {
                // Update employer info
                $stmt = $conn->prepare("UPDATE employers SET 
                                        company_name = ?, 
                                        contact_person = ?, 
                                        phone = ?, 
                                        address = ?, 
                                        website = ?,
                                        updated_at = NOW()
                                        WHERE id = ?");
                $stmt->bind_param("sssssi", $company_name, $contact_person, $phone, $address, $website, $employer_id);
                $stmt->execute();
                
                // Update job posting
                $stmt = $conn->prepare("UPDATE job_postings SET
                                        title = ?, 
                                        category = ?, 
                                        job_type = ?, 
                                        location = ?, 
                                        salary_range = ?, 
                                        description = ?, 
                                        requirements = ?, 
                                        benefits = ?,
                                        application_deadline = ?,
                                        updated_at = NOW()
                                        WHERE id = ? AND employer_id = ?");
                
                $stmt->bind_param("ssssssssssii", 
                    $title, 
                    $category, 
                    $job_type, 
                    $location, 
                    $salary_range, 
                    $description, 
                    $requirements, 
                    $benefits,
                    $application_deadline,
                    $job_id,
                    $employer_id
                );
                
                $stmt->execute();
                
                // Commit transaction
                $conn->commit();
                
                $success = true;
            }
        } else {
            // New job posting
            // Check if employer exists
            $stmt = $conn->prepare("SELECT id FROM employers WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $employer = $result->fetch_assoc();
            
            if ($employer) {
                // Employer exists, update their info
                $employer_id = $employer['id'];
                $stmt = $conn->prepare("UPDATE employers SET 
                                        company_name = ?, 
                                        contact_person = ?, 
                                        phone = ?, 
                                        address = ?, 
                                        website = ?,
                                        updated_at = NOW()
                                        WHERE id = ?");
                $stmt->bind_param("sssssi", $company_name, $contact_person, $phone, $address, $website, $employer_id);
                $stmt->execute();
            } else {
                // New employer, insert into database
                $stmt = $conn->prepare("INSERT INTO employers (company_name, contact_person, email, phone, address, website, status) 
                                        VALUES (?, ?, ?, ?, ?, ?, 'pending')");
                $stmt->bind_param("ssssss", $company_name, $contact_person, $email, $phone, $address, $website);
                $stmt->execute();
                $employer_id = $conn->insert_id;
            }
            
            // Insert job posting
            $stmt = $conn->prepare("INSERT INTO job_postings (
                                        employer_id, 
                                        title, 
                                        category, 
                                        job_type, 
                                        location, 
                                        salary_range, 
                                        description, 
                                        requirements, 
                                        benefits,
                                        application_deadline,
                                        status
                                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            
            $stmt->bind_param("isssssssss", 
                $employer_id, 
                $title, 
                $category, 
                $job_type, 
                $location, 
                $salary_range, 
                $description, 
                $requirements, 
                $benefits,
                $application_deadline
            );
            
            $stmt->execute();
            $job_id = $conn->insert_id;
            
            // Commit transaction
            $conn->commit();
            
            $success = true;
        }
        
        // Clear CSRF token after successful submission
        if ($success) {
            unset($_SESSION['csrf_token']);
        }
        
    } catch (mysqli_sql_exception $e) {
        // Rollback on error
        $conn->rollback();
        $errors[] = 'Database error occurred. Please try again later.';
        error_log('Job posting error: ' . $e->getMessage());
    } catch (Exception $e) {
        $errors[] = 'An unexpected error occurred. Please try again later.';
        error_log('Unexpected error: ' . $e->getMessage());
    }
}

// Regenerate CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Redirect or display message
if ($success) {
    if ($is_update) {
        // Redirect to dashboard after update
        header('Location: my-posted-jobs-omr.php?updated=1');
    } else {
        // Success page for new posting
        header('Location: job-posted-success-omr.php?id=' . urlencode($job_id));
    }
    exit;
} else {
    // Error page with form data
    $_SESSION['job_form_errors'] = $errors;
    $_SESSION['job_form_data'] = $_POST;
    if ($is_update) {
        header('Location: edit-job-omr.php?id=' . urlencode($job_id) . '&error=1');
    } else {
        header('Location: post-job-omr.php?error=1');
    }
    exit;
}
?>

