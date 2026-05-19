# JOB-INSERT-AND-SEO-SOP

**Step-by-step guide for publishing jobs with SEO validation and privacy flag checks.**

---

## Scope

Publishing job listings to MyOMR with correct slug URLs, SEO schema, and employer phone privacy.

---

## RACI

- **Responsible:** Builder (uses template, validates)
- **Accountable:** Architect (approves HANDOFF execution)
- **Consulted:** SEO lead (schema verification)
- **Informed:** Project lead

---

## Preconditions

- [ ] Job data prepared (title, description, category, locality, contact info)
- [ ] Category confirmed (IT, services, admin, retail, food, healthcare, education, manufacturing)
- [ ] Locality confirmed (OMR, Padur, Thiruvanmiyur, Medavakkam, Perungudi, Karapakkam, Kottivakkam)
- [ ] Employer verified (existing or create in `employers` table)
- [ ] Remote MySQL connection working (see `README-REMOTE-DATABASE.md`)

---

## Procedure

### 1. Prepare Job Data

- [ ] Job title: Concise, descriptive (max 255 chars)
- [ ] Description: Full details in markdown or HTML
- [ ] Category: Pick from enum (IT, services, admin, etc.)
- [ ] Locality: Pick from enum (OMR, Padur, etc.)
- [ ] Contact phone: Valid format (e.g., 9876543210, +91-9876543210)
- [ ] Contact email: Valid email
- [ ] Employer name: Create if new
- [ ] Status: Set to `active`
- [ ] Expiry date: Optional, in future

### 2. Generate Slug

- [ ] Convert title to lowercase
- [ ] Replace spaces with hyphens
- [ ] Remove special characters
- [ ] Example: "Senior Developer OMR" → "senior-developer-omr"

### 3. Check Slug Uniqueness

- [ ] Query: `SELECT id FROM jobs WHERE slug = ? AND locality_id = ?`
- [ ] If exists: Append `-v2` and check again
- [ ] If still collision: Use `-v3`, etc.
- [ ] Store final slug

### 4. Use Template

- [ ] Template path: `dev-tools/jobs/insert_jobname_locality.php`
- [ ] Copy from similar job (e.g., `insert_senior_developer_omr.php`)
- [ ] Populate variables:
  ```php
  $job_title = "Senior Developer OMR";
  $category_id = 1; // IT
  $locality_id = 1; // OMR
  $slug = "senior-developer-omr";
  $description = "...full text...";
  $employer_id = 5;
  $contact_phone = "9876543210";
  $contact_email = "jobs@company.com";
  $hide_employer_phone = 0; // 0=show, 1=hide
  $expires_at = "2026-06-19"; // 1 month from now
  ```

### 5. Dry Run

- [ ] Set `$test_mode = true` at top of script
- [ ] Run: `php dev-tools/jobs/insert_*.php`
- [ ] Check: SQL logged, no errors

### 6. Verify Schema

- [ ] Check: All columns populated
- [ ] Check: Category ID exists in `job_categories`
- [ ] Check: Locality ID exists in `localities`
- [ ] Check: Employer ID exists in `employers`
- [ ] Check: Slug is unique for this locality

### 7. Check Privacy Flag on All Surfaces

**This is CRITICAL — must check everywhere:**

- [ ] Detail page (`omr-local-job-listings/job-detail-omr.php`):
  ```php
  // Before showing phone:
  if (!$job['hide_employer_phone']) {
      echo $job['contact_phone'];
  }
  ```
- [ ] Sidebar widget (`components/jobs-sidebar-widget.php`):
  - [ ] Check flag before showing phone
- [ ] Email campaigns (`omr-local-job-listings/jobs-email/myomr-job-posting-email.html`):
  - [ ] Check flag before including phone
- [ ] Search results (if applicable):
  - [ ] Check flag before showing phone in snippets

**Test:** Create job with `hide_employer_phone=1`; verify phone NOT showing on detail page, sidebar, email.

### 8. SEO Validation

- [ ] Canonical URL: `https://myomr.in/job/{id}/{slug}`
- [ ] Set in script: `$canonical_url = "https://myomr.in/job/{id}/{slug}";`
- [ ] JSON-LD schema: Verify in browser DevTools
  ```json
  {
    "@type": "JobPosting",
    "title": "Senior Developer",
    "description": "...",
    "jobLocation": "OMR",
    "employmentType": "FULL_TIME",
    "baseSalary": {...}
  }
  ```
- [ ] Meta tags: Title, description, og:url set

### 9. Dry Run Verification

- [ ] Run template with `$test_mode = true`
- [ ] Expected: SQL INSERT logged, no errors
- [ ] Check: Schema constraints would pass (column types, FK refs)

### 10. Architect Approval

- [ ] Create `IMPLEMENTATION-SUMMARY.md` in sprint folder
- [ ] Include: Job ID, slug, category, locality, privacy flag setting
- [ ] Wait for architect approval before going live

### 11. Execute on Live DB

- [ ] Set `$test_mode = false` (or remove it)
- [ ] Run: `php dev-tools/jobs/insert_*.php`
- [ ] Capture job ID printed to console

### 12. Verify Detail Page

- [ ] Navigate to: `https://myomr.in/job/{id}/{slug}`
- [ ] Check: Page loads, no 404
- [ ] Check: Title, description display correctly
- [ ] Check: Canonical URL correct (view page source)
- [ ] Check: JSON-LD schema valid (DevTools → Elements → search `jobPosting`)
- [ ] Check: Privacy flag respected:
  - If `hide_employer_phone=0`: Phone visible
  - If `hide_employer_phone=1`: Phone NOT visible (check detail + sidebar)

### 13. Update Sitemap

- [ ] Run: `php omr-local-job-listings/generate-jobs-sitemap.php`
- [ ] Verify: `grep "{id}/{slug}" omr-local-job-listings/sitemap.xml`
- [ ] Expected: URL found in sitemap

### 14. GA4 Event

- [ ] Open job detail page
- [ ] GA4 Admin → Real-Time
- [ ] Expected: `page_view` event within 5 seconds
- [ ] If job publishing event tracked: expect `job_published` event

### 15. Email Notification

- [ ] Check: Email sent to subscribers
- [ ] Verify: Job title in subject/body, CTA link works, unsubscribe link works
- [ ] Check: Privacy flag respected in email

---

## Validation

### Pre-Publish Checklist

- [ ] Job title, description, category, locality confirmed
- [ ] Slug unique for this locality (or using v2/v3)
- [ ] Category ID + locality ID + employer ID exist in DB
- [ ] Contact phone + email valid
- [ ] `hide_employer_phone` flag set (0 or 1)
- [ ] Expiry date in future (if set)

### Post-Publish Checklist

- [ ] Detail page accessible (no 404)
- [ ] Canonical URL correct
- [ ] JSON-LD schema valid
- [ ] Privacy flag respected on ALL surfaces (detail, sidebar, email)
- [ ] Sitemap includes URL
- [ ] GA4 Real-Time shows page_view event
- [ ] Email sent to subscribers
- [ ] Search Console sees URL (within 24 hours)

---

## Rollback

If job shouldn't be live:

```sql
DELETE FROM jobs WHERE id = {id};
```

Then regenerate sitemap + ping Search Console.

---

## Evidence

Document for team:

- [ ] Template script: `dev-tools/jobs/insert_*.php`
- [ ] Job ID: {ID}
- [ ] URL: https://myomr.in/job/{ID}/{slug}
- [ ] Screenshot: Detail page renders correctly
- [ ] Screenshot: Privacy flag respected (phone hidden/shown correctly)
- [ ] Screenshot: Sitemap includes URL
- [ ] Screenshot: GA4 Real-Time page_view event

---

## Related References

- [`sop/LIVE-PUBLISH-CHECKLIST-SOP.md`](./LIVE-PUBLISH-CHECKLIST-SOP.md) — Master checklist
- [`sop/DEV-TOOLS-TEMPLATES.md`](./DEV-TOOLS-TEMPLATES.md) — Template locations
- [`docs/data-model.md`](../docs/data-model.md) — Jobs table schema
- [`docs/validation.md`](../docs/validation.md) — SEO validation rules
- [`planning/DOMAIN.md`](../planning/DOMAIN.md) — Job enum values
- [`KNOWN-ISSUES.md`](../KNOWN-ISSUES.md) — #002 (phone privacy)
- [`RISKS.md`](../RISKS.md) — #002 (phone privacy audit needed)

---

**Last Updated:** 2026-05-19
