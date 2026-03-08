<?php
/**
 * Employer Applicant Card Component
 * Displays individual applicant profile in the dashboard
 * 
 * @var array $app|$applicationData - Application data from database
 */

// Support both variable names for flexibility
if (!isset($app) && isset($applicationData)) {
    $app = $applicationData;
}
if (!isset($app)) {
    return; // No data to display
}

// Calculate age from birth date or use stored age
$applicantAge = !empty($app['applicant_age']) ? (int)$app['applicant_age'] : null;
$applicantName = htmlspecialchars($app['applicant_name'] ?? '');
$applicantEmail = htmlspecialchars($app['applicant_email'] ?? '');
$applicantPhone = htmlspecialchars($app['applicant_phone'] ?? '');
$applicantInitial = strtoupper(substr($applicantName, 0, 1));

// Status
$status = strtolower($app['status'] ?? 'pending');
$statusBadgeClass = 'secondary';
if ($status === 'shortlisted') $statusBadgeClass = 'success';
elseif ($status === 'reviewed') $statusBadgeClass = 'info';
elseif ($status === 'rejected') $statusBadgeClass = 'danger';
elseif ($status === 'pending') $statusBadgeClass = 'warning';

// Contact preferences
$contactMobile = ($app['contact_preference'] ?? 'Both') !== 'Email';
$contactEmail = ($app['contact_preference'] ?? 'Both') !== 'Mobile';

// Additional info
$currentSalary = !empty($app['applicant_current_salary']) ? number_format((float)$app['applicant_current_salary'], 0) : null;
$languages = !empty($app['applicant_languages']) ? htmlspecialchars($app['applicant_languages']) : null;
$education = !empty($app['applicant_education']) ? htmlspecialchars($app['applicant_education']) : null;
$experience = !empty($app['experience_years']) ? (int)$app['experience_years'] : null;
$locality = !empty($app['applicant_locality']) ? htmlspecialchars($app['applicant_locality']) : null;
$role = !empty($app['job_title']) ? htmlspecialchars($app['job_title']) : 'Marketing';
$location = $locality ? $locality : (!empty($app['job_location']) ? htmlspecialchars($app['job_location']) : 'Chennai');

// Applied date
$appliedDate = !empty($app['applied_at']) ? strtotime($app['applied_at']) : time();
$appliedDateFormatted = date('d M Y', $appliedDate);

// Timeline status
$timelineStatus = !empty($app['timeline_status']) ? htmlspecialchars($app['timeline_status']) : 'You have not performed any action on this profile yet!';

// Is VIP
$isVip = !empty($app['is_vip']) ? (bool)$app['is_vip'] : false;
$applicationId = (int)$app['id'];
?>

<div class="applicant-card" data-application-id="<?php echo $applicationId; ?>">
    <div class="applicant-card-header">
        <div class="applicant-checkbox">
            <input type="checkbox" class="form-check-input applicant-select-checkbox" 
                   value="<?php echo $applicationId; ?>" 
                   id="app-<?php echo $applicationId; ?>">
            <label for="app-<?php echo $applicationId; ?>" class="visually-hidden">Select applicant</label>
        </div>
        
        <div class="applicant-avatar">
            <?php echo $applicantInitial; ?>
        </div>
        
        <div class="applicant-info">
            <div class="applicant-name-row">
                <h5 class="applicant-name">
                    <?php if ($isVip): ?>
                        <i class="fas fa-crown text-warning me-1" title="VIP Applicant"></i>
                    <?php endif; ?>
                    <?php echo $applicantEmail ? $applicantEmail : $applicantName; ?>
                </h5>
            </div>
            <div class="applicant-meta">
                <?php if ($applicantAge): ?>
                    <span><?php echo $applicantAge; ?> years</span>
                    <span class="separator">•</span>
                <?php endif; ?>
                <span><?php echo $role; ?></span>
                <?php if ($location): ?>
                    <span class="separator">•</span>
                    <span>from <?php echo $location; ?>, Chennai</span>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="applicant-actions-top">
            <button type="button" class="btn btn-sm btn-outline-primary send-sms-btn" 
                    data-phone="<?php echo htmlspecialchars($applicantPhone); ?>"
                    data-email="<?php echo $applicantEmail; ?>"
                    <?php echo !$contactMobile ? 'disabled title="Mobile contact not available"' : ''; ?>>
                <i class="fas fa-sms me-1"></i>Send SMS
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary send-email-btn" 
                    data-email="<?php echo $applicantEmail; ?>"
                    data-name="<?php echo $applicantName; ?>"
                    <?php echo !$contactEmail ? 'disabled title="Email contact not available"' : ''; ?>>
                <i class="fas fa-envelope me-1"></i>Send Email
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary view-contact-btn" 
                    data-application-id="<?php echo $applicationId; ?>">
                <i class="fas fa-address-card me-1"></i>View Contact
            </button>
        </div>
    </div>
    
    <div class="applicant-details">
        <div class="applied-date">
            <i class="fas fa-calendar-alt me-2"></i>Applied on <?php echo $appliedDateFormatted; ?>
        </div>
        
        <div class="contact-availability">
            <?php if ($contactMobile): ?>
                <span class="contact-badge available">
                    <i class="fas fa-mobile-alt"></i>Mobile
                </span>
            <?php endif; ?>
            <?php if ($contactEmail): ?>
                <span class="contact-badge available">
                    <i class="fas fa-envelope"></i>Email
                </span>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="applicant-info-details">
        <?php if ($currentSalary !== null): ?>
            <div class="info-item">
                <strong>Current salary per month:</strong> ₹<?php echo $currentSalary; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($languages): ?>
            <div class="info-item">
                <strong>Languages known:</strong> <?php echo $languages; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($education): ?>
            <div class="info-item">
                <strong>Education:</strong> <?php echo $education; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($experience !== null): ?>
            <div class="info-item">
                <strong>Experience:</strong> <?php echo $experience; ?> year<?php echo $experience !== 1 ? 's' : ''; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="applicant-timeline">
        <small class="text-muted">
            <strong>Your timeline for</strong> <?php echo $timelineStatus; ?>
        </small>
    </div>
    
    <div class="applicant-actions-bottom">
        <form method="POST" action="update-application-status-omr.php" class="d-inline" 
              onsubmit="return confirm('Mark this application as shortlisted?');">
            <input type="hidden" name="application_id" value="<?php echo $applicationId; ?>">
            <input type="hidden" name="status" value="shortlisted">
            <input type="hidden" name="job_id" value="<?php echo (int)$app['job_id']; ?>">
            <button type="submit" class="btn btn-sm btn-primary shortlist-btn">
                <i class="fas fa-star me-1"></i>Shortlist
            </button>
        </form>
        
        <form method="POST" action="update-application-status-omr.php" class="d-inline" 
              onsubmit="return confirm('Reject this application?');">
            <input type="hidden" name="application_id" value="<?php echo $applicationId; ?>">
            <input type="hidden" name="status" value="rejected">
            <input type="hidden" name="job_id" value="<?php echo (int)$app['job_id']; ?>">
            <button type="submit" class="btn btn-sm btn-outline-secondary reject-btn">
                <i class="fas fa-times me-1"></i>Reject
            </button>
        </form>
    </div>
</div>

