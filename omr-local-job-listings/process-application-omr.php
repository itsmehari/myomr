<?php
/**
 * MyOMR Job Portal - Process Application
 * Backend handler for job application submission (incl. resume upload)
 *
 * @package MyOMR Job Portal
 * @version 1.1.0
 */

// Enable error reporting for development
require_once __DIR__ . '/includes/error-reporting.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include helper functions
require_once __DIR__ . '/includes/job-functions-omr.php';
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/../core/email.php';

// Initialize
$errors = [];
$success = false;
$resume_path = null;

// Get and validate job ID
$job_id = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0;

if ($job_id <= 0) {
    header('Location: index.php?error=invalid_job');
    exit;
}

// Sanitize input
$applicant_name = sanitizeInput($_POST['applicant_name'] ?? '');
$applicant_email = sanitizeInput($_POST['applicant_email'] ?? '');
$applicant_phone = sanitizeInput($_POST['applicant_phone'] ?? '');
$experience_years = isset($_POST['experience_years']) ? intval($_POST['experience_years']) : 0;
$cover_letter = sanitizeInput($_POST['cover_letter'] ?? '');

// Resume upload (optional) — PDF, DOC, DOCX, max 2 MB
$allowed_mimes = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
];
$allowed_ext = ['pdf', 'doc', 'docx'];
$max_size = 2 * 1024 * 1024; // 2 MB

if (!empty($_FILES['resume']['name']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
    $f = $_FILES['resume'];
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    $mime = '';
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $f['tmp_name']);
        finfo_close($finfo);
    }
    $mime_ok = $mime && in_array($mime, $allowed_mimes, true);
    $ext_ok = in_array($ext, $allowed_ext, true);

    if ($f['size'] > $max_size) {
        $errors[] = 'Resume must be 2 MB or smaller.';
    } elseif (!$ext_ok || ($mime && !$mime_ok)) {
        $errors[] = 'Resume must be PDF, DOC, or DOCX only.';
    } else {
        $upload_dir = __DIR__ . '/uploads/resumes';
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0750, true);
        }
        if (is_dir($upload_dir) && is_writable($upload_dir)) {
            $safe_name = sprintf('%s_%s.%s', date('Ymd-His'), bin2hex(random_bytes(4)), $ext);
            $target = $upload_dir . '/' . $safe_name;
            if (@move_uploaded_file($f['tmp_name'], $target)) {
                $resume_path = 'uploads/resumes/' . $safe_name;
            } else {
                $errors[] = 'Could not save resume. Please try again.';
            }
        } else {
            $errors[] = 'Resume upload is temporarily unavailable.';
        }
    }
}

// Persist submitted data for repopulation on validation failure
$_SESSION['application_form_data'] = [
    'applicant_name' => $applicant_name,
    'applicant_email' => $applicant_email,
    'applicant_phone' => $applicant_phone,
    'experience_years' => $experience_years,
    'cover_letter' => $cover_letter,
];

// Validation
if (empty($applicant_name)) {
    $errors[] = 'Name is required.';
}

if (empty($applicant_email)) {
    $errors[] = 'Email is required.';
} elseif (!validateEmail($applicant_email)) {
    $errors[] = 'Invalid email address.';
}

if (empty($applicant_phone)) {
    $errors[] = 'Phone number is required.';
} elseif (!validatePhone($applicant_phone)) {
    $errors[] = 'Invalid phone number format.';
}

// Check if job exists
$job_check = $conn->prepare("SELECT id, title, status FROM job_postings WHERE id = ?");
$job_check->bind_param("i", $job_id);
$job_check->execute();
$job_result = $job_check->get_result();
$job_data = $job_result->fetch_assoc();

if (!$job_data) {
    header('Location: index.php?error=job_not_found');
    exit;
}

// Check if job is still open
if ($job_data['status'] !== 'approved') {
    $errors[] = 'This job is no longer accepting applications.';
}

// Check for duplicate applications
if (hasUserApplied($job_id, $applicant_email)) {
    $errors[] = 'You have already applied for this position.';
}

// If no errors, process the application
if (empty($errors)) {
    try {
        // Insert application (applicant_resume optional — column must exist)
        $stmt = $conn->prepare("INSERT INTO job_applications (
                                    job_id,
                                    applicant_name,
                                    applicant_email,
                                    applicant_phone,
                                    experience_years,
                                    cover_letter,
                                    applicant_resume,
                                    status
                                ) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");

        $resume_val = $resume_path ?? '';
        $stmt->bind_param("ississs",
            $job_id,
            $applicant_name,
            $applicant_email,
            $applicant_phone,
            $experience_years,
            $cover_letter,
            $resume_val
        );
        
        $stmt->execute();
        
        // Update application count for the job
        $update_stmt = $conn->prepare("UPDATE job_postings SET applications_count = applications_count + 1 WHERE id = ?");
        $update_stmt->bind_param("i", $job_id);
        $update_stmt->execute();
        
        $success = true;
        
        // Set cookie to track this applicant
        setcookie('applicant_email', $applicant_email, time() + (86400 * 30), '/');

        // Clear any stored form data after successful submission
        unset($_SESSION['application_form_data']);

        // Fetch employer email for notification
        $empStmt = $conn->prepare("SELECT e.email, j.title
                                   FROM job_postings j INNER JOIN employers e ON j.employer_id = e.id
                                   WHERE j.id = ? LIMIT 1");
        $empStmt->bind_param("i", $job_id);
        $empStmt->execute();
        $empRes = $empStmt->get_result();
        $empRow = $empRes->fetch_assoc();

        // Send notifications (best-effort)
        if ($empRow && !empty($empRow['email'])) {
            $resumeNote = $resume_path
                ? '<p><strong>Resume:</strong> Attached — view in your <a href="' . htmlspecialchars('https://myomr.in/omr-local-job-listings/employer-dashboard-omr.php') . '">employer dashboard</a> to download.</p>'
                : '';
            $empBody = renderEmailTemplate(
                'New job application',
                '<h2>New application received</h2>' .
                '<p><strong>Job:</strong> ' . htmlspecialchars($empRow['title']) . '</p>' .
                '<p><strong>Applicant:</strong> ' . htmlspecialchars($applicant_name) . ' (' . htmlspecialchars($applicant_email) . ')</p>' .
                '<p><strong>Phone:</strong> ' . htmlspecialchars($applicant_phone) . '</p>' .
                (!empty($cover_letter) ? '<p><strong>Cover letter:</strong><br>' . nl2br(htmlspecialchars($cover_letter)) . '</p>' : '') .
                $resumeNote
            );
            @sendEmail($empRow['email'], 'New application - ' . $empRow['title'], $empBody);
        }

        $appBody = renderEmailTemplate(
            'Application received',
            '<h2>Thank you for applying</h2>' .
            '<p>We\'ve received your application for the role. The employer will contact you if shortlisted.</p>'
        );
        @sendEmail($applicant_email, 'Your application was received', $appBody);
        
    } catch (mysqli_sql_exception $e) {
        if ((int)$e->getCode() === 1062) { // duplicate key
            $errors[] = 'You have already applied for this position.';
        } else {
            $errors[] = 'Database error occurred. Please try again later.';
        }
        error_log('Application error: ' . $e->getMessage());
    }
}

// Redirect based on result
if ($success) {
    // Success - redirect to confirmation page
    header('Location: application-submitted-omr.php?job_id=' . urlencode($job_id) . '&applicant=' . urlencode($applicant_name));
    exit;
} else {
    // Error - redirect back to job detail with errors
    $_SESSION['application_errors'] = $errors;
    header('Location: job-detail-omr.php?id=' . $job_id . '&error=1');
    exit;
}
?>

