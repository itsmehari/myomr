<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php 
include '../weblog/log.php';
include '../core/omr-connect.php';

// Page variables for SEO
$page_title = 'Pallikaranai Ramsar: Complete Guide for OMR/ECR Residents | MyOMR';
$page_description = 'Complete guide to Pallikaranai Marsh Ramsar site: designation date, flood protection for OMR/ECR, recent NGT orders, and buyer verification steps for Chennai residents.';
$page_keywords = 'Pallikaranai Marsh, Ramsar site Chennai, OMR floods, ECR flooding, Okkiyam Maduvu, CMDA approvals, SEIAA TN, NGT order Chennai';
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
    <link rel="icon" href="../My-OMR-Logo.jpg" type="image/jpeg">

    <!-- Article Specific Styles -->
    <style>
        :root {
            --myomr-green: #14532d;
            --myomr-light-green: #22c55e;
        }

        .article-hero {
            background: linear-gradient(135deg, #0f5132 0%, #22c55e 100%);
            color: white;
            padding: 3rem 0;
            text-align: center;
        }

        .article-container {
            max-width: 900px;
            margin: auto;
            padding: 2rem 1rem;
            font-family: 'Roboto', sans-serif;
            line-height: 1.8;
        }

        .article-container h2 {
            font-family: 'Playfair Display', serif;
            color: var(--myomr-green);
            font-size: 2rem;
            margin: 2rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--myomr-light-green);
        }

        .article-container h3 {
            font-family: 'Playfair Display', serif;
            color: var(--myomr-green);
            font-size: 1.5rem;
            margin: 1.5rem 0 1rem;
        }

        .info-box {
            background: #e7f5e7;
            border-left: 4px solid var(--myomr-light-green);
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 5px;
        }

        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 5px;
        }

        .alert-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 5px;
        }

        .article-meta {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 1rem 0;
        }

        .fact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .fact-card {
            background: #f0fdf4;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            border: 2px solid var(--myomr-light-green);
        }

        .fact-card .number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--myomr-green);
        }

        .fact-card .label {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.5rem;
        }
    </style>

    <!-- JSON-LD Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsArticle",
      "headline": "Pallikaranai Ramsar: Complete Guide for OMR/ECR Residents",
      "datePublished": "2025-10-27",
      "author": {
        "@type": "Person",
        "name": "MyOMR Editorial Team"
      },
      "description": "Complete guide to Pallikaranai Marsh Ramsar site including designation, flood protection, NGT orders, and buyer verification steps"
    }
    </script>
</head>

<body>
<?php include '../components/main-nav.php'; ?>

<!-- Hero Section -->
<section class="article-hero">
    <div class="container">
        <h1><i class="fas fa-water"></i> Pallikaranai Ramsar: Complete Guide</h1>
        <p>What OMR/ECR Residents Need to Know</p>
        <div class="article-meta">
            Published: October 27, 2025 | Author: MyOMR Editorial Team
        </div>
    </div>
</section>

<!-- Article Content -->
<main class="article-container">
    <div class="alert alert-danger">
        <strong>Disclaimer:</strong> For informational purposes only. Consult qualified professionals for legal/environmental due diligence.
    </div>

    <!-- Key Facts -->
    <div class="fact-grid">
        <div class="fact-card">
            <div class="number">2481</div>
            <div class="label">Ramsar Site Number</div>
        </div>
        <div class="fact-card">
            <div class="number">1,247 ha</div>
            <div class="label">Official Area</div>
        </div>
        <div class="fact-card">
            <div class="number">April 8, 2022</div>
            <div class="label">Designation Date</div>
        </div>
        <div class="fact-card">
            <div class="number">7</div>
            <div class="label">Criteria Met</div>
        </div>
    </div>

    <h2>What is a Ramsar Site?</h2>
    <p>Ramsar Sites are wetlands listed under the intergovernmental Ramsar Convention for their international ecological importance. Contracting Parties commit to "wise use": maintain ecological character, avoid degradation, and manage through science-based plans and regulation.</p>
    
    <p>In India, Ramsar sites are regulated through the Environment (Protection) Act, 1986 via the Wetlands (Conservation & Management) Rules, 2017, which require State Wetland Authorities to notify wetlands, define boundaries/"zone of influence," list prohibited and regulated activities, and prepare Integrated Management Plans (IMPs).</p>

    <div class="info-box">
        <strong>Sources:</strong> <a href="https://ramsar.org" target="_blank">Ramsar Convention</a>, <a href="https://www.moef.gov.in" target="_blank">MoEFCC</a>
    </div>

    <h2>Pallikaranai Marsh at a Glance</h2>
    <ul>
        <li><strong>Official name:</strong> Pallikaranai Marsh Reserve Forest (Ramsar Site)</li>
        <li><strong>Ramsar Site Number:</strong> 2481</li>
        <li><strong>Designation date:</strong> 2022-04-08 (as per the Ramsar Information Sheet)</li>
        <li><strong>Official area:</strong> 1,247.537 hectares</li>
        <li><strong>Coordinates (centroid):</strong> 12.966389 N; 80.209722 E</li>
        <li><strong>Ramsar criteria met:</strong> 1, 2, 3, 4, 6, 7, 8 (representative wetland type; threatened species; biodiversity significance; waterbird thresholds; fish life-cycle support)</li>
    </ul>

    <h2>Why OMR/ECR Residents Should Care</h2>

    <h3>1. Flood Attenuation, Storage, and Recharge</h3>
    <p>Pallikaranai functions as a storm-water sponge: it receives excess runoff from dozens of upstream lakes and storm channels, stores monsoon flows, slows flood peaks, and recharges the South Chennai aquifer—a critical water buffer for neighborhoods along OMR/ECR.</p>

    <div class="info-box">
        <strong>Recent Update:</strong> Okkiyam Maduvu capacity has been enhanced (ventway widening) following Cyclone Michaung to improve flood conveyance and protect OMR neighborhoods.
    </div>

    <h3>2. Waterway Linkages That Protect Your Streets</h3>
    <p>Two principal outlets move water from the wetland complex toward the sea:</p>
    <ul>
        <li><strong>Okkiyam Maduvu</strong> → Buckingham Canal → Kovalam estuary/Bay of Bengal</li>
    </ul>
    
    <p>During heavy rain, upstream tanks and urban drains overflow into Pallikaranai; surplus flows exit via Okkiyam Maduvu into Buckingham Canal and out through the Kovalam estuary. If these paths are narrowed or blocked, OMR/ECR inundation risk spikes.</p>

    <h3>3. Biodiversity, Public Health, and Microclimate</h3>
    <p>The site is on the Central Asian flyway and supports threatened species and fish assemblages. Wetland cooling also mitigates urban heat-island effects for adjacent high-density corridors.</p>

    <h2>Recent News and Court Orders</h2>

    <div class="info-box">
        <h4>September 2025 - NGT Order</h4>
        <p><strong>NGT Order:</strong> National Green Tribunal (Southern Zone) restrains CMDA from issuing building approvals "in and around" Pallikaranai until scientific boundaries/buffers are finalized and integrated into planning. Notices issued to agencies and developers.</p>
        <p><strong>Effect:</strong> De-facto freeze pending IMP and mapping handover.</p>
    </div>

    <div class="info-box">
        <h4>October 2025 - CMDA Compliance</h4>
        <p><strong>CMDA Compliance:</strong> CMDA circulates compliance instructions referencing NGT. Reportage notes no permits inside the Ramsar site and a 1-km influence zone until further notice.</p>
        <p><strong>Effect:</strong> Local bodies told to enforce the halt.</p>
    </div>

    <h2>How to Self-Verify If Your Plot is at Risk</h2>

    <h3>Step 1: Pin Down Your Parcel</h3>
    <p>Pull Survey No., sub-division, village from sale deed/EC or A-Register/FMB at Revenue/TN-e-sevai.</p>

    <h3>Step 2: Overlay with Official Wetland Layers</h3>
    <p>Check <a href="https://rsis.ramsar.org/ris/2481" target="_blank">RSIS/RIS for Ramsar footprint</a>; watch for TNSWA notifications/maps and CMDA Master Plan layers showing "swamp/conservation." If in doubt, file an RTI to TNSWA/CMDA seeking "whether Survey No. ___ falls within Ramsar/wetland or influence zone."</p>

    <h3>Step 3: Verify Environmental Clearance</h3>
    <p>For any project you plan to buy in (≥20,000 m² built-up), locate its SEIAA-TN EC on the <a href="https://environmentclearance.nic.in" target="_blank">national portal</a>; verify scope, survey numbers, and standard conditions. Absence/mismatch is a red flag.</p>

    <h3>Step 4: Check Planning/Building Permission</h3>
    <p>Verify CMDA/Local Body approval number, date, drawings, conditions. If the plot is in or near Ramsar/influence zone, approvals may be invalidated or stayed by operative NGT directions.</p>

    <h3>Step 5: Verify TNPCB Consents</h3>
    <p>Large projects require CTE/CTO; search <a href="https://ocmms.tn.gov.in" target="_blank">TNPCB portals</a> for granted consents and compliance status.</p>

    <h3>Step 6: Check RERA Registration</h3>
    <p>Verify <a href="https://rera.tn.gov.in" target="_blank">TNRERA registration</a>, sanctioned plan, and litigation disclosures.</p>

    <div class="alert-box">
        <strong>⚠️ If You Detect a Conflict:</strong><br>
        • File TNPCB complaint (pollution/solid waste)<br>
        • Contact CM Helpline/Dept. portals (encroachment/compliance)<br>
        • File NGT e-filing for environmental matters (Southern Zone)<br>
        • File RTI to TNSWA/CMDA/SEIAA for records & maps
    </div>

    <h2>FAQs</h2>

    <div class="row">
        <div class="col-md-6">
            <h4>Is Pallikaranai a National Park?</h4>
            <p>No. Ramsar is an international wetland designation; not the same as a Wildlife Sanctuary/National Park, but India's Wetland Rules 2017 regulate Ramsar sites comparably for ecological protection.</p>
        </div>

        <div class="col-md-6">
            <h4>Is Construction Allowed Near Ramsar Sites?</h4>
            <p>Inside notified wetland: <strong>prohibited</strong> permanent construction. In the vicinity: regulated through IMP/State orders; as of now, NGT-linked freezes apply around Pallikaranai pending final mapping.</p>
        </div>
    </div>

    <h2>References</h2>
    <ul>
        <li><a href="https://rsis.ramsar.org/ris/2481" target="_blank">Official Ramsar Site Information</a></li>
        <li><a href="https://www.moef.gov.in" target="_blank">MoEFCC - Wetlands Rules 2017</a></li>
        <li><a href="https://environmentclearance.nic.in" target="_blank">Environment Clearance Portal</a></li>
        <li><a href="https://cmdachennai.gov.in" target="_blank">CMDA Master Plan</a></li>
        <li><a href="https://ocmms.tn.gov.in" target="_blank">TNPCB Pollution Monitoring</a></li>
        <li><a href="https://rera.tn.gov.in" target="_blank">TNRERA Registration</a></li>
    </ul>

    <hr>

    <div class="text-center mt-5 mb-5">
        <h3>Explore More</h3>
        <a href="/info/pallikaranai-marsh-ramsar-wetland.php" class="btn btn-success btn-lg">
            <i class="fas fa-leaf"></i> Learn About Natural Benefits & Wildlife
        </a>
    </div>
</main>

<?php include '../components/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

