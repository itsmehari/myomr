<?php
/**
 * Space Owner Registration – MyOMR Coworking Spaces Portal
 * Creates or updates an owner profile and logs in the owner
 */

// Enable error reporting during development
require_once __DIR__ . '/includes/error-reporting.php';

// Start session for CSRF tokens
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/includes/owner-auth.php';

$error = '';
$success = '';

// Default redirect after successful registration
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'add-space.php?registered=1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Security validation failed. Please try again.';
    }
    
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($full_name) || empty($email)) {
        $error = 'Please provide your full name and email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please provide a valid email address.';
    } else {
        // Check if owner already exists by email
        $stmt = $conn->prepare("SELECT id FROM space_owners WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            // Update profile with provided details
            $stmt->close();
            $stmt = $conn->prepare("UPDATE space_owners SET full_name = ?, phone = ? WHERE email = ?");
            $stmt->bind_param("sss", $full_name, $phone, $email);
            $stmt->execute();
            $success = 'Profile updated successfully!';
        } else {
            // Create new owner in pending state
            $stmt->close();
            $password_hash = password_hash('temp_password', PASSWORD_DEFAULT);
            $status = 'pending';
            $stmt = $conn->prepare("INSERT INTO space_owners (full_name, email, phone, password_hash, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $full_name, $email, $phone, $password_hash, $status);
            $stmt->execute();
            $success = 'Account created successfully!';
        }

        // Log in the owner and redirect
        if (ownerLogin($email)) {
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
    <title>Register as Space Owner - MyOMR Coworking Spaces</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/coworking-spaces.css">
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
        <h1 class="hero-modern-title">Register as a Space Owner</h1>
        <p class="hero-modern-subtitle">Create your profile to list coworking spaces, manage inquiries</p>
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
                        
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <form method="POST" novalidate class="needs-validation">
                            <?php
                            if (empty($_SESSION['csrf_token'])) {
                                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                            }
                            ?>
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <div class="form-group-modern mb-4">
                                <label for="full_name" class="form-label-modern required-field">Full Name</label>
                                <input type="text" class="form-control-modern" id="full_name" name="full_name" required>
                            </div>

                            <div class="form-group-modern mb-4">
                                <label for="email" class="form-label-modern required-field">Email Address</label>
                                <input type="email" class="form-control-modern" id="email" name="email" required placeholder="you@email.com">
                                <div class="help-text-modern"><i class="fas fa-info-circle"></i> This email will be used to sign in and receive inquiries</div>
                            </div>

                            <div class="form-group-modern mb-4">
                                <label for="phone" class="form-label-modern">Phone Number</label>
                                <input type="text" class="form-control-modern" id="phone" name="phone" placeholder="Optional">
                            </div>

                            <button type="submit" class="btn-modern btn-modern-primary w-100">
                                <i class="fas fa-user-plus"></i>
                                <span>Create Owner Profile</span>
                            </button>
                        </form>

                        <hr class="my-4">
                        <p class="mb-0 text-center text-muted">
                            Already registered? <a href="owner-login.php" class="text-primary fw-semibold">Login here</a>
                            <br>
                            Or you can <a href="add-space.php" class="text-primary fw-semibold">add a workspace</a> directly and we'll create your profile.
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

