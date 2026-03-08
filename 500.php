<?php
/**
 * MyOMR — Custom 500 Page
 * Displayed when an internal server error occurs.
 */
http_response_code(500);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Server Error | MyOMR</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <style>
    body { font-family: 'Poppins', sans-serif; background: #f8f9fa; }
    .error-box { max-width: 560px; margin: 80px auto; text-align: center; padding: 2rem; }
    .error-code { font-size: 5rem; font-weight: 700; color: #dc3545; }
  </style>
</head>
<body>
  <div class="error-box">
    <div class="error-code">500</div>
    <h1 class="h3 mb-3">Something went wrong</h1>
    <p class="text-muted mb-4">We're having a temporary issue. Please try again in a moment.</p>
    <a href="/" class="btn btn-primary px-4">Go to Homepage</a>
  </div>
</body>
</html>
