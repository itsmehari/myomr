<?php
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/admin-auth.php';
require_once __DIR__ . '/../includes/event-functions-omr.php';
requireAdmin();

$today = new DateTime('today');
$startParam = $_GET['start'] ?? '';
$daysParam = isset($_GET['days']) ? (int)$_GET['days'] : 7;
if ($daysParam < 1 || $daysParam > 14) { $daysParam = 7; }

if ($startParam === '') {
  // Default to Friday this week if earlier than Fri; else today
  $start = ((int)$today->format('N') <= 5) ? new DateTime('friday this week') : clone $today;
} else {
  $start = new DateTime($startParam);
}
$end = clone $start; $end->modify('+' . ($daysParam - 1) . ' days');

$filters = [
  'search' => '',
  'category' => 0,
  'locality' => '',
  'is_free' => '',
  'date_from' => $start->format('Y-m-d'),
  'date_to' => $end->format('Y-m-d'),
];
$events = getEvents($filters, 100, 0);

function emailUrl(string $slug): string {
  $base = '/omr-local-events/event/' . $slug;
  $qs = http_build_query([
    'utm_source' => 'email',
    'utm_medium' => 'mail',
    'utm_campaign' => 'weekly_digest'
  ]);
  return 'https://myomr.in' . $base . '?' . $qs;
}

// Suggested subject
$subject = 'This Week in OMR: ' . max(1, count($events)) . ' events to check out';

// Build basic email HTML (inline styles for compatibility)
ob_start();
?>
<!DOCTYPE html>
<html>
  <body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f7fb;padding:20px 0;">
      <tr>
        <td align="center">
          <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background:#ffffff;border-radius:8px;overflow:hidden;border:1px solid #e5e7eb;">
            <tr>
              <td style="padding:16px 20px;background:#0583D2;color:#ffffff;">
                <table width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="left" style="font-weight:bold;font-size:18px;">MyOMR – Weekly Events Digest</td>
                    <td align="right" style="font-size:12px;opacity:.9;"><?php echo htmlspecialchars($start->format('M j')); ?> – <?php echo htmlspecialchars($end->format('M j, Y')); ?></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td style="padding:18px 20px;">
                <p style="margin:0 0 12px 0;color:#111827;font-size:16px;">Here are upcoming events in and around OMR.</p>
                <?php if (empty($events)): ?>
                  <p style="margin:0;color:#6b7280;">No events found for this window.</p>
                <?php else: ?>
                  <?php foreach ($events as $e): ?>
                    <?php
                      $title = $e['title'];
                      $slug = $e['slug'];
                      $when = date('D, M j g:ia', strtotime($e['start_datetime']));
                      $venue = $e['location'] ?: ($e['locality'] ?: '');
                      $img = trim((string)($e['image_url'] ?? ''));
                      $href = emailUrl($slug);
                    ?>
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 14px 0;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                      <?php if ($img !== ''): ?>
                        <tr>
                          <td style="background:#f8fafc;">
                            <a href="<?php echo htmlspecialchars($href); ?>" target="_blank" style="text-decoration:none;">
                              <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($title); ?>" style="display:block;width:100%;max-height:220px;object-fit:cover;border:0;">
                            </a>
                          </td>
                        </tr>
                      <?php endif; ?>
                      <tr>
                        <td style="padding:12px 14px;">
                          <a href="<?php echo htmlspecialchars($href); ?>" target="_blank" style="color:#0583D2;font-size:16px;font-weight:bold;text-decoration:none;">
                            <?php echo htmlspecialchars($title); ?>
                          </a>
                          <div style="color:#374151;font-size:14px;margin-top:6px;">
                            <span><?php echo htmlspecialchars($when); ?></span>
                            <?php if ($venue !== ''): ?>
                              <span> • <?php echo htmlspecialchars($venue); ?></span>
                            <?php endif; ?>
                          </div>
                          <div style="margin-top:10px;">
                            <a href="<?php echo htmlspecialchars($href); ?>" target="_blank" style="background:#0583D2;color:#ffffff;border-radius:4px;padding:8px 12px;display:inline-block;font-size:14px;text-decoration:none;">View details</a>
                          </div>
                        </td>
                      </tr>
                    </table>
                  <?php endforeach; ?>
                <?php endif; ?>
                <div style="margin-top:18px;text-align:center;">
                  <a href="https://myomr.in/omr-local-events/weekend?utm_source=email&utm_medium=mail&utm_campaign=weekly_digest" target="_blank" style="background:#10b981;color:#ffffff;border-radius:6px;padding:10px 16px;display:inline-block;font-size:15px;text-decoration:none;">See all weekend events</a>
                </div>
              </td>
            </tr>
            <tr>
              <td style="background:#f9fafb;padding:14px 20px;color:#6b7280;font-size:12px;text-align:center;">
                You received this because you follow MyOMR Events. <a href="https://myomr.in/webmaster-contact-my-omr.php" style="color:#6b7280;">Contact us</a> for feedback.
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
<?php
$htmlEmail = ob_get_clean();

// Build plaintext
$textLines = [];
$textLines[] = 'MyOMR – Weekly Events Digest (' . $start->format('M j') . ' – ' . $end->format('M j, Y') . ')';
$textLines[] = '';
foreach ($events as $e) {
  $when = date('D, M j g:ia', strtotime($e['start_datetime']));
  $textLines[] = $e['title'] . ' – ' . $when . ' @ ' . ($e['location'] ?: $e['locality']);
  $textLines[] = emailUrl($e['slug']);
  $textLines[] = '';
}
$textLines[] = 'See all weekend events: https://myomr.in/omr-local-events/weekend?utm_source=email&utm_medium=mail&utm_campaign=weekly_digest';
$textEmail = implode("\n", $textLines);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Digest – MyOMR Events</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0">Weekly Email Digest</h1>
      <a href="index.php" class="btn btn-outline-secondary">Back to Events Admin</a>
    </div>
    <form class="row g-3 mb-3" method="get">
      <div class="col-auto">
        <label class="form-label">Start date</label>
        <input type="date" class="form-control" name="start" value="<?php echo htmlspecialchars($start->format('Y-m-d')); ?>">
      </div>
      <div class="col-auto">
        <label class="form-label">Days (1–14)</label>
        <input type="number" min="1" max="14" class="form-control" name="days" value="<?php echo (int)$daysParam; ?>">
      </div>
      <div class="col-auto align-self-end">
        <button class="btn btn-primary" type="submit">Refresh</button>
      </div>
    </form>

    <div class="card mb-3">
      <div class="card-body">
        <div class="mb-2"><strong>Suggested subject:</strong> <code><?php echo htmlspecialchars($subject); ?></code></div>
        <h5 class="card-title">HTML (paste into Brevo/Mailchimp)</h5>
        <textarea id="htmlEmail" class="form-control" rows="16" readonly><?php echo htmlspecialchars($htmlEmail); ?></textarea>
        <div class="mt-2">
          <button class="btn btn-success" type="button" onclick="copyText('htmlEmail')">Copy HTML</button>
        </div>
      </div>
    </div>

    <div class="card mb-5">
      <div class="card-body">
        <h5 class="card-title">Plain text (fallback)</h5>
        <textarea id="textEmail" class="form-control" rows="10" readonly><?php echo htmlspecialchars($textEmail); ?></textarea>
        <div class="mt-2">
          <button class="btn btn-secondary" type="button" onclick="copyText('textEmail')">Copy Text</button>
        </div>
      </div>
    </div>
  </div>
  <script>
    function copyText(id){
      var el = document.getElementById(id);
      el.select(); el.setSelectionRange(0, 99999);
      try { document.execCommand('copy'); } catch(e) {}
    }
  </script>
</body>
</html>


