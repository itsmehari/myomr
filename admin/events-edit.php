<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}
include_once '../core/omr-connect.php';
$title = 'Edit Event';
$breadcrumbs = ['Events' => 'events-list.php', 'Edit Event' => null];
$msg = '';
$errors = [];
$event = null;
$title_val = $date_val = $location_val = $desc_val = $image_val = $link_val = $status_val = '';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title_val = trim($_POST['event_title']);
        $date_val = trim($_POST['event_date']);
        $location_val = trim($_POST['event_location']);
        $desc_val = trim($_POST['event_description']);
        $image_val = trim($_POST['event_image']);
        $link_val = trim($_POST['event_link']);
        $status_val = trim($_POST['event_status']);
        if ($title_val === '') $errors[] = 'Event title is required.';
        if ($date_val === '') $errors[] = 'Event date is required.';
        if (empty($errors)) {
            $title_sql = $conn->real_escape_string($title_val);
            $date_sql = $conn->real_escape_string($date_val);
            $location_sql = $conn->real_escape_string($location_val);
            $desc_sql = $conn->real_escape_string($desc_val);
            $image_sql = $conn->real_escape_string($image_val);
            $link_sql = $conn->real_escape_string($link_val);
            $status_sql = $conn->real_escape_string($status_val);
            $sql = "UPDATE events SET title='$title_sql', event_date='$date_sql', location='$location_sql', description='$desc_sql', image='$image_sql', link='$link_sql', status='$status_sql' WHERE id=$id";
            if ($conn->query($sql)) {
                $_SESSION['flash_success'] = 'Event updated successfully!';
                header('Location: events-list.php');
                exit;
            } else {
                $msg = 'Error: ' . $conn->error;
            }
        }
    } else {
        $result = $conn->query("SELECT * FROM events WHERE id=$id LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $event = $result->fetch_assoc();
            $title_val = $event['title'];
            $date_val = $event['event_date'];
            $location_val = $event['location'];
            $desc_val = $event['description'];
            $image_val = $event['image'];
            $link_val = $event['link'];
            $status_val = $event['status'];
        }
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event (Admin) - MyOMR</title>
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
      <h1 class="mb-4" style="font-family: 'Playfair Display', serif; color: #008552;">Edit Event (Admin)</h1>
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
      <?php if (isset($id) && ($title_val || $date_val)): ?>
      <form method="post" action="" aria-label="Edit Event">
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
          <div class="form-group">
              <label for="event_status">Status:</label>
              <select id="event_status" name="event_status" class="form-control">
                  <option value="pending" <?php if($status_val==='pending') echo 'selected'; ?>>Pending</option>
                  <option value="approved" <?php if($status_val==='approved') echo 'selected'; ?>>Approved</option>
                  <option value="published" <?php if($status_val==='published') echo 'selected'; ?>>Published</option>
                  <option value="rejected" <?php if($status_val==='rejected') echo 'selected'; ?>>Rejected</option>
              </select>
          </div>
          <button type="submit" class="btn btn-success">Save Changes</button>
          <a href="events-list.php" class="btn btn-secondary ml-2">Back to Events List</a>
      </form>
      <?php else: ?>
          <p>Event not found.</p>
      <?php endif; ?>
    </main>
  </div>
</div>
</body>
</html> 