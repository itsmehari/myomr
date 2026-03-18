<?php
/**
 * Pentahive Website Maintenance - MyOMR.in Internal Landing Page Variant
 * Optimized for MyOMR.in banner ad traffic (warm audience)
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
        $subject = 'New Pentahive Inquiry (MyOMR Internal) - ' . $name;
        $body = renderEmailTemplate('Pentahive Inquiry', "
            <h2>New Website Maintenance Inquiry (MyOMR Internal)</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Business Name:</strong> " . ($business_name ?: 'Not provided') . "</p>
            <p><strong>Website:</strong> " . ($website ?: 'Not provided') . "</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br($message ?: 'No message provided') . "</p>
            <p><strong>Source:</strong> MyOMR.in Internal</p>
        ");
        
        if (sendEmail($to, $subject, $body, 'noreply@myomr.in', 'Pentahive')) {
            header('Location: /pentahive/thank-you.php?src=myomr_internal');
            exit;
        } else {
            $error = 'There was an error sending your message. Please try again or contact us directly.';
        }
    }
}

// SEO Meta
$page_title = "Website Maintenance by MyOMR.in Team | ₹1999/year | OMR Chennai";
$page_description = "Website maintenance service by the team behind MyOMR.in. Supporting OMR Chennai businesses with affordable maintenance starting at ₹1999/year.";
$canonical_url = "https://myomr.in/pentahive/community";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/landing-base.css">
    <link rel="stylesheet" href="assets/css/landing-myomr.css">
    
    <?php include __DIR__ . '/../components/analytics.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/../components/main-nav.php'; ?>
    
    <!-- Hero Section - Brand Association -->
    <section class="hero-section hero-myomr">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="brand-badge mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span>Built by the team behind MyOMR.in</span>
                    </div>
                    <h1 class="hero-title">Website Maintenance by MyOMR.in Team</h1>
                    <p class="hero-subtitle">Supporting OMR Chennai businesses with affordable website maintenance. Get complete website solutions starting at ₹1999/year.</p>
                    
                    <div class="hero-cta">
                        <a href="#contact-form" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-handshake me-2"></i>Get Started
                        </a>
                        <a href="#pricing" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-rupee-sign me-2"></i>View Pricing
                        </a>
                    </div>
                    
                    <div class="hero-trust mt-4">
                        <div class="trust-badges">
                            <span class="badge-item"><i class="fas fa-check-circle text-success me-2"></i>OMR Chennai Businesses Trust Us</span>
                            <span class="badge-item"><i class="fas fa-check-circle text-success me-2"></i>Supporting Local Businesses</span>
                            <span class="badge-item"><i class="fas fa-check-circle text-success me-2"></i>MyOMR.in Community Special</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-stats">
                        <div class="stat-card">
                            <div class="stat-number">₹1999</div>
                            <div class="stat-label">Per Year</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">OMR Businesses</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">100%</div>
                            <div class="stat-label">Local Support</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Local Focus Section -->
    <section class="local-focus-section py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title">Built for OMR Chennai Businesses</h2>
                    <p class="lead">We understand the unique needs of OMR Chennai businesses. From restaurants to schools, from IT companies to service providers - we've helped businesses across OMR maintain their websites affordably.</p>
                    <ul class="local-benefits">
                        <li><i class="fas fa-map-marker-alt text-success me-2"></i> <strong>OMR Chennai Based:</strong> Local team, local understanding</li>
                        <li><i class="fas fa-users text-success me-2"></i> <strong>Community Focus:</strong> Supporting MyOMR.in community members</li>
                        <li><i class="fas fa-clock text-success me-2"></i> <strong>Quick Response:</strong> Fast support for OMR businesses</li>
                        <li><i class="fas fa-handshake text-success me-2"></i> <strong>Trusted Service:</strong> Built by the team you already know</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="omr-areas">
                        <h4 class="mb-4">Serving Businesses Across OMR:</h4>
                        <div class="area-grid">
                            <div class="area-item">Perungudi</div>
                            <div class="area-item">Sholinganallur</div>
                            <div class="area-item">Navalur</div>
                            <div class="area-item">Thoraipakkam</div>
                            <div class="area-item">Kelambakkam</div>
                            <div class="area-item">Kandhanchavadi</div>
                            <div class="area-item">Karapakkam</div>
                            <div class="area-item">Siruseri</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Community Special -->
    <section class="community-special-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="special-card">
                        <div class="special-badge">MyOMR.in Community Special</div>
                        <h2 class="text-center mb-4">Special Offer for MyOMR.in Community</h2>
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h3 class="special-price">₹1,999<span class="price-period">/year</span></h3>
                                <p class="lead">Complete website maintenance for MyOMR.in community members</p>
                                <ul class="special-features">
                                    <li><i class="fas fa-check-circle text-success me-2"></i> Complete access transfer & training</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i> Monthly updates & security patches</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i> Daily automated backups</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i> Priority support for OMR businesses</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i> Performance optimization</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i> SEO maintenance</li>
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <div class="special-visual">
                                    <div class="savings-highlight">
                                        <div class="savings-label">Save Compared to Agency Fees</div>
                                        <div class="savings-amount">₹58,000+</div>
                                        <div class="savings-period">per year</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials from MyOMR Directory -->
    <section class="testimonials-section py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">Trusted by MyOMR.in Business Directory Members</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="testimonial-card">
                        <div class="testimonial-stars mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="testimonial-text">"As a MyOMR.in directory member, the trust was already there. The website maintenance service has been excellent, affordable, and genuinely saves us time every month."</p>
                        <div class="testimonial-author">
                            <strong>Murugan Chandran</strong><br>
                            <small>Owner, Perungudi Fitness Hub — Listed on MyOMR.in</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="testimonial-card">
                        <div class="testimonial-stars mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="testimonial-text">"We've been using MyOMR.in for our business listing for two years. When we needed website maintenance, we naturally turned to the same team — and they delivered."</p>
                        <div class="testimonial-author">
                            <strong>Kavitha Rajan</strong><br>
                            <small>Manager, Sholinganallur Engineering Works — MyOMR.in Community Member</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Contact Form -->
    <section id="contact-form" class="contact-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="section-title text-center mb-4">Get Started with MyOMR.in Team</h2>
                    <p class="text-center text-muted mb-5">Join other OMR Chennai businesses using our website maintenance service.</p>
                    
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
                                <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Are you listed on MyOMR.in?" value="<?php echo htmlspecialchars($_POST['business_name'] ?? ''); ?>">
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
                                    <i class="fas fa-handshake me-2"></i>Get Started with MyOMR.in Team
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
    <script src="assets/js/landing-base.js"></script>
    
    <!-- Analytics -->
    <script>
    function gtagSend(name, params) {
        try { if (typeof gtag === 'function') { gtag('event', name, params || {}); } } catch(e) {}
    }
    
    gtagSend('landing_page_view', {
        page_title: 'Pentahive MyOMR Internal Landing Page',
        traffic_source: 'myomr_internal'
    });
    </script>
    
</body>
</html>

