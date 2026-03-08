/**
 * Coworking Spaces Search and Filter Functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Grid/List view toggle
    const viewButtons = document.querySelectorAll('[data-view]');
    const container = document.getElementById('spaces-container');
    
    if (viewButtons.length > 0 && container) {
        viewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const view = this.getAttribute('data-view');
                
                // Update active state
                viewButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Update container class
                if (view === 'list') {
                    container.classList.add('list-view');
                    container.classList.remove('grid-view');
                } else {
                    container.classList.add('grid-view');
                    container.classList.remove('list-view');
                }
            });
        });
    }
    
    // Add analytics tracking for searches
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            if (typeof gtag !== 'undefined') {
                const searchTerm = this.querySelector('[name="search"]')?.value;
                const locality = this.querySelector('[name="locality"]')?.value;
                
                gtag('event', 'space_search_submit', {
                    'event_category': 'Coworking_Spaces',
                    'event_label': searchTerm || locality || 'all',
                    'search_term': searchTerm || '',
                    'locality': locality || 'all'
                });
            }
        });
    }
    
    // Track space card clicks
    const spaceCards = document.querySelectorAll('.space-card');
    spaceCards.forEach((card, index) => {
        const link = card.querySelector('a.space-card-title');
        if (link) {
            link.addEventListener('click', function() {
                const spaceName = this.textContent.trim();
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'space_view', {
                        'event_category': 'Coworking_Spaces',
                        'event_label': spaceName,
                        'position': index + 1
                    });
                }
            });
        }
    });
});

// List view specific styles
const style = document.createElement('style');
style.textContent = `
    #spaces-container.list-view .col-lg-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    #spaces-container.list-view .space-card {
        display: flex;
        flex-direction: row;
    }
    #spaces-container.list-view .space-card-image-wrapper {
        width: 300px;
        flex-shrink: 0;
    }
    #spaces-container.list-view .space-card-image {
        height: 100%;
        object-fit: cover;
    }
    @media (max-width: 768px) {
        #spaces-container.list-view .space-card {
            flex-direction: column;
        }
        #spaces-container.list-view .space-card-image-wrapper {
            width: 100%;
        }
    }
`;
document.head.appendChild(style);

