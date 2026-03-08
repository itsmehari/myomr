# 🎯 MyOMR Job Portal - Complete Implementation Plan

## 📋 Executive Summary

Create a **robust, standalone job portal** for OMR residents and businesses to post job vacancies and allow job seekers to search and apply for positions.

---

## 🏗️ Architecture Overview

### **Folder Structure:**

```
/jobs/
  ├── index.php                 # Main job listings page
  ├── post-job.php              # Post new job form
  ├── job-detail.php            # Individual job view
  ├── apply-job.php             # Apply for job form
  ├── my-applications.php       # View applications (job seekers)
  ├── my-job-postings.php       # Manage posted jobs (employers)
  ├── admin/
  │   ├── dashboard.php         # Admin overview
  │   ├── manage-jobs.php       # Approve/reject jobs
  │   └── view-applications.php # See all applications
  ├── includes/
  │   ├── job-functions.php     # Helper functions
  │   └── job-nav.php           # Navigation
  └── assets/
      └── jobs.css              # Job-specific styling
```

---

## 🗄️ Database Design

### **Table 1: `employers`**

Stores information about companies/individuals posting jobs.

```sql
CREATE TABLE employers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_name VARCHAR(200) NOT NULL,
    contact_person VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT,
    website VARCHAR(255),
    company_description TEXT,
    status ENUM('pending', 'verified', 'suspended') DEFAULT 'pending',
    verification_token VARCHAR(100),
    verified_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### **Table 2: `job_postings`**

Stores all job vacancy posts.

```sql
CREATE TABLE job_postings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employer_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    category VARCHAR(100) NOT NULL,  -- IT, Teaching, Healthcare, etc.
    job_type ENUM('Full-time', 'Part-time', 'Contract', 'Internship') NOT NULL,
    location VARCHAR(200) NOT NULL,
    salary_range VARCHAR(50),  -- e.g., "₹20,000 - ₹40,000"
    description TEXT NOT NULL,
    requirements TEXT,
    benefits TEXT,
    application_deadline DATE,
    status ENUM('pending', 'approved', 'rejected', 'closed') DEFAULT 'pending',
    featured BOOLEAN DEFAULT 0,
    views INT DEFAULT 0,
    applications_count INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employer_id) REFERENCES employers(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_category (category),
    INDEX idx_created (created_at)
);
```

### **Table 3: `job_applications`**

Stores applications submitted by job seekers.

```sql
CREATE TABLE job_applications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    job_id INT NOT NULL,
    applicant_name VARCHAR(100) NOT NULL,
    applicant_email VARCHAR(255) NOT NULL,
    applicant_phone VARCHAR(20) NOT NULL,
    applicant_resume TEXT,  -- File path or URL
    cover_letter TEXT,
    experience_years INT DEFAULT 0,
    status ENUM('pending', 'reviewed', 'shortlisted', 'rejected') DEFAULT 'pending',
    applied_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES job_postings(id) ON DELETE CASCADE,
    INDEX idx_job_id (job_id),
    INDEX idx_applicant_email (applicant_email)
);
```

### **Table 4: `job_categories`**

Pre-defined job categories.

```sql
CREATE TABLE job_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    is_active BOOLEAN DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert default categories
INSERT INTO job_categories (name, slug) VALUES
('Information Technology', 'it'),
('Teaching & Education', 'teaching-education'),
('Healthcare', 'healthcare'),
('Sales & Marketing', 'sales-marketing'),
('Construction', 'construction'),
('Hospitality', 'hospitality'),
('Finance & Accounting', 'finance-accounting'),
('Engineering', 'engineering'),
('Other', 'other');
```

---

## 👥 User Mapping & Roles

### **Role 1: Visitor/Browser**

- ✅ View job listings
- ✅ Search jobs by category/location
- ❌ Cannot post jobs or apply

### **Role 2: Job Seeker**

- ✅ View all job listings
- ✅ Search and filter jobs
- ✅ Apply for jobs (must provide contact details)
- ✅ View their own applications
- ❌ Cannot post jobs

### **Role 3: Employer**

- ✅ Post job vacancies (after employer registration)
- ✅ View applications for their posted jobs
- ✅ Update/close their job postings
- ✅ View application status
- ❌ Cannot approve their own applications

### **Role 4: Admin**

- ✅ Approve/reject job postings
- ✅ Verify employers
- ✅ View all applications
- ✅ Manage job categories
- ✅ Analytics dashboard
- ✅ Suspend employers or jobs

---

## 🔄 System Workflow

### **Workflow 1: Employer Posts Job**

```
1. Employer fills out registration form at /jobs/post-job.php
   └─> If not registered: Creates employer account
   └─> If registered: Logs in via email/phone verification

2. Employer fills job posting form
   ├─> Job Title
   ├─> Category (dropdown)
   ├─> Job Type (Full-time/Part-time/etc.)
   ├─> Location (with OMR context)
   ├─> Salary Range
   ├─> Description
   ├─> Requirements
   └─> Application Deadline

3. Form submits to process-job.php
   └─> Validates all fields
   └─> Creates employer record (if new)
   └─> Creates job_posting record with status='pending'

4. Admin gets notification
   └─> Admin reviews at /jobs/admin/manage-jobs.php
   └─> Approves → status='approved'
   └─> Job appears on /jobs/index.php

5. Employer can track their jobs
   └─> View at /jobs/my-job-postings.php
   └─> Edit/Close jobs
   └─> View applications received
```

### **Workflow 2: Job Seeker Applies**

```
1. Job seeker browses jobs at /jobs/index.php

2. Clicks on job card
   └─> Shows full details at /jobs/job-detail.php?id=123

3. Job seeker clicks "Apply Now"
   └─> Opens application form at /jobs/apply-job.php?job_id=123

4. Fills application form
   ├─> Name
   ├─> Email
   ├─> Phone
   ├─> Upload Resume (optional)
   ├─> Cover Letter
   └─> Years of Experience

5. Form submits to process-application.php
   └─> Validates fields
   └─> Stores in job_applications table
   └─> Sends email notification to employer
   └─> Sends confirmation to job seeker

6. Employer reviews applications
   └─> At /jobs/my-job-postings.php
   └─> Can see status: pending/reviewed/shortlisted/rejected
```

### **Workflow 3: Admin Management**

```
1. Admin logs into /jobs/admin/dashboard.php

2. Admin sees pending jobs
   └─> Reviews each job posting
   └─> Can approve/reject/edit

3. Admin verifies employers
   └─> Check contact details
   └─> Verify company legitimacy
   └─> Mark as 'verified'

4. Admin views analytics
   ├─> Total jobs posted
   ├─> Total applications
   ├─> Popular categories
   ├─> Pending approvals
   └─> Active employers
```

---

## 📐 Rules & Validation

### **Job Posting Rules:**

1. ✅ Title: Minimum 10 characters, maximum 200
2. ✅ Description: Minimum 50 characters
3. ✅ Location must include "OMR" or specific OMR locality
4. ✅ Application deadline must be future date
5. ✅ Salary range must follow format: "₹XX,XXX - ₹XX,XXX"
6. ✅ Employer must provide valid email and phone
7. ✅ Phone number must be 10 digits (Indian format)
8. ✅ Jobs expire after 90 days (auto-close)

### **Application Rules:**

1. ✅ One application per email per job
2. ✅ Email must be valid format
3. ✅ Phone must be 10 digits
4. ✅ Resume upload max 2MB, PDF/DOC only
5. ✅ Cover letter max 1000 characters

### **System Rules:**

1. ✅ All job postings require admin approval
2. ✅ Spam/suspicious jobs automatically flagged
3. ✅ Employers must be verified before featuring jobs
4. ✅ Job seekers receive confirmation email
5. ✅ Employers receive application notifications
6. ✅ Jobs automatically removed after deadline passes
7. ✅ Featured jobs appear first in listings
8. ✅ Search indexed by title, category, location

---

## 🎨 Features & Functionality

### **Frontend Features:**

- ✅ Responsive job listing cards
- ✅ Advanced search (category, location, job type, salary)
- ✅ Job detail page with full information
- ✅ Application form with resume upload
- ✅ Employer dashboard (my-postings)
- ✅ Application tracking for job seekers
- ✅ Email notifications
- ✅ Mobile-friendly design

### **Backend Features:**

- ✅ Data validation
- ✅ SQL injection protection (prepared statements)
- ✅ File upload security
- ✅ Email sending (SMTP)
- ✅ Admin dashboard
- ✅ Analytics tracking
- ✅ Job expiry automation
- ✅ Search indexing

### **Security Features:**

- ✅ SQL injection prevention
- ✅ XSS protection
- ✅ File upload validation
- ✅ Employer email verification
- ✅ Admin authentication
- ✅ Rate limiting for applications
- ✅ CAPTCHA on forms (optional)

---

## 📊 SEO & Analytics

### **SEO Features:**

- ✅ Meta tags per job
- ✅ Structured data (JobPosting schema)
- ✅ Clean URLs (/jobs/it-software-developer)
- ✅ Sitemap generation for jobs
- ✅ Social sharing (Open Graph)

### **Analytics:**

- ✅ Track job views
- ✅ Track application submissions
- ✅ Track popular categories
- ✅ Track employer activity
- ✅ Google Analytics integration

---

## 🚀 Implementation Phases

### **Phase 1: Database Setup** (30 min)

- Create all 4 tables
- Insert default categories
- Create admin account

### **Phase 2: Core Pages** (2 hours)

- /jobs/index.php - Job listings
- /jobs/post-job.php - Post form
- /jobs/job-detail.php - Individual job
- /jobs/apply-job.php - Application form

### **Phase 3: Backend Processing** (1 hour)

- process-job.php - Handle job posting
- process-application.php - Handle applications
- Email notification system

### **Phase 4: Employer Features** (1 hour)

- Employer registration/login
- my-job-postings.php
- Update/close jobs
- View applications

### **Phase 5: Admin Panel** (2 hours)

- Admin dashboard
- Manage jobs (approve/reject)
- Verify employers
- Analytics

### **Phase 6: Polish & Testing** (1 hour)

- CSS styling
- Mobile responsiveness
- Testing all flows
- Bug fixes

**Total Estimated Time: 6-8 hours**

---

## 📝 File Creation Checklist

### **Database:**

- [ ] Create tables: employers, job_postings, job_applications, job_categories
- [ ] Insert default categories
- [ ] Create admin user
- [ ] Add indexes for performance

### **Core Pages:**

- [ ] /jobs/index.php
- [ ] /jobs/post-job.php
- [ ] /jobs/job-detail.php
- [ ] /jobs/apply-job.php

### **Backend:**

- [ ] /jobs/process-job.php
- [ ] /jobs/process-application.php
- [ ] /jobs/includes/job-functions.php

### **Employer:**

- [ ] /jobs/my-job-postings.php
- [ ] /jobs/edit-job.php
- [ ] /jobs/view-applications.php

### **Admin:**

- [ ] /jobs/admin/dashboard.php
- [ ] /jobs/admin/manage-jobs.php
- [ ] /jobs/admin/employers.php

### **Assets:**

- [ ] /jobs/assets/jobs.css
- [ ] /jobs/includes/job-nav.php

### **Configuration:**

- [ ] Email templates
- [ ] .htaccess for clean URLs
- [ ] robots.txt
- [ ] Sitemap generation

---

## ✅ Success Metrics

- **Functionality:** ✅ Users can post jobs
- **Functionality:** ✅ Users can apply for jobs
- **Functionality:** ✅ Employers can manage jobs
- **Functionality:** ✅ Admin can approve/reject
- **UX:** ✅ Mobile-responsive design
- **SEO:** ✅ Jobs indexed by Google
- **Security:** ✅ No SQL injection/XSS
- **Performance:** ✅ Fast page loads (<2s)
- **Analytics:** ✅ Track conversions

---

## 🎯 Next Steps

1. ✅ **Approve this plan**
2. ✅ **Create database tables**
3. ✅ **Build core pages**
4. ✅ **Test with real data**
5. ✅ **Deploy to live site**

---

**Status:** Ready for implementation
**Priority:** High (User-requested feature)
**Effort:** 6-8 hours
**Dependencies:** None (standalone feature)
