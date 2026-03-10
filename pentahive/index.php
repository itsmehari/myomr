<?php
/**
 * Pentahive Website Maintenance Service - Primary Landing Page
 * 
 * @package Pentahive
 * @version 1.0.0
 * Phase 1: Primary Landing Page
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
    
    // Validation
    if (empty($name) || empty($email) || empty($phone)) {
        $error = 'Please fill in all required fields (Name, Email, Phone).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Send email notification
        $to = 'info@myomr.in';
        $subject = 'New Pentahive Website Maintenance Inquiry - ' . $name;
        $body = renderEmailTemplate('Pentahive Inquiry', "
            <h2>New Website Maintenance Inquiry</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Business Name:</strong> " . ($business_name ?: 'Not provided') . "</p>
            <p><strong>Website:</strong> " . ($website ?: 'Not provided') . "</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br($message ?: 'No message provided') . "</p>
        ");
        
        if (sendEmail($to, $subject, $body, 'noreply@myomr.in', 'Pentahive')) {
            header('Location: /pentahive/thank-you.php?src=primary');
            exit;
        } else {
            $error = 'There was an error sending your message. Please try again or contact us directly.';
        }
    }
}

// SEO Meta
$page_title = "Pentahive Website Maintenance Service - ₹1999/year | MyOMR";
$page_description = "Affordable website maintenance service for OMR Chennai businesses. Design, development & maintenance starting at ₹1999/year. Free website audit available.";
$canonical_url = "https://myomr.in/pentahive/";
$page_keywords = "website maintenance, website maintenance Chennai, website maintenance OMR, affordable website maintenance, pentahive, website design development maintenance";
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
    <meta property="og:site_name" content="MyOMR">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/landing-base.css">
    
    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Service",
        "serviceType": "Website Maintenance Service",
        "provider": {
            "@type": "Organization",
            "name": "Pentahive Technology Solutions",
            "url": "https://myomr.in/pentahive/",
            "logo": "https://myomr.in/My-OMR-Logo.png"
        },
        "areaServed": {
            "@type": "City",
            "name": "Chennai",
            "containedIn": {
                "@type": "State",
                "name": "Tamil Nadu"
            }
        },
        "offers": {
            "@type": "Offer",
            "price": "1999",
            "priceCurrency": "INR",
            "priceSpecification": {
                "@type": "UnitPriceSpecification",
                "price": "1999",
                "priceCurrency": "INR",
                "valueAddedTaxIncluded": true
            }
        }
    }
    </script>
    
    <?php include __DIR__ . '/../components/analytics.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/../components/main-nav.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="hero-title">Website Maintenance Service - Starting at ₹1999/year</h1>
                    <p class="hero-subtitle">Complete website solutions: design, development, and ongoing maintenance for OMR Chennai businesses. Get full control of your website without the high agency costs.</p>
                    
                    <div class="hero-cta">
                        <a href="#contact-form" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-calendar-check me-2"></i>Get Free Website Audit
                        </a>
                        <a href="#pricing" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-rupee-sign me-2"></i>View Pricing
                        </a>
                    </div>
                    
                    <div class="hero-trust mt-4">
                        <div class="trust-badges">
                            <span class="badge-item"><i class="fas fa-check-circle text-success me-2"></i>24-Hour Response</span>
                            <span class="badge-item"><i class="fas fa-check-circle text-success me-2"></i>Complete Access Transfer</span>
                            <span class="badge-item"><i class="fas fa-check-circle text-success me-2"></i>OMR Chennai Based</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-image">
                        <div class="hero-stats">
                            <div class="stat-card">
                                <div class="stat-number">₹1999</div>
                                <div class="stat-label">Per Year</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">24/7</div>
                                <div class="stat-label">Support</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">100%</div>
                                <div class="stat-label">Access</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Problem Section -->
    <section class="problem-section py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">Facing These Website Problems?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="problem-card">
                        <div class="problem-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h4>Can't Update Content</h4>
                        <p>Dependent on developer or agency for every small update. No access to your own website.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="problem-card">
                        <div class="problem-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h4>High Maintenance Costs</h4>
                        <p>Paying ₹5000-15000/month to agencies for basic updates. Costs keep adding up.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="problem-card">
                        <div class="problem-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Security Concerns</h4>
                        <p>Website not updated regularly. Vulnerable to security threats and malware.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="problem-card">
                        <div class="problem-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <h4>No Backups</h4>
                        <p>No backup system in place. Risk of losing all data if something goes wrong.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="problem-card">
                        <div class="problem-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <h4>Slow Performance</h4>
                        <p>Website loading slowly. Affecting user experience and search rankings.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="problem-card">
                        <div class="problem-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h4>SEO Not Maintained</h4>
                        <p>Meta tags outdated, sitemaps not updated. Missing out on search visibility.</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="#solutions" class="btn btn-outline-primary btn-lg">See How We Solve These <i class="fas fa-arrow-down ms-2"></i></a>
            </div>
        </div>
    </section>
    
    <!-- Solution Section -->
    <section id="solutions" class="solution-section py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">Complete Website Maintenance Solution</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <h4>Full Access Transfer</h4>
                        <p>Get complete admin credentials and training. Full control of your website.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <h4>Regular Updates</h4>
                        <p>Monthly updates for WordPress, plugins, and security patches.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-cloud-download-alt"></i>
                        </div>
                        <h4>Daily Backups</h4>
                        <p>Automated daily backups with 30-day retention. Your data is safe.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-virus"></i>
                        </div>
                        <h4>Security Monitoring</h4>
                        <p>Malware scanning, firewall protection, and security monitoring.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <h4>Performance Optimization</h4>
                        <p>Speed optimization, image compression, and caching for faster load times.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h4>SEO Maintenance</h4>
                        <p>Meta tags, sitemap updates, and SEO audits to maintain visibility.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h4>Content Support</h4>
                        <p>Up to 10 content updates per month. Menu, hours, products, and more.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4>Priority Support</h4>
                        <p>24-48 hour response time. Email and phone support included.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Pricing Section -->
    <section id="pricing" class="pricing-section py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">Simple, Affordable Pricing</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3>Website Maintenance</h3>
                            <div class="pricing-amount">
                                <span class="currency">₹</span>
                                <span class="amount">1,999</span>
                                <span class="period">/year</span>
                            </div>
                            <p class="pricing-subtitle">Less than ₹200/month</p>
                        </div>
                        <div class="pricing-features">
                            <ul>
                                <li><i class="fas fa-check text-success me-2"></i>Complete access transfer & training</li>
                                <li><i class="fas fa-check text-success me-2"></i>Monthly updates (WordPress, plugins, security)</li>
                                <li><i class="fas fa-check text-success me-2"></i>Daily automated backups (30-day retention)</li>
                                <li><i class="fas fa-check text-success me-2"></i>Security monitoring & malware scanning</li>
                                <li><i class="fas fa-check text-success me-2"></i>Performance optimization</li>
                                <li><i class="fas fa-check text-success me-2"></i>SEO maintenance (meta tags, sitemaps)</li>
                                <li><i class="fas fa-check text-success me-2"></i>Up to 10 content updates/month</li>
                                <li><i class="fas fa-check text-success me-2"></i>Priority support (24-48 hour response)</li>
                                <li><i class="fas fa-check text-success me-2"></i>Monthly performance reports</li>
                            </ul>
                        </div>
                        <div class="pricing-cta">
                            <a href="#contact-form" class="btn btn-primary btn-lg w-100">
                                Get Started Today
                            </a>
                        </div>
                        <div class="pricing-comparison mt-4">
                            <p class="text-center text-muted">
                                <small>Save ₹3000-15000/month compared to agency fees</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- How It Works -->
    <section class="how-it-works-section py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">How It Works</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h4>Sign Up</h4>
                        <p>Fill out the contact form or request a free website audit.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h4>Free Audit</h4>
                        <p>We analyze your website and provide a comprehensive audit report.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h4>Access Transfer</h4>
                        <p>Get full admin credentials and training on managing your website.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h4>Ongoing Support</h4>
                        <p>We maintain your website with regular updates, backups, and support.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="faq-section py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">Frequently Asked Questions</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    What is included in the ₹1999/year maintenance service?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    The service includes complete access transfer, monthly updates (WordPress, plugins, security), daily automated backups, security monitoring, performance optimization, SEO maintenance, up to 10 content updates per month, and priority support with 24-48 hour response time.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Will I get full access to my website?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, you will receive complete admin credentials and training. You'll have full control to make updates yourself, and we'll also maintain it for you.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    What if I need more than 10 content updates per month?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Additional content updates are available at ₹200 per update. Most businesses find 10 updates per month sufficient for their needs.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Do you work with all types of websites?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We primarily work with WordPress and PHP-based websites. If you have a different platform, contact us to discuss your specific needs.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    How quickly do you respond to support requests?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We aim to respond to all support requests within 24-48 hours. For urgent issues, please call us directly.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">What OMR Businesses Say</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card h-100">
                        <div class="testimonial-stars mb-3">
                            <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="testimonial-text">"We went from paying ₹8,000/month to an agency to just ₹1,999/year with Pentahive. The savings are real and the service is just as good — if not better."</p>
                        <div class="testimonial-author mt-3">
                            <strong>Rajesh Kumar</strong><br>
                            <small class="text-muted">Owner, Spice Bay Restaurant, Sholinganallur</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card h-100">
                        <div class="testimonial-stars mb-3">
                            <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="testimonial-text">"We saved over ₹60,000 per year. The team is responsive, the backups run automatically, and our website is faster than ever."</p>
                        <div class="testimonial-author mt-3">
                            <strong>Priya Sundar</strong><br>
                            <small class="text-muted">Administrator, Excel Public School, Navalur</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card h-100">
                        <div class="testimonial-stars mb-3">
                            <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="testimonial-text">"Best investment we made this year. Full access to our website, professional maintenance, and a local team that actually picks up the phone."</p>
                        <div class="testimonial-author mt-3">
                            <strong>Anand Selvam</strong><br>
                            <small class="text-muted">Director, Kandhanchavadi Dental Care, Kandhanchavadi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section id="contact-form" class="contact-section py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="section-title text-center mb-5">Get Your Free Website Audit</h2>
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
                    <form method="POST" action="#contact-form" class="contact-form">
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
                                <label for="business_name" class="form-label">Business Name</label>
                                <input type="text" class="form-control" id="business_name" name="business_name" value="<?php echo htmlspecialchars($_POST['business_name'] ?? ''); ?>">
                            </div>
                            <div class="col-12">
                                <label for="website" class="form-label">Website URL</label>
                                <input type="url" class="form-control" id="website" name="website" placeholder="https://yourwebsite.com" value="<?php echo htmlspecialchars($_POST['website'] ?? ''); ?>">
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Tell us about your website maintenance needs..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" name="submit_contact" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Request Free Website Audit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <?php include __DIR__ . '/../components/footer.php'; ?>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/landing-base.js"></script>
    
    <!-- Analytics Tracking -->
    <script>
    function gtagSend(name, params) {
        try { 
            if (typeof gtag === 'function') { 
                gtag('event', name, params || {}); 
            } 
        } catch(e) {}
    }
    
    // Track landing page view
    gtagSend('landing_page_view', {
        page_title: 'Pentahive Primary Landing Page',
        page_location: window.location.href
    });
    
    // Track CTA clicks
    document.querySelectorAll('a[href="#contact-form"]').forEach(link => {
        link.addEventListener('click', function() {
            gtagSend('cta_click', {
                cta_text: this.textContent.trim(),
                destination: '#contact-form'
            });
        });
    });
    </script>
    
</body>
</html>

