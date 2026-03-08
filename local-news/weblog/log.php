<?php
$ipaddress = $_SERVER['REMOTE_ADDR'];
$page = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}"; 
$referrer = $_SERVER['HTTP_REFERER'];
$date = date('Y-m-d H:i:s');
$useragent = $_SERVER['HTTP_USER_AGENT'];
$remotehost = @getHostByAddr($ipaddress);

// echo $ipaddress;
// echo "<br>";
// echo $page;
// echo "<br>";
// echo $useragent;
// echo "<br>";
// echo $referrer;
// echo "<br>";
// echo $date;
// echo "<br>";
// echo $remotehost;

// Create log line
$logline = $ipaddress . '|' . $referrer . '|' . $date . '|' . $useragent . '|' . $remotehost . '|' . $page . "\n";

// echo $logline;

// // Write to log file:
$logfile = 'weblog/logfile.txt';

// // Open the log file in "Append" mode
if (!$handle = fopen($logfile, 'a+')) {
    die("Failed to open log file");
}
// // Write $logline to our logfile.
if (fwrite($handle, $logline) === FALSE) {
    die("Failed to write to log file");
}
  
fclose($handle);
?>