/**
 * Pentahive Landing Page - Base JavaScript
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        // Smooth scroll for anchor links
        initSmoothScroll();
        
        // Form validation enhancement
        initFormValidation();
        
        // Track scroll depth
        initScrollTracking();
        
        // Track time on page
        initTimeTracking();
    }

    // Smooth scroll for anchor links
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#' || href === '#!') return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    const offsetTop = target.offsetTop - 80; // Account for fixed nav
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // Form validation enhancement
    function initFormValidation() {
        const form = document.querySelector('.contact-form');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            const email = form.querySelector('#email');
            const phone = form.querySelector('#phone');
            const website = form.querySelector('#website');
            
            // Email validation
            if (email && email.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email.value)) {
                    e.preventDefault();
                    email.classList.add('is-invalid');
                    alert('Please enter a valid email address.');
                    return false;
                }
            }
            
            // Phone validation (basic)
            if (phone && phone.value) {
                const phoneRegex = /^[0-9]{10}$/;
                const cleanPhone = phone.value.replace(/\D/g, '');
                if (cleanPhone.length < 10) {
                    e.preventDefault();
                    phone.classList.add('is-invalid');
                    alert('Please enter a valid 10-digit phone number.');
                    return false;
                }
            }
            
            // Website URL validation
            if (website && website.value) {
                try {
                    new URL(website.value);
                } catch (e) {
                    e.preventDefault();
                    website.classList.add('is-invalid');
                    alert('Please enter a valid website URL (e.g., https://example.com)');
                    return false;
                }
            }
        });

        // Remove invalid class on input
        form.querySelectorAll('input, textarea').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
    }

    // Scroll depth tracking
    function initScrollTracking() {
        let scrollDepthTracked = {
            25: false,
            50: false,
            75: false,
            100: false
        };

        window.addEventListener('scroll', function() {
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollPercent = Math.round((scrollTop / (documentHeight - windowHeight)) * 100);
            
            [25, 50, 75, 100].forEach(threshold => {
                if (scrollPercent >= threshold && !scrollDepthTracked[threshold]) {
                    scrollDepthTracked[threshold] = true;
                    trackEvent('scroll_depth', {
                        scroll_percent: threshold,
                        page_path: window.location.pathname
                    });
                }
            });
        });
    }

    // Time on page tracking
    function initTimeTracking() {
        const startTime = Date.now();
        
        // Track 30 seconds
        setTimeout(() => {
            trackEvent('time_on_page', {
                time_seconds: 30,
                page_path: window.location.pathname
            });
        }, 30000);
        
        // Track 60 seconds
        setTimeout(() => {
            trackEvent('time_on_page', {
                time_seconds: 60,
                page_path: window.location.pathname
            });
        }, 60000);
        
        // Track on page unload
        window.addEventListener('beforeunload', function() {
            const timeOnPage = Math.round((Date.now() - startTime) / 1000);
            trackEvent('page_exit', {
                time_on_page_seconds: timeOnPage,
                page_path: window.location.pathname
            });
        });
    }

    // Helper function to track events
    function trackEvent(eventName, eventParams) {
        try {
            if (typeof gtag === 'function') {
                gtag('event', eventName, eventParams || {});
            }
        } catch(e) {
            console.error('Analytics error:', e);
        }
    }

})();

