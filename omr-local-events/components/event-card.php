<?php
/**
 * Reusable event card component.
 * Expects: $ev array with title, slug, location, locality, start_datetime, end_datetime, is_free, price, tickets_url, image_url, featured
 * Optional: $compact (bool) - smaller layout for homepage widget
 */
if (empty($ev) || empty($ev['slug'])) return;

$imgUrl = !empty($ev['image_url']) ? $ev['image_url'] : 'https://myomr.in/My-OMR-Logo.png';
$evUrl = '/omr-local-events/event/' . htmlspecialchars($ev['slug']);
$locSlug = !empty($ev['locality']) ? localityToSlug($ev['locality']) : '';
$compact = !empty($compact);
?>
<div class="event-card <?php echo $compact ? 'event-card--compact' : ''; ?>">
  <div class="event-card__image-wrap">
    <a href="<?php echo $evUrl; ?>" class="event-card__image-link" aria-hidden="true">
      <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="" class="event-card__image" loading="lazy">
    </a>
    <div class="event-card__badges">
      <?php if (!empty($ev['featured'])): ?>
        <span class="event-card__badge event-card__badge--featured"><i class="fas fa-star"></i> Featured</span>
      <?php endif; ?>
      <?php if (!empty($ev['is_free'])): ?>
        <span class="event-card__badge event-card__badge--free">Free</span>
      <?php elseif (!empty($ev['price'])): ?>
        <span class="event-card__badge event-card__badge--price"><?php echo htmlspecialchars($ev['price']); ?></span>
      <?php endif; ?>
    </div>
  </div>
  <div class="event-card__body">
    <h3 class="event-card__title">
      <a href="<?php echo $evUrl; ?>"><?php echo htmlspecialchars($ev['title']); ?></a>
    </h3>
    <div class="event-card__meta">
      <span class="event-card__date"><i class="far fa-calendar-alt"></i> <?php echo date('M d, Y g:i a', strtotime($ev['start_datetime'])); ?></span>
      <span class="event-card__location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($ev['location']); ?><?php echo !empty($ev['locality']) ? ' · ' . htmlspecialchars($ev['locality']) : ''; ?></span>
      <?php if (!empty($locSlug) && !$compact): ?>
        <a class="event-card__locality-tag" href="/omr-local-events/locality/<?php echo urlencode($locSlug); ?>">#<?php echo htmlspecialchars($ev['locality']); ?></a>
      <?php endif; ?>
      <?php if (!empty($ev['location']) && !$compact): $venueSlug = locationToVenueSlug($ev['location']); ?>
        <a class="event-card__venue-tag" href="/omr-local-events/venue/<?php echo urlencode($venueSlug); ?>">@ <?php echo htmlspecialchars($ev['location']); ?></a>
      <?php endif; ?>
    </div>
    <div class="event-card__actions">
      <a href="<?php echo $evUrl; ?>" class="btn btn-sm btn-primary event-card__btn" data-analytics="viewClicked" data-analytics-label="<?php echo htmlspecialchars($ev['slug']); ?>">
        View Details
      </a>
      <?php if (!empty($ev['tickets_url'])): ?>
        <a href="<?php echo htmlspecialchars($ev['tickets_url']); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-success event-card__btn" data-analytics="getTickets" data-analytics-label="<?php echo htmlspecialchars($ev['slug']); ?>">
          Get Tickets
        </a>
      <?php endif; ?>
      <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($ev['location'] . ' ' . ($ev['locality'] ?? 'OMR Chennai')); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary event-card__btn" data-analytics="mapClicked" data-analytics-label="<?php echo htmlspecialchars($ev['slug']); ?>">
        <i class="fas fa-map-marker-alt"></i>
      </a>
    </div>
  </div>
</div>
