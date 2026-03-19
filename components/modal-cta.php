<?php
/**
 * Site-wide CTA modals: Post job, Employer Pack, Subscribe / Job alerts.
 * Include once per layout (e.g. in footer). Modals are shown by triggers
 * (data-omr-cta="post-job" | "employer-pack" | "subscribe") or optional JS.
 * Uses Bootstrap 5 modal. Does not auto-open; pages/JS control visibility.
 */
if (!defined('ROOT_PATH')) {
    $ROOT_PATH = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__, 2);
} else {
    $ROOT_PATH = ROOT_PATH;
}
$jobs_base = '/omr-local-job-listings';
?>
<!-- Post a job CTA modal -->
<div class="modal fade" id="omrCtaPostJob" tabindex="-1" aria-labelledby="omrCtaPostJobLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="omrCtaPostJobLabel"><i class="fas fa-briefcase me-2"></i> Hire on OMR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Reach job seekers in Chennai's IT corridor. Post your vacancy on MyOMR — free listing or featured with Employer Pack.</p>
                <ul class="small text-muted mb-0">
                    <li>OMR-focused audience</li>
                    <li>Direct applications to your inbox</li>
                    <li>Featured placement with Employer Pack</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <a href="<?php echo htmlspecialchars($jobs_base . '/post-job-omr.php', ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-success">Post a job (free)</a>
                <a href="<?php echo htmlspecialchars($jobs_base . '/employer-pack-landing-omr.php', ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary">Employer Pack</a>
            </div>
        </div>
    </div>
</div>

<!-- Employer Pack CTA modal -->
<div class="modal fade" id="omrCtaEmployerPack" tabindex="-1" aria-labelledby="omrCtaEmployerPackLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="omrCtaEmployerPackLabel"><i class="fas fa-star me-2"></i> MyOMR Employer Pack</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">10 jobs per month, all featured. Get more visibility and priority moderation.</p>
                <p class="small text-muted mb-0">Ideal for OMR companies hiring regularly. One plan, one invoice.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <a href="<?php echo htmlspecialchars($jobs_base . '/employer-pack-landing-omr.php', ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary">View Employer Pack</a>
            </div>
        </div>
    </div>
</div>

<!-- Subscribe / Job alerts CTA modal -->
<div class="modal fade" id="omrCtaSubscribe" tabindex="-1" aria-labelledby="omrCtaSubscribeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="omrCtaSubscribeLabel"><i class="fas fa-bell me-2"></i> Job alerts &amp; news</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Get OMR news, events and job alerts in your inbox. No spam.</p>
                <p class="small text-muted mb-0">Subscribe in the footer below or visit our contact page.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#footer-subscribe" class="btn btn-primary" data-bs-dismiss="modal">Go to subscribe</a>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        var triggers = document.querySelectorAll('[data-omr-cta]');
        triggers.forEach(function(el) {
            el.addEventListener('click', function(e) {
                var id = el.getAttribute('data-omr-cta');
                var modalId = id === 'post-job' ? 'omrCtaPostJob' : (id === 'employer-pack' ? 'omrCtaEmployerPack' : (id === 'subscribe' ? 'omrCtaSubscribe' : null));
                if (modalId) {
                    e.preventDefault();
                    var modal = document.getElementById(modalId);
                    if (modal && typeof bootstrap !== 'undefined') {
                        var m = bootstrap.Modal.getOrCreateInstance(modal);
                        m.show();
                    }
                }
            });
        });
    });
})();
</script>
