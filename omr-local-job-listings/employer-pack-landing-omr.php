<?php
/**
 * Employer Pack — Sales / Landing Page
 * Reusable page for B2B employer package (10 jobs/month, featured, etc.).
 */
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/..') ?: (__DIR__ . '/..'));
}
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$page_title = 'Employer Pack — 10 Jobs/Month | Hire on OMR | MyOMR';
$page_description = 'Post up to 10 featured jobs per month on MyOMR. One fixed price. Hyper-local OMR reach. Contact us to activate your Employer Pack.';
$canonical_url = 'https://myomr.in/omr-local-job-listings/employer-pack-landing-omr.php';
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
            <h1 class="hero-modern-title mb-3">Hire from OMR. Reach local talent where they live.</h1>
            <p class="hero-modern-subtitle mb-2">MyOMR Employer Pack — 10 jobs per month. One fixed price. No per-application fees.</p>
            <p class="lead mb-0">Post up to 10 jobs every month. Each job is featured so it stands out. We only serve the OMR corridor — so every view is relevant.</p>
        </div>
    </div>
</section>

<main class="py-5">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-4">
                <div class="card-modern p-4 h-100">
                    <div class="mb-3" style="color:#14532d;font-size:1.8rem;"><i class="fas fa-map-marker-alt"></i></div>
                    <h4 class="h5 mb-2" style="color:#14532d;">Hyper-local</h4>
                    <p class="text-muted mb-0">We cover Palavakkam, Perungudi, Sholinganallur, Navalur, Thoraipakkam, Kelambakkam and the rest of OMR — the same geography where your team and candidates live.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-modern p-4 h-100">
                    <div class="mb-3" style="color:#14532d;font-size:1.8rem;"><i class="fas fa-star"></i></div>
                    <h4 class="h5 mb-2" style="color:#14532d;">Featured placement</h4>
                    <p class="text-muted mb-0">Your jobs appear at the top with a Featured badge so they get more views and applications.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-modern p-4 h-100">
                    <div class="mb-3" style="color:#14532d;font-size:1.8rem;"><i class="fas fa-briefcase"></i></div>
                    <h4 class="h5 mb-2" style="color:#14532d;">10 jobs per month</h4>
                    <p class="text-muted mb-0">One monthly price for 10 job posts. No per-job or per-application charges like on large portals.</p>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4 col-sm-6">
                <div class="card-modern p-4 text-center">
                    <div class="h2 mb-1" style="color:#14532d;">10</div>
                    <small class="text-muted">Job posts per month</small>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card-modern p-4 text-center">
                    <div class="h2 mb-1" style="color:#22c55e;">Featured</div>
                    <small class="text-muted">Top placement for each job</small>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card-modern p-4 text-center">
                    <div class="h2 mb-1" style="color:#0891b2;">Unlimited</div>
                    <small class="text-muted">Applications per job</small>
                </div>
            </div>
        </div>

        <div class="card-modern p-5 mb-5">
            <h2 class="h4 mb-4 text-center">What you get</h2>
            <ul class="list-unstyled mb-0">
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Up to <strong>10 job posts per month</strong> under your organization.</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Featured</strong> visibility for each of those jobs (top placement + badge).</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Unlimited applications</strong> per job; view and shortlist in your employer dashboard.</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Priority review</strong> so your jobs go live quickly.</li>
                <li class="mb-0"><i class="fas fa-check text-success me-2"></i><strong>OMR-wide reach</strong> on our job board and locality pages.</li>
            </ul>
        </div>

        <div class="card-modern p-5 mb-5">
            <h2 class="h4 mb-3">Pricing</h2>
            <p class="mb-2"><strong>Employer Pack — 10 jobs/month:</strong> ₹2,499/month standard (or ₹1,499 introductory when applicable). Inclusive of 10 featured job posts.</p>
            <p class="text-muted mb-0">Invoice monthly; bank transfer or UPI. Contact for current offers.</p>
        </div>

        <div class="card-modern p-5 mb-4">
            <h2 class="h4 mb-3">How to enable</h2>
            <p class="mb-4">Contact us to activate your Employer Pack. We'll set up your company account and send a simple monthly invoice. No long-term contract — pay month by month.</p>
            <div class="d-flex gap-3 flex-wrap">
                <a href="/weblog/contact-my-omr-team.php" class="btn btn-lg fw-semibold" style="background:#14532d;color:#fff;border-radius:8px;padding:12px 28px;">
                    <i class="fas fa-envelope me-2"></i>Contact us
                </a>
                <a href="employer-landing-omr.php" class="btn btn-outline-secondary btn-lg">Post jobs free</a>
            </div>
        </div>
    </div>
</main>

<?php omr_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
