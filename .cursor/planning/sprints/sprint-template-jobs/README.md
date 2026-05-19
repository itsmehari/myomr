# Sprint Template: Job Publishing

**Copy this entire folder for job-related sprints.**

Template location: `.cursor/planning/sprints/sprint-template-jobs/`

---

## REQUIREMENTS.md

# Sprint {NN}: Publish {Job Title}

**What:** Publish {company/job} job listing to MyOMR with correct SEO + privacy settings

**Why:** {Business goal — e.g., "Support local hiring for OMR IT corridor"}

**Success Metric:** 
- [ ] Job live at https://myomr.in/job/{id}/{slug}
- [ ] Slug URL works (no redirects)
- [ ] JSON-LD schema valid in Search Console
- [ ] Email sent to {N} subscribers
- [ ] Phone privacy respected on all surfaces

**Primary Users:** Job seekers searching for {role} in {locality}

**In Scope:**
- [ ] Insert job to remote MySQL
- [ ] Validate slug unique per locality
- [ ] Check privacy flag
- [ ] Generate sitemap entry
- [ ] Notify subscribers

**Out of Scope:**
- Job application workflow
- Candidate resume processing

**Timeline:** 30 minutes (dry run + live)

---

## BLUEPRINT.md

# Sprint {NN}: Publish {Job Title} — Blueprint

**Architecture:**

```
User provides job details (copy/screenshot)
  ↓
Find/create template: dev-tools/jobs/insert_{company}.php
  ↓
Populate variables: title, category, locality, slug, phone, email, etc.
  ↓
Validate: slug unique, category valid, locality valid
  ↓
Dry run: $test_mode = true (no DB changes)
  ↓
Architect approves IMPLEMENTATION-SUMMARY
  ↓
Live: $test_mode = false → INSERT
  ↓
Verify: Detail page renders, sitemap includes URL
```

**Tech Decisions:**
- Use existing `dev-tools/jobs/insert_*.php` template (proven)
- Slug per locality (avoid collision)
- Phone privacy flag checked on detail + sidebar

**Edge Cases:**
- Slug collision → Use `-v2` suffix
- Phone hidden? → Verify NOT showing on sidebar, email, search
- Employer new? → Create in `employers` table first

**Risk Mitigation:**
- Dry run before live (prevents bad SQL)
- Architect approval required
- Rollback: Simple DELETE from jobs table

---

## ACCEPTANCE-CRITERIA.md

# Sprint {NN}: Publish {Job Title} — Acceptance Criteria

- [x] Dry run passed (no SQL errors)
- [x] Architect reviewed IMPLEMENTATION-SUMMARY
- [ ] Job live on myomr.in (detail page accessible)
- [ ] Slug URL correct: `/job/{id}/{title-slug}`
- [ ] Privacy flag respected:
  - If `hide_employer_phone=0`: Phone visible on detail + sidebar
  - If `hide_employer_phone=1`: Phone NOT visible anywhere
- [ ] JSON-LD schema valid (Search Console)
- [ ] Sitemap regenerated + URL included
- [ ] Email sent to subscribers (check email queue)
- [ ] Canonical URL: `https://myomr.in/job/{id}/{slug}`
- [ ] No 404 errors in server logs

**Reference:** [`.cursor/sop/JOB-INSERT-AND-SEO-SOP.md`](./../sop/JOB-INSERT-AND-SEO-SOP.md)

---

## HANDOFF-PROMPT.md

# Sprint {NN}: Publish {Job Title} — Handoff Prompt

**Builder,** publish this job to live MyOMR.

**Job Details:**
- Title: {job title}
- Company: {company name}
- Category: {IT / services / admin / retail / food / healthcare / education / manufacturing}
- Locality: {OMR / Padur / Thiruvanmiyur / Medavakkam / Perungudi / Karapakkam / Kottivakkam}
- Contact Phone: {phone}
- Contact Email: {email}
- Hide Phone?: {yes/no} → If yes, phone NOT shown on detail/sidebar/email
- Description: {full job details}
- Expected Salary: {optional}

**Steps:**

1. Read `.cursor/sop/JOB-INSERT-AND-SEO-SOP.md` (checklist)
2. Use template: `dev-tools/jobs/insert_*.php`
3. Generate slug: "{title}" → "{slug-in-lowercase-hyphens}"
4. Check slug unique: No other {locality} jobs with same slug
5. Populate template:
   ```php
   $job_title = "{job title}";
   $category_id = {enum};  // 1=IT, 2=services, etc.
   $locality_id = {enum};  // 1=OMR, 2=Padur, etc.
   $slug = "{slug}";
   $contact_phone = "{phone}";
   $contact_email = "{email}";
   $hide_employer_phone = {0 or 1};
   ```
6. Dry run: `php dev-tools/jobs/insert_*.php` (should show SQL preview, no errors)
7. Create IMPLEMENTATION-SUMMARY.md:
   ```
   Job Title: {title}
   Job ID: {generated-id}
   URL: https://myomr.in/job/{id}/{slug}
   Privacy Flag: {show/hide}
   Any notes: ...
   ```
8. Wait for architect approval
9. If approved: Run live (set `$test_mode = false`)
10. Verify: Detail page renders at URL above
11. Commit: `git add .cursor/planning/sprints/... && git commit -m "feat: sprint-{NN} published {job}"`

**QA Checklist (from SOP):**
- [ ] Slug unique per locality
- [ ] Privacy flag respected on detail + sidebar
- [ ] Detail page accessible
- [ ] Sitemap includes URL
- [ ] Email sent to subscribers

**Approval:** Architect must approve IMPLEMENTATION-SUMMARY before step 9.

---

## STATUS.md

# Sprint {NN}: Publish {Job Title} — Status

**Current Status:** IN PROGRESS

**Timeline:**
- Started: 2026-05-19
- Dry run: [date/time]
- Architect review: [pending]
- Live: [pending]
- Completed: [pending]

**Blockers:** None

**Notes:** 
- Job details from {source/user}
- Category {category} confirmed

---

## To Use This Template

```bash
# 1. Copy the template folder
cp -r .cursor/planning/sprints/sprint-template-jobs/ \
      .cursor/planning/sprints/sprint-01-publish-techcompany-job

# 2. Fill in the 4 docs with your specific job details

# 3. Commit to git
git add .cursor/planning/sprints/sprint-01-*
git commit -m "feat: sprint-01 job publishing (Tech Company)"
```

---

**Template Created:** 2026-05-19
