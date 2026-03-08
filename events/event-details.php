<?php
include_once '../core/omr-connect.php';
$page_title = 'Event Details | MyOMR';
$page_description = 'View details for this event on MyOMR.';
$event = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM events WHERE id=$id AND (status='published' OR status='approved') LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $event = $result->fetch_assoc();
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-success">
                <?php if ($event && !empty($event['image'])): ?>
                    <img src="<?php echo htmlspecialchars($event['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($event['title']); ?>">
                <?php endif; ?>
                <div class="card-body">
                    <?php if ($event): ?>
                        <h2 class="card-title mb-3" style="font-family: 'Playfair Display', serif; color: #008552;"> <?php echo htmlspecialchars($event['title']); ?> </h2>
                        <p class="card-text mb-1"><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
                        <p class="card-text mb-1"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                        <p class="card-text mt-3"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                        <?php if (!empty($event['link'])): ?>
                            <a href="<?php echo htmlspecialchars($event['link']); ?>" class="btn btn-outline-success mt-3" target="_blank">Learn More</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <p>Event not found.</p>
                    <?php endif; ?>
                    <a href="index.php" class="btn btn-secondary mt-3">Back to Events</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../components/footer.php'; ?>
</body>
</html> 