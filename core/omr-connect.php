<?php
$servername = "localhost:3306";
$username = "metap8ok_myomr_admin";
$password = "myomr@123";
$database = "metap8ok_myomr";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    // Log error
    error_log("Database connection failed: " . $conn->connect_error);
    // Sitemap requests must return valid XML so Google doesn't report "Couldn't fetch"
    if (defined('SITEMAP_REQUEST') && SITEMAP_REQUEST) {
        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>';
        exit;
    }
    // Show error if in development mode
    if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) {
        die("Database Connection Failed: " . htmlspecialchars($conn->connect_error) . 
            "<br><br>Please check:<br>" .
            "- Database server is running<br>" .
            "- Credentials in core/omr-connect.php are correct<br>" .
            "- Database name exists<br>");
    } else {
        die("Database connection failed. Please contact administrator.");
    }
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");
?>