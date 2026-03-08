<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}
include_once '../core/omr-connect.php';
$title = 'Add Event';
$breadcrumbs = ['Events' => 'events-list.php', 'Add Event' => null];
$msg = '';
$errors = [];
$title_val = $date_val = $location_val = $desc_val = $image_val = $link_val = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title_val = trim($_POST['event_title']);
    $date_val = trim($_POST['event_date']);
    $location_val = trim($_POST['event_location']);
    $desc_val = trim($_POST['event_description']);
    $image_val = trim($_POST['event_image']);
    $link_val = trim($_POST['event_link']);
    if ($title_val === '') $errors[] = 'Event title is required.';
    if ($date_val === '') $errors[] = 'Event date is required.';
    if (empty($errors)) {
        $title_sql = $conn->real_escape_string($title_val);
        $date_sql = $conn->real_escape_string($date_val);
        $location_sql = $conn->real_escape_string($location_val);
        $desc_sql = $conn->real_escape_string($desc_val);
        $image_sql = $conn->real_escape_string($image_val);
        $link_sql = $conn->real_escape_string($link_val);
        $sql = "INSERT INTO events (title, event_date, location, description, image, link, status) VALUES ('$title_sql', '$date_sql', '$location_sql', '$desc_sql', '$image_sql', '$link_sql', 'published')";
        if ($conn->query($sql)) {
            $_SESSION['flash_success'] = 'Event added successfully!';
            header('Location: events-list.php');
            exit;
        } else {
            $msg = 'Error: ' . $conn->error;
        }
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event (Admin) - MyOMR</title>
    <link rel="stylesheet" href="../events/Omr-Road-Events-and-Happenings.css">  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>
<body style="background: #f4f6f8;">
<div class="container-fluid">
  <div class="row">
    <?php include 'admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content" aria-label="Main content">
      <?php include 'admin-header.php'; ?>
      <?php include 'admin-breadcrumbs.php'; ?>
      <?php include 'admin-flash.php'; ?>
      <h1 class="mb-4" style="font-family: 'Playfair Display', serif; color: #008552;">Add Event (Admin)</h1>
      <?php if ($msg): ?><div class="alert alert-danger"> <?php echo $msg; ?> </div><?php endif; ?>
      <?php if (!empty($errors)): ?>
          <div class="alert alert-danger">
              <ul>
                  <?php foreach ($errors as $error): ?>
                      <li><?php echo htmlspecialchars($error); ?></li>
                  <?php endforeach; ?>
              </ul>
          </div>
      <?php endif; ?>
      <form method="post" action="" aria-label="Add Event">
          <div class="form-group">
              <label for="event_title">Event Title:</label>
              <input type="text" id="event_title" name="event_title" class="form-control" value="<?php echo htmlspecialchars($title_val); ?>" required aria-required="true">
          </div>
          <div class="form-group">
              <label for="event_date">Date:</label>
              <input type="date" id="event_date" name="event_date" class="form-control" value="<?php echo htmlspecialchars($date_val); ?>" required aria-required="true">
          </div>
          <div class="form-group">
              <label for="event_location">Location:</label>
              <input type="text" id="event_location" name="event_location" class="form-control" value="<?php echo htmlspecialchars($location_val); ?>">
          </div>
          <div class="form-group">
              <label for="event_image">Image URL:</label>
              <input type="text" id="event_image" name="event_image" class="form-control" value="<?php echo htmlspecialchars($image_val); ?>">
          </div>
          <div class="form-group">
              <label for="event_link">External Link:</label>
              <input type="text" id="event_link" name="event_link" class="form-control" value="<?php echo htmlspecialchars($link_val); ?>">
          </div>
          <div class="form-group">
              <label for="event_description">Description:</label>
              <textarea id="event_description" name="event_description" class="form-control" rows="4"><?php echo htmlspecialchars($desc_val); ?></textarea>
          </div>
          <button type="submit" class="btn btn-success">Add Event</button>
          <a href="events-list.php" class="btn btn-secondary ml-2">Back to Events List</a>
      </form>
    </main>
  </div>
</div>
</body>
</html> 