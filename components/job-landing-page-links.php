<?php
/**
 * Job Landing Page Quick Links Component
 * Reusable component for displaying links to job landing pages
 * 
 * Usage: <?php include 'components/job-landing-page-links.php'; ?>
 */

// Determine what type of links to show
$link_type = $link_type ?? 'all'; // 'all', 'locations', 'industries', 'types', 'experience'
$show_title = $show_title ?? true;
$columns = $columns ?? 3; // 2, 3, 4, or 6
?>

<?php if ($show_title): ?>
<div class="row mb-4">
    <div class="col-12">
        <h3 class="h4 mb-3"><?php echo $section_title ?? 'Find Jobs by Category'; ?></h3>
    </div>
</div>
<?php endif; ?>

<?php if ($link_type === 'all' || $link_type === 'locations'): ?>
<!-- Location-Specific Pages -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <h4 class="h6 text-muted mb-3">Jobs by Location</h4>
    </div>
    <div class="col-md-<?php echo $columns === 2 ? '6' : ($columns === 3 ? '4' : ($columns === 4 ? '3' : '2')); ?> col-sm-6">
        <a href="/jobs-in-perungudi-omr.php" class="btn btn-outline-primary btn-sm w-100">
            <i class="fas fa-map-marker-alt me-1"></i> Jobs in Perungudi
        </a>
    </div>
    <div class="col-md-<?php echo $columns === 2 ? '6' : ($columns === 3 ? '4' : ($columns === 4 ? '3' : '2')); ?> col-sm-6">
        <a href="/jobs-in-sholinganallur-omr.php" class="btn btn-outline-primary btn-sm w-100">
            <i class="fas fa-map-marker-alt me-1"></i> Jobs in Sholinganallur
        </a>
    </div>
    <div class="col-md-<?php echo $columns === 2 ? '6' : ($columns === 3 ? '4' : ($columns === 4 ? '3' : '2')); ?> col-sm-6">
        <a href="/jobs-in-navalur-omr.php" class="btn btn-outline-primary btn-sm w-100">
            <i class="fas fa-map-marker-alt me-1"></i> Jobs in Navalur
        </a>
    </div>
    <div class="col-md-<?php echo $columns === 2 ? '6' : ($columns === 3 ? '4' : ($columns === 4 ? '3' : '2')); ?> col-sm-6">
        <a href="/jobs-in-thoraipakkam-omr.php" class="btn btn-outline-primary btn-sm w-100">
            <i class="fas fa-map-marker-alt me-1"></i> Jobs in Thoraipakkam
        </a>
    </div>
    <div class="col-md-<?php echo $columns === 2 ? '6' : ($columns === 3 ? '4' : ($columns === 4 ? '3' : '2')); ?> col-sm-6">
        <a href="/jobs-in-kelambakkam-omr.php" class="btn btn-outline-primary btn-sm w-100">
            <i class="fas fa-map-marker-alt me-1"></i> Jobs in Kelambakkam
        </a>
    </div>
</div>
<?php endif; ?>

<?php if ($link_type === 'all' || $link_type === 'industries'): ?>
<!-- Industry-Specific Pages -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <h4 class="h6 text-muted mb-3">Jobs by Industry</h4>
    </div>
    <div class="col-md-<?php echo $columns === 2 ? '6' : ($columns === 3 ? '4' : ($columns === 4 ? '3' : '2')); ?> col-sm-6">
        <a href="/it-jobs-omr-chennai.php" class="btn btn-outline-success btn-sm w-100">
            <i class="fas fa-laptop-code me-1"></i> IT Jobs
        </a>
    </div>
    <div class="col-md-<?php echo $columns === 2 ? '6' : ($columns === 3 ? '4' : ($columns === 4 ? '3' : '2')); ?> col-sm-6">
        <a href="/teaching-jobs-omr-chennai.php" class="btn btn-outline-success btn-sm w-100">
            <i class="fas fa-chalkboard-teacher me-1"></i> Teaching Jobs
        </a>
    </div>
    <div class="col-md-<?php echo $columns === 2 ? '6' : ($columns === 3 ? '4' : ($columns === 4 ? '3' : '2')); ?> col-sm-6">
        <a href="/healthcare-jobs-omr-chennai.php" class="btn btn-outline-success btn-sm w-100">
            <i class="fas fa-user-md me-1"></i> Healthcare Jobs
        </a>
    </div>
    <div class="col-md-<?php echo $columns === 2 ? '6' : ($columns === 3 ? '4' : ($columns === 4 ? '3' : '2')); ?> col-sm-6">
        <a href="/retail-jobs-omr-chennai.php" class="btn btn-outline-success btn-sm w-100">
            <i class="fas fa-shopping-bag me-1"></i> Retail Jobs
        </a>
    </div>
    <div class="col-md-<?php echo $columns === 2 ? '6' : ($columns === 3 ? '4' : ($columns === 4 ? '3' : '2')); ?> col-sm-6">
        <a href="/hospitality-jobs-omr-chennai.php" class="btn btn-outline-success btn-sm w-100">
            <i class="fas fa-utensils me-1"></i> Hospitality Jobs
        </a>
    </div>
</div>
<?php endif; ?>

<?php if ($link_type === 'all' || $link_type === 'experience'): ?>
<!-- Experience-Level Pages -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <h4 class="h6 text-muted mb-3">Jobs by Experience Level</h4>
    </div>
    <div class="col-md-<?php echo $columns === 2 ? '6' : ($columns === 3 ? '4' : ($columns === 4 ? '3' : '2')); ?> col-sm-6">
        <a href="/fresher-jobs-omr-chennai.php" class="btn btn-outline-info btn-sm w-100">
            <i class="fas fa-user-graduate me-1"></i> Fresher Jobs
        </a>
    </div>
    <div class="col-md-<?php echo $columns === 2 ? '6' : ($columns === 3 ? '4' : ($columns === 4 ? '3' : '2')); ?> col-sm-6">
        <a href="/experienced-jobs-omr-chennai.php" class="btn btn-outline-info btn-sm w-100">
            <i class="fas fa-user-tie me-1"></i> Experienced Jobs
        </a>
    </div>
</div>
<?php endif; ?>

<?php if ($link_type === 'all' || $link_type === 'types'): ?>
<!-- Job-Type Pages -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <h4 class="h6 text-muted mb-3">Jobs by Type</h4>
    </div>
    <div class="col-md-<?php echo $columns === 2 ? '6' : ($columns === 3 ? '4' : ($columns === 4 ? '3' : '2')); ?> col-sm-6">
        <a href="/part-time-jobs-omr-chennai.php" class="btn btn-outline-warning btn-sm w-100">
            <i class="fas fa-clock me-1"></i> Part-Time Jobs
        </a>
    </div>
    <div class="col-md-<?php echo $columns === 2 ? '6' : ($columns === 3 ? '4' : ($columns === 4 ? '3' : '2')); ?> col-sm-6">
        <a href="/work-from-home-jobs-omr.php" class="btn btn-outline-warning btn-sm w-100">
            <i class="fas fa-home me-1"></i> Work from Home
        </a>
    </div>
</div>
<?php endif; ?>

