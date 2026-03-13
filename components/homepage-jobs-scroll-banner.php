<?php
/**
 * Horizontal scroll banner: recent job posts
 * Expects $recent_jobs array with: id, title, location (optional)
 */
if (empty($recent_jobs) || !is_array($recent_jobs)) {
  return;
}
if (!function_exists('getJobDetailPath')) {
  require_once __DIR__ . '/../omr-local-job-listings/includes/job-functions-omr.php';
}
?>
<section class="jobs-scroll-banner" aria-label="Recent job listings">
  <div class="jobs-scroll-banner__label">
    <i class="fas fa-briefcase" aria-hidden="true"></i>
    <span>Recent jobs in OMR</span>
  </div>
  <div class="jobs-scroll-banner__track-wrap">
    <div class="jobs-scroll-banner__track" aria-hidden="true">
      <?php foreach ($recent_jobs as $j): ?>
      <a href="/omr-local-job-listings/<?php echo getJobDetailPath((int)($j['id'] ?? 0), $j['title'] ?? null); ?>" class="jobs-scroll-banner__item">
        <span class="jobs-scroll-banner__title"><?php echo htmlspecialchars($j['title'] ?? 'Job'); ?></span>
        <?php if (!empty($j['location'])): ?>
        <span class="jobs-scroll-banner__location"><?php echo htmlspecialchars($j['location']); ?></span>
        <?php endif; ?>
      </a>
      <span class="jobs-scroll-banner__sep" aria-hidden="true">•</span>
      <?php endforeach; ?>
      <?php /* Duplicate content for seamless loop */ foreach ($recent_jobs as $j): ?>
      <a href="/omr-local-job-listings/<?php echo getJobDetailPath((int)($j['id'] ?? 0), $j['title'] ?? null); ?>" class="jobs-scroll-banner__item">
        <span class="jobs-scroll-banner__title"><?php echo htmlspecialchars($j['title'] ?? 'Job'); ?></span>
        <?php if (!empty($j['location'])): ?>
        <span class="jobs-scroll-banner__location"><?php echo htmlspecialchars($j['location']); ?></span>
        <?php endif; ?>
      </a>
      <span class="jobs-scroll-banner__sep" aria-hidden="true">•</span>
      <?php endforeach; ?>
    </div>
  </div>
  <a href="/omr-local-job-listings/" class="jobs-scroll-banner__cta">Browse all jobs</a>
</section>
