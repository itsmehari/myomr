/**
 * Landing Page Analytics Tracking
 * Enhanced analytics tracking for landing pages
 */

(function() {
    'use strict';

    // Helper function to send Google Analytics events
    function gtagSend(eventName, eventParams) {
        try {
            if (typeof gtag === 'function') {
                gtag('event', eventName, eventParams || {});
            }
        } catch(e) {
            console.error('Analytics error:', e);
        }
    }

    // Track landing page view
    function trackLandingPageView() {
        const pageTitle = document.title;
        const pageUrl = window.location.href;
        const pagePath = window.location.pathname;
        
        // Determine page type
        let pageType = 'unknown';
        if (pagePath.includes('jobs-in-')) {
            pageType = 'location';
        } else if (pagePath.includes('it-jobs') || pagePath.includes('retail-jobs') || pagePath.includes('teaching-jobs') || pagePath.includes('healthcare-jobs') || pagePath.includes('hospitality-jobs')) {
            pageType = 'industry';
        } else if (pagePath.includes('fresher-jobs') || pagePath.includes('experienced-jobs')) {
            pageType = 'experience';
        } else if (pagePath.includes('part-time-jobs') || pagePath.includes('work-from-home')) {
            pageType = 'job-type';
        } else if (pagePath.includes('jobs-in-omr-chennai')) {
            pageType = 'primary';
        }
        
        gtagSend('landing_page_view', {
            page_title: pageTitle,
            page_location: pageUrl,
            page_path: pagePath,
            page_type: pageType
        });
    }

    // Track search form submissions
    function trackSearchFormSubmission() {
        const searchForms = document.querySelectorAll('form[action*="/omr-local-job-listings/"]');
        
        searchForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const searchInput = form.querySelector('input[name="search"]');
                const locationInput = form.querySelector('input[name="location"]');
                const categoryInput = form.querySelector('select[name="category"]');
                
                const searchTerm = searchInput ? searchInput.value : '';
                const location = locationInput ? locationInput.value : '';
                const category = categoryInput ? categoryInput.value : '';
                
                gtagSend('job_search_from_landing', {
                    search_term: searchTerm,
                    location: location,
                    category: category,
                    page_path: window.location.pathname
                });
            });
        });
    }

    // Track CTA button clicks
    function trackCTAClicks() {
        // Track "Search Jobs" / "View All Jobs" clicks
        const searchButtons = document.querySelectorAll('a[href*="/omr-local-job-listings/"], a[href*="jobs-in-"]');
        searchButtons.forEach(button => {
            button.addEventListener('click', function() {
                const buttonText = this.textContent.trim();
                const destination = this.href;
                
                gtagSend('landing_page_cta_click', {
                    cta_text: buttonText,
                    destination: destination,
                    page_path: window.location.pathname
                });
            });
        });

        // Track "Post a Job" clicks
        const postJobButtons = document.querySelectorAll('a[href*="post-job"], a[href*="employer-register"]');
        postJobButtons.forEach(button => {
            button.addEventListener('click', function() {
                gtagSend('employer_cta_click', {
                    cta_text: 'Post a Job',
                    destination: this.href,
                    page_path: window.location.pathname
                });
            });
        });
    }

    // Track job listing clicks
    function trackJobListingClicks() {
        const jobLinks = document.querySelectorAll('a[href*="job-detail-omr.php"]');
        
        jobLinks.forEach(link => {
            link.addEventListener('click', function() {
                const jobId = this.href.match(/id=(\d+)/);
                const jobTitle = this.closest('.featured-job-card, .job-card')?.querySelector('h5, h3')?.textContent?.trim() || '';
                
                gtagSend('job_listing_click', {
                    job_id: jobId ? jobId[1] : '',
                    job_title: jobTitle,
                    source: 'landing_page',
                    page_path: window.location.pathname
                });
            });
        });
    }

    // Track related page clicks
    function trackRelatedPageClicks() {
        const relatedLinks = document.querySelectorAll('.related-landing-pages a, .card-hover[href*="jobs-in-"], .card-hover[href*="it-jobs"], .card-hover[href*="fresher-jobs"], .card-hover[href*="part-time-jobs"]');
        
        relatedLinks.forEach(link => {
            link.addEventListener('click', function() {
                const relatedPage = this.href;
                const relatedTitle = this.querySelector('h5, .card-title')?.textContent?.trim() || '';
                
                gtagSend('related_page_click', {
                    related_page: relatedPage,
                    related_title: relatedTitle,
                    source_page: window.location.pathname
                });
            });
        });
    }

    // Track scroll depth
    function trackScrollDepth() {
        let scrollDepthTracked = {
            25: false,
            50: false,
            75: false,
            100: false
        };

        window.addEventListener('scroll', function() {
            const scrollPercent = Math.round((window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100);
            
            [25, 50, 75, 100].forEach(threshold => {
                if (scrollPercent >= threshold && !scrollDepthTracked[threshold]) {
                    scrollDepthTracked[threshold] = true;
                    gtagSend('scroll_depth', {
                        scroll_percent: threshold,
                        page_path: window.location.pathname
                    });
                }
            });
        });
    }

    // Track time on page
    function trackTimeOnPage() {
        const startTime = Date.now();
        
        // Track 30 seconds
        setTimeout(() => {
            gtagSend('time_on_page', {
                time_seconds: 30,
                page_path: window.location.pathname
            });
        }, 30000);
        
        // Track 60 seconds
        setTimeout(() => {
            gtagSend('time_on_page', {
                time_seconds: 60,
                page_path: window.location.pathname
            });
        }, 60000);
        
        // Track on page unload
        window.addEventListener('beforeunload', function() {
            const timeOnPage = Math.round((Date.now() - startTime) / 1000);
            gtagSend('page_exit', {
                time_on_page_seconds: timeOnPage,
                page_path: window.location.pathname
            });
        });
    }

    // Initialize all tracking
    function initAnalytics() {
        // Track page view
        trackLandingPageView();
        
        // Track interactions
        trackSearchFormSubmission();
        trackCTAClicks();
        trackJobListingClicks();
        trackRelatedPageClicks();
        
        // Track engagement
        trackScrollDepth();
        trackTimeOnPage();
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAnalytics);
    } else {
        initAnalytics();
    }

})();

