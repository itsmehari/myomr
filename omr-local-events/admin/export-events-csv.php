<?php
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="events-upcoming.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['title','slug','start_datetime','end_datetime','is_free','price','location','locality','tickets_url']);

try {
  global $conn;
  $sql = "SELECT title, slug, start_datetime, end_datetime, is_free, price, location, locality, tickets_url
          FROM event_listings WHERE status IN ('scheduled','ongoing') ORDER BY start_datetime ASC";
  $res = $conn->query($sql);
  while ($row = $res->fetch_assoc()) {
    fputcsv($out, [
      $row['title'], $row['slug'], $row['start_datetime'], $row['end_datetime'],
      (int)$row['is_free'], $row['price'], $row['location'], $row['locality'], $row['tickets_url']
    ]);
  }
} catch (Throwable $e) {
  // Write an error line for visibility in the CSV
  fputcsv($out, ['ERROR', $e->getMessage()]);
}

fclose($out);
exit;


