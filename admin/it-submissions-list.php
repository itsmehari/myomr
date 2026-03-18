<?php
require_once __DIR__ . '/_bootstrap.php';
require_once '../core/omr-connect.php';
require_once '../core/security-helpers.php';

// Ensure featured table exists
$conn->query("CREATE TABLE IF NOT EXISTS omr_it_companies_featured (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_slno INT NOT NULL,
  rank_position INT NOT NULL DEFAULT 1,
  blurb VARCHAR(400) DEFAULT NULL,
  cta_text VARCHAR(80) DEFAULT NULL,
  cta_url VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(company_slno)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Approve submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'approve') {
    $id = (int)($_POST['id'] ?? 0);
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'Invalid CSRF token.';
        header('Location: it-submissions-list.php');
        exit;
    }
    $feature = isset($_POST['feature']) ? 1 : 0;
    $rank = max(1, (int)($_POST['rank'] ?? 1));
    $blurb = trim($_POST['blurb'] ?? '');
    $cta_text = trim($_POST['cta_text'] ?? 'Learn more');
    $cta_url = trim($_POST['cta_url'] ?? '');
    $start_at = trim($_POST['start_at'] ?? '');
    $end_at = trim($_POST['end_at'] ?? '');

    // Fetch submission
    $subStmt = $conn->prepare('SELECT company_name, address, locality, website, contact_name, email, phone, industry_type FROM omr_it_company_submissions WHERE id = ? AND status = "pending"');
    $subStmt->bind_param('i', $id);
    $subStmt->execute();
    $subRes = $subStmt->get_result();
    $submission = $subRes ? $subRes->fetch_assoc() : null;
    $subStmt->close();

    if (!$submission) {
        $_SESSION['flash_error'] = 'Submission not found or already processed.';
        header('Location: it-submissions-list.php');
        exit;
    }

    // Prepare contact string
    $contactParts = [];
    if (!empty($submission['contact_name'])) { $contactParts[] = $submission['contact_name']; }
    if (!empty($submission['phone'])) { $contactParts[] = $submission['phone']; }
    if (!empty($submission['email'])) { $contactParts[] = $submission['email']; }
    if (!empty($submission['website'])) { $contactParts[] = $submission['website']; }
    $contactStr = implode(' | ', $contactParts);

    // Use separate locality column; keep address as provided
    $fullAddress = $submission['address'];
    $locVal = $submission['locality'] ?? null;

    // Insert into main IT companies table (verified=1 on admin approve)
    $ins = $conn->prepare('INSERT INTO omr_it_companies (company_name, address, locality, contact, industry_type, verified) VALUES (?, ?, ?, ?, ?, 1)');
    $ins->bind_param('sssss', $submission['company_name'], $fullAddress, $locVal, $contactStr, $submission['industry_type']);
    if ($ins->execute()) {
        $newSlno = $ins->insert_id;
        $ins->close();

        // Generate slug as kebab-case name + '-' + id
        $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $submission['company_name']));
        $slugBase = trim($slugBase, '-');
        $slug = $slugBase . '-' . $newSlno;
        $updSlug = $conn->prepare('UPDATE omr_it_companies SET slug = ? WHERE slno = ?');
        $updSlug->bind_param('si', $slug, $newSlno);
        $updSlug->execute();
        $updSlug->close();

        // Update submission status
        $upd = $conn->prepare('UPDATE omr_it_company_submissions SET status = "approved", approved_company_slno = ? WHERE id = ?');
        $upd->bind_param('ii', $newSlno, $id);
        $upd->execute();
        $upd->close();

        // Optional feature
        if ($feature) {
            if ($start_at === '') { $start_at = date('Y-m-d H:i:s'); }
            $feat = $conn->prepare('INSERT INTO omr_it_companies_featured (company_slno, rank_position, blurb, cta_text, cta_url, start_at, end_at) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $feat->bind_param('iisssss', $newSlno, $rank, $blurb, $cta_text, $cta_url, $start_at, $end_at);
            $feat->execute();
            $feat->close();
        }

        $_SESSION['flash_success'] = 'Submission approved' . ($feature ? ' and featured.' : '.');
        $_SESSION['ga_event'] = [
            'name' => 'admin_approve_submission',
            'company_id' => (int)$newSlno,
            'company_name' => (string)$submission['company_name'],
            'featured' => (int)$feature,
            'rank' => (int)$rank
        ];
    } else {
        $_SESSION['flash_error'] = 'Error approving submission: ' . $ins->error;
        $ins->close();
    }
    header('Location: it-submissions-list.php');
    exit;
}

// Reject submission
if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];
    $rej = $conn->prepare('UPDATE omr_it_company_submissions SET status = "rejected" WHERE id = ? AND status = "pending"');
    $rej->bind_param('i', $id);
    $rej->execute();
    $rej->close();
    $_SESSION['flash_success'] = 'Submission rejected.';
    $_SESSION['ga_event'] = [
        'name' => 'admin_reject_submission',
        'submission_id' => (int)$id
    ];
    header('Location: it-submissions-list.php');
    exit;
}

// Fetch submissions
$status = $_GET['status'] ?? 'pending';
$allowedStatuses = ['pending','approved','rejected'];
if (!in_array($status, $allowedStatuses, true)) { $status = 'pending'; }

$result = $conn->prepare('SELECT id, company_name, address, locality, website, contact_name, email, phone, industry_type, featured_requested, created_at FROM omr_it_company_submissions WHERE status = ? ORDER BY created_at DESC');
$result->bind_param('s', $status);
$result->execute();
$submissions = $result->get_result();
$result->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IT Company Submissions - MyOMR CMS</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background: #f4f6f8;">
<div class="container-fluid">
  <div class="row">
    <?php include 'admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content" aria-label="Main content">
      <?php include 'admin-header.php'; ?>
      <?php include 'admin-breadcrumbs.php'; ?>
      <?php include 'admin-flash.php'; ?>
      <?php if (!empty($_SESSION['ga_event'])): $ev = $_SESSION['ga_event']; unset($_SESSION['ga_event']); ?>
      <script>
        (function(){
          if (typeof window.gtag !== 'function') return;
          var ev = <?php echo json_encode($ev, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); ?>;
          var name = ev.name || 'admin_action';
          var params = Object.assign({ event_category: 'admin_it' }, ev);
          delete params.name;
          window.gtag('event', name, params);
        })();
      </script>
      <?php endif; ?>
      <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <h2>IT Company Submissions (<?php echo htmlspecialchars($status); ?>)</h2>
        <div>
          <a class="btn btn-sm btn-outline-secondary" href="?status=pending">Pending</a>
          <a class="btn btn-sm btn-outline-secondary" href="?status=approved">Approved</a>
          <a class="btn btn-sm btn-outline-secondary" href="?status=rejected">Rejected</a>
        </div>
      </div>
      <div class="table-responsive bg-white p-3">
        <table class="table table-bordered table-hover">
          <thead class="thead-dark">
            <tr>
              <th>ID</th>
              <th>Company</th>
              <th>Locality</th>
              <th>Contact</th>
              <th>Industry</th>
              <th>Featured?</th>
              <th>Submitted</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($submissions && $submissions->num_rows > 0): ?>
              <?php while ($row = $submissions->fetch_assoc()): ?>
                <tr>
                  <td><?php echo (int)$row['id']; ?></td>
                  <td>
                    <strong><?php echo htmlspecialchars($row['company_name']); ?></strong><br>
                    <small><?php echo htmlspecialchars($row['address']); ?></small>
                  </td>
                  <td><?php echo htmlspecialchars($row['locality'] ?? ''); ?></td>
                  <td>
                    <?php echo htmlspecialchars($row['contact_name'] ?? ''); ?><br>
                    <?php echo htmlspecialchars($row['phone'] ?? ''); ?><br>
                    <a href="mailto:<?php echo htmlspecialchars($row['email'] ?? ''); ?>"><?php echo htmlspecialchars($row['email'] ?? ''); ?></a><br>
                    <a href="<?php echo htmlspecialchars($row['website'] ?? ''); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($row['website'] ?? ''); ?></a>
                  </td>
                  <td><?php echo htmlspecialchars($row['industry_type'] ?? ''); ?></td>
                  <td><?php echo ((int)$row['featured_requested'] ? 'Requested' : ''); ?></td>
                  <td><small><?php echo htmlspecialchars($row['created_at']); ?></small></td>
                  <td>
                    <?php if ($status === 'pending'): ?>
                    <form method="post" class="mb-2">
                      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
                      <input type="hidden" name="action" value="approve">
                      <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                      <div class="form-row align-items-center">
                        <div class="col-auto">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="feature" id="feature<?php echo (int)$row['id']; ?>">
                            <label class="form-check-label" for="feature<?php echo (int)$row['id']; ?>">Feature</label>
                          </div>
                        </div>
                        <div class="col-auto">
                          <input type="number" class="form-control" name="rank" value="1" min="1" style="width:80px" placeholder="Rank">
                        </div>
                        <div class="col-auto">
                          <input type="text" class="form-control" name="blurb" placeholder="Blurb" style="width:200px">
                        </div>
                        <div class="col-auto">
                          <input type="text" class="form-control" name="cta_text" placeholder="CTA text" style="width:120px" value="Learn more">
                        </div>
                        <div class="col-auto">
                          <input type="url" class="form-control" name="cta_url" placeholder="https://" style="width:220px">
                        </div>
                        <div class="col-auto">
                          <input type="datetime-local" class="form-control" name="start_at" style="width:210px" placeholder="Start">
                        </div>
                        <div class="col-auto">
                          <input type="datetime-local" class="form-control" name="end_at" style="width:210px" placeholder="End (optional)">
                        </div>
                        <div class="col-auto">
                          <button type="submit" class="btn btn-sm btn-success">Approve</button>
                          <a href="it-submissions-list.php?reject=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Reject this submission?');">Reject</a>
                        </div>
                      </div>
                    </form>
                    <?php else: ?>
                      <span class="text-muted">No actions</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="8" class="text-center">No submissions.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


