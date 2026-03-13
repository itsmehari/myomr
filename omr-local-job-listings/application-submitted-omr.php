<?php
/**
 * MyOMR Job Portal - Application Submitted Confirmation
 * Success page after job application submission
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
require_once __DIR__ . '/includes/job-functions-omr.php';

$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;
$applicant_name = isset($_GET['applicant']) ? htmlspecialchars($_GET['applicant']) : '';

if ($job_id > 0) {
    $stmt = $conn->prepare("SELECT title, company_name FROM job_postings j JOIN employers e ON j.employer_id = e.id WHERE j.id = ?");
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
    <title>Application Submitted Successfully - MyOMR Job Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/job-listings-omr.css">
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
            <h1 class="hero-modern-title">Application Submitted Successfully!</h1>
            <p class="hero-modern-subtitle">
                Thank you, <?php echo htmlspecialchars($applicant_name); ?>. Your application has been received.
            </p>
        </div>
    </div>
</div>

<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Application Details -->
                <?php if (isset($job)): ?>
                <div class="card-modern mb-4">
                    <div class="p-4">
                        <div class="form-section-header mb-4">
                            <div class="form-section-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <h3>Application Details</h3>
                        </div>
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="fw-semibold" style="width: 150px;">Position:</td>
                                <td><?php echo htmlspecialchars($job['title']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Company:</td>
                                <td><?php echo htmlspecialchars($job['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Status:</td>
                                <td><span class="badge-modern badge-modern-primary">Received & Pending Review</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Applied By:</td>
                                <td><?php echo $applicant_name; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
                
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
                            <li class="mb-2"><strong>Review:</strong> The employer will review your application along with other candidates.</li>
                            <li class="mb-2"><strong>Shortlisting:</strong> If shortlisted, the employer will contact you directly via email or phone.</li>
                            <li class="mb-2"><strong>Interview:</strong> The employer may schedule an interview with you.</li>
                            <li><strong>Follow-up:</strong> Don't hesitate to follow up if you don't hear back within 2 weeks.</li>
                        </ol>
                    </div>
                </div>
                
                <!-- Tips -->
                <div class="alert-modern mb-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="form-section-icon" style="flex-shrink: 0;">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div>
                            <h5 class="mb-3">Pro Tips</h5>
                            <ul class="mb-0">
                                <li>Keep your phone and email accessible for employer contact</li>
                                <li>Prepare for potential interviews</li>
                                <li>Continue applying to other jobs to increase your opportunities</li>
                                <li>Stay professional in all communications</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="text-center d-flex gap-3 flex-wrap justify-content-center">
                    <a href="index.php" class="btn-modern btn-modern-primary">
                        <i class="fas fa-search"></i>
                        <span>Browse More Jobs</span>
                    </a>
                    <?php if ($job_id > 0): ?>
                    <a href="<?php echo getJobDetailPath($job_id, $job['title'] ?? null); ?>" class="btn-modern btn-modern-secondary">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Job</span>
                    </a>
                    <?php endif; ?>
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
  gtag('event', 'job_applied', {
    'job_id':    <?= (int)$job_id ?>,
    'job_title': <?= json_encode(isset($job) ? $job['title'] : '') ?>,
    'company':   <?= json_encode(isset($job) ? $job['company_name'] : '') ?>,
    'applicant': <?= json_encode($applicant_name) ?>
  });
})();
</script>

</body>
</html>

