<?php
/**
 * Add New Property Form – uses module bootstrap and root layout.
 */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/owner-auth.php';
requireOwnerAuth();

if (!isOwnerLoggedIn() && !empty($_GET['email'])) {
    ownerLogin(sanitizeInput($_GET['email']));
}

$page_nav = 'main';
$page_title = 'Add Your Property - MyOMR Hostels & PGs';
$page_description = 'List your hostel or PG on MyOMR. Reach thousands of students and professionals in OMR Chennai.';
$canonical_url = 'https://myomr.in/omr-hostels-pgs/add-property.php';
$og_type = 'website';
$breadcrumbs = [
    ['https://myomr.in/', 'Home'],
    ['https://myomr.in/omr-hostels-pgs/', 'Hostels & PGs in OMR'],
    [$canonical_url, 'Add Property'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once ROOT_PATH . '/components/meta.php'; ?>
    <?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
    <link rel="stylesheet" href="/omr-hostels-pgs/assets/hostels-pgs.css">
</head>
<body class="modern-page">

<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav(); ?>

<div class="hero-modern">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center text-white">
            <div>
                <h1 class="hero-modern-title mb-2">Add Your Property</h1>
                <p class="hero-modern-subtitle mb-0">List your hostel or PG and reach thousands of potential tenants</p>
            </div>
            <a href="my-properties.php" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<main class="py-5">
    <div class="container">
        <form method="POST" action="process-property.php" id="add-property-form" class="needs-validation" novalidate>
            <?php
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <!-- Section 1: Basic Information -->
            <div class="card-modern mb-4">
                <div class="p-4 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-section-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h3 class="h5 mb-0">Basic Information</h3>
                            <small class="text-muted">Step 1 of 4</small>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="property_name" class="form-label-modern required-field">Property Name</label>
                            <input type="text" class="form-control-modern" id="property_name" name="property_name" required placeholder="e.g., Green View Boys PG">
                            <div class="invalid-feedback">Please provide a property name.</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="property_type" class="form-label-modern required-field">Property Type</label>
                            <select class="form-control-modern" id="property_type" name="property_type" required>
                                <option value="">Select Type</option>
                                <option value="PG">PG (Paying Guest)</option>
                                <option value="Hostel">Hostel</option>
                                <option value="Co-living">Co-living</option>
                            </select>
                            <div class="invalid-feedback">Please select a property type.</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label-modern required-field">Complete Address</label>
                        <textarea class="form-control-modern" id="address" name="address" rows="2" required placeholder="Street address, building name, etc."></textarea>
                        <div class="invalid-feedback">Please provide the complete address.</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="locality" class="form-label-modern required-field">Locality</label>
                            <select class="form-control-modern" id="locality" name="locality" required>
                                <option value="">Select Locality</option>
                                <option value="Navalur">Navalur</option>
                                <option value="Sholinganallur">Sholinganallur</option>
                                <option value="Thoraipakkam">Thoraipakkam</option>
                                <option value="Kandhanchavadi">Kandhanchavadi</option>
                                <option value="Perungudi">Perungudi</option>
                                <option value="Okkiyam Thuraipakkam">Okkiyam Thuraipakkam</option>
                                <option value="Other">Other (OMR)</option>
                            </select>
                            <div class="invalid-feedback">Please select a locality.</div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="landmark" class="form-label-modern">Landmark</label>
                            <input type="text" class="form-control-modern" id="landmark" name="landmark" placeholder="e.g., Near Metro Station">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="pincode" class="form-label-modern">Pin Code</label>
                            <input type="text" class="form-control-modern" id="pincode" name="pincode" placeholder="e.g., 600130">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nearby_metro" class="form-label-modern">Nearby Metro Station</label>
                            <input type="text" class="form-control-modern" id="nearby_metro" name="nearby_metro" placeholder="e.g., Navalur Metro">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nearby_bus_stand" class="form-label-modern">Nearby Bus Stand</label>
                            <input type="text" class="form-control-modern" id="nearby_bus_stand" name="nearby_bus_stand" placeholder="e.g., Navalur Bus Stand">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section 2: Description -->
            <div class="card-modern mb-4">
                <div class="p-4 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-section-icon">
                            <i class="fas fa-file-text"></i>
                        </div>
                        <div>
                            <h3 class="h5 mb-0">Description</h3>
                            <small class="text-muted">Step 2 of 4</small>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        <label for="brief_overview" class="form-label-modern required-field">Brief Overview</label>
                        <textarea class="form-control-modern" id="brief_overview" name="brief_overview" rows="2" required placeholder="One-line summary (100-150 words)"></textarea>
                        <div class="help-text-modern">This appears in search results</div>
                        <div class="invalid-feedback">Please provide a brief overview.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="full_description" class="form-label-modern required-field">Full Description</label>
                        <textarea class="form-control-modern" id="full_description" name="full_description" rows="5" required placeholder="Detailed description of your property"></textarea>
                        <div class="invalid-feedback">Please provide a full description.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="house_rules" class="form-label-modern">House Rules</label>
                        <textarea class="form-control-modern" id="house_rules" name="house_rules" rows="3" placeholder="e.g., No smoking, No alcohol, Entry time 11 PM"></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Section 3: Property Details -->
            <div class="card-modern mb-4">
                <div class="p-4 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-section-icon">
                            <i class="fas fa-bed"></i>
                        </div>
                        <div>
                            <h3 class="h5 mb-0">Property Details</h3>
                            <small class="text-muted">Step 3 of 4</small>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="total_beds" class="form-label-modern">Total Beds Available</label>
                            <input type="number" class="form-control-modern" id="total_beds" name="total_beds" min="1" placeholder="e.g., 20">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="gender_preference" class="form-label-modern required-field">Gender Preference</label>
                            <select class="form-control-modern" id="gender_preference" name="gender_preference" required>
                                <option value="">Select</option>
                                <option value="Boys Only">Boys Only</option>
                                <option value="Girls Only">Girls Only</option>
                                <option value="Co-living">Co-living</option>
                            </select>
                            <div class="invalid-feedback">Please select gender preference.</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="monthly_rent_single" class="form-label-modern">Single Room (₹/month)</label>
                            <input type="number" class="form-control-modern" id="monthly_rent_single" name="monthly_rent_single" min="0" placeholder="6000">
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="monthly_rent_double" class="form-label-modern">Double Sharing (₹/month)</label>
                            <input type="number" class="form-control-modern" id="monthly_rent_double" name="monthly_rent_double" min="0" placeholder="4500">
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="monthly_rent_triple" class="form-label-modern">Triple Sharing (₹/month)</label>
                            <input type="number" class="form-control-modern" id="monthly_rent_triple" name="monthly_rent_triple" min="0" placeholder="3500">
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="security_deposit" class="form-label-modern">Security Deposit (₹)</label>
                            <input type="number" class="form-control-modern" id="security_deposit" name="security_deposit" min="0" placeholder="5000">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="food_options" class="form-label-modern">Food Options</label>
                            <input type="text" class="form-control-modern" id="food_options" name="food_options" placeholder="e.g., Veg and Non-Veg, 3 meals per day">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="facilities" class="form-label-modern">Key Facilities</label>
                            <input type="text" class="form-control-modern" id="facilities" name="facilities" placeholder="Comma separated: WiFi, AC, Food, Laundry">
                            <div class="help-text-modern">Enter facilities separated by commas</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_student_friendly" name="is_student_friendly" value="1">
                            <label class="form-check-label" for="is_student_friendly">
                                Student-Friendly Accommodation
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section 4: Contact & Submit -->
            <div class="card-modern mb-4">
                <div class="p-4 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-section-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <h3 class="h5 mb-0">Review & Submit</h3>
                            <small class="text-muted">Step 4 of 4</small>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <h5 class="mb-3">Contact Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_person" class="form-label-modern">Contact Person</label>
                            <input type="text" class="form-control-modern" id="contact_person" name="contact_person" placeholder="e.g., Ram Kumar">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact_email" class="form-label-modern">Contact Email</label>
                            <input type="email" class="form-control-modern" id="contact_email" name="contact_email" placeholder="contact@example.com">
                            <div class="help-text-modern">Optional - defaults to your registered email</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_phone" class="form-label-modern">Contact Phone</label>
                            <input type="tel" class="form-control-modern" id="contact_phone" name="contact_phone" placeholder="e.g., 9876543210">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact_whatsapp" class="form-label-modern">WhatsApp (Optional)</label>
                            <input type="tel" class="form-control-modern" id="contact_whatsapp" name="contact_whatsapp" placeholder="e.g., 9876543210">
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>What Happens Next?</h5>
                        <ul class="mb-0">
                            <li>Your property will be reviewed by our team</li>
                            <li>Review typically takes 24-48 hours</li>
                            <li>You'll receive an email once your listing is approved</li>
                            <li>All false or misleading listings will be rejected</li>
                        </ul>
                    </div>
                    
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="../terms-and-conditions-my-omr.php" target="_blank">Terms and Conditions</a> and confirm this information is accurate
                        </label>
                        <div class="invalid-feedback">You must agree to the terms.</div>
                    </div>
                    
                    <div class="d-flex gap-3 flex-wrap">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="fas fa-paper-plane"></i>
                            <span>Submit Property Listing</span>
                        </button>
                        <a href="my-properties.php" class="btn-modern btn-modern-secondary">
                            <i class="fas fa-times"></i>
                            <span>Cancel</span>
                        </a>
                    </div>
                </div>
            </div>
            
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function() {
  var started = false;
  var form = document.getElementById('add-property-form');
  if (!form) return;
  form.addEventListener('focusin', function() {
    if (started) return;
    started = true;
    if (typeof gtag === 'function') { gtag('event', 'form_start', { 'form_name': 'add_pg_property' }); }
  });
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
<?php include ROOT_PATH . '/components/sdg-badge.php'; ?>
</body>
</html>

