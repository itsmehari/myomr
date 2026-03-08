<?php
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Events – Calendar & Export</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4 mb-0">Calendar & Export</h1>
      <a href="/admin/" class="btn btn-outline-secondary">Admin Home</a>
    </div>
    <div class="card shadow-sm">
      <div class="card-body">
        <p>Download upcoming events for publishing on calendars or spreadsheets.</p>
        <div class="d-flex gap-2 flex-wrap">
          <a class="btn btn-primary" href="export-events-ics.php">Download ICS (all upcoming)</a>
          <a class="btn btn-outline-primary" href="export-events-csv.php">Download CSV (all upcoming)</a>
          <a class="btn btn-outline-secondary" href="view-listings.php">Back to Listings</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>


