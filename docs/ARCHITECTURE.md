# Architecture Overview for MyOMR.in

## High-Level Architecture

- **Frontend:** HTML, CSS, JavaScript, jQuery, Bootstrap (no Node.js frameworks)
- **Backend:** PHP (legacy scripts and Laravel), MySQL database
- **Hosting:** cPanel/Linux shared hosting (public_html as web root)

## Directory Structure

- `public_html/` — All public-facing files (HTML, CSS, JS, images)
- `src/` or `app/` — PHP logic and business code (outside public_html if possible)
- `config/` — Configuration files (database, app settings)
- `docs/` — Documentation (onboarding, architecture, etc.)
- `tests/` — Unit and feature tests (if present)
- See `context.md` for a living directory tree and rationale

## Key Design Decisions

- **Separation of Concerns:** Public vs. private code, clear asset management
- **Security:** Sensitive files outside web root, .htaccess protection, strong DB credentials
- **Performance:** Asset minification, image optimization, caching
- **Extensibility:** Laravel backend for scalable features, legacy PHP for compatibility

## Ongoing Updates

- This file provides a snapshot; see `context.md` for the most current architecture and decisions.

## Recent Architectural Updates (as of December 2024)

- **Homepage Redesign:** `index.php` was refactored for a modular, mobile-friendly layout, integrating all dynamic PHP logic.
- **CSS Consolidation:** All custom CSS has been consolidated into `assets/css/main.css` for maintainability and performance. Includes footer, modal, action links, news bulletin, subscribe, and social styles.
- **Directory Pages Modularization:** Implemented centralized template system with `directory-template.php` and `directory-config.php` for all OMR directory pages (schools, hospitals, restaurants, banks, ATMs, etc.).
- **UN SDG Integration:** Added comprehensive Sustainable Development Goals page with MyOMR branding and community alignment.
- **Documentation:** All major docs (`README.md`, `CHANGELOG.md`, `ONBOARDING.md`, `TODO.md`, `context.md`, `ADDITIONAL_FEATURES_TODO.md`) are up to date and should be referenced for the latest project context and decisions.

## Directory Pages Migration (June 2024)

- All directory/listing pages (hospitals, IT companies, industries, banks, ATMs, parks, restaurants, government offices, schools, best schools) have been moved to `omr-listings/`.
- All navigation, cross-links, and internal links updated to use the new `/omr-listings/` URLs.
- All PHP `require` and `include` statements checked—no broken dependencies or missing includes.
- No errors found in static or dynamic references (HTML, JS, CSS, PHP).
- Documentation and changelog updated to reflect the migration.

## Listings Folder Structure (June 2024)

- The `listings/` folder now contains all business ads, job postings, real estate/rental, and education/class-related files.
- This improves organization, SEO, and maintainability.
- All cross-links, navigation, and references have been updated to use the new folder structure.
- Deleted files have been removed from the codebase and all dependencies checked.

## Local News Folder Structure (June 2024)

- The `local-news/` folder now contains all news, community, and gallery-related files previously in the root or other locations.
- This improves organization, SEO, and maintainability.
- All cross-links, navigation, and references have been updated to use the new folder structure.
- Deleted files have been removed from the codebase and all dependencies checked.

## Dependencies and Requirements

### Core Dependencies

- **PHP 7.4+** - Server-side scripting language
- **MySQL 5.7+** - Database management system
- **Apache/Nginx** - Web server
- **cPanel** - Hosting control panel (shared hosting)

### Frontend Dependencies

- **Bootstrap 4.6.1** - CSS framework for responsive design
- **jQuery 3.6.0** - JavaScript library
- **Font Awesome 6.0.0** - Icon library
- **Google Fonts** - Typography (Playfair Display, Josefin Sans, Roboto)
- **MDB UI Kit 4.4.0** - Material Design components

### Database Tables

**15+ tables in MySQL database `metap8ok_myomr`:**

**Content Tables:**

- `news_bulletin` - News articles and updates
- `events` - Community events and happenings
- `gallery` - Photo gallery for community images
- `businesses` - Featured businesses

**Directory Tables:**

- `omr_restaurants` - Restaurant listings with ratings, filters, and geolocation
- `omrschoolslist` - School directory
- `omrhospitalslist` - Hospital directory
- `omrbankslist` - Bank directory with IFSC codes
- `omratmslist` - ATM locations
- `omritcompanieslist` - IT companies
- `omrindustrieslist` - Industrial units
- `omrparkslist` - Parks and recreational areas
- `omrgovernmentofficeslist` - Government offices

**System Tables:**

- `List of Areas` - OMR localities and neighborhoods
- `admin_users` - Admin panel user accounts

**Complete Documentation:**

- **Database Structure:** See `docs/DATABASE_STRUCTURE.md` for complete table schemas, columns, indexes, and relationships
- **Quick Reference:** See `docs/DATABASE_QUICK_REFERENCE.md` for common queries and quick access
- **Connection Setup:** See `docs/LOCAL_TO_REMOTE_DATABASE_SETUP.md` for local development access
- **Schema Export:** Use `export-database-schema.php` to generate CREATE TABLE statements

### File Dependencies

#### Core Files

- `core/omr-connect.php` - Database connection
- `core/action-links.php` - Action buttons component
- `core/modal.js` - Modal functionality
- `core/modal.css` - Modal styles

#### Components

- `components/main-nav.php` - Main navigation
- `components/footer.php` - Site footer
- `components/meta.php` - Meta tags
- `components/analytics.php` - Google Analytics
- `components/head-resources.php` - Common head resources
- `components/social-icons.php` - Social media icons
- `components/subscribe.php` - Newsletter subscription
- `components/myomr-news-bulletin.php` - News bulletin component

#### Directory System

- `omr-listings/directory-config.php` - Centralized directory configuration
- `omr-listings/directory-template.php` - Reusable directory template
- `omr-listings/omr-listings-nav.php` - Directory navigation

#### Stylesheets

- `assets/css/main.css` - Centralized custom styles
- `components/footer.css` - Footer-specific styles (legacy)
- `components/myomr-news-bulletin.css` - News bulletin styles (legacy)
- `core/action-links.css` - Action links styles (legacy)
- `core/modal.css` - Modal styles (legacy)

### External Services

- **Google Analytics** - Website analytics (G-JX509Z0779)
- **Google Maps API** - Map integration (requires API key)
- **Facebook SDK** - Social media integration
- **WhatsApp** - Community chat integration
- **Brevo Conversations** - Live chat widget
- **TradingView** - Market data widgets

### Security Dependencies

- **SSL Certificate** - HTTPS encryption
- **.htaccess** - URL rewriting and security rules
- **Input Sanitization** - `htmlspecialchars()` for all user inputs
- **Prepared Statements** - MySQLi prepared statements for database queries
- **Session Management** - PHP sessions for admin authentication

### Performance Dependencies

- **Image Optimization** - WebP format support
- **Lazy Loading** - JavaScript-based image lazy loading
- **CDN Integration** - External CDN for static assets
- **Caching** - Browser caching headers
- **Compression** - Gzip compression

### Development Dependencies

- **Git** - Version control
- **VS Code** - Development environment
- **PHP Debugging** - Error reporting and logging
- **MySQL Workbench** - Database management
- **FileZilla** - FTP client for deployment

### Browser Support

- **Chrome 80+** - Primary browser support
- **Firefox 75+** - Secondary browser support
- **Safari 13+** - iOS/macOS support
- **Edge 80+** - Windows support
- **Mobile Browsers** - Responsive design support

### Hosting Requirements

- **PHP 7.4+** with MySQLi extension
- **MySQL 5.7+** database
- **Apache** with mod_rewrite enabled
- **SSL Certificate** support
- **File Upload** permissions
- **Cron Jobs** support (for scheduled tasks)
- **Email** functionality (for contact forms)

### API Dependencies

- **Google Maps API** - For location services
- **Facebook API** - For social media integration
- **WhatsApp Business API** - For messaging
- **Email Service** - For notifications and newsletters
- **SMS Service** - For mobile notifications (future)

### Third-Party Libraries

- **Bootstrap** - CSS framework
- **jQuery** - JavaScript library
- **Font Awesome** - Icon library
- **Google Fonts** - Typography
- **MDB UI Kit** - Material Design components
- **TradingView** - Financial widgets
- **Pinterest** - Social sharing
