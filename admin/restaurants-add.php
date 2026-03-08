<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}
require_once '../core/omr-connect.php';
$title = 'Add Restaurant';
$breadcrumbs = ['Restaurants' => 'restaurants-list.php', 'Add Restaurant' => null];
$error = '';
$success = '';
$name_val = $address_val = $locality_val = $cuisine_val = $cost_for_two_val = $rating_val = $availability_val = $accessibility_val = $reviews_val = $imagelocation_val = $latitude_val = $longitude_val = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name_val = trim($_POST['name'] ?? '');
    $address_val = trim($_POST['address'] ?? '');
    $locality_val = trim($_POST['locality'] ?? '');
    $cuisine_val = trim($_POST['cuisine'] ?? '');
    $cost_for_two_val = (int)($_POST['cost_for_two'] ?? 0);
    $rating_val = (float)($_POST['rating'] ?? 0.0);
    $availability_val = trim($_POST['availability'] ?? '');
    $accessibility_val = trim($_POST['accessibility'] ?? '');
    $reviews_val = trim($_POST['reviews'] ?? '');
    $latitude_val = (float)($_POST['latitude'] ?? 0.0);
    $longitude_val = (float)($_POST['longitude'] ?? 0.0);

    // Handle image upload
    $imagelocation_val = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/restaurants/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $file_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_type, $allowed_types)) {
            $error = 'Only JPG, JPEG, PNG, and GIF files are allowed.';
        } elseif ($_FILES["image"]["size"] > 5000000) { // 5MB limit
            $error = 'Image file size must be less than 5MB.';
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $imagelocation_val = $target_file;
            } else {
                $error = 'Error uploading image.';
            }
        }
    } else {
        $error = 'Please upload an image.';
    }

    if (!$error && $name_val && $address_val && $locality_val && $cuisine_val && $cost_for_two_val && $rating_val && $availability_val && $imagelocation_val) {
        $stmt = $conn->prepare('INSERT INTO omr_restaurants (name, address, locality, cuisine, cost_for_two, rating, availability, accessibility, reviews, imagelocation, geolocation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ST_GeomFromText(?))');
        $point = "POINT($longitude_val $latitude_val)";
        $stmt->bind_param('ssssidsssss', $name_val, $address_val, $locality_val, $cuisine_val, $cost_for_two_val, $rating_val, $availability_val, $accessibility_val, $reviews_val, $imagelocation_val, $point);
        if ($stmt->execute()) {
            $_SESSION['flash_success'] = 'Restaurant added successfully!';
            header('Location: restaurants-list.php');
            exit;
        } else {
            $error = 'Error adding restaurant: ' . $stmt->error;
        }
        $stmt->close();
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - MyOMR</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background: #f4f6f8;">
<div class="container-fluid">
  <div class="row">
    <?php include 'admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content" aria-label="Main content">
      <?php include 'admin-header.php'; ?>
      <?php include 'admin-breadcrumbs.php'; ?>
      <?php include 'admin-flash.php'; ?>
      <div class="container">
        <h1><?php echo $title; ?></h1>
        <form method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name_val; ?>" required>
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo $address_val; ?>" required>
          </div>
          <div class="form-group">
            <label for="locality">Locality</label>
            <input type="text" class="form-control" id="locality" name="locality" value="<?php echo $locality_val; ?>" required>
          </div>
          <div class="form-group">
            <label for="cuisine">Cuisine</label>
            <input type="text" class="form-control" id="cuisine" name="cuisine" value="<?php echo $cuisine_val; ?>" required>
          </div>
          <div class="form-group">
            <label for="cost_for_two">Cost for Two</label>
            <input type="number" class="form-control" id="cost_for_two" name="cost_for_two" value="<?php echo $cost_for_two_val; ?>" required>
          </div>
          <div class="form-group">
            <label for="rating">Rating</label>
            <input type="number" step="0.01" class="form-control" id="rating" name="rating" value="<?php echo $rating_val; ?>" required>
          </div>
          <div class="form-group">
            <label for="availability">Availability</label>
            <input type="text" class="form-control" id="availability" name="availability" value="<?php echo $availability_val; ?>" required>
          </div>
          <div class="form-group">
            <label for="accessibility">Accessibility</label>
            <input type="text" class="form-control" id="accessibility" name="accessibility" value="<?php echo $accessibility_val; ?>" required>
          </div>
          <div class="form-group">
            <label for="reviews">Reviews</label>
            <textarea class="form-control" id="reviews" name="reviews" rows="3" required><?php echo $reviews_val; ?></textarea>
          </div>
          <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
          </div>
          <div class="form-group">
            <label for="latitude">Latitude</label>
            <input type="number" step="0.000001" class="form-control" id="latitude" name="latitude" value="<?php echo $latitude_val; ?>" required>
          </div>
          <div class="form-group">
            <label for="longitude">Longitude</label>
            <input type="number" step="0.000001" class="form-control" id="longitude" name="longitude" value="<?php echo $longitude_val; ?>" required>
          </div>
          <button type="submit" class="btn btn-primary">Add Restaurant</button>
        </form>
      </div>
    </main>
  </div>
</div>
</body>
</html>