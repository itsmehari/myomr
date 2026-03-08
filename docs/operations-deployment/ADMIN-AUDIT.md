# Admin Panel Audit – MyOMR.in

**Date:** 2026-02-25  
**Scope:** `/admin/` folder, auth flow, navigation, workflow, and project alignment  
**Status:** Phase 1–3 fixes applied; Phase 4 recommendations documented

---

## Executive Summary

Audit of the admin panel identified:

- Inconsistent auth redirects (one page redirected to wrong login)
- Credentials stored as hash; now set to **admin** / **password** for development
- Dead code in `logout.php`
- Two event modules (legacy vs unified); navigation correctly uses unified one
- Navigation config matches actual admin files; all paths resolve

---

## Phase 1 – Credentials (Completed)

| Item | Status |
|------|--------|
| Username `admin` | Already configured in `core/admin-config.php` |
| Password `password` | Updated from `Mylapore@21g` to `password` |
| Editor password hash | Synced to `password` for consistency |

**File modified:** `core/admin-config.php`

**Production note:** Change `MYOMR_ADMIN_PASSWORD_HASH` for production using `password_hash('your-secure-password', PASSWORD_DEFAULT)`.

---

## Phase 2 – Auth and Redirects (Completed)

| Issue | Fix |
|-------|-----|
| `admin/it-parks/import-export.php` redirected to `/commonlogin.php` (public user login, wrong path) | Redirect updated to `/admin/login.php` |
| `admin/logout.php` contained duplicate dead code block (second `<?php` block unreachable) | Duplicate block removed; single flow uses `adminLogout()` and redirects to `/admin/login.php` |

**Files modified:** `admin/it-parks/import-export.php`, `admin/logout.php`

### Auth Pattern Overview

| Pattern | Usage | Notes |
|---------|-------|-------|
| `_bootstrap.php` + `requireAdmin()` | Articles module, dashboard, newer pages | Preferred |
| `session_start()` + manual `$_SESSION['admin_logged_in']` | Most legacy pages (banks, schools, news, events) | Works but inconsistent |
| `commonlogin.php` | Public “Common User Login” – not admin | Do not use for admin redirects |

---

## Phase 3 – Navigation vs Files (Verified)

Navigation config (`admin/config/navigation.php`) paths were checked against existing files. All main module paths resolve:

| Module | Path | Exists |
|--------|------|--------|
| Dashboard | `/admin/dashboard.php` | Yes |
| Articles | `/admin/articles/index.php` | Yes |
| News Bulletin | `/admin/news-list.php` | Yes |
| Events Control | `/admin/events/events-list.php` | Yes |
| Jobs Portal | `/omr-local-job-listings/admin/index.php` | External module |
| Hostels & PGs | `/omr-hostels-pgs/admin/manage-properties.php` | External module |
| Restaurants | `/admin/restaurants-list.php` | Yes |
| Banks, Schools, Hospitals, Parks, Industries, Government, ATMs | Various `/admin/*-list.php`, `manage-*.php` | Yes |
| IT Submissions, IT Companies, Featured IT, IT Parks | Various | Yes |
| Migrations, Process Listing, Change Password | Yes | Yes |

---

## Workflow and Product Findings

### Workflow

1. Two event modules exist: `admin/events-*.php` (legacy) and `admin/events/events-*.php` (unified). Navigation uses the unified one; consider deprecating legacy later.
2. News: two systems – `omr_articles` (new Articles module) and `news_bulletin` (legacy News Bulletin). Both are linked in navigation.
3. Auth is mixed: some pages use `_bootstrap.php` + `requireAdmin()`, others use manual session checks.

### Technical

1. Some redirects use relative `login.php`; subfolder pages use `../login.php`. Both work but absolute `/admin/login.php` is clearer.
2. `commonlogin.php` in `/admin/` is for public users; admin should always redirect to `/admin/login.php`.

### Product / Relevancy

1. Admin structure matches the product: news, events, directories (banks, schools, hospitals, parks, industries, ATMs, government), IT parks, restaurants, jobs, hostels.
2. New Articles module (image URL + upload) aligns with content needs.

---

## Recommendations

1. **Standardize auth:** Migrate remaining admin pages to `_bootstrap.php` + `requireAdmin()` for consistency and easier maintenance.
2. **Production credentials:** Replace default `admin` / `password` with strong credentials before production.
3. **Legacy cleanup:** Plan deprecation of `admin/events-*.php` once usage is confirmed minimal.
4. **Docs:** Keep this audit updated when adding or changing admin modules.

---

## Files Modified (This Audit)

| File | Change |
|------|--------|
| `core/admin-config.php` | Password set to `password` (admin + editor hashes) |
| `admin/it-parks/import-export.php` | Redirect `/commonlogin.php` → `/admin/login.php` |
| `admin/logout.php` | Removed dead duplicate block |

---

## Related Documentation

- `docs/ARCHITECTURE.md` – Project structure
- `docs/operations-deployment/ONBOARDING.md` – Development setup
- `docs/content-projects/ADMIN-ARTICLES-DASHBOARD-PLAN.md` – Articles dashboard design
