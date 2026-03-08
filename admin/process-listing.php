<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_type = $_POST["service_type"];
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $details = $_POST["details"];

    $data = "Name: " . $name . "\nPhone: " . $phone . "\nDetails: " . $details . "\n--------------------\n";
    
    $file_path = __DIR__ . "/" . $service_type . "_listings.txt";
    file_put_contents($file_path, $data, FILE_APPEND);

    echo "<h2>Thank you, your submission has been recorded!</h2>";
}
?>
