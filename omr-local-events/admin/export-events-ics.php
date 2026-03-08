<?php
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();

header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="events-upcoming.ics"');

function escIcs($s){ return addcslashes(preg_replace('/[\r\n]+/',' ', (string)$s), ",;\\"); }

echo "BEGIN:VCALENDAR\r\n";
echo "VERSION:2.0\r\n";
echo "PRODID:-//MyOMR//Events Export//EN\r\n";

try {
  global $conn;
  $sql = "SELECT title, slug, start_datetime, end_datetime, location, locality, description
          FROM event_listings WHERE status IN ('scheduled','ongoing') ORDER BY start_datetime ASC";
  $res = $conn->query($sql);
  while ($row = $res->fetch_assoc()) {
    $uid = uniqid('myomr-') . '@myomr.in';
    $dtStart = gmdate('Ymd\THis\Z', strtotime($row['start_datetime']));
    $dtEnd = gmdate('Ymd\THis\Z', strtotime($row['end_datetime'] ?: $row['start_datetime'] . ' +1 hour'));
    $summary = escIcs($row['title']);
    $loc = escIcs(trim(($row['location'] ?: '') . ' ' . ($row['locality'] ?: 'OMR Chennai')));
    $desc = escIcs(strip_tags($row['description'] ?? ''));
    echo "BEGIN:VEVENT\r\n";
    echo "UID:$uid\r\n";
    echo "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
    echo "DTSTART:$dtStart\r\n";
    echo "DTEND:$dtEnd\r\n";
    echo "SUMMARY:$summary\r\n";
    echo "LOCATION:$loc\r\n";
    echo "DESCRIPTION:$desc\r\n";
    echo "END:VEVENT\r\n";
  }
} catch (Throwable $e) {
  // If error, emit a dummy VEVENT with the message for visibility
  echo "BEGIN:VEVENT\r\n";
  echo "SUMMARY:Export error\r\n";
  echo "DESCRIPTION:" . escIcs($e->getMessage()) . "\r\n";
  echo "DTSTART:" . gmdate('Ymd\THis\Z') . "\r\n";
  echo "DTEND:" . gmdate('Ymd\THis\Z', time()+3600) . "\r\n";
  echo "END:VEVENT\r\n";
}

echo "END:VCALENDAR\r\n";
exit;


