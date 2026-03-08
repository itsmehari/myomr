<?php
include_once '../core/omr-connect.php';
$page_title = 'Events & Happenings | MyOMR';
$page_description = 'Discover upcoming events, activities, and happenings along OMR, Chennai. Stay updated with the latest community events at MyOMR.in.';
// Set canonical URL for events page
$canonical_url = 'https://myomr.in/events/index.php';
$events = [];
$sql = "SELECT id, title, description, event_date, location, image, link FROM events WHERE status='published' OR status='approved' ORDER BY event_date ASC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
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
    <h1 class="text-center mb-5" style="font-family: 'Playfair Display', serif; color: #008552;">Events & Happenings</h1>
    <div class="row">
        <?php if (count($events) > 0): ?>
            <?php foreach ($events as $event): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-success">
                        <?php if (!empty($event['image'])): ?>
                            <img src="<?php echo htmlspecialchars($event['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($event['title']); ?>">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title" style="font-family: 'Playfair Display', serif; color: #008552;">
                                <?php echo htmlspecialchars($event['title']); ?>
                            </h5>
                            <p class="card-text mb-1"><strong>Date:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($event['event_date']))); ?></p>
                            <p class="card-text mb-1"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                            <p class="card-text mt-2" style="font-size: 0.97rem; color: #444;">
                                <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                            </p>
                            <div class="mt-auto">
                                <?php if (!empty($event['link'])): ?>
                                    <a href="<?php echo htmlspecialchars($event['link']); ?>" class="btn btn-outline-success btn-block mt-2" target="_blank">Learn More</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p>No upcoming events at the moment. Please check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
    <div class="text-center mt-4">
        <a href="submit-event.php" class="btn btn-primary">Submit an Event</a>
    </div>
</div>
<?php include '../components/footer.php'; ?>
</body>
</html> 