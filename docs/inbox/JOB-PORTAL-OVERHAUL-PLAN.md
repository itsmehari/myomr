# OMR Local Job Listings — Complete Overhaul Plan

**Created:** March 2026  
**Trigger:** https://myomr.in/omr-local-job-listings/ not loading  
**Scope:** Entire `omr-local-job-listings/` tree + dependencies  
**Model:** index.php modular structure (page-bootstrap, head-includes, omr_nav, omr_footer)

---

## Executive Summary

The job portal page is not loading. Root causes may include:
1. **display_errors Off** (`.htaccess`) + fatal PHP error = blank page
2. **DB connection** — credentials, server, or missing tables
3. **Path resolution** — `$_SERVER['DOCUMENT_ROOT']` on shared hosting
4. **Missing/inactive job_categories** — query fails
5. **error-reporting.php** — ob_start + custom handlers may mask or alter output

This plan: fix immediate issues (Phase 1), migrate to modular structure (Phase 2), audit code (Phase 3), audit content (Phase 4).

---

## File Tree (omr-local-job-listings/)

```
omr-local-job-listings/
├── index.php                    # Main listings (NOT LOADING)
├── job-detail-omr.php           # Single job view
├── post-job-omr.php             # Post job form
├── edit-job-omr.php             # Edit job
├── process-job-omr.php          # Form handler
├── process-application-omr.php  # Application handler
├── employer-*.php               # Register, login, dashboard, landing, logout
├── my-posted-jobs-omr.php
├── view-applications-omr.php
├── application-submitted-omr.php
├── job-posted-success-omr.php
├── edit-employer-profile-omr.php
├── update-application-status-omr.php
├── download-resume-omr.php
├── generate-sitemap.php
├── .htaccess
├── robots.txt
├── assets/
│   ├── job-portal-2026.css
│   ├── job-portal-2026.js
│   ├── job-listings-omr.css
│   ├── omr-jobs-unified-design.css
│   ├── post-job-form-modern.css
│   ├── job-search-omr.js
│   ├── job-ajax-search.js
│   ├── landing-page-analytics.js
│   ├── employer-dashboard.css
│   ├── employer-dashboard.js
│   └── job-analytics-events.js
├── includes/
│   ├── error-reporting.php
│   ├── job-functions-omr.php
│   ├── seo-helper.php
│   ├── employer-auth.php
│   ├── landing-page-template.php
│   ├── sidebar-filters.php
│   ├── employer-applicant-card.php
│   ├── save-job.php
│   └── save-job-alert.php
├── admin/
│   ├── index.php
│   ├── manage-jobs-omr.php
│   ├── view-all-applications-omr.php
│   └── verify-employers-omr.php
├── api/
│   └── jobs.php
├── cron/
│   └── expire-jobs.php
├── uploads/resumes/
├── test-*.php                   # test-connection, test-jobs, test-categories, debug-categories-direct
└── FIX-CATEGORIES.sql, *.md
```

---

## Phase 1: Bug Fixes — Get Page Loading (Priority) ✅ DONE

**Applied 2026-03-10:**
- Removed `display_errors Off` from `omr-local-job-listings/.htaccess` so error-reporting.php controls it
- Added try/catch around data fetch in index.php with safe defaults on exception
- Hardened getJobListings/getJobCount in job-functions-omr.php: check execute() and get_result()
- Added skip-link.php for accessibility
- Error log: weblog/job-portal-errors.log (unchanged)

### 1.1 Diagnostic Checklist

| Check | Action |
|-------|--------|
| Run `test-connection.php` | Visit `/omr-local-job-listings/test-connection.php` to verify DB + tables |
| Check error log | `/weblog/job-portal-errors.log` or server error log |
| Verify tables | `employers`, `job_postings`, `job_applications`, `job_categories` |
| job_categories active | `UPDATE job_categories SET is_active = 1;` if needed |
| display_errors | Temporarily enable in index.php for debugging: `ini_set('display_errors',1);` at top (remove after fix) |

### 1.2 Likely Fixes

1. **Graceful DB failure** — If `omr-connect` fails, page dies. Ensure `job-functions` return [] when DB down so page still renders (possibly with "No jobs" message).
2. **Remove/fix .htaccess display_errors** — `php_value display_errors Off` in omr-local-job-listings/.htaccess can hide errors. Consider removing so error-reporting.php can control it, or ensure DEVELOPMENT_MODE is false in production and errors go to log.
3. **Path fallback** — Ensure `$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..'` works on server. Some hosts have DOCUMENT_ROOT unset.
4. **getJobCategories()** — If table missing or empty, wrap in try/catch or check before use; avoid fatal.

### 1.3 Immediate Code Changes (Phase 1)

- Add error-surfacing at top of index.php (temporary): `ini_set('display_errors',1); error_reporting(E_ALL);` for debugging.
- Ensure `getJobCategories()` returns `[]` on failure (already may do; verify).
- Add fallback when `$jobs`/`$categories` are empty so page renders.
- Check `core/omr-connect.php` — ensure production doesn’t expose credentials; use env/config if available.

---

## Phase 2: Modular Structure Migration (In Progress)

**Applied 2026-03-10:**
- index.php: Added skip-link; try/catch + safe defaults
- job-detail-omr.php: ROOT_PATH bootstrap, omr_nav, omr_footer, skip-link, fixed breadcrumb home link
- post-job-omr.php: ROOT_PATH bootstrap, omr_nav, omr_footer, skip-link, ROOT_PATH analytics, fixed terms link
- landing-page-template.php: ROOT_PATH, component-includes, omr_nav, omr_footer, fixed asset paths

**Phase 3 (partial):**
- employer-dashboard-omr.php: Replaced real_escape_string with whitelists for status/category/sort

### 2.1 Target Pattern (from index.php)

```php
// Bootstrap
$page_nav = 'main';
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

// Head
require_once ROOT_PATH . '/components/meta.php';   // if page-specific meta
require_once ROOT_PATH . '/components/head-includes.php';  // or custom head for job portal

// Body
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav(); ?>
<main id="main-content">...</main>
<?php omr_footer(); ?>
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
<?php include ROOT_PATH . '/components/sdg-badge.php'; ?>
```

### 2.2 Job Portal CSS Stack

Job portal uses Bootstrap 5 + custom CSS. Options:
- **A:** Add `$omr_css_job_portal = true` to head-includes and load job-portal-2026.css via that.
- **B:** Keep job portal head custom but use `page-bootstrap` + `omr_nav`/`omr_footer`; head remains job-specific.

**Recommendation:** Use page-bootstrap + omr_nav/omr_footer. Head stays job-specific (Bootstrap 5, job CSS) until head-includes supports a job-portal flag.

### 2.3 Migration Order

| Order | File | Changes |
|-------|------|---------|
| 1 | index.php | Already has page-bootstrap, omr_nav, omr_footer. Add meta.php, skip-link, sdg-badge, js-includes. Switch analytics to head-includes or keep include. |
| 2 | job-detail-omr.php | page-bootstrap, ROOT_PATH includes, omr_nav, omr_footer |
| 3 | post-job-omr.php | Same |
| 4 | edit-job-omr.php | Same |
| 5 | employer-*.php | Same |
| 6 | landing-page-template.php | Use ROOT_PATH for all includes; used by root job landings |
| 7 | Other pages | Same pattern |
| 8 | admin/* | Admin stack (admin-sidebar, etc.) — separate pattern |

### 2.4 Path Standardization

Replace all:
- `../components/` → `ROOT_PATH . '/components/'`
- `__DIR__ . '/../core/` → `ROOT_PATH . '/core/'`
- `__DIR__ . '/../../components/` → `ROOT_PATH . '/components/'`

---

## Phase 3: Code Audit

### 3.1 Security

| Item | Check |
|------|-------|
| SQL injection | All queries use prepared statements |
| XSS | All output escaped with htmlspecialchars |
| File upload | Restrict type, size, path (resumes) |
| Auth | employer-auth.php, session checks on employer pages |
| Direct access | .htaccess blocks includes/ except save-job, save-job-alert |

### 3.2 Deprecated / Bad Practices

| Item | Action |
|------|--------|
| Hardcoded DB credentials | Move to config/env |
| DEVELOPMENT_MODE true | Set false in production |
| display_errors | Off in production |
| Error log path | `/home/myomr/weblog/` — verify exists on server |

### 3.3 Performance

| Item | Check |
|------|-------|
| N+1 queries | getJobListings, getRelatedJobs — verify single queries |
| Pagination | LIMIT/OFFSET in place |
| Asset loading | Consider defer, minify for JS/CSS |
| DB connection | Single include, no duplicate connections |

### 3.4 Robustness

| Item | Action |
|------|--------|
| Empty categories | getJobCategories returns [] — ensure no fatal on empty |
| DB down | Graceful degradation — show "Temporarily unavailable" |
| Missing job | job-detail 404 when job not found |
| Invalid input | Validate filters, pagination bounds |

---

## Phase 4: Content Audit

### 4.1 SEO

| Item | Check |
|------|-------|
| Meta tags | Title, description, canonical on all pages |
| Structured data | JobPosting schema on job-detail |
| Sitemap | generate-sitemap.php in sitemap index |
| Breadcrumbs | Present on listing and detail |

### 4.2 UX / Copy

| Item | Check |
|------|-------|
| Empty states | "No jobs found" messaging |
| Form validation | Client + server |
| Error messages | Clear, actionable |
| CTA buttons | Labels, placement |

### 4.3 Accessibility

| Item | Check |
|------|-------|
| Skip link | Add skip-link.php |
| ARIA labels | Forms, buttons |
| Focus states | Visible focus |
| Semantic HTML | main, nav, article |

---

## Phase 5: Cleanup & Deploy

### 5.1 Remove / Restrict

| File | Action |
|------|--------|
| test-connection.php | Restrict via .htaccess or remove in production |
| test-jobs.php | Same |
| test-categories.php | Same |
| debug-categories-direct.php | Same |

### 5.2 Consolidate Docs

- Keep README, DEPLOYMENT-CHECKLIST, ERROR-DEBUG-GUIDE
- Archive or merge UNIFIED-DESIGN-UPDATE-PLAN, DESIGN-UPDATE-SUMMARY, FIXES-APPLIED

### 5.3 Deployment Checklist

- [ ] DB tables exist, job_categories has is_active=1
- [ ] omr-connect.php credentials correct for production
- [ ] DEVELOPMENT_MODE = false
- [ ] display_errors = 0
- [ ] Test all critical paths: index, job-detail, post-job, apply
- [ ] Verify sitemap in Search Console

---

## Execution Order

1. **Phase 1 (Now):** Run test-connection.php, check logs, apply fixes to get index loading.
2. **Phase 2:** Migrate index.php fully to modular pattern; verify; then migrate remaining pages.
3. **Phase 3:** Security, deprecations, performance, robustness.
4. **Phase 4:** Content/SEO/UX/accessibility.
5. **Phase 5:** Cleanup and deploy.

---

## Dependencies

- `core/omr-connect.php`
- `core/include-path.php`
- `components/page-bootstrap.php`
- `components/component-includes.php`
- `components/head-includes.php` (optional for job portal)
- `components/meta.php`
- `components/analytics.php`
- `components/skip-link.php`
- `components/sdg-badge.php`
- `components/main-nav.php` (via omr_nav)
- `components/footer.php` (via omr_footer)

---

## References

- `docs/workflows-pipelines/MODULAR-INCLUDES.md`
- `omr-local-job-listings/ERROR-DEBUG-GUIDE.md`
- `omr-local-job-listings/README.md`
- `omr-local-job-listings/FIX-CATEGORIES.sql`
