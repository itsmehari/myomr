<?php
include_once '../core/omr-connect.php';
$page_title = 'Submit an Event | MyOMR';
$page_description = 'Submit your event to be featured on MyOMR. Share your community happenings with OMR residents.';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $date = $conn->real_escape_string($_POST['date']);
    $location = $conn->real_escape_string($_POST['location']);
    $desc = $conn->real_escape_string($_POST['description']);
    $sql = "INSERT INTO events (title, event_date, location, description, status) VALUES ('$title', '$date', '$location', '$desc', 'pending')";
    if ($conn->query($sql)) {
        $msg = 'Thank you! Your event has been submitted for review.';
    } else {
        $msg = 'Error: ' . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include '../components/meta.php'; ?>
<?php include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
<link rel="stylesheet" href="Omr-Road-Events-and-Happenings.css">
</head>
<body>
<?php include '../components/navbar.php'; ?>
<div class="container py-5">
    <h1 class="text-center mb-4" style="font-family: 'Playfair Display', serif; color: #008552;">Submit an Event</h1>
    <?php if ($msg): ?><div class="alert alert-info text-center"> <?php echo $msg; ?> </div><?php endif; ?>
    <form method="post" action="" class="mx-auto" style="max-width: 500px;">
        <div class="form-group">
            <label>Event Title:</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Date:</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Location:</label>
            <input type="text" name="location" class="form-control">
        </div>
        <div class="form-group">
            <label>Description:</label>
            <textarea name="description" rows="4" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success btn-block">Submit</button>
    </form>
    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-link">Back to Events</a>
    </div>
</div>
<?php include '../components/footer.php'; ?>
</body>
</html> 