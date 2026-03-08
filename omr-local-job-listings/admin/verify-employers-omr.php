<?php
/**
 * Admin - Verify Employers
 */
require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/email.php';

// CSRF token
if (empty($_SESSION['admin_csrf'])) {
    $_SESSION['admin_csrf'] = bin2hex(random_bytes(16));
}

// Handle status changes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['admin_csrf'], (string)$token)) {
        http_response_code(403);
        exit('Invalid session token. Please refresh the page and try again.');
    }
    $employer_id = (int)($_POST['employer_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if ($employer_id > 0 && in_array($action, ['verify', 'suspend', 'pending'], true)) {
        $newStatus = $action === 'verify' ? 'verified' : ($action === 'suspend' ? 'suspended' : 'pending');
        $stmt = $conn->prepare("UPDATE employers SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $employer_id);
        $stmt->execute();

        // Audit log
        $adminUser = $_SESSION['admin_role'] ?? 'admin';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $audit = $conn->prepare("INSERT INTO admin_audit_log (admin_user, action, entity_type, entity_id, old_value, new_value, ip_address) VALUES (?, 'employer_status_update', 'employer', ?, '', ?, ?)");
        $audit->bind_param("sisss", $adminUser, $employer_id, $newStatus, $ip);
        $audit->execute();

        // Notify employer via email
        $info = null;
        $infoStmt = $conn->prepare("SELECT company_name, email FROM employers WHERE id = ? LIMIT 1");
        $infoStmt->bind_param("i", $employer_id);
        if ($infoStmt->execute()) {
            $infoRes = $infoStmt->get_result();
            $info = $infoRes ? $infoRes->fetch_assoc() : null;
        }
        if ($info && !empty($info['email'])) {
            $subject = 'Employer Status Updated: ' . ucfirst($newStatus);
            $body = renderEmailTemplate(
                'Your Employer Account was ' . ucfirst($newStatus),
                '<p>Dear ' . htmlspecialchars($info['company_name'] ?? 'Employer') . ',</p>' .
                '<p>Your employer account status on MyOMR has been updated to <strong>' . ucfirst($newStatus) . '</strong>.</p>' .
                ($newStatus === 'verified' ? '<p>You can now post jobs and manage applications with full access.</p>' : '') .
                ($newStatus === 'suspended' ? '<p>If you believe this is a mistake, please reply to this email for assistance.</p>' : '')
            );
            @sendEmail($info['email'], $subject, $body);
        }

        header('Location: verify-employers-omr.php?success=1');
        exit;
    }
}

// Filters
$statusFilter = $_GET['status'] ?? 'all';
$allowedStatuses = ['all', 'pending', 'verified', 'suspended'];
if (!in_array($statusFilter, $allowedStatuses, true)) { $statusFilter = 'all'; }

// Fetch employers
if ($statusFilter === 'all') {
    $result = $conn->query("SELECT id, company_name, contact_person, email, phone, status, created_at FROM employers ORDER BY created_at DESC");
} else {
    $stmt = $conn->prepare("SELECT id, company_name, contact_person, email, phone, status, created_at FROM employers WHERE status = ? ORDER BY created_at DESC");
    $stmt->bind_param("s", $statusFilter);
    $stmt->execute();
    $result = $stmt->get_result();
}
$employers = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Employers - Admin | MyOMR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><i class="fas fa-user-check me-2"></i>Verify Employers</h1>
        <a href="index.php" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Employer status updated successfully!</div>
    <?php endif; ?>

    <form method="get" class="mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-auto">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="all" <?php echo $statusFilter==='all'?'selected':''; ?>>All</option>
                    <option value="pending" <?php echo $statusFilter==='pending'?'selected':''; ?>>Pending</option>
                    <option value="verified" <?php echo $statusFilter==='verified'?'selected':''; ?>>Verified</option>
                    <option value="suspended" <?php echo $statusFilter==='suspended'?'selected':''; ?>>Suspended</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" type="submit"><i class="fas fa-filter me-1"></i>Filter</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employers as $emp): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($emp['company_name']); ?></td>
                        <td><?php echo htmlspecialchars($emp['contact_person']); ?></td>
                        <td><?php echo htmlspecialchars($emp['email']); ?></td>
                        <td><?php echo htmlspecialchars($emp['phone']); ?></td>
                        <td>
                            <?php $badge = ['pending'=>'warning','verified'=>'success','suspended'=>'danger'][$emp['status']] ?? 'secondary'; ?>
                            <span class="badge bg-<?php echo $badge; ?>"><?php echo ucfirst($emp['status']); ?></span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($emp['created_at'])); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="employer_id" value="<?php echo (int)$emp['id']; ?>">
                                    <input type="hidden" name="action" value="verify">
                                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
                                    <button type="submit" class="btn btn-success" title="Verify" <?php echo $emp['status']==='verified'?'disabled':''; ?>><i class="fas fa-check"></i></button>
                                </form>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="employer_id" value="<?php echo (int)$emp['id']; ?>">
                                    <input type="hidden" name="action" value="pending">
                                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
                                    <button type="submit" class="btn btn-warning" title="Mark Pending" <?php echo $emp['status']==='pending'?'disabled':''; ?>><i class="fas fa-hourglass-half"></i></button>
                                </form>
                                <form method="post" class="d-inline" onsubmit="return confirm('Suspend this employer?');">
                                    <input type="hidden" name="employer_id" value="<?php echo (int)$emp['id']; ?>">
                                    <input type="hidden" name="action" value="suspend">
                                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['admin_csrf']); ?>">
                                    <button type="submit" class="btn btn-danger" title="Suspend" <?php echo $emp['status']==='suspended'?'disabled':''; ?>><i class="fas fa-ban"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


