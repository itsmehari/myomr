<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Store email in a text file (or use a database)
        file_put_contents("subscribers.txt", $email . "\n", FILE_APPEND);
        
        // Redirect back with success message
        header("Location: thank-you.html");
        exit();
    } else {
        // Redirect back with error
        header("Location: index.html?error=invalid_email");
        exit();
    }
}
?>
