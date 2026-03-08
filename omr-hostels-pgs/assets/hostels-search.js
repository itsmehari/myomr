/**
 * Hostels & PGs Search and Filter Functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Grid/List view toggle
    const viewButtons = document.querySelectorAll('[data-view]');
    const container = document.getElementById('properties-container');
    
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
                
                gtag('event', 'property_search_submit', {
                    'event_category': 'Hostels_PGs',
                    'event_label': searchTerm || locality || 'all',
                    'search_term': searchTerm || '',
                    'locality': locality || 'all'
                });
            }
        });
    }
    
    // Track property card clicks
    const propertyCards = document.querySelectorAll('.property-card');
    propertyCards.forEach((card, index) => {
        const link = card.querySelector('a.property-card-title');
        if (link) {
            link.addEventListener('click', function() {
                const propertyName = this.textContent.trim();
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'property_view', {
                        'event_category': 'Hostels_PGs',
                        'event_label': propertyName,
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
    #properties-container.list-view .col-lg-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    #properties-container.list-view .property-card {
        display: flex;
        flex-direction: row;
    }
    #properties-container.list-view .property-card-image-wrapper {
        width: 300px;
        flex-shrink: 0;
    }
    #properties-container.list-view .property-card-image {
        height: 100%;
        object-fit: cover;
    }
    @media (max-width: 768px) {
        #properties-container.list-view .property-card {
            flex-direction: column;
        }
        #properties-container.list-view .property-card-image-wrapper {
            width: 100%;
        }
    }
`;
document.head.appendChild(style);

