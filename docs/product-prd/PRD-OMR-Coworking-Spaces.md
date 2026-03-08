# 📋 Product Requirement Document: OMR Coworking Spaces

**Version:** 1.0  
**Date:** January 2025  
**Status:** 🟢 Ready for Staging - 90% Complete - Critical Fixes Applied  
**Prepared by:** AI Product Manager  
**Project:** MyOMR.in  
**Note:** This is a future feature. Focus currently on Hostels & PGs module.

---

## 📋 Table of Contents

1. [Executive Summary](#executive-summary)
2. [Business Analysis](#business-analysis)
3. [Target Users & Personas](#target-users--personas)
4. [Feature Components](#feature-components)
5. [User Roles & Workflows](#user-roles--workflows)
6. [Technical Architecture](#technical-architecture)
7. [Folder Structure](#folder-structure)
8. [Work Breakdown Structure (WBS)](#work-breakdown-structure-wbs)
9. [Database Schema](#database-schema)
10. [SEO Strategy](#seo-strategy)
11. [Security Considerations](#security-considerations)
12. [Success Metrics](#success-metrics)

---

## 🎯 Executive Summary

### Product Vision

Create a **comprehensive coworking space discovery and booking platform** for the OMR corridor, connecting freelancers, startups, and remote workers with affordable, flexible workspace solutions while empowering space owners to maximize occupancy and visibility.

### Business Goals

- 📈 **Space Listings:** 50+ coworking spaces in 3 months
- 👥 **User Acquisition:** 500+ registered users seeking workspaces
- 📊 **Inquiries:** 200+ booking inquiries per month
- 🔍 **SEO Performance:** Rank #1 for "coworking spaces in OMR Chennai"
- 💰 **Revenue Potential:** Featured listings monetization
- 🤝 **Community Impact:** Foster local business ecosystem

### Key Differentiators

- ✅ Location-specific to OMR corridor (hyperlocal)
- ✅ Verified space owners (trust badges)
- ✅ Detailed amenities filtering
- ✅ Transparent pricing models
- ✅ Direct owner communication
- ✅ Free initial listings for space owners

---

## 🏢 Business Analysis

### Problem Statement

**For Workspace Seekers:**

- Difficulty finding affordable, well-equipped coworking spaces in OMR
- Lack of centralized information about amenities, pricing, and availability
- Uncertainty about space quality and credibility
- Time-consuming manual research across multiple platforms

**For Space Owners:**

- Limited visibility in a crowded market
- Inefficient booking and inquiry management
- High vacancy rates leading to revenue loss
- Lack of digital presence and professional listing

### Solution Approach

**Centralized Discovery Platform**

- Single destination for all OMR coworking spaces
- Advanced filtering by location, amenities, price, and availability
- Detailed space profiles with photos and reviews
- Direct communication channel with space owners

**Owner Empowerment Tools**

- Self-service dashboard for listing management
- Analytics on views and inquiries
- Featured placement options for premium visibility
- Automated inquiry notifications

**Trust & Credibility**

- Owner verification system
- Space verification badges
- User reviews and ratings
- Transparent pricing information

---

## 👥 Target Users & Personas

### Persona 1: Freelance Developer - Arjun (28 years old)

- **Demographics:** Freelancer, lives in Thoraipakkam
- **Goals:** Find affordable workspace with good internet and quiet zones
- **Pain Points:** Working from home is distracting, needs professional environment
- **Tech Savviness:** High (mobile-first user)
- **Budget:** ₹5,000-8,000/month
- **Location Preference:** Near metro stations in OMR

### Persona 2: Startup Founder - Priya (32 years old)

- **Demographics:** Runs 5-person startup, location flexible
- **Goals:** Scalable workspace for growing team with meeting rooms
- **Pain Points:** Long-term leases are risky, needs flexibility
- **Tech Savviness:** Medium (prefers simple processes)
- **Budget:** ₹30,000-50,000/month
- **Location Preference:** IT parks in Sholinganallur or Navalur

### Persona 3: Remote Employee - Suresh (35 years old)

- **Demographics:** Works for Mumbai-based company, relocated to Chennai
- **Goals:** Professional workspace with conference facilities
- **Pain Points:** Needs reliable workspace for daily video calls
- **Tech Savviness:** Medium
- **Budget:** ₹8,000-12,000/month
- **Location Preference:** Close to residential areas in OMR

### Persona 4: Space Owner - Rajesh (45 years old)

- **Demographics:** Owns coworking space in Navalur, 50 seats
- **Goals:** Maximize occupancy, attract quality professionals
- **Pain Points:** Low visibility, manual inquiry management
- **Tech Savviness:** Low (needs simple interface)
- **Revenue Goal:** 80% occupancy rate

---

## 🎨 Feature Components

### User-Facing Features

#### 1. Homepage (`index.php`)

- Hero section with search functionality
- Featured coworking spaces (3-6 spaces)
- Quick filters (location, price range, amenities)
- Category tiles (startup friendly, day pass, 24/7 access)
- Statistics banner (X spaces, Y members, Z inquiries)
- "List Your Space" CTA for owners

#### 2. Search Results Page (`search.php`)

- **Filters:**
  - Location (dropdown with OMR localities)
  - Price range (slider: ₹0-₹50,000/month)
  - Amenities (checkboxes): WiFi, AC, Meeting Rooms, Cafeteria, Parking, 24/7 Access, Printing, Security, Gym, Terrace
  - Space type: Day Pass, Hot Desk, Dedicated Desk, Private Cabin, Team Space
  - Verified badge filter
- **Sort Options:**
  - Featured first
  - Price: Low to High / High to Low
  - Recently Added
  - Highest Rated
- **View Options:** Grid / List
- **Results Display:**
  - Space card with photo, name, location, price, key amenities, rating
  - Pagination (20 per page)

#### 3. Space Detail Page (`space-detail.php`)

- **Header Section:**
  - Image gallery (5-10 photos)
  - Space name, verified badge
  - Location with Google Maps link
  - Price range and pricing models
  - Quick actions: Book Now, Save, Share
- **Overview Tab:**
  - Description (300-500 words)
  - Amenities checklist
  - Capacity and seating options
  - Operating hours
- **Pricing Tab:**
  - Day Pass pricing
  - Monthly plans breakdown
  - Compare pricing options
  - Special offers/discounts
- **Photos Tab:**
  - Image gallery
  - Virtual tour (if available)
- **Reviews Tab:**
  - User ratings (5-star system)
  - Written reviews
  - Owner responses
- **Location Tab:**
  - Google Maps embed
  - Nearby landmarks
  - Public transport options
  - Distance from metro/bus stops
- **Owner Info:**
  - Owner name and contact
  - Response time
  - Joined date
  - Other listings (if any)

#### 4. Booking/Inquiry Form (`inquiry.php`)

- **User Details:**
  - Full name
  - Email
  - Phone number
  - Current location (dropdown)
- **Booking Preferences:**
  - Interested space type
  - Start date
  - Duration (1 day, 1 week, 1 month, 3 months, 6 months, 1 year)
  - Number of seats required
  - Special requirements (text area)
- **Additional Info:**
  - Purpose (dropdown): Work, Startup, Team, Other
  - Budget range
  - Any questions for owner
- **Terms & Conditions:**
  - Privacy policy checkbox
  - Terms acceptance
- **Submit:** Sends notification to owner, confirmation to user

#### 5. User Dashboard (`user-dashboard.php`)

- **My Inquiries:**
  - Active inquiries (pending responses)
  - Completed inquiries (responses received)
  - Saved spaces
- **Profile Settings:**
  - Personal info
  - Notification preferences
  - Password change
- **Help & Support:**
  - FAQ
  - Contact form

### Owner-Facing Features

#### 6. Owner Registration (`owner-register.php`)

- **Company/Personal Details:**
  - Full name / Company name
  - Email
  - Phone
  - Address
  - Website (optional)
- **Business Info:**
  - Years in operation
  - Number of spaces managed
  - Description
- **Account Security:**
  - Password
  - Email verification
- **Status:** Pending → Verified by admin

#### 7. Owner Dashboard (`owner-dashboard.php`)

- **Overview Stats:**
  - Total listings
  - Active listings
  - Total views (last 30 days)
  - Total inquiries (last 30 days)
  - Response rate
- **My Spaces:**
  - List of all spaces with status indicators
  - Quick actions: View, Edit, Duplicate, Delete
  - Add New Space CTA
- **Inquiries:**
  - New inquiries (highlighted)
  - All inquiries with filters
  - Quick response buttons
  - Mark as contacted/resolved
- **Analytics:**
  - Views per space
  - Inquiries per space
  - Popular times
  - Source of traffic

#### 8. Add/Edit Space (`add-space.php`, `edit-space.php`)

- **Basic Information:**
  - Space name
  - Address (auto-complete with Google Places)
  - Locality (dropdown)
  - Landmark
  - Pin code
- **Description:**
  - Brief overview (100 words)
  - Full description (300-500 words)
  - Special features highlight
- **Space Details:**
  - Total capacity
  - Available space types and seating options
  - Operating hours
  - Check-in policy
- **Pricing:**
  - Day pass price
  - Hot desk monthly price
  - Dedicated desk monthly price
  - Private cabin monthly price (if applicable)
  - Team space price (per seat, if applicable)
  - Special offers/promotions
- **Amenities:**
  - Checkboxes for all amenities
  - Additional custom amenities (text field)
- **Photos:**
  - Upload multiple photos (max 10, max 5MB each)
  - Set featured image
  - Add captions
  - Image optimization (auto WebP conversion)
- **Contact:**
  - Contact person name
  - Email (default from owner account)
  - Phone
  - WhatsApp number (optional)
- **Settings:**
  - Featured listing toggle (admin-controlled for verified accounts)
  - Status: Active / Inactive
  - Verification status: Pending / Verified

#### 9. Owner View Inquiries (`view-inquiries.php`)

- **Inquiry Details:**
  - User name, email, phone
  - Interested space
  - Date and time of inquiry
  - Booking preferences
  - User questions
- **Response Actions:**
  - Reply via email (auto-populate)
  - Call user (click to dial)
  - WhatsApp message
  - Mark as contacted
  - Mark as resolved
- **Inquiry History:**
  - Timeline of interactions
  - Notes (owner can add private notes)

### Admin Features

#### 10. Admin Dashboard (`admin/index.php`)

- **Overview Stats:**
  - Total spaces
  - Active spaces
  - Pending owner registrations
  - Total inquiries
  - System health metrics
- **Quick Actions:**
  - Approve/reject owner registrations
  - Verify spaces
  - View flagged listings
  - Manage featured spaces

#### 11. Manage Owners (`admin/manage-owners.php`)

- **Owner List:**
  - Name, email, phone
  - Number of listings
  - Status: Pending / Verified / Suspended
  - Action buttons: View, Verify, Suspend, Delete
- **Filters:**
  - Status
  - Registration date
  - Search by name/email

#### 12. Manage Spaces (`admin/manage-spaces.php`)

- **Space List:**
  - Space name, owner, location
  - Views count
  - Inquiries count
  - Status: Active / Inactive / Flagged
  - Verification badge
  - Action buttons: View, Edit, Verify, Feature, Unlist, Delete
- **Bulk Actions:**
  - Bulk verify
  - Bulk feature
  - Bulk unlist

#### 13. Manage Inquiries (`admin/view-all-inquiries.php`)

- **All Inquiries Table:**
  - User details
  - Space details
  - Inquiry date
  - Status: New / Contacted / Resolved / Archived
  - Action: View details

---

## 🔄 User Roles & Workflows

### Role 1: Space Seeker (Guest User)

#### Workflow: Search and Book

1. **Discovery**
   - Land on homepage → Browse featured spaces
   - Use search bar → Enter location/keyword
   - Apply filters → Narrow down results
2. **Evaluation**
   - Click on space → View details
   - Check photos, amenities, pricing
   - Read reviews
   - View location on map
3. **Decision**
   - Save space for later OR
   - Proceed with inquiry
4. **Booking/Inquiry**
   - Fill inquiry form
   - Submit details
   - Receive confirmation email
5. **Follow-up**
   - Owner contacts via email/phone
   - Visit space (if needed)
   - Finalize booking offline
6. **Post-Booking**
   - Leave review after experience
   - Book again in future

---

### Role 2: Space Owner (Registered User)

#### Workflow: List and Manage

1. **Registration**
   - Fill registration form
   - Submit for admin approval
   - Email verification
   - Wait for admin verification (24-48 hours)
2. **First Listing**
   - Log in to dashboard
   - Click "Add New Space"
   - Fill all details
   - Upload photos
   - Submit for review
   - Admin approves (24-48 hours)
3. **Ongoing Management**
   - Receive inquiry notifications (email + dashboard)
   - View inquiry details
   - Respond to user
   - Mark inquiry as contacted/resolved
4. **Optimization**
   - Monitor analytics (views, inquiries)
   - Update pricing if needed
   - Add new photos
   - Respond to reviews
   - Request featured placement (verified owners only)

---

### Role 3: Super Admin

#### Workflow: Platform Management

1. **Owner Management**
   - Review pending registrations
   - Verify owner credentials (phone/address check)
   - Approve or reject
   - Send verification email
2. **Space Management**
   - Review new space listings
   - Verify space details (optional phone verification)
   - Add verification badge if verified
   - Approve or request edits
3. **Content Moderation**
   - Monitor flagged listings
   - Review user reports
   - Edit/delete inappropriate content
   - Suspend problematic owners
4. **Featured Management**
   - Promote quality spaces to featured
   - Rotate featured spaces monthly
   - Remove expired featured listings
5. **Analytics & Reporting**
   - Weekly KPI reports
   - Traffic analysis
   - User behavior insights
   - Revenue tracking

---

## 🗄️ Technical Architecture

### Database Schema

See [Database Schema](#database-schema) section below.

### Technology Stack

- **Frontend:** HTML5, CSS3, Bootstrap 5, Vanilla JavaScript
- **Backend:** PHP 7.4+ (Procedural)
- **Database:** MySQL 5.7+ (InnoDB, UTF8MB4)
- **Hosting:** cPanel Shared Hosting
- **APIs:** Google Maps API, Email (PHPMailer/SMTP)

### Core Files Structure

```
omr-coworking-spaces/
├── index.php                          # Homepage
├── search.php                         # Search results
├── space-detail.php                   # Individual space view
├── inquiry.php                        # Booking inquiry form
├── user-dashboard.php                 # User dashboard
├── owner-register.php                 # Owner registration
├── owner-login.php                    # Owner login
├── owner-dashboard.php                # Owner dashboard
├── add-space.php                      # Add new space form
├── edit-space.php                     # Edit space form
├── view-inquiries.php                 # Owner view inquiries
├── process-inquiry.php                # Handle inquiry submission
├── process-space.php                  # Handle space add/edit
├── process-owner.php                  # Handle owner registration
├── includes/
│   ├── space-functions.php            # Helper functions
│   ├── owner-auth.php                 # Owner authentication
│   └── seo-helper.php                 # SEO utilities
├── admin/
│   ├── index.php                      # Admin dashboard
│   ├── manage-owners.php              # Manage owners
│   ├── manage-spaces.php              # Manage spaces
│   ├── view-all-inquiries.php         # All inquiries
│   └── verify-spaces.php              # Verify spaces
├── assets/
│   ├── coworking-spaces.css           # Main stylesheet
│   ├── coworking-search.js            # Search functionality
│   └── coworking-gallery.js           # Image gallery
└── README.md                          # Documentation
```

---

## 🗂️ Folder Structure

### Module Folder: `/omr-coworking-spaces/`

```
omr-coworking-spaces/
│
├── 📄 index.php                       # Main listing page with featured spaces
├── 📄 search.php                      # Search results with filters
├── 📄 space-detail.php                # Individual coworking space profile
├── 📄 inquiry.php                     # Booking inquiry form
├── 📄 inquiry-confirmation.php        # Inquiry success page
├── 📄 owner-register.php              # Space owner registration
├── 📄 owner-login.php                 # Owner login
├── 📄 owner-logout.php                # Owner logout
├── 📄 owner-dashboard.php             # Owner dashboard (guarded)
├── 📄 add-space.php                   # Add new coworking space
├── 📄 edit-space.php                  # Edit existing space
├── 📄 view-inquiries.php              # Owner view inquiries
├── 📄 user-dashboard.php              # User inquiry management
├── 📄 save-space.php                  # Save/unsave spaces (AJAX)
├── 📄 process-inquiry.php             # Backend: handle inquiry
├── 📄 process-space.php               # Backend: handle space CRUD
├── 📄 process-owner.php               # Backend: handle owner registration
├── 📄 upload-space-photos.php         # Backend: handle photo uploads
│
├── 📁 includes/
│   ├── space-functions.php            # Helper functions
│   ├── owner-auth.php                 # Owner session management
│   ├── inquiry-functions.php          # Inquiry operations
│   └── seo-helper.php                 # SEO utilities
│
├── 📁 admin/
│   ├── index.php                      # Admin dashboard
│   ├── manage-owners.php              # Approve/verify owners
│   ├── manage-spaces.php              # Moderate spaces
│   ├── view-all-inquiries.php         # All inquiries view
│   ├── verify-spaces.php              # Verify space details
│   ├── analytics.php                  # Analytics dashboard
│   └── featured-management.php        # Manage featured spaces
│
├── 📁 assets/
│   ├── coworking-spaces.css           # Main stylesheet
│   ├── coworking-search.js            # Search/filter logic
│   ├── coworking-gallery.js           # Image gallery slider
│   ├── coworking-analytics-events.js  # Google Analytics events
│   └── images/
│       ├── default-space.jpg          # Default image
│       └── amenities/                 # Amenity icons
│
├── 📄 CREATE-COWORKING-DATABASE.sql   # Database schema
├── 📄 README.md                       # Module documentation
└── 📄 .htaccess                       # Clean URLs (if needed)
```

### Links in Main Navigation

**Global Navigation (components/main-nav.php):**

```html
<!-- Add to main navigation -->
<li><a href="/omr-coworking-spaces/">Coworking Spaces</a></li>
```

**Homepage (index.php):**

```html
<!-- Add to Quick Actions or Features section -->
<div class="feature-card">
  <h3>Find Coworking Spaces</h3>
  <p>Discover affordable and flexible workspaces in OMR</p>
  <a href="/omr-coworking-spaces/" class="btn btn-primary">Explore Spaces</a>
</div>
```

---

## ✅ Work Breakdown Structure (WBS)

**Legend:** [ ] Pending | [~] In Progress | [x] Completed

---

### Phase 1: Foundation & Planning (Week 1)

#### 1.1 Requirements & Documentation

- [x] 1.1.1 Finalize PRD document (this document)
- [x] 1.1.2 Create detailed wireframes for all pages
- [x] 1.1.3 Design database schema
- [x] 1.1.4 Prepare technical architecture document
- [x] 1.1.5 Set up project folder structure
- **Owner:** Product Manager | **Effort:** 2 days

#### 1.2 Database Setup

- [x] 1.2.1 Create `CREATE-COWORKING-DATABASE.sql`
- [x] 1.2.2 Run migration in phpMyAdmin
- [ ] 1.2.3 Insert sample/seed data
- [x] 1.2.4 Test database connections
- [x] 1.2.5 Verify indexes and foreign keys
- **Owner:** Backend Developer | **Effort:** 1 day
- **Dependencies:** Schema finalized

#### 1.3 Core File Setup

- [x] 1.3.1 Create folder structure (`omr-coworking-spaces/`)
- [x] 1.3.2 Set up `includes/space-functions.php`
- [x] 1.3.3 Set up `includes/owner-auth.php`
- [x] 1.3.4 Create core helper functions
- [x] 1.3.5 Configure `.htaccess` for clean URLs (if needed)
- **Owner:** Backend Developer | **Effort:** 1 day

#### 1.4 Design System Integration

- [x] 1.4.1 Review MyOMR design tokens (`assets/css/core.css`)
- [x] 1.4.2 Create `coworking-spaces.css` using tokens
- [x] 1.4.3 Design component library (cards, buttons, forms)
- [x] 1.4.4 Ensure responsive breakpoints
- **Owner:** Frontend Developer | **Effort:** 2 days

---

### Phase 2: Public User Features (Week 2-3)

#### 2.1 Homepage (`index.php`)

- [x] 2.1.1 Hero section with search bar
- [x] 2.1.2 Featured spaces grid (3-6 spaces)
- [x] 2.1.3 Statistics banner
- [x] 2.1.4 Category tiles
- [x] 2.1.5 CTAs (List Your Space, Browse All)
- [x] 2.1.6 Add Google Analytics events
- [x] 2.1.7 Add JSON-LD structured data
- **Owner:** Frontend Developer | **Effort:** 2 days
- **Dependencies:** Database ready, design system

#### 2.2 Search Results Page (`search.php`)

- [x] 2.2.1 Filter sidebar (location, price, amenities)
- [x] 2.2.2 Sort dropdown functionality
- [x] 2.2.3 Grid/List view toggle
- [x] 2.2.4 Space cards with key info
- [x] 2.2.5 Pagination
- [x] 2.2.6 Filter persistence in URL
- [x] 2.2.7 "No results" state
- [x] 2.2.8 Add analytics events
- **Owner:** Frontend + Backend | **Effort:** 3 days

#### 2.3 Space Detail Page (`space-detail.php`)

- [x] 2.3.1 Image gallery with lightbox
- [x] 2.3.2 Tabbed interface (Overview, Pricing, Photos, Reviews, Location)
- [x] 2.3.3 Amenities checklist display
- [x] 2.3.4 Pricing breakdown table
- [x] 2.3.5 Reviews and ratings section
- [x] 2.3.6 Google Maps embed
- [x] 2.3.7 Owner contact section
- [x] 2.3.8 Social sharing buttons
- [x] 2.3.9 "Book Now" / "Save Space" CTAs
- [x] 2.3.10 Add Structured Data (LocalBusiness schema)
- [x] 2.3.11 Add breadcrumb JSON-LD
- [x] 2.3.12 Add analytics tracking
- **Owner:** Frontend + Backend | **Effort:** 4 days

#### 2.4 Inquiry Form (`inquiry.php`)

- [x] 2.4.1 Form fields (user details, preferences)
- [x] 2.4.2 Client-side validation
- [x] 2.4.3 Server-side validation (`process-inquiry.php`)
- [x] 2.4.4 Email notification to owner
- [x] 2.4.5 Confirmation email to user
- [x] 2.4.6 Store in database
- [x] 2.4.7 Success page (`inquiry-confirmation.php`)
- [x] 2.4.8 CSRF protection
- [x] 2.4.9 Rate limiting (5 inquiries/hour per email)
- [x] 2.4.10 Add analytics conversion event
- **Owner:** Frontend + Backend | **Effort:** 2 days

#### 2.5 User Dashboard (`user-dashboard.php`)

- [ ] 2.5.1 My Inquiries section
- [ ] 2.5.2 Saved Spaces section
- [ ] 2.5.3 Profile settings
- [ ] 2.5.4 View inquiry details
- [ ] 2.5.5 Unsave space functionality
- [ ] 2.5.6 Email/SMS notification preferences
- [ ] 2.5.7 Help/FAQ section
- **Owner:** Frontend + Backend | **Effort:** 2 days

---

### Phase 3: Owner Features (Week 4)

#### 3.1 Owner Registration (`owner-register.php`)

- [x] 3.1.1 Registration form
- [x] 3.1.2 Client-side validation
- [~] 3.1.3 Server-side validation (`process-owner.php`)
- [ ] 3.1.4 Email verification
- [ ] 3.1.5 Admin notification for approval
- [x] 3.1.6 Store in database
- [x] 3.1.7 Success message
- [x] 3.1.8 CSRF protection
- **Owner:** Frontend + Backend | **Effort:** 1 day

#### 3.2 Owner Login (`owner-login.php`)

- [x] 3.2.1 Login form
- [x] 3.2.2 Session management (`includes/owner-auth.php`)
- [x] 3.2.3 Password hashing verification
- [ ] 3.2.4 Remember me functionality
- [x] 3.2.5 Redirect to dashboard after login
- [ ] 3.2.6 Rate limiting (5 attempts/hour)
- **Owner:** Backend | **Effort:** 0.5 days

#### 3.3 Owner Dashboard (`owner-dashboard.php`)

- [x] 3.3.1 Overview stats cards
- [x] 3.3.2 My Spaces list with quick actions
- [~] 3.3.3 Recent Inquiries widget
- [x] 3.3.4 Quick links (Add Space, View Analytics)
- [x] 3.3.5 Guard with `requireOwnerAuth()`
- [x] 3.3.6 Responsive layout
- **Owner:** Frontend + Backend | **Effort:** 2 days

#### 3.4 Add/Edit Space (`add-space.php`, `edit-space.php`)

- [x] 3.4.1 Multi-section form (Basic Info, Details, Pricing, Amenities, Photos, Contact)
- [ ] 3.4.2 Google Places autocomplete for address
- [ ] 3.4.3 Image upload with preview (`upload-space-photos.php`)
- [x] 3.4.4 Client-side validation
- [x] 3.4.5 Server-side validation (`process-space.php`)
- [x] 3.4.6 Slug generation
- [ ] 3.4.7 Admin notification for approval
- [x] 3.4.8 Success/error messages
- [x] 3.4.9 CSRF protection
- [ ] 3.4.10 File upload security (type, size, virus check)
- **Owner:** Frontend + Backend | **Effort:** 3 days

#### 3.5 View Inquiries (`view-inquiries.php`)

- [x] 3.5.1 Inquiry list with filters
- [x] 3.5.2 Inquiry details modal/section
- [x] 3.5.3 Quick actions (Email, Call, WhatsApp, Mark as Contacted)
- [x] 3.5.4 Inquiry status tracking
- [ ] 3.5.5 Add private notes
- [ ] 3.5.6 Export to CSV (optional)
- **Owner:** Frontend + Backend | **Effort:** 2 days

#### 3.6 Photo Upload (`upload-space-photos.php`)

- [ ] 3.6.1 Multi-file upload
- [ ] 3.6.2 Image optimization (resize, WebP conversion)
- [ ] 3.6.3 Store in `/assets/img/coworking-spaces/{space_id}/`
- [ ] 3.6.4 Validate file type (JPG, PNG, WebP)
- [ ] 3.6.5 Validate file size (max 5MB)
- [ ] 3.6.6 Return JSON response for AJAX
- **Owner:** Backend | **Effort:** 1 day

---

### Phase 4: Admin Features (Week 5)

#### 4.1 Admin Dashboard (`admin/index.php`)

- [x] 4.1.1 Overview stats cards
- [x] 4.1.2 Pending approvals count
- [x] 4.1.3 Recent activity feed
- [x] 4.1.4 Quick actions menu
- [x] 4.1.5 Guard with `requireAdmin()`
- **Owner:** Frontend + Backend | **Effort:** 1 day

#### 4.2 Manage Owners (`admin/manage-owners.php`)

- [x] 4.2.1 Owner list with filters
- [x] 4.2.2 Approve/Reject/Suspend actions
- [x] 4.2.3 Email notifications on status change
- [x] 4.2.4 View owner details
- [ ] 4.2.5 Bulk actions
- [x] 4.2.6 CSRF protection
- [ ] 4.2.7 Audit logging
- **Owner:** Frontend + Backend | **Effort:** 2 days

#### 4.3 Manage Spaces (`admin/manage-spaces.php`)

- [x] 4.3.1 Space list with filters
- [x] 4.3.2 Approve/Reject/Unlist actions
- [x] 4.3.3 Verify/Feature toggles
- [x] 4.3.4 View space details
- [ ] 4.3.5 Bulk actions
- [x] 4.3.6 CSRF protection
- [ ] 4.3.7 Audit logging
- **Owner:** Frontend + Backend | **Effort:** 2 days

#### 4.4 View All Inquiries (`admin/view-all-inquiries.php`)

- [x] 4.4.1 All inquiries table
- [~] 4.4.2 Filters by date, space, status
- [x] 4.4.3 View inquiry details
- [ ] 4.4.4 Export to CSV
- **Owner:** Frontend + Backend | **Effort:** 1 day

#### 4.5 Featured Management (`admin/featured-management.php`)

- [x] 4.5.1 Featured spaces list
- [x] 4.5.2 Add/Remove from featured
- [x] 4.5.3 Set start/end dates
- [ ] 4.5.4 Auto-expire featured listings
- **Owner:** Frontend + Backend | **Effort:** 1 day

---

### Phase 5: SEO & Analytics (Week 6)

#### 5.1 SEO Implementation

- [x] 5.1.1 Meta tags on all pages
- [x] 5.1.2 Structured Data (LocalBusiness, BreadcrumbList, ItemList)
- [x] 5.1.3 Clean URLs via `.htaccess`
- [x] 5.1.4 Sitemap generation (`generate-sitemap.php`)
- [ ] 5.1.5 Robots.txt configuration
- [x] 5.1.6 Internal linking strategy
- [x] 5.1.7 Canonical URLs
- [ ] 5.1.8 Submit to Google Search Console
- **Owner:** SEO Specialist + Backend | **Effort:** 2 days

#### 5.2 Analytics Integration

- [x] 5.2.1 Google Analytics 4 setup
- [x] 5.2.2 Event tracking (`coworking-analytics-events.js`)
- [ ] 5.2.3 Conversion goals
- [ ] 5.2.4 Funnel analysis
- [x] 5.2.5 User behavior tracking
- [ ] 5.2.6 Heatmap tool (optional: Hotjar)
- **Owner:** Analytics Specialist | **Effort:** 1 day

---

### Phase 6: Testing & QA (Week 7)

#### 6.1 Functional Testing

- [ ] 6.1.1 Test all user workflows
- [ ] 6.1.2 Test all owner workflows
- [ ] 6.1.3 Test all admin workflows
- [ ] 6.1.4 Test edge cases and error handling
- [ ] 6.1.5 Test form validations
- [ ] 6.1.6 Test file uploads
- **Owner:** QA Tester | **Effort:** 2 days

#### 6.2 Security Testing

- [ ] 6.2.1 SQL injection testing
- [ ] 6.2.2 XSS testing
- [ ] 6.2.3 CSRF testing
- [ ] 6.2.4 File upload security testing
- [ ] 6.2.5 Session security testing
- [ ] 6.2.6 OWASP ZAP scan
- **Owner:** Security Specialist | **Effort:** 1 day

#### 6.3 Performance Testing

- [ ] 6.3.1 Page load speed tests
- [ ] 6.3.2 Database query optimization
- [ ] 6.3.3 Image optimization verification
- [ ] 6.3.4 Mobile performance testing
- [ ] 6.3.5 Lighthouse audit
- **Owner:** Performance Specialist | **Effort:** 1 day

#### 6.4 Accessibility Testing

- [ ] 6.4.1 Keyboard navigation
- [ ] 6.4.2 Screen reader compatibility (NVDA)
- [ ] 6.4.3 Color contrast check
- [ ] 6.4.4 Alt text verification
- [ ] 6.4.5 WCAG 2.1 AA compliance audit (WAVE, axe)
- **Owner:** Accessibility Specialist | **Effort:** 1 day

#### 6.5 Cross-Browser Testing

- [ ] 6.5.1 Chrome, Firefox, Safari, Edge
- [ ] 6.5.2 Mobile browsers (Chrome, Safari)
- [ ] 6.5.3 Responsive design verification
- **Owner:** Frontend Developer | **Effort:** 1 day

---

### Phase 7: Deployment & Launch (Week 8)

#### 7.1 Pre-Deployment

- [ ] 7.1.1 Final code review
- [ ] 7.1.2 Documentation completion
- [ ] 7.1.3 Backup database
- [ ] 7.1.4 Environment configuration
- [ ] 7.1.5 SSL certificate verification
- **Owner:** DevOps | **Effort:** 0.5 days

#### 7.2 Deployment

- [ ] 7.2.1 Deploy files to production
- [ ] 7.2.2 Run database migrations
- [ ] 7.2.3 Configure permissions
- [ ] 7.2.4 Test live environment
- [ ] 7.2.5 Verify email functionality
- [ ] 7.2.6 Submit sitemap to Search Console
- **Owner:** DevOps | **Effort:** 0.5 days

#### 7.3 Post-Launch

- [ ] 7.3.1 Monitor error logs
- [ ] 7.3.2 Monitor analytics
- [ ] 7.3.3 User feedback collection
- [ ] 7.3.4 Bug fixes and hotfixes
- [ ] 7.3.5 Performance monitoring
- **Owner:** Full Team | **Effort:** Ongoing

#### 7.4 Marketing & Promotion

- [ ] 7.4.1 Homepage banner announcement
- [ ] 7.4.2 Social media posts
- [ ] 7.4.3 Email to existing users
- [ ] 7.4.4 Reach out to coworking space owners
- [ ] 7.4.5 Local business partnerships
- **Owner:** Marketing Team | **Effort:** Ongoing

---

### Phase 8: Future Enhancements (Post-Launch)

- [ ] 8.1 User reviews and ratings system
- [ ] 8.2 Advanced booking calendar
- [ ] 8.3 Online payment integration
- [ ] 8.4 Mobile app (React Native/PWA)
- [ ] 8.5 Virtual tours integration
- [ ] 8.6 Instant booking (if owner enabled)
- [ ] 8.7 Notification system (push, SMS)
- [ ] 8.8 Advanced analytics dashboard
- [ ] 8.9 Multi-language support
- [ ] 8.10 AI-powered space recommendations

---

## 🗃️ Database Schema

```sql
-- ============================================================
-- MyOMR Coworking Spaces - Database Setup
-- ============================================================

-- Drop existing tables if they exist (for clean rebuild)
DROP TABLE IF EXISTS space_inquiries;
DROP TABLE IF EXISTS space_reviews;
DROP TABLE IF EXISTS space_photos;
DROP TABLE IF EXISTS coworking_spaces;
DROP TABLE IF EXISTS space_owners;
DROP TABLE IF EXISTS saved_spaces;

-- ============================================================
-- TABLE 1: space_owners
-- Stores coworking space owners/managers
-- ============================================================

CREATE TABLE space_owners (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    company_name VARCHAR(200),
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    whatsapp VARCHAR(20),
    address TEXT,
    website VARCHAR(255),
    description TEXT,
    password_hash VARCHAR(255) NOT NULL,
    status ENUM('pending', 'verified', 'suspended') DEFAULT 'pending',
    verification_token VARCHAR(100),
    verified_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE 2: coworking_spaces
-- Stores all coworking space listings
-- ============================================================

CREATE TABLE coworking_spaces (
    id INT PRIMARY KEY AUTO_INCREMENT,
    owner_id INT NOT NULL,
    space_name VARCHAR(200) NOT NULL,
    slug VARCHAR(260) UNIQUE NOT NULL,
    address TEXT NOT NULL,
    locality VARCHAR(100),
    landmark VARCHAR(100),
    pincode VARCHAR(10),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    brief_overview TEXT,
    full_description MEDIUMTEXT,
    total_capacity INT,
    space_types TEXT, -- JSON: [{"type": "Hot Desk", "seats": 20}, ...]
    operating_hours VARCHAR(200),
    checkin_policy TEXT,
    day_pass_price DECIMAL(10, 2),
    hot_desk_monthly DECIMAL(10, 2),
    dedicated_desk_monthly DECIMAL(10, 2),
    private_cabin_monthly DECIMAL(10, 2),
    team_space_monthly DECIMAL(10, 2),
    special_offers TEXT,
    amenities TEXT, -- JSON: ["WiFi", "AC", "Meeting Rooms", ...]
    custom_amenities TEXT,
    featured_image VARCHAR(255),
    verification_status ENUM('pending', 'verified') DEFAULT 'pending',
    featured BOOLEAN DEFAULT 0,
    featured_start DATETIME NULL,
    featured_end DATETIME NULL,
    status ENUM('pending', 'active', 'inactive', 'flagged') DEFAULT 'pending',
    views_count INT DEFAULT 0,
    inquiries_count INT DEFAULT 0,
    rating_average DECIMAL(2, 1) DEFAULT 0,
    reviews_count INT DEFAULT 0,
    contact_person VARCHAR(100),
    contact_email VARCHAR(255),
    contact_phone VARCHAR(20),
    contact_whatsapp VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES space_owners(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE 3: space_photos
-- Stores photos for each coworking space
-- ============================================================

CREATE TABLE space_photos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    space_id INT NOT NULL,
    photo_url VARCHAR(255) NOT NULL,
    caption TEXT,
    display_order INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (space_id) REFERENCES coworking_spaces(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE 4: space_inquiries
-- Stores booking inquiries from users
-- ============================================================

CREATE TABLE space_inquiries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    space_id INT NOT NULL,
    user_name VARCHAR(100) NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    user_phone VARCHAR(20) NOT NULL,
    user_location VARCHAR(200),
    interested_space_type VARCHAR(100),
    start_date DATE,
    duration VARCHAR(50),
    seats_required INT,
    special_requirements TEXT,
    purpose VARCHAR(100),
    budget_range VARCHAR(100),
    additional_questions TEXT,
    status ENUM('new', 'contacted', 'resolved', 'archived') DEFAULT 'new',
    owner_notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (space_id) REFERENCES coworking_spaces(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE 5: space_reviews
-- Stores user reviews and ratings (Future Enhancement)
-- ============================================================

CREATE TABLE space_reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    space_id INT NOT NULL,
    user_name VARCHAR(100) NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_title VARCHAR(200),
    review_text TEXT,
    verified_purchase BOOLEAN DEFAULT 0,
    helpful_count INT DEFAULT 0,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (space_id) REFERENCES coworking_spaces(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE 6: saved_spaces
-- Stores user's saved/bookmarked spaces (Future Enhancement)
-- ============================================================

CREATE TABLE saved_spaces (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_email VARCHAR(255) NOT NULL,
    space_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (space_id) REFERENCES coworking_spaces(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_space (user_email, space_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE INDEXES FOR PERFORMANCE
-- ============================================================

CREATE INDEX idx_owner_status ON space_owners(status);
CREATE INDEX idx_space_owner ON coworking_spaces(owner_id);
CREATE INDEX idx_space_locality ON coworking_spaces(locality);
CREATE INDEX idx_space_status ON coworking_spaces(status);
CREATE INDEX idx_space_featured ON coworking_spaces(featured);
CREATE INDEX idx_space_featured_dates ON coworking_spaces(featured_start, featured_end);
CREATE INDEX idx_space_verification ON coworking_spaces(verification_status);
CREATE INDEX idx_space_slug ON coworking_spaces(slug);
CREATE INDEX idx_photos_space ON space_photos(space_id);
CREATE INDEX idx_inquiries_space ON space_inquiries(space_id);
CREATE INDEX idx_inquiries_status ON space_inquiries(status);
CREATE INDEX idx_inquiries_email ON space_inquiries(user_email);
CREATE INDEX idx_reviews_space ON space_reviews(space_id);
CREATE INDEX idx_reviews_status ON space_reviews(status);
CREATE INDEX idx_saved_user ON saved_spaces(user_email);

-- ============================================================
-- SAMPLE DATA (OPTIONAL - FOR TESTING)
-- ============================================================

-- Insert a sample owner
INSERT INTO space_owners (
    full_name,
    company_name,
    email,
    phone,
    password_hash,
    status
) VALUES (
    'Rajesh Kumar',
    'OMR Workspace Solutions',
    'rajesh@omrworkspace.in',
    '9876543210',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: "password"
    'verified'
);

-- Insert a sample coworking space
INSERT INTO coworking_spaces (
    owner_id,
    space_name,
    slug,
    address,
    locality,
    brief_overview,
    full_description,
    total_capacity,
    space_types,
    operating_hours,
    day_pass_price,
    hot_desk_monthly,
    amenities,
    verification_status,
    status
) VALUES (
    LAST_INSERT_ID(),
    'Innovation Hub - Navalur',
    'innovation-hub-navalur',
    '123 IT Highway, Navalur, OMR, Chennai - 600130',
    'Navalur',
    'Modern coworking space perfect for startups and freelancers in the heart of OMR.',
    'Welcome to Innovation Hub, Navalur\'s premier coworking space designed for the modern professional. Our space offers flexible seating options, high-speed internet, meeting rooms, and 24/7 access. Located in the heart of the IT corridor, we provide an ideal environment for productivity and collaboration.',
    50,
    '[{"type": "Hot Desk", "seats": 30}, {"type": "Dedicated Desk", "seats": 15}, {"type": "Private Cabin", "seats": 5}]',
    'Mon-Sat: 9 AM - 8 PM, 24/7 access available',
    500,
    8000,
    '["WiFi", "AC", "Meeting Rooms", "Cafeteria", "Parking", "24/7 Access", "Printing", "Security", "Gym", "Terrace"]',
    'verified',
    'active'
);

-- ============================================================
-- VERIFY DATABASE SETUP
-- ============================================================

SELECT 'Database setup completed successfully!' as message;
SELECT COUNT(*) as total_owners FROM space_owners;
SELECT COUNT(*) as total_spaces FROM coworking_spaces;

```

---

## 🔍 SEO Strategy

### Keyword Strategy

**Primary Keywords:**

- coworking spaces in OMR
- shared office space OMR Chennai
- coworking near me OMR
- day pass coworking OMR
- hot desk OMR Chennai

**Long-tail Keywords:**

- affordable coworking space in Navalur OMR
- 24/7 coworking space in Sholinganallur
- startup-friendly workspace OMR
- meeting room rental OMR

### On-Page SEO

- **Title Tags:** Include location + keyword (e.g., "Affordable Coworking Spaces in OMR Chennai | MyOMR")
- **Meta Descriptions:** 150-160 characters with CTA
- **H1 Tags:** One per page, includes primary keyword
- **Structured Data:** LocalBusiness, BreadcrumbList, ItemList
- **Internal Linking:** Link to locality pages, featured spaces
- **Image Alt Text:** Descriptive alt text for all photos

### Content Strategy

- **Locality Pages:** Dedicated pages for Navalur, Sholinganallur, Thoraipakkam, etc.
- **Blog Posts:** "Top 5 Coworking Spaces in OMR", "Day Pass vs Monthly Plan", etc.
- **FAQ Section:** Address common queries
- **User Guide:** "How to Choose a Coworking Space in OMR"

---

## 🔒 Security Considerations

### Authentication & Authorization

- Password hashing with bcrypt
- Session management with secure cookies
- CSRF tokens on all forms
- Rate limiting on login (5 attempts/hour)
- Email verification for owners

### Data Protection

- Prepared statements for all SQL queries
- Input sanitization with `htmlspecialchars`
- File upload validation (type, size)
- XSS prevention
- SQL injection prevention

### Privacy

- Privacy policy compliance
- GDPR considerations (if applicable)
- Secure data handling
- User data retention policy

---

## 📊 Success Metrics

### Launch Metrics (3 months)

| Metric                           | Target |
| -------------------------------- | ------ |
| Total Space Listings             | 50+    |
| Active Listings                  | 40+    |
| Registered Owners                | 30+    |
| Total Inquiries                  | 500+   |
| Average Monthly Views            | 5,000+ |
| Conversion Rate (View → Inquiry) | 5%+    |
| Response Rate by Owners          | 80%+   |

### SEO Metrics

| Metric                              | Target       |
| ----------------------------------- | ------------ |
| Google Ranking for Primary Keywords | Top 3        |
| Organic Sessions                    | 1,000+/month |
| Pages Indexed                       | 100%         |
| Backlinks                           | 20+          |

### User Engagement Metrics

| Metric                   | Target     |
| ------------------------ | ---------- |
| Average Session Duration | 3+ minutes |
| Bounce Rate              | < 60%      |
| Pages per Session        | 3+         |
| Return Users             | 30%+       |

---

## 📝 Acceptance Criteria

### Functional Requirements

- [ ] Users can search and filter coworking spaces
- [ ] Users can submit booking inquiries
- [ ] Owners can register and login
- [ ] Owners can add/edit spaces
- [ ] Owners can view and manage inquiries
- [ ] Admin can moderate owners and spaces
- [ ] All forms validate on client and server
- [ ] Email notifications work correctly

### Non-Functional Requirements

- [ ] Page load time < 3 seconds
- [ ] Mobile responsive (320px+)
- [ ] WCAG 2.1 AA compliant
- [ ] Zero critical security vulnerabilities
- [ ] SEO meta tags on all pages
- [ ] Structured data validates
- [ ] Cross-browser compatible

---

## 🚀 Next Steps

1. **Approve this PRD** ✅
2. **Create database** (`CREATE-COWORKING-DATABASE.sql`)
3. **Begin Phase 1 implementation**
4. **Set up project tracking**
5. **Schedule weekly review meetings**

---

**Document Status:** ✅ Ready for Review  
**Estimated Timeline:** 8 weeks  
**Team Size:** 2 developers (1 frontend, 1 backend)

---

**End of Document**
