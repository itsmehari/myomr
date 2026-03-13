# OMR Rent & Lease — Complete Overhaul Plan

**Created:** March 2026  
**Trigger:** Overhaul rent/lease feature like omr-local-job-listings  
**Scope:** Current `listings/` rent/lease pages → new `omr-rent-lease/` module  
**Model:** Hostels/PGs + Job Portal (DB-backed, modular, ROOT_PATH, omr_nav, omr_footer)

---

## Executive Summary

**Current state (broken/fragmented):**
- `listings/rent-house-omr.php` → posts to `submit-rental.php` **(does not exist)**
- `listings/rent-land-omr.php` → posts to `admin/process-listing.php` (writes to txt files, not DB)
- `listings/sell-rent-property-house-plot-omr-chennai.php` → hub page, mixed styling, wrong paths
- No database, no search/browse, no admin moderation

**Target state:**
- New `omr-rent-lease/` folder: full-featured rental/lease property portal
- DB-backed (rent_lease_properties, rent_lease_owners)
- Browse listings, property detail, add property (owner auth), admin moderation
- Modular structure: ROOT_PATH, omr_nav, omr_footer, skip-link
- PHP 7.4+ compatible (avoid str_contains, match)

---

## Current File Inventory

| File | Purpose | Issue |
|------|---------|-------|
| listings/rent-house-omr.php | House rental form | submit-rental.php 404; wrong include paths |
| listings/rent-land-omr.php | Land lease form | process-listing writes txt only |
| listings/sell-rent-property-house-plot-omr-chennai.php | Hub page | Mixed styles, commonlogin link |
| listings/sell-property-omr.php | Sell form | Uses process-listing (txt) |
| admin/process-listing.php | Form handler | Appends to service_type_listings.txt only |

---

## Proposed Structure

```
omr-rent-lease/
├── index.php                    # Main listings (browse rent/lease properties)
├── property-detail-omr.php      # Single property view
├── add-property-omr.php         # Post property form (owner auth)
├── process-property-omr.php     # Form handler
├── property-posted-success-omr.php
├── owner-register-omr.php
├── owner-login-omr.php
├── owner-landing-omr.php
├── owner-logout-omr.php
├── my-properties-omr.php        # Owner dashboard
├── edit-property-omr.php
├── inquiry-confirmation-omr.php
├── generate-sitemap.php
├── robots.txt
├── includes/
│   ├── error-reporting.php
│   ├── property-functions.php
│   ├── owner-auth.php
│   └── seo-helper.php
├── assets/
│   └── rent-lease-omr.css
├── admin/
│   ├── index.php
│   ├── manage-properties-omr.php
│   ├── verify-owners-omr.php
│   └── view-inquiries-omr.php
└── uploads/images/              # Property photos
```

---

## Database Schema

```sql
-- rent_lease_owners (like employers)
CREATE TABLE rent_lease_owners (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  phone VARCHAR(20) NOT NULL,
  password_hash VARCHAR(255),
  address TEXT,
  status ENUM('pending','verified') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- rent_lease_properties
CREATE TABLE rent_lease_properties (
  id INT AUTO_INCREMENT PRIMARY KEY,
  owner_id INT NOT NULL,
  listing_type ENUM('rent-house','rent-apartment','rent-land','lease-commercial','sell-house','sell-plot') NOT NULL,
  title VARCHAR(255) NOT NULL,
  locality VARCHAR(100) NOT NULL,
  address TEXT,
  monthly_rent DECIMAL(12,2),
  deposit DECIMAL(12,2),
  property_details TEXT,
  amenities TEXT,
  contact_phone VARCHAR(20),
  contact_email VARCHAR(255),
  images JSON,
  status ENUM('pending','approved','rejected') DEFAULT 'pending',
  featured TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (owner_id) REFERENCES rent_lease_owners(id)
);

-- rent_lease_inquiries (optional Phase 2)
```

---

## Phase 1: Structure & Bootstrap ✅ DONE (2026-03-10)

- [x] Create `omr-rent-lease/` folder structure
- [ ] Add `includes/error-reporting.php` (copy from job portal)
- [ ] Add `includes/property-functions.php` (stub)
- [ ] Add `index.php` with modular bootstrap (ROOT_PATH, omr_nav, omr_footer)
- [ ] Add `README.md`

---

## Phase 2: Database & Core ✅ DONE

- [x] Create migration SQL: dev-tools/migrations/run_2026_03_10_create_rent_lease_tables.sql
- [x] property-functions: getRentLeaseProperties(), getRentLeasePropertyById(), getRentLeaseCount()
- [x] index.php: browse listings, filters, empty state
- [x] property-detail-omr.php: single property view

---

## Phase 3: Add Property ✅ DONE (simplified – no owner auth yet)

- [x] add-property-omr.php form
- [x] process-property-omr.php handler (CSRF, validation, DB insert)
- [x] property-posted-success-omr.php
- [ ] owner-register, owner-login (Phase 3b – future)

---

## Phase 4: Admin & Polish ✅ DONE

- [x] admin/index.php, manage-properties-omr.php
- [x] Central admin nav: Rent & Lease module
- [x] CSS (rent-lease-omr.css), skip-link on admin pages
- [x] Public pages use analytics (via component-includes / index); listings/ redirects in Phase 5

---

## Phase 5: Migration & Cleanup ✅ DONE

- [x] Redirect listings/rent-house-omr.php → omr-rent-lease/add-property-omr.php?type=rent-house (301)
- [x] Redirect listings/rent-land-omr.php → omr-rent-lease/add-property-omr.php?type=rent-land (301)
- [x] Update sell-rent-property hub to link to omr-rent-lease (browse + list); removed public admin login link
- [ ] Archive or deprecate txt-based process-listing for rent/lease (optional: admin/process-listing.php still used by other listing types; document that rent/lease flows use omr-rent-lease only)

---

## Dependencies

- `core/omr-connect.php`
- `core/include-path.php`
- `components/component-includes.php`, meta.php, analytics.php, skip-link.php
- `components/main-nav.php`, footer.php

---

## References

- `omr-hostels-pgs/` — structure, owner-auth, property flow
- `omr-local-job-listings/` — modular pattern, ROOT_PATH, PHP 7 compat
- `docs/inbox/JOB-PORTAL-OVERHAUL-PLAN.md`
- `docs/product-prd/PRD-OMR-Hostels-PGs.md`
