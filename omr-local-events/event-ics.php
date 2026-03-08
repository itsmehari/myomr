<?php
require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/includes/event-functions-omr.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
if (!$slug) { http_response_code(404); exit('Not found'); }
$event = getEventBySlug($slug);
if (!$event) { http_response_code(404); exit('Not found'); }

header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="' . preg_replace('/[^a-zA-Z0-9_-]/','-', $slug) . '.ics"');

$uid = uniqid('myomr-') . '@myomr.in';
$dtStart = gmdate('Ymd\THis\Z', strtotime($event['start_datetime']));
$dtEnd = gmdate('Ymd\THis\Z', strtotime($event['end_datetime'] ?: $event['start_datetime'] . ' +1 hour'));
$summary = str_replace(["\n","\r"], ' ', $event['title']);
$location = str_replace(["\n","\r"], ' ', $event['location']);
$desc = strip_tags($event['description']);
$desc = preg_replace('/\s+/', ' ', $desc);

echo "BEGIN:VCALENDAR\r\n";
echo "VERSION:2.0\r\n";
echo "PRODID:-//MyOMR//Events//EN\r\n";
echo "BEGIN:VEVENT\r\n";
echo "UID:$uid\r\n";
echo "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
echo "DTSTART:$dtStart\r\n";
echo "DTEND:$dtEnd\r\n";
echo "SUMMARY:" . addcslashes($summary, ",;\\") . "\r\n";
echo "LOCATION:" . addcslashes($location, ",;\\") . "\r\n";
echo "DESCRIPTION:" . addcslashes($desc, ",;\\") . "\r\n";
echo "END:VEVENT\r\n";
echo "END:VCALENDAR\r\n";


