<?php
/**
 * MyOMR Job Portal - Job Posted Success
 * Confirmation page after successful job posting
 * 
 * @package MyOMR Job Portal
 * @version 1.0.0
 */

require_once __DIR__ . '/includes/error-reporting.php';

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
}
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';
require_once ROOT_PATH . '/core/omr-connect.php';

$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($job_id > 0) {
    $stmt = $conn->prepare("SELECT j.*, e.company_name, e.contact_person, e.email 
                            FROM job_postings j 
                            JOIN employers e ON j.employer_id = e.id 
                            WHERE j.id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $job = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Posted Successfully - MyOMR Job Portal</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/job-listings-omr.css">
    <link rel="stylesheet" href="assets/omr-jobs-unified-design.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    
    <!-- Google Analytics -->
    <?php include '../components/analytics.php'; ?>
</head>
<body class="modern-page">

<?php omr_nav('main'); ?>

<!-- Hero Section -->
<div class="hero-modern">
    <div class="container">
        <div class="text-center text-white">
            <div class="mb-4">
                <div class="success-icon-modern">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <h1 class="hero-modern-title">Job Posted Successfully!</h1>
            <p class="hero-modern-subtitle">
                Your job posting has been submitted and is pending review.
            </p>
        </div>
    </div>
</div>

<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Job Details -->
                <?php if (isset($job)): ?>
                <div class="card-modern mb-4">
                    <div class="p-4">
                        <div class="form-section-header mb-4">
                            <div class="form-section-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <h3>Your Job Details</h3>
                        </div>
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="fw-semibold" style="width: 150px;">Job Title:</td>
                                <td><?php echo htmlspecialchars($job['title']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Company:</td>
                                <td><?php echo htmlspecialchars($job['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Location:</td>
                                <td><?php echo htmlspecialchars($job['location']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Job Type:</td>
                                <td><?php echo htmlspecialchars($job['job_type']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Category:</td>
                                <td><?php echo htmlspecialchars($job['category']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Status:</td>
                                <td><span class="badge-modern badge-modern-warning">Pending Review</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <div class="alert alert-info border-0 mb-4 small">
                    <strong>Tip for hiring:</strong> Tell candidates they can upload a CV and create a free <a href="candidate-profile-omr.php?utm_source=job_posted_success&utm_medium=employer_tip&utm_campaign=job_seeker_profile">MyOMR job seeker profile</a> so you receive stronger applications.
                </div>
                
                <!-- Next Steps -->
                <div class="card-modern mb-4">
                    <div class="p-4">
                        <div class="form-section-header mb-4">
                            <div class="form-section-icon">
                                <i class="fas fa-list-check"></i>
                            </div>
                            <h3>What Happens Next?</h3>
                        </div>
                        <ol class="mb-0">
                            <li class="mb-2"><strong>Review Process:</strong> Our team will review your job posting within 24-48 hours.</li>
                            <li class="mb-2"><strong>Email Notification:</strong> You'll receive an email at <strong><?php echo htmlspecialchars($job['email'] ?? 'your email'); ?></strong> once your job is approved.</li>
                            <li class="mb-2"><strong>Job Goes Live:</strong> Once approved, your job will appear on our job listings page.</li>
                            <li><strong>Receive Applications:</strong> You'll start receiving applications from qualified candidates.</li>
                        </ol>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="text-center d-flex gap-3 flex-wrap justify-content-center">
                    <a href="index.php" class="btn-modern btn-modern-primary">
                        <i class="fas fa-search"></i>
                        <span>Browse All Jobs</span>
                    </a>
                    <a href="post-job-omr.php" class="btn-modern btn-modern-secondary">
                        <i class="fas fa-plus"></i>
                        <span>Post Another Job</span>
                    </a>
                </div>
                
            </div>
        </div>
    </div>
</main>

<?php omr_footer(); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function() {
  if (typeof gtag !== 'function') return;
  gtag('event', 'job_posted', {
    'job_id':       <?= (int)$job_id ?>,
    'job_title':    <?= json_encode(isset($job) ? $job['title'] : '') ?>,
    'job_type':     <?= json_encode(isset($job) ? $job['job_type'] : '') ?>,
    'job_category': <?= json_encode(isset($job) ? $job['category'] : '') ?>,
    'location':     <?= json_encode(isset($job) ? $job['location'] : '') ?>
  });
})();
</script>

</body>
</html>

