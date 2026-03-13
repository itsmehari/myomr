<?php
/**
 * Hostels & PGs – CTA section for property owners.
 * Expects: HOSTELS_PGS_BASE_URL (optional).
 */
$base = defined('HOSTELS_PGS_BASE_URL') ? HOSTELS_PGS_BASE_URL : '/omr-hostels-pgs';
?>
<section class="cta-section bg-primary text-white py-5" aria-labelledby="cta-heading">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="h3 mb-3" id="cta-heading">Are You a Property Owner?</h2>
                <p class="lead mb-4">List your hostel or PG and connect with thousands of students and professionals in the OMR area.</p>
                <a href="<?php echo htmlspecialchars($base); ?>/owner-register.php" class="btn btn-light btn-lg me-3">
                    <i class="fas fa-plus me-1"></i> List Your Property
                </a>
                <a href="<?php echo htmlspecialchars($base); ?>/owner-login.php" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-sign-in-alt me-1"></i> Owner Login
                </a>
            </div>
        </div>
    </div>
</section>
