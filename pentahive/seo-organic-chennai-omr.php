<?php
/**
 * Pentahive Website Maintenance - SEO Organic Landing Page Variant
 * Content-rich page optimized for long-tail keyword rankings
 * Target: 2000+ words, comprehensive guide
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
        $subject = 'New Pentahive Inquiry (SEO Organic) - ' . $name;
        $body = renderEmailTemplate('Pentahive Inquiry', "
            <h2>New Website Maintenance Inquiry (SEO Organic)</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Business Name:</strong> " . ($business_name ?: 'Not provided') . "</p>
            <p><strong>Website:</strong> " . ($website ?: 'Not provided') . "</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br($message ?: 'No message provided') . "</p>
            <p><strong>Source:</strong> SEO Organic</p>
        ");
        
        if (sendEmail($to, $subject, $body, 'noreply@myomr.in', 'Pentahive')) {
            $success = 'Thank you! We\'ll contact you within 24 hours.';
            $_POST = [];
        } else {
            $error = 'There was an error sending your message. Please try again or contact us directly.';
        }
    }
}

// SEO Meta - Long-tail keywords optimized
$page_title = "Website Maintenance Service Chennai OMR - Complete Guide 2025 | ₹1999/year";
$page_description = "Complete guide to website maintenance service in Chennai OMR. Affordable website maintenance starting at ₹1999/year. Learn about updates, backups, security, SEO maintenance, and more for OMR Chennai businesses.";
$canonical_url = "https://myomr.in/pentahive/chennai";
$page_keywords = "website maintenance service Chennai, website maintenance OMR, website maintenance Chennai OMR, affordable website maintenance Chennai, website maintenance service OMR, website updates Chennai, website security Chennai, website backup service Chennai";
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
    <meta property="og:type" content="article">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/landing-base.css">
    
    <!-- JSON-LD Structured Data - FAQPage Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "What is website maintenance service?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Website maintenance service includes regular updates, security patches, backups, performance optimization, SEO maintenance, and content updates to keep your website running smoothly and securely."
                }
            },
            {
                "@type": "Question",
                "name": "How much does website maintenance cost in Chennai?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Website maintenance costs vary. Agencies typically charge ₹5000-15000 per month. Our service costs just ₹1999 per year, which is less than ₹200 per month - saving you ₹3000-15000 per month."
                }
            },
            {
                "@type": "Question",
                "name": "What is included in website maintenance service?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Our website maintenance service includes complete access transfer, monthly WordPress and security updates, daily automated backups, security monitoring, performance optimization, SEO maintenance, up to 10 content updates per month, and priority support."
                }
            },
            {
                "@type": "Question",
                "name": "Do I need website maintenance for my business?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes, regular website maintenance is essential for security, performance, and SEO. Without maintenance, your website is vulnerable to security threats, may slow down, and can lose search engine rankings."
                }
            },
            {
                "@type": "Question",
                "name": "Where can I find website maintenance service in OMR Chennai?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Pentahive offers affordable website maintenance service specifically for OMR Chennai businesses. We're based in OMR and understand the local business needs. Starting at ₹1999/year."
                }
            }
        ]
    }
    </script>
    
    <?php include __DIR__ . '/../components/analytics.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/../components/main-nav.php'; ?>
    
    <!-- Hero Section - SEO Optimized -->
    <section class="hero-section py-5" style="margin-top: 60px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="hero-title">Complete Guide to Website Maintenance Service in Chennai OMR (2025)</h1>
                    <p class="hero-subtitle">Everything you need to know about website maintenance for OMR Chennai businesses. Learn about costs, services, and how to choose the right maintenance provider.</p>
                    <div class="hero-cta mt-4">
                        <a href="#contact-form" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-calendar-check me-2"></i>Get Free Website Audit
                        </a>
                        <a href="tel:+919445088028" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-phone me-2"></i>Call Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Table of Contents -->
    <nav class="toc-section py-4 bg-light sticky-top" style="top: 60px; z-index: 999;">
        <div class="container">
            <div class="toc-container">
                <strong>Table of Contents:</strong>
                <a href="#what-is-website-maintenance">What is Website Maintenance?</a>
                <a href="#why-needed">Why is Website Maintenance Needed?</a>
                <a href="#what-included">What's Included in Website Maintenance?</a>
                <a href="#costs">Website Maintenance Costs in Chennai</a>
                <a href="#choosing-provider">Choosing the Right Provider</a>
                <a href="#omr-chennai">Website Maintenance for OMR Chennai Businesses</a>
                <a href="#faq">Frequently Asked Questions</a>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="content-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- What is Website Maintenance -->
                    <section id="what-is-website-maintenance" class="content-block mb-5">
                        <h2>What is Website Maintenance Service?</h2>
                        <p>Website maintenance service involves regular updates, monitoring, and optimization to keep your website secure, fast, and performing well. It's an ongoing process that ensures your website remains functional, secure, and optimized for search engines and users.</p>
                        <p>Unlike website development (creating a new website), maintenance focuses on keeping your existing website up-to-date, secure, and optimized. This includes:</p>
                        <ul>
                            <li><strong>Security Updates:</strong> Installing security patches and updates to protect against vulnerabilities</li>
                            <li><strong>Content Updates:</strong> Updating website content, images, and information as needed</li>
                            <li><strong>Performance Optimization:</strong> Ensuring fast loading times and optimal performance</li>
                            <li><strong>Backup Management:</strong> Regular backups to protect against data loss</li>
                            <li><strong>SEO Maintenance:</strong> Keeping meta tags, sitemaps, and SEO elements updated</li>
                            <li><strong>Plugin/Theme Updates:</strong> Updating WordPress plugins and themes for compatibility and security</li>
                        </ul>
                    </section>
                    
                    <!-- Why is Website Maintenance Needed -->
                    <section id="why-needed" class="content-block mb-5">
                        <h2>Why is Website Maintenance Essential for Your Business?</h2>
                        <p>Many business owners underestimate the importance of regular website maintenance. Here's why it's crucial:</p>
                        
                        <h3>1. Security Protection</h3>
                        <p>Outdated websites are vulnerable to security threats. Hackers constantly look for vulnerabilities in outdated software, plugins, and themes. Regular maintenance ensures your website is protected with the latest security patches.</p>
                        <p>Without maintenance, your website could be compromised, leading to:</p>
                        <ul>
                            <li>Data breaches and customer information theft</li>
                            <li>Malware infections</li>
                            <li>Website defacement</li>
                            <li>Loss of customer trust</li>
                        </ul>
                        
                        <h3>2. Performance Optimization</h3>
                        <p>Over time, websites can slow down due to:</p>
                        <ul>
                            <li>Unoptimized images and media files</li>
                            <li>Outdated plugins and themes</li>
                            <li>Database bloat</li>
                            <li>Missing caching configurations</li>
                        </ul>
                        <p>Regular maintenance includes performance optimization to ensure fast loading times, which improves user experience and search engine rankings.</p>
                        
                        <h3>3. Search Engine Optimization (SEO)</h3>
                        <p>Search engines favor websites that are regularly updated and well-maintained. Regular maintenance helps:</p>
                        <ul>
                            <li>Keep meta tags and descriptions current</li>
                            <li>Update sitemaps for search engine indexing</li>
                            <li>Fix broken links and errors</li>
                            <li>Improve page load speeds</li>
                            <li>Ensure mobile responsiveness</li>
                        </ul>
                        
                        <h3>4. Data Protection</h3>
                        <p>Regular backups are essential for protecting your website data. Without backups, you risk losing:</p>
                        <ul>
                            <li>All website content</li>
                            <li>Customer data and information</li>
                            <li>Business-critical information</li>
                            <li>Years of work and updates</li>
                        </ul>
                        <p>Professional maintenance services include automated daily backups with retention policies to ensure your data is always protected.</p>
                    </section>
                    
                    <!-- What's Included -->
                    <section id="what-included" class="content-block mb-5">
                        <h2>What's Included in Professional Website Maintenance Service?</h2>
                        <p>A comprehensive website maintenance service should include:</p>
                        
                        <h3>1. Complete Access Transfer</h3>
                        <p>You should receive full admin credentials and training to manage your website. This eliminates dependency on developers and gives you control over your digital presence.</p>
                        
                        <h3>2. Regular Updates</h3>
                        <p>Monthly updates for:</p>
                        <ul>
                            <li>WordPress core updates</li>
                            <li>Plugin updates</li>
                            <li>Theme updates</li>
                            <li>Security patches</li>
                            <li>PHP version updates (when compatible)</li>
                        </ul>
                        
                        <h3>3. Backup Management</h3>
                        <p>Automated daily backups with:</p>
                        <ul>
                            <li>30-day retention policy</li>
                            <li>Automated backup scheduling</li>
                            <li>Backup verification and testing</li>
                            <li>Easy restoration process</li>
                        </ul>
                        
                        <h3>4. Security Monitoring</h3>
                        <p>Ongoing security measures including:</p>
                        <ul>
                            <li>Malware scanning and removal</li>
                            <li>Firewall protection</li>
                            <li>Security vulnerability monitoring</li>
                            <li>SSL certificate management</li>
                            <li>Brute force protection</li>
                        </ul>
                        
                        <h3>5. Performance Optimization</h3>
                        <p>Regular optimization to maintain fast loading times:</p>
                        <ul>
                            <li>Image compression and optimization</li>
                            <li>Database optimization</li>
                            <li>Caching configuration</li>
                            <li>CDN setup (if applicable)</li>
                            <li>Page speed monitoring</li>
                        </ul>
                        
                        <h3>6. SEO Maintenance</h3>
                        <p>Keeping your website optimized for search engines:</p>
                        <ul>
                            <li>Meta tag updates</li>
                            <li>Sitemap generation and updates</li>
                            <li>Broken link checking and fixing</li>
                            <li>SEO audit and recommendations</li>
                            <li>Mobile-friendly verification</li>
                        </ul>
                        
                        <h3>7. Content Support</h3>
                        <p>Help with content updates:</p>
                        <ul>
                            <li>Text updates</li>
                            <li>Image uploads and optimization</li>
                            <li>Product/service updates</li>
                            <li>Menu and pricing updates</li>
                            <li>Blog post publishing (if applicable)</li>
                        </ul>
                        
                        <h3>8. Priority Support</h3>
                        <p>Access to support when you need it:</p>
                        <ul>
                            <li>24-48 hour response time</li>
                            <li>Email and phone support</li>
                            <li>Issue resolution assistance</li>
                            <li>Training and guidance</li>
                        </ul>
                    </section>
                    
                    <!-- Costs Section -->
                    <section id="costs" class="content-block mb-5">
                        <h2>Website Maintenance Costs in Chennai: What to Expect</h2>
                        <p>Website maintenance costs in Chennai vary significantly depending on the provider and service level. Here's what you can expect:</p>
                        
                        <h3>Typical Agency Pricing</h3>
                        <p>Most web development agencies in Chennai charge:</p>
                        <ul>
                            <li><strong>Basic Maintenance:</strong> ₹3,000 - ₹5,000 per month</li>
                            <li><strong>Standard Maintenance:</strong> ₹5,000 - ₹10,000 per month</li>
                            <li><strong>Premium Maintenance:</strong> ₹10,000 - ₹15,000+ per month</li>
                        </ul>
                        <p>This translates to ₹36,000 - ₹1,80,000+ per year for website maintenance alone.</p>
                        
                        <h3>Affordable Alternative: Pentahive</h3>
                        <p>Pentahive offers comprehensive website maintenance service for just <strong>₹1,999 per year</strong> - that's less than ₹200 per month!</p>
                        <p>This includes all the essential maintenance services mentioned above, saving you ₹34,000 - ₹1,78,000 per year compared to agency fees.</p>
                        
                        <h3>What Affects Maintenance Costs?</h3>
                        <p>Several factors influence website maintenance costs:</p>
                        <ul>
                            <li><strong>Website Complexity:</strong> More complex websites require more maintenance</li>
                            <li><strong>Update Frequency:</strong> How often you need content updates</li>
                            <li><strong>Security Requirements:</strong> Higher security needs may increase costs</li>
                            <li><strong>Support Level:</strong> Response time and support availability</li>
                            <li><strong>Provider Type:</strong> Agencies typically charge more than specialized maintenance services</li>
                        </ul>
                    </section>
                    
                    <!-- Choosing Provider -->
                    <section id="choosing-provider" class="content-block mb-5">
                        <h2>How to Choose the Right Website Maintenance Provider</h2>
                        <p>Choosing the right website maintenance provider is crucial for your business. Here are key factors to consider:</p>
                        
                        <h3>1. Service Inclusions</h3>
                        <p>Ensure the provider offers comprehensive services including updates, backups, security, and support. Ask for a detailed list of what's included.</p>
                        
                        <h3>2. Pricing Transparency</h3>
                        <p>Look for transparent pricing with no hidden fees. Be wary of providers who charge low initial fees but add charges for every small task.</p>
                        
                        <h3>3. Access Transfer</h3>
                        <p>Choose a provider who will give you complete admin access and training. Avoid providers who keep you locked in without access.</p>
                        
                        <h3>4. Response Time</h3>
                        <p>Check the provider's response time commitment. Most businesses need 24-48 hour response time for urgent issues.</p>
                        
                        <h3>5. Local Understanding</h3>
                        <p>For OMR Chennai businesses, choosing a local provider who understands the local business environment can be beneficial.</p>
                        
                        <h3>6. Track Record</h3>
                        <p>Check reviews, testimonials, and case studies. A provider with a proven track record is more reliable.</p>
                        
                        <h3>7. Scalability</h3>
                        <p>Consider if the provider can scale with your business needs as you grow.</p>
                    </section>
                    
                    <!-- OMR Chennai Specific -->
                    <section id="omr-chennai" class="content-block mb-5">
                        <h2>Website Maintenance Service for OMR Chennai Businesses</h2>
                        <p>OMR (Old Mahabalipuram Road) Chennai is home to thousands of businesses, from IT companies to restaurants, schools to service providers. These businesses need reliable, affordable website maintenance solutions.</p>
                        
                        <h3>Why OMR Businesses Choose Pentahive</h3>
                        <p>Pentahive is specifically designed for OMR Chennai businesses:</p>
                        <ul>
                            <li><strong>Local Understanding:</strong> We understand the unique needs of OMR businesses</li>
                            <li><strong>Affordable Pricing:</strong> ₹1,999/year is affordable for small and medium businesses in OMR</li>
                            <li><strong>Complete Service:</strong> All essential maintenance services included</li>
                            <li><strong>Full Access:</strong> Business owners get complete control over their websites</li>
                            <li><strong>Local Support:</strong> OMR-based team for quick response and support</li>
                        </ul>
                        
                        <h3>OMR Business Types We Serve</h3>
                        <p>We provide website maintenance for various business types in OMR:</p>
                        <ul>
                            <li><strong>Restaurants & Cafes:</strong> Menu updates, hours, special offers</li>
                            <li><strong>Schools & Educational Institutions:</strong> Admission updates, events, news</li>
                            <li><strong>IT Companies:</strong> Portfolio updates, team information, services</li>
                            <li><strong>Service Businesses:</strong> Service area updates, contact information, testimonials</li>
                            <li><strong>Healthcare Providers:</strong> Doctor schedules, services, appointment information</li>
                            <li><strong>Retail Stores:</strong> Product catalogs, pricing, inventory updates</li>
                        </ul>
                        
                        <h3>OMR Areas We Cover</h3>
                        <p>We serve businesses across all OMR areas including:</p>
                        <ul>
                            <li>Perungudi</li>
                            <li>Sholinganallur</li>
                            <li>Navalur</li>
                            <li>Thoraipakkam</li>
                            <li>Kelambakkam</li>
                            <li>Kandhanchavadi</li>
                            <li>Karapakkam</li>
                            <li>Siruseri</li>
                            <li>And all other OMR areas</li>
                        </ul>
                    </section>
                    
                    <!-- FAQ Section -->
                    <section id="faq" class="content-block mb-5">
                        <h2>Frequently Asked Questions About Website Maintenance</h2>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        What is website maintenance service?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Website maintenance service includes regular updates, security patches, backups, performance optimization, SEO maintenance, and content updates to keep your website running smoothly and securely.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        How much does website maintenance cost in Chennai?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Website maintenance costs vary. Agencies typically charge ₹5000-15000 per month. Our service costs just ₹1999 per year, which is less than ₹200 per month - saving you ₹3000-15000 per month.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        What is included in website maintenance service?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Our website maintenance service includes complete access transfer, monthly WordPress and security updates, daily automated backups, security monitoring, performance optimization, SEO maintenance, up to 10 content updates per month, and priority support.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                        Do I need website maintenance for my business?
                                    </button>
                                </h2>
                                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Yes, regular website maintenance is essential for security, performance, and SEO. Without maintenance, your website is vulnerable to security threats, may slow down, and can lose search engine rankings.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                        Where can I find website maintenance service in OMR Chennai?
                                    </button>
                                </h2>
                                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Pentahive offers affordable website maintenance service specifically for OMR Chennai businesses. We're based in OMR and understand the local business needs. Starting at ₹1999/year.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                        Will I get full access to my website?
                                    </button>
                                </h2>
                                <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Yes, you will receive complete admin credentials and training. You'll have full control to make updates yourself, and we'll also maintain it for you.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                                        How quickly do you respond to support requests?
                                    </button>
                                </h2>
                                <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        We aim to respond to all support requests within 24-48 hours. For urgent issues, please call us directly.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">
                                        Do you work with all types of websites?
                                    </button>
                                </h2>
                                <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        We primarily work with WordPress and PHP-based websites. If you have a different platform, contact us to discuss your specific needs.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    
                    <!-- Internal Links to MyOMR -->
                    <section class="content-block mb-5">
                        <h2>Related Resources on MyOMR.in</h2>
                        <p>Explore more resources for OMR Chennai businesses:</p>
                        <ul>
                            <li><a href="/omr-listings/restaurants.php">Restaurants in OMR Chennai</a></li>
                            <li><a href="/omr-listings/schools.php">Schools in OMR Chennai</a></li>
                            <li><a href="/omr-listings/it-companies.php">IT Companies in OMR</a></li>
                            <li><a href="/local-news/news-highlights-from-omr-road.php">OMR News & Updates</a></li>
                            <li><a href="/about-myomr-omr-community-portal.php">About MyOMR.in</a></li>
                        </ul>
                    </section>
                    
                    <!-- CTA Section -->
                    <section class="cta-section py-5 bg-light">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-8 mx-auto text-center">
                                    <h2>Ready to Get Started with Website Maintenance?</h2>
                                    <p class="lead">Join OMR Chennai businesses using our affordable website maintenance service. Get complete website maintenance for just ₹1999/year.</p>
                                    <div class="cta-buttons mt-4">
                                        <a href="#contact-form" class="btn btn-primary btn-lg me-3">
                                            <i class="fas fa-calendar-check me-2"></i>Get Free Website Audit
                                        </a>
                                        <a href="tel:+919445088028" class="btn btn-outline-primary btn-lg">
                                            <i class="fas fa-phone me-2"></i>Call Us Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar-sticky">
                        <div class="sidebar-card mb-4">
                            <h3>Get Free Website Audit</h3>
                            <p>Get a comprehensive audit report analyzing your website's security, performance, SEO, and more.</p>
                            <a href="#contact-form" class="btn btn-primary w-100">
                                <i class="fas fa-gift me-2"></i>Request Free Audit
                            </a>
                        </div>
                        
                        <div class="sidebar-card mb-4">
                            <h4>Quick Contact</h4>
                            <p><i class="fas fa-phone me-2"></i> <a href="tel:+919445088028">+91 94450 88028</a></p>
                            <p><i class="fas fa-envelope me-2"></i> <a href="mailto:info@myomr.in">info@myomr.in</a></p>
                            <p><i class="fas fa-map-marker-alt me-2"></i> OMR Chennai</p>
                        </div>
                        
                        <div class="sidebar-card">
                            <h4>Service Areas</h4>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i> Perungudi</li>
                                <li><i class="fas fa-check text-success me-2"></i> Sholinganallur</li>
                                <li><i class="fas fa-check text-success me-2"></i> Navalur</li>
                                <li><i class="fas fa-check text-success me-2"></i> Thoraipakkam</li>
                                <li><i class="fas fa-check text-success me-2"></i> Kelambakkam</li>
                                <li><i class="fas fa-check text-success me-2"></i> All OMR Areas</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Contact Form -->
    <section id="contact-form" class="contact-section py-5">
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
    <script src="assets/js/landing-base.js"></script>
    
    <!-- Analytics -->
    <script>
    function gtagSend(name, params) {
        try { if (typeof gtag === 'function') { gtag('event', name, params || {}); } } catch(e) {}
    }
    
    gtagSend('landing_page_view', {
        page_title: 'Pentahive SEO Organic Landing Page',
        traffic_source: 'seo_organic',
        content_type: 'comprehensive_guide'
    });
    </script>
    
    <style>
    /* SEO Content Page Styles */
    .toc-section {
        border-bottom: 2px solid #e5e7eb;
    }
    
    .toc-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }
    
    .toc-container a {
        color: var(--primary-color);
        text-decoration: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        background: white;
        transition: all 0.3s ease;
    }
    
    .toc-container a:hover {
        background: var(--primary-color);
        color: white;
    }
    
    .content-block {
        margin-bottom: 3rem;
    }
    
    .content-block h2 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--primary-color);
    }
    
    .content-block h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .content-block p {
        margin-bottom: 1rem;
        line-height: 1.8;
        color: var(--text-dark);
    }
    
    .content-block ul {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }
    
    .content-block li {
        margin-bottom: 0.5rem;
        line-height: 1.8;
    }
    
    .sidebar-sticky {
        position: sticky;
        top: 100px;
    }
    
    .sidebar-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
    }
    
    .sidebar-card h3,
    .sidebar-card h4 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--text-dark);
    }
    
    .sidebar-card p {
        color: var(--text-light);
        margin-bottom: 0.5rem;
    }
    
    .sidebar-card a {
        color: var(--primary-color);
        text-decoration: none;
    }
    
    .sidebar-card a:hover {
        text-decoration: underline;
    }
    
    @media (max-width: 991px) {
        .sidebar-sticky {
            position: relative;
            top: 0;
        }
    }
    </style>
    
</body>
</html>

