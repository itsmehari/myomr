<?php
/**
 * Add New Space Form
 * Allows space owners to add new listings
 */

require_once __DIR__ . '/includes/error-reporting.php';
require_once __DIR__ . '/includes/owner-auth.php';
requireOwnerAuth();

// If redirected here, auto-login
if (!isOwnerLoggedIn() && !empty($_GET['email'])) {
    ownerLogin(sanitizeInput($_GET['email']));
}

// SEO Meta
$page_title = "Add Your Workspace - MyOMR Coworking Spaces";
$page_description = "List your coworking space on MyOMR. Reach thousands of freelancers and professionals in OMR Chennai.";
$canonical_url = "https://myomr.in/omr-coworking-spaces/add-space.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/coworking-spaces.css">
    <?php include '../components/analytics.php'; ?>
</head>
<body class="modern-page">

<?php require_once __DIR__ . '/../components/main-nav.php'; ?>

<div class="hero-modern">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center text-white">
            <div>
                <h1 class="hero-modern-title mb-2">Add Your Workspace</h1>
                <p class="hero-modern-subtitle mb-0">List your coworking space and reach thousands of potential users</p>
            </div>
            <a href="my-spaces.php" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<main class="py-5">
    <div class="container">
        <form method="POST" action="process-space.php" id="add-space-form" class="needs-validation" novalidate>
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
                        <div class="col-md-12 mb-3">
                            <label for="space_name" class="form-label-modern required-field">Workspace Name</label>
                            <input type="text" class="form-control-modern" id="space_name" name="space_name" required placeholder="e.g., Innovation Hub Coworking">
                            <div class="invalid-feedback">Please provide a workspace name.</div>
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
                            <label for="total_capacity" class="form-label-modern">Total Capacity (Seats)</label>
                            <input type="number" class="form-control-modern" id="total_capacity" name="total_capacity" min="1" placeholder="e.g., 50">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="operating_hours" class="form-label-modern">Operating Hours</label>
                            <input type="text" class="form-control-modern" id="operating_hours" name="operating_hours" placeholder="e.g., Mon-Sat: 9 AM - 8 PM">
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
                        <textarea class="form-control-modern" id="full_description" name="full_description" rows="5" required placeholder="Detailed description of your workspace"></textarea>
                        <div class="invalid-feedback">Please provide a full description.</div>
                    </div>
                </div>
            </div>
            
            <!-- Section 3: Pricing & Amenities -->
            <div class="card-modern mb-4">
                <div class="p-4 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-section-icon">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                        <div>
                            <h3 class="h5 mb-0">Pricing & Amenities</h3>
                            <small class="text-muted">Step 3 of 4</small>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="day_pass_price" class="form-label-modern">Day Pass (₹/day)</label>
                            <input type="number" class="form-control-modern" id="day_pass_price" name="day_pass_price" min="0" placeholder="500">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="hot_desk_monthly" class="form-label-modern">Hot Desk Monthly (₹)</label>
                            <input type="number" class="form-control-modern" id="hot_desk_monthly" name="hot_desk_monthly" min="0" placeholder="8000">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dedicated_desk_monthly" class="form-label-modern">Dedicated Desk Monthly (₹)</label>
                            <input type="number" class="form-control-modern" id="dedicated_desk_monthly" name="dedicated_desk_monthly" min="0" placeholder="12000">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="private_cabin_monthly" class="form-label-modern">Private Cabin Monthly (₹)</label>
                            <input type="number" class="form-control-modern" id="private_cabin_monthly" name="private_cabin_monthly" min="0" placeholder="25000">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="amenities" class="form-label-modern">Key Amenities</label>
                            <input type="text" class="form-control-modern" id="amenities" name="amenities" placeholder="Comma separated: WiFi, AC, Meeting Rooms, Cafeteria">
                            <div class="help-text-modern">Enter amenities separated by commas</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="special_offers" class="form-label-modern">Special Offers</label>
                            <input type="text" class="form-control-modern" id="special_offers" name="special_offers" placeholder="e.g., First month 20% off">
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
                            <input type="text" class="form-control-modern" id="contact_person" name="contact_person" placeholder="e.g., John Doe">
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
                            <li>Your workspace will be reviewed by our team</li>
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
                            <span>Submit Workspace Listing</span>
                        </button>
                        <a href="my-spaces.php" class="btn-modern btn-modern-secondary">
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
  var form = document.getElementById('add-space-form');
  if (!form) return;
  form.addEventListener('focusin', function() {
    if (started) return;
    started = true;
    if (typeof gtag === 'function') { gtag('event', 'form_start', { 'form_name': 'add_coworking_space' }); }
  });
})();
</script>

</body>
</html>

