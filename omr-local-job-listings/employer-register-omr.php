<?php
/**
 * Employer Registration – MyOMR Job Portal
 * Creates or updates an employer profile and logs in the employer
 */

// Enable error reporting during development (safe to keep; production can disable display_errors)
require_once __DIR__ . '/includes/error-reporting.php';

require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/includes/employer-auth.php';

$error = '';

// Default redirect after successful registration
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'my-posted-jobs-omr.php?registered=1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = trim($_POST['company_name'] ?? '');
    $contact_person = trim($_POST['contact_person'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($company_name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please provide a valid company name and email address.';
    } else {
        // Check if employer already exists by email
        $stmt = $conn->prepare("SELECT id FROM employers WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            // Update profile with provided details
            $stmt->close();
            $stmt = $conn->prepare("UPDATE employers SET company_name = ?, contact_person = ?, phone = ? WHERE email = ?");
            $stmt->bind_param("ssss", $company_name, $contact_person, $phone, $email);
            $stmt->execute();
        } else {
            // Create new employer in pending state
            $stmt->close();
            $status = 'pending';
            $stmt = $conn->prepare("INSERT INTO employers (company_name, contact_person, email, phone, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $company_name, $contact_person, $email, $phone, $status);
            $stmt->execute();
        }

        // Log in the employer and redirect
        if (employerLogin($email)) {
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = 'Registration succeeded but login failed. Please try logging in.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Employer - MyOMR Job Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/omr-jobs-unified-design.css">
    <?php include '../components/analytics.php'; ?>
</head>
<body class="modern-page">

<?php require_once __DIR__ . '/../components/main-nav.php'; ?>

<!-- Hero -->
<div class="hero-modern">
    <div class="text-center text-white">
        <div class="mb-3">
            <div class="form-section-icon" style="margin: 0 auto;">
                <i class="fas fa-building"></i>
            </div>
        </div>
        <h1 class="hero-modern-title">Register as an Employer</h1>
        <p class="hero-modern-subtitle">Create your employer profile to post jobs and manage applications</p>
    </div>
</div>

<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card-modern">
                    <div class="p-4">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="POST" novalidate class="needs-validation">
                            <div class="form-group-modern mb-4">
                                <label for="company_name" class="form-label-modern required-field">Company Name</label>
                                <input type="text" class="form-control-modern" id="company_name" name="company_name" required>
                            </div>

                            <div class="form-group-modern mb-4">
                                <label for="contact_person" class="form-label-modern">Contact Person</label>
                                <input type="text" class="form-control-modern" id="contact_person" name="contact_person">
                            </div>

                            <div class="form-group-modern mb-4">
                                <label for="email" class="form-label-modern required-field">Email Address</label>
                                <input type="email" class="form-control-modern" id="email" name="email" required placeholder="you@company.com">
                                <div class="help-text-modern"><i class="fas fa-info-circle"></i> This email will be used to sign in</div>
                            </div>

                            <div class="form-group-modern mb-4">
                                <label for="phone" class="form-label-modern">Phone</label>
                                <input type="text" class="form-control-modern" id="phone" name="phone" placeholder="Optional">
                            </div>

                            <button type="submit" class="btn-modern btn-modern-primary w-100">
                                <i class="fas fa-user-plus"></i>
                                <span>Create Employer Profile</span>
                            </button>
                        </form>

                        <hr class="my-4">
                        <p class="mb-0 text-center text-muted">
                            Already registered? <a href="employer-login-omr.php" class="text-primary fw-semibold">Login here</a>
                            <br>
                            Or you can <a href="post-job-omr.php" class="text-primary fw-semibold">post a job</a> directly and we’ll create your profile.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


