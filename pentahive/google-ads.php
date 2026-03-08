<?php
/**
 * Pentahive Website Maintenance - Google Ads Landing Page Variant
 * Optimized for paid search traffic with high-intent keywords
 * 
 * @package Pentahive
 * @version 1.0.0
 * Phase 2: Traffic Source Variants
 */

// Form submission handling (same as index.php)
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    require_once __DIR__ . '/../core/email.php';
    
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $website = htmlspecialchars(trim($_POST['website'] ?? ''));
    $business_name = htmlspecialchars(trim($_POST['business_name'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    
    // Validation
    if (empty($name) || empty($email) || empty($phone)) {
        $error = 'Please fill in all required fields (Name, Email, Phone).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Send email notification
        $to = 'info@myomr.in';
        $subject = 'New Pentahive Inquiry (Google Ads) - ' . $name;
        $body = renderEmailTemplate('Pentahive Inquiry', "
            <h2>New Website Maintenance Inquiry (Google Ads)</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Business Name:</strong> " . ($business_name ?: 'Not provided') . "</p>
            <p><strong>Website:</strong> " . ($website ?: 'Not provided') . "</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br($message ?: 'No message provided') . "</p>
            <p><strong>Source:</strong> Google Ads</p>
        ");
        
        if (sendEmail($to, $subject, $body, 'noreply@myomr.in', 'Pentahive')) {
            header('Location: /pentahive/thank-you.php?src=google_ads');
            exit;
        } else {
            $error = 'There was an error sending your message. Please try again or contact us directly.';
        }
    }
}

// SEO Meta - Google Ads optimized
$page_title = "Website Maintenance Service - ₹1999/year | Free Audit Available";
$page_description = "Affordable website maintenance for Chennai businesses. Just ₹1999/year. Get free website audit. Complete access transfer included.";
$canonical_url = "https://myomr.in/pentahive/google";
$page_keywords = "website maintenance Chennai, website maintenance service, affordable website maintenance, ₹1999 website maintenance";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
    <link rel="canonical" href="<?php echo $canonical_url; ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="og:url" content="<?php echo $canonical_url; ?>">
    <meta property="og:type" content="website">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/landing-base.css">
    <link rel="stylesheet" href="assets/css/landing-google.css">
    
    <?php include __DIR__ . '/../components/analytics.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/../components/main-nav.php'; ?>
    
    <!-- Hero Section - Google Ads Optimized -->
    <section class="hero-section hero-google-ads">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="hero-title">Website Maintenance Service - Just ₹1999/year</h1>
                    <p class="hero-subtitle">Complete website maintenance for Chennai businesses. Get full access, regular updates, daily backups, and priority support. Less than ₹200/month.</p>
                    
                    <div class="hero-cta">
                        <a href="#audit-form" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-gift me-2"></i>Get Free Website Audit
                        </a>
                        <a href="tel:+919445088028" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-phone me-2"></i>Call Now
                        </a>
                    </div>
                    
                    <div class="hero-trust mt-4">
                        <div class="trust-badges">
                            <span class="badge-item"><i class="fas fa-check-circle text-success me-2"></i>50+ Businesses Trust Us</span>
                            <span class="badge-item"><i class="fas fa-check-circle text-success me-2"></i>24-Hour Response</span>
                            <span class="badge-item"><i class="fas fa-check-circle text-success me-2"></i>Complete Access Transfer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Quick Benefits - Minimal Navigation -->
    <section class="quick-benefits py-4 bg-light">
        <div class="container">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="quick-benefit-item text-center">
                        <i class="fas fa-key text-primary fs-3 mb-2"></i>
                        <p class="mb-0"><strong>Full Access</strong><br><small>Complete credentials & training</small></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="quick-benefit-item text-center">
                        <i class="fas fa-sync-alt text-primary fs-3 mb-2"></i>
                        <p class="mb-0"><strong>Regular Updates</strong><br><small>Monthly WordPress & security updates</small></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="quick-benefit-item text-center">
                        <i class="fas fa-cloud-download-alt text-primary fs-3 mb-2"></i>
                        <p class="mb-0"><strong>Daily Backups</strong><br><small>30-day retention included</small></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Problem-Solution Match -->
    <section class="problem-match-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title">Stop Paying ₹5000-15000/month for Website Maintenance</h2>
                    <p class="lead">Most agencies charge ₹5000-15000 per month for basic website maintenance. Our annual service costs just ₹1999 - that's less than ₹200/month!</p>
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> <strong>Save ₹3000-15000/month</strong> compared to agency fees</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> <strong>Get full access</strong> to your website - no dependency</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> <strong>Complete service</strong> - updates, backups, security, SEO</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> <strong>Priority support</strong> - 24-48 hour response time</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="comparison-card">
                        <h4 class="text-center mb-4">Cost Comparison</h4>
                        <div class="comparison-item">
                            <div class="comparison-label">Agency Fees (Monthly)</div>
                            <div class="comparison-value">₹5,000 - ₹15,000</div>
                            <div class="comparison-year">₹60,000 - ₹1,80,000/year</div>
                        </div>
                        <div class="comparison-divider">vs</div>
                        <div class="comparison-item highlight">
                            <div class="comparison-label">Pentahive (Annual)</div>
                            <div class="comparison-value">₹1,999/year</div>
                            <div class="comparison-year">Less than ₹200/month</div>
                        </div>
                        <div class="comparison-savings">
                            <strong>Save ₹58,000 - ₹1,78,000 per year!</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Free Audit Form - Above the Fold -->
    <section id="audit-form" class="audit-form-section py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="audit-form-card">
                        <h2 class="text-center mb-4">Get Your Free Website Audit (Worth ₹2000)</h2>
                        <p class="text-center text-muted mb-4">Get a comprehensive audit report analyzing your website's security, performance, SEO, and more.</p>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($success); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="#audit-form" class="contact-form">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Your Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="website" class="form-label">Website URL <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control" id="website" name="website" placeholder="https://yourwebsite.com" required value="<?php echo htmlspecialchars($_POST['website'] ?? ''); ?>">
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="submit_contact" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-gift me-2"></i>Get My Free Website Audit
                                    </button>
                                    <p class="text-center text-muted mt-3 mb-0">
                                        <small><i class="fas fa-lock me-1"></i> We'll never share your information</small>
                                    </p>
                                    <p class="text-center mt-2 mb-0" style="font-size:.82rem;">
                                        <i class="fas fa-clock text-warning me-1"></i>
                                        Only 5 free audit slots available this week. We'll confirm within 24 hours.
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Trust Elements -->
    <section class="trust-section py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="trust-card text-center">
                        <i class="fas fa-shield-alt fs-1 text-primary mb-3"></i>
                        <h4>Secure & Reliable</h4>
                        <p>Daily backups, security monitoring, and malware protection included.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="trust-card text-center">
                        <i class="fas fa-headset fs-1 text-primary mb-3"></i>
                        <h4>24-Hour Response</h4>
                        <p>Priority support with 24-48 hour response time for all inquiries.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="trust-card text-center">
                        <i class="fas fa-map-marker-alt fs-1 text-primary mb-3"></i>
                        <h4>OMR Chennai Based</h4>
                        <p>Local support team based in OMR Chennai. We understand your business needs.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php include __DIR__ . '/../components/footer.php'; ?>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/landing-base.js"></script>
    
    <!-- Analytics Tracking -->
    <script>
    function gtagSend(name, params) {
        try { if (typeof gtag === 'function') { gtag('event', name, params || {}); } } catch(e) {}
    }
    
    gtagSend('landing_page_view', {
        page_title: 'Pentahive Google Ads Landing Page',
        page_location: window.location.href,
        traffic_source: 'google_ads'
    });
    
    // Track form engagement
    document.querySelectorAll('input, textarea').forEach(field => {
        field.addEventListener('focus', function() {
            gtagSend('form_engagement', {
                field_name: this.name,
                traffic_source: 'google_ads'
            });
        });
    });
    </script>
    
</body>
</html>

