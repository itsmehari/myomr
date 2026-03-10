<?php
// Start output buffering to catch any errors
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Spread UN SDG awareness in OMR schools! Empower students, teachers, and parents to become SDG ambassadors. Join MyOMR's mission to make OMR the first SDG-aware community in India.">
    <meta name="keywords" content="UN SDG education, SDG in schools, SDG awareness India, OMR schools SDG, sustainable development education, student SDG ambassadors, parent SDG awareness">
    <meta name="author" content="MyOMR Team">
    <title>SDG Education in OMR Schools | Parents & Students | MyOMR.in</title>
    
    <!-- Google Analytics -->
    <?php 
    if (file_exists('../components/analytics.php')) {
        include '../components/analytics.php';
    }
    ?>
    
    <!-- Meta Tags for Social Sharing -->
    <?php
    $page_title = "SDG Education in OMR Schools | Parents & Students";
    $page_description = "Join MyOMR's mission to spread UN SDG awareness in OMR schools. Empower students, teachers, and parents to become SDG ambassadors and make OMR India's first SDG-aware community.";
    $page_keywords = "UN SDG education, SDG in schools, SDG awareness India, OMR schools SDG";
    $og_title = "SDG Education in OMR Schools | Parents & Students | MyOMR";
    $og_description = "Spread UN SDG awareness in OMR schools! Empower students, teachers, and parents to become SDG ambassadors.";
    $og_image = "https://myomr.in/My-OMR-Logo.png";
    $og_url = "https://myomr.in/discover-myomr/sdg-education-schools.php";
    if (file_exists('../components/meta.php')) {
        include '../components/meta.php';
    }
    ?>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Core tokens -->
    <link rel="stylesheet" href="/assets/css/core.css">
    
    <style>
        :root {
            --sdg-primary: #4CAF50;
            --sdg-secondary: #2E7D32;
            --sdg-accent: #1B5E20;
            --gradient-green: linear-gradient(135deg, #1e3a1e 0%, #3d7a3d 50%, #4CAF50 100%);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.8;
        }
        
        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }
        
        .hero-section {
            background: var(--gradient-green);
            color: white;
            padding: 80px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            opacity: 0.3;
        }
        
        .hero-section h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }
        
        .hero-section .lead {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }
        
        .stat-box {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }
        
        .stat-box:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--sdg-primary);
            display: block;
        }
        
        .stat-label {
            color: #666;
            font-size: 1.1rem;
            margin-top: 10px;
        }
        
        .audience-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            border-top: 5px solid var(--sdg-primary);
            transition: all 0.3s ease;
        }
        
        .audience-card:hover {
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }
        
        .audience-icon {
            font-size: 4rem;
            color: var(--sdg-primary);
            margin-bottom: 20px;
        }
        
        .action-box {
            background: #f8f9fa;
            border-left: 5px solid var(--sdg-primary);
            padding: 25px;
            margin: 20px 0;
            border-radius: 10px;
        }
        
        .action-box h4 {
            color: var(--sdg-accent);
            margin-bottom: 15px;
        }
        
        .action-box ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .action-box li {
            margin: 10px 0;
        }
        
        .resource-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }
        
        .resource-card:hover {
            border-color: var(--sdg-primary);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.2);
        }
        
        .resource-card h5 {
            color: var(--sdg-accent);
            margin-bottom: 15px;
        }
        
        .badge-custom {
            background: var(--gradient-green);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            margin: 5px;
        }
        
        .cta-button {
            background: var(--gradient-green);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-block;
            margin: 10px;
            transition: all 0.3s ease;
            border: none;
        }
        
        .cta-button:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            color: white;
        }
        
        .problem-section {
            background: #fff3cd;
            border-left: 5px solid #ffc107;
            padding: 30px;
            border-radius: 10px;
            margin: 40px 0;
        }
        
        .solution-section {
            background: #d1ecf1;
            border-left: 5px solid #17a2b8;
            padding: 30px;
            border-radius: 10px;
            margin: 40px 0;
        }
        
        .vision-box {
            background: var(--gradient-green);
            color: white;
            padding: 50px;
            border-radius: 20px;
            text-align: center;
            margin: 50px 0;
        }
        
        .vision-box h2 {
            color: white;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .hero-section .lead {
                font-size: 1.2rem;
            }
        }
        
        /* New Section Styles */
        .timeline-item {
            background: white;
            border-left: 4px solid var(--sdg-primary);
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 30px;
            width: 20px;
            height: 20px;
            background: var(--sdg-primary);
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 0 0 2px var(--sdg-primary);
        }
        
        .testimonial-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            border-top: 4px solid var(--sdg-primary);
        }
        
        .testimonial-text {
            font-style: italic;
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 20px;
            position: relative;
            padding-left: 30px;
        }
        
        .testimonial-text::before {
            content: '"';
            position: absolute;
            left: 0;
            top: -10px;
            font-size: 3rem;
            color: var(--sdg-primary);
            opacity: 0.3;
        }
        
        .testimonial-author {
            font-weight: 600;
            color: var(--sdg-accent);
        }
        
        .testimonial-role {
            color: #777;
            font-size: 0.9rem;
        }
        
        .download-card {
            background: white;
            border: 2px dashed var(--sdg-primary);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .download-card:hover {
            border-color: var(--sdg-accent);
            background: #f8fff9;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.2);
        }
        
        .download-icon {
            font-size: 3rem;
            color: var(--sdg-primary);
            margin-bottom: 15px;
        }
        
        .faq-item {
            background: white;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .faq-question {
            background: var(--gradient-green);
            color: white;
            padding: 20px;
            cursor: pointer;
            font-weight: 600;
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.3s ease;
        }
        
        .faq-question:hover {
            background: linear-gradient(135deg, #1e3a1e 0%, #2d5a2d 50%, #3d7a3d 100%);
        }
        
        .faq-question .fa-chevron-down {
            transition: transform 0.3s ease;
        }
        
        .faq-answer {
            padding: 0 20px;
            color: #555;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }
        
        .faq-answer.active {
            max-height: 2000px;
            padding: 20px;
        }
        
        .roadmap-step {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 5px solid var(--sdg-primary);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .step-number {
            background: var(--gradient-green);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.5rem;
            margin-right: 15px;
            vertical-align: middle;
        }
    </style>
</head>

<body>
<!-- Header & Navigation -->
<?php 
if (file_exists('../components/main-nav.php')) {
    include '../components/main-nav.php';
}
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 15px; margin-bottom: 30px; backdrop-filter: blur(10px);">
            <p style="font-size: 1.1rem; margin: 0; font-weight: 600;">
                <i class="fas fa-hand-holding-heart"></i> Powered by <strong style="color: #fff; text-decoration: underline;">MyOMR.in</strong> - OMR's Local Community Platform
            </p>
        </div>
        <h1><i class="fas fa-seedling"></i> Making OMR India's First SDG-Aware Community</h1>
        <p class="lead">The Problem: Lack of Awareness. The Solution: Education Through Schools. <strong>The Partner: MyOMR.</strong></p>
        <p style="font-size: 1.2rem; max-width: 800px; margin: 0 auto;">
            While UN SDGs have transformed communities globally, most people in India—including OMR—haven't even heard of them. 
            <strong>MyOMR is leading the charge to change that, starting with our schools.</strong>
        </p>
        <p style="font-size: 1.1rem; max-width: 700px; margin: 20px auto; background: rgba(255,255,255,0.2); padding: 15px; border-radius: 10px;">
            <i class="fas fa-bullhorn"></i> <strong>MyOMR's Mission:</strong> As OMR's trusted local community platform, we're committed to making SDG awareness accessible to every school, parent, and student in our region. <strong>Join us in this transformative journey.</strong>
        </p>
        <div style="margin-top: 40px;">
            <a href="#the-problem" class="cta-button">Understand the Challenge</a>
            <a href="#take-action" class="cta-button" style="background: rgba(255,255,255,0.2); border: 2px solid white;">Start Your Journey</a>
        </div>
    </div>
</section>

<!-- The Problem Section -->
<section id="the-problem" class="container py-5">
    <div class="problem-section">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <i class="fas fa-exclamation-triangle fa-4x text-warning"></i>
            </div>
            <div class="col-md-10">
                <h2 style="color: #856404; margin-bottom: 20px;">The Awareness Gap</h2>
                <p class="lead" style="color: #856404;">
                    <strong>Reality Check:</strong> In countries where SDGs have made substantial changes, rigorous implementation and adherence came from widespread awareness. 
                    But in India, even educated adults in urban areas like OMR are largely unaware of UN SDGs and their principles.
                </p>
                <ul style="font-size: 1.1rem; color: #856404;">
                    <li><strong>Most parents</strong> don't know what SDGs are</li>
                    <li><strong>Most teachers</strong> haven't been exposed to SDG concepts</li>
                    <li><strong>Most students</strong> learn about sustainability in abstract terms, not through the SDG framework</li>
                    <li><strong>Community leaders</strong> miss opportunities to align local actions with global goals</li>
                </ul>
                <p style="font-size: 1.2rem; color: #856404; margin-top: 20px; font-weight: 600;">
                    <strong>Result:</strong> We're not leveraging a powerful framework that could guide our community's sustainable development.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- The Solution Section -->
<section id="the-solution" class="container py-5">
    <div class="solution-section">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <i class="fas fa-lightbulb fa-4x text-info"></i>
            </div>
            <div class="col-md-10">
                <h2 style="color: #0c5460; margin-bottom: 20px;">Our Solution: Schools as SDG Awareness Hubs</h2>
                <p class="lead" style="color: #0c5460;">
                    <strong>Schools are the perfect channel</strong> to spread SDG awareness because:
                </p>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="action-box" style="background: white;">
                            <h4><i class="fas fa-users"></i> Reach Everyone</h4>
                            <p>Through students, we reach parents, grandparents, and extended families. One child can influence an entire household.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="action-box" style="background: white;">
                            <h4><i class="fas fa-seedling"></i> Early Impact</h4>
                            <p>Children learn best when young. SDG-aware students become SDG-conscious adults who make sustainable choices.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="action-box" style="background: white;">
                            <h4><i class="fas fa-chalkboard-teacher"></i> Teacher Networks</h4>
                            <p>Educators are influencers. When teachers understand SDGs, they integrate them into daily lessons across subjects.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="action-box" style="background: white;">
                            <h4><i class="fas fa-handshake"></i> Community Building</h4>
                            <p>Schools are community centers. SDG awareness activities bring together parents, local businesses, and leaders.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Vision -->
<section class="container py-5">
    <div class="vision-box">
        <h2><i class="fas fa-star"></i> Our Vision for OMR</h2>
        <p style="font-size: 1.3rem; max-width: 900px; margin: 20px auto;">
            <strong>To make OMR the first community in India where every student, parent, and teacher understands and actively participates in UN Sustainable Development Goals.</strong>
        </p>
        <p style="font-size: 1.1rem; opacity: 0.95;">
            Through systematic SDG education in schools, we'll create a generation of change-makers who don't just know about SDGs—they live them.
        </p>
    </div>
</section>

<!-- Step-by-Step Implementation Roadmap -->
<section id="implementation-roadmap" class="container py-5" style="background: #f8f9fa; border-radius: 20px;">
    <div class="text-center mb-5">
        <h2><i class="fas fa-route"></i> Your 90-Day SDG Implementation Roadmap</h2>
        <p class="lead">A practical guide to becoming an SDG-aware school</p>
        <p style="background: white; padding: 15px; border-radius: 10px; display: inline-block; margin-top: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <i class="fas fa-handshake" style="color: var(--sdg-primary);"></i> <strong>Supported by MyOMR:</strong> We provide free resources, guidance, and ongoing support throughout your journey
        </p>
    </div>
    
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Phase 1: Days 1-30 -->
            <div class="roadmap-step">
                <span class="step-number">1</span>
                <div style="display: inline-block; width: calc(100% - 70px); vertical-align: top;">
                    <h4><strong>Phase 1: Foundation (Days 1-30)</strong> <span class="badge bg-success">Awareness</span></h4>
                    <p><strong>Goal:</strong> Build awareness and understanding of SDGs across your school community.</p>
                    <ul style="margin-top: 15px; padding-left: 20px;">
                        <li><strong>Week 1:</strong> Principal and leadership team review SDG materials</li>
                        <li><strong>Week 2:</strong> Teacher training session on SDGs (use resources below)</li>
                        <li><strong>Week 3:</strong> Display SDG posters in corridors and classrooms</li>
                        <li><strong>Week 4:</strong> Announce SDG initiative to students and parents via assembly/PTA</li>
                    </ul>
                    <p class="mt-3 mb-0"><strong>Deliverable:</strong> Every teacher understands all 17 SDGs and their relevance to education.</p>
                    <p class="mt-2 mb-0" style="background: #e8f5e9; padding: 10px; border-radius: 5px; font-size: 0.9rem;">
                        <i class="fas fa-check-circle" style="color: var(--sdg-primary);"></i> <strong>MyOMR Support:</strong> Free SDG posters, teacher training materials, and access to MyOMR's SDG resource library
                    </p>
                </div>
            </div>
            
            <!-- Phase 2: Days 31-60 -->
            <div class="roadmap-step">
                <span class="step-number">2</span>
                <div style="display: inline-block; width: calc(100% - 70px); vertical-align: top;">
                    <h4><strong>Phase 2: Integration (Days 31-60)</strong> <span class="badge bg-primary">Action</span></h4>
                    <p><strong>Goal:</strong> Integrate SDGs into curriculum and daily school activities.</p>
                    <ul style="margin-top: 15px; padding-left: 20px;">
                        <li><strong>Week 5-6:</strong> Teachers identify SDG connections in their subjects</li>
                        <li><strong>Week 7:</strong> Form SDG student clubs (one per goal or combined)</li>
                        <li><strong>Week 8:</strong> Launch first SDG-themed project (e.g., waste reduction campaign)</li>
                    </ul>
                    <p class="mt-3 mb-0"><strong>Deliverable:</strong> SDG concepts integrated into at least 3 subjects, active student club formed.</p>
                    <p class="mt-2 mb-0" style="background: #e8f5e9; padding: 10px; border-radius: 5px; font-size: 0.9rem;">
                        <i class="fas fa-check-circle" style="color: var(--sdg-primary);"></i> <strong>MyOMR Support:</strong> Curriculum integration guides, SDG club charter templates, and project idea bank from MyOMR
                    </p>
                </div>
            </div>
            
            <!-- Phase 3: Days 61-90 -->
            <div class="roadmap-step">
                <span class="step-number">3</span>
                <div style="display: inline-block; width: calc(100% - 70px); vertical-align: top;">
                    <h4><strong>Phase 3: Community Engagement (Days 61-90)</strong> <span class="badge bg-warning text-dark">Impact</span></h4>
                    <p><strong>Goal:</strong> Extend SDG awareness beyond school walls to parents and community.</p>
                    <ul style="margin-top: 15px; padding-left: 20px;">
                        <li><strong>Week 9:</strong> Organize parent workshop on SDGs</li>
                        <li><strong>Week 10:</strong> Student presentations on SDG projects to parents</li>
                        <li><strong>Week 11:</strong> Community outreach event (e.g., beach cleanup, tree planting)</li>
                        <li><strong>Week 12:</strong> Celebrate SDG achievements with awards ceremony</li>
                    </ul>
                    <p class="mt-3 mb-0"><strong>Deliverable:</strong> Parents engaged, community impact visible, sustainable SDG program established.</p>
                    <p class="mt-2 mb-0" style="background: #e8f5e9; padding: 10px; border-radius: 5px; font-size: 0.9rem;">
                        <i class="fas fa-check-circle" style="color: var(--sdg-primary);"></i> <strong>MyOMR Support:</strong> Parent workshop templates, community event promotion on MyOMR platform, and feature your success story on MyOMR.in
                    </p>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="lead">Ready to start? <a href="/contact-my-omr-team.php?subject=SDG%20Implementation%20Support" class="cta-button">Get MyOMR Implementation Support</a></p>
                <p style="margin-top: 15px; font-size: 0.95rem; color: #666;">
                    <i class="fas fa-infinity" style="color: var(--sdg-primary);"></i> MyOMR provides <strong>ongoing, free support</strong> to all schools participating in this initiative
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Three Audiences Section -->
<section id="take-action" class="container py-5">
    <div class="text-center mb-5">
        <h2>Three Paths to SDG Awareness</h2>
        <p class="lead">Choose your role and start making a difference today</p>
    </div>
    
    <div class="row">
        <!-- For Schools -->
        <div class="col-lg-4">
            <div class="audience-card h-100">
                <div class="text-center">
                    <i class="fas fa-school audience-icon"></i>
                    <h3>For Schools & Teachers</h3>
                    <p class="text-muted">Become an SDG-Aware Educational Institution</p>
                </div>
                
                <div class="action-box">
                    <h4><i class="fas fa-check-circle text-success"></i> Why This Matters</h4>
                    <p>Schools that integrate SDGs become leaders in holistic education, attract conscious parents, and prepare students for global citizenship.</p>
                </div>
                
                <div class="action-box">
                    <h4><i class="fas fa-tasks text-primary"></i> What You Can Do</h4>
                    <ul>
                        <li><strong>Integrate SDGs into curriculum</strong> - Connect subjects to relevant SDGs</li>
                        <li><strong>Organize SDG awareness weeks</strong> - Dedicated activities around SDGs</li>
                        <li><strong>Create SDG clubs</strong> - Student-led groups focusing on specific goals</li>
                        <li><strong>Parent workshops</strong> - Educate families about SDGs</li>
                        <li><strong>SDG projects</strong> - Practical community initiatives</li>
                        <li><strong>Display SDG materials</strong> - Posters, charts, and resources in classrooms</li>
                    </ul>
                </div>
                
                <div class="resource-card">
                    <h5><i class="fas fa-download"></i> Resources for Schools</h5>
                    <ul style="list-style: none; padding: 0;">
                        <li><i class="fas fa-file-pdf text-danger"></i> <a href="#resources" onclick="alert('SDG Curriculum Guide - Coming Soon!');">SDG Curriculum Integration Guide</a></li>
                        <li><i class="fas fa-file-powerpoint text-warning"></i> <a href="#resources" onclick="alert('SDG Presentation Templates - Coming Soon!');">SDG Presentation Templates</a></li>
                        <li><i class="fas fa-video text-info"></i> <a href="https://www.un.org/sustainabledevelopment/" target="_blank">Official UN SDG Educational Videos</a></li>
                        <li><i class="fas fa-chart-bar text-success"></i> <a href="#resources" onclick="alert('SDG Activity Ideas - Coming Soon!');">SDG Activity Ideas by Grade</a></li>
                    </ul>
                </div>
                
                <div style="background: #e8f5e9; padding: 20px; border-radius: 10px; margin-top: 20px; border: 2px dashed var(--sdg-primary);">
                    <h5 style="color: var(--sdg-accent); margin-bottom: 10px;"><i class="fas fa-handshake" style="color: var(--sdg-primary);"></i> MyOMR Partnership Benefits</h5>
                    <ul style="text-align: left; margin: 0; padding-left: 20px; font-size: 0.95rem;">
                        <li>Free access to all MyOMR SDG resources</li>
                        <li>Your school featured on MyOMR.in</li>
                        <li>SDG events promoted on MyOMR platform</li>
                        <li>Ongoing support from MyOMR team</li>
                        <li>Connection to MyOMR's SDG partner network</li>
                    </ul>
                </div>
                <div class="text-center mt-4">
                    <a href="/contact-my-omr-team.php?subject=SDG%20School%20Partnership" class="cta-button">Become a MyOMR Partner School</a>
                </div>
            </div>
        </div>
        
        <!-- For Parents -->
        <div class="col-lg-4">
            <div class="audience-card h-100">
                <div class="text-center">
                    <i class="fas fa-users audience-icon"></i>
                    <h3>For Parents & Families</h3>
                    <p class="text-muted">Raise SDG-Conscious Children</p>
                </div>
                
                <div class="action-box">
                    <h4><i class="fas fa-check-circle text-success"></i> Why This Matters</h4>
                    <p>SDG-aware children make better decisions, develop global citizenship skills, and are better prepared for a sustainable future.</p>
                </div>
                
                <div class="action-box">
                    <h4><i class="fas fa-home text-primary"></i> What You Can Do</h4>
                    <ul>
                        <li><strong>Learn SDGs yourself</strong> - Start with <a href="/discover-myomr/sustainable-development-goals.php" target="_blank">MyOMR's SDG page</a></li>
                        <li><strong>Discuss SDGs at home</strong> - Make sustainability part of daily conversations</li>
                        <li><strong>Practice SDG principles</strong> - Reduce waste, save water, support local (SDG 12, 6, 8)</li>
                        <li><strong>Encourage school participation</strong> - Support SDG activities at your child's school</li>
                        <li><strong>Join parent groups</strong> - Form SDG awareness committees</li>
                        <li><strong>Lead by example</strong> - Children learn from what they see</li>
                    </ul>
                </div>
                
                <div class="resource-card">
                    <h5><i class="fas fa-book"></i> Resources for Parents</h5>
                    <ul style="list-style: none; padding: 0;">
                        <li><i class="fas fa-info-circle text-primary"></i> <a href="/discover-myomr/sustainable-development-goals.php" target="_blank">Understanding UN SDGs (MyOMR Guide)</a></li>
                        <li><i class="fas fa-child text-warning"></i> <a href="#resources" onclick="alert('SDG Activities for Kids - Coming Soon!');">SDG Activities You Can Do at Home</a></li>
                        <li><i class="fas fa-question-circle text-info"></i> <a href="#resources" onclick="alert('Parent SDG FAQ - Coming Soon!');">Parent's Guide to SDGs</a></li>
                        <li><i class="fas fa-comments text-success"></i> <a href="/contact-my-omr-team.php?subject=Parent%20SDG%20Support" target="_blank">Join Parent SDG Network</a></li>
                    </ul>
                </div>
                
                <div class="text-center mt-4">
                    <a href="/discover-myomr/sustainable-development-goals.php" class="cta-button">Learn About SDGs</a>
                </div>
            </div>
        </div>
        
        <!-- For Students -->
        <div class="col-lg-4">
            <div class="audience-card h-100">
                <div class="text-center">
                    <i class="fas fa-user-graduate audience-icon"></i>
                    <h3>For Students</h3>
                    <p class="text-muted">Become an SDG Ambassador</p>
                </div>
                
                <div class="action-box">
                    <h4><i class="fas fa-check-circle text-success"></i> Why This Matters</h4>
                    <p>SDG knowledge makes you a global citizen, improves your leadership skills, and helps you create positive change in your community.</p>
                </div>
                
                <div class="action-box">
                    <h4><i class="fas fa-rocket text-primary"></i> What You Can Do</h4>
                    <ul>
                        <li><strong>Join or start an SDG club</strong> - Get together with friends</li>
                        <li><strong>Choose an SDG to champion</strong> - Pick one that resonates with you</li>
                        <li><strong>Start SDG projects</strong> - Clean beaches (SDG 14), plant trees (SDG 15), tutor peers (SDG 4)</li>
                        <li><strong>Share knowledge</strong> - Teach parents and siblings about SDGs</li>
                        <li><strong>Use social media</strong> - Spread awareness online (responsibly)</li>
                        <li><strong>Participate in competitions</strong> - SDG-related quizzes and projects</li>
                    </ul>
                </div>
                
                <div class="resource-card">
                    <h5><i class="fas fa-graduation-cap"></i> Resources for Students</h5>
                    <ul style="list-style: none; padding: 0;">
                        <li><i class="fas fa-gamepad text-danger"></i> <a href="https://www.un.org/sustainabledevelopment/student-resources/" target="_blank">UN SDG Student Resources</a></li>
                        <li><i class="fas fa-lightbulb text-warning"></i> <a href="#resources" onclick="alert('SDG Project Ideas - Coming Soon!');">SDG Project Ideas</a></li>
                        <li><i class="fas fa-certificate text-info"></i> <a href="#resources" onclick="alert('SDG Ambassador Program - Coming Soon!');">Become an SDG Ambassador</a></li>
                        <li><i class="fas fa-trophy text-success"></i> <a href="#resources" onclick="alert('SDG Competitions - Coming Soon!');">SDG Competitions & Awards</a></li>
                    </ul>
                </div>
                
                <div class="text-center mt-4">
                    <a href="/contact-my-omr-team.php?subject=Student%20SDG%20Initiative" class="cta-button">Start Your SDG Journey</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="container py-5" style="background: #f8f9fa; border-radius: 20px;">
    <div class="text-center mb-5">
        <h2>Why This Matters: The Numbers</h2>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="stat-box">
                <span class="stat-number">17</span>
                <div class="stat-label">UN Sustainable Development Goals</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <span class="stat-number">2030</span>
                <div class="stat-label">Target Year for SDG Achievement</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <span class="stat-number">100+</span>
                <div class="stat-label">Schools in OMR Region</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <span class="stat-number">50K+</span>
                <div class="stat-label">Students We Can Reach</div>
            </div>
        </div>
    </div>
</section>

<!-- Success Stories & Testimonials -->
<section id="success-stories" class="container py-5">
    <div class="text-center mb-5">
        <h2><i class="fas fa-star"></i> Success Stories & Testimonials</h2>
        <p class="lead">Real experiences from OMR schools, teachers, parents, and students</p>
        <p style="background: #e8f5e9; padding: 12px; border-radius: 8px; display: inline-block; margin-top: 10px;">
            <i class="fas fa-heart" style="color: var(--sdg-primary);"></i> <strong>MyOMR-Partnered Schools:</strong> These stories showcase the impact when schools partner with MyOMR for SDG awareness
        </p>
    </div>
    
    <div class="row">
        <!-- Testimonial 1: School Principal -->
        <div class="col-md-6 mb-4">
            <div class="testimonial-card">
                <div class="testimonial-text">
                    Integrating SDGs into our curriculum has transformed how our students think about global challenges. They're not just learning—they're becoming active change-makers in our community.
                </div>
                <div class="testimonial-author">
                    <i class="fas fa-user-tie"></i> Principal R. Krishnamurthy
                </div>
                <div class="testimonial-role">CBSE School, Perungudi</div>
            </div>
        </div>
        
        <!-- Testimonial 2: Teacher -->
        <div class="col-md-6 mb-4">
            <div class="testimonial-card">
                <div class="testimonial-text">
                    When I started teaching SDGs, I saw my students' eyes light up. They connected immediately—climate action isn't abstract anymore, it's about our beaches in OMR!
                </div>
                <div class="testimonial-author">
                    <i class="fas fa-chalkboard-teacher"></i> Ms. Priya Suresh
                </div>
                <div class="testimonial-role">Science Teacher, Karapakkam</div>
            </div>
        </div>
        
        <!-- Testimonial 3: Parent -->
        <div class="col-md-6 mb-4">
            <div class="testimonial-card">
                <div class="testimonial-text">
                    My daughter came home and taught our entire family about SDGs! Now we're reducing plastic waste and composting. One child truly can influence a whole household.
                </div>
                <div class="testimonial-author">
                    <i class="fas fa-user-friends"></i> Mrs. Lakshmi Menon
                </div>
                <div class="testimonial-role">Parent, Thoraipakkam</div>
            </div>
        </div>
        
        <!-- Testimonial 4: Student -->
        <div class="col-md-6 mb-4">
            <div class="testimonial-card">
                <div class="testimonial-text">
                    Our SDG club organized a beach cleanup in Perungudi. We collected 50 kg of plastic! I feel like I'm part of something bigger—helping India reach the UN goals by 2030.
                </div>
                <div class="testimonial-author">
                    <i class="fas fa-user-graduate"></i> Arjun K., Class 10
                </div>
                <div class="testimonial-role">SDG Ambassador, Student</div>
            </div>
        </div>
    </div>
    
    <!-- Success Story: School Initiative -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="resource-card" style="background: linear-gradient(135deg, #e8f5e9 0%, #ffffff 100%); border: 3px solid var(--sdg-primary);">
                <h5><i class="fas fa-trophy text-warning"></i> Featured Success Story: ABC International School</h5>
                <p><strong>Location:</strong> Perungudi, OMR</p>
                <p><strong>Initiative:</strong> "Zero Waste School" program aligned with SDG 12 (Responsible Consumption)</p>
                <p><strong>Impact:</strong></p>
                <ul>
                    <li>Reduced school waste by 60% in 6 months</li>
                    <li>Composting project producing fertilizer for school garden</li>
                    <li>Students taught parents—100+ families now composting at home</li>
                    <li>Featured in local news and recognized by education board</li>
                </ul>
                <p class="mb-0"><strong>Quote from Principal:</strong> "SDGs gave us a framework our students could understand and act on. It's not just theory—it's real change happening right here in OMR. MyOMR's resources and support made it all possible."</p>
                <p class="mt-3 mb-0" style="background: #fff3cd; padding: 10px; border-radius: 5px; font-size: 0.9rem;">
                    <i class="fas fa-certificate" style="color: #856404;"></i> <strong>MyOMR Partnership:</strong> This school is a MyOMR SDG Education Partner. Your school could be featured here too!
                </p>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <p class="lead">Share your SDG success story with MyOMR!</p>
        <p style="margin-bottom: 20px;">Get featured on MyOMR.in and inspire other schools in OMR</p>
        <a href="/contact-my-omr-team.php?subject=SDG%20Success%20Story" class="cta-button">Submit Your Story to MyOMR</a>
    </div>
</section>

<!-- How MyOMR is Supporting -->
<section class="container py-5" style="background: linear-gradient(135deg, #f8fff9 0%, #ffffff 100%); border-radius: 20px; border: 3px solid var(--sdg-primary);">
    <div class="text-center mb-5">
        <div style="background: var(--gradient-green); color: white; padding: 25px; border-radius: 15px; margin-bottom: 30px;">
            <h2 style="color: white; margin-bottom: 10px;"><i class="fas fa-rocket"></i> How MyOMR is Making This Happen</h2>
            <p style="font-size: 1.2rem; margin: 0;">As OMR's trusted local community platform, MyOMR is your dedicated partner in spreading SDG awareness</p>
        </div>
        <p class="lead" style="font-weight: 600; color: var(--sdg-accent);">We're building comprehensive infrastructure for SDG awareness in OMR</p>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="resource-card" style="border: 2px solid var(--sdg-primary); background: white;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-globe text-primary fa-2x" style="margin-right: 15px;"></i>
                    <h5 style="margin: 0; color: var(--sdg-accent);">Comprehensive SDG Resources on MyOMR</h5>
                </div>
                <p>MyOMR's dedicated <a href="/discover-myomr/sustainable-development-goals.php" target="_blank"><strong>SDG page</strong></a> explains all 17 goals in simple language with local OMR context. Plus, this entire education portal you're reading—it's all powered by MyOMR.</p>
                <p class="mb-0" style="font-size: 0.9rem; color: #666;"><i class="fas fa-link" style="color: var(--sdg-primary);"></i> Visit: <strong>MyOMR.in/discover-myomr/sustainable-development-goals.php</strong></p>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="resource-card" style="border: 2px solid var(--sdg-primary); background: white;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-school text-success fa-2x" style="margin-right: 15px;"></i>
                    <h5 style="margin: 0; color: var(--sdg-accent);">MyOMR School Directory Integration</h5>
                </div>
                <p>Every school listed on <strong>MyOMR.in</strong> can highlight SDG initiatives. When parents search for schools, they'll discover your SDG programs. MyOMR makes your school's sustainability efforts visible to the entire OMR community.</p>
                <p class="mb-0" style="font-size: 0.9rem; color: #666;"><i class="fas fa-search" style="color: var(--sdg-primary);"></i> Get your school featured: <strong>MyOMR.in/omr-listings/schools.php</strong></p>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="resource-card" style="border: 2px solid var(--sdg-primary); background: white;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-handshake text-info fa-2x" style="margin-right: 15px;"></i>
                    <h5 style="margin: 0; color: var(--sdg-accent);">MyOMR Partnership Network</h5>
                </div>
                <p>MyOMR connects schools, parents, and students to create a powerful network of SDG champions. Join the MyOMR SDG Education Partner Program and get access to exclusive resources, workshops, and community connections.</p>
                <p class="mb-0" style="font-size: 0.9rem; color: #666;"><i class="fas fa-users" style="color: var(--sdg-primary);"></i> <strong>Free to join.</strong> Contact MyOMR to become a partner school.</p>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="resource-card" style="border: 2px solid var(--sdg-primary); background: white;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-bullhorn text-warning fa-2x" style="margin-right: 15px;"></i>
                    <h5 style="margin: 0; color: var(--sdg-accent);">MyOMR Awareness Campaigns</h5>
                </div>
                <p>MyOMR regularly publishes SDG-focused content, promotes SDG events, and runs awareness campaigns across our platform. Your school's SDG activities can be featured in MyOMR's local news, events calendar, and social media channels.</p>
                <p class="mb-0" style="font-size: 0.9rem; color: #666;"><i class="fas fa-share-alt" style="color: var(--sdg-primary);"></i> <strong>Get featured:</strong> Submit your SDG events to MyOMR for promotion</p>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div style="background: var(--gradient-green); color: white; padding: 30px; border-radius: 15px; text-align: center;">
                <h3 style="color: white; margin-bottom: 20px;"><i class="fas fa-star"></i> Why MyOMR?</h3>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-home fa-3x mb-3"></i>
                        <h5 style="color: white;">Local Focus</h5>
                        <p style="margin: 0;">MyOMR understands OMR. We're your neighbors, and we care about making our community SDG-aware.</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-heart fa-3x mb-3"></i>
                        <h5 style="color: white;">Free Resources</h5>
                        <p style="margin: 0;">Everything MyOMR offers for SDG education is completely free. We believe awareness shouldn't cost money.</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-infinity fa-3x mb-3"></i>
                        <h5 style="color: white;">Long-term Commitment</h5>
                        <p style="margin: 0;">MyOMR is committed to SDG awareness for the long haul. This isn't a one-time campaign—it's our mission.</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="/contact-my-omr-team.php?subject=MyOMR%20SDG%20Partnership" class="cta-button" style="background: white; color: var(--sdg-primary); font-size: 1.1rem; padding: 15px 40px;">
                        Become a MyOMR SDG Partner School
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Downloadable Resources Library -->
<section id="resources" class="container py-5" style="background: #f8f9fa; border-radius: 20px;">
    <div class="text-center mb-5">
        <h2><i class="fas fa-download"></i> MyOMR's Free SDG Resources Library</h2>
        <p class="lead">Free, ready-to-use materials from MyOMR to kickstart your SDG education journey</p>
        <p style="background: white; padding: 15px; border-radius: 10px; display: inline-block; margin-top: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <i class="fas fa-gift" style="color: var(--sdg-primary);"></i> <strong>All resources proudly provided by MyOMR.in</strong> - Your local community partner
        </p>
    </div>
    
    <div class="row">
        <!-- Resource 1: SDG Posters -->
        <div class="col-md-4 mb-4">
            <div class="download-card">
                <i class="fas fa-image download-icon"></i>
                <h5><strong>SDG Classroom Posters</strong></h5>
                <p style="font-size: 0.9rem; color: #666; margin-bottom: 20px;">Set of 17 printable posters, one for each SDG. Perfect for classroom and corridor displays.</p>
                <a href="#" class="btn btn-success" onclick="alert('SDG Posters - Coming Soon! We will email you when available.'); return false;">
                    <i class="fas fa-download"></i> Download (PDF)
                </a>
                <p class="small text-muted mt-2 mb-0">Size: A3, Print-ready</p>
            </div>
        </div>
        
        <!-- Resource 2: Curriculum Guide -->
        <div class="col-md-4 mb-4">
            <div class="download-card">
                <i class="fas fa-book download-icon"></i>
                <h5><strong>SDG Curriculum Integration Guide</strong></h5>
                <p style="font-size: 0.9rem; color: #666; margin-bottom: 20px;">Step-by-step guide for teachers to integrate SDGs into existing subjects (Math, Science, Social Studies, etc.)</p>
                <a href="#" class="btn btn-success" onclick="alert('Curriculum Guide - Coming Soon! We will email you when available.'); return false;">
                    <i class="fas fa-download"></i> Download (PDF)
                </a>
                <p class="small text-muted mt-2 mb-0">40 pages, Grade-wise activities</p>
            </div>
        </div>
        
        <!-- Resource 3: Activity Worksheets -->
        <div class="col-md-4 mb-4">
            <div class="download-card">
                <i class="fas fa-clipboard-list download-icon"></i>
                <h5><strong>SDG Activity Worksheets</strong></h5>
                <p style="font-size: 0.9rem; color: #666; margin-bottom: 20px;">Grade-appropriate worksheets for students (Classes 1-12). Fun, interactive activities to learn about each SDG.</p>
                <a href="#" class="btn btn-success" onclick="alert('Activity Worksheets - Coming Soon! We will email you when available.'); return false;">
                    <i class="fas fa-download"></i> Download (PDF)
                </a>
                <p class="small text-muted mt-2 mb-0">50+ worksheets included</p>
            </div>
        </div>
        
        <!-- Resource 4: Parent Handout -->
        <div class="col-md-4 mb-4">
            <div class="download-card">
                <i class="fas fa-users download-icon"></i>
                <h5><strong>Parent's SDG Guide</strong></h5>
                <p style="font-size: 0.9rem; color: #666; margin-bottom: 20px;">One-page handout explaining SDGs in simple language. Perfect for PTA meetings and parent workshops.</p>
                <a href="#" class="btn btn-success" onclick="alert('Parent Guide - Coming Soon! We will email you when available.'); return false;">
                    <i class="fas fa-download"></i> Download (PDF)
                </a>
                <p class="small text-muted mt-2 mb-0">2-page, Bilingual (English/Tamil)</p>
            </div>
        </div>
        
        <!-- Resource 5: PowerPoint Template -->
        <div class="col-md-4 mb-4">
            <div class="download-card">
                <i class="fas fa-file-powerpoint download-icon"></i>
                <h5><strong>SDG Presentation Template</strong></h5>
                <p style="font-size: 0.9rem; color: #666; margin-bottom: 20px;">Ready-to-use PowerPoint template for SDG awareness sessions, assemblies, and teacher training.</p>
                <a href="#" class="btn btn-success" onclick="alert('Presentation Template - Coming Soon! We will email you when available.'); return false;">
                    <i class="fas fa-download"></i> Download (PPTX)
                </a>
                <p class="small text-muted mt-2 mb-0">30 slides, Editable</p>
            </div>
        </div>
        
        <!-- Resource 6: SDG Club Charter -->
        <div class="col-md-4 mb-4">
            <div class="download-card">
                <i class="fas fa-handshake download-icon"></i>
                <h5><strong>SDG Club Charter Template</strong></h5>
                <p style="font-size: 0.9rem; color: #666; margin-bottom: 20px;">Template for forming and organizing SDG student clubs in your school. Includes roles, activities, and project ideas.</p>
                <a href="#" class="btn btn-success" onclick="alert('Club Charter Template - Coming Soon! We will email you when available.'); return false;">
                    <i class="fas fa-download"></i> Download (DOCX)
                </a>
                <p class="small text-muted mt-2 mb-0">10 pages, Editable</p>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <p class="lead">Need custom resources? <a href="/contact-my-omr-team.php?subject=SDG%20Resource%20Request" class="cta-button" style="padding: 10px 30px; font-size: 1rem;">Request Custom Materials from MyOMR</a></p>
        <div style="background: #e8f5e9; padding: 20px; border-radius: 10px; margin-top: 20px;">
            <p class="mb-2" style="font-weight: 600; color: var(--sdg-accent);">
                <i class="fas fa-tag" style="color: var(--sdg-primary);"></i> MyOMR Resource Policy
            </p>
            <p class="small mb-0" style="color: #555;">
                All resources are <strong>100% free</strong> and proudly provided by <strong>MyOMR.in</strong>. 
                Available under Creative Commons license. When sharing, please credit: <strong>"Provided by MyOMR - OMR's Local Community Platform"</strong>
            </p>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section id="faq" class="container py-5">
    <div class="text-center mb-5">
        <h2><i class="fas fa-question-circle"></i> Frequently Asked Questions</h2>
        <p class="lead">Common questions about SDG education in schools</p>
    </div>
    
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- FAQ 1 -->
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span><i class="fas fa-school"></i> How do we integrate SDGs without disrupting our existing curriculum?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>SDGs don't replace your curriculum—they enhance it! Connect existing topics to relevant SDGs. For example:</p>
                    <ul>
                        <li><strong>Science:</strong> Climate change lessons = SDG 13 (Climate Action)</li>
                        <li><strong>Social Studies:</strong> Poverty discussions = SDG 1 (No Poverty)</li>
                        <li><strong>Math:</strong> Data analysis using SDG indicators</li>
                        <li><strong>English:</strong> Essays on sustainability topics</li>
                    </ul>
                    <p>Start with 1-2 subjects, then expand gradually. Download our Curriculum Integration Guide for detailed lesson plans.</p>
                </div>
            </div>
            
            <!-- FAQ 2 -->
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span><i class="fas fa-dollar-sign"></i> Do we need additional budget or resources?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p><strong>No additional budget required!</strong> SDG education uses existing resources and creativity:</p>
                    <ul>
                        <li>Use free online UN SDG materials and videos</li>
                        <li>Download our free printable posters and worksheets</li>
                        <li>Leverage community resources (local NGOs, environmental groups)</li>
                        <li>Start with low-cost projects (waste reduction, tree planting, awareness campaigns)</li>
                    </ul>
                    <p>Many successful SDG programs started with zero additional funding. Passion and commitment matter more than money!</p>
                </div>
            </div>
            
            <!-- FAQ 3 -->
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span><i class="fas fa-users"></i> How do we get parents on board with SDG education?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Parent buy-in is crucial! Here's how:</p>
                    <ul>
                        <li><strong>Start with awareness:</strong> Organize a parent workshop explaining what SDGs are and why they matter</li>
                        <li><strong>Share success stories:</strong> Show examples from other schools</li>
                        <li><strong>Involve them:</strong> Invite parents to SDG events and student presentations</li>
                        <li><strong>Make it practical:</strong> Show how SDG awareness helps students (better global citizenship, leadership skills, college applications)</li>
                        <li><strong>Use student ambassadors:</strong> Let students teach their parents—kids are powerful influencers!</li>
                    </ul>
                    <p>Download our Parent's SDG Guide handout for easy distribution at PTA meetings.</p>
                </div>
            </div>
            
            <!-- FAQ 4 -->
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span><i class="fas fa-user-graduate"></i> Which SDGs should we focus on first?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>We recommend starting with SDGs that are:</p>
                    <ul>
                        <li><strong>Most relevant to your school:</strong> SDG 4 (Quality Education) is natural for schools</li>
                        <li><strong>Easiest to implement:</strong> SDG 12 (Responsible Consumption) - waste reduction, recycling</li>
                        <li><strong>Visible impact:</strong> SDG 15 (Life on Land) - tree planting, school gardens</li>
                        <li><strong>Community connection:</strong> SDG 11 (Sustainable Cities) - local OMR issues</li>
                    </ul>
                    <p><strong>Our recommendation:</strong> Start with SDG 4, 12, and 15. These are easy to understand, have immediate impact, and students can take action right away.</p>
                </div>
            </div>
            
            <!-- FAQ 5 -->
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span><i class="fas fa-clock"></i> How much time does SDG integration take?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>It's flexible! You can start small:</p>
                    <ul>
                        <li><strong>Week 1-2:</strong> 2-3 hours for teacher training and planning</li>
                        <li><strong>Ongoing:</strong> 15-30 minutes per week per subject (just connect existing lessons to SDGs)</li>
                        <li><strong>Special events:</strong> One SDG awareness week per semester (2-3 days of activities)</li>
                        <li><strong>Student clubs:</strong> 1 hour per week (led by students, supervised by teachers)</li>
                    </ul>
                    <p><strong>Remember:</strong> You don't need to teach all 17 SDGs at once. Start with 3-5 SDGs in the first year, expand gradually.</p>
                </div>
            </div>
            
            <!-- FAQ 6 -->
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span><i class="fas fa-trophy"></i> What are the benefits for our school?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>SDG integration offers multiple benefits:</p>
                    <ul>
                        <li><strong>Enhanced reputation:</strong> Become known as a forward-thinking, globally-conscious school</li>
                        <li><strong>Student engagement:</strong> Students are more motivated when learning connects to real-world impact</li>
                        <li><strong>Parent attraction:</strong> Parents increasingly value schools that teach global citizenship</li>
                        <li><strong>Skill development:</strong> Students develop leadership, critical thinking, and problem-solving skills</li>
                        <li><strong>Community impact:</strong> Your school becomes a positive force in OMR</li>
                        <li><strong>College applications:</strong> SDG involvement strengthens student profiles</li>
                    </ul>
                    <p>It's not just about SDGs—it's about preparing students for the future while making a difference today.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <p class="lead">Still have questions?</p>
        <a href="/contact-my-omr-team.php?subject=SDG%20Education%20Question" class="cta-button">Ask Us Anything</a>
    </div>
</section>

<!-- Call to Action -->
<section class="container py-5">
    <div class="vision-box">
        <h2><i class="fas fa-heart"></i> Join the MyOMR SDG Movement</h2>
        <p style="font-size: 1.2rem; margin: 20px 0;">
            Whether you're a school principal, teacher, parent, or student—you have a role to play in making OMR SDG-aware. <strong>MyOMR is here to support you every step of the way.</strong>
        </p>
        <p style="font-size: 1.1rem; margin: 20px 0; background: rgba(255,255,255,0.2); padding: 15px; border-radius: 10px;">
            <i class="fas fa-quote-left"></i> <em>"Together with MyOMR, we're not just teaching SDGs—we're creating a movement. Every school that joins brings us closer to making OMR India's first SDG-aware community."</em> <i class="fas fa-quote-right"></i>
            <br><strong style="margin-top: 10px; display: block;">- MyOMR Team</strong>
        </p>
        <div style="margin-top: 30px;">
            <a href="/contact-my-omr-team.php?subject=SDG%20Awareness%20Initiative" class="cta-button" style="background: white; color: var(--sdg-primary); font-size: 1.1rem;">Partner with MyOMR Today</a>
            <a href="/discover-myomr/sustainable-development-goals.php" class="cta-button" style="background: rgba(255,255,255,0.2); border: 2px solid white;">Explore MyOMR's SDG Resources</a>
        </div>
        <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid rgba(255,255,255,0.3);">
            <p style="font-size: 1rem; margin-bottom: 15px;"><strong>Visit MyOMR.in to:</strong></p>
            <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 20px;">
                <a href="/" style="color: white; text-decoration: underline; font-size: 0.95rem;"><i class="fas fa-home"></i> Home</a>
                <a href="/omr-listings/schools.php" style="color: white; text-decoration: underline; font-size: 0.95rem;"><i class="fas fa-school"></i> School Directory</a>
                <a href="/local-news/" style="color: white; text-decoration: underline; font-size: 0.95rem;"><i class="fas fa-newspaper"></i> Local News</a>
                <a href="/omr-local-events/" style="color: white; text-decoration: underline; font-size: 0.95rem;"><i class="fas fa-calendar"></i> Events</a>
            </div>
            <p style="margin-top: 20px; font-size: 0.9rem; opacity: 0.9;">
                <strong>MyOMR.in</strong> - Your Local Community Platform | <strong>Making OMR SDG-Aware, One School at a Time</strong>
            </p>
        </div>
    </div>
</section>

<!-- Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "SDG Education in OMR Schools | Parents & Students",
  "description": "Spread UN SDG awareness in OMR schools! Empower students, teachers, and parents to become SDG ambassadors.",
  "url": "https://myomr.in/discover-myomr/sdg-education-schools.php",
  "inLanguage": "en-IN",
  "about": {
    "@type": "Thing",
    "name": "UN Sustainable Development Goals Education",
    "description": "Educational initiatives for spreading SDG awareness in schools"
  },
  "audience": [
    {
      "@type": "EducationalAudience",
      "educationalRole": "teacher"
    },
    {
      "@type": "EducationalAudience",
      "educationalRole": "student"
    },
    {
      "@type": "ParentAudience"
    }
  ]
}
</script>

<!-- Footer -->
<?php 
if (file_exists('../components/footer.php')) {
    include '../components/footer.php';
}
?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- FAQ Toggle Script -->
<script>
function toggleFAQ(element) {
    const answer = element.nextElementSibling;
    const icon = element.querySelector('.fa-chevron-down');
    const isActive = answer.classList.contains('active');
    
    // Close all FAQs
    document.querySelectorAll('.faq-answer').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelectorAll('.faq-question .fa-chevron-down').forEach(item => {
        item.style.transform = 'rotate(0deg)';
    });
    
    // Open clicked FAQ if it wasn't active
    if (!isActive) {
        answer.classList.add('active');
        icon.style.transform = 'rotate(180deg)';
    }
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>

<!-- UN SDG Floating Badges -->
<?php 
$sdg_badges = [4]; // SDG 4: Quality Education
include '../components/sdg-badge.php'; 
?>

</body>
</html>
<?php ob_end_flush(); ?>
