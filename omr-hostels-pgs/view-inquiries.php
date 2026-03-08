<?php
/**
 * View Inquiries
 * Property owners can view and manage booking inquiries
 */

require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/owner-auth.php';
requireOwnerAuth();

require_once __DIR__ . '/../core/omr-connect.php';
global $conn;

$owner_id = (int)($_SESSION['owner_id'] ?? 0);
$property_id = isset($_GET['property_id']) ? intval($_GET['property_id']) : 0;

// Get inquiries
$inquiries = [];

if ($property_id > 0) {
    // Inquiries for specific property
    $query = "SELECT i.*, h.property_name, h.locality
              FROM property_inquiries i
              INNER JOIN hostels_pgs h ON i.property_id = h.id
              WHERE i.property_id = ? AND h.owner_id = ?
              ORDER BY i.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $property_id, $owner_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $inquiries = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    // All inquiries for all owner's properties
    $query = "SELECT i.*, h.property_name, h.locality
              FROM property_inquiries i
              INNER JOIN hostels_pgs h ON i.property_id = h.id
              WHERE h.owner_id = ?
              ORDER BY i.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $owner_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $inquiries = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Count by status
$status_counts = [
    'new' => 0,
    'contacted' => 0,
    'resolved' => 0,
    'archived' => 0
];
foreach ($inquiries as $inquiry) {
    $status = strtolower($inquiry['status'] ?? 'new');
    if (isset($status_counts[$status])) {
        $status_counts[$status]++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inquiries - MyOMR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/hostels-pgs.css">
    <style>
        .inquiry-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        .inquiry-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .applicant-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #008552 0%, #006d42 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
        }
    </style>
    <?php include '../components/analytics.php'; ?>
</head>
<body class="modern-page">

<?php require_once __DIR__ . '/../components/main-nav.php'; ?>

<section class="hero-modern py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center text-white">
            <div>
                <h1 class="hero-modern-title mb-2">Property Inquiries</h1>
                <p class="hero-modern-subtitle mb-0">Manage booking inquiries from potential tenants</p>
            </div>
            <a href="my-properties.php" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>
</section>

<main class="py-5">
    <div class="container">
        
        <!-- Stats -->
        <?php if (!empty($inquiries)): ?>
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="card-modern p-3 text-center">
                    <div class="h2 mb-1 text-primary"><?php echo $status_counts['new']; ?></div>
                    <small class="text-muted">New Inquiries</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card-modern p-3 text-center">
                    <div class="h2 mb-1 text-info"><?php echo $status_counts['contacted']; ?></div>
                    <small class="text-muted">Contacted</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card-modern p-3 text-center">
                    <div class="h2 mb-1 text-success"><?php echo $status_counts['resolved']; ?></div>
                    <small class="text-muted">Resolved</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card-modern p-3 text-center">
                    <div class="h2 mb-1 text-secondary"><?php echo count($inquiries); ?></div>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (empty($inquiries)): ?>
            <div class="card-modern p-5 text-center">
                <div class="mb-3">
                    <i class="fas fa-inbox fa-3x text-muted"></i>
                </div>
                <h2 class="h4 mb-2">No Inquiries Yet</h2>
                <p class="text-muted mb-4">When people express interest in your properties, their inquiries will appear here.</p>
                <a href="my-properties.php" class="btn btn-primary">Back to My Properties</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($inquiries as $inquiry): ?>
                    <div class="col-12 mb-3">
                        <div class="inquiry-card">
                            <div class="d-flex align-items-start gap-3">
                                <div class="applicant-avatar">
                                    <?php echo strtoupper(substr($inquiry['user_name'], 0, 1)); ?>
                                </div>
                                
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="mb-1"><?php echo htmlspecialchars($inquiry['user_name']); ?></h5>
                                            <p class="text-muted mb-1 small">
                                                <i class="fas fa-home me-1"></i><?php echo htmlspecialchars($inquiry['property_name']); ?>
                                                <span class="mx-2">•</span>
                                                <?php echo htmlspecialchars($inquiry['locality']); ?>
                                            </p>
                                            <p class="text-muted mb-2 small">
                                                <i class="fas fa-envelope me-1"></i>
                                                <a href="mailto:<?php echo htmlspecialchars($inquiry['user_email']); ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($inquiry['user_email']); ?>
                                                </a>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-phone me-1"></i>
                                                <a href="tel:<?php echo htmlspecialchars($inquiry['user_phone']); ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($inquiry['user_phone']); ?>
                                                </a>
                                            </p>
                                        </div>
                                        <span class="badge bg-primary">
                                            <?php echo ucfirst(htmlspecialchars($inquiry['status'])); ?>
                                        </span>
                                    </div>
                                    
                                    <?php if (!empty($inquiry['interested_room_type'])): ?>
                                        <p class="mb-1"><strong>Interested in:</strong> <?php echo htmlspecialchars($inquiry['interested_room_type']); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($inquiry['special_requirements'])): ?>
                                        <div class="alert alert-light mb-2">
                                            <strong><i class="fas fa-comment me-1"></i>Requirements:</strong>
                                            <?php echo nl2br(htmlspecialchars($inquiry['special_requirements'])); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="d-flex gap-2 mt-3">
                                        <a href="mailto:<?php echo htmlspecialchars($inquiry['user_email']); ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-envelope me-1"></i>Email
                                        </a>
                                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $inquiry['user_phone']); ?>" 
                                           class="btn btn-sm btn-success" target="_blank">
                                            <i class="fab fa-whatsapp me-1"></i>WhatsApp
                                        </a>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-phone me-1"></i>Call
                                        </button>
                                    </div>
                                    
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo date('M j, Y g:i A', strtotime($inquiry['created_at'])); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>
</main>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

