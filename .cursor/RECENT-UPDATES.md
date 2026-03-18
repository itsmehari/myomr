# Project recent updates and changes

Summary of notable changes for deployment and context. Keep this updated when shipping features or refactors.

---

## March 2026

### Session summary: learnings/tasks snapshot (latest)

**Completed in this session:**

- Implemented jobs segmentation + high-conversion jobs UI rollout in code (detail/listing/API/landing/sitemap scope).
- Executed remote DB migration for `job_postings.work_segment` with backfill + index.
- Fixed production fatal (`undefined function jobPostingsHasColumn`) by adding guarded fallbacks in segment landing pages.
- Updated project knowledge docs:
  - `.cursor/RECENT-UPDATES.md`
  - `.cursor/LEARNINGS.md`
  - `.cursor/skills/myomr-project/SKILL.md`

**Operational tasks attempted:**

- Search Console sitemap submit via API:
  - `sc-domain:myomr.in` submit: success
  - URL-prefix property submit: permission blocked
- Indexing API URL notifications: blocked because API not enabled in GCP project.

**Still pending (ops/deploy):**

- Deploy latest jobs module/design files to production.
- Regenerate jobs sitemap in server environment:
  - `php omr-local-job-listings/generate-sitemap.php`
- Enable Web Search Indexing API (optional, only if API-based URL push is required).
- Add SA permissions for URL-prefix property (`https://myomr.in/`) if that property submission is needed.

### SERP sitelinks implementation + Search Console API submissions

**Shipped in code (workspace):** Implemented the refined MyOMR sitelinks strategy across navigation, schema, canonical/sitemap hygiene, and hub authority internal links.

| Change | Location |
|--------|----------|
| Shared crawl-critical hub navigation source | `core/site-navigation.php` |
| Header aligned to hub links (consistent labels/URLs) | `components/main-nav.php` |
| Footer link normalization (root-absolute links, no dynamic base URL) | `components/footer.php` |
| `SiteNavigationElement` JSON-LD generation + Organization schema normalization | `components/meta.php` |
| Stale sitemap index cleanup | `generate-sitemap-index.php` |
| Legacy sitemap redirect to canonical sitemap index | `.htaccess` (`/info/sitemap.xml` -> `/sitemap.xml`) |
| Listings hub migrated to modular stack with canonical/meta consistency | `omr-listings/index.php` |
| Reusable hub cross-link block for non-branded topic authority | `components/omr-topic-hubs.php` |
| Hub cross-link rollout | `jobs-in-omr-chennai.php`, `omr-local-job-listings/index.php`, `omr-local-events/index.php`, `omr-buy-sell/index.php`, `omr-listings/index.php` |
| Query/page mapping + 8-week tracking sheet | `docs/analytics-seo/OMR-SITELINKS-IMPLEMENTATION-MAP.md` |

**Validation completed:**

- Lint check on modified files: no issues.
- PHP syntax checks on modified files: no syntax errors.

**Search Console API automation (new):**

- Service account created and granted **Full** access in Search Console.
- Root sitemap submitted for `sc-domain:myomr.in`:
  - `https://myomr.in/sitemap.xml`
- Child sitemap submission + verification executed programmatically:
  - `https://myomr.in/sitemap-pages.xml`
  - `https://myomr.in/local-news/sitemap.xml`
  - `https://myomr.in/omr-listings/sitemap.xml`
  - `https://myomr.in/it-parks/sitemap.xml`
  - `https://myomr.in/omr-local-events/sitemap.xml`
  - `https://myomr.in/omr-local-job-listings/sitemap.xml`
  - `https://myomr.in/omr-hostels-pgs/sitemap.xml`
  - `https://myomr.in/omr-coworking-spaces/sitemap.xml`
  - `https://myomr.in/omr-buy-sell/sitemap.xml`
  - `https://myomr.in/pentahive/sitemap.xml`
  - `https://myomr.in/elections-2026/sitemap.xml`

**Operational note:**

- Live endpoint checks from this environment returned `406` for direct HTTP fetches, but Search Console API submissions succeeded and returned status fields (`isPending`, `errors`, `warnings`, `lastSubmitted`).
- Follow-up is required after recrawl to resolve sitemap-level historical errors (notably local-news and a few module sitemaps with `errors=1`).

### Job segmentation + high-conversion jobs design rollout

**Shipped in code (workspace):** End-to-end Office vs Manpower segmentation and high-conversion redesign across job detail, listing, API-rendered cards, and SEO landing pages.

| Change | Location |
|--------|----------|
| Segment classification in jobs flow (`work_segment`) with safe helper fallbacks | `omr-local-job-listings/includes/job-functions-omr.php`, `process-job-omr.php`, `post-job-omr.php`, `edit-job-omr.php` |
| Detail page conversion redesign (hero CTA panel, conversion strip, quick facts, trust/community blocks, reduced inline styles) | `omr-local-job-listings/job-detail-omr.php`, `omr-local-job-listings/assets/job-portal-2026.css` |
| Listing + sidebar + card segment UI | `omr-local-job-listings/index.php`, `omr-local-job-listings/includes/sidebar-filters.php`, `omr-local-job-listings/assets/job-portal-2026.css` |
| API + AJAX parity between SSR and dynamic cards | `omr-local-job-listings/api/jobs.php`, `omr-local-job-listings/assets/job-ajax-search.js` |
| Office/Manpower SEO landing pages | `office-jobs-omr-chennai.php`, `manpower-jobs-omr-chennai.php`, `omr-local-job-listings/includes/landing-page-template.php` |
| Internal linking and segment discoverability | `components/job-related-landing-pages.php`, `jobs-in-omr-chennai.php` |
| Jobs sitemap includes segment landing pages | `omr-local-job-listings/generate-sitemap.php` |
| Detail-page conversion analytics events (apply/WhatsApp CTA) | `omr-local-job-listings/assets/job-analytics-events.js` |

**Database update executed (remote):**

- Added `job_postings.work_segment` (`office`, `manpower`, `hybrid`) with backfill + default + index.
- Result snapshot after migration: `office=10`, `manpower=5`.

**Ops note:** A production mismatch caused `undefined function jobPostingsHasColumn()` on `office-jobs-omr-chennai.php`. Fixed by adding fallback local helper functions in both segment landing pages to keep them deployment-safe.

### WhatsApp community group CTA rollout

**Shipped:** "Join our WhatsApp group" for connectivity and updates in multiple locations.

| Change | Location |
|--------|----------|
| Central constant for group URL | `core/include-path.php` — `MYOMR_WHATSAPP_GROUP_URL` |
| Hero CTA | `index.php` — "Join WhatsApp Group" button next to "Join the Community" |
| Article page | `local-news/article.php` — compact CTA after share bar; community section link uses constant |
| Job detail sidebar | `omr-local-job-listings/job-detail-omr.php` — "Join Our WhatsApp Group" block after Share This Job |
| Footer | `components/footer.php` — WhatsApp icon in Follow us; `assets/css/footer.css` — `.footer-social__link--wa` |

**Deploy checklist (6 files):**

- [ ] `core/include-path.php`
- [ ] `index.php`
- [ ] `local-news/article.php`
- [ ] `omr-local-job-listings/job-detail-omr.php`
- [ ] `components/footer.php`
- [ ] `assets/css/footer.css`

**Learnings:** See [.cursor/LEARNINGS.md](.cursor/LEARNINGS.md).  
**Skill reference:** [.cursor/skills/myomr-project/SKILL.md](.cursor/skills/myomr-project/SKILL.md) — WhatsApp community CTA section.

### Facebook group CTA rollout

**Shipped:** "Join our Facebook group" CTAs across homepage, article detail, job detail, and footer using a central constant + fallback pattern.

| Change | Location |
|--------|----------|
| Central constant for Facebook group URL | `core/include-path.php` — `MYOMR_FACEBOOK_GROUP_URL` |
| Hero CTA | `index.php` — "Join Facebook Group" button next to existing community CTAs |
| Article page | `local-news/article.php` — compact CTA near share bar/WhatsApp CTA line |
| Job detail sidebar | `omr-local-job-listings/job-detail-omr.php` — "Join Our Facebook Group" block after WhatsApp community block |
| Footer | `components/footer.php` — Facebook group icon/link in Follow us; `assets/css/footer.css` — `.footer-social__link--fb-group` |

**Deploy checklist (6 files):**

- [ ] `core/include-path.php`
- [ ] `index.php`
- [ ] `local-news/article.php`
- [ ] `omr-local-job-listings/job-detail-omr.php`
- [ ] `components/footer.php`
- [ ] `assets/css/footer.css`

**Docs updated:**

- [ ] `.cursor/LEARNINGS.md`
- [ ] `.cursor/RECENT-UPDATES.md`
- [ ] `.cursor/skills/myomr-project/SKILL.md`
