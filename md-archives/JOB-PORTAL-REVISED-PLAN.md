# 🎯 MyOMR Job Portal - Revised Implementation Plan

## Following MyOMR Naming Conventions

---

## 📁 Folder Structure (ALIGNED WITH EXISTING STYLE)

### **Keep Using `/listings/` Folder** (Not `/jobs/`)

```
/listings/
  ├── post-job-omr.php                           # ✅ Already exists
  ├── search-and-post-jobs-job-vacancy-employment-platform-for-omr-chennai.php  # ✅ Already exists
  ├── job-detail-omr-chennai.php                 # 🆕 Individual job view
  ├── apply-job-omr-chennai.php                  # 🆕 Application form
  ├── employer-register-omr.php                   # 🆕 Employer signup
  ├── my-posted-jobs-omr.php                     # 🆕 Employer dashboard
  ├── view-applications-omr.php                   # 🆕 Application management
  └── process-job-omr.php                         # 🆕 Backend processor
```

---

## 📂 Naming Convention Rules (From Your Existing Files)

### **Pattern:**

```
[action]-[location]-[additional-descriptor].php

Examples:
✅ post-job-omr.php
✅ search-and-post-jobs-job-vacancy-employment-platform-for-omr-chennai.php
✅ rent-house-omr.php
✅ sell-property-omr.php
```

### **Rules:**

1. ✅ Use kebab-case (lowercase with hyphens)
2. ✅ Include location: "omr" or "omr-chennai"
3. ✅ Be descriptive and SEO-friendly
4. ✅ Use full phrases, not abbreviations
5. ✅ Start with action: "post", "search", "apply", "rent", "sell"
6. ✅ Place all in `/listings/` folder (existing structure)

---

## 🗄️ Database Tables (SAME AS BEFORE)

### **Keep SQL File: `CREATE-JOBS-DATABASE.sql`**

- Table names: `employers`, `job_postings`, `job_applications`, `job_categories`
- ✅ Follows MySQL conventions
- ✅ Already created and ready

---

## 📝 File Naming Examples (Following Your Pattern)

### **Main Features:**

```
✅ search-and-post-jobs-job-vacancy-employment-platform-for-omr-chennai.php  (Exists)
✅ post-job-omr.php                                                           (Exists)
✅ job-detail-omr-chennai.php                                                 (New)
✅ apply-job-omr-chennai.php                                                  (New)
✅ process-job-omr.php                                                         (New)
```

### **Employer Features:**

```
✅ employer-register-omr-chennai.php
✅ employer-login-omr.php
✅ my-posted-jobs-omr.php
✅ view-applications-omr.php
✅ edit-job-omr.php
```

### **Admin Features:**

```
✅ admin/manage-job-listings-omr.php
✅ admin/verify-employers-omr.php
✅ admin/job-analytics-omr.php
```

### **Assets:**

```
✅ assets/css/job-listings-omr.css
✅ assets/js/job-search-omr.js
```

---

## 🔄 Updated Workflow (Following Your Conventions)

### **Employer Workflow:**

```
1. Employer visits: /listings/post-job-omr.php
2. If not registered, redirects to: /listings/employer-register-omr-chennai.php
3. Fills employer form → Creates account
4. Returns to posting form
5. Fills job details → Submits
6. Goes to: /listings/my-posted-jobs-omr.php (dashboard)
```

### **Job Seeker Workflow:**

```
1. Job seeker visits: /listings/search-and-post-jobs-job-vacancy-employment-platform-for-omr-chennai.php
2. Clicks job → Goes to: /listings/job-detail-omr-chennai.php?id=123
3. Clicks Apply → Opens: /listings/apply-job-omr-chennai.php?job_id=123
4. Fills form → Submits
5. Redirects to success page
```

---

## 📋 Updated File Checklist

### **Core Pages (Following Your Naming):**

- [x] `/listings/post-job-omr.php` - Already exists
- [x] `/listings/search-and-post-jobs-job-vacancy-employment-platform-for-omr-chennai.php` - Already exists
- [ ] `/listings/job-detail-omr-chennai.php` - Needs creation
- [ ] `/listings/apply-job-omr-chennai.php` - Needs creation
- [ ] `/listings/employer-register-omr-chennai.php` - Needs creation

### **Backend:**

- [ ] `/listings/process-job-omr.php` - Needs creation
- [ ] `/listings/process-application-omr.php` - Needs creation
- [ ] `/listings/includes/job-functions-omr.php` - Needs creation

### **Employer Dashboard:**

- [ ] `/listings/my-posted-jobs-omr.php` - Needs creation
- [ ] `/listings/view-applications-omr.php` - Needs creation
- [ ] `/listings/edit-job-omr.php` - Needs creation

### **Admin Panel (Create admin subfolder in listings):**

- [ ] `/listings/admin/manage-job-listings-omr.php` - Needs creation
- [ ] `/listings/admin/verify-employers-omr.php` - Needs creation

### **Assets:**

- [ ] `/assets/css/job-listings-omr.css` - Needs creation
- [ ] `/assets/js/job-search-omr.js` - Needs creation

---

## ✅ Changes Made to Align with Your Standards

### **1. Folder Structure:**

- ❌ Removed: `/jobs/` folder
- ✅ Using: `/listings/` folder (existing)

### **2. Naming Convention:**

- ✅ All files: kebab-case
- ✅ All include location: "omr" or "omr-chennai"
- ✅ Descriptive, SEO-friendly names
- ✅ Full phrases, not abbreviations

### **3. Integration:**

- ✅ Build upon existing files
- ✅ Enhance, don't replace
- ✅ Follow existing patterns

---

## 🎯 Implementation Approach

### **Phase 1: Enhance Existing Files**

- Update `post-job-omr.php` with better form
- Update `search-and-post-jobs-job-vacancy-employment-platform-for-omr-chennai.php` to load from database

### **Phase 2: Create New Files**

- Create detail, apply, register pages
- Follow exact naming conventions

### **Phase 3: Backend Processing**

- Create process files following naming convention
- Handle form submissions

### **Phase 4: Admin & Assets**

- Create admin panel
- Create CSS/JS files

---

## 📊 Summary

**What Changed:**

- ✅ Folder: `/listings/` (not `/jobs/`)
- ✅ Naming: kebab-case with location
- ✅ Descriptive, SEO-friendly names
- ✅ Aligns with existing files

**What Stayed Same:**

- ✅ Database structure
- ✅ Functionality
- ✅ Workflows
- ✅ User roles

---

**Status:** Ready to implement with proper naming ✅
**Next Step:** Update existing files first, then create new ones following conventions
