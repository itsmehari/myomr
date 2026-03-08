<?php
// Internal links helper for hubs/directories. Include where needed in articles.
// Usage: set $links = [ [ 'label' => 'IT Companies in OMR', 'url' => '/it-companies' ], ... ]
// If not set, shows a sensible default set of hubs.

if (!isset($links) || !is_array($links) || empty($links)) {
  $links = [
    [ 'label' => 'Events in OMR', 'url' => '/omr-local-events/' ],
    [ 'label' => 'This Weekend in OMR', 'url' => '/omr-local-events/weekend' ],
    [ 'label' => 'IT Companies in OMR', 'url' => '/it-companies' ],
    [ 'label' => 'Hospitals in OMR', 'url' => '/hospitals' ],
    [ 'label' => 'Banks in OMR', 'url' => '/omr-listings/banks.php' ],
    [ 'label' => 'Schools in OMR', 'url' => '/omr-listings/schools.php' ],
  ];
}
?>
<div class="card my-3">
  <div class="card-body">
    <h5 class="card-title" style="color:#0583D2;">Explore OMR</h5>
    <ul class="list-unstyled mb-0">
      <?php foreach ($links as $l): ?>
        <li class="mb-1"><a href="<?php echo htmlspecialchars($l['url'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($l['label'], ENT_QUOTES, 'UTF-8'); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
  </div>


