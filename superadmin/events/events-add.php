<?php
require_once __DIR__ . '/../_bootstrap.php';
include_once __DIR__ . '/../../core/omr-connect.php';
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
        $stmt = $conn->prepare("INSERT INTO events (title, event_date, location, description, image, link, status) VALUES (?, ?, ?, ?, ?, ?, 'published')");
        $stmt->bind_param('ssssss', $title_val, $date_val, $location_val, $desc_val, $image_val, $link_val);
        if ($stmt->execute()) {
            $_SESSION['flash_success'] = 'Event added successfully!';
            header('Location: events-list.php');
            exit;
        } else {
            $msg = 'Error: ' . $conn->error;
        }
    }
}

$pageTitle = $pageTitle ?? $title ?? 'Add Event';
include __DIR__ . '/../includes/admin-shell-open.php';
?>
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
          <a href="events-list.php" class="btn btn-secondary ms-2">Back to Events List</a>
      </form>
<?php include __DIR__ . '/../includes/admin-shell-close.php'; ?>
