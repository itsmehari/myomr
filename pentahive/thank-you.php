<?php
/**
 * Pentahive — Form Submission Thank-You Page
 * noindex — conversion goal URL for GA4 tracking
 */
$page_title = 'Thank You — We\'ll Be in Touch | Pentahive by MyOMR';

// GA4 source: passed via query string from redirect
$source = htmlspecialchars($_GET['src'] ?? 'general', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="robots" content="noindex, nofollow">
  <?php include __DIR__ . '/../components/analytics.php'; ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; background: #f9fafb; display: flex; flex-direction: column; min-height: 100vh; }
    main { flex: 1; display: flex; align-items: center; }
    .ty-card {
      background: #fff; border-radius: 20px;
      box-shadow: 0 8px 32px rgba(20,83,45,.1);
      padding: 56px 48px; text-align: center; max-width: 560px; margin: 40px auto;
    }
    .ty-icon {
      width: 80px; height: 80px; background: #f0fdf4; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 2.2rem; color: #14532d; margin: 0 auto 1.5rem;
    }
    .ty-card h1 { font-weight: 700; color: #14532d; font-size: 1.8rem; margin-bottom: .75rem; }
    .ty-card p { color: #6b7280; font-size: 1rem; }
    .next-steps { background: #f0fdf4; border-radius: 12px; padding: 20px 24px; text-align: left; margin-top: 1.5rem; }
    .next-step { display: flex; align-items: flex-start; gap: 12px; padding: .5rem 0; font-size: .9rem; color: #374151; }
    .step-icon { color: #14532d; font-size: 1rem; min-width: 22px; margin-top: 2px; }
    .btn-back { background: #14532d; color: #fff; border: none; border-radius: 8px; padding: 12px 28px; font-weight: 600; font-size: .95rem; text-decoration: none; display: inline-block; margin-top: 1.5rem; transition: background .2s; }
    .btn-back:hover { background: #0f3d1f; color: #fff; }
    .btn-secondary-back { color: #14532d; font-weight: 500; font-size: .9rem; text-decoration: none; margin-top: .75rem; display: inline-block; }
  </style>
</head>
<body>
<?php include __DIR__ . '/../components/main-nav.php'; ?>

<main>
  <div class="container">
    <div class="ty-card">
      <div class="ty-icon"><i class="fas fa-check"></i></div>
      <h1>Thank You!</h1>
      <p>Your inquiry has been received. Our team will review your details and contact you within <strong>24 hours</strong>.</p>

      <div class="next-steps">
        <div class="next-step">
          <span class="step-icon"><i class="fas fa-phone-alt"></i></span>
          <span>We'll call or WhatsApp you to discuss your website maintenance needs.</span>
        </div>
        <div class="next-step">
          <span class="step-icon"><i class="fas fa-search"></i></span>
          <span>We'll run a free audit of your website and share a summary.</span>
        </div>
        <div class="next-step">
          <span class="step-icon"><i class="fas fa-file-alt"></i></span>
          <span>You'll receive a clear proposal with pricing and what's included.</span>
        </div>
      </div>

      <a href="/pentahive/" class="btn-back"><i class="fas fa-arrow-left me-2"></i>Back to Pentahive</a><br>
      <a href="/" class="btn-secondary-back">Or go to MyOMR homepage</a>
    </div>
  </div>
</main>

<script>
/* GA4 — fire generate_lead on the thank-you page for conversion tracking */
(function() {
  if (typeof gtag !== 'function') return;
  gtag('event', 'generate_lead', {
    'conversion_type': 'pentahive_inquiry',
    'source': '<?= $source ?>'
  });
})();
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
