<?php
// Simple newsletter capture form (file-based). Replace later with provider.
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['events_newsletter_csrf'])) { $_SESSION['events_newsletter_csrf'] = bin2hex(random_bytes(16)); }
?>
<form class="card-modern p-3" method="post" action="/omr-local-events/process-newsletter-signup.php" style="max-width:480px;">
  <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['events_newsletter_csrf']); ?>">
  <div class="mb-2"><strong>Get OMR events in your inbox</strong></div>
  <div class="row g-2">
    <div class="col-12 col-md-8">
      <input type="email" class="form-control" name="email" placeholder="you@example.com" required>
      <div style="position:absolute;left:-9999px;top:auto;">
        <input type="text" name="website" tabindex="-1" autocomplete="off">
      </div>
    </div>
    <div class="col-12 col-md-4 d-grid">
      <button class="btn btn-primary" type="submit">Subscribe</button>
    </div>
  </div>
  <div class="form-text">Weekly summary. No spam.</div>
</form>


