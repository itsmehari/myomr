<?php
/**
 * Related Landing Pages Component
 * Shows contextually relevant landing pages on each landing page
 * 
 * Usage: <?php 
 *   $current_page_type = 'location'; // 'location', 'industry', 'experience', 'type'
 *   $current_page_value = 'Perungudi'; // e.g., 'Perungudi', 'IT', 'fresher', 'part-time'
 *   include 'components/job-related-landing-pages.php'; 
 * ?>
 */

// Determine related pages based on current page type
$related_pages = [];

if (isset($current_page_type)) {
    switch ($current_page_type) {
        case 'location':
            // Related: Industries in this location, experience levels, job types
            $related_pages = [
                ['url' => '/it-jobs-omr-chennai.php', 'title' => 'IT Jobs in OMR', 'icon' => 'laptop-code'],
                ['url' => '/teaching-jobs-omr-chennai.php', 'title' => 'Teaching Jobs in OMR', 'icon' => 'chalkboard-teacher'],
                ['url' => '/fresher-jobs-omr-chennai.php', 'title' => 'Fresher Jobs in OMR', 'icon' => 'user-graduate'],
                ['url' => '/part-time-jobs-omr-chennai.php', 'title' => 'Part-Time Jobs in OMR', 'icon' => 'clock'],
            ];
            break;
            
        case 'industry':
            // Related: Other locations, experience levels, job types
            $related_pages = [
                ['url' => '/jobs-in-sholinganallur-omr.php', 'title' => 'Jobs in Sholinganallur', 'icon' => 'map-marker-alt'],
                ['url' => '/jobs-in-perungudi-omr.php', 'title' => 'Jobs in Perungudi', 'icon' => 'map-marker-alt'],
                ['url' => '/fresher-jobs-omr-chennai.php', 'title' => 'Fresher Jobs', 'icon' => 'user-graduate'],
                ['url' => '/experienced-jobs-omr-chennai.php', 'title' => 'Experienced Jobs', 'icon' => 'user-tie'],
            ];
            break;
            
        case 'experience':
            // Related: Industries, locations, job types
            $related_pages = [
                ['url' => '/it-jobs-omr-chennai.php', 'title' => 'IT Jobs for Freshers', 'icon' => 'laptop-code'],
                ['url' => '/jobs-in-sholinganallur-omr.php', 'title' => 'Jobs in Sholinganallur', 'icon' => 'map-marker-alt'],
                ['url' => '/part-time-jobs-omr-chennai.php', 'title' => 'Part-Time Jobs', 'icon' => 'clock'],
            ];
            break;
            
        case 'type':
            // Related: Industries, locations, experience levels
            $related_pages = [
                ['url' => '/retail-jobs-omr-chennai.php', 'title' => 'Retail Jobs', 'icon' => 'shopping-bag'],
                ['url' => '/jobs-in-navalur-omr.php', 'title' => 'Jobs in Navalur', 'icon' => 'map-marker-alt'],
                ['url' => '/fresher-jobs-omr-chennai.php', 'title' => 'Fresher Jobs', 'icon' => 'user-graduate'],
            ];
            break;
            
        default:
            // Default: Show popular pages
            $related_pages = [
                ['url' => '/it-jobs-omr-chennai.php', 'title' => 'IT Jobs in OMR', 'icon' => 'laptop-code'],
                ['url' => '/jobs-in-sholinganallur-omr.php', 'title' => 'Jobs in Sholinganallur', 'icon' => 'map-marker-alt'],
                ['url' => '/fresher-jobs-omr-chennai.php', 'title' => 'Fresher Jobs', 'icon' => 'user-graduate'],
                ['url' => '/part-time-jobs-omr-chennai.php', 'title' => 'Part-Time Jobs', 'icon' => 'clock'],
            ];
    }
}

// Limit to 4 related pages
$related_pages = array_slice($related_pages, 0, 4);
?>

<?php if (!empty($related_pages)): ?>
<section class="related-landing-pages py-4 bg-light">
    <div class="container">
        <h3 class="h5 mb-4">You Might Also Be Interested In</h3>
        <div class="row g-3">
            <?php foreach ($related_pages as $page): ?>
            <div class="col-md-3 col-sm-6">
                <a href="<?php echo htmlspecialchars($page['url']); ?>" class="card card-hover text-decoration-none h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-<?php echo htmlspecialchars($page['icon'] ?? 'briefcase'); ?> fa-2x text-primary mb-2"></i>
                        <h5 class="card-title mb-0"><?php echo htmlspecialchars($page['title']); ?></h5>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<style>
.card-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #dee2e6;
}
.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    text-decoration: none;
}
</style>

