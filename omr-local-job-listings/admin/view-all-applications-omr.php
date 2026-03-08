<?php
/**
 * Admin - View All Applications
 */
require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();

require_once __DIR__ . '/../../core/omr-connect.php';

// Get all applications
$result = $conn->query("SELECT a.*, j.title as job_title, e.company_name 
                        FROM job_applications a 
                        JOIN job_postings j ON a.job_id = j.id 
                        JOIN employers e ON j.employer_id = e.id 
                        ORDER BY a.applied_at DESC");
$applications = $result->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Applications - Admin | MyOMR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/job-listings-omr.css">
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><i class="fas fa-inbox me-2"></i>All Job Applications</h1>
        <a href="index.php" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Applicant</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Job Title</th>
                    <th>Company</th>
                    <th>Applied</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($app['applicant_name']); ?></td>
                        <td><?php echo htmlspecialchars($app['applicant_email']); ?></td>
                        <td><?php echo htmlspecialchars($app['applicant_phone']); ?></td>
                        <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($app['company_name']); ?></td>
                        <td><?php echo date('M j, Y H:i', strtotime($app['applied_at'])); ?></td>
                        <td>
                            <?php
                                $status = $app['status'];
                                $badge = ['pending' => 'warning', 'reviewed' => 'info', 'shortlisted' => 'success', 'rejected' => 'danger'][$status] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?php echo $badge; ?>"><?php echo ucfirst($status); ?></span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#appModal<?php echo (int)$app['id']; ?>">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Application Details Modal -->
                    <div class="modal fade" id="appModal<?php echo (int)$app['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Application Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Cover Letter:</strong></p>
                                    <p><?php echo nl2br(htmlspecialchars($app['cover_letter'] ?: 'No cover letter provided.')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

