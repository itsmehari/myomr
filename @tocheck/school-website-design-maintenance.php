<?php
/**
 * Industry Page: School Website Design & Maintenance (OMR Chennai)
 * URL: /school-website-design-maintenance
 * Naming agreed: [industry]-website-design-maintenance
 */

$page_title = "School Website Design & Maintenance - OMR Chennai | Pentahive";
$page_description = "School website design, development & maintenance for OMR Chennai. Admission dates, events, notices, SEO & security. Maintenance from ₹1999/year.";
$canonical_url = "https://myomr.in/school-website-design-maintenance";
$page_keywords = "school website maintenance, school website design OMR, admission website updates, Chennai";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($page_title); ?></title>
<meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
<meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
<link rel="canonical" href="<?php echo $canonical_url; ?>">

<meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
<meta property="og:url" content="<?php echo $canonical_url; ?>">
<meta property="og:type" content="website">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
<style>
body{font-family:'Poppins',system-ui,-apple-system,'Segoe UI',sans-serif}
.hero{padding:80px 0;background:linear-gradient(135deg,#f5f7fa,#e0f2fe)}
.hero h1{font-weight:700}
.section-title{font-weight:700}
.card-elev{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.06)}
.badge-list{display:flex;gap:1rem;flex-wrap:wrap}
.badge-list .badge{background:#ecfeff;color:#0e7490;padding:.5rem .75rem;border-radius:999px;font-weight:600}
</style>
<?php include __DIR__ . '/components/analytics.php'; ?>
</head>
<body>
<?php include __DIR__ . '/components/main-nav.php'; ?>

<section class="hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <h1 class="mb-3">School Website Design & Maintenance (OMR Chennai)</h1>
        <p class="lead text-muted mb-4">Keep your school website current: admission dates, events, notices, photos and SEO. Maintenance starts at ₹1999/year. Design and development available on request.</p>
        <div class="d-flex gap-2 flex-wrap">
          <a href="/pentahive/" class="btn btn-primary btn-lg"><i class="fa-solid fa-calendar-check me-2"></i>Get Free Website Audit</a>
          <a href="#features" class="btn btn-outline-primary btn-lg">See What’s Included</a>
        </div>
        <div class="badge-list mt-4">
          <span class="badge">Admissions</span>
          <span class="badge">Events & Notices</span>
          <span class="badge">Photo Updates</span>
          <span class="badge">SEO Basics</span>
          <span class="badge">Daily Backups</span>
        </div>
      </div>
      <div class="col-lg-5 mt-4 mt-lg-0">
        <div class="card-elev p-4">
          <h5 class="mb-3">Designed for Schools</h5>
          <ul class="mb-0 text-muted">
            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>Admission dates and prospectus updates</li>
            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>Events, circulars and notices</li>
            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>Gallery updates (campus, activities)</li>
            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>Google-friendly basics (meta, sitemap)</li>
            <li><i class="fa-solid fa-check text-success me-2"></i>Security, backups and performance checks</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="problems" class="py-5">
  <div class="container">
    <h2 class="section-title mb-4">Common School Website Problems</h2>
    <div class="row g-3">
      <div class="col-md-6 col-lg-4"><div class="card-elev p-4 h-100"><h5>Outdated Admissions</h5><p class="mb-0 text-muted">Old dates and prospectus create confusion for parents.</p></div></div>
      <div class="col-md-6 col-lg-4"><div class="card-elev p-4 h-100"><h5>Events Not Published</h5><p class="mb-0 text-muted">Activities, results and notices not updated on time.</p></div></div>
      <div class="col-md-6 col-lg-4"><div class="card-elev p-4 h-100"><h5>Slow Pages</h5><p class="mb-0 text-muted">Unoptimized images and missing caching slow down the site.</p></div></div>
      <div class="col-md-6 col-lg-4"><div class="card-elev p-4 h-100"><h5>Missing SEO Basics</h5><p class="mb-0 text-muted">No meta, sitemap, or structured data basics in place.</p></div></div>
      <div class="col-md-6 col-lg-4"><div class="card-elev p-4 h-100"><h5>No Backups</h5><p class="mb-0 text-muted">Risk of losing updates and photos without backups.</p></div></div>
      <div class="col-md-6 col-lg-4"><div class="card-elev p-4 h-100"><h5>Access Issues</h5><p class="mb-0 text-muted">No admin access; dependent on third parties for changes.</p></div></div>
    </div>
  </div>
</section>

<section id="features" class="py-5 bg-light">
  <div class="container">
    <h2 class="section-title mb-4">What You Get</h2>
    <div class="row g-3">
      <div class="col-md-6 col-lg-4"><div class="card-elev p-4 h-100"><h5>Design & Development (On Request)</h5><p class="mb-0 text-muted">New pages, redesigns and components built as needed.</p></div></div>
      <div class="col-md-6 col-lg-4"><div class="card-elev p-4 h-100"><h5>Maintenance (₹1999/year)</h5><p class="mb-0 text-muted">Monthly updates, backups, security and performance checks.</p></div></div>
      <div class="col-md-6 col-lg-4"><div class="card-elev p-4 h-100"><h5>Admission & Notice Updates</h5><p class="mb-0 text-muted">Up to 10 content updates/month (text, images, notices, PDFs).</p></div></div>
      <div class="col-md-6 col-lg-4"><div class="card-elev p-4 h-100"><h5>Gallery Management</h5><p class="mb-0 text-muted">Upload and optimize campus and event photos.</p></div></div>
      <div class="col-md-6 col-lg-4"><div class="card-elev p-4 h-100"><h5>SEO Basics</h5><p class="mb-0 text-muted">Meta, sitemap and search-friendly structure.</p></div></div>
      <div class="col-md-6 col-lg-4"><div class="card-elev p-4 h-100"><h5>Reports</h5><p class="mb-0 text-muted">Monthly performance and update summary.</p></div></div>
    </div>
    <div class="text-center mt-4">
      <a href="/pentahive/" class="btn btn-primary btn-lg">Get Free Website Audit</a>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <h2 class="section-title mb-4">Frequently Asked Questions</h2>
    <div class="accordion" id="faqSch">
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#s1">What does ₹1999/year include?</button>
        </h2>
        <div id="s1" class="accordion-collapse collapse show" data-bs-parent="#faqSch">
          <div class="accordion-body text-muted">Monthly updates, security patches, daily backups, performance checks, SEO basics and up to 10 content updates/month.</div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#s2">Can you help with admission updates?</button>
        </h2>
        <div id="s2" class="accordion-collapse collapse" data-bs-parent="#faqSch">
          <div class="accordion-body text-muted">Yes. We publish admission dates, prospectus PDFs, and related notices as part of the monthly updates.</div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#s3">How quickly are updates applied?</button>
        </h2>
        <div id="s3" class="accordion-collapse collapse" data-bs-parent="#faqSch">
          <div class="accordion-body text-muted">We aim for a 24–48 hour response and timely updates depending on request size.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="mb-3">Ready to keep your school website updated?</h2>
    <p class="text-muted mb-4">Start with a free website audit. No commitments.</p>
    <a href="/pentahive/" class="btn btn-primary btn-lg">Start Free Audit</a>
  </div>
</section>

<?php include __DIR__ . '/components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
