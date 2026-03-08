<?php
require_once __DIR__ . '/../includes/error-reporting.php';
require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/admin-auth.php';
require_once __DIR__ . '/../includes/event-functions-omr.php';
requireAdmin();

$today = new DateTime('today');
// Default window: upcoming Fri–Sun from now
$startParam = $_GET['start'] ?? '';
$endParam = $_GET['end'] ?? '';

if ($startParam === '' || $endParam === '') {
  // Find next Friday and Sunday
  $start = new DateTime('next friday');
  if ((int)$today->format('N') <= 5) { $start = new DateTime('friday this week'); }
  $end = clone $start; $end->modify('+2 days');
} else {
  $start = new DateTime($startParam);
  $end = new DateTime($endParam);
}

$filters = [
  'search' => '',
  'category' => 0,
  'locality' => '',
  'is_free' => '',
  'date_from' => $start->format('Y-m-d'),
  'date_to' => $end->format('Y-m-d'),
];
$events = getEvents($filters, 50, 0);

function shareUrl(string $slug, string $source): string {
  $base = '/omr-local-events/event/' . $slug;
  $qs = http_build_query([
    'utm_source' => $source,
    'utm_medium' => 'social',
    'utm_campaign' => 'weekly_weekend_share'
  ]);
  return $base . '?' . $qs;
}

// Build messages
$lines = [];
$lines[] = 'This Weekend in OMR – top events you shouldn\'t miss:';
foreach ($events as $e) {
  $when = date('D, M j g:ia', strtotime($e['start_datetime']));
  $lines[] = '• ' . $e['title'] . ' – ' . $when . ' @ ' . ($e['location'] ?: $e['locality']);
  $lines[] = '  ' . 'https://myomr.in' . shareUrl($e['slug'], 'whatsapp');
}
$lines[] = '';
$lines[] = 'More: https://myomr.in/omr-local-events/weekend?utm_source=whatsapp&utm_medium=social&utm_campaign=weekly_weekend_share';
$whatsappMsg = implode("\n", $lines);

// Telegram version (different utm_source)
$tLines = [];
$tLines[] = 'This Weekend in OMR – top events you shouldn\'t miss:';
foreach ($events as $e) {
  $when = date('D, M j g:ia', strtotime($e['start_datetime']));
  $tLines[] = '• ' . $e['title'] . ' – ' . $when . ' @ ' . ($e['location'] ?: $e['locality']);
  $tLines[] = '  ' . 'https://myomr.in' . shareUrl($e['slug'], 'telegram');
}
$tLines[] = '';
$tLines[] = 'More: https://myomr.in/omr-local-events/weekend?utm_source=telegram&utm_medium=social&utm_campaign=weekly_weekend_share';
$telegramMsg = implode("\n", $tLines);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Friday Share Playbook – MyOMR Events</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0">Friday Share Playbook</h1>
      <a href="index.php" class="btn btn-outline-secondary">Back to Events Admin</a>
    </div>
    <form class="row g-3 mb-3" method="get">
      <div class="col-auto">
        <label class="form-label">From</label>
        <input type="date" class="form-control" name="start" value="<?php echo htmlspecialchars($start->format('Y-m-d')); ?>">
      </div>
      <div class="col-auto">
        <label class="form-label">To</label>
        <input type="date" class="form-control" name="end" value="<?php echo htmlspecialchars($end->format('Y-m-d')); ?>">
      </div>
      <div class="col-auto align-self-end">
        <button class="btn btn-primary" type="submit">Refresh</button>
      </div>
    </form>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">WhatsApp message</h5>
            <textarea id="wa" class="form-control" rows="12" readonly><?php echo htmlspecialchars($whatsappMsg); ?></textarea>
            <div class="mt-2 d-flex gap-2">
              <button class="btn btn-success" type="button" onclick="copyText('wa')">Copy</button>
              <a class="btn btn-outline-success" target="_blank" rel="noopener" href="https://wa.me/?text=<?php echo rawurlencode($whatsappMsg); ?>">Open WhatsApp</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Telegram message</h5>
            <textarea id="tg" class="form-control" rows="12" readonly><?php echo htmlspecialchars($telegramMsg); ?></textarea>
            <div class="mt-2 d-flex gap-2">
              <button class="btn btn-primary" type="button" onclick="copyText('tg')">Copy</button>
              <a class="btn btn-outline-primary" target="_blank" rel="noopener" href="https://t.me/share/url?text=<?php echo rawurlencode($telegramMsg); ?>">Open Telegram</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm mt-4">
      <div class="card-body">
        <h5 class="card-title mb-3">Included events (<?php echo count($events); ?>)</h5>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead><tr><th>When</th><th>Title</th><th>Venue</th><th>Link</th></tr></thead>
            <tbody>
              <?php foreach ($events as $e): ?>
                <tr>
                  <td><?php echo htmlspecialchars(date('D, M j g:ia', strtotime($e['start_datetime']))); ?></td>
                  <td><?php echo htmlspecialchars($e['title']); ?></td>
                  <td><?php echo htmlspecialchars($e['location'] ?: $e['locality'] ?: ''); ?></td>
                  <td><a href="<?php echo htmlspecialchars(shareUrl($e['slug'], 'whatsapp')); ?>" target="_blank" rel="noopener">open</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
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


