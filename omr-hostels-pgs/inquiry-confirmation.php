<?php
/**
 * Inquiry Confirmation Success Page
 * Displayed after user submits an inquiry
 */

// Enable error reporting
require_once __DIR__ . '/includes/error-reporting.php';

require_once __DIR__ . '/../core/omr-connect.php';
require_once __DIR__ . '/includes/property-functions.php';

global $conn;

// Get property ID
$property_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$property = null;
if ($property_id > 0) {
    $property = getPropertyById($property_id);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Sent Successfully - MyOMR Hostels & PGs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/hostels-pgs.css">
    <?php include '../components/analytics.php'; ?>
</head>
<body class="modern-page">

<?php require_once __DIR__ . '/../components/main-nav.php'; ?>

<div class="hero-modern">
    <div class="container text-center text-white">
        <div class="mb-3">
            <div class="form-section-icon" style="margin: 0 auto;">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <h1 class="hero-modern-title">Inquiry Sent Successfully!</h1>
        <p class="hero-modern-subtitle">The property owner will contact you soon</p>
    </div>
</div>

<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-modern">
                    <div class="p-5 text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle fa-5x text-success"></i>
                        </div>
                        
                        <h2 class="h3 mb-3">Thank You for Your Interest!</h2>
                        <p class="lead mb-4">
                            Your inquiry has been sent to the property owner. They will contact you via email or phone within 24-48 hours to discuss details and arrange a visit.
                        </p>
                        
                        <?php if ($property): ?>
                            <div class="alert alert-info mb-4">
                                <h5 class="alert-heading">
                                    <i class="fas fa-home me-2"></i>
                                    <?php echo htmlspecialchars($property['property_name']); ?>
                                </h5>
                                <p class="mb-2">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    <?php echo htmlspecialchars($property['locality']); ?>
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-rupee-sign me-2"></i>
                                    Starting from ₹<?php echo number_format($property['monthly_rent_single'] ?? 0); ?>/month
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="info-box mb-4">
                            <h5 class="mb-3"><i class="fas fa-lightbulb me-2"></i>What Happens Next?</h5>
                            <ol class="text-start mb-0">
                                <li>The owner will receive your inquiry details</li>
                                <li>They will contact you to discuss the property</li>
                                <li>Arrange a time to visit the property in person</li>
                                <li>Verify all amenities and facilities during your visit</li>
                                <li>Make an informed decision</li>
                            </ol>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <?php if ($property): ?>
                                <a href="property-detail.php?id=<?php echo $property_id; ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Property
                                </a>
                            <?php endif; ?>
                            <a href="index.php" class="btn btn-success">
                                <i class="fas fa-search me-2"></i>Browse More Properties
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function() {
  if (typeof gtag !== 'function') return;
  gtag('event', 'generate_lead', {
    'conversion_type': 'pg_inquiry',
    'property_name':   <?= json_encode(isset($property) ? $property['property_name'] : '') ?>,
    'locality':        <?= json_encode(isset($property) ? $property['locality'] : '') ?>,
    'property_id':     <?= (int)$property_id ?>
  });
})();
</script>

</body>
</html>

