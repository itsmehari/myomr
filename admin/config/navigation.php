<?php
/**
 * Unified navigation registry for MyOMR admin.
 * Structure powers the sidebar, dashboard index, and future menu builders.
 */

return [
    [
        'key' => 'dashboard_content',
        'label' => 'Dashboard & Content',
        'icon' => 'fa-gauge-high',
        'modules' => [
            [
                'key' => 'module_picker',
                'name' => 'Module Picker',
                'description' => 'Browse all admin modules by category. Entry point for quick navigation.',
                'path' => '/admin/index.php',
                'icon' => 'fa-th-large',
                'tags' => ['dashboard', 'navigation', 'modules'],
            ],
            [
                'key' => 'dashboard',
                'name' => 'Admin Overview',
                'description' => 'Stats, recent activity, and quick links to news, events, restaurants.',
                'path' => '/admin/dashboard.php',
                'icon' => 'fa-chart-pie',
                'tags' => ['dashboard', 'overview', 'analytics'],
            ],
            [
                'key' => 'articles',
                'name' => 'News Articles',
                'description' => 'Articles table: homepage news cards + /local-news/ detail pages. Primary news system.',
                'path' => '/admin/articles/index.php',
                'icon' => 'fa-newspaper',
                'tags' => ['news', 'content', 'articles', 'homepage'],
                'actions' => [
                    ['label' => 'Add Article', 'path' => '/admin/articles/add.php'],
                ],
            ],
            [
                'key' => 'news',
                'name' => 'News Bulletin',
                'description' => 'Legacy news_bulletin table (separate from Articles). Dashboard recent-news links here.',
                'path' => '/admin/news-list.php',
                'icon' => 'fa-list',
                'tags' => ['news', 'legacy'],
                'actions' => [
                    ['label' => 'Add News', 'path' => '/admin/news-add.php'],
                ],
            ],
            [
                'key' => 'events',
                'name' => 'Events Control',
                'description' => 'Review submissions, publish events, and access analytics.',
                'path' => '/admin/events/events-list.php',
                'icon' => 'fa-calendar-days',
                'tags' => ['events', 'calendar'],
                'actions' => [
                    ['label' => 'Add Event', 'path' => '/admin/events/events-add.php'],
                    ['label' => 'Events Analytics', 'path' => '/admin/events-analytics.php'],
                ],
            ],
            [
                'key' => 'jobs',
                'name' => 'Jobs Portal',
                'description' => 'Manage job listings, approvals, and featured roles.',
                'path' => '/omr-local-job-listings/admin/index.php',
                'icon' => 'fa-briefcase',
                'tags' => ['jobs', 'careers', 'employment'],
                'actions' => [
                    ['label' => 'Manage Jobs', 'path' => '/omr-local-job-listings/admin/manage-jobs-omr.php'],
                    ['label' => 'Verify Employers', 'path' => '/omr-local-job-listings/admin/verify-employers-omr.php'],
                    ['label' => 'All Applications', 'path' => '/omr-local-job-listings/admin/view-all-applications-omr.php'],
                ],
            ],
            [
                'key' => 'community_events',
                'name' => 'OMR Community Events',
                'description' => 'Moderate event submissions and manage published community events.',
                'path' => '/omr-local-events/admin/index.php',
                'icon' => 'fa-calendar-check',
                'tags' => ['events', 'community', 'submissions'],
                'actions' => [
                    ['label' => 'Manage Submissions', 'path' => '/omr-local-events/admin/manage-events-omr.php'],
                    ['label' => 'View Listings', 'path' => '/omr-local-events/admin/view-listings.php'],
                ],
            ],
            [
                'key' => 'hostels_pgs',
                'name' => 'Hostels & PGs',
                'description' => 'Oversee hostel, PG, and co-living listings along OMR.',
                'path' => '/omr-hostels-pgs/admin/index.php',
                'icon' => 'fa-building-user',
                'tags' => ['hostel', 'pg', 'property'],
                'actions' => [
                    ['label' => 'Manage Properties', 'path' => '/omr-hostels-pgs/admin/manage-properties.php'],
                    ['label' => 'Manage Owners', 'path' => '/omr-hostels-pgs/admin/manage-owners.php'],
                    ['label' => 'View Inquiries', 'path' => '/omr-hostels-pgs/admin/view-all-inquiries.php'],
                ],
            ],
            [
                'key' => 'rent_lease',
                'name' => 'Rent & Lease',
                'description' => 'Manage house, apartment, and land listings for rent/lease in OMR.',
                'path' => '/omr-rent-lease/admin/index.php',
                'icon' => 'fa-house',
                'tags' => ['rent', 'lease', 'property'],
            ],
            [
                'key' => 'buy_sell',
                'name' => 'Buy & Sell',
                'description' => 'Manage buy/sell classifieds listings in OMR.',
                'path' => '/omr-buy-sell/admin/index.php',
                'icon' => 'fa-shopping-bag',
                'tags' => ['buy', 'sell', 'classifieds'],
                'actions' => [
                    ['label' => 'Manage Listings', 'path' => '/omr-buy-sell/admin/manage-listings-omr.php'],
                    ['label' => 'Manage Categories', 'path' => '/omr-buy-sell/admin/manage-categories-omr.php'],
                ],
            ],
            [
                'key' => 'classified_ads',
                'name' => 'OMR Classified Ads',
                'description' => 'Approve services, wanted, and community notice listings (10-day expiry).',
                'path' => '/omr-classified-ads/admin/index.php',
                'icon' => 'fa-newspaper',
                'tags' => ['classifieds', 'community', 'moderation'],
                'actions' => [
                    ['label' => 'Manage Listings', 'path' => '/omr-classified-ads/admin/manage-listings-omr.php'],
                    ['label' => 'Reports', 'path' => '/omr-classified-ads/admin/view-reports-omr.php'],
                ],
            ],
            [
                'key' => 'coworking',
                'name' => 'Coworking Spaces',
                'description' => 'Manage coworking space listings, approvals, and featured slots.',
                'path' => '/omr-coworking-spaces/admin/index.php',
                'icon' => 'fa-building',
                'tags' => ['coworking', 'property', 'workspaces'],
                'actions' => [
                    ['label' => 'Manage Spaces', 'path' => '/omr-coworking-spaces/admin/manage-spaces.php'],
                    ['label' => 'Manage Owners', 'path' => '/omr-coworking-spaces/admin/manage-owners.php'],
                    ['label' => 'View Inquiries', 'path' => '/omr-coworking-spaces/admin/view-all-inquiries.php'],
                ],
            ],
            [
                'key' => 'restaurants',
                'name' => 'Restaurants & Cafés',
                'description' => 'Curate restaurant listings, menus, and availability.',
                'path' => '/admin/restaurants-list.php',
                'icon' => 'fa-utensils',
                'tags' => ['restaurants', 'food', 'dining'],
                'actions' => [
                    ['label' => 'Add Restaurant', 'path' => '/admin/restaurants-add.php'],
                ],
            ],
        ],
    ],
    [
        'key' => 'directories',
        'label' => 'Local Directories',
        'icon' => 'fa-city',
        'modules' => [
            [
                'key' => 'banks',
                'name' => 'Banks Directory',
                'description' => 'List and edit bank profiles. Use Manage Banks for delete operations.',
                'path' => '/admin/banks-list.php',
                'icon' => 'fa-landmark',
                'tags' => ['banks', 'finance', 'directories'],
                'actions' => [
                    ['label' => 'Manage Banks', 'path' => '/admin/manage-banks.php'],
                ],
            ],
            [
                'key' => 'schools',
                'name' => 'Schools & Colleges',
                'description' => 'Oversee educational listings, contact info, and facilities.',
                'path' => '/admin/schools-list.php',
                'icon' => 'fa-graduation-cap',
                'tags' => ['schools', 'education', 'directories'],
                'actions' => [
                    ['label' => 'Manage Schools', 'path' => '/admin/manage-schools.php'],
                ],
            ],
            [
                'key' => 'hospitals',
                'name' => 'Hospitals & Clinics',
                'description' => 'Maintain healthcare providers and emergency contacts.',
                'path' => '/admin/hospitals-list.php',
                'icon' => 'fa-house-medical',
                'tags' => ['hospitals', 'health', 'directories'],
                'actions' => [
                    ['label' => 'Manage Hospitals', 'path' => '/admin/manage-hospitals.php'],
                ],
            ],
            [
                'key' => 'parks',
                'name' => 'Parks & Recreation',
                'description' => 'Update parks, recreation spaces, and amenities.',
                'path' => '/admin/parks-list.php',
                'icon' => 'fa-tree',
                'tags' => ['parks', 'outdoor', 'directories'],
                'actions' => [
                    ['label' => 'Manage Parks', 'path' => '/admin/manage-parks.php'],
                ],
            ],
            [
                'key' => 'industries',
                'name' => 'Industrial Directory',
                'description' => 'Manage industrial hubs and company listings.',
                'path' => '/admin/industries-list.php',
                'icon' => 'fa-industry-windows',
                'tags' => ['industries', 'business', 'directories'],
                'actions' => [
                    ['label' => 'Manage Industries', 'path' => '/admin/manage-industries.php'],
                ],
            ],
            [
                'key' => 'government',
                'name' => 'Government Offices',
                'description' => 'Maintain municipal offices, contacts, and services.',
                'path' => '/admin/government-offices-list.php',
                'icon' => 'fa-landmark-flag',
                'tags' => ['government', 'civic', 'directories'],
            ],
            [
                'key' => 'atms',
                'name' => 'ATM Network',
                'description' => 'Track ATMs and banking touchpoints across OMR.',
                'path' => '/admin/atms-list.php',
                'icon' => 'fa-building-columns',
                'tags' => ['atm', 'finance', 'directories'],
                'actions' => [
                    ['label' => 'Manage ATMs', 'path' => '/admin/manage-atms.php'],
                ],
            ],
        ],
    ],
    [
        'key' => 'technology',
        'label' => 'Technology & IT',
        'icon' => 'fa-microchip',
        'modules' => [
            [
                'key' => 'it_submissions',
                'name' => 'IT Submissions',
                'description' => 'Review IT company submissions and inbound leads.',
                'path' => '/admin/it-submissions-list.php',
                'icon' => 'fa-clipboard-list',
                'tags' => ['it', 'submissions', 'technology'],
            ],
            [
                'key' => 'it_companies',
                'name' => 'IT Companies',
                'description' => 'Manage IT company profiles and categories.',
                'path' => '/admin/it-companies-list.php',
                'icon' => 'fa-building',
                'tags' => ['it', 'companies', 'technology'],
            ],
            [
                'key' => 'featured_it',
                'name' => 'Featured IT Listings',
                'description' => 'Highlight premium IT companies and sponsorships.',
                'path' => '/admin/featured-it-list.php',
                'icon' => 'fa-star',
                'tags' => ['featured', 'it', 'technology'],
            ],
            [
                'key' => 'it_parks',
                'name' => 'IT Parks Hub',
                'description' => 'Maintain IT park details, tenants, and facilities.',
                'path' => '/admin/it-parks/manage.php',
                'icon' => 'fa-network-wired',
                'tags' => ['it', 'parks', 'technology'],
                'actions' => [
                    ['label' => 'Featured IT Parks', 'path' => '/admin/it-parks/featured.php'],
                    ['label' => 'Import & Export', 'path' => '/admin/it-parks/import-export.php'],
                ],
            ],
        ],
    ],
    [
        'key' => 'operations',
        'label' => 'Operations & Utilities',
        'icon' => 'fa-screwdriver-wrench',
        'modules' => [
            [
                'key' => 'migrations',
                'name' => 'Migrations Runner',
                'description' => 'Execute database migrations and schema updates (dev-tools).',
                'path' => '/admin/migrations-runner.php',
                'icon' => 'fa-database',
                'tags' => ['operations', 'database', 'maintenance'],
            ],
            [
                'key' => 'change_password',
                'name' => 'Admin Security',
                'description' => 'Change password (updates admin_users table). Note: login uses core/admin-config.php.',
                'path' => '/admin/change-password.php',
                'icon' => 'fa-user-shield',
                'tags' => ['security', 'account', 'operations'],
            ],
        ],
    ],
];


