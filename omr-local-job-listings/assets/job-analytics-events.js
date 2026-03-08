/**
 * Job Portal - Enhanced Google Analytics Event Tracking
 * Track important user interactions
 */

// Track job application submission
function trackJobApplication(jobId, jobTitle, companyName) {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'job_application', {
            'event_category': 'Job Portal',
            'event_label': jobTitle,
            'job_id': jobId,
            'company_name': companyName,
            'value': 1
        });
    }
}

// Track job posting submission
function trackJobPosting(jobTitle, category, jobType) {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'job_posted', {
            'event_category': 'Employer',
            'event_label': jobTitle,
            'job_category': category,
            'job_type': jobType,
            'value': 1
        });
    }
}

// Track job search
function trackJobSearch(searchTerm, filters) {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'search', {
            'search_term': searchTerm,
            'event_category': 'Job Search',
            'filters': JSON.stringify(filters)
        });
    }
}

// Track job view
function trackJobView(jobId, jobTitle, companyName) {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'view_item', {
            'items': [{
                'id': jobId,
                'name': jobTitle,
                'category': 'Job',
                'brand': companyName
            }]
        });
    }
}

// Track filter usage
function trackFilterUsage(filterType, filterValue) {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'filter', {
            'event_category': 'Job Search',
            'event_label': filterType,
            'filter_value': filterValue
        });
    }
}

// Track share action
function trackShareAction(platform, jobId, jobTitle) {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'share', {
            'method': platform,
            'content_type': 'job',
            'item_id': jobId,
            'content_name': jobTitle
        });
    }
}

// Auto-track page views for job detail pages
document.addEventListener('DOMContentLoaded', function() {
    // Track job views on job detail page
    const jobId = document.querySelector('[itemprop="jobPosting"]')?.getAttribute('data-job-id');
    const jobTitle = document.querySelector('h1')?.textContent;
    const companyName = document.querySelector('[itemprop="hiringOrganization"]')?.textContent;
    
    if (jobId && jobTitle) {
        trackJobView(jobId, jobTitle, companyName || '');
    }
    
    // Track search form submissions
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            const searchTerm = document.getElementById('search')?.value || '';
            const category = document.getElementById('category')?.value || '';
            const location = document.getElementById('location')?.value || '';
            
            trackJobSearch(searchTerm, {
                category: category,
                location: location
            });
        });
    }
    
    // Track application form submissions
    const applyForm = document.getElementById('applyForm');
    if (applyForm) {
        applyForm.addEventListener('submit', function() {
            const jobId = document.querySelector('input[name="job_id"]')?.value;
            const jobTitle = document.querySelector('.modal-title')?.textContent.replace('Apply for ', '');
            
            if (jobId && jobTitle) {
                trackJobApplication(jobId, jobTitle, '');
            }
        });
    }
    
    // Track job posting form submissions
    const postJobForm = document.getElementById('post-job-form');
    if (postJobForm) {
        postJobForm.addEventListener('submit', function() {
            const jobTitle = document.getElementById('title')?.value || '';
            const category = document.getElementById('category')?.value || '';
            const jobType = document.getElementById('job_type')?.value || '';
            
            trackJobPosting(jobTitle, category, jobType);
        });
    }
});

