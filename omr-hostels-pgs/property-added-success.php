<?php
/**
 * Property Added Success Page
 */

require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/owner-auth.php';
requireOwnerAuth();

$property_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listed Successfully - MyOMR</title>
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
        <h1 class="hero-modern-title">Property Listed Successfully!</h1>
        <p class="hero-modern-subtitle">Your property is under review</p>
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
                        
                        <h2 class="h3 mb-3">Thank You!</h2>
                        <p class="lead mb-4">
                            Your property listing has been submitted successfully and is now under review by our team.
                        </p>
                        
                        <div class="alert alert-info mb-4">
                            <h5 class="alert-heading"><i class="fas fa-clock me-2"></i>What Happens Next?</h5>
                            <ol class="text-start mb-0">
                                <li>Our team will review your listing within 24-48 hours</li>
                                <li>We'll verify the property details and information</li>
                                <li>You'll receive an email once your listing is approved</li>
                                <li>Your property will go live on MyOMR and start receiving inquiries</li>
                            </ol>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="my-properties.php" class="btn btn-success">
                                <i class="fas fa-home me-2"></i>View My Properties
                            </a>
                            <a href="add-property.php" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-2"></i>Add Another Property
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
  gtag('event', 'property_listed', {
    'property_id': <?= (int)$property_id ?>
  });
})();
</script>

</body>
</html>

