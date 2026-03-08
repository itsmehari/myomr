/**
 * Employer Dashboard JavaScript
 * Handles filtering, sorting, bulk actions, and dynamic updates
 * 
 * @package MyOMR Job Portal
 * @version 2.0.0
 */

(function() {
    'use strict';
    
    // State management
    const dashboardState = {
        selectedApplications: new Set(),
        currentFilters: {},
        isLoading: false
    };
    
    // Initialize dashboard when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeDashboard();
    });
    
    /**
     * Initialize dashboard functionality
     */
    function initializeDashboard() {
        setupJobSelector();
        setupFilterHandlers();
        setupBulkSelection();
        setupBulkActions();
        setupSorting();
        setupPagination();
        setupContactActions();
    }
    
    /**
     * Job selector change handler
     */
    function setupJobSelector() {
        const jobSelector = document.getElementById('job-selector');
        if (jobSelector) {
            jobSelector.addEventListener('change', function() {
                const jobId = this.value;
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('job_id', jobId);
                currentUrl.searchParams.set('page', '1'); // Reset to first page
                window.location.href = currentUrl.toString();
            });
        }
    }
    
    /**
     * Setup filter change handlers
     */
    function setupFilterHandlers() {
        // Status filter
        const statusFilter = document.getElementById('filter-status-select');
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                applyFilters();
            });
        }
        
        // Locality filter
        const localityCheckboxes = document.querySelectorAll('#locality-checkboxes input[type="checkbox"]');
        localityCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                applyFilters();
            });
        });
        
        // Locality search
        const localitySearch = document.getElementById('filter-locality-search');
        if (localitySearch) {
            localitySearch.addEventListener('input', function() {
                filterLocalityOptions(this.value);
            });
        }
        
        // Education filter
        const educationCheckboxes = document.querySelectorAll('#filter-education input[type="checkbox"]');
        educationCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                applyFilters();
            });
        });
        
        // Gender filter
        const genderCheckboxes = document.querySelectorAll('#filter-gender input[type="checkbox"]');
        genderCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                applyFilters();
            });
        });
        
        // Salary range filter
        const salaryMin = document.getElementById('salary-min');
        const salaryMax = document.getElementById('salary-max');
        if (salaryMin) {
            salaryMin.addEventListener('change', debounce(applyFilters, 500));
        }
        if (salaryMax) {
            salaryMax.addEventListener('change', debounce(applyFilters, 500));
        }
        
        // Experience filter
        const experienceMin = document.getElementById('experience-min');
        const experienceMax = document.getElementById('experience-max');
        if (experienceMin) {
            experienceMin.addEventListener('change', debounce(applyFilters, 500));
        }
        if (experienceMax) {
            experienceMax.addEventListener('change', debounce(applyFilters, 500));
        }
    }
    
    /**
     * Apply filters and reload results
     */
    function applyFilters() {
        if (dashboardState.isLoading) return;
        
        const currentUrl = new URL(window.location.href);
        
        // Get status filter
        const statusFilter = document.getElementById('filter-status-select');
        if (statusFilter && statusFilter.value) {
            currentUrl.searchParams.set('status', statusFilter.value);
        } else {
            currentUrl.searchParams.delete('status');
        }
        
        // Get locality filters
        const selectedLocalities = Array.from(document.querySelectorAll('#locality-checkboxes input[type="checkbox"]:checked'))
            .map(cb => cb.value);
        if (selectedLocalities.length > 0) {
            currentUrl.searchParams.set('locality', selectedLocalities.join(','));
        } else {
            currentUrl.searchParams.delete('locality');
        }
        
        // Get education filters
        const selectedEducation = Array.from(document.querySelectorAll('#filter-education input[type="checkbox"]:checked'))
            .map(cb => cb.value);
        if (selectedEducation.length > 0) {
            currentUrl.searchParams.set('education', selectedEducation.join(','));
        } else {
            currentUrl.searchParams.delete('education');
        }
        
        // Get gender filters
        const selectedGender = Array.from(document.querySelectorAll('#filter-gender input[type="checkbox"]:checked'))
            .map(cb => cb.value);
        if (selectedGender.length > 0) {
            currentUrl.searchParams.set('gender', selectedGender.join(','));
        } else {
            currentUrl.searchParams.delete('gender');
        }
        
        // Get salary range
        const salaryMin = document.getElementById('salary-min')?.value;
        const salaryMax = document.getElementById('salary-max')?.value;
        if (salaryMin) currentUrl.searchParams.set('salary_min', salaryMin);
        else currentUrl.searchParams.delete('salary_min');
        if (salaryMax) currentUrl.searchParams.set('salary_max', salaryMax);
        else currentUrl.searchParams.delete('salary_max');
        
        // Get experience range
        const experienceMin = document.getElementById('experience-min')?.value;
        const experienceMax = document.getElementById('experience-max')?.value;
        if (experienceMin) currentUrl.searchParams.set('experience_min', experienceMin);
        else currentUrl.searchParams.delete('experience_min');
        if (experienceMax) currentUrl.searchParams.set('experience_max', experienceMax);
        else currentUrl.searchParams.delete('experience_max');
        
        // Reset to first page when filtering
        currentUrl.searchParams.set('page', '1');
        
        // Reload page with new filters
        window.location.href = currentUrl.toString();
    }
    
    /**
     * Filter locality options by search term
     */
    function filterLocalityOptions(searchTerm) {
        const checkboxes = document.querySelectorAll('#locality-checkboxes .form-check');
        const term = searchTerm.toLowerCase().trim();
        
        checkboxes.forEach(function(formCheck) {
            const label = formCheck.querySelector('label');
            const text = label.textContent.toLowerCase();
            
            if (text.includes(term)) {
                formCheck.style.display = '';
            } else {
                formCheck.style.display = 'none';
            }
        });
    }
    
    /**
     * Setup bulk selection (checkboxes)
     */
    function setupBulkSelection() {
        // Individual checkboxes
        const checkboxes = document.querySelectorAll('.applicant-select-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateBulkSelection(this.value, this.checked);
            });
        });
        
        // Select all checkbox (if exists)
        const selectAllCheckbox = document.getElementById('select-all-applicants');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.applicant-select-checkbox');
                checkboxes.forEach(function(cb) {
                    cb.checked = selectAllCheckbox.checked;
                    updateBulkSelection(cb.value, cb.checked);
                });
            });
        }
    }
    
    /**
     * Update bulk selection state
     */
    function updateBulkSelection(applicationId, isSelected) {
        if (isSelected) {
            dashboardState.selectedApplications.add(applicationId);
        } else {
            dashboardState.selectedApplications.delete(applicationId);
        }
        
        updateBulkActionsBar();
    }
    
    /**
     * Update bulk actions bar visibility and count
     */
    function updateBulkActionsBar() {
        const bulkActionsBar = document.getElementById('bulk-actions-bar');
        const selectedCount = document.getElementById('selected-count');
        
        if (bulkActionsBar && selectedCount) {
            const count = dashboardState.selectedApplications.size;
            
            if (count > 0) {
                bulkActionsBar.classList.remove('d-none');
                selectedCount.textContent = count + ' selected';
            } else {
                bulkActionsBar.classList.add('d-none');
            }
        }
    }
    
    /**
     * Setup bulk actions (SMS, Email, Download)
     */
    function setupBulkActions() {
        const bulkSmsBtn = document.getElementById('bulk-sms-btn');
        const bulkEmailBtn = document.getElementById('bulk-email-btn');
        const bulkDownloadBtn = document.getElementById('bulk-download-btn');
        
        if (bulkSmsBtn) {
            bulkSmsBtn.addEventListener('click', function() {
                handleBulkSMS();
            });
        }
        
        if (bulkEmailBtn) {
            bulkEmailBtn.addEventListener('click', function() {
                handleBulkEmail();
            });
        }
        
        if (bulkDownloadBtn) {
            bulkDownloadBtn.addEventListener('click', function() {
                handleBulkDownload();
            });
        }
    }
    
    /**
     * Handle bulk SMS action
     */
    function handleBulkSMS() {
        const selectedIds = Array.from(dashboardState.selectedApplications);
        if (selectedIds.length === 0) {
            alert('Please select at least one applicant.');
            return;
        }
        
        if (confirm('Send SMS to ' + selectedIds.length + ' selected applicant(s)?')) {
            // TODO: Implement SMS API call
            alert('Bulk SMS feature will be available soon. Please use individual SMS buttons for now.');
        }
    }
    
    /**
     * Handle bulk Email action
     */
    function handleBulkEmail() {
        const selectedIds = Array.from(dashboardState.selectedApplications);
        if (selectedIds.length === 0) {
            alert('Please select at least one applicant.');
            return;
        }
        
        if (confirm('Send email to ' + selectedIds.length + ' selected applicant(s)?')) {
            // Redirect to bulk email page with selected IDs
            const params = new URLSearchParams();
            params.set('ids', selectedIds.join(','));
            window.location.href = 'bulk-email-applicants.php?' + params.toString();
        }
    }
    
    /**
     * Handle bulk Download action
     */
    function handleBulkDownload() {
        const selectedIds = Array.from(dashboardState.selectedApplications);
        if (selectedIds.length === 0) {
            alert('Please select at least one applicant.');
            return;
        }
        
        // Download CSV with selected applicants
        const params = new URLSearchParams();
        params.set('ids', selectedIds.join(','));
        params.set('format', 'csv');
        window.location.href = 'api/export-applicants.php?' + params.toString();
    }
    
    /**
     * Setup sorting controls
     */
    function setupSorting() {
        const sortOptions = document.querySelectorAll('input[name="sort-option"]');
        sortOptions.forEach(function(option) {
            option.addEventListener('change', function() {
                if (this.checked) {
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('sort', this.value);
                    currentUrl.searchParams.set('page', '1'); // Reset to first page
                    window.location.href = currentUrl.toString();
                }
            });
        });
        
        const resultsPerPage = document.getElementById('results-per-page');
        if (resultsPerPage) {
            resultsPerPage.addEventListener('change', function() {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('per_page', this.value);
                currentUrl.searchParams.set('page', '1'); // Reset to first page
                window.location.href = currentUrl.toString();
            });
        }
    }
    
    /**
     * Setup pagination (handled by server-side, just ensure links work)
     */
    function setupPagination() {
        // Pagination is handled by server-side rendering
        // This is a placeholder for any client-side pagination enhancements
    }
    
    /**
     * Setup contact action buttons (SMS, Email, View Contact)
     */
    function setupContactActions() {
        // SMS buttons
        document.querySelectorAll('.send-sms-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const phone = this.getAttribute('data-phone');
                const email = this.getAttribute('data-email');
                if (phone) {
                    // TODO: Implement SMS functionality
                    alert('SMS feature will be available soon. Phone: ' + phone);
                }
            });
        });
        
        // Email buttons
        document.querySelectorAll('.send-email-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const email = this.getAttribute('data-email');
                const name = this.getAttribute('data-name');
                if (email) {
                    window.location.href = 'mailto:' + encodeURIComponent(email) + 
                        '?subject=Regarding your job application';
                }
            });
        });
        
        // View Contact buttons
        document.querySelectorAll('.view-contact-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const applicationId = this.getAttribute('data-application-id');
                // Show contact details in modal or expand card
                showContactDetails(applicationId);
            });
        });
    }
    
    /**
     * Show contact details for an applicant
     */
    function showContactDetails(applicationId) {
        const card = document.querySelector('.applicant-card[data-application-id="' + applicationId + '"]');
        if (card) {
            // Toggle visibility of contact details
            // This is a placeholder - implement as needed
            alert('Contact details view will be available soon.');
        }
    }
    
    /**
     * Debounce function to limit function calls
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Export functions for external use if needed
    window.employerDashboard = {
        updateBulkSelection: updateBulkSelection,
        applyFilters: applyFilters
    };
    
})();

