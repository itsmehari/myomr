# MyOMR Job Portal – Technical Baseline (Discovery Draft)

_Created: 11 Nov 2025 — Supports WBS Step 2 (“Data & Backend Stabilisation”)_

## 1. Data Sources & Queries

| Area | File(s) | Query Pattern | Notes |
|------|---------|---------------|-------|
| Listings page | `omr-local-job-listings/index.php` | `SELECT * FROM job_postings WHERE status='approved' ORDER BY featured DESC, created_at DESC` (full table scan) | Fetches entire approved dataset, then filters/paginates in PHP. Salary filters collected but unused. |
| Job enrichment | `index.php` | Prepared `IN` clause on selected job IDs to join employers/categories | Uses dynamic placeholders; safe but only after full in-memory slice. |
| Search fallback | `index.php`, `includes/job-functions-omr.php` | `SELECT * FROM job_postings WHERE LOWER(TRIM(status)) = 'approved'` | Redundant second pass for data cleanliness. |
| Job counts | `includes/job-functions-omr.php`, `jobs-in-*.php` | Count query `SELECT COUNT(*) FROM job_postings WHERE status='approved'` then optional in-PHP filtering | `salary_min/max` never applied; category/location filters use `stripos` in PHP. |
| Job detail | `job-detail-omr.php` | `SELECT j.*, e.*, c.* FROM job_postings j LEFT JOIN employers e ... WHERE j.id = ? AND j.status='approved'` | Falls back to non-status-constrained query if no match; increments view counter. |
| Applications | `process-application-omr.php` | Insert + `UPDATE job_postings SET applications_count = applications_count + 1` | Duplicate detection via `job_applications` unique constraint (implicit) and helper. |
| Employer dashboard | `my-posted-jobs-omr.php` | `SELECT * FROM job_postings WHERE employer_id = {id}` plus email join fallback | Loads all employer jobs at once; no pagination/filter. |
| Admin moderation | `admin/manage-jobs-omr.php` | `SELECT j.*, e.* FROM job_postings j JOIN employers e ...` | No pagination; entire corpus fetched for UI table. |
| Category loading | `includes/job-functions-omr.php` | `SELECT * FROM job_categories WHERE is_active = 1` fallback to all | Logs warnings if none active. |

## 2. Performance & Scalability Risks

- **Full-table loads:** User, employer, and admin views retrieve all rows into memory before filtering. Risk grows exponentially with dataset.
- **Repeated queries per request:** Search filtering issues (status fallback, employer name lookups inside loop) generate extra round trips proportional to job count.
- **Absent indexes:** No evidence of composite indexes on `(status, created_at)` or `(employer_id, created_at)` beyond SQL migrations; confirm in DB.
- **Synchronous email:** Application/job status emails sent inline; can bottleneck response time.
- **Static assets:** CSS/JS served via CDN + local bundles; further perf work pending (defer non-critical CSS, bundling).

## 3. Data Quality Observations

- `job_postings.status` treated inconsistently (case, whitespace) — reason for `LOWER(TRIM())` fallback.
- `job_type` stored with capitalised values (e.g., `Full-time`) while filters expect lowercase (`full-time`), causing mismatch.
- `salary_range` stored as freeform string; no structured min/max fields; UI lacks enforcement.
- `job_categories.is_active` gating categories; missing active flags trigger warning message on employer form.
- Employer auto-creation sets placeholder data (`company_name = 'Employer'`), requiring manual update later.

## 4. Monitoring & Analytics State

- Google Analytics hooks embedded (`components/analytics.php`), but many events rely on console logs or TODO comments; no server-side metrics.
- No error/latency monitoring around critical endpoints; reliance on PHP error log and optional worklog notes.

## 5. Immediate Baseline Tasks (for Stabilisation Sprint)

1. Measure dataset size & response time for `index.php` under current load (requires production metrics access).
2. Audit actual DB indexes against migration files (`dev-tools/migrations/2025-11-01_seed_legacy_jobs.sql`, etc.).
3. Capture sample job records for enum normalisation (status/job_type casing, trailing whitespace).
4. Inventory email deliverability success/failure (audit `core/email.php` logs).
5. Validate `job_applications` table constraints (duplicate prevention, referential integrity).

---

_Next_: Use this baseline to drive refactor requirements in WBS Step 2 and inform success metrics (WBS Step 1 deliverable).  
_Maintainer_: AI Agent (GPT-5 Codex) — please review and triage from `docs/inbox/`.

