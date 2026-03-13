<?php
/**
 * MyOMR Megamenu Configuration
 * Central config for main navigation structure
 * Used by components/megamenu-myomr.php
 */

$current_uri = $_SERVER['REQUEST_URI'] ?? '/';
$current_path = parse_url($current_uri, PHP_URL_PATH) ?: '/';

function nav_is_active($path, $current, $prefix = false) {
  if ($prefix && $path !== '/') {
    return strpos($current, $path) === 0;
  }
  return rtrim($current, '/') === rtrim($path, '/');
}

$megamenu = [
  [
    'label' => 'Home',
    'url'   => '/',
    'icon'  => 'fas fa-home',
    'active' => nav_is_active('/', $current_path),
  ],
  [
    'label'   => 'Explore Places',
    'url'     => '/omr-listings/',
    'icon'    => 'fas fa-map-marker-alt',
    'mega'    => true,
    'columns' => [
      ['title' => 'Directory', 'items' => [
        ['label' => 'OMR Database', 'url' => '/omr-listings/'],
        ['label' => 'IT Parks', 'url' => '/omr-listings/it-parks-in-omr.php'],
        ['label' => 'IT Companies', 'url' => '/omr-listings/it-companies.php'],
        ['label' => 'Schools', 'url' => '/omr-listings/schools.php'],
        ['label' => 'Best Schools', 'url' => '/omr-listings/best-schools.php'],
        ['label' => 'Hospitals', 'url' => '/omr-listings/hospitals.php'],
        ['label' => 'Restaurants', 'url' => '/omr-listings/restaurants.php'],
        ['label' => 'Banks', 'url' => '/omr-listings/banks.php'],
        ['label' => 'ATMs', 'url' => '/omr-listings/atms.php'],
        ['label' => 'Parks', 'url' => '/omr-listings/parks.php'],
        ['label' => 'Industries', 'url' => '/omr-listings/industries.php'],
        ['label' => 'Government Offices', 'url' => '/omr-listings/government-offices.php'],
      ]],
      ['title' => 'Accommodation & Work', 'items' => [
        ['label' => 'Hostels & PGs', 'url' => '/omr-hostels-pgs/'],
        ['label' => 'Coworking Spaces', 'url' => '/omr-coworking-spaces/'],
        ['label' => 'Rent & Lease', 'url' => '/omr-rent-lease/'],
      ]],
    ],
  ],
  [
    'label'   => 'Jobs',
    'url'     => '/omr-local-job-listings/',
    'icon'    => 'fas fa-briefcase',
    'mega'    => true,
    'columns' => [
      ['title' => 'Browse Jobs', 'items' => [
        ['label' => 'All Jobs in OMR', 'url' => '/omr-local-job-listings/'],
        ['label' => 'Jobs in OMR Chennai', 'url' => '/jobs-in-omr-chennai.php'],
      ]],
      ['title' => 'By Industry', 'items' => [
        ['label' => 'IT Jobs', 'url' => '/it-jobs-omr-chennai.php'],
        ['label' => 'Teaching Jobs', 'url' => '/teaching-jobs-omr-chennai.php'],
        ['label' => 'Healthcare Jobs', 'url' => '/healthcare-jobs-omr-chennai.php'],
        ['label' => 'Retail Jobs', 'url' => '/retail-jobs-omr-chennai.php'],
        ['label' => 'Hospitality Jobs', 'url' => '/hospitality-jobs-omr-chennai.php'],
      ]],
      ['title' => 'By Type', 'items' => [
        ['label' => 'Fresher Jobs', 'url' => '/fresher-jobs-omr-chennai.php'],
        ['label' => 'Experienced Jobs', 'url' => '/experienced-jobs-omr-chennai.php'],
        ['label' => 'Part-Time Jobs', 'url' => '/part-time-jobs-omr-chennai.php'],
        ['label' => 'Work from Home', 'url' => '/work-from-home-jobs-omr.php'],
      ]],
      ['title' => 'By Location', 'items' => [
        ['label' => 'Perungudi', 'url' => '/jobs-in-perungudi-omr.php'],
        ['label' => 'Sholinganallur', 'url' => '/jobs-in-sholinganallur-omr.php'],
        ['label' => 'Navalur', 'url' => '/jobs-in-navalur-omr.php'],
        ['label' => 'Thoraipakkam', 'url' => '/jobs-in-thoraipakkam-omr.php'],
        ['label' => 'Kelambakkam', 'url' => '/jobs-in-kelambakkam-omr.php'],
      ]],
    ],
  ],
  [
    'label'   => 'Events',
    'url'     => '/omr-local-events/',
    'icon'    => 'fas fa-calendar-alt',
    'active'  => nav_is_active('/omr-local-events', $current_path, true),
  ],
  [
    'label'   => 'News & Media',
    'url'     => '/local-news/news-highlights-from-omr-road.php',
    'icon'    => 'fas fa-newspaper',
    'mega'    => true,
    'columns' => [
      ['title' => 'Content', 'items' => [
        ['label' => 'News Highlights', 'url' => '/local-news/news-highlights-from-omr-road.php'],
        ['label' => 'Gallery', 'url' => '/local-news/image-video-gallery-old-mahabalipuram-road-news.php'],
      ]],
    ],
  ],
  [
    'label'   => 'Discover',
    'url'     => '/discover-myomr/overview.php',
    'icon'    => 'fas fa-compass',
    'mega'    => true,
    'columns' => [
      ['title' => 'About MyOMR', 'items' => [
        ['label' => 'Overview', 'url' => '/discover-myomr/overview.php'],
        ['label' => 'Get Started', 'url' => '/discover-myomr/getting-started.php'],
        ['label' => 'Features', 'url' => '/discover-myomr/features.php'],
        ['label' => 'Pricing', 'url' => '/discover-myomr/pricing.php'],
        ['label' => 'Community', 'url' => '/discover-myomr/community.php'],
        ['label' => 'Support', 'url' => '/discover-myomr/support.php'],
        ['label' => 'SDGs', 'url' => '/discover-myomr/sustainable-development-goals.php'],
      ]],
    ],
  ],
  [
    'label'   => 'Services',
    'url'     => '#',
    'icon'    => 'fas fa-tasks',
    'mega'    => true,
    'columns' => [
      ['title' => 'Listings', 'items' => [
        ['label' => 'Post a Job', 'url' => '/omr-local-job-listings/post-job-omr.php'],
        ['label' => 'List an Event', 'url' => '/omr-local-events/post-event-omr.php'],
        ['label' => 'List a Property', 'url' => '/omr-rent-lease/add-property-omr.php'],
        ['label' => 'List Hostel/PG', 'url' => '/omr-hostels-pgs/owner-login.php'],
      ]],
      ['title' => 'Resources', 'items' => [
        ['label' => 'Rent & Lease', 'url' => '/omr-rent-lease/'],
        ['label' => 'Citizens Charter', 'url' => '/citizens-charter-old-mahabali-puram-road.php'],
      ]],
    ],
  ],
  [
    'label'   => 'About',
    'url'     => '/about-myomr-omr-community-portal.php',
    'icon'    => 'fas fa-info-circle',
    'active'  => nav_is_active('/about-myomr-omr-community-portal.php', $current_path),
  ],
  [
    'label'   => 'Contact',
    'url'     => '/contact-my-omr-team.php',
    'icon'    => 'fas fa-envelope',
    'active'  => nav_is_active('/contact-my-omr-team.php', $current_path),
  ],
];
