<?php
require_once __DIR__ . '/_bootstrap.php';
require_once '../core/omr-connect.php';
$title = 'Edit Restaurant';
$breadcrumbs = ['Restaurants' => 'restaurants-list.php', 'Edit Restaurant' => null];
$error = '';
$success = '';
$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: restaurants-list.php');
    exit;
}

// Fetch current data
$stmt = $conn->prepare('SELECT *, ST_X(geolocation) AS lon, ST_Y(geolocation) AS lat FROM omr_restaurants WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$restaurant = $result->fetch_assoc();
$stmt->close();
if (!$restaurant) {
    $error = 'Restaurant not found.';
}

// Set default values
$name_val = $restaurant['name'] ?? '';
$address_val = $restaurant['address'] ?? '';
$locality_val = $restaurant['locality'] ?? '';
$cuisine_val = $restaurant['cuisine'] ?? '';
$cost_for_two_val = $restaurant['cost_for_two'] ?? 0;
$rating_val = $restaurant['rating'] ?? 0.0;
$availability_val = $restaurant['availability'] ?? '';
$accessibility_val = $restaurant['accessibility'] ?? '';
$reviews_val = $restaurant['reviews'] ?? '';
$imagelocation_val = $restaurant['imagelocation'] ?? '';
$latitude_val = $restaurant['lat'] ?? 0.0;
$longitude_val = $restaurant['lon'] ?? 0.0;

// Handle update
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
                // Delete old image if it exists
                if ($imagelocation_val && file_exists($imagelocation_val)) {
                    unlink($imagelocation_val);
                }
                $imagelocation_val = $target_file;
            } else {
                $error = 'Error uploading image.';
            }
        }
    }

    if (!$error && $name_val && $address_val && $locality_val && $cuisine_val && $cost_for_two_val && $rating_val && $availability_val && $imagelocation_val) {
        $stmt = $conn->prepare('UPDATE omr_restaurants SET name=?, address=?, locality=?, cuisine=?, cost_for_two=?, rating=?, availability=?, accessibility=?, reviews=?, imagelocation=?, geolocation=ST_GeomFromText(?) WHERE id=?');
        $point = "POINT($longitude_val $latitude_val)";
        $stmt->bind_param('ssssidsssssi', $name_val, $address_val, $locality_val, $cuisine_val, $cost_for_two_val, $rating_val, $availability_val, $accessibility_val, $reviews_val, $imagelocation_val, $point, $id);
        if ($stmt->execute()) {
            $_SESSION['flash_success'] = 'Restaurant updated successfully!';
            header('Location: restaurants-list.php');
            exit;
        } else {
            $error = 'Error updating restaurant: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        if (!$error) $error = 'Please fill in all required fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Restaurant - MyOMR CMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f6f8; }
        .sidebar { min-height: 100vh; background: #14532d; color: #fff; }
        .sidebar a { color: #fff; display: block; padding: 1rem; border-bottom: 1px solid #1e6b3a; text-decoration: none; }
        .sidebar a.active, .sidebar a:hover { background: #22c55e; color: #14532d; font-weight: bold; }
        .main-content { padding: 2rem; }
    </style>
    <script>
      window.onerror = function(msg, url, line, col, error) {
        alert('Error: ' + msg + '\n' + url + ':' + line + ':' + col);
        return false;
      };
    </script>
</head>
<body style="background: #f4f6f8;">
<div class="container-fluid">
  <div class="row">
    <?php include 'admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content" aria-label="Main content">
      <?php include 'admin-header.php'; ?>
      <?php include 'admin-breadcrumbs.php'; ?>
      <?php include 'admin-flash.php'; ?>
      <h2 class="mb-4">Edit Restaurant</h2>
      <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
      <?php if ($restaurant): ?>
      <form method="POST" enctype="multipart/form-data" autocomplete="off" aria-label="Edit Restaurant">
        <div class="form-group">
          <label for="name">Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name_val); ?>" required aria-required="true">
        </div>
        <div class="form-group">
          <label for="address">Address <span class="text-danger">*</span></label>
          <textarea class="form-control" id="address" name="address" rows="3" required aria-required="true"><?php echo htmlspecialchars($address_val); ?></textarea>
        </div>
        <div class="form-group">
          <label for="locality">Locality <span class="text-danger">*</span></label>
          <select class="form-control" id="locality" name="locality" required aria-required="true">
            <option value="">Select Locality</option>
            <option value="Thuraipakkam" <?php echo $locality_val == 'Thuraipakkam' ? 'selected' : ''; ?>>Thuraipakkam</option>
            <option value="Perungudi" <?php echo $locality_val == 'Perungudi' ? 'selected' : ''; ?>>Perungudi</option>
            <option value="Sholinganallur" <?php echo $locality_val == 'Sholinganallur' ? 'selected' : ''; ?>>Sholinganallur</option>
            <option value="Navalur" <?php echo $locality_val == 'Navalur' ? 'selected' : ''; ?>>Navalur</option>
            <option value="Karapakkam" <?php echo $locality_val == 'Karapakkam' ? 'selected' : ''; ?>>Karapakkam</option>
            <option value="SRP Tools" <?php echo $locality_val == 'SRP Tools' ? 'selected' : ''; ?>>SRP Tools</option>
          </select>
        </div>
        <div class="form-group">
          <label for="cuisine">Cuisine <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="cuisine" name="cuisine" value="<?php echo htmlspecialchars($cuisine_val); ?>" required aria-required="true">
        </div>
        <div class="form-group">
          <label for="cost_for_two">Cost for Two (₹) <span class="text-danger">*</span></label>
          <input type="number" class="form-control" id="cost_for_two" name="cost_for_two" value="<?php echo htmlspecialchars($cost_for_two_val); ?>" required aria-required="true" min="0">
        </div>
        <div class="form-group">
          <label for="rating">Rating <span class="text-danger">*</span></label>
          <input type="number" step="0.1" class="form-control" id="rating" name="rating" value="<?php echo htmlspecialchars($rating_val); ?>" required aria-required="true" min="0" max="5">
        </div>
        <div class="form-group">
          <label for="availability">Availability <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="availability" name="availability" value="<?php echo htmlspecialchars($availability_val); ?>" required aria-required="true">
        </div>
        <div class="form-group">
          <label for="accessibility">Accessibility</label>
          <input type="text" class="form-control" id="accessibility" name="accessibility" value="<?php echo htmlspecialchars($accessibility_val); ?>">
        </div>
        <div class="form-group">
          <label for="reviews">Reviews</label>
          <textarea class="form-control" id="reviews" name="reviews" rows="3"><?php echo htmlspecialchars($reviews_val); ?></textarea>
        </div>
        <div class="form-group">
          <label for="image">Upload New Image (optional)</label>
          <input type="file" class="form-control" id="image" name="image" accept="image/*">
          <small class="form-text text-muted">Current image: <a href="<?php echo htmlspecialchars($imagelocation_val); ?>" target="_blank">View Image</a></small>
        </div>
        <div class="form-group">
          <label for="latitude">Latitude <span class="text-danger">*</span></label>
          <input type="number" step="any" class="form-control" id="latitude" name="latitude" value="<?php echo htmlspecialchars($latitude_val); ?>" required aria-required="true">
        </div>
        <div class="form-group">
          <label for="longitude">Longitude <span class="text-danger">*</span></label>
          <input type="number" step="any" class="form-control" id="longitude" name="longitude" value="<?php echo htmlspecialchars($longitude_val); ?>" required aria-required="true">
        </div>
        <button type="submit" class="btn btn-success">Update Restaurant</button>
        <a href="restaurants-list.php" class="btn btn-secondary ml-2">Back to List</a>
      </form>
      <?php endif; ?>
    </main>
  </div>
</div>
</body>
</html>
<?php $conn->close(); ?>