<?php
/**
 * Owner Dashboard - My Spaces
 * Space owner's dashboard to manage listings
 */

// Enable error reporting
require_once __DIR__ . '/includes/error-reporting.php';

require_once __DIR__ . '/includes/owner-auth.php';
require_once __DIR__ . '/includes/space-functions.php';
requireOwnerAuth();

$ownerId = (int)($_SESSION['owner_id'] ?? 0);
$ownerEmail = $_SESSION['owner_email'] ?? '';

require_once __DIR__ . '/../core/omr-connect.php';
global $conn;

if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
    die("Database connection error. Please try again later.");
}

// Get owner profile information
$owner = null;
if (!empty($ownerEmail)) {
    $emailQuery = "SELECT * FROM space_owners WHERE email = '" . $conn->real_escape_string($ownerEmail) . "'";
    $emailResult = $conn->query($emailQuery);
    if ($emailResult && $emailResult->num_rows > 0) {
        $owner = $emailResult->fetch_assoc();
        $correctId = (int)$owner['id'];
        if ($ownerId !== $correctId) {
            $_SESSION['owner_id'] = $correctId;
            $ownerId = $correctId;
        }
    }
}

if (!$owner && $ownerId > 0) {
    $ownerQuery = "SELECT * FROM space_owners WHERE id = {$ownerId}";
    $ownerResult = $conn->query($ownerQuery);
    $owner = $ownerResult ? $ownerResult->fetch_assoc() : null;
}

if ($owner && isset($owner['id'])) {
    $ownerId = (int)$owner['id'];
}

// Get owner's spaces
$spaces = [];

if ($ownerId > 0) {
    $spacesQuery = "SELECT * FROM coworking_spaces WHERE owner_id = {$ownerId} ORDER BY created_at DESC";
    $spacesResult = $conn->query($spacesQuery);
    
    if ($spacesResult && $spacesResult->num_rows > 0) {
        $spaces = $spacesResult->fetch_all(MYSQLI_ASSOC);
    }
}

$status_totals = [
    'active' => 0,
    'pending' => 0,
    'inactive' => 0,
];
$total_inquiries = 0;
$total_views = 0;

foreach ($spaces as $space) {
    $status_key = strtolower($space['status'] ?? '');
    if (array_key_exists($status_key, $status_totals)) {
        $status_totals[$status_key]++;
    }
    $total_inquiries += (int)($space['inquiries_count'] ?? 0);
    $total_views += (int)($space['views_count'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Workspaces - Owner Dashboard | MyOMR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/coworking-spaces.css">
    
    <style>
        .dashboard-actions .btn {
            min-width: 44px;
        }
        .dashboard-table thead {
            background-color: #f9fafb;
        }
        .dashboard-table thead th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #6b7280;
        }
        .stat-card small {
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
            color: #64748b;
        }
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
        }
    </style>
    
    <?php include '../components/analytics.php'; ?>
</head>
<body class="modern-page">
<?php require_once __DIR__ . '/../components/main-nav.php'; ?>

<section class="hero-modern py-4">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 text-white">
            <div>
                <h1 class="hero-modern-title mb-2">My Workspaces</h1>
                <p class="hero-modern-subtitle mb-0">Welcome, <?php echo htmlspecialchars($owner['full_name'] ?? 'Owner'); ?>. Manage your listings and track performance.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-light" href="add-space.php"><i class="fas fa-plus me-2"></i>Add New Workspace</a>
                <a class="btn btn-outline-light" href="owner-logout.php"><i class="fas fa-right-from-bracket me-2"></i>Logout</a>
            </div>
        </div>
    </div>
</section>

<main class="py-5">
    <div class="container">
        
        <!-- Owner Profile Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card-modern p-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-1"><?php echo htmlspecialchars($owner['full_name'] ?? 'Owner'); ?></h3>
                                <p class="text-muted mb-0 small">
                                    <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($owner['email'] ?? 'N/A'); ?>
                                    <?php if (!empty($owner['phone'])): ?>
                                        <span class="ms-3"><i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($owner['phone']); ?></span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="add-space.php" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Add New Workspace
                            </a>
                        </div>
                    </div>
                    
                    <?php if (!empty($owner['status'])): ?>
                    <div class="mt-3">
                        <small class="text-muted">Account Status:</small>
                        <?php
                        $statusColor = 'secondary';
                        if ($owner['status'] === 'verified') $statusColor = 'success';
                        elseif ($owner['status'] === 'pending') $statusColor = 'warning';
                        elseif ($owner['status'] === 'suspended') $statusColor = 'danger';
                        ?>
                        <span class="badge bg-<?php echo $statusColor; ?>"><?php echo ucfirst(htmlspecialchars($owner['status'])); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php if (!empty($spaces)): ?>
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card-modern p-4 stat-card h-100">
                    <small>Active Spaces</small>
                    <div class="stat-value"><?php echo $status_totals['active']; ?></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card-modern p-4 stat-card h-100">
                    <small>Pending Review</small>
                    <div class="stat-value"><?php echo $status_totals['pending']; ?></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card-modern p-4 stat-card h-100">
                    <small>Total Inquiries</small>
                    <div class="stat-value"><?php echo $total_inquiries; ?></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card-modern p-4 stat-card h-100">
                    <small>Total Views</small>
                    <div class="stat-value"><?php echo $total_views; ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (empty($spaces)): ?>
            <div class="card-modern p-5 text-center">
                <div class="mb-3">
                    <i class="fas fa-building fa-3x text-success"></i>
                </div>
                <h2 class="h4 mb-2">You haven't listed any workspaces yet</h2>
                <p class="text-muted mb-4">Start attracting users by creating your first workspace listing.</p>
                <a href="add-space.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add your first workspace</a>
            </div>
        <?php else: ?>
            <div class="card-modern">
                <div class="p-4 border-bottom">
                    <h2 class="h5 mb-0">All Workspaces</h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover dashboard-table mb-0" id="space-dashboard-table">
                        <thead>
                            <tr>
                                <th>Workspace Name</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th class="text-center">Views</th>
                                <th class="text-center">Inquiries</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($spaces as $space): ?>
                                <tr>
                                    <td>
                                        <a href="../omr-coworking-spaces/space-detail.php?id=<?php echo $space['id']; ?>" 
                                           class="text-decoration-none fw-semibold">
                                            <?php echo htmlspecialchars($space['space_name']); ?>
                                        </a>
                                        <?php if (!empty($space['featured'])): ?>
                                            <span class="badge bg-warning text-dark ms-2"><i class="fas fa-star"></i> Featured</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($space['locality'] ?? 'OMR'); ?></td>
                                    <td>
                                        <?php
                                        $statusColors = [
                                            'active' => 'success',
                                            'pending' => 'warning',
                                            'inactive' => 'secondary',
                                            'flagged' => 'danger'
                                        ];
                                        $statusColor = $statusColors[$space['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $statusColor; ?>">
                                            <?php echo ucfirst(htmlspecialchars($space['status'])); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <i class="fas fa-eye me-1"></i>
                                        <?php echo number_format($space['views_count'] ?? 0); ?>
                                    </td>
                                    <td class="text-center">
                                        <i class="fas fa-envelope me-1"></i>
                                        <?php echo number_format($space['inquiries_count'] ?? 0); ?>
                                    </td>
                                    <td class="text-center dashboard-actions">
                                        <div class="btn-group btn-group-sm">
                                            <a href="view-inquiries.php?space_id=<?php echo $space['id']; ?>" 
                                               class="btn btn-outline-primary" 
                                               title="View Inquiries">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                            <a href="edit-space.php?id=<?php echo $space['id']; ?>" 
                                               class="btn btn-outline-secondary" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        
    </div>
</main>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

