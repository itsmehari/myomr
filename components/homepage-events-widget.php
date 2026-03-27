<?php
/**
 * Homepage Events Widget - upcoming events strip
 * Expects $recent_events array with: id, title, slug, start_datetime, location, locality (optional)
 */
if (!defined('MYOMR_EVENTS_HUB_PATH')) {
  require_once __DIR__ . '/../core/include-path.php';
}
if (empty($recent_events) || !is_array($recent_events)) {
  return;
}
?>
<section class="events-homepage-strip" aria-label="Upcoming events in OMR">
  <div class="events-homepage-strip__label">
    <i class="fas fa-calendar-day" aria-hidden="true"></i>
    <span>Upcoming events in OMR</span>
  </div>
  <div class="events-homepage-strip__track-wrap">
    <div class="events-homepage-strip__track">
      <?php foreach ($recent_events as $ev): ?>
      <a href="<?php echo htmlspecialchars(MYOMR_EVENTS_HUB_PATH, ENT_QUOTES, 'UTF-8'); ?>/event/<?php echo urlencode($ev['slug'] ?? ''); ?>" class="events-homepage-strip__item">
        <span class="events-homepage-strip__title"><?php echo htmlspecialchars($ev['title'] ?? 'Event'); ?></span>
        <span class="events-homepage-strip__meta">
          <?php echo !empty($ev['start_datetime']) ? date('M d', strtotime($ev['start_datetime'])) : ''; ?>
          <?php if (!empty($ev['locality'])): ?> · <?php echo htmlspecialchars($ev['locality']); ?><?php endif; ?>
        </span>
      </a>
      <span class="events-homepage-strip__sep" aria-hidden="true">•</span>
      <?php endforeach; ?>
      <?php foreach ($recent_events as $ev): ?>
      <a href="<?php echo htmlspecialchars(MYOMR_EVENTS_HUB_PATH, ENT_QUOTES, 'UTF-8'); ?>/event/<?php echo urlencode($ev['slug'] ?? ''); ?>" class="events-homepage-strip__item">
        <span class="events-homepage-strip__title"><?php echo htmlspecialchars($ev['title'] ?? 'Event'); ?></span>
        <span class="events-homepage-strip__meta">
          <?php echo !empty($ev['start_datetime']) ? date('M d', strtotime($ev['start_datetime'])) : ''; ?>
          <?php if (!empty($ev['locality'])): ?> · <?php echo htmlspecialchars($ev['locality']); ?><?php endif; ?>
        </span>
      </a>
      <span class="events-homepage-strip__sep" aria-hidden="true">•</span>
      <?php endforeach; ?>
    </div>
  </div>
  <a href="<?php echo htmlspecialchars(MYOMR_EVENTS_HUB_PATH, ENT_QUOTES, 'UTF-8'); ?>/" class="events-homepage-strip__cta">Browse all events</a>
</section>
