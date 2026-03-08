# MyOMR Hostels & PGs Portal

A comprehensive portal for discovering and booking hostels, paying guest accommodations, and co-living spaces in the Old Mahabalipuram Road (OMR) corridor, Chennai.

## Overview

The Hostels & PGs module provides a complete platform for:
- **Students** and **Professionals** to find safe, affordable accommodations
- **Property Owners** to list and manage their properties
- **Administrators** to moderate and oversee the platform

## Features

### User-Facing Features
- Advanced search and filtering (location, property type, gender preference, price)
- Property detail pages with photos, amenities, and pricing
- Inquiry submission form
- Responsive grid/list views
- Featured properties highlighting

### Owner-Facing Features
- Property listing management
- Inquiry tracking and management
- Dashboard analytics
- Photo uploads

### Admin Features
- Property moderation
- Owner verification
- Inquiry oversight

## Technical Stack

- **Frontend:** HTML5, CSS3, Bootstrap 5, Vanilla JavaScript
- **Backend:** PHP (Procedural)
- **Database:** MySQL 5.7+
- **Architecture:** MVC-style separation

## Database Schema

See `CREATE-HOSTELS-PGS-DATABASE.sql` for complete schema:
- `property_owners` - Owner accounts
- `hostels_pgs` - Property listings
- `property_photos` - Property images
- `property_inquiries` - Booking inquiries
- `property_reviews` - Reviews (future)
- `saved_properties` - Bookmarks (future)

## Folder Structure

```
omr-hostels-pgs/
├── includes/
│   ├── error-reporting.php
│   ├── property-functions.php
│   ├── owner-auth.php
│   └── seo-helper.php
├── admin/
│   └── (admin pages)
├── assets/
│   ├── images/
│   ├── hostels-pgs.css
│   └── hostels-search.js
├── index.php
├── property-detail.php
├── owner-login.php
├── owner-register.php
├── add-property.php
├── my-properties.php
├── CREATE-HOSTELS-PGS-DATABASE.sql
└── README.md
```

## Installation

1. Import database schema:
```bash
mysql -u username -p database_name < CREATE-HOSTELS-PGS-DATABASE.sql
```

2. Configure database connection in `../core/omr-connect.php`

3. Ensure proper file permissions for uploads

## SEO Features

- Schema.org structured data (LocalBusiness, BreadcrumbList, ItemList)
- Open Graph tags
- Twitter Cards
- Canonical URLs
- Alt text for images
- Semantic HTML

## Security

- Prepared statements for all SQL queries
- Input sanitization (`htmlspecialchars`, `strip_tags`)
- CSRF protection on forms
- File upload validation
- XSS prevention

## Accessibility

- WCAG 2.1 AA compliant
- Semantic HTML5
- ARIA labels
- Keyboard navigation
- Screen reader support
- Skip links
- Focus indicators

## Performance

- Optimized database queries with indexes
- Lazy loading for images
- Minified CSS/JS
- CDN for Bootstrap/Font Awesome

## License

Copyright © MyOMR.in. All rights reserved.

## Contact

For support: webmaster@myomr.in

