# Admin Modules – Logical Organization & End-to-End Analysis

**Date:** 2026-02-25  
**Scope:** All admin modules, workflows, database tables, layout consistency, and navigation  
**Status:** Analysis complete; fixes applied where noted

---

## 1. Module Map: Files ↔ Tables ↔ Workflows

| Module | Primary Table(s) | Files (List / Add / Edit / Delete) | Workflow Status | Notes |
|--------|------------------|------------------------------------|-----------------|-------|
| **Articles** | `articles` | index, add, edit | ✅ CRUD | New news system; image URL + upload |
| **News Bulletin** | `news_bulletin` | news-list, news-add, news-edit | ✅ CRUD | Legacy; dashboard Recent News links here |
| **Events** | `events` | events-list, events-add, events-edit, events-delete | ✅ CRUD | **Duplicate**: root + `events/` subfolder |
| **Restaurants** | `omr_restaurants` | restaurants-list, add, edit | ✅ CRUD | Geolocation support |
| **Banks** | `omrbankslist` | banks-list, banks-edit / manage-banks | ✅ CRUD | list=read+edit; manage=delete |
| **Schools** | `omrschoolslist` | schools-list, schools-edit / manage-schools | ✅ CRUD | Same pattern as banks |
| **Hospitals** | `omrhospitalslist` | hospitals-list, edit / manage-hospitals | ✅ CRUD | Same pattern |
| **Parks** | `omrparkslist` | parks-list / manage-parks | ⚠️ List only | manage-parks deletes; no edit form |
| **Industries** | `omr_industries` | industries-list, industries-edit / manage-industries | ✅ CRUD | Same pattern |
| **Government** | `omr_gov_offices` | government-offices-list | ⚠️ Read-only | No add/edit/delete in admin |
| **ATMs** | `omr_atms` | atms-list / manage-atms | ✅ CRUD | manage-atms for delete |
| **IT Submissions** | `omr_it_company_submissions` | it-submissions-list | ✅ Approve/Reject | Approve → inserts into omr_it_companies |
| **IT Companies** | `omr_it_companies` | it-companies-list, it-companies-edit | ✅ CRUD | Profile edit (about, services, careers_url) |
| **Featured IT** | `omr_it_companies_featured` | featured-it-list | ✅ CRUD | Featured slots |
| **IT Parks** | `omr_it_parks`, `omr_it_parks_featured` | it-parks/manage, featured, import-export | ✅ CRUD | Subfolder module |
| **Jobs** | External | `/omr-local-job-listings/admin/` | External | Separate app |
| **Hostels & PGs** | External | `/omr-hostels-pgs/admin/` | External | Separate app |
| **Coworking Spaces** | External | `/omr-coworking-spaces/admin/manage-spaces.php` | External | Added to nav |
| **Change Password** | `admin_users` | change-password | ⚠️ Disconnected | Updates DB; login uses file config |
| **Migrations** | — | migrations-runner | ✅ Utility | Runs dev-tools migrations |
| **Process Listing** | — | process-listing | ❌ Misleading | Appends to txt files; not DB listings |
| **news-update-page-admin** | — | news-update-page-admin + updatenewsform | ❌ Broken | Form submits to basicform1; wrong table |
| **updatenewsform** | `basicform1` | — | ❌ Orphan | Job-application table; not news |
| **commonlogin** | — | commonlogin | N/A | Public user login; not admin |

---

## 2. Logical Grouping (Current vs Proposed)

### Current Navigation Sections (updated 2026-02-24)

1. **Dashboard & Content** – module picker, admin overview, articles, news bulletin, events, jobs, hostels, **coworking spaces**, restaurants  
2. **Local Directories** – banks, schools, hospitals, parks, industries, government, atms  
3. **Technology & IT** – it-submissions, it-companies, featured-it, it-parks  
4. **Operations** – migrations, change-password *(process-listing removed; misleading)*  

### Proposed Logical Grouping

| Group | Purpose | Modules |
|-------|---------|---------|
| **1. Home & Overview** | Entry point, module picker | index.php (unified picker) |
| **2. Content & News** | News and articles | Articles, News Bulletin |
| **3. Community** | Events, restaurants, jobs, hostels | Events, Restaurants, Jobs, Hostels |
| **4. Directories** | Local listings (read/edit/delete) | Banks, Schools, Hospitals, Parks, Industries, Government, ATMs |
| **5. Technology** | IT parks, companies, submissions | IT Parks, IT Companies, IT Submissions, Featured IT |
| **6. Operations** | Migrations, security, utilities | Migrations, Change Password |

---

## 3. Issues Found (Workflow & Technical)

### 3.1 Duplicate Event Modules

| Location | Path | Sidebar Include | Status |
|----------|------|-----------------|--------|
| Legacy | `admin/events-list.php`, events-add, events-edit, events-delete | `admin-sidebar.php` (admin/) | Uses admin sidebar |
| Unified | `admin/events/events-list.php`, etc. | `../../components/admin-sidebar.php` | Uses components sidebar (minimal) |

**Recommendation:** Use only `admin/events/` and remove legacy `admin/events-*.php`. Update dashboard.php links.

### 3.2 Two Sidebar Implementations

| Location | Type | Used By |
|----------|------|---------|
| `admin/admin-sidebar.php` | Full nav from config | Most admin root pages |
| `components/admin-sidebar.php` | Hardcoded minimal | admin/events/, change-password, news-update-page-admin |

**Recommendation:** All admin pages should use `admin/admin-sidebar.php` for consistent UX. Fix include paths in admin/events/, change-password, news-update-page-admin.

### 3.3 Auth Inconsistency

| Page Type | Auth Method |
|-----------|-------------|
| Articles | `_bootstrap.php` + `requireAdmin()` |
| News, Events, Restaurants, Directories, IT | `session_start()` + manual `$_SESSION['admin_logged_in']` |
| IT Parks (manage, featured, import-export) | `requireAdmin()` or manual check |

**Recommendation:** Migrate all to `_bootstrap.php` + `requireAdmin()` for consistency.

### 3.4 Change Password vs Login

- **Login:** Uses `core/admin-config.php` (file-based `MYOMR_ADMIN_PASSWORD_HASH`)
- **Change Password:** Updates `admin_users` table in DB  
- Login does **not** read from `admin_users`, so password changes have no effect on login.

**Recommendation:** Either (a) migrate login to use `admin_users`, or (b) make change-password update the config/env used by login, or (c) document that change-password is for future DB-backed auth only.

### 3.5 Orphan / Broken Files

| File | Issue |
|------|-------|
| `news-update-page-admin.php` | Form submits to `updatenewsform.php`; updates `basicform1` (wrong table) |
| `updatenewsform.php` | Updates `basicform1`; appears to be job-application form logic |
| `process-listing.php` | Nav says "process pending listing imports"; actually appends to txt files |

**Recommendation:** Archive or remove news-update-page-admin + updatenewsform. Update process-listing description or repurpose.

### 3.6 Two Dashboards

| File | Type | Links To |
|------|------|----------|
| `admin/index.php` | Unified module picker | Uses config; shows all modules |
| `admin/dashboard.php` | Stats + recent activity | Hardcoded links to legacy events-list, news-edit, restaurants-edit |

**Recommendation:** Unify: use index.php as main entry. Either merge dashboard stats into index or redirect dashboard → index.

### 3.7 Government & Parks Gaps

- **Government Offices:** `government-offices-list.php` – read-only; no add/edit/delete.
- **Parks:** `manage-parks` allows delete; `parks-list` shows list; no dedicated edit form (industries-edit style).

---

## 4. Include Path Fixes Applied

| File | Before | After |
|------|--------|-------|
| `admin/events/events-list.php` | `../../components/admin-sidebar.php` | `../admin-sidebar.php` |
| `admin/events/events-add.php` | same | same |
| `admin/events/events-edit.php` | same | same |
| `admin/change-password.php` | `../components/admin-sidebar.php` | `admin-sidebar.php` (relative to admin/) |
| `admin/news-update-page-admin.php` | `../components/` | `admin-sidebar.php` |

*(Fixes applied in implementation phase.)*

---

## 5. Directory Structure (Current)

```
admin/
├── index.php              # Unified module picker
├── dashboard.php          # Stats + legacy links
├── login.php, logout.php
├── _bootstrap.php
├── admin-sidebar.php      # Full nav (config-driven)
├── admin-header.php, admin-breadcrumbs.php, admin-flash.php
├── config/navigation.php
├── layout/header.php, footer.php
├── articles/              # New articles (omr_articles / articles)
├── events/                # Unified events
├── it-parks/              # IT parks subfolder
├── [legacy] events-list.php, events-add.php, events-edit.php, events-delete.php
├── news-list.php, news-add.php, news-edit.php
├── news-update-page-admin.php, updatenewsform.php  # Broken
├── restaurants-*.php
├── banks-*.php, schools-*.php, hospitals-*.php, parks-*.php, industries-*.php
├── government-offices-list.php
├── atms-list.php, manage-atms.php
├── manage-banks.php, manage-schools.php, ... (delete-focused)
├── it-submissions-list.php, it-companies-*.php, featured-it-list.php
├── migrations-runner.php, process-listing.php, change-password.php
└── commonlogin.php        # Public user login (not admin)
```

---

## 6. End-to-End Workflow Verification

| Module | List | Add | Edit | Delete | Frontend Display |
|--------|------|-----|------|--------|------------------|
| Articles | ✅ | ✅ | ✅ | ✅ | local-news/article.php, homepage cards |
| News Bulletin | ✅ | ✅ | ✅ | ✅ | components/myomr-news-bulletin.php |
| Events | ✅ | ✅ | ✅ | ✅ | omr-local-events/, events page |
| Restaurants | ✅ | ✅ | ✅ | ✅ | omr-listings/restaurants.php |
| Banks | ✅ | — | ✅ | ✅ | omr-listings/banks |
| Schools | ✅ | — | ✅ | ✅ | omr-road-schools.php |
| Hospitals | ✅ | — | ✅ | ✅ | Hospitals listing |
| Parks | ✅ | — | ⚠️ | ✅ | Parks listing |
| Industries | ✅ | — | ✅ | ✅ | Industries listing |
| Government | ✅ | — | — | — | Government offices |
| ATMs | ✅ | — | — | ✅ | ATMs listing |
| IT Parks | ✅ | ✅ | ✅ | ✅ | IT parks pages |
| IT Companies | ✅ | via submissions | ✅ | — | IT companies |
| Featured IT | ✅ | ✅ | ✅ | ✅ | Featured section |

---

## 7. Recommendations Summary

1. **Standardize sidebar:** Use `admin/admin-sidebar.php` everywhere; fix include paths in admin/events/, change-password, news-update-page-admin.
2. **Deprecate legacy events:** Remove admin/events-list.php, events-add.php, events-edit.php, events-delete.php; use only admin/events/.
3. **Update dashboard.php:** Link to admin/events/events-list.php, not events-list.php; or merge with index.php.
4. **Fix or archive:** news-update-page-admin, updatenewsform (broken flow); clarify process-listing purpose.
5. **Auth:** Migrate all admin pages to _bootstrap + requireAdmin; reconcile change-password with login (file vs DB).
6. **Government / ATMs:** Add edit forms if needed; document read-only if intentional.

---

## 8. Related Documentation

- `docs/operations-deployment/ADMIN-AUDIT.md` – Credentials, redirects, auth
- `docs/content-projects/ADMIN-ARTICLES-DASHBOARD-PLAN.md` – Articles module design
- `docs/data-backend/DATABASE_INDEX.md` – Table references
