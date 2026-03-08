/**
 * UN SDG Floating Badge JavaScript
 * Handles tooltip interactions, accessibility, and analytics tracking
 */

(function() {
    'use strict';

    // SDG names and descriptions
    const sdgInfo = {
        1: { name: 'No Poverty', desc: 'End poverty in all its forms everywhere' },
        2: { name: 'Zero Hunger', desc: 'End hunger, achieve food security and improved nutrition' },
        3: { name: 'Good Health & Well-being', desc: 'Ensure healthy lives and promote well-being for all' },
        4: { name: 'Quality Education', desc: 'Ensure inclusive and equitable quality education' },
        5: { name: 'Gender Equality', desc: 'Achieve gender equality and empower all women and girls' },
        6: { name: 'Clean Water & Sanitation', desc: 'Ensure availability and sustainable management of water' },
        7: { name: 'Affordable & Clean Energy', desc: 'Ensure access to affordable, reliable, sustainable energy' },
        8: { name: 'Decent Work & Economic Growth', desc: 'Promote sustained economic growth and employment' },
        9: { name: 'Industry, Innovation & Infrastructure', desc: 'Build resilient infrastructure and promote innovation' },
        10: { name: 'Reduced Inequalities', desc: 'Reduce inequality within and among countries' },
        11: { name: 'Sustainable Cities & Communities', desc: 'Make cities and human settlements inclusive, safe, and sustainable' },
        12: { name: 'Responsible Consumption & Production', desc: 'Ensure sustainable consumption and production patterns' },
        13: { name: 'Climate Action', desc: 'Take urgent action to combat climate change' },
        14: { name: 'Life Below Water', desc: 'Conserve and sustainably use marine resources' },
        15: { name: 'Life on Land', desc: 'Protect and restore terrestrial ecosystems' },
        16: { name: 'Peace, Justice & Strong Institutions', desc: 'Promote peaceful and inclusive societies' },
        17: { name: 'Partnerships for the Goals', desc: 'Strengthen implementation and partnerships' }
    };

    /**
     * Initialize SDG badges
     */
    function initSDGBadges() {
        const badges = document.querySelectorAll('.sdg-badge');
        
        badges.forEach(function(badge) {
            const sdgNumber = extractSDGNumber(badge);
            
            if (sdgNumber && sdgInfo[sdgNumber]) {
                // Add ARIA label for accessibility
                badge.setAttribute('aria-label', `SDG ${sdgNumber}: ${sdgInfo[sdgNumber].name}`);
                
                // Add tooltip
                addTooltip(badge, sdgNumber);
                
                // Track click analytics
                badge.addEventListener('click', function(e) {
                    trackSDGClick(sdgNumber);
                });
            }
        });
    }

    /**
     * Extract SDG number from badge class or data attribute
     */
    function extractSDGNumber(badge) {
        // Try data-sdg attribute first
        if (badge.dataset.sdg) {
            return parseInt(badge.dataset.sdg);
        }
        
        // Try class name (e.g., "sdg-11")
        const classMatch = badge.className.match(/sdg-(\d+)/);
        if (classMatch) {
            return parseInt(classMatch[1]);
        }
        
        // Try text content (e.g., badge text is "11")
        const textMatch = badge.textContent.match(/(\d+)/);
        if (textMatch) {
            return parseInt(textMatch[1]);
        }
        
        return null;
    }

    /**
     * Add tooltip to badge
     */
    function addTooltip(badge, sdgNumber) {
        const info = sdgInfo[sdgNumber];
        if (!info) return;
        
        const tooltip = document.createElement('div');
        tooltip.className = 'sdg-tooltip';
        tooltip.setAttribute('role', 'tooltip');
        tooltip.setAttribute('aria-hidden', 'true');
        
        const title = document.createElement('span');
        title.className = 'sdg-tooltip-title';
        title.textContent = `SDG ${sdgNumber}: ${info.name}`;
        
        const desc = document.createElement('span');
        desc.className = 'sdg-tooltip-desc';
        desc.textContent = info.desc;
        
        const link = document.createElement('a');
        link.className = 'sdg-tooltip-link';
        link.href = '/discover-myomr/sustainable-development-goals.php';
        link.textContent = 'Learn More';
        link.setAttribute('aria-label', 'Learn more about SDGs');
        
        tooltip.appendChild(title);
        tooltip.appendChild(desc);
        tooltip.appendChild(link);
        
        badge.appendChild(tooltip);
        
        // Update aria-describedby for accessibility
        const tooltipId = 'sdg-tooltip-' + sdgNumber + '-' + Math.random().toString(36).substr(2, 9);
        tooltip.id = tooltipId;
        badge.setAttribute('aria-describedby', tooltipId);
    }

    /**
     * Track SDG badge click for analytics
     */
    function trackSDGClick(sdgNumber) {
        // Google Analytics 4 event tracking
        if (typeof gtag !== 'undefined') {
            gtag('event', 'sdg_badge_click', {
                'event_category': 'SDG Engagement',
                'event_label': 'SDG ' + sdgNumber,
                'value': sdgNumber
            });
        }
        
        // Universal Analytics (legacy)
        if (typeof ga !== 'undefined') {
            ga('send', 'event', 'SDG Engagement', 'SDG Badge Click', 'SDG ' + sdgNumber);
        }
        
        // Console log for debugging (remove in production if needed)
        console.log('SDG Badge Clicked: SDG ' + sdgNumber);
    }

    /**
     * Handle keyboard navigation
     */
    function handleKeyboardNavigation() {
        document.addEventListener('keydown', function(e) {
            const badges = document.querySelectorAll('.sdg-badge');
            const currentIndex = Array.from(badges).findIndex(b => b === document.activeElement);
            
            if (currentIndex === -1) return;
            
            let nextIndex = -1;
            
            // Arrow keys for navigation
            if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                e.preventDefault();
                nextIndex = currentIndex > 0 ? currentIndex - 1 : badges.length - 1;
            } else if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                e.preventDefault();
                nextIndex = currentIndex < badges.length - 1 ? currentIndex + 1 : 0;
            }
            
            if (nextIndex !== -1 && badges[nextIndex]) {
                badges[nextIndex].focus();
            }
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initSDGBadges();
            handleKeyboardNavigation();
        });
    } else {
        initSDGBadges();
        handleKeyboardNavigation();
    }

})();
