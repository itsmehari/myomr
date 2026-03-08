# MyOMR Job Portal 2026 — Deployment Checklist

## Files to Upload via cPanel File Manager

Upload these files to `/public_html/omr-local-job-listings/`:

### New / Replaced files
| Local Path | Remote Path | Action |
|---|---|---|
| `omr-local-job-listings/index.php` | `/omr-local-job-listings/index.php` | Replace |
| `omr-local-job-listings/job-detail-omr.php` | `/omr-local-job-listings/job-detail-omr.php` | Replace |
| `omr-local-job-listings/assets/job-portal-2026.css` | `/omr-local-job-listings/assets/job-portal-2026.css` | New |
| `omr-local-job-listings/assets/job-portal-2026.js` | `/omr-local-job-listings/assets/job-portal-2026.js` | New |
| `omr-local-job-listings/api/jobs.php` | `/omr-local-job-listings/api/jobs.php` | Replace |
| `omr-local-job-listings/includes/job-functions-omr.php` | `/omr-local-job-listings/includes/job-functions-omr.php` | Replace |
| `omr-local-job-listings/includes/sidebar-filters.php` | `/omr-local-job-listings/includes/sidebar-filters.php` | New |
| `omr-local-job-listings/includes/save-job.php` | `/omr-local-job-listings/includes/save-job.php` | New |
| `omr-local-job-listings/includes/save-job-alert.php` | `/omr-local-job-listings/includes/save-job-alert.php` | New |
| `omr-local-job-listings/process-application-omr.php` | `/omr-local-job-listings/process-application-omr.php` | Replace |
| `omr-local-job-listings/download-resume-omr.php` | `/omr-local-job-listings/download-resume-omr.php` | New |
| `omr-local-job-listings/uploads/` (folder + .htaccess) | `/omr-local-job-listings/uploads/` | New |
| `omr-local-job-listings/includes/seo-helper.php` | `/omr-local-job-listings/includes/seo-helper.php` | Replace |
| `omr-local-job-listings/.htaccess` | `/omr-local-job-listings/.htaccess` | New |
| `listings/post-job-omr.php` | `/listings/post-job-omr.php` | Replace (301 redirect) |
| `dev-tools/migrations/2026-03-06_p1_schema_upgrades.sql` | Run in phpMyAdmin | SQL |

---

## SQL Migration (phpMyAdmin)

Run this file in phpMyAdmin on the live database:

```
dev-tools/migrations/2026-03-06_p1_schema_upgrades.sql
```

This adds:
- `salary_min`, `salary_max` to `job_postings`
- `experience_level` ENUM column
- `is_remote` flag
- `openings_count` column
- `company_logo` to `employers`
- Indexes for performance

Then also run this to create the job alerts table (if not auto-created):

```sql
-- Add applicant_resume if your schema doesn't have it (ignore error if already exists)
-- ALTER TABLE job_applications ADD COLUMN applicant_resume VARCHAR(255) DEFAULT NULL;

CREATE TABLE IF NOT EXISTS omr_job_alerts (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email       VARCHAR(180) NOT NULL,
    keyword     VARCHAR(120) DEFAULT NULL,
    location    VARCHAR(100) DEFAULT 'OMR',
    active      TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_alert (email, keyword, location)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## Cron Job (cPanel → Cron Jobs)

Set up once a day (2 AM):
```
0 2 * * *  /usr/local/bin/php /home/myomr/public_html/omr-local-job-listings/cron/expire-jobs.php >> /home/myomr/weblog/cron-jobs.log 2>&1
```

---

## Post-Deployment Checks

- [ ] Visit https://myomr.in/omr-local-job-listings/ — verify hero, pills and job cards load
- [ ] Click a quick-filter pill — verify AJAX fetch works without full-page reload
- [ ] Sidebar category filter works on desktop
- [ ] Mobile: tap "Filters" button — sidebar drawer opens/closes
- [ ] Job card WhatsApp button → opens WhatsApp with correct message
- [ ] Job card heart button → saves job (session); turns red
- [ ] Open a job detail → highlights bar shows location, type, salary, experience
- [ ] Click "Apply Now" → modal opens with resume upload zone
- [ ] Drag a PDF onto resume zone → file name appears
- [ ] "Apply via WhatsApp" in modal → correct message pre-filled
- [ ] Check Google Rich Results: https://search.google.com/test/rich-results?url=https://myomr.in/omr-local-job-listings/job-detail-omr.php?id=1
- [ ] Run SQL migration — no errors
- [ ] Test job alert signup from sidebar — check `omr_job_alerts` table in phpMyAdmin
- [ ] Check error log: `/home/myomr/weblog/job-portal-errors.log`

---

## What's New (2026 Overhaul Summary)

### Listing Page (`index.php`)
- ✅ Hero with **Experience Level** dropdown (Fresher / 1–3 yrs / 3–5 yrs / Senior / Lead)
- ✅ **Sticky quick-filter pills** bar (All, WFH, Fresher, Part-Time, IT, Healthcare, Walk-In) with live counts
- ✅ **Two-column layout** — 280px desktop sidebar + main content
- ✅ Sidebar: category radio, job type, experience, remote toggle, location chips
- ✅ **Mobile sidebar drawer** (tap "Filters" button — slides in from left)
- ✅ Active filter tags with one-click removal
- ✅ **Featured jobs spotlight** (carousel of top 3 featured)
- ✅ Redesigned job cards with company logo/initial, badge row, description snippet
- ✅ **WhatsApp quick-apply** button on every card (if employer phone available)
- ✅ **Save job** (heart button → session storage)
- ✅ **Job alert email signup** in sidebar
- ✅ Custom pagination with prev/next
- ✅ AJAX live search + sort — URL updates via `history.pushState`

### Job Detail Page (`job-detail-omr.php`)
- ✅ **Job highlights bar** in hero: location, type, salary, experience, openings, deadline
- ✅ Company logo / initial in hero header
- ✅ **WhatsApp Apply** as primary green CTA (alongside form apply)
- ✅ **Resume/CV upload** field in apply modal (PDF/DOC, 2 MB max, drag & drop)
- ✅ **Social proof strip**: view count, applications count, posted time
- ✅ Mobile sticky bottom apply bar
- ✅ Improved job details table with experience + openings
- ✅ Trust/safety guidelines box
- ✅ Related jobs with company initials

### New Files
- `assets/job-portal-2026.css` — complete design system (tokens, cards, sidebar, hero, modal)
- `assets/job-portal-2026.js` — AJAX search, save jobs, toast, sidebar drawer
- `includes/sidebar-filters.php` — shared filter panel (desktop + mobile)
- `includes/save-job.php` — session-based save/remove endpoint
- `includes/save-job-alert.php` — job alert email subscription
- `.htaccess` — clean URLs + security headers

### Updated Files
- `api/jobs.php` — now returns `html` + `pagination` for client-side injection
- `includes/job-functions-omr.php` — added `is_featured` filter support
