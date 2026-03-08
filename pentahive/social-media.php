<?php
/**
 * Pentahive Website Maintenance - Social Media Landing Page Variant
 * Optimized for Facebook/Instagram ads (awareness stage traffic)
 * 
 * @package Pentahive
 * @version 1.0.0
 * Phase 2: Traffic Source Variants
 */

// Form submission handling
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
    
    if (empty($name) || empty($email) || empty($phone)) {
        $error = 'Please fill in all required fields (Name, Email, Phone).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $to = 'info@myomr.in';
        $subject = 'New Pentahive Inquiry (Social Media) - ' . $name;
        $body = renderEmailTemplate('Pentahive Inquiry', "
            <h2>New Website Maintenance Inquiry (Social Media)</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Business Name:</strong> " . ($business_name ?: 'Not provided') . "</p>
            <p><strong>Website:</strong> " . ($website ?: 'Not provided') . "</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br($message ?: 'No message provided') . "</p>
            <p><strong>Source:</strong> Social Media</p>
        ");
        
        if (sendEmail($to, $subject, $body, 'noreply@myomr.in', 'Pentahive')) {
            header('Location: /pentahive/thank-you.php?src=social_media');
            exit;
        } else {
            $error = 'There was an error sending your message. Please try again or contact us directly.';
        }
    }
}

// SEO Meta
$page_title = "Stop Paying ₹5000/month for Website Maintenance | Save Money Now";
$page_description = "Stop overpaying for website maintenance. Get complete website maintenance for just ₹1999/year. Free audit available. OMR Chennai businesses trust us.";
$canonical_url = "https://myomr.in/pentahive/facebook";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <link rel="canonical" href="<?php echo $canonical_url; ?>">
    
    <!-- Open Graph — required for Facebook/Instagram link previews -->
    <meta property="og:title"       content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="og:url"         content="<?php echo $canonical_url; ?>">
    <meta property="og:type"        content="website">
    <meta property="og:image"       content="https://myomr.in/My-OMR-Logo.jpg">
    <meta property="og:site_name"   content="MyOMR — Pentahive">
    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?php echo htmlspecialchars($page_title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="twitter:image"       content="https://myomr.in/My-OMR-Logo.jpg">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/landing-base.css">
    <link rel="stylesheet" href="assets/css/landing-social.css">
    
    <?php include __DIR__ . '/../components/analytics.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/../components/main-nav.php'; ?>
    
    <!-- Hero Section - Problem-Focused -->
    <section class="hero-section hero-social">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">Stop Paying ₹5000/month for Website Maintenance</h1>
                    <p class="hero-subtitle">Most businesses pay ₹5000-15000 per month to agencies. Get the same service for just ₹1999/year - that's less than ₹200/month!</p>
                    <div class="hero-cta">
                        <a href="#audit-form" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-gift me-2"></i>Get Free Website Audit
                        </a>
                    </div>
                    <div class="hero-stats-inline mt-4">
                        <div class="stat-inline">
                            <span class="stat-number">₹58,000+</span>
                            <span class="stat-label">Saved per year</span>
                        </div>
                        <div class="stat-inline">
                            <span class="stat-number">₹1999</span>
                            <span class="stat-label">Per year only</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-visual">
                        <div class="comparison-visual">
                            <div class="cost-bad">
                                <div class="cost-label">Agency Fees</div>
                                <div class="cost-amount">₹5,000-15,000/month</div>
                                <div class="cost-year">₹60,000-1,80,000/year</div>
                            </div>
                            <div class="arrow-down">↓</div>
                            <div class="cost-good">
                                <div class="cost-label">Pentahive</div>
                                <div class="cost-amount">₹1,999/year</div>
                                <div class="cost-year">Less than ₹200/month</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Problem Cards - Visual -->
    <section class="problem-cards-section py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">Is Your Website at Risk?</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="problem-card-visual">
                        <div class="problem-icon-large">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h4>Can't Update Website</h4>
                        <p>Dependent on developer for every small change. No control over your own website.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="problem-card-visual">
                        <div class="problem-icon-large">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                        <h4>High Monthly Costs</h4>
                        <p>Paying ₹5000-15000/month to agencies. Costs keep adding up every month.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="problem-card-visual">
                        <div class="problem-icon-large">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Security Vulnerabilities</h4>
                        <p>Website not updated regularly. Risk of malware and security breaches.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="problem-card-visual">
                        <div class="problem-icon-large">
                            <i class="fas fa-database"></i>
                        </div>
                        <h4>No Backups</h4>
                        <p>No backup system. Risk of losing all data if something goes wrong.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="problem-card-visual">
                        <div class="problem-icon-large">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <h4>Slow Performance</h4>
                        <p>Website loading slowly. Affecting user experience and search rankings.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="problem-card-visual">
                        <div class="problem-icon-large">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h4>Locked Out</h4>
                        <p>Don't have admin access. Can't make changes even if you want to.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Social Proof Section -->
    <section class="social-proof-section py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">Trusted by OMR Chennai Businesses</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-stars mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="testimonial-text">"Finally, we have full control of our website. No more waiting for developers or paying high monthly fees. Worth every rupee."</p>
                        <div class="testimonial-author">
                            <strong>Rajesh Kumar</strong><br>
                            <small>Owner, Spice Bay Restaurant, Sholinganallur</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-stars mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="testimonial-text">"We saved over ₹60,000 per year compared to our previous agency. The support is prompt and the service is thorough."</p>
                        <div class="testimonial-author">
                            <strong>Priya Sundar</strong><br>
                            <small>School Administrator, Excel Public School, Navalur</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-stars mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="testimonial-text">"Best investment we made this year. Full access to our website and professional ongoing maintenance at a fraction of agency costs."</p>
                        <div class="testimonial-author">
                            <strong>Anand Selvam</strong><br>
                            <small>Director, Kandhanchavadi Dental Care, Kandhanchavadi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Solution Section -->
    <section class="solution-social-section py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title">Complete Website Maintenance Solution</h2>
                    <p class="lead">Get everything you need to manage and maintain your website - all for just ₹1999/year.</p>
                    <ul class="solution-list">
                        <li><i class="fas fa-check-circle text-success me-2"></i> Complete access transfer & training</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> Monthly WordPress & security updates</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> Daily automated backups (30-day retention)</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> Security monitoring & malware scanning</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> Performance optimization</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> SEO maintenance</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> Up to 10 content updates/month</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> Priority support (24-48 hour response)</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="solution-visual">
                        <div class="feature-grid">
                            <div class="feature-item">
                                <i class="fas fa-key"></i>
                                <span>Full Access</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-sync-alt"></i>
                                <span>Regular Updates</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-cloud-download-alt"></i>
                                <span>Daily Backups</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-shield-virus"></i>
                                <span>Security</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Performance</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-search"></i>
                                <span>SEO</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Audit Form -->
    <section id="audit-form" class="audit-form-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="audit-form-card">
                        <h2 class="text-center mb-4">Get Your Free Website Audit</h2>
                        <p class="text-center text-muted mb-4">Discover what your website needs. Get a comprehensive audit report (worth ₹2000) - completely free!</p>
                        
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
                                    <label for="website" class="form-label">Website URL</label>
                                    <input type="url" class="form-control" id="website" name="website" placeholder="https://yourwebsite.com" value="<?php echo htmlspecialchars($_POST['website'] ?? ''); ?>">
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="submit_contact" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-gift me-2"></i>Get My Free Website Audit
                                    </button>
                                    <p class="text-center text-muted mt-3 mb-0">
                                        <small><i class="fas fa-lock me-1"></i> Your information is safe with us</small>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php include __DIR__ . '/../components/footer.php'; ?>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/landing-base.js"></script>
    
    <!-- Analytics -->
    <script>
    function gtagSend(name, params) {
        try { if (typeof gtag === 'function') { gtag('event', name, params || {}); } } catch(e) {}
    }
    
    gtagSend('landing_page_view', {
        page_title: 'Pentahive Social Media Landing Page',
        traffic_source: 'social_media'
    });
    </script>
    
</body>
</html>

