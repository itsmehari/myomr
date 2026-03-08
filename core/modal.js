// Scroll detection variables
let isScrolling = false;
let scrollTimeout = null;
let lastScrollTop = 0;
let scrollVelocity = 0;
let modalTimeout = null;

// Track scrolling activity
function trackScrolling() {
    const currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const scrollDelta = Math.abs(currentScrollTop - lastScrollTop);
    
    // Calculate scroll velocity (pixels per frame)
    scrollVelocity = scrollDelta;
    
    // Mark as scrolling
    isScrolling = true;
    lastScrollTop = currentScrollTop;
    
    // Clear existing timeout
    if (scrollTimeout) {
        clearTimeout(scrollTimeout);
    }
    
    // Reset scrolling flag after scroll stops (with delay for momentum scrolling)
    scrollTimeout = setTimeout(function() {
        isScrolling = false;
        scrollVelocity = 0;
    }, 150); // Delay accounts for momentum scrolling on mobile
}

// Initialize scroll tracking
let scrollTrackingInitialized = false;
function initScrollTracking() {
    if (scrollTrackingInitialized) return;
    scrollTrackingInitialized = true;
    
    lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    // Track scroll events
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                trackScrolling();
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
    
    // Also track wheel events for fast scrolling (mouse wheel)
    let wheelTimeout = null;
    window.addEventListener('wheel', function(e) {
        // Detect fast scrolling
        if (Math.abs(e.deltaY) > 50) {
            scrollVelocity = Math.abs(e.deltaY);
            isScrolling = true;
            
            if (wheelTimeout) {
                clearTimeout(wheelTimeout);
            }
            
            wheelTimeout = setTimeout(function() {
                scrollVelocity = 0;
                isScrolling = false;
            }, 300);
        }
    }, { passive: true });
    
    // Also track touch events for better mobile support
    let touchStartY = 0;
    document.addEventListener('touchstart', function(e) {
        touchStartY = e.touches[0].clientY;
    }, { passive: true });
    
    document.addEventListener('touchmove', function(e) {
        const touchY = e.touches[0].clientY;
        const touchDelta = Math.abs(touchY - touchStartY);
        if (touchDelta > 10) {
            isScrolling = true;
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            scrollTimeout = setTimeout(function() {
                isScrolling = false;
            }, 200); // Longer delay for touch momentum
        }
    }, { passive: true });
}

function showModal() {
    const modalOverlay = document.getElementById("modalOverlay");
    if (modalOverlay && !isScrolling && scrollVelocity < 50) {
        // Get current scroll position
        const scrollY = window.scrollY || document.documentElement.scrollTop;
        
        // Add active class
        modalOverlay.classList.add('active');
        
        // Update ARIA attributes for accessibility
        modalOverlay.setAttribute('aria-hidden', 'false');
        
        // Prevent background scrolling - better cross-browser support
        document.body.classList.add('modal-open');
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollY}px`;
        document.body.style.width = '100%';
        
        // Store scroll position for restoration
        document.body.setAttribute('data-scroll-y', scrollY);
        
        // Focus trap - focus on modal for accessibility
        const firstFocusable = modalOverlay.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (firstFocusable) {
            firstFocusable.focus();
        }
    } else if (isScrolling || scrollVelocity >= 50) {
        // User is scrolling, delay modal appearance
        if (modalTimeout) {
            clearTimeout(modalTimeout);
        }
        modalTimeout = setTimeout(function() {
            // Check again after scroll stops
            if (!isScrolling && scrollVelocity < 50) {
                showModal();
            }
        }, 500);
    }
}

function closeModal() {
    const modalOverlay = document.getElementById("modalOverlay");
    if (modalOverlay) {
        // Remove active class
        modalOverlay.classList.remove('active');
        
        // Update ARIA attributes for accessibility
        modalOverlay.setAttribute('aria-hidden', 'true');
        
        // Restore scrolling - better cross-browser support
        const scrollY = document.body.getAttribute('data-scroll-y') || '0';
        document.body.classList.remove('modal-open');
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        
        // Restore scroll position
        window.scrollTo(0, parseInt(scrollY, 10));
        
        // Clean up
        document.body.removeAttribute('data-scroll-y');
    }
}

// Show modal after delay (only if user hasn't seen it before and not scrolling)
// Use DOMContentLoaded for better compatibility, fallback to load
function initModal() {
    try {
        // Initialize scroll tracking first
        initScrollTracking();
        
        const hasSeenModal = sessionStorage.getItem('myomr_modal_seen');
        if (!hasSeenModal) {
            // Wait for initial scroll to settle
            setTimeout(function() {
                // Check if user is still scrolling
                if (!isScrolling && scrollVelocity < 50) {
                    const modalOverlay = document.getElementById("modalOverlay");
                    if (modalOverlay) {
                        showModal();
                        sessionStorage.setItem('myomr_modal_seen', 'true');
                    }
                } else {
                    // User is scrolling, wait for it to stop
                    const checkScroll = setInterval(function() {
                        if (!isScrolling && scrollVelocity < 50) {
                            clearInterval(checkScroll);
                            const modalOverlay = document.getElementById("modalOverlay");
                            if (modalOverlay) {
                                showModal();
                                sessionStorage.setItem('myomr_modal_seen', 'true');
                            }
                        }
                    }, 200);
                    
                    // Timeout after 10 seconds to prevent infinite waiting
                    setTimeout(function() {
                        clearInterval(checkScroll);
                        const modalOverlay = document.getElementById("modalOverlay");
                        if (modalOverlay && !modalOverlay.classList.contains('active')) {
                            showModal();
                            sessionStorage.setItem('myomr_modal_seen', 'true');
                        }
                    }, 10000);
                }
            }, 3000);
        }
    } catch (e) {
        // Fallback if sessionStorage is not available
        console.warn('Modal initialization skipped:', e);
    }
}

// Support both modern and older browsers
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initModal);
} else {
    // DOM already loaded
    window.addEventListener('load', initModal);
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modalOverlay = document.getElementById('modalOverlay');
    if (modalOverlay && e.target === modalOverlay && modalOverlay.classList.contains('active')) {
        closeModal();
    }
}, true);

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    const modalOverlay = document.getElementById('modalOverlay');
    if (modalOverlay && modalOverlay.classList.contains('active')) {
        // Support both 'Escape' and 'Esc' for better compatibility
        if (e.key === 'Escape' || e.key === 'Esc' || e.keyCode === 27) {
            e.preventDefault();
            closeModal();
        }
    }
});

// Prevent modal from showing if user starts scrolling after page load
window.addEventListener('scroll', function() {
    // If modal hasn't shown yet and user is scrolling, cancel any pending modal
    const modalOverlay = document.getElementById('modalOverlay');
    if (modalOverlay && !modalOverlay.classList.contains('active') && modalTimeout) {
        // Don't cancel immediately, but check scroll velocity
        if (scrollVelocity > 100) {
            // User is scrolling fast, cancel modal
            clearTimeout(modalTimeout);
            modalTimeout = null;
        }
    }
}, { passive: true });
