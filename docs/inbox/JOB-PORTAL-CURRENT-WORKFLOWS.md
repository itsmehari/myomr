# MyOMR Job Portal – Current Workflow Map (Discovery Draft)

_Created: 11 Nov 2025 — Prepared for WBS Step 1 (“Discovery & Planning”)_

## 1. Job Seeker (User) Journey

1. **Landing & Browse**
   - Entry points: `omr-local-job-listings/index.php`, location/industry landing pages (e.g., `jobs-in-perungudi-omr.php`).
   - Initial data load pulls all approved jobs into PHP memory, then applies in-memory filtering/pagination.
   - UI: hero search form (keyword/location/category), secondary filters (job type chip), static landing links.
2. **Search & Filter**
   - Keyword, location, category, job type submitted via GET; salary min/max inputs exist but unused.
   - Search sorts via on-page buttons (`job-search-omr.js`) with client-side resorting only.
3. **Job Detail Exploration**
   - Detail view `job-detail-omr.php` fetches job by ID, increments views, displays metadata and related jobs.
   - “Apply Now” triggers modal form; share buttons (WhatsApp/LinkedIn) log to console only.
4. **Application Submission**
   - Form posts to `process-application-omr.php`; validates name/email/phone, inserts row, increments counter.
   - Duplicate detection via `hasUserApplied()` cookie/email check; sends employer email if configured.
   - Applicant redirect to `application-submitted-omr.php` confirmation page.

## 2. Employer Journey

1. **Authentication**
   - `employer-login-omr.php` email-only login; creates pending employer if none exists (`employerLogin()`).
2. **Job Posting**
   - Access gated by `requireEmployerAuth()` -> `post-job-omr.php` multi-section form.
   - CSRF token stored in session; categories sourced via `getJobCategories()`.
3. **Processing Submission**
   - `process-job-omr.php` handles create/update:
     - Validates fields, ensures description length, normalises optional fields.
     - Inserts/updates employer, inserts job with `status = 'pending'`.
     - On success redirects to `job-posted-success-omr.php`.
4. **Employer Dashboard**
   - `my-posted-jobs-omr.php` displays stats, job table; pulls all jobs for employer (no pagination, limited filters).
   - Actions: edit (`edit-job-omr.php`), view applications (`view-applications-omr.php`), preview listing.

## 3. Admin Journey

1. **Dashboard Overview**
   - `omr-local-job-listings/admin/index.php` shows pending/approved counts, applications, employers.
2. **Job Moderation**
   - `admin/manage-jobs-omr.php` loads all postings joined with employer info.
   - Actions: single or bulk approve/reject; audit log entries; employer email notifications.
3. **Employer Verification**
   - `admin/verify-employers-omr.php` adjusts employer status (pending/verified/suspended); writes audit log.
4. **Application Oversight**
   - `admin/view-all-applications-omr.php` (list), `admin/view-applications-omr.php` (per job) for review.

## 4. Cross-Cutting Observations (Baseline for Later Steps)

- **Data Access:** Many endpoints bypass prepared statements when composing dynamic IN lists or full table scans.
- **Pagination:** User and admin lists load entire datasets into memory before slicing, impacting scalability.
- **Audit/Logging:** Admin actions recorded via `admin_audit_log`; user-side analytics rely on GA events (manual triggers).
- **Email:** Notifications rely on `core/email.php`; no queue/retry mechanism.

---

_Next actions for Discovery_: capture technical baseline metrics (Step 2) and compile stakeholder interview prompts (Step 1).  
_Maintainer_: AI Agent (GPT-5 Codex) — please triage from `docs/inbox/` after review.

