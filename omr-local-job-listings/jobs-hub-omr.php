<?php
/**
 * Jobs hub — Common entry page for job seekers and employers
 * When users click "Jobs" from homepage or main navigation they land here.
 * Educates first-time users on what MyOMR offers and how to use the job feature.
 */
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
}
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$page_title = 'Jobs in OMR Chennai — Find local jobs or hire locally | MyOMR';
$page_description = 'OMR\'s local job board: find IT, teaching, hospitality & office jobs in Perungudi, Sholinganallur, Navalur. Upload your résumé, create a job seeker profile, or post jobs free. Apply direct to employers.';
$canonical_url = 'https://myomr.in/omr-local-job-listings/jobs-hub-omr.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/job-listings-omr.css">
    <link rel="stylesheet" href="assets/omr-jobs-unified-design.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <?php include ROOT_PATH . '/components/analytics.php'; ?>
</head>
<body class="modern-page">

<?php omr_nav('main'); ?>

<section class="hero-modern py-5">
    <div class="container">
        <div class="text-center text-white">
            <h1 class="hero-modern-title mb-3">Jobs in OMR — Find a job or hire locally</h1>
            <p class="hero-modern-subtitle mb-2">MyOMR is your local job board for Old Mahabalipuram Road. Whether you're looking for your next role or want to reach local talent, start here.</p>
            <p class="lead mb-0">Trusted by local businesses and job seekers on OMR.</p>
        </div>
    </div>
</section>

<main class="py-5">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card-modern p-4 h-100 d-flex flex-column">
                    <div class="mb-3" style="color:#14532d;font-size:2rem;"><i class="fas fa-search"></i></div>
                    <h2 class="h4 mb-3" style="color:#14532d;">I'm looking for a job</h2>
                    <ul class="list-unstyled mb-4 flex-grow-1">
                        <li class="mb-2"><i class="fas fa-map-marker-alt text-success me-2"></i>Jobs only in OMR — Perungudi, Sholinganallur, Navalur, Thoraipakkam, Palavakkam, Kelambakkam and more.</li>
                        <li class="mb-2"><i class="fas fa-file-upload text-primary me-2"></i><strong>Get hired faster:</strong> upload your résumé and create your candidate profile — skills, headline, and preferred locality in one place.</li>
                        <li class="mb-2"><i class="fas fa-paper-plane text-success me-2"></i>Apply for free, directly to employers. Browse listings without an account; add a profile when you are ready.</li>
                        <li class="mb-0"><i class="fas fa-briefcase text-success me-2"></i>IT, teaching, hospitality, office, fresher and part-time jobs — all in one place.</li>
                    </ul>
                    <p class="text-muted small mb-3">How it works: (Optional) Create profile with CV → Browse jobs → Open a role → Apply with your résumé.</p>
                    <a href="candidate-profile-omr.php" class="btn btn-lg w-100 fw-semibold mb-2" style="background:#1a73e8;color:#fff;border-radius:8px;padding:12px 24px;">
                        <i class="fas fa-cloud-upload-alt me-2"></i>Upload résumé &amp; create profile
                    </a>
                    <a href="index.php" class="btn btn-lg w-100 fw-semibold btn-outline-success" style="border-radius:8px;padding:12px 24px;border-width:2px;">
                        <i class="fas fa-list me-2"></i>Browse jobs in OMR
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-modern p-4 h-100 d-flex flex-column">
                    <div class="mb-3" style="color:#14532d;font-size:2rem;"><i class="fas fa-building"></i></div>
                    <h2 class="h4 mb-3" style="color:#14532d;">I want to hire / post jobs</h2>
                    <ul class="list-unstyled mb-4 flex-grow-1">
                        <li class="mb-2"><i class="fas fa-users text-success me-2"></i>Reach local candidates where they live — same geography as your team.</li>
                        <li class="mb-2"><i class="fas fa-tachometer-alt text-success me-2"></i>Post jobs and get applications in your employer dashboard. Manage everything in one place.</li>
                        <li class="mb-0"><i class="fas fa-star text-success me-2"></i>Free listing, or Employer Pack for featured placement and more posts per month.</li>
                    </ul>
                    <p class="text-muted small mb-3">How it works: Register or log in → Post a job → We review and go live → Applications appear in your dashboard.</p>
                    <a href="employer-landing-omr.php" class="btn btn-lg w-100 fw-semibold mb-2" style="background:#14532d;color:#fff;border-radius:8px;padding:12px 24px;">
                        <i class="fas fa-plus-circle me-2"></i>Post a job
                    </a>
                    <a href="employer-pack-landing-omr.php" class="btn btn-outline-secondary btn-sm">Learn about Employer Pack (10 jobs/month, featured)</a>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card-modern p-4 text-center">
                    <a href="/it-jobs-omr-chennai.php" class="text-decoration-none text-dark">
                        <div class="h5 mb-1" style="color:#14532d;"><i class="fas fa-laptop-code me-2"></i>IT Jobs</div>
                        <small class="text-muted">Tech roles on OMR</small>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-modern p-4 text-center">
                    <a href="/jobs-in-perungudi-omr.php" class="text-decoration-none text-dark">
                        <div class="h5 mb-1" style="color:#14532d;"><i class="fas fa-map-marker-alt me-2"></i>Jobs by location</div>
                        <small class="text-muted">Perungudi, Sholinganallur &amp; more</small>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-modern p-4 text-center">
                    <a href="/fresher-jobs-omr-chennai.php" class="text-decoration-none text-dark">
                        <div class="h5 mb-1" style="color:#14532d;"><i class="fas fa-user-graduate me-2"></i>Fresher jobs</div>
                        <small class="text-muted">Entry-level on OMR</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
