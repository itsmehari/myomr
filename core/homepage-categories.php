<?php
/**
 * Homepage category grid configuration for MyOMR.in
 * Defines $home_categories: label, url, icon, highlight, count (optional)
 * Include after omr-connect.php for listing counts
 */

$home_categories = [];

// Helper to safely get count
$get_count = function($conn, $table) {
  if (!$conn || $conn->connect_error) return null;
  $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
  $r = @$conn->query("SELECT COUNT(*) c FROM `$table`");
  return ($r && $row = $r->fetch_assoc()) ? (int)$row['c'] : null;
};

// Define categories with optional counts
$cats = [
  ['label' => 'Schools', 'url' => '/omr-listings/schools.php', 'icon' => 'fas fa-graduation-cap', 'highlight' => false, 'table' => 'omrschoolslist'],
  ['label' => 'Restaurants', 'url' => '/omr-listings/restaurants.php', 'icon' => 'fas fa-utensils', 'highlight' => false, 'table' => 'omr_restaurants'],
  ['label' => 'Jobs', 'url' => '/omr-local-job-listings/', 'icon' => 'fas fa-briefcase', 'highlight' => true, 'table' => null],
  ['label' => 'Events', 'url' => '/omr-local-events/', 'icon' => 'fas fa-calendar-day', 'highlight' => true, 'table' => 'event_listings'],
  ['label' => 'Hostels & PGs', 'url' => '/omr-hostels-pgs/', 'icon' => 'fas fa-bed', 'highlight' => false, 'table' => null],
  ['label' => 'Coworking', 'url' => '/omr-coworking-spaces/', 'icon' => 'fas fa-building', 'highlight' => false, 'table' => null],
  ['label' => 'Hospitals', 'url' => '/omr-listings/hospitals.php', 'icon' => 'fas fa-hospital', 'highlight' => false, 'table' => 'omrhospitalslist'],
  ['label' => 'Banks', 'url' => '/omr-listings/banks.php', 'icon' => 'fas fa-university', 'highlight' => false, 'table' => 'omrbankslist'],
  ['label' => 'IT Parks', 'url' => '/omr-listings/it-parks-in-omr.php', 'icon' => 'fas fa-laptop-house', 'highlight' => false, 'table' => null],
];

// Job count: use job_listings if available
$job_count = null;
if (isset($conn) && $conn && !$conn->connect_error) {
  $tables = ['job_postings', 'job_listings', 'omr_job_listings', 'jobs'];
  foreach ($tables as $t) {
    $chk = @$conn->query("SHOW TABLES LIKE '$t'");
    if ($chk && $chk->num_rows > 0) {
      $r = @$conn->query("SELECT COUNT(*) c FROM `$t`");
      if ($r && $row = $r->fetch_assoc()) { $job_count = (int)$row['c']; break; }
    }
  }
}

foreach ($cats as $c) {
  $count = null;
  if (!empty($c['table']) && isset($conn)) {
    $count = $get_count($conn, $c['table']);
  }
  if ($c['label'] === 'Jobs' && $job_count !== null) {
    $count = $job_count;
  }
  $home_categories[] = [
    'label'  => $c['label'],
    'url'    => $c['url'],
    'icon'   => $c['icon'],
    'highlight' => (bool)$c['highlight'],
    'count'  => $count,
  ];
}
