# 🧪 Human Testing Checklist - MyOMR Job Portal

**Date:** October 29, 2025  
**Tester:** ******\_\_\_******  
**Testing Date:** ******\_\_\_******  
**Test Duration:** ******\_\_\_******

---

## 📋 Testing Overview

### **Test Environment:**

- **URL:** `https://myomr.in/omr-local-job-listings/`
- **Browser(s):** Chrome / Firefox / Safari / Edge
- **Device(s):** Desktop / Tablet / Mobile
- **Internet Connection:** ******\_\_\_******

---

## 🎯 User Journey 1: Job Seeker Flow

### **Task 1.1: Browse Jobs**

- [ ] Visit homepage: `/omr-local-job-listings/`
- [ ] Page loads correctly
- [ ] Job listings display properly
- [ ] Search bar visible and functional
- [ ] Filter options visible (category, location, job type)
- [ ] Pagination works (if more than 20 jobs)
- [ ] Job cards show all information (title, company, location, salary)

**Issues Found:**

---

---

---

### **Task 1.2: Search for Jobs**

- [ ] Enter search term (e.g., "Software Developer")
- [ ] Click search button
- [ ] Results filter correctly
- [ ] Results page shows relevant jobs
- [ ] "No results" message shows if no matches
- [ ] Can clear search and see all jobs

**Issues Found:**

---

---

---

### **Task 1.3: Apply Filters**

- [ ] Select category from dropdown
- [ ] Select location
- [ ] Select job type
- [ ] Apply filters
- [ ] Results update correctly
- [ ] Can clear filters
- [ ] Filter state persists in URL

**Issues Found:**

---

---

---

### **Task 1.4: View Job Details**

- [ ] Click on a job card
- [ ] Job detail page loads
- [ ] All job information displays correctly:
  - [ ] Job title
  - [ ] Company name
  - [ ] Location
  - [ ] Job type
  - [ ] Salary (if provided)
  - [ ] Full job description
  - [ ] Requirements
  - [ ] Benefits
  - [ ] Application deadline (if set)
- [ ] "Apply Now" button visible
- [ ] Related jobs section shows (if available)
- [ ] Share buttons work

**Issues Found:**

---

---

---

### **Task 1.5: Apply for Job**

- [ ] Click "Apply Now" button
- [ ] Application modal/form opens
- [ ] Form fields display correctly:
  - [ ] Name field
  - [ ] Email field
  - [ ] Phone field
  - [ ] Experience years
  - [ ] Cover letter (optional)
- [ ] Fill in all required fields
- [ ] Submit application
- [ ] Success message displays
- [ ] Redirect to confirmation page
- [ ] Confirmation page shows correct information

**Issues Found:**

---

---

---

### **Task 1.6: Test Duplicate Application**

- [ ] Try to ago apply for the same job with same email
- [ ] System prevents duplicate application
- [ ] Appropriate error message shows

**Issues Found:**

---

---

---

## 👔 User Journey 2: Employer Flow

### **Task 2.1: Post a Job**

- [ ] Click "Post a Job" button
- [ ] Navigate to post-job-omr.php
- [ ] Form loads correctly
- [ ] Fill in company information:
  - [ ] Company name
  - [ ] Contact person
  - [ ] Email
  - [ ] Phone
  - [ ] Address (optional)
- [ ] Fill in job details:
  - [ ] Job title
  - [ ] Category
  - [ ] Job type
  - [ ] Location
  - [ ] Salary range
  - [ ] Job description (at least 50 characters)
  - [ ] Requirements
  - [ ] Benefits
- [ ] Check terms and conditions
- [ ] Submit form
- [ ] Success page displays
- [ ] Job shows as "pending" status

**Issues Found:**

---

---

---

### **Task 2.2: Employer Login**

- [ ] Navigate to employer-login-omr.php
- [ ] Enter email address
- [ ] Click login
- [ ] Dashboard loads (my-posted-jobs-omr.php)
- [ ] Posted jobs list displays
- [ ] Can see job status (pending/approved/rejected)

**Issues Found:**

---

---

---

### **Task 2.3: View Posted Jobs**

- [ ] Dashboard shows all posted jobs
- [ ] Job information displays correctly:
  - [ ] Job title
  - [ ] Status badge
  - [ ] Application count
  - [ ] View count
  - [ ] Posted date
  - [ ] Application deadline
- [ ] Can click to view job details
- [ ] Can click to view applications (if any)

**Issues Found:**

---

---

---

### **Task 2.4: Logout**

- [ ] Click logout button
- [ ] Session ends correctly
- [ ] Redirected to login page
- [ ] Cannot access dashboard without login

**Issues Found:**

---

---

---

## 👨‍💼 User Journey 3: Admin Flow

### **Task 3.1: Admin Login**

- [ ] Login as admin (email: admin@myomr.in or set admin flag)
- [ ] Admin dashboard loads
- [ ] Statistics display correctly:
  - [ ] Pending jobs count
  - [ ] Approved jobs count
  - [ ] Total applications
  - [ ] Total employers

**Issues Found:**

---

---

---

### **Task 3.2: Approve/Reject Jobs**

- [ ] Navigate to manage-jobs-omr.php
- [ ] See list of all jobs (pending, approved, rejected)
- [ ] Click approve button for pending job
- [ ] Confirm approval
- [ ] Job status changes to "approved"
- [ ] Job appears on public listings
- [ ] Click reject button for pending job
- [ ] Confirm rejection
- [ ] Job status changes to "rejected"

**Issues Found:**

---

---

---

### **Task 3.3: View Applications**

- [ ] Navigate to view-all-applications-omr.php
- [ ] See all applications listed
- [ ] Application details display:
  - [ ] Applicant name
  - [ ] Email
  - [ ] Phone
  - [ ] Job title
  - [ ] Company
  - [ ] Applied date
  - [ ] Status
- [ ] Can click to view full application details
- [ ] Cover letter displays correctly

**Issues Found:**

---

---

---

## 📱 Mobile Testing

### **Task 4.1: Mobile Responsiveness**

- [ ] Test on mobile device (or browser responsive mode)
- [ ] Homepage displays correctly on mobile
- [ ] Search form stacks properly
- [ ] Job cards display correctly
- [ ] Job detail page readable
- [ ] Apply button visible and accessible
- [ ] Forms usable on mobile
- [ ] Navigation menu works
- [ ] Images scale properly
- [ ] Text is readable

**Device Tested:** ******\_\_\_******  
**Browser:** ******\_\_\_******  
**Issues Found:**

---

---

---

## ⚡ Performance Testing

### **Task 5.1: Page Load Speed**

- [ ] Homepage loads in < 3 seconds
- [ ] Job detail page loads in < 3 seconds
- [ ] Forms load quickly
- [ ] Images load properly
- [ ] No broken resources (404 errors)

**Load Times:**

- Homepage: **\_\_\_** seconds
- Job Detail: **\_\_\_** seconds
- Form Page: **\_\_\_** seconds

**Issues Found:**

---

---

---

## 🔍 SEO & Analytics Testing

### **Task 6.1: SEO Elements**

- [ ] Page titles are unique and descriptive
- [ ] Meta descriptions present
- [ ] Open Graph tags present (test with Facebook Debugger)
- [ ] Canonical URLs present
- [ ] Structured data present (test with Google Rich Results Test)

**Test URLs:**

- Facebook Debugger: https://developers.facebook.com/tools/debug/
- Google Rich Results: https://search.google.com/test/rich-results

**Issues Found:**

---

---

---

### **Task 6.2: Google Analytics**

- [ ] Check Google Analytics Real-Time reports
- [ ] Page views registering
- [ ] Events firing (after actions)
- [ ] No duplicate tracking codes
- [ ] Analytics loads correctly

**Issues Found:**

---

---

---

## ♿ Accessibility Testing

### **Task 7.1: Keyboard Navigation**

- [ ] Tab through all interactive elements
- [ ] Focus indicators visible
- [ ] Can access all features via keyboard
- [ ] Skip links work
- [ ] Forms navigable via keyboard

**Issues Found:**

---

---

---

### **Task 7.2: Screen Reader Testing**

- [ ] Test with screen reader (NVDA/JAWS/VoiceOver)
- [ ] All content is announced
- [ ] Form labels associated correctly
- [ ] ARIA labels present where needed
- [ ] Navigation understandable

**Screen Reader Used:** ******\_\_\_******  
**Issues Found:**

---

---

---

## 🔒 Security Testing

### **Task 8.1: Security Checks**

- [ ] SQL injection prevention (try SQL in search)
- [ ] XSS prevention (try script tags in forms)
- [ ] CSRF protection (forms have tokens)
- [ ] Session security (logout works)
- [ ] Admin panel protected

**Issues Found:**

---

---

---

## 🐛 Bug Reporting

### **Critical Bugs (Blocks Functionality):**

1. ***
2. ***
3. ***

### **Major Bugs (Significant Issues):**

1. ***
2. ***
3. ***

### **Minor Bugs (Cosmetic/Small Issues):**

1. ***
2. ***
3. ***

---

## ✅ Final Checklist

### **Before Signing Off:**

- [ ] All critical bugs fixed
- [ ] All major bugs documented
- [ ] Mobile testing completed
- [ ] Cross-browser testing completed (at least Chrome, Firefox)
- [ ] Performance acceptable
- [ ] Security verified
- [ ] Accessibility acceptable
- [ ] SEO verified
- [ ] Analytics working

---

## 📝 Tester Comments

**Overall Assessment:**

---

---

---

**Recommendation:**

- [ ] ✅ Ready for Production
- [ ] ⚠️ Needs Minor Fixes (list above)
- [ ] ❌ Needs Major Work (list above)

**Tester Signature:** ******\_\_\_******  
**Date:** ******\_\_\_******

---

**Last Updated:** October 29, 2025
