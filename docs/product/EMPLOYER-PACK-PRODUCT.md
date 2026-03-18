# Employer Pack — Product & Operations

**Last updated:** March 2026  
**Purpose:** B2B employer package (10 jobs/month, featured, repeatable). Use this for onboarding new package subscribers and for agent context.

---

## Product name

**MyOMR Employer Pack — 10 Jobs/Month** (or "OMR Local Hire — Monthly 10")

---

## What the package includes

- **Posting allowance:** Up to 10 live job posts per calendar month under one organization. Unused slots do not roll over.
- **Extended access:** Dedicated employer login; all jobs manageable from employer dashboard and my-posted-jobs.
- **Enhanced visibility:** Each job under this plan is **featured** (top of listings + "Featured" badge).
- **Priority moderation:** Jobs from package subscribers can be fast-tracked; admin may auto-set featured on approve.
- **OMR-only audience:** Hyper-local (Perungudi, Sholinganallur, Navalur, Thoraipakkam, Palavakkam, Kelambakkam, etc.).
- **Applications:** Unlimited applications per job; direct contact with candidates; no per-application fee.

---

## Standard pricing

- **₹2,499/month** — standard for Employer Pack (10 jobs, featured, priority).
- **₹1,499/month** — introductory or relationship price when offered.
- Same price list for every prospect. Invoice monthly; bank transfer or UPI. One invoice template per subscriber per month.

---

## Subscriber lifecycle

1. **Onboarding:** Create or identify employer account. In DB, set `plan_type` = `employer_pack_10`, `plan_start_date`, `plan_end_date` (e.g. end of current month). Optionally use admin **Package Subscribers** page to view; plan assignment is via DB or a future "Assign plan" in Verify Employers.
2. **Monthly cap:** System enforces 10 approved jobs per calendar month in `process-job-omr.php`. At cap, employer sees a friendly error; no new job is inserted.
3. **Featured:** When admin approves a job whose employer has an active paid plan, the job is set to **featured** automatically in `admin/manage-jobs-omr.php`.
4. **Renewal:** Before `plan_end_date`, send invoice. On payment, extend `plan_end_date` (e.g. by one month). If unpaid after grace period (e.g. 7 days), set `plan_type` = `free` and clear or extend `plan_end_date` as per policy.

---

## Technical reference

- **DB:** `employers.plan_type`, `employers.plan_start_date`, `employers.plan_end_date`. Migration: `dev-tools/migrations/2026-03-employer-pack-plan-columns.sql`.
- **Plan config:** `omr-local-job-listings/includes/job-functions-omr.php` — `EMPLOYER_PLAN_CAP`, `getPlanCap()`, `isEmployerOnActivePlan()`, `countJobsThisMonthForEmployer()`, `getPlanLabel()`, `jobEmployersTableHasPlanColumns()`.
- **Enforcement:** Cap check in `process-job-omr.php` (new job only; guard: only when employer has active paid plan).
- **Admin:** `admin/manage-jobs-omr.php` — Plan column, auto-featured on approve for package employers. `admin/package-subscribers-omr.php` — list of active subscribers.
- **Employer UI:** Plan/usage block (card-modern) on `employer-dashboard-omr.php` and `my-posted-jobs-omr.php` when employer has active plan.
- **Landing:** `employer-pack-landing-omr.php` — reusable sales page; same design as employer-landing (hero-modern, card-modern).

---

## Adding a new client

1. Create or identify employer (e.g. via employer-register or existing account).
2. Set in DB: `plan_type` = `employer_pack_10`, `plan_start_date` = today or 1st of month, `plan_end_date` = end of month or +1 month.
3. Send invoice (reusable template). On payment, extend `plan_end_date` as needed.
4. Employer sees plan/usage on dashboard and my-posted-jobs; at 10 jobs in month, next post is blocked with message until renewal.

---

## Future tiers

- Add `employer_pack_5` or `employer_pack_20` in `EMPLOYER_PLAN_CAP` and in DB enum/values. Enforcement and dashboard read cap from config; no hardcoded 10.
