<?php
/**
 * Process job seeker profile: résumé upload + upsert by email
 */
require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/job-functions-omr.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
}
require_once ROOT_PATH . '/core/omr-connect.php';
require_once ROOT_PATH . '/core/security-helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: candidate-profile-omr.php');
    exit;
}

// Honeypot: bots fill hidden field; pretend success
if (isset($_POST['company_website']) && trim((string)$_POST['company_website']) !== '') {
    header('Location: candidate-profile-omr.php?success=1');
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    $_SESSION['candidate_profile_errors'] = ['Your session expired. Please try again.'];
    header('Location: candidate-profile-omr.php');
    exit;
}

$errors = [];
$full_name = sanitizeInput($_POST['full_name'] ?? '');
$email = trim(strtolower((string)($_POST['email'] ?? '')));
$phone = sanitizeInput($_POST['phone'] ?? '');
$preferred_locality = sanitizeInput($_POST['preferred_locality'] ?? '');
$experience_level = sanitizeInput($_POST['experience_level'] ?? '');
$headline = sanitizeInput($_POST['headline'] ?? '');
$skills_summary = sanitizeInput($_POST['skills_summary'] ?? '');

$allowed_exp = ['', 'Fresher', 'Junior', 'Mid-level', 'Senior', 'Lead', 'Any'];
if ($experience_level !== '' && !in_array($experience_level, $allowed_exp, true)) {
    $experience_level = '';
}

if (empty($_POST['consent_privacy'])) {
    $errors[] = 'Please accept the Privacy Policy to create a profile.';
}

$consent_outreach = !empty($_POST['consent_outreach']);
$consent_contact = $consent_outreach ? 1 : 0;

if ($full_name === '') {
    $errors[] = 'Full name is required.';
}
if ($email === '' || !validateEmail($email)) {
    $errors[] = 'A valid email address is required.';
}
if ($phone === '' || !validatePhone($phone)) {
    $errors[] = 'A valid phone number is required.';
}
if (mb_strlen($headline) > 255) {
    $errors[] = 'Professional headline is too long.';
}

$now = time();
if (!empty($_SESSION['jp_profile_rate_lock']) && ($now - (int)$_SESSION['jp_profile_rate_lock']) < 45) {
    $errors[] = 'Please wait a moment before submitting again.';
}

$table_check = $conn->query("SHOW TABLES LIKE 'job_seeker_profiles'");
if (!$table_check || $table_check->num_rows === 0) {
    $errors[] = 'Profile signup is not available yet. Please try again later or browse jobs and apply directly.';
}

$existing = null;
if (!$errors) {
    $st = $conn->prepare('SELECT id, resume_path FROM job_seeker_profiles WHERE email = ? LIMIT 1');
    $st->bind_param('s', $email);
    $st->execute();
    $res = $st->get_result();
    $existing = $res ? $res->fetch_assoc() : null;
    $st->close();
}

if ($errors) {
    $_SESSION['candidate_profile_errors'] = $errors;
    $_SESSION['candidate_profile_old'] = [
        'full_name' => $full_name,
        'email' => $email,
        'phone' => $phone,
        'preferred_locality' => $preferred_locality,
        'experience_level' => $experience_level,
        'headline' => $headline,
        'skills_summary' => $skills_summary,
        'consent_outreach' => $consent_outreach,
    ];
    header('Location: candidate-profile-omr.php');
    exit;
}

$allowed_mimes = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
];
$allowed_ext = ['pdf', 'doc', 'docx'];
$max_size = 2 * 1024 * 1024;
$resume_path = null;

if (!empty($_FILES['resume']['name']) && (int)($_FILES['resume']['error'] ?? 0) === UPLOAD_ERR_OK) {
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
        $errors[] = 'Résumé must be 2 MB or smaller.';
    } elseif (!$ext_ok || ($mime && !$mime_ok)) {
        $errors[] = 'Résumé must be PDF, DOC, or DOCX only.';
    } else {
        $upload_dir = __DIR__ . '/uploads/resumes';
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0750, true);
        }
        if (is_dir($upload_dir) && is_writable($upload_dir)) {
            $safe_name = sprintf('candidate_%s_%s.%s', date('Ymd-His'), bin2hex(random_bytes(4)), $ext);
            $target = $upload_dir . '/' . $safe_name;
            if (@move_uploaded_file($f['tmp_name'], $target)) {
                $resume_path = 'uploads/resumes/' . $safe_name;
            } else {
                $errors[] = 'Could not save your résumé. Please try again.';
            }
        } else {
            $errors[] = 'Upload is temporarily unavailable.';
        }
    }
} elseif (!$existing) {
    $errors[] = 'Please upload your résumé (PDF, DOC, or DOCX).';
}

if ($errors) {
    $_SESSION['candidate_profile_errors'] = $errors;
    $_SESSION['candidate_profile_old'] = [
        'full_name' => $full_name,
        'email' => $email,
        'phone' => $phone,
        'preferred_locality' => $preferred_locality,
        'experience_level' => $experience_level,
        'headline' => $headline,
        'skills_summary' => $skills_summary,
        'consent_outreach' => $consent_outreach,
    ];
    header('Location: candidate-profile-omr.php');
    exit;
}

if (!$resume_path && $existing && !empty($existing['resume_path'])) {
    $resume_path = $existing['resume_path'];
}

if ($resume_path && $existing && !empty($existing['resume_path']) && $existing['resume_path'] !== $resume_path) {
    $old = $existing['resume_path'];
    if (strpos($old, 'uploads/resumes/') === 0) {
        $old_full = __DIR__ . '/' . $old;
        if (is_file($old_full)) {
            @unlink($old_full);
        }
    }
}

// Empty strings for optional VARCHAR/TEXT (PHP 8.0 mysqli bind_param compatibility)
$pref_loc = $preferred_locality !== '' ? $preferred_locality : '';
$exp_sql = $experience_level !== '' ? $experience_level : '';
$head_sql = $headline !== '' ? $headline : '';
$skills_sql = $skills_summary !== '' ? $skills_summary : '';

$has_consent_cols = false;
$colchk = $conn->query("SHOW COLUMNS FROM job_seeker_profiles LIKE 'consent_contact'");
if ($colchk && $colchk->num_rows > 0) {
    $has_consent_cols = true;
}

if ($existing) {
    if ($has_consent_cols) {
        $stmt = $conn->prepare('UPDATE job_seeker_profiles SET full_name = ?, phone = ?, preferred_locality = ?, experience_level = ?, headline = ?, skills_summary = ?, resume_path = ?, consent_contact = ? WHERE email = ?');
        $stmt->bind_param(
            str_repeat('s', 7) . 'is',
            $full_name,
            $phone,
            $pref_loc,
            $exp_sql,
            $head_sql,
            $skills_sql,
            $resume_path,
            $consent_contact,
            $email
        );
    } else {
        $stmt = $conn->prepare('UPDATE job_seeker_profiles SET full_name = ?, phone = ?, preferred_locality = ?, experience_level = ?, headline = ?, skills_summary = ?, resume_path = ? WHERE email = ?');
        $stmt->bind_param(
            'ssssssss',
            $full_name,
            $phone,
            $pref_loc,
            $exp_sql,
            $head_sql,
            $skills_sql,
            $resume_path,
            $email
        );
    }
} else {
    if ($has_consent_cols) {
        $stmt = $conn->prepare('INSERT INTO job_seeker_profiles (full_name, email, phone, preferred_locality, experience_level, headline, skills_summary, resume_path, consent_contact) VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->bind_param(
            str_repeat('s', 8) . 'i',
            $full_name,
            $email,
            $phone,
            $pref_loc,
            $exp_sql,
            $head_sql,
            $skills_sql,
            $resume_path,
            $consent_contact
        );
    } else {
        $stmt = $conn->prepare('INSERT INTO job_seeker_profiles (full_name, email, phone, preferred_locality, experience_level, headline, skills_summary, resume_path) VALUES (?,?,?,?,?,?,?,?)');
        $stmt->bind_param(
            'ssssssss',
            $full_name,
            $email,
            $phone,
            $pref_loc,
            $exp_sql,
            $head_sql,
            $skills_sql,
            $resume_path
        );
    }
}

if (!$stmt->execute()) {
    $_SESSION['candidate_profile_errors'] = ['Could not save your profile. Please try again.'];
    $_SESSION['candidate_profile_old'] = [
        'full_name' => $full_name,
        'email' => $email,
        'phone' => $phone,
        'preferred_locality' => $preferred_locality,
        'experience_level' => $experience_level,
        'headline' => $headline,
        'skills_summary' => $skills_summary,
        'consent_outreach' => $consent_outreach,
    ];
    $stmt->close();
    header('Location: candidate-profile-omr.php');
    exit;
}
$stmt->close();

if ($has_consent_cols && $consent_contact === 1) {
    $atcol = $conn->query("SHOW COLUMNS FROM job_seeker_profiles LIKE 'consent_at'");
    if ($atcol && $atcol->num_rows > 0) {
        $ts = $conn->prepare('UPDATE job_seeker_profiles SET consent_at = COALESCE(consent_at, NOW()) WHERE email = ?');
        if ($ts) {
            $ts->bind_param('s', $email);
            $ts->execute();
            $ts->close();
        }
    }
}

$_SESSION['jp_profile_rate_lock'] = $now;

$_SESSION['seeker_profile_prefill'] = [
    'applicant_name' => $full_name,
    'applicant_email' => $email,
    'applicant_phone' => $phone,
];

unset($_SESSION['candidate_profile_old'], $_SESSION['candidate_profile_errors']);
header('Location: candidate-profile-omr.php?success=1');
exit;
