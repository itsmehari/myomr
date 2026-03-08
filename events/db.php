<?php
// Database connection for Events module
$host = 'localhost';
$db   = 'myomr_db'; // Change to your DB name
$user = 'myomr_user'; // Change to your DB user
$pass = 'your_password'; // Change to your DB password

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?> 