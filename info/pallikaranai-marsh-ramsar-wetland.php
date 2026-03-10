<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php 
include '../weblog/log.php';
include '../core/omr-connect.php';

// Page variables for SEO
$page_title = 'Pallikaranai Marsh | Ramsar Wetland Site in Chennai';
$page_description = 'Explore Pallikaranai Marsh - Chennai\'s internationally recognized Ramsar wetland. Learn about its biodiversity, ecological importance, and conservation efforts for OMR residents.';
$page_keywords = 'Pallikaranai Marsh Chennai, Ramsar wetland, OMR nature, Chennai wetlands, biodiversity Chennai, bird watching Chennai, ecological importance';
$og_title = 'Pallikaranai Marsh - Ramsar Wetland Site Chennai';
$og_description = 'Discover the ecological importance and natural beauty of Pallikaranai Marsh, Chennai\'s Ramsar designated wetland.';
$og_image = 'https://myomr.in/images/pallikaranai-marsh-hero.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include '../components/meta.php'; ?>
<?php include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <!-- Favicon -->
    <link rel="icon" href="../My-OMR-Logo.png" type="image/jpeg">

    <!-- Page Specific Styles -->
    <style>
        :root {
            --myomr-green: #14532d;
            --myomr-light-green: #22c55e;
            --myomr-orange: #F77F00;
        }

        .hero-section {
            background: linear-gradient(135deg, #0f5132 0%, #166d47 50%, #22c55e 100%);
            color: white;
            padding: 5rem 0 4rem;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><path fill="rgba(255,255,255,0.05)" d="M0,300 Q300,100 600,300 T1200,300 L1200,600 L0,600 Z"/></svg>') repeat;
            opacity: 0.5;
        }

        .hero-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .hero-section .subtitle {
            font-family: 'Roboto', sans-serif;
            font-size: 1.3rem;
            opacity: 0.95;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .section-header {
            text-align: center;
            margin: 4rem 0 3rem;
        }

        .section-header h2 {
            font-family: 'Playfair Display', serif;
            color: var(--myomr-green);
            font-size: 2.5rem;
        }

        .nature-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            padding: 2rem;
            margin: 1.5rem 0;
            transition: transform 0.3s, box-shadow 0.3s;
            border-top: 4px solid var(--myomr-light-green);
        }

        .nature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .nature-card .icon {
            font-size: 3rem;
            color: var(--myomr-light-green);
            margin-bottom: 1rem;
        }

        .nature-card h3 {
            font-family: 'Playfair Display', serif;
            color: var(--myomr-green);
            font-size: 1.5rem;
        }

        .fact-highlight {
            background: linear-gradient(135deg, #22c55e 0%, #14532d 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            margin: 2rem 0;
        }

        .fact-highlight .number {
            font-size: 3rem;
            font-weight: bold;
            margin: 0.5rem 0;
        }

        .info-badge {
            display: inline-block;
            background: var(--myomr-light-green);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            margin: 0.5rem 0.5rem 0.5rem 0;
        }

        .biodiversity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .biodiversity-item {
            background: #f0fdf4;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            border: 2px solid var(--myomr-light-green);
        }

        .biodiversity-item strong {
            color: var(--myomr-green);
            display: block;
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .hero-section h1 { font-size: 2rem; }
            .section-header h2 { font-size: 1.8rem; }
        }
    </style>

    <!-- JSON-LD Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "TouristAttraction",
      "name": "Pallikaranai Marsh Reserve Forest",
      "description": "Ramsar designated wetland site in Chennai, home to diverse flora and fauna",
      "url": "https://myomr.in/info/pallikaranai-marsh-ramsar-wetland.php",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Chennai",
        "addressRegion": "Tamil Nadu",
        "addressCountry": "India"
      },
      "sameAs": "https://rsis.ramsar.org/ris/2481"
    }
    </script>
</head>

<body>
<!-- Header & Navigation -->
<?php include '../components/main-nav.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1><i class="fas fa-leaf"></i> Pallikaranai Marsh</h1>
        <p class="subtitle">Chennai's Ramsar Wetland - An Ecological Treasure for OMR Residents</p>
        <div class="mt-4">
            <span class="info-badge"><i class="fas fa-certificate"></i> Ramsar Site #2481</span>
            <span class="info-badge"><i class="fas fa-calendar"></i> Designated 2022</span>
            <span class="info-badge"><i class="fas fa-map-marker-alt"></i> Chennai, Tamil Nadu</span>
        </div>
    </div>
</section>

<!-- Content -->
<main class="container py-5">
    
    <!-- What is Pallikaranai Marsh -->
    <section class="section-header">
        <h2>About Pallikaranai Marsh</h2>
        <p class="lead">One of the last remaining natural wetlands in Chennai</p>
    </section>

    <div class="nature-card">
        <div class="icon"><i class="fas fa-water"></i></div>
        <h3>Natural Wetland Ecosystem</h3>
        <p>Pallikaranai Marsh is a 1,247-hectare wetland reserve that serves as a critical natural ecosystem in the heart of Chennai. This marshland is one of the few remaining natural water bodies in South Chennai and plays a vital role in the region's ecological balance.</p>
        
        <p>The marsh connects to the Bay of Bengal through the Okkiyam Maduvu channel, Buckingham Canal, and Kovalam estuary, creating an important hydrological system that supports both wildlife and the surrounding urban areas.</p>
    </div>

    <!-- Key Facts -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="fact-highlight">
                <div class="icon"><i class="fas fa-ruler-combined"></i></div>
                <div class="number">1,247 ha</div>
                <div>Wetland Area</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="fact-highlight">
                <div class="icon"><i class="fas fa-globe-asia"></i></div>
                <div class="number">7</div>
                <div>Ramsar Criteria Met</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="fact-highlight">
                <div class="icon"><i class="fas fa-feather-alt"></i></div>
                <div class="number">200+</div>
                <div>Bird Species</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="fact-highlight">
                <div class="icon"><i class="fas fa-cloud-rain"></i></div>
                <div class="number">Natural</div>
                <div>Flood Buffer</div>
            </div>
        </div>
    </div>

    <!-- Biodiversity -->
    <section class="section-header">
        <h2><i class="fas fa-seedling"></i> Biodiversity & Wildlife</h2>
        <p>Home to diverse species of birds, fish, and aquatic life</p>
    </section>

    <div class="row">
        <div class="col-md-6">
            <div class="nature-card">
                <div class="icon"><i class="fas fa-dove"></i></div>
                <h3>Bird Species</h3>
                <p>Pallikaranai Marsh serves as a crucial stopover on the Central Asian Flyway, attracting hundreds of migratory bird species each year. The wetland is home to over 200 species of birds including:</p>
                <div class="biodiversity-grid">
                    <div class="biodiversity-item">
                        <strong>Ducks & Geese</strong>
                        Winter migrants
                    </div>
                    <div class="biodiversity-item">
                        <strong>Herons & Egrets</strong>
                        Wading birds
                    </div>
                    <div class="biodiversity-item">
                        <strong>Cormorants</strong>
                        Fishing birds
                    </div>
                    <div class="biodiversity-item">
                        <strong>Raptors</strong>
                        Birds of prey
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="nature-card">
                <div class="icon"><i class="fas fa-fish"></i></div>
                <h3>Aquatic Life</h3>
                <p>The marsh supports a rich diversity of fish and aquatic organisms that form the base of the wetland food chain. Several species found here are important for local fishing communities.</p>
                <ul>
                    <li>Native fish species adapted to wetland conditions</li>
                    <li>Shrimp and crustaceans</li>
                    <li>Aquatic insects and invertebrates</li>
                    <li>Plankton essential for ecosystem health</li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="nature-card">
                <div class="icon"><i class="fas fa-leaf"></i></div>
                <h3>Flora</h3>
                <p>The wetland vegetation includes a variety of native aquatic plants, reeds, and marsh grasses that provide habitat and food for wildlife while also helping to filter water and stabilize sediments.</p>
                <ul>
                    <li>Marsh grasses and sedges</li>
                    <li>Aquatic plants and floating vegetation</li>
                    <li>Mangrove species near canal outlets</li>
                    <li>Native reed species</li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="nature-card">
                <div class="icon"><i class="fas fa-spider"></i></div>
                <h3>Other Wildlife</h3>
                <p>Beyond birds and fish, the marsh supports a variety of terrestrial and semi-aquatic animals that depend on the wetland ecosystem.</p>
                <ul>
                    <li>Amphibians like frogs and toads</li>
                    <li>Reptiles including snakes and water monitors</li>
                    <li>Small mammals</li>
                    <li>Diverse insect populations</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Ecological Importance -->
    <section class="section-header">
        <h2><i class="fas fa-home"></i> Why It Matters for OMR Residents</h2>
        <p>The marsh plays multiple vital roles in our ecosystem</p>
    </section>

    <div class="row">
        <div class="col-md-6">
            <div class="nature-card">
                <div class="icon"><i class="fas fa-cloud-rain"></i></div>
                <h3>Natural Flood Protection</h3>
                <p>The marsh acts as a natural sponge during heavy rains, storing excess water and gradually releasing it into the drainage system. This helps prevent flooding in nearby OMR neighborhoods during monsoon season.</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="nature-card">
                <div class="icon"><i class="fas fa-faucet"></i></div>
                <h3>Groundwater Recharge</h3>
                <p>As water percolates through the wetland, it helps recharge the South Chennai aquifer, providing a vital source of groundwater for the region.</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="nature-card">
                <div class="icon"><i class="fas fa-thermometer-half"></i></div>
                <h3>Temperature Regulation</h3>
                <p>The wetland helps moderate local temperatures, providing a cooling effect that makes the OMR area more comfortable, especially during hot summers.</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="nature-card">
                <div class="icon"><i class="fas fa-wind"></i></div>
                <h3>Air Quality</h3>
                <p>Wetland vegetation helps filter pollutants from the air, contributing to better air quality in the OMR corridor.</p>
            </div>
        </div>
    </div>

    <!-- Conservation Efforts -->
    <section class="section-header">
        <h2><i class="fas fa-hands-helping"></i> Conservation & Protection</h2>
        <p>Efforts to preserve this vital ecosystem</p>
    </section>

    <div class="nature-card">
        <div class="icon"><i class="fas fa-certificate"></i></div>
        <h3>Ramsar Designation</h3>
        <p>In April 2022, Pallikaranai Marsh was designated as India's 242nd Ramsar Site, recognizing its international importance for biodiversity and wetland conservation. This designation brings:</p>
        <ul>
            <li>Enhanced protection under the Wetlands Rules 2017</li>
            <li>Scientific management plans for sustainable use</li>
            <li>International recognition and support for conservation</li>
            <li>Clear guidelines for wise use of the wetland</li>
        </ul>
    </div>

    <div class="nature-card">
        <div class="icon"><i class="fas fa-shield-alt"></i></div>
        <h3>Conservation Initiatives</h3>
        <p>The Tamil Nadu State Wetland Authority, Forest Department, and Chennai Metropolitan Development Authority work together to protect the marsh through:</p>
        <div class="row mt-3">
            <div class="col-md-6">
                <ul>
                    <li>Boundary protection and fencing</li>
                    <li>Removal of invasive species</li>
                    <li>Waste cleanup and restoration</li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul>
                    <li>Scientific monitoring programs</li>
                    <li>Public awareness campaigns</li>
                    <li>Habitat restoration projects</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Visiting the Marsh -->
    <section class="section-header">
        <h2><i class="fas fa-camera"></i> Observing the Marsh</h2>
        <p>How residents can appreciate this natural treasure</p>
    </section>

    <div class="nature-card">
        <h3><i class="fas fa-binoculars"></i> Bird Watching</h3>
        <p>The marsh offers excellent bird watching opportunities, especially during the migration season (October to March). Common species visible include various herons, egrets, ducks, and other water birds.</p>
        <p><strong>Best Times:</strong> Early mornings and late afternoons offer the best viewing conditions.</p>
    </div>

    <div class="nature-card">
        <h3><i class="fas fa-sun"></i> Photography</h3>
        <p>The marsh provides stunning opportunities for nature photography, from sunrise and sunset views over the water to close-up shots of birds and wildlife.</p>
    </div>

    <div class="nature-card">
        <h3><i class="fas fa-info-circle"></i> Educational Value</h3>
        <p>Schools and educational institutions often organize field trips to observe and learn about wetland ecology, conservation, and the importance of protecting natural habitats.</p>
    </div>

    <!-- How You Can Help -->
    <section class="section-header">
        <h2><i class="fas fa-heart"></i> How You Can Help</h2>
        <p>Every resident can contribute to wetland conservation</p>
    </section>

    <div class="row">
        <div class="col-md-4">
            <div class="nature-card text-center">
                <div class="icon"><i class="fas fa-recycle"></i></div>
                <h4>Proper Waste Disposal</h4>
                <p>Avoid dumping waste near the marsh. Proper disposal prevents pollution of this important water resource.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="nature-card text-center">
                <div class="icon"><i class="fas fa-share-alt"></i></div>
                <h4>Spread Awareness</h4>
                <p>Educate friends and neighbors about the importance of the marsh and its role in flood protection.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="nature-card text-center">
                <div class="icon"><i class="fas fa-leaf"></i></div>
                <h4>Support Conservation</h4>
                <p>Participate in local conservation efforts and support organizations working to protect Chennai's wetlands.</p>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <section class="my-5 p-5" style="background: linear-gradient(135deg, #14532d 0%, #22c55e 100%); border-radius: 15px; color: white; text-align: center;">
        <h2 style="color: white; font-family: 'Playfair Display', serif;">Protecting Chennai's Natural Heritage</h2>
        <p class="lead">Pallikaranai Marsh is not just a wetland - it's a vital part of Chennai's ecosystem that benefits all OMR residents through flood protection, water recharge, and biodiversity conservation.</p>
        <a href="/contact-my-omr-team.php" class="btn btn-light btn-lg mt-3">
            <i class="fas fa-envelope mr-2"></i>Contact Us About Conservation
        </a>
    </section>

</main>

<!-- Footer Section -->
<?php include '../components/footer.php'; ?>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

