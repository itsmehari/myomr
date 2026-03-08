---
title: Employer Dashboard Redesign - Engineering Design Document
status: In Progress
owner: Hari Krishnan
created: 30-11-2025
updated: 30-11-2025
version: 1.0
document-type: Engineering Design Document
tags:
  - job-portal
  - employer-dashboard
  - ui-redesign
  - engineering-design
---

# 🎨 Employer Dashboard Redesign - Engineering Design Document

> **Document Type:** Engineering Design Document (EDD)  
> **Status:** `In Progress v1.0` | **Last Updated:** 30-11-2025  
> **Purpose:** Comprehensive engineering and design plan for redesigning the employer dashboard based on modern job portal references (QuikrJobs)  
> **Reference:** QuikrJobs employer application management interface

**Tags:** `job-portal` `employer-dashboard` `ui-redesign` `engineering-design`

---

## 📋 Table of Contents

1. [Executive Summary](#executive-summary)
2. [Requirements Analysis](#requirements-analysis)
3. [System Architecture](#system-architecture)
4. [UI/UX Design Specification](#uiux-design-specification)
5. [Database Schema Changes](#database-schema-changes)
6. [Component Breakdown](#component-breakdown)
7. [Implementation Plan](#implementation-plan)
8. [Dependencies & Refactoring](#dependencies--refactoring)
9. [Testing Strategy](#testing-strategy)
10. [Deployment Checklist](#deployment-checklist)

---

## 🎯 Executive Summary

### Project Overview

**Objective:** Redesign the employer dashboard to provide a modern, intuitive interface for managing job applications with advanced filtering, bulk actions, and enhanced candidate profiles.

**Current State:**
- Basic job listings dashboard (`my-posted-jobs-omr.php`)
- Separate application view per job (`view-applications-omr.php`)
- Limited filtering capabilities
- No bulk actions
- Basic candidate profile display

**Target State:**
- Unified applications dashboard with sidebar filters
- Advanced filtering (Locality, Education, Gender, Salary, Experience)
- Status-based navigation (All, Shortlisted, Matching Profiles)
- Bulk actions (SMS, Email, Download)
- Enhanced applicant profile cards
- Sorting options (VIP first, Recent first)
- Responsive, modern UI matching industry standards

**Reference Inspiration:** QuikrJobs employer dashboard interface

### Key Features

1. **Unified Applications View**
   - View all applications across jobs in one place
   - Job selector dropdown
   - Filter by job, status, date range

2. **Advanced Filtering Sidebar**
   - Candidate Status
   - Locality/Location
   - Education Level
   - Gender
   - Salary Range
   - Experience (in years)
   - Clear all filters option

3. **Status Categories Navigation**
   - All Applicants
   - Matching Profiles
   - Shortlisted Profiles

4. **Bulk Actions**
   - Select multiple applicants
   - Send SMS to selected
   - Send Email to selected
   - Download selected profiles (CSV/PDF)

5. **Enhanced Applicant Profiles**
   - Profile cards with avatar
   - Contact information (mobile, email icons)
   - Current salary display
   - Languages known
   - Experience details
   - Education details
   - Application timeline
   - Quick action buttons (Shortlist, Reject, View Contact)

6. **Sorting & Pagination**
   - Sort by: VIP first, Recent first
   - Results per page selector (10, 20, 50)
   - Pagination controls

---

## 📊 Requirements Analysis

### Functional Requirements

| ID | Requirement | Priority | Status |
|----|-------------|----------|--------|
| FR-001 | Unified applications dashboard showing all applicants across jobs | P0 | Planned |
| FR-002 | Job selector dropdown to filter by specific job posting | P0 | Planned |
| FR-003 | Sidebar filter panel with collapsible sections | P0 | Planned |
| FR-004 | Filter by candidate status (Pending, Reviewed, Shortlisted, Rejected) | P0 | Planned |
| FR-005 | Filter by locality/location | P1 | Planned |
| FR-006 | Filter by education level | P1 | Planned |
| FR-007 | Filter by gender | P1 | Planned |
| FR-008 | Filter by salary range | P1 | Planned |
| FR-009 | Filter by experience (in years) | P1 | Planned |
| FR-010 | Status category navigation (All, Shortlisted, Matching) | P0 | Planned |
| FR-011 | Bulk selection checkbox for each applicant | P0 | Planned |
| FR-012 | Bulk SMS action | P1 | Planned |
| FR-013 | Bulk Email action | P0 | Planned |
| FR-014 | Bulk Download action (CSV export) | P1 | Planned |
| FR-015 | Individual applicant profile cards with enhanced information | P0 | Planned |
| FR-016 | Quick action buttons per applicant (Shortlist, Reject, Email, SMS, View Contact) | P0 | Planned |
| FR-017 | Sorting options (VIP first, Recent first) | P0 | Planned |
| FR-018 | Results per page selector | P1 | Planned |
| FR-019 | Pagination controls | P0 | Planned |
| FR-020 | Application timeline per candidate | P1 | Planned |

### Non-Functional Requirements

| ID | Requirement | Priority | Target |
|----|-------------|----------|--------|
| NFR-001 | Page load time < 2 seconds | P0 | < 2s |
| NFR-002 | Mobile responsive design | P0 | All screen sizes |
| NFR-003 | Browser compatibility (Chrome, Firefox, Safari, Edge) | P0 | Latest 2 versions |
| NFR-004 | Accessibility (WCAG 2.1 Level AA) | P1 | Level AA |
| NFR-005 | Filter performance with 1000+ applications | P0 | < 500ms |
| NFR-006 | Bulk actions handle 50+ selections | P0 | < 5s processing |

---

## 🏗️ System Architecture

### Page Structure

```
/omr-local-job-listings/
├── employer-dashboard-omr.php (NEW - Main unified dashboard)
├── view-applications-omr.php (REFACTORED - Keep for job-specific view)
├── my-posted-jobs-omr.php (KEEP - Job management view)
├── includes/
│   ├── employer-auth.php (EXISTING)
│   ├── employer-dashboard-filters.php (NEW)
│   └── employer-applicant-card.php (NEW)
├── assets/
│   ├── employer-dashboard.css (NEW)
│   ├── employer-dashboard.js (NEW)
│   └── employer-filters.js (NEW)
└── api/
    ├── filter-applications.php (NEW - AJAX endpoint)
    ├── bulk-actions.php (NEW - AJAX endpoint)
    └── update-application-status.php (NEW - AJAX endpoint)
```

### Data Flow

```
User Request
    ↓
employer-dashboard-omr.php
    ↓
Load Employer Jobs + Applications
    ↓
Apply Filters (Server-side or Client-side)
    ↓
Render Filtered Applications
    ↓
User Actions (Filter, Sort, Bulk Actions)
    ↓
AJAX Calls to API Endpoints
    ↓
Update Database
    ↓
Return Updated Results
```

---

## 🎨 UI/UX Design Specification

### Layout Structure

```
┌─────────────────────────────────────────────────────────────┐
│ Header: "Job Applications" + Job Selector + Action Buttons  │
├──────────────┬──────────────────────────────────────────────┤
│              │ Sort Options + Results per Page              │
│   SIDEBAR    ├──────────────────────────────────────────────┤
│              │                                               │
│  Filters:    │         APPLICANT PROFILE CARDS              │
│  - Status    │                                               │
│  - Locality  │  ┌─────────────────────────────────────┐    │
│  - Education │  │ ☐ [Avatar] Applicant Name           │    │
│  - Gender    │  │    19 years • Marketing • Location  │    │
│  - Salary    │  │    [Send SMS] [Send Email] [Contact]│    │
│  - Experience│  │    Applied: 28 Nov 2025             │    │
│              │  │    [Shortlist] [Reject]             │    │
│  Status Nav: │  └─────────────────────────────────────┘    │
│  - All       │                                               │
│  - Matching  │  ┌─────────────────────────────────────┐    │
│  - Shortlist │  │ ☐ [Avatar] Applicant Name           │    │
│              │  │    ...                               │    │
│  [Clear All] │  └─────────────────────────────────────┘    │
│              │                                               │
│              │              [Pagination]                     │
└──────────────┴──────────────────────────────────────────────┘
```

### Color Scheme

- **Primary Color:** `#008552` (MyOMR Green)
- **Secondary Color:** `#006d42` (Dark Green)
- **Success:** `#28a745`
- **Warning:** `#ffc107`
- **Danger:** `#dc3545`
- **Info:** `#17a2b8`
- **Background:** `#f9fafb`
- **Card Background:** `#ffffff`
- **Border:** `#e5e7eb`

### Typography

- **Font Family:** Poppins (primary)
- **Heading 1:** 24px, Bold (700)
- **Heading 2:** 20px, Semi-bold (600)
- **Body:** 16px, Regular (400)
- **Small Text:** 14px, Regular (400)
- **Label:** 12px, Medium (500), Uppercase

### Component Specifications

#### 1. Sidebar Filters

- **Width:** 280px (desktop), Full width (mobile)
- **Background:** White
- **Border:** Right border, 1px solid #e5e7eb
- **Sections:** Collapsible accordion
- **Filter Controls:**
  - Dropdowns for status, gender
  - Checkboxes for locality, education
  - Range sliders for salary, experience
  - Input fields for text search

#### 2. Applicant Profile Card

- **Dimensions:** Full width, auto height
- **Padding:** 20px
- **Margin:** 12px bottom
- **Border:** 1px solid #e5e7eb
- **Border Radius:** 8px
- **Shadow:** 0 2px 4px rgba(0,0,0,0.1)
- **Hover Effect:** Shadow elevation + border color change

**Card Sections:**
1. Header: Checkbox + Avatar + Name + Age + Role + Location
2. Contact Info: Mobile icon, Email icon, Contact availability
3. Details: Current salary, Languages, Education, Experience
4. Meta: Application date, Timeline status
5. Actions: Shortlist, Reject, Send SMS, Send Email, View Contact

#### 3. Status Categories Navigation

- **Style:** Tab navigation or icon buttons
- **Active State:** Underline/border + color change
- **Icons:** 
  - All: Users icon
  - Matching: Two users icon
  - Shortlisted: Star + user icon

#### 4. Bulk Actions Bar

- **Position:** Top of applicant list
- **Visibility:** Only when items selected
- **Actions:** SMS, Email, Download buttons
- **Count Badge:** "X selected"

#### 5. Sorting Controls

- **Layout:** Horizontal row
- **Controls:** Radio buttons for sort options
- **Dropdown:** Results per page selector

---

## 🗄️ Database Schema Changes

### Current Schema Analysis

**Table: `job_applications`**

Current columns (based on code analysis):
- `id`
- `job_id`
- `applicant_name`
- `applicant_email`
- `applicant_phone`
- `applicant_resume` (file path)
- `cover_letter`
- `experience_years`
- `status` (pending, reviewed, shortlisted, rejected)
- `applied_at`
- `updated_at`

### Required Schema Additions

**New Columns to Add:**

```sql
ALTER TABLE job_applications
ADD COLUMN applicant_age INT NULL AFTER applicant_phone,
ADD COLUMN applicant_gender ENUM('Male', 'Female', 'Other', 'Prefer not to say') NULL AFTER applicant_age,
ADD COLUMN applicant_locality VARCHAR(200) NULL AFTER applicant_gender,
ADD COLUMN applicant_education VARCHAR(200) NULL AFTER applicant_locality,
ADD COLUMN applicant_current_salary DECIMAL(10,2) NULL AFTER applicant_education,
ADD COLUMN applicant_languages VARCHAR(255) NULL AFTER applicant_current_salary,
ADD COLUMN is_vip BOOLEAN DEFAULT 0 AFTER status,
ADD COLUMN contact_preference ENUM('Mobile', 'Email', 'Both') DEFAULT 'Both' AFTER is_vip,
ADD COLUMN timeline_status VARCHAR(255) NULL AFTER contact_preference;

-- Add indexes for filtering performance
ALTER TABLE job_applications
ADD INDEX idx_status (status),
ADD INDEX idx_locality (applicant_locality),
ADD INDEX idx_education (applicant_education),
ADD INDEX idx_gender (applicant_gender),
ADD INDEX idx_salary (applicant_current_salary),
ADD INDEX idx_experience (experience_years),
ADD INDEX idx_applied_at (applied_at),
ADD INDEX idx_is_vip (is_vip);
```

**Note:** These fields should be collected during application submission. For existing records, we'll need to:
1. Backfill data where possible
2. Allow manual editing by employers
3. Show "Not specified" for missing data

### Optional: Enhanced Application Tracking

```sql
CREATE TABLE application_timeline (
    id INT PRIMARY KEY AUTO_INCREMENT,
    application_id INT NOT NULL,
    action VARCHAR(50) NOT NULL, -- 'applied', 'viewed', 'shortlisted', 'rejected', 'contacted'
    performed_by ENUM('employer', 'system') DEFAULT 'system',
    notes TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES job_applications(id) ON DELETE CASCADE,
    INDEX idx_application_id (application_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
);
```

---

## 🧩 Component Breakdown

### 1. Main Dashboard Page

**File:** `employer-dashboard-omr.php`

**Responsibilities:**
- Authenticate employer
- Load all jobs for employer
- Load applications based on selected job/filter
- Render page layout with sidebar and main content
- Handle job selector change
- Initialize JavaScript components

**Dependencies:**
- `includes/employer-auth.php`
- `includes/employer-dashboard-filters.php`
- `includes/employer-applicant-card.php`
- `assets/employer-dashboard.css`
- `assets/employer-dashboard.js`

### 2. Filter Sidebar Component

**File:** `includes/employer-dashboard-filters.php`

**Responsibilities:**
- Render filter sidebar HTML
- Handle filter state
- Emit filter change events
- Clear all filters functionality

**Props/Parameters:**
- Current filter values
- Available options (localities, education levels, etc.)
- Filter change callback

### 3. Applicant Card Component

**File:** `includes/employer-applicant-card.php`

**Responsibilities:**
- Render individual applicant profile card
- Display all applicant information
- Handle action button clicks
- Show checkbox for bulk selection

**Props/Parameters:**
- Applicant data array
- Job details
- Action callbacks

### 4. JavaScript Controller

**File:** `assets/employer-dashboard.js`

**Responsibilities:**
- Handle filter changes
- Make AJAX calls for filtering
- Manage bulk selection state
- Handle bulk actions
- Handle sorting
- Handle pagination
- Update UI dynamically

### 5. Filter Logic JavaScript

**File:** `assets/employer-filters.js`

**Responsibilities:**
- Filter validation
- Filter combination logic
- Client-side filtering (for small datasets)
- Filter state management

### 6. API Endpoints

**File:** `api/filter-applications.php`

**Responsibilities:**
- Accept filter parameters
- Query database with filters
- Return JSON response with applications
- Handle pagination
- Handle sorting

**File:** `api/bulk-actions.php`

**Responsibilities:**
- Accept bulk action request
- Process SMS/Email/Download
- Return status response
- Log bulk actions

**File:** `api/update-application-status.php`

**Responsibilities:**
- Update application status
- Create timeline entry
- Send notifications
- Return updated application data

---

## 📋 Implementation Plan

### Phase 1: Database & Backend Foundation (Day 1-2)

1. **Database Schema Updates**
   - Add new columns to `job_applications` table
   - Create indexes for performance
   - Optional: Create `application_timeline` table
   - Backfill existing data where possible

2. **Update Application Form**
   - Modify `process-application-omr.php`
   - Add new fields to application form
   - Update validation logic
   - Store new fields in database

3. **Create API Endpoints**
   - `api/filter-applications.php`
   - `api/bulk-actions.php`
   - `api/update-application-status.php`
   - Add authentication checks
   - Add error handling

### Phase 2: Core Components (Day 3-4)

1. **Create Filter Sidebar Component**
   - `includes/employer-dashboard-filters.php`
   - Render all filter sections
   - Handle collapsible sections
   - Emit filter events

2. **Create Applicant Card Component**
   - `includes/employer-applicant-card.php`
   - Design card layout
   - Display all applicant information
   - Add action buttons

3. **Create Main Dashboard Page**
   - `employer-dashboard-omr.php`
   - Integrate sidebar and cards
   - Add job selector
   - Add bulk actions bar

### Phase 3: Frontend Logic (Day 5-6)

1. **JavaScript Controller**
   - `assets/employer-dashboard.js`
   - Filter change handlers
   - AJAX integration
   - Bulk selection logic
   - Sorting logic
   - Pagination logic

2. **Filter JavaScript**
   - `assets/employer-filters.js`
   - Client-side filtering
   - Filter state management
   - Validation

3. **Styling**
   - `assets/employer-dashboard.css`
   - Implement design specifications
   - Responsive breakpoints
   - Hover effects
   - Loading states

### Phase 4: Integration & Refactoring (Day 7-8)

1. **Update Existing Pages**
   - Refactor `view-applications-omr.php` to use new components
   - Update `my-posted-jobs-omr.php` to link to new dashboard
   - Ensure consistency across pages

2. **Update Navigation**
   - Add dashboard link to employer menu
   - Update breadcrumbs
   - Add back navigation

3. **Email/SMS Integration**
   - Integrate bulk email functionality
   - Integrate SMS service (if available)
   - Add email templates

### Phase 5: Testing & Polish (Day 9-10)

1. **Functional Testing**
   - Test all filters
   - Test bulk actions
   - Test sorting and pagination
   - Test on different screen sizes

2. **Performance Testing**
   - Test with large datasets (1000+ applications)
   - Optimize queries
   - Add caching where needed

3. **UI/UX Polish**
   - Refine spacing and typography
   - Add loading indicators
   - Add empty states
   - Add error messages
   - Accessibility improvements

---

## 🔄 Dependencies & Refactoring

### Files to Create

1. `omr-local-job-listings/employer-dashboard-omr.php` - **NEW**
2. `omr-local-job-listings/includes/employer-dashboard-filters.php` - **NEW**
3. `omr-local-job-listings/includes/employer-applicant-card.php` - **NEW**
4. `omr-local-job-listings/assets/employer-dashboard.css` - **NEW**
5. `omr-local-job-listings/assets/employer-dashboard.js` - **NEW**
6. `omr-local-job-listings/assets/employer-filters.js` - **NEW**
7. `omr-local-job-listings/api/filter-applications.php` - **NEW**
8. `omr-local-job-listings/api/bulk-actions.php` - **NEW**
9. `omr-local-job-listings/api/update-application-status.php` - **NEW**

### Files to Modify

1. `omr-local-job-listings/view-applications-omr.php` - **REFACTOR** (use new components)
2. `omr-local-job-listings/my-posted-jobs-omr.php` - **UPDATE** (add link to new dashboard)
3. `omr-local-job-listings/process-application-omr.php` - **UPDATE** (add new fields)
4. `omr-local-job-listings/post-job-omr.php` - **CHECK** (if application form needs updates)

### Database Changes

1. ALTER TABLE `job_applications` - Add new columns
2. CREATE INDEXES - Performance optimization
3. Optional: CREATE TABLE `application_timeline`

### External Dependencies

1. **Bootstrap 5** - Already included
2. **Font Awesome** - Already included
3. **jQuery** - Consider for AJAX (or use vanilla JS)
4. **SMS Service** - Need to integrate (Twilio, MSG91, etc.)
5. **Email Service** - Already have `core/email.php`

### Breaking Changes

- None expected (backward compatible with existing applications)
- New fields are nullable, so existing records won't break

---

## 🧪 Testing Strategy

### Unit Tests

- Filter logic functions
- Date formatting functions
- Status badge rendering
- Card component rendering

### Integration Tests

- API endpoint responses
- Database queries with filters
- Bulk action processing
- Status updates

### Manual Testing Checklist

- [ ] Login as employer
- [ ] View dashboard with no applications
- [ ] View dashboard with multiple applications
- [ ] Filter by job
- [ ] Filter by status
- [ ] Filter by locality
- [ ] Filter by education
- [ ] Filter by gender
- [ ] Filter by salary range
- [ ] Filter by experience
- [ ] Combine multiple filters
- [ ] Clear all filters
- [ ] Switch status categories
- [ ] Select single applicant (checkbox)
- [ ] Select multiple applicants
- [ ] Bulk email action
- [ ] Bulk SMS action (if available)
- [ ] Bulk download action
- [ ] Sort by VIP first
- [ ] Sort by recent first
- [ ] Change results per page
- [ ] Navigate pagination
- [ ] Shortlist applicant
- [ ] Reject applicant
- [ ] Send email to individual
- [ ] Send SMS to individual
- [ ] View contact details
- [ ] View on mobile device
- [ ] View on tablet
- [ ] Test with 1000+ applications

### Performance Tests

- Load time < 2 seconds
- Filter response time < 500ms
- Bulk action processing < 5s for 50 items

---

## 🚀 Deployment Checklist

### Pre-Deployment

- [ ] Database schema changes executed
- [ ] All new files created
- [ ] All existing files updated
- [ ] Code review completed
- [ ] Testing completed
- [ ] Performance validated

### Deployment Steps

1. Backup database
2. Execute database migrations
3. Upload new files
4. Update existing files
5. Test in production environment
6. Monitor for errors

### Post-Deployment

- [ ] Verify dashboard loads
- [ ] Test filters functionality
- [ ] Test bulk actions
- [ ] Monitor error logs
- [ ] Collect user feedback
- [ ] Make iterative improvements

---

## 📝 Notes & Considerations

### Future Enhancements

1. **Advanced Search**
   - Full-text search across all fields
   - Saved filter presets
   - Export filtered results

2. **Communication Features**
   - In-app messaging
   - Interview scheduling
   - Status change notifications

3. **Analytics**
   - Application metrics dashboard
   - Time-to-hire tracking
   - Source of applications

4. **Candidate Matching**
   - AI-powered matching algorithm
   - Skills-based matching
   - Compatibility scoring

### Technical Debt

- Consider migrating to a modern framework (React/Vue) in future
- Implement proper state management
- Add comprehensive error handling
- Add comprehensive logging

---

**Document Status:** In Progress  
**Next Steps:** Begin Phase 1 implementation (Database & Backend Foundation)

