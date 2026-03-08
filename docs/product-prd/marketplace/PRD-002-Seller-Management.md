---
title: PRD-002 Seller Management
status: Draft
owner: Hari Krishnan
project-manager: GPT-5 Codex (AI)
created: 18-11-2025
updated: 18-11-2025
version: 1.0
document-type: PRD
tags:
  - omr-marketplace
  - seller-management
  - onboarding
  - prd
---

# PRD-002: Seller Management

> **Document Type:** Product Requirements Document (PRD)  
> **Status:** `Draft v1.0` | **Last Updated:** 18-11-2025  
> **Priority:** P0 (Critical - MVP)  
> **Related Documents:** [PRD-001 User Management](../marketplace/PRD-001-User-Management-Authentication.md) | [Marketplace Project Discussion](../../inbox/MARKETPLACE-PROJECT-DISCUSSION.md)

---

## 📋 Executive Summary

Seller Management enables users to become sellers on the marketplace. This PRD defines the seller onboarding process, account verification, subscription management, and seller dashboard features. Sellers can list products/services, manage inquiries, and track performance through a comprehensive dashboard.

### Key Objectives

1. Enable seamless seller onboarding with verification
2. Provide seller dashboard for listing and inquiry management
3. Implement subscription tier management (Starter/Featured/Premium)
4. Support seller verification and trust building
5. Track seller metrics and performance

---

## 🎯 User Stories

### US-002.1: Seller Account Creation
**As a** registered user,  
**I want to** create a seller account,  
**So that** I can list products/services on the marketplace.

**Acceptance Criteria:**
- User can access seller onboarding from profile menu
- Business profile form with required fields
- Email and phone verification already completed
- Policy acceptance required
- Seller account created after submission
- Onboarding wizard guides first listing creation

### US-002.2: Seller Profile Setup
**As a** new seller,  
**I want to** complete my business profile,  
**So that** buyers can trust my listings.

**Acceptance Criteria:**
- Business name, type, description fields
- Business address/location
- GST number (optional for MVP)
- Trade license (optional for MVP)
- Business hours (optional)
- Social media links (optional)
- Profile completion progress indicator

### US-002.3: Seller Verification
**As a** seller,  
**I want to** verify my business identity,  
**So that** I get a verified badge and increased trust.

**Acceptance Criteria:**
- Document upload for verification (GST, license, Aadhaar)
- Verification status tracked (pending/verified/rejected)
- Verified sellers get badge on listings
- Verification decision communicated via email
- Re-submission allowed if rejected

### US-002.4: Subscription Management
**As a** seller,  
**I want to** subscribe to a plan (Free/Featured/Premium),  
**So that** I can access listing limits and features.

**Acceptance Criteria:**
- View current subscription plan and features
- Upgrade/downgrade between tiers
- View subscription expiry date
- Automatic renewal option
- Payment gateway integration for paid plans
- Email confirmation on subscription changes

### US-002.5: Seller Dashboard
**As a** seller,  
**I want to** access my dashboard,  
**So that** I can manage listings, inquiries, and track performance.

**Acceptance Criteria:**
- Dashboard overview with key metrics
- Quick access to listings, inquiries, analytics
- Recent activity feed
- Performance summary (views, inquiries, response rate)
- Pending actions highlighted
- Mobile-responsive design

---

## 📋 Functional Requirements

### FR-002.1: Seller Onboarding Flow

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Users must complete seller onboarding to create listings. The flow includes business profile setup, policy acceptance, and initial listing creation guidance.

**Requirements:**
- Onboarding wizard with 4 steps:
  1. **Business Profile**: Name, type, description, address
  2. **Verification** (Optional): Document upload
  3. **Subscription Selection**: Choose plan (default: Starter Free)
  4. **First Listing** (Optional): Guided listing creation
- Progress indicator showing completion percentage
- Save draft functionality at each step
- Back/forward navigation between steps
- Skip optional steps with ability to complete later
- Welcome email sent upon completion
- Onboarding completion status tracked

**Technical Specifications:**
- Wizard implemented with JavaScript/Bootstrap modals or multi-step form
- Form validation at each step
- Data stored in `seller_accounts` table
- Integration with user management (users table)
- Redirect to dashboard upon completion

### FR-002.2: Business Profile Management

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Sellers can create and manage their business profile, which appears on all their listings and in seller directory.

**Requirements:**
- Profile fields:
  - Business Name (required, 2-160 characters)
  - Business Type (required): Individual/Retail/Service/NGO/Other
  - Business Description (required, 50-1000 characters)
  - Address (required): Street, Area, City, Pincode
  - Phone Number (auto-populated from user profile, editable)
  - Email (auto-populated, read-only)
  - GST Number (optional, 15 characters if provided)
  - Trade License (optional, 50 characters if provided)
  - Business Hours (optional): JSON format
  - Social Media Links (optional): Facebook, Instagram, WhatsApp
- Profile image upload (logo, max 2MB, JPG/PNG)
- Profile preview before saving
- Public profile page: `/marketplace/seller/{seller_id}`
- Profile updates logged in audit trail

**Technical Specifications:**
- Data stored in `seller_accounts` table
- Image storage: `marketplace/uploads/sellers/`
- Address geocoding (optional, Phase 2)
- Profile completeness score calculated
- Public profile page with listings list

### FR-002.3: Seller Verification System

**Priority:** P1 (High)  
**Status:** Planned

**Description:**
Sellers can submit documents for verification to earn a verified badge, increasing buyer trust.

**Requirements:**
- Verification document types:
  - GST Certificate
  - Aadhaar Card (individual sellers)
  - Trade License
  - PAN Card
  - Business Registration Certificate
- Document upload:
  - Multiple files per document type
  - File formats: PDF, JPG, PNG (max 5MB each)
  - Secure file storage
- Verification workflow:
  1. Seller submits documents
  2. Status: "pending"
  3. Admin reviews (manual process for MVP)
  4. Status: "verified" or "rejected" with reason
  5. Email notification sent to seller
- Verified badge:
  - Green checkmark icon on listings
  - "Verified Seller" text
  - Appears on profile page
- Re-submission:
  - Allowed if rejected
  - Previous submissions archived
  - New submission creates new verification record

**Technical Specifications:**
- Document storage: `marketplace/uploads/verifications/`
- Verification records in `seller_verifications` table
- Admin review interface in admin dashboard
- Document validation (file type, size)
- Secure file access (not directly accessible via URL)

### FR-002.4: Subscription Tier Management

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Sellers subscribe to plans (Starter/Featured/Premium) that determine listing limits and features.

**Requirements:**
- Subscription tiers:
  - **Starter** (Free):
    - 3 live listings max
    - Basic dashboard
    - Inquiry alerts
    - No featured placement
  - **Featured** (₹999/month):
    - 10 live listings max
    - Homepage placement
    - Badge highlighting
    - Basic analytics
  - **Premium** (₹2,499/month):
    - Unlimited listings
    - Category top spots
    - Advanced analytics
    - Dedicated support
- Subscription features:
  - View current plan and limits
  - Upgrade/downgrade between tiers
  - View subscription expiry date
  - Auto-renewal toggle
  - Payment history
  - Invoice download
- Plan enforcement:
  - Block listing creation if limit reached
  - Notify seller when approaching limit
  - Grace period after expiry (7 days)
  - Automatic downgrade to Starter if unpaid
- Payment integration:
  - Payment gateway: Razorpay/PayU/Stripe
  - Secure payment flow
  - Payment webhook handling
  - Failed payment retry mechanism

**Technical Specifications:**
- Subscription data in `plan_subscriptions` table
- Payment transactions in `payment_transactions` table
- Listing limit checks before creating new listing
- Cron job for subscription expiry checks
- Email notifications for payment success/failure
- Integration with payment gateway SDK

### FR-002.5: Seller Dashboard

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Comprehensive dashboard for sellers to manage listings, inquiries, and track performance.

**Requirements:**
- Dashboard sections:
  1. **Overview**:
     - Active listings count
     - Total inquiries (last 30 days)
     - Response rate
     - Pending moderation count
     - Subscription status
  2. **Listings**:
     - Quick view of all listings
     - Status filter (live/pending/paused/suspended)
     - Quick actions (edit, pause, view)
     - Create new listing button
  3. **Inquiries**:
     - Recent inquiries with status
     - Unanswered inquiries highlighted
     - Quick response actions
     - Inquiry count by status
  4. **Analytics** (Featured/Premium):
     - Listing views over time
     - Inquiry conversion rate
     - Popular categories
     - Performance trends
  5. **Settings**:
     - Business profile edit
     - Subscription management
     - Notification preferences
     - Account settings
- Real-time updates (new inquiries, listing status changes)
- Mobile-responsive design
- Quick action buttons throughout
- Performance metrics cards
- Recent activity timeline

**Technical Specifications:**
- Dashboard at `/marketplace/seller-dashboard/`
- Data fetched from multiple tables (listings, inquiries, metrics)
- AJAX updates for real-time notifications
- Cached metrics for performance (updated hourly)
- Protected route (seller role required)

### FR-002.6: Seller Metrics & Analytics

**Priority:** P1 (High - Featured/Premium Plans)  
**Status:** Planned

**Description:**
Sellers can track performance metrics to optimize listings and understand buyer behavior.

**Requirements:**
- Metrics tracked:
  - Total listings created
  - Active listings count
  - Total listing views
  - Inquiry count per listing
  - Average response time (minutes)
  - Response rate (percentage)
  - Dispute rate (percentage)
  - Conversion rate (inquiries to sales, user-reported)
- Analytics dashboard:
  - Views over time (line chart)
  - Inquiry trends (bar chart)
  - Top performing listings
  - Category performance
  - Geographic distribution of inquiries
- Reports:
  - Weekly performance summary (email)
  - Monthly detailed report (PDF export)
  - Custom date range analysis
- Data retention: 12 months
- Metrics calculated nightly (cron job)

**Technical Specifications:**
- Metrics stored in `seller_metrics` table
- Calculated via cron job or real-time triggers
- Chart rendering: Chart.js or Google Charts
- Report generation: PDF library (TCPDF/FPDF)
- Email delivery via existing mailer system

---

## 🗄️ Database Schema

### `seller_accounts` Table
```sql
CREATE TABLE seller_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    business_name VARCHAR(160) NOT NULL,
    business_type ENUM('individual', 'retail', 'service', 'ngo', 'other') NOT NULL,
    business_description TEXT NOT NULL,
    address TEXT NOT NULL,
    gst_number VARCHAR(20) NULL,
    trade_license VARCHAR(50) NULL,
    business_hours JSON NULL,
    social_links JSON NULL,
    logo_url VARCHAR(255) NULL,
    verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    verification_notes TEXT NULL,
    joined_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_verification_status (verification_status),
    INDEX idx_business_name (business_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### `seller_verifications` Table
```sql
CREATE TABLE seller_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    document_type ENUM('gst', 'aadhaar', 'pan', 'license', 'other') NOT NULL,
    document_path VARCHAR(255) NOT NULL,
    verified_by INT NULL,
    status ENUM('submitted', 'approved', 'rejected') DEFAULT 'submitted',
    notes TEXT NULL,
    reviewed_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES seller_accounts(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_seller_id (seller_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### `seller_metrics` Table
```sql
CREATE TABLE seller_metrics (
    seller_id INT PRIMARY KEY,
    total_listings INT DEFAULT 0,
    active_listings INT DEFAULT 0,
    total_views INT DEFAULT 0,
    total_inquiries INT DEFAULT 0,
    avg_response_minutes INT NULL,
    response_rate DECIMAL(5,2) DEFAULT 0.00,
    dispute_ratio DECIMAL(5,2) DEFAULT 0.00,
    last_calculated DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES seller_accounts(id) ON DELETE CASCADE,
    INDEX idx_updated (last_calculated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### `plan_subscriptions` Table
```sql
CREATE TABLE plan_subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    plan_code ENUM('starter', 'featured', 'premium') NOT NULL,
    status ENUM('active', 'trial', 'cancelled', 'expired') DEFAULT 'active',
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    auto_renew TINYINT(1) DEFAULT 1,
    last_payment_id VARCHAR(100) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES seller_accounts(id) ON DELETE CASCADE,
    INDEX idx_seller_id (seller_id),
    INDEX idx_status (status),
    INDEX idx_end_date (end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 🎨 UI/UX Requirements

### UR-002.1: Onboarding Wizard
- Multi-step form with clear progress indicator
- Step-by-step validation with helpful error messages
- Mobile-responsive design
- Save draft button on each step
- Exit and resume later functionality
- Success celebration animation on completion

### UR-002.2: Seller Dashboard
- Clean, organized layout with cards/sections
- Quick stats at top (KPI cards)
- Color-coded status indicators
- Action buttons prominently placed
- Loading states for async operations
- Empty states with helpful CTAs
- Mobile-optimized navigation

### UR-002.3: Business Profile Page
- Two-column layout (form left, preview right)
- Real-time preview of public profile
- Image upload with drag-and-drop
- Auto-save draft functionality
- Clear save/submit buttons

---

## 🧪 Testing Requirements

### TR-002.1: Onboarding Flow Tests
- Complete onboarding wizard end-to-end
- Draft save and resume functionality
- Form validation at each step
- Skip optional steps workflow
- Error handling for failed submissions

### TR-002.2: Subscription Management Tests
- Plan upgrade/downgrade flows
- Payment gateway integration
- Subscription expiry handling
- Listing limit enforcement
- Invoice generation

### TR-002.3: Dashboard Tests
- Dashboard data accuracy
- Real-time notification updates
- Mobile responsiveness
- Performance with large datasets
- Access control verification

---

## 📊 Success Metrics

### KPIs
- **Seller Onboarding Completion Rate**: Target >70%
- **Average Onboarding Time**: Target <10 minutes
- **Verification Approval Rate**: Target >60%
- **Subscription Conversion Rate** (Free to Paid): Target >20%
- **Dashboard Usage**: Target >80% daily active sellers

---

## 🗓️ Implementation Timeline

### Phase 1: MVP (Weeks 5-8)
- ✅ Seller onboarding wizard
- ✅ Business profile management
- ✅ Subscription tier system (Starter free tier)
- ✅ Basic seller dashboard
- ✅ Listing limit enforcement

### Phase 2: Enhancements (Weeks 9-12)
- Seller verification system
- Advanced analytics (Featured/Premium)
- Payment gateway integration
- Enhanced dashboard features
- Performance optimization

---

## 📝 Open Questions & Decisions

### Decisions Made
- ✅ Onboarding wizard for guided setup
- ✅ Free Starter tier for low barrier to entry
- ✅ Manual verification for MVP
- ✅ Basic analytics for Featured/Premium plans only

### Open Questions
- [ ] Auto-verification based on GST API?
- [ ] Seller rating/review system in MVP?
- [ ] Bulk listing import feature needed?
- [ ] Seller support chat integration?

---

## 📎 Related Documents

- [PRD-001: User Management](PRD-001-User-Management-Authentication.md)
- [PRD-003: Listing Management](PRD-003-Listing-Management.md) (Next)
- [Marketplace Project Discussion](../../inbox/MARKETPLACE-PROJECT-DISCUSSION.md)

---

**Last Updated:** 18-11-2025  
**Next Review:** Upon stakeholder feedback  
**Document Owner:** Project Manager (GPT-5 Codex)




