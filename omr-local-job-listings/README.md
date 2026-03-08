# 🧭 MyOMR Local Job Listings – Repository Guide

_Last updated: 10 Nov 2025 • Status: Actively maintained_

This README is the living map for everything inside `omr-local-job-listings/`.  
Use it to understand how the public job board, employer tools, and admin operations fit together.

---

## 1. High-Level Workflow

| Stage | Stakeholder | Files / Scripts | Description |
| --- | --- | --- | --- |
| Browse Jobs | Job seekers | `index.php`, `job-detail-omr.php`, `assets/job-listings-omr.css`, `assets/job-search-omr.js` | Public landing page with filters & search; detail view shows structured data, OG tags, and inquiry CTA. |
| Post Job | Employers | `post-job-omr.php`, `process-job-omr.php`, `job-posted-success-omr.php` | Secure form with CSRF + validation. On submit, data is stored as `pending` until admin approval. |
| Manage Jobs | Employers | `employer-login-omr.php`, `my-posted-jobs-omr.php`, `edit-job-omr.php`, `update-application-status-omr.php` | Logged-in employers manage listings, edit postings, and review candidate applications. Sessions handled via `includes/employer-auth.php`. |
| Admin Ops | Internal team | `admin/manage-jobs-omr.php`, `admin/verify-employers-omr.php`, `admin/view-all-applications-omr.php` | Admins approve jobs, verify employers, and escalate reports. Views gated by `$_SESSION['admin_logged_in']`. |
| SEO & Outreach | Marketing / SEO | `generate-sitemap.php`, `assets/landing-page-analytics.js`, `includes/seo-helper.php` | Generates `sitemap.xml`, injects JSON-LD schemas, tracks conversions. |
| Maintenance & QA | Dev / QA | `DEPLOYMENT-CHECKLIST.md`, `HUMAN-TESTING-CHECKLIST.md`, `ERROR-DEBUG-GUIDE.md`, `READY-FOR-DEPLOYMENT.md` | Checklists and guides for regression testing, launch prep, and debugging. |

---

## 2. Folder & File Breakdown

```
omr-local-job-listings/
├── admin/                     # Admin dashboards and moderation tools
│   ├── index.php              # Overview dashboard
│   ├── manage-jobs-omr.php    # Approve / reject job postings
│   ├── verify-employers-omr.php
│   └── view-all-applications-omr.php
├── assets/                    # Frontend assets (CSS/JS)
│   ├── omr-jobs-unified-design.css
│   ├── job-listings-omr.css
│   ├── employer-dashboard.css # Dashboard styles (NEW)
│   ├── employer-dashboard.js  # Dashboard interactivity (NEW)
│   ├── post-job-form-modern.css
│   ├── job-search-omr.js
│   └── landing-page-analytics.js
├── includes/                  # Shared PHP helpers
│   ├── employer-auth.php      # Session management + guards
│   ├── employer-applicant-card.php # Applicant profile card component (NEW)
│   ├── job-functions-omr.php  # CRUD helpers, validation, pagination, filters
│   ├── landing-page-template.php
│   └── seo-helper.php         # Meta tags, canonical URLs, schema builders
├── DEPLOYMENT-CHECKLIST.md    # Step-by-step deployment SOP
├── DESIGN-UPDATE-SUMMARY.md   # UI/UX changes with dates
├── ERROR-DEBUG-GUIDE.md       # Known issues + fixes
├── FIXES-APPLIED.md           # Patch history (hotfix log)
├── HUMAN-TESTING-CHECKLIST.md # Manual QA flow
├── QUICK-START-GUIDE.md       # Onboarding notes for new devs
├── READY-FOR-DEPLOYMENT.md    # Final Go/No-Go criteria
├── generate-sitemap.php       # Builds job sitemap with clean URLs
├── robots.txt                 # Disallow rules for crawler hygiene
├── index.php                  # Jobs landing page (searchable list)
├── job-detail-omr.php         # Individual vacancy page (JobPosting schema)
├── post-job-omr.php           # Employer-facing job form
├── process-job-omr.php        # Form handler (writes to DB, sends email)
├── employer-register-omr.php  # Employer onboarding
├── employer-login-omr.php     # Auth portal
├── employer-landing-omr.php   # Marketing landing page for employers
├── employer-dashboard-omr.php # Unified applications dashboard (NEW - advanced filtering, bulk actions)
├── my-posted-jobs-omr.php     # Employer dashboard (job management view)
├── view-applications-omr.php  # Employer-side application list (job-specific)
├── process-application-omr.php# Handles new applications
├── update-application-status-omr.php
├── edit-job-omr.php           # Edit flow for existing jobs
├── application-submitted-omr.php
└── job-posted-success-omr.php # Thank-you / follow-up CTAs
```

**Test & Diagnostics Utilities**
- `test-connection.php`, `test-jobs.php`, `test-categories.php`, `debug-categories-direct.php` – used during integration to inspect DB state.
- `FIX-CATEGORIES.sql` – corrective script for taxonomy mismatches.

---

## 3. Data Model Cheat Sheet

| Table | Purpose | Key Columns | Related Files |
| --- | --- | --- | --- |
| `job_postings` | Stores all job listings | `id`, `employer_id`, `status`, `featured`, `published_at` | `process-job-omr.php`, `job-detail-omr.php`, `includes/job-functions-omr.php` |
| `employers` | Employer profiles & login credentials | `id`, `company_name`, `email`, `password_hash`, `status` | `employer-register-omr.php`, `includes/employer-auth.php`, `admin/verify-employers-omr.php` |
| `job_applications` | Candidate applications | `id`, `job_id`, `candidate_email`, `status`, `source` | `process-application-omr.php`, `view-applications-omr.php`, `update-application-status-omr.php` |
| `job_categories` | List of categories & slugs | `id`, `name`, `slug`, `is_active` | `includes/job-functions-omr.php`, `FIX-CATEGORIES.sql` |

> **Status Flags:** `job_postings.status` cycles `pending → approved → archived`. Admin actions reside in `admin/manage-jobs-omr.php`.

---

## 4. End-to-End Flow Summaries

### A. Job Posting Lifecycle
1. Employer registers (`employer-register-omr.php`) → auto email / pending status.
2. After login, they submit via `post-job-omr.php`.
3. `process-job-omr.php` validates, sanitizes, inserts record with `status='pending'`.
4. Admin reviews in `admin/manage-jobs-omr.php`; on approval, job appears publicly.
5. Employer edits via `edit-job-omr.php`; changes revert to `pending` for moderation if major fields are touched.

### B. Application Handling
1. Candidate applies through CTA (modal or `application-submitted-omr.php`).
2. `process-application-omr.php` stores entry, triggers email notifications.
3. Employer views applications via `employer-dashboard-omr.php` (unified view with advanced filtering, bulk actions) OR via `view-applications-omr.php` (job-specific view).
4. Status updates (shortlisted/rejected) go through `update-application-status-omr.php`; admins see full trail in `admin/view-all-applications-omr.php`.

### C. SEO / Analytics Loop
1. `includes/seo-helper.php` attaches per-page metadata, canonical tags, and JSON-LD (`JobPosting` for detail pages, `CollectionPage` for listings).
2. `generate-sitemap.php` builds job-specific sitemap consumed by Search Console.
3. `landing-page-analytics.js` + `job-analytics-events.js` fire GA events for conversions (form submits, CTAs).

---

## 5. Deployment & QA Guardrails

- **Before Deploy:** run through `DEPLOYMENT-CHECKLIST.md` and `READY-FOR-DEPLOYMENT.md`.
- **Manual QA:** follow `HUMAN-TESTING-CHECKLIST.md` (covers auth, CRUD, filters, mobile).
- **Known Fixes:** review `FIXES-APPLIED.md` + `DESIGN-UPDATE-SUMMARY.md` before making structural changes.
- **Debugging:** `ERROR-DEBUG-GUIDE.md` explains logging (`includes/error-reporting.php`) and fallback behaviours.

---

## 6. Quick Setup Reference

1. **Environment:** PHP ≥ 8.0, MySQL ≥ 5.7, Bootstrap 5 + Poppins font.
2. **Configuration:** Update DB credentials inside `core/omr-connect.php` (shared global).
3. **Database:** Import latest schema (`CREATE-JOBS-DATABASE.sql` lives in project root / docs).
4. **Permalinks:** `.htaccess` rewrites ensure clean URLs; verify on staging.
5. **Cron / Alerts:** Email notifications rely on `sendmail`. For staging, enable logging in `includes/error-reporting.php`.

---

## 7. Collaboration Notes

- Use this README as the authoritative map; update it whenever files move or workflow changes.
- Cross-reference `docs/worklogs/` for daily context and `HUMAN-TESTING-CHECKLIST.md` after major edits.
- When touching shared helpers (`includes/job-functions-omr.php`), document changes in `FIXES-APPLIED.md`.

---

**Maintainers:** Hari Krishnan & GPT-5 Codex  
Questions? Start with `QUICK-START-GUIDE.md`, then ping on worklog.  
_“Built with ❤️ for the OMR community.”_
