<?php 
include 'weblog/log.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About MyOMR: The Local Community Portal for OMR and Neighbourhood | MyOMR.in</title>
    <meta name="description" content="About MyOMR - Your trusted local community portal connecting OMR residents with news, events, services, and happenings along the Old Mahabalipuram Road IT Corridor. Discover our vision, services, and areas we cover.">
    <meta name="keywords" content="About MyOMR, OMR community portal, Old Mahabalipuram Road, OMR residents, IT Corridor Chennai, local news network, OMR community">
    
    <!-- Open Graph -->
    <meta property="og:title" content="About MyOMR: The Local Community Portal for OMR and Neighbourhood">
    <meta property="og:description" content="Your trusted local community portal connecting OMR residents with news, events, and happenings along Old Mahabalipuram Road, Chennai.">
    <meta property="og:image" content="https://myomr.in/My-OMR-Logo.jpg">
    <meta property="og:url" content="https://myomr.in/about-myomr-omr-community-portal.php">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="About MyOMR: The Local Community Portal for OMR Residents">
    <meta name="twitter:description" content="Your trusted local community portal connecting OMR residents with news, events, and happenings along Old Mahabalipuram Road, Chennai.">
    <meta name="twitter:image" content="https://myomr.in/My-OMR-Logo.jpg">
    
    <!-- Canonical -->
    <link rel="canonical" href="https://myomr.in/about-myomr-omr-community-portal.php">
    
    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="icon" href="/My-OMR-Logo.jpg" type="image/jpeg">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --omr-primary: #008552;
            --omr-secondary: #22c55e;
            --omr-accent: #14532d;
            --omr-light: #f0fdf4;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            color: #333;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #0f5132 0%, #22c55e 100%);
            color: white;
            padding: 80px 0 60px;
            text-align: center;
        }
        
        .hero-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .hero-section p {
            font-size: 1.25rem;
            margin-bottom: 0;
            opacity: 0.95;
        }
        
        .section-title {
            font-family: 'Playfair Display', serif;
            color: var(--omr-primary);
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--omr-secondary);
        }
        
        .section-padding {
            padding: 60px 0;
        }
        
        .vision-card, .service-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .vision-card:hover, .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.15);
        }
        
        .vision-card h3 {
            color: var(--omr-primary);
            font-family: 'Playfair Display', serif;
            margin-bottom: 15px;
        }
        
        .icon-box {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0f5132 0%, #22c55e 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .icon-box i {
            font-size: 2.5rem;
            color: white;
        }
        
        .service-card h4 {
            color: var(--omr-primary);
            font-family: 'Playfair Display', serif;
            margin-top: 20px;
            margin-bottom: 15px;
        }
        
        .stats-box {
            background: var(--omr-light);
            border-left: 5px solid var(--omr-primary);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .stats-box h2 {
            color: var(--omr-primary);
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin: 0;
        }
        
        .area-tag {
            display: inline-block;
            background: var(--omr-light);
            color: var(--omr-primary);
            padding: 8px 16px;
            margin: 5px;
            border-radius: 20px;
            font-size: 0.9rem;
            border: 2px solid var(--omr-secondary);
        }
        
        .cta-section {
            background: linear-gradient(135deg, #0f5132 0%, #22c55e 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        
        .cta-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .btn-omr {
            background: white;
            color: var(--omr-primary);
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 30px;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-omr:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
            color: white;
            background: var(--omr-accent);
        }
        
        .timeline-item {
            position: relative;
            padding-left: 40px;
            margin-bottom: 30px;
            border-left: 3px solid var(--omr-secondary);
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 0;
            width: 20px;
            height: 20px;
            background: var(--omr-primary);
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 0 0 3px var(--omr-secondary);
        }
        
        .social-links a {
            display: inline-block;
            width: 50px;
            height: 50px;
            background: var(--omr-primary);
            color: white;
            text-align: center;
            line-height: 50px;
            border-radius: 50%;
            margin: 0 10px;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--omr-secondary);
            transform: translateY(-5px);
        }
        
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
        }
    </style>
    
    <!-- Analytics -->
    <?php include 'components/analytics.php'; ?>
</head>
<body>

<?php include 'components/main-nav.php'; ?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <h1>About MyOMR</h1>
        <p>Your Local Community Portal Connecting OMR and Its Neighbourhood Residents</p>
    </div>
</div>

<!-- Vision & Mission Section -->
<section class="section-padding" style="background: #f8f9fa;">
    <div class="container">
        <h2 class="section-title">Our Vision & Mission</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="vision-card">
                    <div class="icon-box">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p>To become the most trusted local community platform that empowers OMR residents with timely information, connects neighbors, and fosters collective growth through sharing experiences, resources, and knowledge along the Old Mahabalipuram Road corridor.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="vision-card">
                    <div class="icon-box">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p>Create an active, engaged community of OMR residents by providing comprehensive news coverage, event information, business listings, and civic resources while promoting collective action on social and civic issues affecting our locality.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What We Do Section -->
<section class="section-padding">
    <div class="container">
        <h2 class="section-title">What We Do</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="service-card">
                    <div class="icon-box">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h4>Local News & Updates</h4>
                    <p>Stay informed with latest news, events, and happenings specifically relevant to OMR residents, IT companies, and the surrounding neighborhoods.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="icon-box">
                        <i class="fas fa-building"></i>
                    </div>
                    <h4>Business Directory</h4>
                    <p>Discover local businesses, services, restaurants, and commercial establishments along OMR. Find what you need in your neighborhood.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="icon-box">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h4>Job Portal</h4>
                    <p>Connect job seekers with opportunities at IT companies and local businesses. Post vacancies or find your next career move on OMR.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="icon-box">
                        <i class="fas fa-home"></i>
                    </div>
                    <h4>Real Estate</h4>
                    <p>Search for rental properties, buy or sell homes, plots, and commercial spaces across OMR locations.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="icon-box">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h4>Events & Happenings</h4>
                    <p>Never miss community events, festivals, workshops, seminars, and social gatherings in and around OMR.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="icon-box">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <h4>Civic Issues</h4>
                    <p>Raise awareness about civic problems, infrastructure needs, and community concerns. Advocate for better facilities and services.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Community Impact Section -->
<section class="section-padding" style="background: var(--omr-light);">
    <div class="container">
        <h2 class="section-title">Our Community Impact</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="stats-box text-center">
                    <h2 class="text-primary">500+</h2>
                    <p class="mb-0"><strong>Active Community Members</strong></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-box text-center">
                    <h2 class="text-primary">100+</h2>
                    <p class="mb-0"><strong>Local News Articles</strong></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-box text-center">
                    <h2 class="text-primary">50+</h2>
                    <p class="mb-0"><strong>Businesses Listed</strong></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-box text-center">
                    <h2 class="text-primary">45km</h2>
                    <p class="mb-0"><strong>OMR Coverage Area</strong></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Areas We Cover -->
<section class="section-padding">
    <div class="container">
        <h2 class="section-title">Areas We Cover</h2>
        <div class="text-center mb-4">
            <p class="lead">We cover the entire 45 km stretch of Old Mahabalipuram Road (OMR) from Madhya Kailash to Mahabalipuram, including all adjacent residential and commercial areas:</p>
        </div>
        <div class="text-center">
            <span class="area-tag">Adyar</span>
            <span class="area-tag">Tharamani</span>
            <span class="area-tag">Kandanchavadi</span>
            <span class="area-tag">SRP Tools</span>
            <span class="area-tag">Perungudi</span>
            <span class="area-tag">Thoraipakkam</span>
            <span class="area-tag">Karapakkam</span>
            <span class="area-tag">Pallikaranai</span>
            <span class="area-tag">Sholinganallur</span>
            <span class="area-tag">Navalur</span>
            <span class="area-tag">Kelambakkam</span>
            <span class="area-tag">Siruseri</span>
            <span class="area-tag">Padur</span>
            <span class="area-tag">Vandalur</span>
            <span class="area-tag">Thazhambur</span>
            <span class="area-tag">Mettukuppam</span>
        </div>
    </div>
</section>

<!-- Our Journey -->
<section class="section-padding" style="background: #f8f9fa;">
    <div class="container">
        <h2 class="section-title">Our Journey</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="timeline-item">
                    <h4><strong>The Birth of a Community Portal</strong></h4>
                    <p class="text-muted">Established to serve the rapidly growing IT Corridor</p>
                    <p>Recognizing the need for a centralized information hub for the expanding OMR community, MyOMR was created to bridge the gap between residents, businesses, and happenings along the Old Mahabalipuram Road.</p>
                </div>
                <div class="timeline-item">
                    <h4><strong>Building Connections</strong></h4>
                    <p class="text-muted">Connecting neighborhoods and communities</p>
                    <p>From Kandanchavadi to Navalur, Perungudi to Sholinganallur, we've worked tirelessly to ensure every resident has access to local news, services, and resources relevant to their area.</p>
                </div>
                <div class="timeline-item">
                    <h4><strong>Empowering Residents</strong></h4>
                    <p class="text-muted">Creating an active, informed community</p>
                    <p>Through news coverage, business directories, job postings, and civic advocacy, we help OMR residents make informed decisions and actively participate in community development.</p>
                </div>
                <div class="timeline-item">
                    <h4><strong>Today & Tomorrow</strong></h4>
                    <p class="text-muted">Continuing to grow and evolve</p>
                    <p>As OMR continues to develop into Chennai's prime IT and residential corridor, MyOMR evolves to meet new challenges and opportunities, always keeping the community at the heart of everything we do.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
    <div class="container">
        <h2>Join Our Community</h2>
        <p class="lead mb-4">Be part of the active OMR community. Share news, discover events, connect with neighbors, and help make our locality better!</p>
        <div class="mb-4">
            <a href="/events/" class="btn-omr mr-3">View Events</a>
            <a href="/contact-my-omr-team.php" class="btn-omr" style="background: transparent; border: 2px solid white; color: white;">Get Involved</a>
        </div>
        <div class="social-links">
            <a href="https://www.facebook.com/myomrCommunity" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="https://www.instagram.com/myomrcommunity/" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi" target="_blank"><i class="fab fa-whatsapp"></i></a>
            <a href="https://www.youtube.com/channel/UCyFrgbaQht7C-17m_prn0Rg" target="_blank"><i class="fab fa-youtube"></i></a>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section-padding">
    <div class="container">
        <h2 class="section-title">Get In Touch</h2>
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h4 style="color: var(--omr-primary); font-family: 'Playfair Display', serif;">We'd Love to Hear From You</h4>
                <p class="lead">Have a news tip, event to share, or civic concern? Want to contribute content or partner with us? Reach out and be part of the OMR community!</p>
                <div class="mb-4">
                    <i class="fas fa-envelope" style="color: var(--omr-primary); font-size: 1.5rem;"></i>
                    <p class="mt-2"><strong>Email:</strong> <a href="mailto:myomrnews@gmail.com">myomrnews@gmail.com</a></p>
                </div>
                <div class="mb-4">
                    <i class="fas fa-phone" style="color: var(--omr-primary); font-size: 1.5rem;"></i>
                    <p class="mt-2"><strong>Phone:</strong> <a href="tel:+919445088028">+91 94450 88028</a></p>
                </div>
                <div class="mb-4">
                    <i class="fas fa-map-marker-alt" style="color: var(--omr-primary); font-size: 1.5rem;"></i>
                    <p class="mt-2"><strong>Location:</strong> Old Mahabalipuram Road, Chennai</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'components/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

