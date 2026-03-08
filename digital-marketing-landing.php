<?php
/**
 * MyOMR.in Digital Marketing Landing Page
 * 
 * Purpose: Landing page for digital marketing team to send to new and potential users
 * Features: SEO optimized, GEO compliant, W3C standards, Google Analytics, Rich Snippets
 * 
 * @package MyOMR
 * @version 2.0.0 - Phase 4 Complete (All Phases Implemented)
 */

// Set page-specific variables
$page_title = 'MyOMR.in - Your Complete Digital Community Hub for OMR, Chennai';
$page_description = 'Discover MyOMR.in - Your one-stop platform for local news, jobs, events, business listings, hostels, coworking spaces, and more in the OMR corridor, Chennai. Join the digital community today!';
$page_keywords = 'MyOMR, OMR Chennai, Old Mahabalipuram Road, local news, jobs OMR, events Chennai, business directory, hostels OMR, coworking spaces, community portal, digital services';

// Canonical URL
$canonical_url = 'https://myomr.in/digital-marketing-landing.php';

// OG tags
$og_title = 'MyOMR.in - Your Complete Digital Community Hub for OMR, Chennai';
$og_description = 'Join MyOMR.in - Chennai\'s premier digital community platform. Get local news, find jobs, discover events, browse businesses, and connect with the OMR community.';
$og_image = 'https://myomr.in/My-OMR-Logo.jpg';
$og_url = $canonical_url;

// Twitter tags
$twitter_title = $og_title;
$twitter_description = $og_description;
$twitter_image = $og_image;

// Include error handling
ini_set('display_errors', 0);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en-IN">
<head>
<?php include 'components/meta.php'; ?>
<?php include 'components/analytics.php'; ?>
<?php include 'components/head-resources.php'; ?>

<!-- Additional Meta Tags for Landing Page -->
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<meta name="geo.region" content="IN-TN">
<meta name="geo.placename" content="Chennai">
<meta name="geo.position" content="12.9716;80.2206">
<meta name="ICBM" content="12.9716, 80.2206">
<meta name="language" content="en-IN">
<meta name="revisit-after" content="7 days">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Landing Page Custom CSS -->
<style>
:root {
    --myomr-primary: #14532d;
    --myomr-primary-dark: #14532d;
    --myomr-primary-light: #22c55e;
    --myomr-accent: #F77F00;
    --myomr-accent-light: #FFA500;
    --myomr-text-dark: #1a1a1a;
    --myomr-text-light: #6b7280;
    --myomr-bg-light: #f8f9fa;
    --myomr-white: #ffffff;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    color: var(--myomr-text-dark);
    line-height: 1.6;
    overflow-x: hidden;
}

body.loaded {
    opacity: 1;
    transition: opacity 0.3s ease;
}

/* Skip Link for Accessibility */
.skip-link {
    position: absolute;
    top: -40px;
    left: 6px;
    background: var(--myomr-primary);
    color: white;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 4px;
    z-index: 10000;
}

.skip-link:focus {
    top: 6px;
}

/* Header Styles */
.landing-header {
    background: var(--myomr-white);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    position: sticky;
    top: 0;
    z-index: 1000;
    padding: 1rem 0;
}

.landing-header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1280px;
}

.logo-section {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.logo-section img {
    height: 50px;
    width: auto;
}

.logo-section .logo-text {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--myomr-primary);
    text-decoration: none;
}

.landing-nav {
    display: flex;
    align-items: center;
    gap: 2rem;
    list-style: none;
}

.landing-nav a {
    color: var(--myomr-text-dark);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.landing-nav a:hover {
    color: var(--myomr-primary);
}

.landing-nav .cta-nav {
    background: var(--myomr-primary);
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
}

.landing-nav .cta-nav:hover {
    background: var(--myomr-primary-dark);
    color: white;
}

.mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--myomr-text-dark);
    cursor: pointer;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--myomr-primary) 0%, var(--myomr-primary-dark) 100%);
    color: white;
    padding: 100px 0;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.1;
    z-index: 1;
}

.hero-section .container {
    position: relative;
    z-index: 2;
    max-width: 1280px;
}

.hero-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.hero-badge {
    display: inline-block;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1.25rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 1.5rem;
}

.hero-title .highlight {
    color: var(--myomr-accent-light);
    position: relative;
    display: inline-block;
}

.hero-title .highlight::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--myomr-accent-light);
    border-radius: 2px;
}

.hero-description {
    font-size: 1.25rem;
    line-height: 1.8;
    margin-bottom: 2rem;
    opacity: 0.95;
}

.hero-cta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-hero-primary {
    background: var(--myomr-accent);
    color: white;
    padding: 1rem 2.5rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.1rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-hero-primary:hover {
    background: var(--myomr-accent-light);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(247, 127, 0, 0.3);
    color: white;
}

.btn-hero-secondary {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    padding: 1rem 2.5rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.1rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.btn-hero-secondary:hover {
    background: rgba(255, 255, 255, 0.25);
    color: white;
    transform: translateY(-2px);
}

.hero-image {
    position: relative;
    text-align: center;
}

.hero-image-placeholder {
    width: 100%;
    max-width: 500px;
    height: 400px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
}

.hero-image-placeholder i {
    font-size: 8rem;
    opacity: 0.5;
    animation: float 3s ease-in-out infinite;
    z-index: 2;
}

/* Floating Social Icons */
.floating-social-icons {
    position: fixed;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 999;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.social-icon-float {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    color: var(--myomr-primary);
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 1.25rem;
}

.social-icon-float:hover {
    transform: translateY(-5px) scale(1.1);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    color: var(--myomr-primary);
}

.social-icon-float.facebook { color: #3b5998; }
.social-icon-float.instagram { color: #E4405F; }
.social-icon-float.linkedin { color: #0077B5; }
.social-icon-float.whatsapp { color: #25D366; }

/* Statistics Section */
.stats-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.stat-card {
    text-align: center;
    padding: 2rem;
}

.stat-icon {
    font-size: 2.5rem;
    color: var(--myomr-primary);
    margin-bottom: 1rem;
}

.stat-number {
    font-size: 3.5rem;
    font-weight: 800;
    color: var(--myomr-primary);
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1.1rem;
    color: var(--myomr-text-light);
    font-weight: 500;
}

/* Services Section */
.services-section {
    padding: 80px 0;
    background: var(--myomr-white);
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--myomr-primary);
    margin-bottom: 1rem;
}

.section-subtitle {
    text-align: center;
    font-size: 1.2rem;
    color: var(--myomr-text-light);
    margin-bottom: 3rem;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.services-grid-primary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 4rem;
}

.service-card {
    background: var(--myomr-white);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.service-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 133, 82, 0.15);
    border-color: var(--myomr-primary);
}

.service-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, var(--myomr-primary), var(--myomr-primary-light));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    margin-bottom: 1.5rem;
}

.service-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--myomr-primary);
    margin-bottom: 1rem;
}

.service-description {
    color: var(--myomr-text-light);
    line-height: 1.7;
    margin-bottom: 1.5rem;
    flex-grow: 1;
}

.service-cta {
    background: var(--myomr-primary);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    width: fit-content;
}

.service-cta:hover {
    background: var(--myomr-primary-dark);
    transform: translateX(5px);
    color: white;
}

.business-directory-section {
    margin-top: 4rem;
    padding-top: 4rem;
    border-top: 2px solid #e5e7eb;
}

.business-directory-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--myomr-primary);
    margin-bottom: 1.5rem;
    text-align: center;
}

.services-grid-secondary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.service-card-small {
    background: var(--myomr-white);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
    text-align: center;
}

.service-card-small:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 133, 82, 0.12);
    border-color: var(--myomr-primary-light);
}

.service-icon-small {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--myomr-primary-light), var(--myomr-primary));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin: 0 auto 1rem;
}

.service-title-small {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--myomr-text-dark);
    margin-bottom: 0.5rem;
}

.service-cta-small {
    color: var(--myomr-primary);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.3s ease;
}

.service-cta-small:hover {
    color: var(--myomr-primary-dark);
    gap: 0.5rem;
}

/* CTA Sections */
.cta-section {
    padding: 80px 0;
    background: linear-gradient(135deg, var(--myomr-primary) 0%, var(--myomr-primary-dark) 100%);
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.1;
    z-index: 1;
}

.cta-section .container {
    position: relative;
    z-index: 2;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-description {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.95;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-cta-primary {
    background: var(--myomr-accent);
    color: white;
    padding: 1rem 2.5rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.1rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-cta-primary:hover {
    background: var(--myomr-accent-light);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(247, 127, 0, 0.3);
    color: white;
}

.btn-cta-secondary {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    padding: 1rem 2.5rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.1rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.btn-cta-secondary:hover {
    background: rgba(255, 255, 255, 0.25);
    color: white;
    transform: translateY(-2px);
}

/* Newsletter Section */
.newsletter-section {
    padding: 80px 0;
    background: var(--myomr-bg-light);
}

.newsletter-card {
    background: var(--myomr-white);
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    max-width: 700px;
    margin: 0 auto;
    text-align: center;
}

.newsletter-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--myomr-primary);
    margin-bottom: 1rem;
}

.newsletter-description {
    color: var(--myomr-text-light);
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

.newsletter-form {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
}

.newsletter-input {
    flex: 1;
    min-width: 250px;
    padding: 1rem 1.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 50px;
    font-size: 1rem;
    font-family: 'Poppins', sans-serif;
    transition: all 0.3s ease;
}

.newsletter-input:focus {
    outline: none;
    border-color: var(--myomr-primary);
    box-shadow: 0 0 0 3px rgba(0, 133, 82, 0.1);
}

.btn-newsletter {
    background: var(--myomr-primary);
    color: white;
    padding: 1rem 2.5rem;
    border: none;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-newsletter:hover {
    background: var(--myomr-primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 133, 82, 0.3);
}

.newsletter-privacy {
    margin-top: 1.5rem;
    font-size: 0.9rem;
    color: var(--myomr-text-light);
}

/* Loading State */
body:not(.loaded) {
    opacity: 0;
}

/* Responsive Design */
@media (max-width: 992px) {
    .hero-content {
        grid-template-columns: 1fr;
        gap: 3rem;
        text-align: center;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-cta {
        justify-content: center;
    }
    
    .landing-nav {
        display: none;
    }
    
    .mobile-menu-toggle {
        display: block;
    }
    
    .floating-social-icons {
        display: none;
    }
    
    .hero-image-placeholder {
        max-width: 100%;
        height: 300px;
    }
    
    .hero-image-placeholder i {
        font-size: 5rem;
    }
    
    .floating-elements {
        display: none;
    }
}

@media (max-width: 768px) {
    .hero-section {
        padding: 60px 0;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-description {
        font-size: 1.1rem;
    }
    
    .btn-hero-primary,
    .btn-hero-secondary {
        padding: 0.875rem 2rem;
        font-size: 1rem;
        width: 100%;
        justify-content: center;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .services-grid-primary,
    .services-grid-secondary {
        grid-template-columns: 1fr;
    }
    
    .stat-number {
        font-size: 2.5rem;
    }
    
    .cta-title {
        font-size: 2rem;
    }
    
    .cta-description {
        font-size: 1.1rem;
    }
    
    .newsletter-form {
        flex-direction: column;
    }
    
    .newsletter-input,
    .btn-newsletter {
        width: 100%;
    }
}

/* Animations & Scroll Effects */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

/* Scroll Animation Classes */
.fade-in-up {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}

.fade-in-up.visible {
    opacity: 1;
    transform: translateY(0);
}

.fade-in {
    opacity: 0;
    transition: opacity 0.8s ease-out;
}

.fade-in.visible {
    opacity: 1;
}

/* Performance Optimizations */
.service-card,
.service-card-small,
.stat-card {
    will-change: transform;
}

/* Enhanced Hero Image with Floating Elements */
.hero-image {
    position: relative;
    text-align: center;
}

.hero-image-placeholder {
    width: 100%;
    max-width: 500px;
    height: 400px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
}

.hero-image-placeholder i {
    font-size: 8rem;
    opacity: 0.5;
    animation: float 3s ease-in-out infinite;
}

/* Floating Elements Around Hero */
.floating-elements {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    pointer-events: none;
}

.floating-element {
    position: absolute;
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: float 4s ease-in-out infinite;
}

.floating-element:nth-child(1) {
    top: 10%;
    left: 10%;
    animation-delay: 0s;
    font-size: 1.5rem;
}

.floating-element:nth-child(2) {
    top: 60%;
    right: 15%;
    animation-delay: 1s;
    font-size: 1.2rem;
}

.floating-element:nth-child(3) {
    bottom: 20%;
    left: 20%;
    animation-delay: 2s;
    font-size: 1.3rem;
}

.floating-element i {
    color: rgba(255, 255, 255, 0.8);
}

/* Enhanced Mobile Menu */
.mobile-menu-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.mobile-menu-overlay.active {
    display: block;
    opacity: 1;
}

.mobile-menu {
    position: fixed;
    top: 0;
    right: -100%;
    width: 300px;
    max-width: 85vw;
    height: 100vh;
    background: white;
    z-index: 1000;
    transition: right 0.3s ease;
    box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
}

.mobile-menu.active {
    right: 0;
}

.mobile-menu-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.mobile-menu-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--myomr-text-dark);
    cursor: pointer;
    padding: 0.5rem;
    transition: transform 0.3s ease;
}

.mobile-menu-close:hover {
    transform: rotate(90deg);
}

.mobile-menu-nav {
    padding: 1rem 0;
    list-style: none;
}

.mobile-menu-nav a {
    display: block;
    padding: 1rem 1.5rem;
    color: var(--myomr-text-dark);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.mobile-menu-nav a:hover,
.mobile-menu-nav a:focus {
    background: var(--myomr-bg-light);
    border-left-color: var(--myomr-primary);
    color: var(--myomr-primary);
    padding-left: 2rem;
}

.mobile-menu-nav .cta-nav {
    background: var(--myomr-primary);
    color: white;
    margin: 1rem 1.5rem;
    border-radius: 8px;
    text-align: center;
    border-left: none;
}

.mobile-menu-nav .cta-nav:hover {
    background: var(--myomr-primary-dark);
    padding-left: 1.5rem;
}

/* Enhanced Button Animations */
.btn-hero-primary:active,
.btn-cta-primary:active,
.btn-newsletter:active {
    transform: translateY(0) scale(0.98);
}

.service-cta:active {
    transform: translateX(3px) scale(0.98);
}

/* Loading States */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Smooth Scroll Behavior */
html {
    scroll-behavior: smooth;
}

@media (prefers-reduced-motion: reduce) {
    html {
        scroll-behavior: auto;
    }
    
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Enhanced Service Card Hover */
.service-card {
    position: relative;
    overflow: hidden;
}

.service-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.service-card:hover::before {
    left: 100%;
}

/* Stat Card Animation */
.stat-card {
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-number {
    transition: color 0.3s ease;
}

.stat-card:hover .stat-number {
    color: var(--myomr-primary-light);
}

/* Enhanced Newsletter Form */
.newsletter-input:invalid:not(:placeholder-shown) {
    border-color: #ef4444;
}

.newsletter-input:valid:not(:placeholder-shown) {
    border-color: var(--myomr-primary-light);
}

/* Accessibility */
*:focus-visible {
    outline: 3px solid var(--myomr-accent);
    outline-offset: 2px;
}

/* Skip Link Enhancement */
.skip-link:focus {
    top: 6px;
    z-index: 10001;
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
    .service-card,
    .newsletter-card {
        border: 2px solid var(--myomr-text-dark);
    }
}

/* Print Styles */
@media print {
    .landing-header,
    .floating-social-icons,
    .hero-cta,
    .mobile-menu-toggle,
    .cta-section,
    .newsletter-section {
        display: none;
    }
    
    .service-card,
    .stat-card {
        break-inside: avoid;
    }
}
</style>

<!-- Structured Data - Organization Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "MyOMR",
  "url": "https://myomr.in",
  "logo": "https://myomr.in/My-OMR-Logo.jpg",
  "description": "MyOMR.in - Your complete digital community hub for Old Mahabalipuram Road (OMR), Chennai. Local news, jobs, events, business listings, and more.",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Chennai",
    "addressRegion": "Tamil Nadu",
    "addressCountry": "IN",
    "streetAddress": "OMR Road"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "email": "myomrnews@gmail.com",
    "contactType": "Customer Service",
    "areaServed": "IN",
    "availableLanguage": ["en", "ta"]
  },
  "sameAs": [
    "https://www.facebook.com/myomrCommunity",
    "https://www.instagram.com/myomrcommunity",
    "https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi",
    "https://www.youtube.com/channel/UCyFrgbaQht7C-17m_prn0Rg"
  ],
  "areaServed": {
    "@type": "City",
    "name": "Chennai",
    "sameAs": "https://en.wikipedia.org/wiki/Chennai"
  }
}
</script>

<!-- Structured Data - WebPage Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "<?php echo addslashes($page_title); ?>",
  "description": "<?php echo addslashes($page_description); ?>",
  "url": "<?php echo $canonical_url; ?>",
  "inLanguage": "en-IN",
  "isPartOf": {
    "@type": "WebSite",
    "name": "MyOMR",
    "url": "https://myomr.in"
  },
  "about": {
    "@type": "Thing",
    "name": "Digital Community Services",
    "description": "Comprehensive digital services for OMR corridor residents and businesses"
  },
  "breadcrumb": {
    "@type": "BreadcrumbList",
    "itemListElement": [
      {
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "https://myomr.in"
      },
      {
        "@type": "ListItem",
        "position": 2,
        "name": "Digital Marketing Landing",
        "item": "<?php echo $canonical_url; ?>"
      }
    ]
  },
  "primaryImageOfPage": {
    "@type": "ImageObject",
    "url": "<?php echo $og_image; ?>",
    "width": 1200,
    "height": 630
  }
}
</script>

<!-- Structured Data - Service Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "serviceType": "Digital Community Platform",
  "provider": {
    "@type": "Organization",
    "name": "MyOMR",
    "url": "https://myomr.in"
  },
  "areaServed": {
    "@type": "City",
    "name": "Chennai",
    "addressRegion": "Tamil Nadu",
    "addressCountry": "IN"
  },
  "hasOfferCatalog": {
    "@type": "OfferCatalog",
    "name": "MyOMR Digital Services",
    "itemListElement": [
      {
        "@type": "OfferCatalog",
        "name": "Local News & Updates",
        "itemListElement": [
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Local News Portal"
            }
          }
        ]
      },
      {
        "@type": "OfferCatalog",
        "name": "Job Listings",
        "itemListElement": [
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Job Search Portal"
            }
          }
        ]
      },
      {
        "@type": "OfferCatalog",
        "name": "Business Directory",
        "itemListElement": [
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Business Listings"
            }
          }
        ]
      }
    ]
  }
}
</script>

<!-- Structured Data - FAQPage Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "What is MyOMR.in?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "MyOMR.in is Chennai's premier digital community hub for Old Mahabalipuram Road (OMR) corridor. We provide comprehensive digital services including local news, job listings, events, business directories, hostels, coworking spaces, and community engagement platforms."
      }
    },
    {
      "@type": "Question",
      "name": "What services does MyOMR.in offer?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "MyOMR.in offers comprehensive digital services including local news & updates, job listings & career services, events & activities, business directory (schools, hospitals, banks, restaurants, IT companies, etc.), hostels & PGs, coworking spaces, community engagement, and newsletter subscriptions."
      }
    },
    {
      "@type": "Question",
      "name": "How can I join MyOMR.in?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You can join MyOMR.in by visiting our website and exploring our various services. Subscribe to our newsletter, browse job listings, discover events, or list your business. Most services are free to use, and you can get started immediately."
      }
    },
    {
      "@type": "Question",
      "name": "Is MyOMR.in free to use?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, most services on MyOMR.in are free to use. We offer optional subscription plans for enhanced features. You can browse news, jobs, events, and business directories without any cost. Check our pricing page for subscription options."
      }
    },
    {
      "@type": "Question",
      "name": "What areas does MyOMR.in cover?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "MyOMR.in covers the entire OMR corridor including Perungudi, Thuraipakkam, Karapakkam, Kandhanchavadi, Mettukuppam, Sholinganallur, Dollar Stop, IT Corridor, Tidel Park, Madhya Kailash, Navalur, Thazhambur, Kelambakkam, and more areas along Old Mahabalipuram Road."
      }
    },
    {
      "@type": "Question",
      "name": "How do I post a job listing?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "To post a job listing, visit our job portal at /omr-local-job-listings/post-job-omr.php. Employers can register, create job postings, and manage their listings through our employer dashboard. Job postings are moderated before going live."
      }
    },
    {
      "@type": "Question",
      "name": "How do I list my business?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "To list your business in our directory, visit our business directory page at /omr-listings/ and check the 'Get Listed' section. You can also contact us directly through our support page for business listings and advertising opportunities."
      }
    },
    {
      "@type": "Question",
      "name": "How do I subscribe to the newsletter?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You can subscribe to our newsletter by entering your email address in the newsletter subscription form on our website. Look for the 'Subscribe to Our Newsletter' section and enter your email. You'll receive regular updates about OMR news, events, and opportunities."
      }
    },
    {
      "@type": "Question",
      "name": "How can I contact MyOMR.in?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You can contact MyOMR.in by email at myomrnews@gmail.com or call us at +91-98847 85845. Visit our support page at /discover-myomr/support.php for more contact options and information."
      }
    },
    {
      "@type": "Question",
      "name": "Is MyOMR.in mobile-friendly?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, MyOMR.in is fully mobile-responsive and optimized for all devices including smartphones, tablets, and desktops. You can access all our services and features seamlessly on any device with an internet connection."
      }
    }
  ]
}
</script>

<!-- Structured Data - ItemList Schema for Services -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "MyOMR.in Digital Services",
  "description": "Complete list of digital services offered by MyOMR.in for the OMR corridor community",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "item": {
        "@type": "Service",
        "name": "Local News & Updates",
        "url": "https://myomr.in/local-news/",
        "description": "Stay informed with latest news, stories, and community updates from OMR corridor"
      }
    },
    {
      "@type": "ListItem",
      "position": 2,
      "item": {
        "@type": "Service",
        "name": "Job Listings & Career Services",
        "url": "https://myomr.in/omr-local-job-listings/",
        "description": "Find and post jobs, connect with employers in OMR"
      }
    },
    {
      "@type": "ListItem",
      "position": 3,
      "item": {
        "@type": "Service",
        "name": "Events & Activities",
        "url": "https://myomr.in/omr-local-events/",
        "description": "Discover and list local events, festivals, and activities"
      }
    },
    {
      "@type": "ListItem",
      "position": 4,
      "item": {
        "@type": "Service",
        "name": "Business Directory",
        "url": "https://myomr.in/omr-listings/",
        "description": "Comprehensive directory of businesses, services, and professionals"
      }
    },
    {
      "@type": "ListItem",
      "position": 5,
      "item": {
        "@type": "Service",
        "name": "Hostels & PGs",
        "url": "https://myomr.in/omr-hostels-pgs/",
        "description": "Find student and professional accommodation along OMR"
      }
    },
    {
      "@type": "ListItem",
      "position": 6,
      "item": {
        "@type": "Service",
        "name": "Coworking Spaces",
        "url": "https://myomr.in/omr-coworking-spaces/",
        "description": "Discover flexible workspace solutions for freelancers and startups"
      }
    },
    {
      "@type": "ListItem",
      "position": 7,
      "item": {
        "@type": "Service",
        "name": "Community Engagement",
        "url": "https://myomr.in/discover-myomr/community.php",
        "description": "Join discussions and connect with OMR community"
      }
    },
    {
      "@type": "ListItem",
      "position": 8,
      "item": {
        "@type": "Service",
        "name": "Newsletter Subscription",
        "url": "https://myomr.in/digital-marketing-landing.php#newsletter",
        "description": "Get latest updates, news, and events delivered to your inbox"
      }
    }
  ]
}
</script>

</head>
<body>
<!-- Skip to main content link -->
<a href="#main-content" class="skip-link">Skip to main content</a>

<!-- Header -->
<header class="landing-header" role="banner">
    <div class="container">
        <div class="logo-section">
            <a href="/" class="logo-text" aria-label="MyOMR Home">
                <img src="/My-OMR-Logo.jpg" alt="MyOMR Logo" height="50" width="50">
                MyOMR.in
            </a>
        </div>
        <nav class="landing-nav" role="navigation" aria-label="Main navigation">
            <a href="/">Home</a>
            <a href="/discover-myomr/features.php">Features</a>
            <a href="/discover-myomr/pricing.php">Pricing</a>
            <a href="/discover-myomr/community.php">Community</a>
            <a href="/discover-myomr/support.php" class="cta-nav">Contact Us</a>
        </nav>
        <button class="mobile-menu-toggle" aria-label="Toggle mobile menu" aria-expanded="false" id="mobileMenuToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</header>

<!-- Main Content -->
<main id="main-content" role="main">
    <!-- Hero Section -->
    <section class="hero-section" aria-labelledby="hero-title">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <span class="hero-badge">
                        <i class="fas fa-star"></i> Your Digital Community Hub
                    </span>
                    <h1 id="hero-title" class="hero-title">
                        OMR Chennai's <span class="highlight">#1</span> Local Community Portal — News, Jobs, Events &amp; More
                    </h1>
                    <p class="hero-description">
                        Discover MyOMR.in - Chennai's premier digital platform connecting residents, businesses, and job seekers along Old Mahabalipuram Road. Get local news, find jobs, discover events, browse business directories, and join a thriving community.
                    </p>
                    <div class="hero-email-form mb-4">
                        <form action="/omr-local-job-listings/" method="get" class="d-flex gap-2 flex-wrap" onsubmit="gtag('event','hero_search_submit',{'event_category':'CTA'});">
                            <input type="text" name="search" placeholder="Search jobs, events, businesses in OMR…"
                                   class="form-control flex-grow-1"
                                   style="border-radius:8px;border:none;padding:12px 18px;font-family:Poppins,sans-serif;font-size:.95rem;min-width:200px;">
                            <button type="submit"
                                    style="background:var(--myomr-accent);color:#fff;border:none;border-radius:8px;padding:12px 24px;font-weight:600;font-size:.95rem;white-space:nowrap;font-family:Poppins,sans-serif;cursor:pointer;">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                        </form>
                    </div>
                    <div class="hero-cta">
                        <a href="#services" class="btn-hero-primary" onclick="gtag('event', 'click', {'event_category': 'CTA', 'event_label': 'Get Started Hero'});">
                            <i class="fas fa-rocket"></i> GET STARTED NOW
                        </a>
                        <a href="/discover-myomr/features.php" class="btn-hero-secondary">
                            <i class="fas fa-info-circle"></i> Learn More
                        </a>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="hero-image-placeholder">
                        <i class="fas fa-city"></i>
                        <div class="floating-elements">
                            <div class="floating-element"><i class="fas fa-newspaper"></i></div>
                            <div class="floating-element"><i class="fas fa-briefcase"></i></div>
                            <div class="floating-element"><i class="fas fa-calendar-day"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section" aria-labelledby="stats-title">
        <div class="container">
            <h2 id="stats-title" class="section-title fade-in-up">Join Thousands of Community Members</h2>
            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">Active Users</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-building"></i></div>
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Business Listings</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
                        <div class="stat-number">1K+</div>
                        <div class="stat-label">Job Listings</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-newspaper"></i></div>
                        <div class="stat-number">500+</div>
                        <div class="stat-label">News Stories</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mid-Page CTA -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title fade-in-up">Ready to Explore OMR?</h2>
            <p class="cta-description fade-in-up">Join thousands of community members and discover everything OMR has to offer!</p>
            <div class="cta-buttons">
                <a href="#services" class="btn-cta-primary" onclick="gtag('event', 'click', {'event_category': 'CTA', 'event_label': 'Explore Services Mid'});">
                    <i class="fas fa-rocket"></i> Explore Services
                </a>
                <a href="/discover-myomr/community.php" class="btn-cta-secondary">
                    <i class="fas fa-users"></i> Join Community
                </a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services-section" aria-labelledby="services-title">
        <div class="container">
            <h2 id="services-title" class="section-title fade-in-up">Our Digital Services</h2>
            <p class="section-subtitle fade-in-up">
                Everything you need to connect, discover, and thrive in the OMR corridor
            </p>
            
            <!-- Primary Services Grid -->
            <div class="services-grid-primary">
                <!-- Local News & Updates -->
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-newspaper"></i></div>
                    <h3 class="service-title">Local News & Updates</h3>
                    <p class="service-description">Stay informed with the latest news, stories, and community updates from the OMR corridor. Get breaking news, civic updates, and local happenings delivered to you.</p>
                    <a href="/local-news/" class="service-cta" onclick="gtag('event', 'click', {'event_category': 'Service', 'event_label': 'Local News'});">
                        Read News <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Job Listings & Career Services -->
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-briefcase"></i></div>
                    <h3 class="service-title">Job Listings & Career Services</h3>
                    <p class="service-description">Find your dream job in OMR! Browse hundreds of job listings by location, industry, and experience level. Post job vacancies and connect with employers.</p>
                    <a href="/omr-local-job-listings/" class="service-cta" onclick="gtag('event', 'click', {'event_category': 'Service', 'event_label': 'Job Listings'});">
                        Browse Jobs <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Events & Activities -->
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-calendar-day"></i></div>
                    <h3 class="service-title">Events & Activities</h3>
                    <p class="service-description">Discover and list local events, festivals, and activities happening in OMR. Stay connected with community events and never miss out on exciting happenings.</p>
                    <a href="/omr-local-events/" class="service-cta" onclick="gtag('event', 'click', {'event_category': 'Service', 'event_label': 'Events'});">
                        View Events <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Business Directory -->
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-building"></i></div>
                    <h3 class="service-title">Business Directory</h3>
                    <p class="service-description">Comprehensive directory of businesses, services, and professionals along OMR. Find schools, hospitals, banks, restaurants, IT companies, and more.</p>
                    <a href="/omr-listings/" class="service-cta" onclick="gtag('event', 'click', {'event_category': 'Service', 'event_label': 'Business Directory'});">
                        Browse Directory <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Hostels & PGs -->
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-bed"></i></div>
                    <h3 class="service-title">Hostels & PGs</h3>
                    <p class="service-description">Find student and professional accommodation along OMR corridor. Browse verified hostels and PGs with detailed information, amenities, and contact details.</p>
                    <a href="/omr-hostels-pgs/" class="service-cta" onclick="gtag('event', 'click', {'event_category': 'Service', 'event_label': 'Hostels PGs'});">
                        Find Accommodation <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Coworking Spaces -->
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-laptop-house"></i></div>
                    <h3 class="service-title">Coworking Spaces</h3>
                    <p class="service-description">Discover flexible workspace solutions for freelancers, startups, and remote workers. Find the perfect coworking space in OMR with day passes and monthly plans.</p>
                    <a href="/omr-coworking-spaces/" class="service-cta" onclick="gtag('event', 'click', {'event_category': 'Service', 'event_label': 'Coworking Spaces'});">
                        Find Workspace <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Community Engagement -->
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-users"></i></div>
                    <h3 class="service-title">Community Engagement</h3>
                    <p class="service-description">Join discussions, share experiences, and help shape the OMR community. Connect with residents, businesses, and community leaders in meaningful ways.</p>
                    <a href="/discover-myomr/community.php" class="service-cta" onclick="gtag('event', 'click', {'event_category': 'Service', 'event_label': 'Community'});">
                        Join Community <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Newsletter Subscription -->
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-envelope-open-text"></i></div>
                    <h3 class="service-title">Newsletter Subscription</h3>
                    <p class="service-description">Get the latest updates, news, events, and exclusive content delivered directly to your inbox. Stay connected with OMR happenings without missing a beat.</p>
                    <a href="#newsletter" class="service-cta" onclick="gtag('event', 'click', {'event_category': 'Service', 'event_label': 'Newsletter'});">
                        Subscribe Now <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Business Directory Sub-Section -->
            <div class="business-directory-section">
                <h3 class="business-directory-title">Business Directory Categories</h3>
                <div class="services-grid-secondary">
                    <div class="service-card-small">
                        <div class="service-icon-small"><i class="fas fa-graduation-cap"></i></div>
                        <h4 class="service-title-small">Schools</h4>
                        <a href="/omr-listings/schools-new.php" class="service-cta-small">
                            Browse <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="service-card-small">
                        <div class="service-icon-small"><i class="fas fa-hospital"></i></div>
                        <h4 class="service-title-small">Hospitals</h4>
                        <a href="/omr-listings/hospitals-new.php" class="service-cta-small">
                            Browse <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="service-card-small">
                        <div class="service-icon-small"><i class="fas fa-university"></i></div>
                        <h4 class="service-title-small">Banks</h4>
                        <a href="/omr-listings/banks-new.php" class="service-cta-small">
                            Browse <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="service-card-small">
                        <div class="service-icon-small"><i class="fas fa-credit-card"></i></div>
                        <h4 class="service-title-small">ATMs</h4>
                        <a href="/omr-listings/atms-new.php" class="service-cta-small">
                            Browse <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="service-card-small">
                        <div class="service-icon-small"><i class="fas fa-laptop-code"></i></div>
                        <h4 class="service-title-small">IT Companies</h4>
                        <a href="/omr-listings/it-companies-new.php" class="service-cta-small">
                            Browse <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="service-card-small">
                        <div class="service-icon-small"><i class="fas fa-utensils"></i></div>
                        <h4 class="service-title-small">Restaurants</h4>
                        <a href="/omr-listings/restaurants.php" class="service-cta-small">
                            Browse <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="service-card-small">
                        <div class="service-icon-small"><i class="fas fa-industry"></i></div>
                        <h4 class="service-title-small">Industries</h4>
                        <a href="/omr-listings/industries-new.php" class="service-cta-small">
                            Browse <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="service-card-small">
                        <div class="service-icon-small"><i class="fas fa-tree"></i></div>
                        <h4 class="service-title-small">Parks</h4>
                        <a href="/omr-listings/parks-new.php" class="service-cta-small">
                            Browse <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="service-card-small">
                        <div class="service-icon-small"><i class="fas fa-landmark"></i></div>
                        <h4 class="service-title-small">Government Offices</h4>
                        <a href="/omr-listings/government-offices-new.php" class="service-cta-small">
                            Browse <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="service-card-small">
                        <div class="service-icon-small"><i class="fas fa-building"></i></div>
                        <h4 class="service-title-small">IT Parks</h4>
                        <a href="/omr-listings/it-parks-in-omr.php" class="service-cta-small">
                            Browse <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section id="newsletter" class="newsletter-section" aria-labelledby="newsletter-title">
        <div class="container">
            <div class="newsletter-card">
                <h2 id="newsletter-title" class="newsletter-title fade-in-up">Stay Updated with OMR</h2>
                <p class="newsletter-description fade-in-up">Subscribe to our newsletter and get the latest news, events, job opportunities, and community updates delivered to your inbox.</p>
                <form action="/core/subscribe.php" method="POST" class="newsletter-form">
                    <input type="email" name="email" class="newsletter-input" placeholder="Enter your email address" required aria-label="Email address for newsletter subscription">
                    <button type="submit" class="btn-newsletter" onclick="gtag('event', 'conversion', {'event_category': 'Newsletter', 'event_label': 'Newsletter Signup Landing'});">
                        <i class="fas fa-paper-plane"></i> Subscribe
                    </button>
                </form>
                <p class="newsletter-privacy">By subscribing, you agree to our Privacy Policy. We'll never spam you. Unsubscribe anytime.</p>
            </div>
        </div>
    </section>

    <!-- Bottom CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title fade-in-up">Join MyOMR.in Today</h2>
            <p class="cta-description fade-in-up">Your complete digital community hub for Old Mahabalipuram Road. Connect, discover, and thrive with thousands of community members.</p>
            <div class="cta-buttons">
                <a href="/" class="btn-cta-primary" onclick="gtag('event', 'click', {'event_category': 'CTA', 'event_label': 'Get Started Bottom'});">
                    <i class="fas fa-home"></i> Explore Homepage
                </a>
                <a href="/discover-myomr/support.php" class="btn-cta-secondary">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
            </div>
        </div>
    </section>
</main>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobileMenuOverlay" aria-hidden="true"></div>

<!-- Mobile Menu -->
<nav class="mobile-menu" id="mobileMenu" aria-label="Mobile navigation" aria-hidden="true">
    <div class="mobile-menu-header">
        <a href="/" class="logo-text" style="font-size: 1.25rem;">MyOMR.in</a>
        <button class="mobile-menu-close" aria-label="Close mobile menu" id="mobileMenuClose">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <ul class="mobile-menu-nav">
        <li><a href="/">Home</a></li>
        <li><a href="/discover-myomr/features.php">Features</a></li>
        <li><a href="/discover-myomr/pricing.php">Pricing</a></li>
        <li><a href="/discover-myomr/community.php">Community</a></li>
        <li><a href="/discover-myomr/support.php" class="cta-nav">Contact Us</a></li>
    </ul>
</nav>

<!-- Footer -->
<?php include 'components/footer.php'; ?>

<!-- Floating Social Icons -->
<div class="floating-social-icons" aria-label="Social media links">
    <a href="https://www.facebook.com/myomrCommunity" target="_blank" rel="noopener noreferrer" class="social-icon-float facebook" aria-label="Facebook">
        <i class="fab fa-facebook-f"></i>
    </a>
    <a href="https://www.instagram.com/myomrcommunity" target="_blank" rel="noopener noreferrer" class="social-icon-float instagram" aria-label="Instagram">
        <i class="fab fa-instagram"></i>
    </a>
    <a href="https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi" target="_blank" rel="noopener noreferrer" class="social-icon-float whatsapp" aria-label="WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    <a href="https://www.linkedin.com/company/myomr" target="_blank" rel="noopener" class="social-icon-float linkedin" aria-label="LinkedIn">
        <i class="fab fa-linkedin-in"></i>
    </a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS for Landing Page -->
<script>
(function() {
    'use strict';
    
    // Intersection Observer for Scroll Animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Mobile Menu Functionality
    function initMobileMenu() {
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
        const mobileMenuClose = document.getElementById('mobileMenuClose');
        
        function openMenu() {
            mobileMenu.classList.add('active');
            mobileMenuOverlay.classList.add('active');
            mobileMenuToggle.setAttribute('aria-expanded', 'true');
            mobileMenu.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }
        
        function closeMenu() {
            mobileMenu.classList.remove('active');
            mobileMenuOverlay.classList.remove('active');
            mobileMenuToggle.setAttribute('aria-expanded', 'false');
            mobileMenu.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
        
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', openMenu);
        }
        
        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', closeMenu);
        }
        
        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', closeMenu);
        }
        
        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                closeMenu();
            }
        });
        
        // Close menu when clicking on a link
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', function() {
                setTimeout(closeMenu, 300);
            });
        });
    }
    
    // Initialize Scroll Animations
    function initScrollAnimations() {
        const animatedElements = document.querySelectorAll('.service-card, .service-card-small, .stat-card, .section-title, .section-subtitle, .newsletter-card, .cta-section');
        
        animatedElements.forEach((el, index) => {
            el.classList.add('fade-in-up');
            el.style.transitionDelay = (index * 0.1) + 's';
            observer.observe(el);
        });
    }
    
    // Smooth Scroll for Anchor Links
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.length > 1) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        const headerOffset = 80;
                        const elementPosition = target.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                        
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                        
                        // Track smooth scroll clicks
                        if (typeof gtag !== 'undefined') {
                            gtag('event', 'click', {
                                'event_category': 'Navigation',
                                'event_label': 'Smooth Scroll to ' + href
                            });
                        }
                    }
                }
            });
        });
    }
    
    // Enhanced Scroll Depth Tracking
    function initScrollTracking() {
        let maxScroll = 0;
        const milestones = [25, 50, 75, 90, 100];
        const trackedMilestones = new Set();
        
        window.addEventListener('scroll', function() {
            const scrollPercent = Math.round((window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100);
            
            if (scrollPercent > maxScroll) {
                maxScroll = scrollPercent;
                
                milestones.forEach(milestone => {
                    if (scrollPercent >= milestone && !trackedMilestones.has(milestone)) {
                        trackedMilestones.add(milestone);
                        
                        if (typeof gtag !== 'undefined') {
                            gtag('event', 'scroll', {
                                'event_category': 'Engagement',
                                'event_label': milestone + '% scrolled',
                                'value': milestone
                            });
                        }
                    }
                });
            }
        }, { passive: true });
    }
    
    // Form Validation & Enhancement
    function initFormEnhancements() {
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                const input = this.querySelector('input[type="email"]');
                if (input && !input.validity.valid) {
                    e.preventDefault();
                    input.focus();
                    input.classList.add('error');
                    
                    // Track invalid submission
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'exception', {
                            'description': 'Invalid email submission',
                            'fatal': false
                        });
                    }
                } else {
                    // Track valid submission
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'conversion', {
                            'event_category': 'Newsletter',
                            'event_label': 'Newsletter Signup Landing',
                            'value': 1
                        });
                    }
                }
            });
        }
    }
    
    // Performance: Lazy Load Images
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('lazyload');
                            imageObserver.unobserve(img);
                        }
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }
    
    // Keyboard Navigation Enhancement
    function initKeyboardNavigation() {
        // Skip link functionality
        const skipLink = document.querySelector('.skip-link');
        if (skipLink) {
            skipLink.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.setAttribute('tabindex', '-1');
                    target.focus();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        }
        
        // Focus trap for mobile menu
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenu) {
            const focusableElements = mobileMenu.querySelectorAll('a, button, [tabindex]:not([tabindex="-1"])');
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            
            mobileMenu.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    if (e.shiftKey) {
                        if (document.activeElement === firstElement) {
                            e.preventDefault();
                            lastElement.focus();
                        }
                    } else {
                        if (document.activeElement === lastElement) {
                            e.preventDefault();
                            firstElement.focus();
                        }
                    }
                }
            });
        }
    }
    
    // Initialize all features when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initMobileMenu();
        initScrollAnimations();
        initSmoothScroll();
        initScrollTracking();
        initFormEnhancements();
        initLazyLoading();
        initKeyboardNavigation();
        
        // Add loaded class to body for CSS transitions
        document.body.classList.add('loaded');
        
        // Track page view
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_view', {
                'page_title': 'Digital Marketing Landing Page',
                'page_location': window.location.href
            });
        }
    });
    
    // Handle page visibility for analytics
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // Page is hidden
            if (typeof gtag !== 'undefined') {
                gtag('event', 'page_hidden', {
                    'event_category': 'Engagement'
                });
            }
        } else {
            // Page is visible
            if (typeof gtag !== 'undefined') {
                gtag('event', 'page_visible', {
                    'event_category': 'Engagement'
                });
            }
        }
    });
    
    // Performance: Preload critical resources
    if ('requestIdleCallback' in window) {
        requestIdleCallback(function() {
            // Preload next likely pages
            const links = document.querySelectorAll('a[href^="/"]');
            links.forEach(link => {
                if (link.href && !link.dataset.preloaded) {
                    const linkElement = document.createElement('link');
                    linkElement.rel = 'prefetch';
                    linkElement.href = link.href;
                    document.head.appendChild(linkElement);
                    link.dataset.preloaded = 'true';
                }
            });
        });
    }
})();
</script>

</body>
</html>
