/**
 * MyOMR Job Portal - JavaScript Functions
 * 
 * @package MyOMR Job Portal
 * @version 1.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize all features
    initSearchForm();
    initSortButtons();
    initJobCards();
    initAccessibility();
    initPerformanceOptimizations();
    
});

/**
 * Initialize search form functionality
 */
function initSearchForm() {
    const searchForm = document.querySelector('.search-form');
    const searchInput = document.getElementById('search');
    const locationInput = document.getElementById('location');
    const categorySelect = document.getElementById('category');
    
    if (!searchForm) return;
    
    // Auto-submit on category change
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            searchForm.submit();
        });
    }
    
    // Add search suggestions (basic implementation)
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                // Could implement AJAX search suggestions here
                console.log('Search term:', searchInput.value);
            }, 300);
        });
        
        // Focus management for accessibility
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });
    }
    
    // Location autocomplete suggestions
    if (locationInput) {
        const commonLocations = [
            'Sholinganallur', 'Thoraipakkam', 'Perungudi', 'Siruseri',
            'Kelambakkam', 'OMR', 'Chennai', 'Tamil Nadu'
        ];
        
        locationInput.addEventListener('input', function() {
            const value = this.value.toLowerCase();
            const suggestions = commonLocations.filter(loc => 
                loc.toLowerCase().includes(value)
            );
            
            // Could implement dropdown suggestions here
            console.log('Location suggestions:', suggestions);
        });
    }
}

/**
 * Initialize sort buttons functionality
 */
function initSortButtons() {
    const sortButtons = document.querySelectorAll('[data-sort]');
    
    sortButtons.forEach(button => {
        button.addEventListener('click', function() {
            const sortType = this.getAttribute('data-sort');
            
            // Remove active class from all buttons
            sortButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Sort job cards
            sortJobCards(sortType);
            
            // Announce to screen readers
            announceToScreenReader(`Jobs sorted by ${sortType}`);
        });
    });
}

/**
 * Sort job cards based on criteria
 */
function sortJobCards(sortType) {
    const container = document.getElementById('job-cards-container');
    if (!container) return;
    
    const jobCards = Array.from(container.children);
    
    jobCards.sort((a, b) => {
        switch (sortType) {
            case 'newest':
                const dateA = new Date(a.querySelector('[itemprop="datePosted"]')?.getAttribute('content') || 0);
                const dateB = new Date(b.querySelector('[itemprop="datePosted"]')?.getAttribute('content') || 0);
                return dateB - dateA;
                
            case 'featured':
                const featuredA = a.querySelector('.badge.bg-warning') ? 1 : 0;
                const featuredB = b.querySelector('.badge.bg-warning') ? 1 : 0;
                return featuredB - featuredA;
                
            default:
                return 0;
        }
    });
    
    // Clear container and re-append sorted cards
    container.innerHTML = '';
    jobCards.forEach(card => container.appendChild(card));
    
    // Add animation
    jobCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.3s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 50);
    });
}

/**
 * Initialize job card interactions
 */
function initJobCards() {
    const jobCards = document.querySelectorAll('.job-card');
    
    jobCards.forEach(card => {
        // Add hover effects
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
        
        // Add click tracking (for analytics)
        const viewButton = card.querySelector('.btn-primary');
        if (viewButton) {
            viewButton.addEventListener('click', function() {
                const jobTitle = card.querySelector('h3 a').textContent;
                const companyName = card.querySelector('.company-info .text-primary').textContent;
                
                // Track job view (could send to analytics)
                console.log('Job viewed:', {
                    title: jobTitle,
                    company: companyName,
                    timestamp: new Date().toISOString()
                });
            });
        }
        
        // Add keyboard navigation
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const link = this.querySelector('h3 a');
                if (link) {
                    link.click();
                }
            }
        });
        
        // Make cards focusable
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'button');
        card.setAttribute('aria-label', 'View job details');
    });
}

/**
 * Initialize accessibility features
 */
function initAccessibility() {
    // Add skip link functionality
    const skipLink = document.querySelector('.skip-link');
    if (skipLink) {
        skipLink.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.focus();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
    
    // Add ARIA live region for announcements
    if (!document.getElementById('aria-live-region')) {
        const liveRegion = document.createElement('div');
        liveRegion.id = 'aria-live-region';
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.setAttribute('aria-atomic', 'true');
        liveRegion.style.position = 'absolute';
        liveRegion.style.left = '-10000px';
        liveRegion.style.width = '1px';
        liveRegion.style.height = '1px';
        liveRegion.style.overflow = 'hidden';
        document.body.appendChild(liveRegion);
    }
    
    // Add focus indicators for keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            document.body.classList.add('keyboard-navigation');
        }
    });
    
    document.addEventListener('mousedown', function() {
        document.body.classList.remove('keyboard-navigation');
    });
    
    // Add reduced motion support
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.documentElement.style.setProperty('--animation-duration', '0s');
    }
}

/**
 * Announce messages to screen readers
 */
function announceToScreenReader(message) {
    const liveRegion = document.getElementById('aria-live-region');
    if (liveRegion) {
        liveRegion.textContent = message;
        setTimeout(() => {
            liveRegion.textContent = '';
        }, 1000);
    }
}

/**
 * Initialize performance optimizations
 */
function initPerformanceOptimizations() {
    // Lazy load images (if any)
    const images = document.querySelectorAll('img[data-src]');
    if (images.length > 0) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
    
    // Preload critical resources
    const criticalLinks = [
        '/omr-local-job-listings/assets/job-listings-omr.css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'
    ];
    
    criticalLinks.forEach(href => {
        const link = document.createElement('link');
        link.rel = 'preload';
        link.as = 'style';
        link.href = href;
        document.head.appendChild(link);
    });
    
    // Add loading states for form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
                
                // Re-enable after 3 seconds (fallback)
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-search me-1"></i> Search';
                }, 3000);
            }
        });
    });
}

/**
 * Utility function to debounce function calls
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

/**
 * Utility function to throttle function calls
 */
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

/**
 * Handle window resize events
 */
window.addEventListener('resize', throttle(function() {
    // Recalculate layout if needed
    console.log('Window resized');
}, 250));

/**
 * Handle page visibility changes
 */
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        console.log('Page hidden - pausing animations');
    } else {
        console.log('Page visible - resuming animations');
    }
});

/**
 * Error handling
 */
window.addEventListener('error', function(e) {
    console.error('JavaScript error:', e.error);
    // Could send error to analytics service
});

/**
 * Export functions for testing
 */
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initSearchForm,
        initSortButtons,
        sortJobCards,
        announceToScreenReader,
        debounce,
        throttle
    };
}
