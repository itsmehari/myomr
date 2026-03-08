### PRD: MyOMR Directory Platform (IT Companies first, extend to all listings)

#### Vision

Turn MyOMR’s directory into the primary acquisition and lead engine by: discoverable landing pages, clean information architecture, measurable funnels, and monetizable placement.

#### Objectives

- Standardize directory architecture to scale quickly across categories and localities.
- Improve SEO + internal linking to grow organic traffic.
- Increase conversion from visits to enquiries and paid featured slots.
- Reduce operational overhead with admin tooling and moderation.

#### In scope

- Directory platformization (schema, config, generic rendering)
- SEO (schema.org, locality hubs, sitemap breadth)
- Monetization (featured slots lifecycle, verified badge)
- Analytics (events + KPIs)
- Performance (assets, DB indices, caching)
- Security/ops (SMTP, logs, errors)
- Admin CRUD and moderation

Out of scope (this phase): payments gateway, full vendor dashboards, ML ranking.

#### Success metrics (60–90 days)

- List → Detail CTR ≥ 25%
- Detail → Enquiry CTR ≥ 5%
- Submission approval rate ≥ 60%
- Featured conversion ≥ 10% within 30 days
- Organic clicks from locality hubs: +30% MoM

#### Users/personas

- Local residents and job-seekers
- Business owners (SMBs, IT firms) wanting exposure
- Editors/admins approving and featuring listings

---

### Canonical URL conventions (reference)

- Base: `https://myomr.in/`
- Directories root: `https://myomr.in/omr-listings/`
  - IT list (canonical): `https://myomr.in/omr-listings/it-companies.php` (pretty alias `/it-companies` is acceptable but canonical tag must point to the php path)
  - Company detail: `https://myomr.in/it-companies/{slug}-{id}`
  - Locality hubs: `https://myomr.in/omr-listings/locality/{locality}.php`
- Events root: `https://myomr.in/omr-local-events/`
- Jobs root: `https://myomr.in/omr-local-job-listings/`

Rules

- Each page must set a `<link rel="canonical" href="..." />` to the canonical above.
- Sitemaps should list canonical URLs only.
- Avoid duplicate indexation between pretty aliases and canonical php paths (prefer canonical in tags; keep one in sitemap).

#### Jobs module authentication and access control

- Employer-facing pages use employer session only:
  - `employer-login-omr.php`, `my-posted-jobs-omr.php`, `post-job-omr.php` → guarded by `requireEmployerAuth()` from `omr-local-job-listings/includes/employer-auth.php`.
- Admin-facing job moderation uses global admin only:
  - All pages under `/omr-local-job-listings/admin/` MUST call `requireAdmin()` from `core/admin-auth.php`.
  - Never redirect job admin URLs to employer login.
  - Admin logout is `/admin/logout.php`.
- Site admin area `/admin/` remains the single entry for CMS; jobs admin is linked as a module.

#### Jobs roles and end-to-end workflows

Roles

- Job Seeker: browses and applies to jobs; no login required.
- Employer (Job Poster): registers/logs in by email; manages postings and applications.
- Super Admin (MyOMR Admin): moderates jobs and employer status via global admin.

Workflows

- Job Seeker

  1. Discover jobs → `omr-local-job-listings/index.php` (filters, pagination)
  2. View details → `omr-local-job-listings/job-detail-omr.php?id={id}`
  3. Apply → `process-application-omr.php` → success `application-submitted-omr.php`
  4. Non-authenticated; data validated server-side; duplicate application checks in backend

- Employer

  1. Register → `employer-register-omr.php` (creates/updates `employers` row, status=pending)
  2. Login → `employer-login-omr.php` (session set via `employerLogin()`)
  3. Post job → `post-job-omr.php` (or via `process-job-omr.php`, status=pending)
  4. Dashboard → `my-posted-jobs-omr.php` (guarded by `requireEmployerAuth()`)
  5. View applications → employer dashboard links per job
  6. Logout → `employer-logout-omr.php`

- Super Admin
  1. Admin login → `/admin/login.php`
  2. Jobs admin → `/omr-local-job-listings/admin/` (guarded by `requireAdmin()`)
  3. Approvals → `admin/manage-jobs-omr.php` (approve/reject)
  4. Employer status → `admin/verify-employers-omr.php` (pending/verified/suspended)
  5. Logout → `/admin/logout.php`

Acceptance criteria (jobs module)

- Auth separation
  - Employer pages deny access without employer session and redirect to `employer-login-omr.php?redirect=...`.
  - Jobs admin pages deny access without admin session and redirect to `/admin/login.php?redirect=...`.
  - No jobs admin URL redirects to employer login.
- Submissions
  - New jobs default to `pending`; only admin can change to `approved`/`rejected`.
  - Applications persist with validation, prevent duplicates by job/email.
- UX
  - List, detail, apply flows load without errors on desktop/mobile.
  - Employer dashboard shows posted jobs with status, views, applications.
  - Admin screens show counts and actions; status updates reflect immediately.

---

### Jobs User Flow Mapping and Guard Points

- Job Seeker

  - Entry: `/omr-local-job-listings/` → filter/search → `/job-detail-omr.php?id={id}` → apply (POST) → `/application-submitted-omr.php`
  - Guards: server validation on apply; duplicate prevention by `(job_id, applicant_email)`; only approved jobs resolve on detail/list

- Employer (Job Poster)

  - Entry: `/omr-local-job-listings/employer-login-omr.php` → session set → `/omr-local-job-listings/my-posted-jobs-omr.php`
  - Actions: Post job (requires `requireEmployerAuth()`), view applications, logout
  - Guards: invalid email blocked; missing employer auto-created on login/register; posting requires CSRF + server validation

- Super Admin
  - Entry: `/admin/login.php` → `/admin/` → Jobs module (`/omr-local-job-listings/admin/`)
  - Actions: Approve/Reject jobs, verify/suspend employers, view all applications
  - Guards: `requireAdmin()` on all jobs admin routes; role checks via `admin-auth.php`

State Transitions

- Job: pending → approved/rejected/closed; only approved appear publicly; transitions timestamped
- Employer: pending → verified/suspended; status reflected in admin and future trust badges

---

### Error Scenarios and Mitigations (Jobs)

- Job detail 404 despite approval (INNER JOIN excluded rows)
  - Mitigation: use LEFT JOIN for `employers` and `job_categories` in list/detail (done)
- Admin URLs redirect to employer login
  - Mitigation: all jobs admin pages call `requireAdmin()`; add `DirectoryIndex index.php` under jobs/admin if needed (ops)
- Posting without login
  - Mitigation: `post-job-omr.php` guarded by `requireEmployerAuth()`; CTA routes to employer login (done)
- Empty categories prevent posting
  - Mitigation: `getJobCategories()` fallback; ops note to set `is_active=1`
- Duplicate applications
  - Mitigation: `hasUserApplied(job_id,email)` gate + DB unique key `(job_id, applicant_email)` (todo)
- Stale PHP opcache after deploy
  - Mitigation: deploy step clears opcache/PHP‑FPM; add cache‑bust helper (ops todo)
- Email delivery failures
  - Mitigation: PHPMailer + SMTP with retries; admin alerts (PRD §6.1)
- DB drift across environments
  - Mitigation: migrations in `dev-tools/migrations/`, idempotent runners (ops)

---

### Improvements Backlog (Jobs)

Product/UX

- Email notifications: applicant confirmation, employer new‑application, admin approve/reject
- Employer verification badge; KYC fields; show “Verified” on listings
- Auto‑expire jobs by `application_deadline`; reminder emails before expiry
- Magic‑link login for employers (passwordless) with rate limits
- CSV export for applications (employer + admin)

Security & Data

- Unique DB constraint `(job_id, applicant_email)`; indexes for `job_postings(status, created_at)` and `job_applications(job_id, applicant_email)`
- CAPTCHA after N failed employer logins; IP/session throttling
- Audit trail for status changes (job/employer)

Performance & Ops

- Output caching for listings by filter hash; purge on approve/reject
- Email queue with background cron worker
- Health checks + GA coverage on admin actions

Routing & SEO

- Optional pretty detail URL `/jobs/{slug}-{id}` with canonical to PHP path
- Jobs sitemap refreshed on approvals

Acceptance for this batch

- Posting requires employer session; CTA routes through login (done)
- LEFT JOIN queries ensure approved jobs resolve even if linked rows are missing (done)
- Admin pages gated by `requireAdmin()` (done)
- DB unique key added for duplicate‑application prevention (todo)
- GA events implemented: employer_login, job_post_submit, admin_job_approve/reject (todo)
- SMTP via PHPMailer configured; test emails deliver (todo)

---

### Work Breakdown Structure (WBS)

Legend: [ ] pending [x] done [~] in progress

1.0 Directory Platformization (P0)

- [x] 1.1 Schema standardization
  - [x] Define canonical tables per directory (config aligned to actual tables)
  - [x] Add `locality`, `slug`, `verified` columns for IT + non‑IT via runner
  - [x] Write migration scripts/runners
  - Deliverables: SQL migrations; schema diagram
  - Owner: Backend | Effort: 1–2d | Dependencies: DB access
  - Acceptance: Migrations run without errors; data intact (applied via phpMyAdmin using `dev-tools/migrations/2025-10-31_directory_columns_and_indexes.sql`)
- [x] 1.2 Align `directory-config.php`
  - [x] Map actual tables/fields (banks, hospitals, schools, parks, industries, gov offices, ATMs)
  - [ ] Add category-specific fields
  - [ ] Document config contract
  - Deliverables: Updated config + docs
  - Owner: Backend | Effort: 0.5–1d
  - Acceptance: Lists render via config without code changes
- [~] 1.3 Generic list renderer
  - [x] Extract reusable renderer (search, filters, sort, pagination, schema, CTAs) — `omr-listings/components/generic-list-renderer.php`
  - [x] Sample usage wired for Banks — `omr-listings/banks.php`
  - [x] Sample usage wired for IT — `omr-listings/it-companies.php`
  - Deliverables: Reusable include + examples
  - Owner: Backend/Frontend | Effort: 2d
  - Acceptance: IT and Banks use the same renderer with config only
- [~] 1.4 Filters and sorting
  - [x] Implement common filters (q, locality) and sorts (A–Z/Newest) — IT done; Banks + Parks + Industries + Gov Offices + ATMs wired (locality)
  - [x] Category-specific filters via config (renderer supports cuisine/rating/cost when present)
  - Deliverables: Filter builder; prepared statements
  - Owner: Backend | Effort: 1d
  - Acceptance: Filters persist via pagination; no injection vectors
- [~] 1.5 Slugging and clean URLs

  - [~] Generate slugs on insert/approve — IT links use slug-id; Banks/Hospitals/Schools link via slug-id
  - [x] `.htaccess` routes for list/detail (IT, Banks, Hospitals, Schools, Parks, Industries, Government Offices, ATMs)
  - [x] Link wiring from list/featured to details (IT + Banks/Hospitals/Schools/Parks/Industries/Gov Offices/ATMs)
  - Deliverables: Slug generator; rewrite rules; link helpers
  - Owner: Backend | Effort: 0.5–1d
  - Acceptance: `/it-companies/{slug}-{id}` resolves everywhere

    2.0 SEO and Discoverability (P0)

- [x] 2.1 Sitewide schema
  - [x] Add `Organization` in `components/meta.php`
  - [x] Add per-page `BreadcrumbList` via `$breadcrumbs` support in `components/meta.php`
  - Deliverables: Meta include updates; validation screenshots
  - Owner: Frontend/SEO | Effort: 0.5d
  - Acceptance: Rich Results tests pass; BreadcrumbList emitted on key directory pages (IT list/detail, Get Listed, Parks, Hospitals, Schools, Best Schools, Industries, Government Offices)
- [x] 2.2 ItemList/LocalBusiness
  - [x] `ItemList` on lists (IT)
  - [x] `LocalBusiness` on details (IT)
  - [x] BreadcrumbList on IT list/detail and locality hubs; Jobs list/detail
  - [x] Organization schema on jobs pages
  - Deliverables: JSON-LD snippets; QA checks
  - Owner: Frontend/SEO | Effort: 0.5d
  - Acceptance: No console errors; JSON-LD validates
- [x] 2.3 Locality hubs (5 initial)
  - [x] Create 5 locality pages with modules + FAQ (plus Navalur, Karapakkam)
  - [x] Link hubs from list/detail pages
  - Deliverables: locality hub pages + internal links + sitemap entries
  - Owner: Content/Frontend | Effort: 3–4d | Dep: Editorial copy
  - Acceptance: Indexed; in sitemap; internally linked
- [x] 2.4 Sitemap breadth + cron

  - [x] Add directory list URLs — IT, banks, hospitals, restaurants, schools, parks, industries, ATMs
  - [x] Nightly cron to refresh — see `docs/CRON-Sitemap-MyOMR.md` and `dev-tools/tasks/build-sitemap.php`
  - Deliverables: Updated generator; cron doc
  - Owner: Backend/DevOps | Effort: 0.5d
  - Acceptance: New URLs present; `lastmod` updates daily

    3.0 Monetization and Lead‑gen (P1)

- [x] 3.1 Featured slots lifecycle
  - [x] Extend table with `start_at`, `end_at`; compute active
  - [x] Admin UI to set/extend
  - [x] Auto‑hide expired features on frontend
  - Deliverables: DB change; admin forms; card logic
  - Owner: Backend/Admin UI | Effort: 2d
  - Acceptance: Expired features stop rendering; rank respected
- [x] 3.2 Pricing tiers + Verified badge

  - [x] Update `get-listed.php` with tiers (Free/Verified/Featured) and plan selection
  - [x] Persist `desired_tier` and optional `logo_url` in submissions
  - [x] Show `Verified` badge on approved entries (list + detail)
  - Deliverables: Updated form + UI; badges; migration for `desired_tier`, `logo_url`
  - Owner: Frontend/Content | Effort: 1d
  - Acceptance: Tier messaging and badges visible

    4.0 Analytics (P0)

- [~] 4.1 Event taxonomy coverage
  - [x] List events (search/map/enquire/pagination) — IT
  - [x] Detail events (map/enquire) — IT detail
  - [x] Submission start/success events — Get Listed form
  - [x] Admin approve/feature events
  - Deliverables: Event binds; GA DebugView QA
  - Owner: Frontend | Effort: 0.5–1d
  - Acceptance: Events visible with correct params
- [ ] 4.2 Reporting

  - [x] Build GA reports for funnels and weekly KPIs
  - Deliverables: `docs/GA-Reporting-MyOMR.md` with event map, conversions, saved exploration specs
  - Owner: Analytics/SEO | Effort: 0.5d
  - Acceptance: Weekly snapshot available (per guide)

    5.0 Performance and Code Health (P1)

- [x] 5.1 Asset consolidation
  - [x] Standardize fonts to Poppins
  - [x] Remove duplicate FA/Google Fonts (consolidated to FA v6)
  - [x] Self‑host core CSS/JS in `/assets` and include via `head-resources.php`
  - Deliverables: Bundled assets; updated includes
  - Owner: Frontend | Effort: 1–2d
  - Acceptance: No duplicate requests; CLS stable
- [x] 5.2 Caching
  - [x] Static caching via `.htaccess`
  - [x] Output caching for list pages keyed by query (IT list)
  - Deliverables: Rules + cache helper (`core/cache-helpers.php`) enabled in `it-companies.php`
  - Owner: Backend | Effort: 1d
  - Acceptance: Reduced TTFB; safe invalidation via `?no_cache=1`
- [x] 5.3 DB indexes

  - [x] Add indexes on `company_name`, `industry_type`, `locality` (IT done; others via safe runner)
  - [x] Runner: `dev-tools/migrations/run_2025_10_31_add_indexes_others.php` (idempotent)
  - Deliverables: runner + prior IT indexes; timings guidance in `docs/DATABASE_INDEX.md`
  - Owner: Backend | Effort: 0.5d
  - Acceptance: Common filters < 100ms (post‑index)

    6.0 Security and Ops (P1)

- [ ] 6.1 SMTP/PHPMailer + DNS
  - [ ] Switch to PHPMailer + SMTP
  - [ ] Add SPF/DKIM/DMARC doc
  - Deliverables: Working email; `docs/EMAIL_SENDER.md`
  - Owner: Backend/DevOps | Effort: 0.5–1d
  - Acceptance: Inbox delivery; DMARC pass
- [x] 6.2 Errors/logs
  - [x] Disable `display_errors` in prod
  - [x] Central error handler (`core/error-handler.php` auto-prepended via `.htaccess`)
  - [x] Log rotation policy (daily files; cleanup cron doc)
  - Deliverables: Env flags; logging policy — see `docs/ERRORS-LOGGING-MyOMR.md`
  - Owner: Backend | Effort: 0.5d
  - Acceptance: No user‑visible errors; logs contained
- [ ] 6.3 Upload hardening

  - [ ] Standardize allowed types/size/folders
  - [ ] Enforce MIME checks
  - Deliverables: Shared upload utility; config
  - Owner: Backend | Effort: 0.5d
  - Acceptance: Unsafe uploads rejected

    7.0 Admin and Moderation (P1)

- [x] 7.1 Generic CRUD scaffolds — Not applicable this phase
  - Note: Dedicated per‑module admin UIs are already in place (e.g., IT, Jobs, Events). No cross‑directory CRUD scaffolding required now.
  - Deliverables: N/A (de‑scoped)
  - Owner: —
  - Acceptance: N/A
- [x] 7.2 Moderation queues (Module status)

  - Submission queues + flash:
    - [x] IT Directory (existing)
    - [x] Jobs (approve/reject queue; CSRF; audit log)
    - [~] Events (basic moderation in progress)
  - Batch actions across directories:
    - [ ] Planned for P1.1 — scope batch approve/reject for Jobs and IT; confirm UX and safeguards.
  - Deliverables: Queues active where applicable; CSRF protection; audit trail logged in `admin_audit_log` (Jobs).
  - Owner: Backend/Admin UI | Effort: 1d (per module for batch actions)
  - Acceptance: Approvals logged; statuses consistent; CSRF enforced; audit trail present for critical actions.

##### 7.2.1 Jobs module – granular checklist (`/omr-local-job-listings/admin/`)

- [x] `index.php` – Admin dashboard for jobs
  - Guard: `requireAdmin()` [done]
  - Lists key counts: pending/approved/applications/employers [done]
- [x] `manage-jobs-omr.php` – Approve/Reject queue
  - Guard: `requireAdmin()` [done]
  - Actions: approve/reject via POST [done]
  - Email notify employer on status change [done]
  - CSRF token on actions [done]
  - Batch approve/reject [done]
- [x] `view-all-applications-omr.php` – All applications list
  - Guard: `requireAdmin()` [done]
  - Prepared statements; pagination optional [baseline done]
- [x] `verify-employers-omr.php` – Verify/Suspend employers
  - Guard: `requireAdmin()` [done]
  - Filters by status; actions: verify/pending/suspend [done]
  - Email notify on employer status change [todo]

Cross-cutting (Jobs admin)

- [x] Auth separation: jobs admin never redirects to employer login
- [x] Prepared statements across pages
- [x] CSRF tokens for all admin POST actions (add hidden token + verify)
- [x] Audit trail for status changes (job/employer) — persisted in `admin_audit_log`
- [ ] Batch actions (approve/reject) with confirmation dialogs

  8.0 UX Consistency (P1)

- [x] 8.1 Design tokens (completed to production standard)

  - Tokens file: `/assets/css/core.css` (variables + utilities)
    - Variables: `--myomr-font-primary`, `--myomr-color-primary`, `--myomr-color-accent`, `--myomr-color-muted`, `--myomr-radius`, `--myomr-maxw`
    - Utilities: `.maxw-1280`, `.rounded-lg`, `.text-muted-omr`, `.bg-primary-omr`, `.btn-omr`
  - Font: Poppins loaded via Google Fonts; included by default on modern pages
  - Include guidance: add in `<head>` → `link /assets/css/core.css` and Poppins; or include `components/head-resources.php`
  - Applied on:
    - Jobs: `omr-local-job-listings/index.php`, `job-detail-omr.php`, `post-job-omr.php`
    - IT: covered via `components/head-resources.php` + Poppins (tokens usable where adopted)
  - Owner: UI/Frontend | Effort: 1d (done)
  - Acceptance: Tokens present; pages render with Poppins and shared color system; max width 1280 preserved

- [x] 8.2 Component refactor

  - [x] Replace inline styles with classes on key lists (IT, Hospitals, Parks, Industries, Government Offices, ATMs) using `assets/css/components.css`
  - [x] Verified badge unified via `.badge-verified`
  - [x] Global navigation: added top-level "Jobs" and "Events" viewer links; contributor CTAs: "List a job", "List an event"
  - [x] Homepage Quick Actions: added "View Jobs in OMR" and "View Events in OMR" CTAs
  - Deliverables: Components stylesheet; updated pages
  - Owner: Frontend | Effort: 1–2d
  - Acceptance: Minimal inline styles; consistent components

- [~] 8.3 Accessibility (systematic pass)

  - Contrast and focus states use tokenized colors; ensure visible focus rings [~]
  - Keyboard navigation: skip links in primary pages; verify tab order [~]
  - Forms: programmatic labels; ARIA where needed; error messaging announced [~]
  - Deliverables (next):
    - Add `.visually-hidden` helper globally; enforce focus styles on `.btn-omr` and links
    - Run quick WCAG smoke test on Jobs list/detail/post and IT list/detail
  - Owner: Frontend | Effort: 1d
  - Acceptance: Basic WCAG A/AA checks pass (labels, focus, contrast, keyboard)

    9.0 Content Depth (P2)

- [~] 9.1 Detail templates
  - [x] Add About/Services/Careers/Map/Nearby transit blocks
  - [ ] Admin fields for top companies
  - Owner: Frontend/Content | Effort: 2d
  - Acceptance: Engagement up; bounce down
- [~] 9.2 Internal linking

  - [ ] Link from news to companies and hubs
  - [x] Related companies widget
  - Owner: SEO/Content | Effort: 1–2d
  - Acceptance: Crawl depth and internal clicks up

    10.0 Rollout & QA

- [x] 10.1 Week‑by‑week plan
  - [x] Week 1: 1.1–1.5, 2.1, 4.1, 5.3
  - [x] Week 2: 2.3, 2.4, 5.1, 6.1
  - [x] Week 3: 3.1, 3.2, 5.2, 7.1
- [ ] 10.2 QA
  - [x] Schema validation (ItemList JSON-LD on banks/hospitals/parks; detail schemas validate)
  - [x] Analytics events QA (employer_login_submit, job_post_submit, admin_job_approve/reject + bulk)
  - [x] Pagination/filter combos verified
  - [x] Accessibility basics (skip links + main-content)
  - Owner: QA/Frontend/Backend | Effort: Ongoing
  - Acceptance: All acceptance criteria met per work item

---

### Epics and Work Breakdown

#### 1) Directory platformization (P0)

- Unify schema and config
  - Adopt canonical table names: `omr_it_companies`, `omr_banks`, `omr_hospitals`, `omr_schools`, `omr_restaurants`, etc.
  - Align `omr-listings/directory-config.php` to real tables; add field mapping for `id`, `name`, `address`, `contact`, `locality`, `slug`, `industry_type` (where relevant), `verified`.
  - Add a generic list renderer that uses config to render, filter, sort, paginate.
- Generic filters
  - Common: keyword (`q`), locality, A–Z/Newest.
  - Category-specific: cuisine, rating, cost (restaurants), services (banks), board (schools), etc.
- Acceptance criteria
  - List pages for each directory use prepared statements; filters preserve query state in pagination links.
  - Config-driven: adding a new directory requires only config + table.

Proposed core fields per directory:

- id INT PK AI, name VARCHAR(255) NOT NULL, address TEXT, contact VARCHAR(255), locality VARCHAR(100), slug VARCHAR(260) UNIQUE, verified TINYINT(1) DEFAULT 0, created_at, updated_at
- Category-specific optional columns via config; avoid schema overfitting.

#### 2) SEO and discoverability (P0)

- Schema
  - Sitewide `Organization` in `components/meta.php`.
  - `BreadcrumbList` on directory and detail pages.
  - `ItemList` on lists (IT done); `LocalBusiness` (or `Organization`) on details (IT done).
  - FAQ schema on key landing pages (IT done).
- Locality hubs
  - Create curated pages for Perungudi, Kandhanchavadi, Thoraipakkam, Sholinganallur, Navalur/Siruseri: intro copy, listings module (IT + Banks + Restaurants), nearby highlights, FAQ, internal links.
- Sitemap breadth
  - Include all directory lists and detail pages; daily cron to regenerate.
  - Canonicals: `/restaurants`, `/atms`, `/government-offices` included; generator updated

Acceptance criteria

- All core pages validate in Rich Results tests.
- Hubs link from list and detail; hubs appear in sitemap.

#### 3) Monetization and lead-gen (P1)

- Featured slots lifecycle
  - Extend `omr_it_companies_featured` with `start_at`, `end_at`, `is_active` computed; rank positions; blurb; CTA; logo (optional).
  - Admin management to create/extend/expire featured.
- Get Listed funnel
  - `get-listed.php`: add pricing tiers (Free → Verified → Featured), transparent benefits; “Verified” badge on approved entries.
  - Capture consent for publishing contact info.

Acceptance criteria

- Featured cards auto-hide after `end_at`.
- Listing row shows “Verified” where applicable.

#### 4) Analytics (P0)

- Event spec
  - list_search_submit, list_map_click, list_enquire_click, list_pagination_click (IT done)
  - detail_map_click, detail_enquire_click
  - submission_start, submission_success, admin_approve, admin_feature_set
- KPIs tracked as GA conversions and funnel stages.

Acceptance criteria

- All events sent with category `{directory_type}` and labels (company name, locality, page).
- Weekly dashboard summary.

#### 5) Performance and code health (P1)

- Asset hygiene
  - Consolidate fonts to Poppins; remove duplicate Font Awesome + Google Fonts includes; self-host critical CSS/JS in `/assets`.
- Caching
  - Static asset caching via `.htaccess`.
  - Optional server-side output caching for list pages keyed by `(type,q,locality,sort,page)`.
- DB indexes
  - Create covering indexes for heavy filters.

Acceptance criteria

- Lighthouse performance ≥ 85 on list and detail pages.
- P95 list page TTFB improved; DB query times under 100ms.

Short snippets

```sql
CREATE INDEX idx_it_name ON omr_it_companies (company_name);
CREATE INDEX idx_it_industry ON omr_it_companies (industry_type);
CREATE INDEX idx_it_locality ON omr_it_companies (locality);
CREATE INDEX idx_it_name_locality ON omr_it_companies (company_name, locality);
```

```apache
# .htaccess add-ons (static caching)
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType text/css "access plus 30 days"
  ExpiresByType application/javascript "access plus 30 days"
  ExpiresByType image/webp "access plus 90 days"
  ExpiresByType image/jpeg "access plus 90 days"
</IfModule>
```

#### 6) Security and ops (P1)

- SMTP via PHPMailer + authenticated SMTP; SPF/DKIM/DMARC guidance in `docs/EMAIL_SENDER.md`.
- Error policy: disable `display_errors` in production; central error handler; log rotation for `weblog`.
- Upload policy: standardize allowed types, sizes, directories; reuse `security-helpers.php` helpers.

Acceptance criteria

- Emails pass DMARC; submissions don’t go to spam.
- No PHP notices visible on production.

#### 7) Admin and moderation (P1)

- Generic CRUD for all directories modeled on restaurants admin.
- Moderation queues for user-submitted entries (where applicable).
- Role-based checks using existing `$_SESSION['admin_logged_in']`.

Acceptance criteria

- Consistent admin UX; bulk approve/reject; search and filter in admin lists.

#### 8) UX consistency (P1)

- Design system: Poppins, Bootstrap grid, max width 1280px, unified button styles, badge styles.
- Replace legacy inline CSS with shared styles in `/assets/css/`.
- Consistent header/footer components everywhere.

Acceptance criteria

- Visual parity across directories; contrast and accessibility checks pass.

#### 9) Content depth (P2)

- Detail pages: About, Services/Tech stack, Careers link, map embed, nearby transit; optional logo.
- Internal linking: from related news to companies and locality hubs; sitewide related companies widget.

Acceptance criteria

- Time on page and secondary click-through improve; measured via GA.

---

### Data model updates (initial)

- Migrate or align to `omr_it_companies` as canonical.
- Add `locality` and `slug` columns.
- Add `verified` TINYINT(1) DEFAULT 0.

```sql
ALTER TABLE omr_it_companies
  ADD COLUMN locality VARCHAR(100) NULL AFTER address,
  ADD COLUMN slug VARCHAR(260) NULL UNIQUE AFTER locality,
  ADD COLUMN verified TINYINT(1) NOT NULL DEFAULT 0 AFTER slug;
```

Slug generation: on approve/insert, build `slugBase = kebab(name) + '-' + id`.

---

### Event taxonomy (examples)

- list_search_submit: {directory_type, search_term, locality, sort}
- list_map_click: {directory_type, company_name}
- list_enquire_click: {directory_type, company_name}
- detail_map_click/detail_enquire_click: {directory_type, company_name}
- submission_start/submission_success: {directory_type, featured_requested}
- admin_approve/admin_feature_set: {directory_type, company_name}

---

### Milestones and timeline

- Week 1 (P0)

  - Add DB indexes to `omr_it_companies`.
  - Align `directory-config.php` to real tables; publish canonical naming.
  - Add sitewide `Organization` schema and `BreadcrumbList` to list/detail.

- Week 2 (P0/P1)

  - Build 5 locality hubs; link from list + detail; add to sitemap.
  - Switch to PHPMailer SMTP; create `EMAIL_SENDER.md` with SPF/DKIM/DMARC steps.
  - Consolidate fonts/FA; move third-party assets to `/assets`; purge duplicates.

- Week 3 (P1)
  - Featured lifecycle: add `start_at`, `end_at`; admin UI; auto-expire.
  - Expand sitemap to banks, schools, hospitals detail URLs; add cron to rebuild nightly.
  - Add output caching for listing pages; measure TTFB and CTR shifts.

Dependencies

- SMTP credentials; DNS records for SPF/DKIM/DMARC.
- Editorial copy for locality hubs.
- Minimal design tokens for badges/buttons.

Risks and mitigations

- Schema drift across directories → mitigate via `directory-config.php` as source of truth and migration scripts.
- SEO cannibalization of hubs vs lists → explicit canonicals; clear internal linking hierarchy.
- Deliverability issues → authenticated SMTP + DNS.

QA plan

- Validate schema.org with Rich Results.
- Verify GA events in Realtime DebugView.
- Check pagination + filter combinations for correct counts and URLs.
- Accessibility: keyboard navigation, contrast, landmarks.

Rollout

- Staged release per directory type; monitor GA funnels weekly.
- Update sitemap and request indexing after each batch.

---

### Appendix: SEO Infrastructure & Routing Rules (Shared Hosting)

- Use a single root sitemap index at `https://myomr.in/sitemap.xml` that references module sitemaps.
- Robots.txt must point only to the root sitemap index; disallow admin paths.
- Prefer centralized clean routes in the root `.htaccess`; per-folder `.htaccess` should be minimal.
- All canonical tags must match the clean routes.

---

### 8.2 Component Refactor – Completed

- Replaced inline styles with classes using `assets/css/tokens.css` and `assets/css/components.css`.
- Unified headers/footers via `components/head-resources.php` global includes.
- Refactored key pages: IT Companies, Hospitals, Schools, Parks, Industries (lists and detail UIs align to tokens).
- Acceptance: key pages free of bespoke inline color blocks; consistent buttons/badges/cards.

### 8.3 Accessibility – Completed

- Added skip link component (`components/skip-link.php`) and included on key pages.
- Focus-visible outlines and accessible skip-link styles in `assets/css/components.css`.
- Added `id="main-content"`/main containers incrementally and `aria-label`s for interactive buttons (e.g., map/enquire).
- Acceptance: keyboard users can skip nav, focus rings visible, controls labeled.
