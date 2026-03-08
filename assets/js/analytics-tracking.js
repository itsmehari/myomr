/**
 * Google Analytics Event Tracking Helper
 * Usage: Call trackEvent() function with event details
 */

/**
 * Track a custom event in Google Analytics
 * @param {string} eventName - Name of the event (e.g., 'read_more_click', 'button_click')
 * @param {string} eventCategory - Category of the event (e.g., 'News', 'Job Portal', 'Button')
 * @param {string} eventLabel - Label for the event (e.g., article title, button name)
 * @param {number} eventValue - Optional numeric value associated with the event
 */
function trackEvent(eventName, eventCategory, eventLabel, eventValue) {
    // Check if gtag is available
    if (typeof gtag === 'function') {
        const eventData = {
            'event_category': eventCategory,
            'event_label': eventLabel
        };
        
        // Add value if provided
        if (eventValue !== undefined && eventValue !== null) {
            eventData['value'] = eventValue;
        }
        
        // Send event to Google Analytics
        gtag('event', eventName, eventData);
        
        // Optional: Log to console in development
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            console.log('GA Event Tracked:', {
                event_name: eventName,
                event_category: eventCategory,
                event_label: eventLabel,
                event_value: eventValue
            });
        }
    } else {
        console.warn('Google Analytics (gtag) is not loaded. Event not tracked:', eventName);
    }
}

/**
 * Track when user clicks "Read More" on a news article
 * @param {Event} event - The click event
 * @param {string} articleTitle - Title of the article
 * @param {string} articleSlug - Slug/URL of the article
 */
function trackNewsReadMore(event, articleTitle, articleSlug) {
    // Get the link element
    const linkElement = event.target.tagName === 'A' ? event.target : event.target.closest('a');
    const href = linkElement ? linkElement.getAttribute('href') : '/local-news/article.php?slug=' + encodeURIComponent(articleSlug);
    
    // Prevent default navigation temporarily to ensure event is sent
    if (event) {
        event.preventDefault();
    }
    
    // Track the event
    trackEvent(
        'read_more_click',
        'News',
        articleTitle,
        null
    );
    
    // Track with additional details (optional - for more granular tracking)
    if (typeof gtag === 'function') {
        gtag('event', 'article_read_more', {
            'event_category': 'News',
            'event_label': articleTitle,
            'article_slug': articleSlug,
            'article_url': href
        });
    }
    
    // Navigate to the article after a brief delay to ensure event is sent
    setTimeout(function() {
        window.location.href = href;
    }, 100);
}

/**
 * Track button clicks generically
 * @param {string} buttonName - Name/ID of the button
 * @param {string} buttonLocation - Where the button is located (e.g., 'homepage', 'job_listing')
 */
function trackButtonClick(buttonName, buttonLocation) {
    trackEvent(
        'button_click',
        'Button',
        buttonName + ' - ' + buttonLocation,
        null
    );
}

/**
 * Track form submissions
 * @param {string} formName - Name/ID of the form
 * @param {string} formType - Type of form (e.g., 'contact', 'job_application', 'newsletter')
 */
function trackFormSubmit(formName, formType) {
    trackEvent(
        'form_submit',
        'Form',
        formName + ' - ' + formType,
        null
    );
}

/**
 * Track external link clicks
 * @param {string} linkUrl - URL of the external link
 * @param {string} linkText - Text/anchor of the link
 */
function trackExternalLink(linkUrl, linkText) {
    trackEvent(
        'external_link_click',
        'External Link',
        linkText + ' - ' + linkUrl,
        null
    );
}

/**
 * Track search queries
 * @param {string} searchQuery - The search term
 * @param {string} searchType - Type of search (e.g., 'job', 'news', 'business')
 */
function trackSearch(searchQuery, searchType) {
    trackEvent(
        'search',
        'Search',
        searchQuery + ' - ' + searchType,
        null
    );
}

/**
 * Track file downloads
 * @param {string} fileName - Name of the file
 * @param {string} fileType - Type of file (e.g., 'pdf', 'image', 'document')
 */
function trackDownload(fileName, fileType) {
    trackEvent(
        'file_download',
        'Download',
        fileName + ' - ' + fileType,
        null
    );
}

/**
 * Track video plays
 * @param {string} videoTitle - Title of the video
 * @param {string} videoUrl - URL of the video
 */
function trackVideoPlay(videoTitle, videoUrl) {
    trackEvent(
        'video_play',
        'Video',
        videoTitle,
        null
    );
}

/**
 * Track job-related events
 * @param {string} action - Action taken (e.g., 'apply', 'view', 'save')
 * @param {string} jobTitle - Title of the job
 * @param {string} jobId - ID of the job
 */
function trackJobAction(action, jobTitle, jobId) {
    trackEvent(
        'job_' + action,
        'Job Portal',
        jobTitle + ' (ID: ' + jobId + ')',
        null
    );
}

/**
 * Track employer actions
 * @param {string} action - Action taken (e.g., 'post_job', 'edit_job', 'view_applications')
 * @param {string} employerEmail - Email of the employer
 */
function trackEmployerAction(action, employerEmail) {
    trackEvent(
        'employer_' + action,
        'Job Portal - Employer',
        employerEmail,
        null
    );
}

