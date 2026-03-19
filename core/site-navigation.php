<?php
/**
 * Central navigation source for crawl-critical MyOMR hub links.
 * Keep labels and paths stable to strengthen sitelink signals.
 */

if (!function_exists('myomr_get_primary_hub_links')) {
    function myomr_get_primary_hub_links() {
        return [
            ['label' => 'Home', 'path' => '/'],
            ['label' => 'Jobs', 'path' => '/omr-local-job-listings/'],
            ['label' => 'IT Career News', 'path' => '/discover-myomr/it-careers-omr.php'],
            ['label' => 'Events', 'path' => '/omr-local-events/'],
            ['label' => 'Explore Places', 'path' => '/omr-listings/index.php'],
            ['label' => 'Buy & Sell', 'path' => '/omr-buy-sell/'],
            ['label' => 'News Highlights', 'path' => '/local-news/news-highlights-from-omr-road.php'],
            ['label' => 'Contact', 'path' => '/contact-my-omr-team.php'],
        ];
    }
}

