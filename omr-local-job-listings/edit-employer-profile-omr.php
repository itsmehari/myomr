<?php
/**
 * Edit Employer Profile
 * Allows employers to update their profile information
 */
// Enable error reporting for development
require_once __DIR__ . '/includes/error-reporting.php';

require_once __DIR__ . '/includes/employer-auth.php';
requireEmployerAuth();

$employerId = (int)($_SESSION['employer_id'] ?? 0);

require_once __DIR__ . '/../core/omr-connect.php';

// Get current employer data
$stmt = $conn->prepare("SELECT * FROM employers WHERE id = ?");
$stmt->bind_param("i", $employerId);
$stmt->execute();
$result = $stmt->get_result();
$employer = $result->fetch_assoc();

if (!$employer) {
    header('Location: my-posted-jobs-omr.php');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = trim($_POST['company_name'] ?? '');
    $contact_person = trim($_POST['contact_person'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $company_description = trim($_POST['company_description'] ?? '');
    
    if (empty($company_name)) {
        $error = 'Company name is required.';
    } else {
        $updateStmt = $conn->prepare("UPDATE employers SET company_name = ?, contact_person = ?, phone = ?, address = ?, website = ?, company_description = ? WHERE id = ?");
        $updateStmt->bind_param("ssssssi", $company_name, $contact_person, $phone, $address, $website, $company_description, $employerId);
        
        if ($updateStmt->execute()) {
            // Update session
            $_SESSION['employer_company'] = $company_name;
            $success = 'Profile updated successfully!';
            // Refresh employer data
            $stmt = $conn->prepare("SELECT * FROM employers WHERE id = ?");
            $stmt->bind_param("i", $employerId);
            $stmt->execute();
            $result = $stmt->get_result();
            $employer = $result->fetch_assoc();
        } else {
            $error = 'Failed to update profile. Please try again.';
        }
        $updateStmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Employer Dashboard | MyOMR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/job-listings-omr.css">
    <link rel="stylesheet" href="assets/omr-jobs-unified-design.css">
    
    <!-- Google Analytics -->
    <?php $ga_user_id = (int)($_SESSION['employer_id'] ?? 0); $ga_user_properties = ['user_type' => 'employer']; include '../components/analytics.php'; ?>
</head>
<body class="modern-page">

<?php require_once __DIR__ . '/../components/main-nav.php'; ?>

<section class="hero-modern py-4">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 text-white">
            <div>
                <h1 class="hero-modern-title mb-2">Edit Profile</h1>
                <p class="hero-modern-subtitle mb-0">Update your company information and profile details</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-light" href="my-posted-jobs-omr.php"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a>
            </div>
        </div>
    </div>
</section>

<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card-modern">
                    <div class="p-4">
                        <form method="POST" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="company_name" class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" 
                                           value="<?php echo htmlspecialchars($employer['company_name'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="contact_person" class="form-label fw-semibold">Contact Person</label>
                                    <input type="text" class="form-control" id="contact_person" name="contact_person" 
                                           value="<?php echo htmlspecialchars($employer['contact_person'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Email Address</label>
                                    <input type="email" class="form-control" id="email" 
                                           value="<?php echo htmlspecialchars($employer['email'] ?? ''); ?>" disabled>
                                    <small class="text-muted">Email cannot be changed</small>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($employer['phone'] ?? ''); ?>" 
                                           placeholder="e.g., 9876543210">
                                </div>
                                
                                <div class="col-12">
                                    <label for="address" class="form-label fw-semibold">Company Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="2" 
                                              placeholder="Enter your company address"><?php echo htmlspecialchars($employer['address'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="website" class="form-label fw-semibold">Website</label>
                                    <input type="url" class="form-control" id="website" name="website" 
                                           value="<?php echo htmlspecialchars($employer['website'] ?? ''); ?>" 
                                           placeholder="https://example.com">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Account Status</label>
                                    <div>
                                        <?php
                                        $statusColor = 'secondary';
                                        if ($employer['status'] === 'verified') $statusColor = 'success';
                                        elseif ($employer['status'] === 'pending') $statusColor = 'warning';
                                        elseif ($employer['status'] === 'suspended') $statusColor = 'danger';
                                        ?>
                                        <span class="badge bg-<?php echo $statusColor; ?>"><?php echo ucfirst(htmlspecialchars($employer['status'] ?? 'pending')); ?></span>
                                        <small class="text-muted ms-2">Status is managed by administrators</small>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <label for="company_description" class="form-label fw-semibold">Company Description</label>
                                    <textarea class="form-control" id="company_description" name="company_description" rows="4" 
                                              placeholder="Tell us about your company..."><?php echo htmlspecialchars($employer['company_description'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Save Changes
                                        </button>
                                        <a href="my-posted-jobs-omr.php" class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

