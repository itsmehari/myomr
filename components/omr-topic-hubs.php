<?php
$omrTopicHubs = [
    ['label' => 'Jobs in OMR Road', 'url' => '/omr-local-job-listings/'],
    ['label' => 'Events in OMR Road', 'url' => '/omr-local-events/'],
    ['label' => 'Explore Places in OMR Road', 'url' => '/omr-listings/index.php'],
    ['label' => 'Buy & Sell in OMR Road', 'url' => '/omr-buy-sell/'],
    ['label' => 'News Highlights from OMR Road', 'url' => '/local-news/news-highlights-from-omr-road.php'],
];
?>
<section class="my-5 p-4 rounded" style="background:#f1f5f9;border:1px solid #e2e8f0;">
    <h2 class="h5 mb-2">Explore More OMR Road Hubs</h2>
    <p class="text-muted mb-3">Navigate key MyOMR sections built for Old Mahabalipuram Road communities and local search intent.</p>
    <div class="d-flex flex-wrap gap-2">
        <?php foreach ($omrTopicHubs as $hub): ?>
            <a href="<?php echo htmlspecialchars($hub['url'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-success btn-sm">
                <?php echo htmlspecialchars($hub['label'], ENT_QUOTES, 'UTF-8'); ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

