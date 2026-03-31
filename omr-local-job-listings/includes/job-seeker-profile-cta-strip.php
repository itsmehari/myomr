<?php
/**
 * Slim CTA strip for job landing pages (include after hero).
 * @var string|null $job_seeker_cta_utm Optional UTM query suffix e.g. "?utm_source=..."
 */
$profile_url = '/omr-local-job-listings/candidate-profile-omr.php';
if (!empty($job_seeker_cta_utm) && is_string($job_seeker_cta_utm)) {
    $profile_url .= (strpos($job_seeker_cta_utm, '?') === 0) ? $job_seeker_cta_utm : ('?' . $job_seeker_cta_utm);
}
?>
<div class="container py-2">
    <div class="alert alert-light border mb-0 d-flex flex-wrap align-items-center justify-content-between gap-2" style="border-color:rgba(0,133,82,.25)!important;background:#f4fbf7;">
        <span class="small mb-0"><i class="fas fa-file-signature text-primary me-1"></i> <strong>Job seeker?</strong> Upload your résumé and create a free <a href="<?= htmlspecialchars($profile_url, ENT_QUOTES, 'UTF-8') ?>">MyOMR profile</a> for the OMR corridor.</span>
        <a href="<?= htmlspecialchars($profile_url, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm btn-success">Résumé &amp; profile</a>
    </div>
</div>
