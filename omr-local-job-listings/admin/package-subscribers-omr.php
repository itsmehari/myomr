<?php
/**
 * Admin - Package Subscribers (Employer Pack)
 * List employers with an active paid plan for renewals and cap checks.
 */
require_once __DIR__ . '/../../core/admin-auth.php';
requireAdmin();

require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../includes/job-functions-omr.php';

$subscribers = [];
if (jobEmployersTableHasPlanColumns($conn)) {
    $sql = "SELECT id, company_name, contact_person, email, phone, plan_type, plan_start_date, plan_end_date
            FROM employers
            WHERE plan_type IS NOT NULL AND plan_type != '' AND plan_type != 'free'
            AND plan_end_date IS NOT NULL AND plan_end_date >= CURDATE()
            ORDER BY plan_end_date ASC";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['jobs_this_month'] = countJobsThisMonthForEmployer($conn, (int)$row['id']);
            $row['cap'] = getPlanCap($row['plan_type']);
            $subscribers[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Subscribers | MyOMR Job Admin</title>
    <?php include '../../components/analytics.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/job-listings-omr.css">
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><i class="fas fa-crown me-2"></i>Package Subscribers</h1>
        <a href="index.php" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

    <?php if (!jobEmployersTableHasPlanColumns($conn)): ?>
        <div class="alert alert-warning">Employer Pack columns are not present. Run the migration <code>dev-tools/migrations/2026-03-employer-pack-plan-columns.sql</code> first.</div>
    <?php elseif (empty($subscribers)): ?>
        <div class="alert alert-info">No active package subscribers. Assign a plan (employer_pack_10, etc.) and plan_end_date to employers in the database or via Verify Employers.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Plan</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Used (month)</th>
                        <th>Cap</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subscribers as $s): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($s['company_name']); ?></td>
                        <td>
                            <small><?php echo htmlspecialchars($s['email']); ?></small>
                            <?php if (!empty($s['phone'])): ?><br><small><?php echo htmlspecialchars($s['phone']); ?></small><?php endif; ?>
                        </td>
                        <td><span class="badge bg-primary"><?php echo htmlspecialchars(getPlanLabel($s['plan_type'])); ?></span></td>
                        <td><?php echo $s['plan_start_date'] ? date('M j, Y', strtotime($s['plan_start_date'])) : '—'; ?></td>
                        <td><?php echo $s['plan_end_date'] ? date('M j, Y', strtotime($s['plan_end_date'])) : '—'; ?></td>
                        <td><?php echo (int)$s['jobs_this_month']; ?></td>
                        <td><?php echo (int)$s['cap']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
