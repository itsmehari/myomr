<?php
/**
 * Tamil Nadu Assembly election 2026 – key dates as ICS (Add to calendar).
 * Poll: 23 Apr 2026; Counting: 4 May 2026.
 */
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="tn-election-2026-dates.ics"');

$poll_start = '20260423';
$poll_end   = '20260424';
$count_start = '20260504';
$count_end   = '20260505';

echo "BEGIN:VCALENDAR\r\n";
echo "VERSION:2.0\r\n";
echo "PRODID:-//MyOMR//Elections 2026//EN\r\n";
echo "CALSCALE:GREGORIAN\r\n";
echo "BEGIN:VEVENT\r\n";
echo "UID:tn-election-2026-poll@myomr.in\r\n";
echo "DTSTAMP:20260315T000000Z\r\n";
echo "DTSTART;VALUE=DATE:{$poll_start}\r\n";
echo "DTEND;VALUE=DATE:{$poll_end}\r\n";
echo "SUMMARY:Tamil Nadu Assembly Election 2026 – Poll Day\r\n";
echo "DESCRIPTION:Voting for all 234 assembly constituencies. OMR: Sholinganallur, Velachery, Thiruporur. https://myomr.in/elections-2026/\r\n";
echo "END:VEVENT\r\n";
echo "BEGIN:VEVENT\r\n";
echo "UID:tn-election-2026-counting@myomr.in\r\n";
echo "DTSTAMP:20260315T000000Z\r\n";
echo "DTSTART;VALUE=DATE:{$count_start}\r\n";
echo "DTEND;VALUE=DATE:{$count_end}\r\n";
echo "SUMMARY:Tamil Nadu Assembly Election 2026 – Counting\r\n";
echo "DESCRIPTION:Results declared. https://myomr.in/elections-2026/\r\n";
echo "END:VEVENT\r\n";
echo "END:VCALENDAR\r\n";
