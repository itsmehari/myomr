---
title: PRD-003 Listing Management
status: Draft
owner: Hari Krishnan
project-manager: GPT-5 Codex (AI)
created: 18-11-2025
updated: 18-11-2025
version: 1.0
document-type: PRD
tags:
  - omr-marketplace
  - listing-management
  - crud-operations
  - prd
---

# PRD-003: Listing Management

> **Document Type:** Product Requirements Document (PRD)  
> **Status:** `Draft v1.0` | **Last Updated:** 18-11-2025  
> **Priority:** P0 (Critical - MVP)  
> **Related Documents:** [PRD-002 Seller Management](PRD-002-Seller-Management.md) | [Marketplace Project Discussion](../../inbox/MARKETPLACE-PROJECT-DISCUSSION.md)

---

## 📋 Executive Summary

Listing Management enables sellers to create, edit, manage, and optimize product/service listings on the marketplace. This PRD defines the listing creation workflow, media management, category classification, pricing, and listing lifecycle management. Listings go through moderation before being published and can be paused, resumed, or archived.

### Key Objectives

1. Enable sellers to create comprehensive product/service listings
2. Support multiple images and media uploads
3. Implement category classification and tagging
4. Manage listing lifecycle (draft → pending → live → archived)
5. Enforce subscription plan limits
6. Support both products and services

---

## 🎯 User Stories

### US-003.1: Create New Listing
**As a** seller,  
**I want to** create a new listing with photos and details,  
**So that** buyers can discover my products/services.

**Acceptance Criteria:**
- Access listing creation form from seller dashboard
- Multi-step form with validation
- Image upload (min 1, max 10 images)
- Category selection required
- Price and description fields
- Preview before submission
- Submit for moderation

### US-003.2: Edit Existing Listing
**As a** seller,  
**I want to** edit my existing listings,  
**So that** I can update prices, descriptions, or availability.

**Acceptance Criteria:**
- Edit button on all seller listings
- Edit form pre-populated with existing data
- Changes saved as draft initially
- Re-submission for moderation if major changes
- Minor edits (price/quantity) can go live immediately
- Edit history tracked

### US-003.3: Upload Listing Images
**As a** seller,  
**I want to** upload multiple images for my listing,  
**So that** buyers can see product/service details.

**Acceptance Criteria:**
- Drag-and-drop or file picker for images
- Image preview before upload
- Upload progress indicator
- Image reordering (drag-and-drop)
- Set primary image
- Image validation (format, size, dimensions)
- Max 10 images per listing
- Min 1 image required

### US-003.4: Manage Listing Status
**As a** seller,  
**I want to** pause, resume, or archive my listings,  
**So that** I can manage availability without deleting.

**Acceptance Criteria:**
- Pause button to temporarily hide listing
- Resume button to reactivate paused listing
- Archive button to remove from active listings
- Status visible in dashboard
- Bulk actions for multiple listings
- Status change confirmation dialogs

### US-003.5: Track Listing Performance
**As a** seller,  
**I want to** see views and inquiries for my listings,  
**So that** I can optimize my listings.

**Acceptance Criteria:**
- View count displayed on each listing
- Inquiry count visible
- Performance metrics in dashboard
- Sort listings by performance
- Export listing performance data (Premium plan)

---

## 📋 Functional Requirements

### FR-003.1: Listing Creation Workflow

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Sellers create listings through a multi-step form that collects all necessary information, validates input, and submits for moderation.

**Requirements:**
- **Step 1: Basic Information**
  - Title (required, 10-180 characters)
  - Category selection (required, hierarchical dropdown)
  - Subcategory (optional, if available)
  - Condition (required): New / Like-New / Used / Refurbished / Service
  - Description (required, 50-5000 characters, rich text editor)
- **Step 2: Pricing & Availability**
  - Price (required, numeric, min ₹1, max ₹99,99,999)
  - Currency (default: INR, fixed for MVP)
  - Quantity available (required, integer, min 1)
  - Negotiable checkbox (optional)
  - Free delivery checkbox (optional)
- **Step 3: Media Upload**
  - Primary image upload (required)
  - Additional images (optional, max 9 more)
  - Image validation (JPG/PNG, max 5MB each, min 400x300px)
  - Image reordering interface
  - Caption for each image (optional)
- **Step 4: Location & Delivery**
  - Location (required): Auto-populated from seller profile, editable
  - Delivery mode (required): Pickup / Delivery / Hybrid
  - Delivery radius (if delivery enabled, in km)
  - Delivery charges (optional, if applicable)
  - Delivery notes (optional, 255 characters)
- **Step 5: Review & Submit**
  - Listing preview
  - Summary of all entered information
  - Check subscription limit (plan enforcement)
  - Submit for moderation button
  - Save as draft option

**Workflow:**
1. Seller clicks "Create Listing" in dashboard
2. Multi-step form presented
3. Validation at each step
4. Data saved as draft after each step
5. Final submission checks subscription limits
6. Listing status: `draft` → `pending` (submitted for moderation)
7. Email notification sent to seller
8. Moderator reviews (see PRD-006)
9. Status: `pending` → `live` (approved) or `rejected` (with feedback)

**Technical Specifications:**
- Form implemented with JavaScript/Bootstrap multi-step wizard
- Real-time validation with visual feedback
- Draft auto-save every 30 seconds
- AJAX submission to avoid page reload
- Subscription limit check before final submission
- Image upload via AJAX with progress tracking

### FR-003.2: Listing Data Model

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Complete listing data structure with all fields, validation rules, and relationships.

**Requirements:**
- **Core Fields:**
  - `id`: Unique identifier
  - `seller_id`: Foreign key to seller_accounts
  - `title`: Listing title
  - `slug`: URL-friendly slug (auto-generated from title)
  - `description`: Full description (HTML allowed)
  - `price`: Decimal (12,2)
  - `currency`: CHAR(3), default 'INR'
  - `condition`: ENUM (new, like-new, used, refurbished, service)
  - `quantity`: Integer, default 1
  - `is_negotiable`: Boolean, default false
  - `free_delivery`: Boolean, default false
- **Location & Delivery:**
  - `location`: VARCHAR(160)
  - `delivery_mode`: ENUM (pickup, delivery, hybrid)
  - `delivery_radius`: Integer (km), nullable
  - `delivery_charges`: Decimal (10,2), nullable
  - `delivery_notes`: VARCHAR(255), nullable
- **Status Management:**
  - `status`: ENUM (draft, pending, live, paused, suspended, archived)
  - `expires_at`: DATETIME, nullable (auto-archive after expiry)
  - `featured`: Boolean (Premium plan listings)
  - `featured_until`: DATETIME, nullable
- **Metadata:**
  - `views_count`: Integer, default 0
  - `inquiries_count`: Integer, default 0
  - `created_at`: DATETIME
  - `updated_at`: DATETIME
  - `published_at`: DATETIME, nullable (when status became 'live')

**Technical Specifications:**
- Database table: `listings` (see schema section)
- Slug generation: PHP function to create URL-safe slug
- Indexes on: seller_id, status, category, created_at, views_count
- Soft delete: Use status='archived' instead of hard delete

### FR-003.3: Category & Tag Management

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Listings are classified into categories and can be tagged for better discovery.

**Requirements:**
- **Category System:**
  - Hierarchical categories (parent-child relationships)
  - Top-level categories:
    - Electronics
    - Fashion & Accessories
    - Home & Furniture
    - Vehicles
    - Books & Media
    - Sports & Fitness
    - Services
    - Handmade & Crafts
    - Real Estate
    - Others
  - Category selection required during listing creation
  - Multi-category selection (optional, Phase 2)
- **Tag System:**
  - Free-form tags (optional)
  - Suggested tags based on category
  - Tag autocomplete
  - Max 5 tags per listing
  - Popular tags displayed on category pages
- **Category Display:**
  - Category pages: `/marketplace/category/{category-slug}`
  - Breadcrumb navigation
  - Category icons/images
  - Category descriptions

**Technical Specifications:**
- Categories table: `categories` (id, slug, name, parent_id, sort_order, is_active)
- Category mapping: `category_map` (listing_id, category_id)
- Tags table: `tags` (id, slug, label, usage_count)
- Tag mapping: `listing_tags` (listing_id, tag_id)
- Category tree built with recursive PHP function or adjacency list model

### FR-003.4: Media Management

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Listing images are uploaded, validated, stored securely, and optimized for web display.

**Requirements:**
- **Image Upload:**
  - Drag-and-drop interface
  - File picker fallback
  - Multiple file selection
  - Upload progress per image
  - Client-side validation (before upload)
  - Server-side validation (format, size, dimensions)
- **Image Processing:**
  - Automatic resize: Max width 1920px, maintain aspect ratio
  - Thumbnail generation: 400x300px
  - WebP conversion (optional, Phase 2)
  - Compression to reduce file size
  - Watermarking (optional, Phase 2)
- **Image Storage:**
  - Storage path: `marketplace/uploads/listings/{listing_id}/`
  - File naming: `{timestamp}-{random}.jpg`
  - Database records: `listing_media` table
  - Primary image marked (is_primary flag)
  - Sort order stored (sort_order field)
- **Image Display:**
  - Primary image shown in listing cards
  - Image gallery on listing detail page
  - Lightbox for full-size images
  - Lazy loading for performance
  - Alt text from caption or auto-generated

**Technical Specifications:**
- Upload handling: PHP with `move_uploaded_file()`
- Image processing: GD library or Imagick
- File validation: MIME type checking, file extension
- Secure storage: Files outside web root or .htaccess protection
- Image optimization: Resize, compress, format conversion
- CDN integration (optional, Phase 2)

### FR-003.5: Listing CRUD Operations

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Full Create, Read, Update, Delete operations for listings with appropriate access controls.

**Requirements:**
- **Create:**
  - Accessible from seller dashboard
  - Subscription limit check
  - Draft auto-save
  - Form validation
  - Success notification
- **Read:**
  - Public view: Listing detail page `/marketplace/listing/{slug}`
  - Seller view: Management interface in dashboard
  - Admin view: Moderation interface
  - View count tracking (increment on public view)
- **Update:**
  - Edit form with pre-populated data
  - Change tracking (what changed)
  - Re-moderation required for:
    - Title changes
    - Category changes
    - Condition changes
    - Image changes
  - Immediate updates allowed for:
    - Price changes
    - Quantity changes
    - Delivery notes
  - Edit history logged (audit trail)
- **Delete:**
  - Soft delete: Status changed to 'archived'
  - Hard delete: Only by admin, requires confirmation
  - Associated data cleanup:
    - Media files deleted (or archived)
    - Inquiries remain (historical record)
    - Moderation logs retained

**Technical Specifications:**
- CRUD endpoints: PHP files in `marketplace/api/listings.php`
- Access control: Session-based, seller_id check
- AJAX for create/update (no page reload)
- Confirmation dialogs for delete actions
- Audit logging for all changes

### FR-003.6: Listing Status Lifecycle

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Listings move through various statuses during their lifecycle, from creation to archival.

**Status Flow:**
```
draft → pending → live → paused → live
                    ↓
                 rejected → draft (resubmit)
                    ↓
                 suspended → (admin action)
                    ↓
                 archived → (seller or auto-expiry)
```

**Status Definitions:**
- `draft`: Listing created but not submitted, editable
- `pending`: Submitted for moderation, awaiting review
- `live`: Approved and published, visible to buyers
- `paused`: Temporarily hidden by seller, can be resumed
- `suspended`: Hidden by admin due to policy violation
- `rejected`: Moderator rejected, feedback provided
- `archived`: Deactivated, not visible, historical record

**Status Transitions:**
- Seller can: draft → pending, live → paused, paused → live, live → archived
- Moderator can: pending → live, pending → rejected, live → suspended
- System can: live → archived (auto-expiry after expires_at date)

**Requirements:**
- Status displayed clearly in seller dashboard
- Status change notifications (email)
- Status history tracked (audit log)
- Bulk status changes (pause multiple listings)
- Auto-expiry: Cron job checks expires_at and archives

**Technical Specifications:**
- Status enum in database
- Status transition validation (allowed transitions)
- Status change logging in audit_trails table
- Email notifications on status changes
- Cron job for auto-expiry: Daily check at midnight

### FR-003.7: Subscription Plan Enforcement

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Listing creation and management must respect subscription plan limits.

**Requirements:**
- **Plan Limits:**
  - Starter (Free): Max 3 live listings
  - Featured (₹999/month): Max 10 live listings
  - Premium (₹2,499/month): Unlimited listings
- **Enforcement:**
  - Check subscription status before allowing listing creation
  - Block creation if limit reached (show upgrade CTA)
  - Count only `live` and `pending` listings toward limit
  - `paused` and `draft` listings don't count
  - Real-time limit display in dashboard
- **Upgrade Prompts:**
  - Show upgrade message when limit reached
  - Highlight benefits of next tier
  - Link to subscription management page
  - Countdown to limit (e.g., "3/3 listings used")

**Technical Specifications:**
- Limit check: PHP function before listing creation
- Query: `SELECT COUNT(*) FROM listings WHERE seller_id = ? AND status IN ('live', 'pending')`
- Cached subscription info in session to reduce DB queries
- Upgrade CTA component in UI

---

## 🗄️ Database Schema

### `listings` Table
```sql
CREATE TABLE listings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    title VARCHAR(180) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    currency CHAR(3) DEFAULT 'INR',
    condition ENUM('new', 'like-new', 'used', 'refurbished', 'service') NOT NULL,
    quantity INT DEFAULT 1 NOT NULL,
    is_negotiable TINYINT(1) DEFAULT 0,
    free_delivery TINYINT(1) DEFAULT 0,
    location VARCHAR(160) NOT NULL,
    delivery_mode ENUM('pickup', 'delivery', 'hybrid') NOT NULL,
    delivery_radius INT NULL,
    delivery_charges DECIMAL(10,2) NULL,
    delivery_notes VARCHAR(255) NULL,
    status ENUM('draft', 'pending', 'live', 'paused', 'suspended', 'rejected', 'archived') DEFAULT 'draft',
    expires_at DATETIME NULL,
    featured TINYINT(1) DEFAULT 0,
    featured_until DATETIME NULL,
    views_count INT DEFAULT 0,
    inquiries_count INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at DATETIME NULL,
    FOREIGN KEY (seller_id) REFERENCES seller_accounts(id) ON DELETE CASCADE,
    INDEX idx_seller_id (seller_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_views_count (views_count),
    INDEX idx_featured (featured, featured_until),
    FULLTEXT idx_search (title, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### `listing_media` Table
```sql
CREATE TABLE listing_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    path VARCHAR(255) NOT NULL,
    media_type ENUM('image', 'video', 'doc') DEFAULT 'image',
    caption VARCHAR(200) NULL,
    sort_order INT DEFAULT 0,
    is_primary TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    INDEX idx_listing_id (listing_id),
    INDEX idx_sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### `categories` Table
```sql
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(120) UNIQUE NOT NULL,
    name VARCHAR(120) NOT NULL,
    parent_id INT NULL,
    description TEXT NULL,
    icon_url VARCHAR(255) NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_parent_id (parent_id),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### `category_map` Table
```sql
CREATE TABLE category_map (
    listing_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (listing_id, category_id),
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### `listing_tags` Table
```sql
CREATE TABLE listing_tags (
    listing_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (listing_id, tag_id),
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 🎨 UI/UX Requirements

### UR-003.1: Listing Creation Form
- Multi-step wizard with progress indicator
- Clear step labels (1 of 5, 2 of 5, etc.)
- Back/Next navigation between steps
- Save draft button on each step
- Form validation with helpful error messages
- Image upload with drag-and-drop
- Preview before submission
- Mobile-responsive design

### UR-003.2: Listing Management Dashboard
- Grid/list view toggle
- Filters: Status, Category, Date
- Search within seller listings
- Bulk actions: Pause, Resume, Archive
- Quick stats per listing (views, inquiries)
- Color-coded status indicators
- Sort options: Date, Views, Inquiries
- Pagination for large lists

### UR-003.3: Listing Detail Page (Public)
- Hero image carousel
- Title and price prominently displayed
- Description with rich formatting
- Category breadcrumb
- Seller information card
- Delivery information
- Inquiry button (prominent CTA)
- Share buttons (WhatsApp, Facebook, Email)
- Related listings section
- SEO meta tags

---

## 🧪 Testing Requirements

### TR-003.1: Listing Creation Tests
- Complete listing creation flow
- Image upload validation
- Form validation at each step
- Draft save functionality
- Subscription limit enforcement
- Category selection
- Price validation

### TR-003.2: Listing Management Tests
- Edit existing listing
- Status transitions
- Bulk actions
- Delete/archive functionality
- Media management (add/remove/reorder)
- Search and filter

### TR-003.3: Performance Tests
- Page load time with 100+ listings
- Image upload performance
- Search query performance
- Database query optimization
- Mobile responsiveness

---

## 📊 Success Metrics

### KPIs
- **Average Listings per Seller**: Target 5-10
- **Listing Creation Completion Rate**: Target >80%
- **Average Time to Create Listing**: Target <10 minutes
- **Listing Approval Rate**: Target >85%
- **Image Upload Success Rate**: Target >95%

---

## 🗓️ Implementation Timeline

### Phase 1: MVP (Weeks 5-8)
- ✅ Listing creation form
- ✅ Media upload system
- ✅ Category management
- ✅ Basic CRUD operations
- ✅ Status lifecycle
- ✅ Subscription limit enforcement

### Phase 2: Enhancements (Weeks 9-12)
- Advanced editing features
- Bulk operations
- Performance optimization
- SEO enhancements
- Analytics integration

---

## 📝 Open Questions & Decisions

### Decisions Made
- ✅ Multi-step form for listing creation
- ✅ Draft auto-save functionality
- ✅ Image max 10 per listing
- ✅ Subscription limits enforced at creation
- ✅ Re-moderation for major changes

### Open Questions
- [ ] Video upload support in MVP?
- [ ] Product variants (sizes, colors) in MVP?
- [ ] Scheduled publishing feature?
- [ ] Listing templates for repeat sellers?

---

## 📎 Related Documents

- [PRD-002: Seller Management](PRD-002-Seller-Management.md)
- [PRD-004: Discovery & Search](PRD-004-Discovery-Search.md) (Next)
- [PRD-006: Moderation System](PRD-006-Moderation-System.md)
- [Marketplace Project Discussion](../../inbox/MARKETPLACE-PROJECT-DISCUSSION.md)

---

**Last Updated:** 18-11-2025  
**Next Review:** Upon stakeholder feedback  
**Document Owner:** Project Manager (GPT-5 Codex)




