# Learnings

Recorded learnings from MyOMR.in development — patterns, decisions, and gotchas.

---

## WhatsApp community group CTA (March 2026)

### What we did

- Added "Join our WhatsApp group" for connectivity and updates in multiple places.
- Introduced a **single source of truth** for the group link so it can be changed in one place.

### Key decisions

1. **Central constant**  
   The WhatsApp group invite URL is defined in `core/include-path.php` as `MYOMR_WHATSAPP_GROUP_URL`. Any new CTA (hero, article, job, footer, etc.) should use this constant with a fallback for edge cases where include-path wasn’t loaded:
   `defined('MYOMR_WHATSAPP_GROUP_URL') ? MYOMR_WHATSAPP_GROUP_URL : 'https://chat.whatsapp.com/...'`

2. **Where we placed CTAs (and why)**
   - **Homepage hero:** Next to "Join the Community" — high visibility for new visitors.
   - **Article detail:** After share bar (compact link) + in existing community section — readers already engaged.
   - **Job detail:** Sidebar block after "Share This Job" — separate from "Apply via WhatsApp" (employer); clear "community" vs "apply" distinction.
   - **Footer:** WhatsApp icon in "Follow us" — one consistent place site-wide.

3. **Analytics**  
   Footer’s existing GA4 click tracker matches `wa.me` and `api.whatsapp.com`. The group link is `chat.whatsapp.com`, so "Join group" clicks are not currently sent as `whatsapp_click`. To track them, extend the condition or add a dedicated event.

### Files involved

- `core/include-path.php` — constant.
- `index.php` — hero CTA.
- `local-news/article.php` — share-bar CTA + community section link.
- `omr-local-job-listings/job-detail-omr.php` — sidebar block.
- `components/footer.php` — Follow us icon.
- `assets/css/footer.css` — `.footer-social__link--wa`.

### Takeaway

For any **site-wide link** (e.g. community group, main contact), define it once in `core/` and reference it everywhere so updates are trivial and consistent.

---

## Facebook group CTA (March 2026)

### What we did

- Added "Join our Facebook group" CTAs in high-visibility community touchpoints.
- Followed the same single-source pattern used for WhatsApp group links.

### Key decisions

1. **Central constant**  
   The Facebook group URL is defined in `core/include-path.php` as `MYOMR_FACEBOOK_GROUP_URL`. Any new CTA should use this constant with a fallback for safety:
   `defined('MYOMR_FACEBOOK_GROUP_URL') ? MYOMR_FACEBOOK_GROUP_URL : 'https://www.facebook.com/groups/416854920508620'`

2. **Where we placed CTAs (and why)**
   - **Homepage hero:** Added next to existing community and WhatsApp CTAs for maximum visibility.
   - **Article detail:** Added compact link next to the share-bar WhatsApp CTA line.
   - **Job detail:** Added dedicated sidebar block after WhatsApp community block.
   - **Footer:** Added dedicated Facebook group icon/link in "Follow us" without removing existing social links.

### Files involved

- `core/include-path.php` — `MYOMR_FACEBOOK_GROUP_URL`.
- `index.php` — hero "Join Facebook Group" CTA.
- `local-news/article.php` — compact Facebook group CTA near share bar.
- `omr-local-job-listings/job-detail-omr.php` — Facebook group sidebar block.
- `components/footer.php` — Facebook group icon/link in Follow us.
- `assets/css/footer.css` — `.footer-social__link--fb-group`.

### Takeaway

All site-wide community links (WhatsApp, Facebook group, and future channels) should live in `core/include-path.php` and be consumed via constants with fallbacks.

---

## Jobs segmentation + high-conversion redesign (March 2026)

### What we did

- Implemented Office vs Manpower job segmentation in both data and UI layers.
- Redesigned job detail into a high-conversion layout (strong CTA hierarchy, proof cues, quick facts).
- Rolled segment cues to listing, filters, API/AJAX cards, landing pages, and sitemap entries.

### Key decisions

1. **DB-first segment model with UI fallback**  
   Added `work_segment` to `job_postings` and backfilled existing rows.  
   UI also supports inference fallback (`inferJobSegmentFromData`) to avoid breaking older deployments.

2. **Deployment-safe helper strategy**  
   Real-world issue: production served newer landing page file with older helper include, causing `undefined function jobPostingsHasColumn()`.  
   Fix: add local `function_exists(...)` fallbacks inside `office-jobs-omr-chennai.php` and `manpower-jobs-omr-chennai.php` for critical helper calls.

3. **Conversion hierarchy on detail page**  
   Order that performs better for local job users:
   - Value/role header
   - Apply + WhatsApp CTAs
   - Social proof/urgency
   - Scannable facts
   - Full description and requirements

4. **SSR/AJAX visual consistency is mandatory**  
   The dynamic listing renderer must match server-rendered card classes/structure, otherwise design drifts under live filtering.

5. **Inline-style heavy templates are fragile**  
   Moved repeated inline styles to `job-portal-2026.css` classes. This reduces visual regressions and speeds future redesign work.

### Files involved (core)

- `omr-local-job-listings/includes/job-functions-omr.php`
- `omr-local-job-listings/process-job-omr.php`
- `omr-local-job-listings/post-job-omr.php`
- `omr-local-job-listings/edit-job-omr.php`
- `omr-local-job-listings/job-detail-omr.php`
- `omr-local-job-listings/index.php`
- `omr-local-job-listings/api/jobs.php`
- `omr-local-job-listings/assets/job-portal-2026.css`
- `omr-local-job-listings/assets/job-ajax-search.js`
- `omr-local-job-listings/assets/job-analytics-events.js`
- `office-jobs-omr-chennai.php`
- `manpower-jobs-omr-chennai.php`

### Takeaway

When shipping feature + design + DB changes together on MyOMR:

- keep runtime fallbacks for helper-version mismatch,
- keep API/SSR markup parity,
- and sequence rollout as DB -> helper -> templates -> CSS/JS -> sitemap/indexing.

---

## Sitelinks + Search Console automation (March 2026)

### What we did

- Implemented a repo-level sitelinks optimization pass (navigation consistency, canonical/sitemap hygiene, schema alignment, hub cross-linking).
- Added service-account based Search Console automation and re-submitted root + child sitemaps programmatically.

### Key decisions

1. **Single source for crawl-critical nav hubs**  
   Added `core/site-navigation.php` and reused it across nav/footer/schema to prevent label/path drift for sitelink candidates.

2. **Footer links must be root-absolute**  
   Replaced dynamic `$baseUrl` composition in footer with root-relative links (`/path`) to avoid subfolder/path ambiguity.

3. **Nav schema should be generated from real nav data**  
   Added `SiteNavigationElement` JSON-LD generation in `components/meta.php` from the same hub links used by UI.

4. **Canonical + sitemap cleanup before authority work**  
   Removed stale sitemap index references and redirected legacy `info/sitemap.xml` to `/sitemap.xml` in `.htaccess`.

5. **Hub authority should be explicit and reusable**  
   Added shared cross-hub component (`components/omr-topic-hubs.php`) and injected into major OMR hubs for non-branded intent reinforcement.

6. **Search Console API is reliable for submit/verify**  
   Direct HTTP fetches from the environment returned `406` on live endpoints, but Search Console API accepted submissions and returned canonical status metadata (`isPending`, `errors`, `warnings`, `lastSubmitted`).

### Files involved (primary)

- `core/site-navigation.php`
- `components/main-nav.php`
- `components/footer.php`
- `components/meta.php`
- `generate-sitemap-index.php`
- `.htaccess`
- `omr-listings/index.php`
- `components/omr-topic-hubs.php`
- `jobs-in-omr-chennai.php`
- `omr-local-job-listings/index.php`
- `omr-local-events/index.php`
- `omr-buy-sell/index.php`
- `docs/analytics-seo/OMR-SITELINKS-IMPLEMENTATION-MAP.md`

### Search Console automation notes

- Service account must be added as **Full** user in property users/permissions.
- Working property identifier used: `sc-domain:myomr.in`.
- Python packages required for CLI automation:
  - `google-api-python-client`
  - `google-auth`
  - `google-auth-httplib2`
- Programmatic submit endpoint used via API client: `sitemaps().submit(...)`.

### Security takeaway

- Never paste full service-account JSON (especially `private_key`) into logs/docs/chats.
- If key content was exposed during setup, rotate/reissue the key after configuration.

### MCP setup takeaway

- Cursor MCP failures with `No such file or directory` were caused by missing server script path.
- Creating `dev-tools/mcp/search_console_mcp.py` resolved server boot failures for `user-search-console`.
- For MCP reliability, validate in order:
  1. script path exists
  2. python can run script
  3. credentials env path is valid
  4. Search Console property access includes service account email

---

## Session task-summary pattern (March 2026)

### What to record after each implementation session

1. **What was implemented** (code/files/modules touched)
2. **What was migrated** (DB schema/data updates and verification snapshot)
3. **What broke and how it was fixed** (runtime mismatch, fallback, deploy order)
4. **What was attempted operationally** (sitemap/indexing/SC actions)
5. **What remains pending** (explicit next actions with commands)

### Practical checklist learned

- If helper functions are newly introduced, protect critical entry pages with `function_exists()` fallback paths during transitional deployments.
- After DB + template changes, verify:
  - schema presence,
  - UI rendering on live URL,
  - sitemap generation success,
  - Search Console submission status.
- Keep a short pending list at end of updates to reduce handoff loss.

### Reusable pending-task template

- [ ] Deploy changed files to production
- [ ] Regenerate sitemap on server (`php .../generate-sitemap.php`)
- [ ] Submit sitemap in Search Console
- [ ] Request/re-notify indexing (if Indexing API is enabled)
- [ ] Run live smoke checks (desktop + mobile + key CTAs)

---

## Remote database connection (March 2026)

### What we did

- Ran Elections 2026 DB migration (create tables + seed) from the local machine against the **remote** MySQL database on cPanel (myomr.in).
- Migration script uses **environment variable `DB_HOST`**: when set, PHP connects to that host instead of `localhost`, so the same script works locally (no DB_HOST) or against remote (DB_HOST=myomr.in).
- Documented prerequisites (local: PHP CLI, mysqli, outbound 3306; server: cPanel Remote MySQL, IP allowlist) and why connection can work on one laptop but fail on another (different public IP, blocked port, etc.).

### Key decisions

1. **Env-based remote host**  
   Script reads `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME`. If `DB_HOST` is set, it builds the connection with those values; otherwise it uses `core/omr-connect.php`. Credentials default to omr-connect values so repo stays single-source; only host (and optionally port) is overridden for remote.

2. **One README at repo root**  
   Created `README-REMOTE-DATABASE.md` in the project root as the main reference for any remote DB use (migrations, scripts). Module-specific copy remains in `elections-2026/dev-tools/README-REMOTE-DB.md` for that module’s migration.

3. **Why “other laptop” fails**  
   Each network has its own **public IP**. cPanel Remote MySQL allows connections only from IPs you add. So the second laptop must have **its** public IP added in cPanel; also, that network must allow outbound TCP to port 3306 (many corporate/school Wi‑Fi block it).

### Files involved

- `README-REMOTE-DATABASE.md` (root) — how connection works, prerequisites, commands, checklist for failing laptop.
- `elections-2026/dev-tools/run-election-2026-migration.php` — supports `DB_HOST` (and optional `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME`) for remote run.
- `elections-2026/dev-tools/README-REMOTE-DB.md` — same content as root README, scoped to elections migration.
- `elections-2026/README.md` — points to Option A (script with DB_HOST) and Option B (phpMyAdmin), and links to README-REMOTE-DB.

### Takeaway

For **running migrations or any PHP script against the remote DB from a dev machine**: set `DB_HOST` to the server hostname, ensure that machine’s **public IP** is in cPanel Remote MySQL, and that the network allows outbound 3306. Refer to `README-REMOTE-DATABASE.md` for full prerequisites and troubleshooting.

---

## Replication and one-off operations (March 2026)

### Replication to another project

When replicating a MyOMR feature to another project (e.g. banner ads to mycovai.in): (a) analyse how it works in this codebase (registry, slots, placements, helpers), and (b) produce a short prompt or implementation summary tailored to the target project's structure. See [.cursor/skills/myomr-project/SKILL.md](.cursor/skills/myomr-project/SKILL.md) (Banner ads) and [docs/inbox/BANNER-ADS-IMPLEMENTATION-PLAN-FOR-MYCOVAI.md](docs/inbox/BANNER-ADS-IMPLEMENTATION-PLAN-FOR-MYCOVAI.md) for an example.

### Manual job posting (SQL)

For support or one-off job posts (bypassing the normal employer form flow): insert/update `employers`, then insert into `job_postings` with `status='approved'`; ensure title/description are SEO-friendly. After insert, regenerate the job sitemap and consider submitting in Search Console. In application code, always use prepared statements; this note is for occasional manual SQL only. See [docs/workflows-pipelines/job-portal-workflow.md](docs/workflows-pipelines/job-portal-workflow.md) for the normal flow.

---

## Hostels & PGs bootstrap fatal — resilient require (March 2026)

### What we did

- Investigated live error on https://myomr.in/omr-hostels-pgs/ (blank page, PHP fatal).
- Used browser tool to compare hub links: Hostels failed; Rent & Lease and Buy & Sell loaded.
- Identified root cause: production was missing `omr-hostels-pgs/includes/error-reporting.php` while bootstrap required it unconditionally.
- Implemented a resilient require so the page works even when that file is absent; applied the same pattern to Buy & Sell for consistency.

### Key decisions

1. **Root cause**  
   `omr-hostels-pgs/includes/bootstrap.php` had `require_once __DIR__ . '/error-reporting.php';`. The file exists in the repo and is not gitignored; on production it was missing (e.g. stale or partial deploy). Unconditional require caused a fatal before any output.

2. **Why only Hostels broke**  
   Rent & Lease does not use a shared bootstrap; its `index.php` requires `error-reporting.php` directly, and that file was present on server. Buy & Sell uses a bootstrap that also requires `error-reporting.php`; that file was present. So only `omr-hostels-pgs/includes/` was missing the file.

3. **Resilient require pattern**  
   Load optional/support files only if they exist so a missing file never fatals:
   ```php
   $err_report = __DIR__ . '/error-reporting.php';
   if (file_exists($err_report)) {
       require_once $err_report;
   }
   ```
   This restores the page immediately and prevents the same failure if the file is ever missing again.

4. **Apply to both bootstrap modules**  
   Hostels and Buy & Sell both use `includes/bootstrap.php` that required `error-reporting.php`. We applied the same pattern to both so neither module can fatal on a missing error-reporting file.

### Files involved

- `omr-hostels-pgs/includes/bootstrap.php` — resilient require for `error-reporting.php`.
- `omr-buy-sell/includes/bootstrap.php` — same pattern.
- `.cpanel.yml` — deploys via `cp -R omr-hostels-pgs ...`; in theory the file should be copied; ops should redeploy and confirm `omr-hostels-pgs/includes/error-reporting.php` exists on server.

### Investigation method

- Grep for `error-reporting.php` and bootstrap usage across the repo.
- Check that `omr-hostels-pgs/includes/error-reporting.php` exists locally and is not in `.gitignore`.
- Use browser MCP to open Hostels, Rent & Lease, and Buy & Sell and compare page state (title, refs) to confirm only Hostels failed.

### Takeaway

For **module bootstrap files** that pull in optional or environment-specific includes (e.g. error-reporting, dev helpers): use `file_exists()` before `require_once` so a missing file does not fatal. Redeploy to ensure all expected files are present on production; the resilient pattern is a safety net, not a substitute for correct deployment.

---

## CTA modals + homepage ad/Buy & Sell layout (March 2026)

### What we did

- Fixed CTA modals (Hire on OMR, Employer Pack, Job alerts) so they work in Chrome and look consistent in Firefox: no raw markup in flow, no overlapping, one modal at a time, Adobe-style design.
- Merged the homepage top ad with the Buy & Sell section in a single row: 65% Buy & Sell (left), 35% ad (right); removed the standalone top ad block that consumed vertical space.
- Added auto-show behaviour: one modal on first visit per session (after ~1.5s), then one modal every 5 minutes in rotation (Post job → Employer Pack → Subscribe → repeat).
- Synced changes to GitHub (commit + push to `origin main`).

### Key decisions

1. **Modal visibility depends on the framework**  
   `components/modal-cta.php` uses Bootstrap 5 markup (`.modal.fade`, `.modal-dialog`) and `bootstrap.Modal` in JS. The homepage and other pages using the main footer did **not** load Bootstrap 5, so modals had no `display: none` and no show/hide logic — they appeared as unstyled blocks and could overlap. Fix: load Bootstrap 5 CSS and JS where the component is used (we did it in `components/footer.php` before including modal-cta), and add **defensive CSS** so the three CTA modal IDs are hidden by default and only shown when they have the `.show` class. That way even before Bootstrap runs, they never appear in the document flow.

2. **Defensive CSS in a global stylesheet**  
   Added rules in `assets/css/main.css` (loaded on all pages) scoped to `#omrCtaPostJob`, `#omrCtaEmployerPack`, `#omrCtaSubscribe`: `display: none !important` by default, and `display: block !important` when `.show` is present. This acts as a fallback for any page that might render the footer before Bootstrap CSS is applied.

3. **Two modal systems coexist**  
   The codebase has (a) a legacy overlay: `#modalOverlay` + `core/modal.js` / `core/modal.css`; (b) Bootstrap CTA modals in the footer. Don’t mix them. The defensive and Adobe-style CSS are scoped to the CTA modal IDs so the legacy modal is unaffected.

4. **Adobe-style overrides in a separate file**  
   Created `assets/css/modal-cta-adobe.css` (loaded after Bootstrap in the footer) to override only the CTA modals: dimmed backdrop, max-width 480px, rounded corners, shadow, clear header/close/body/footer, MyOMR green for primary buttons. No change to modal HTML structure; all look-and-feel in CSS.

5. **Homepage: one row 65/35 instead of stacked ad + section**  
   Removed the full-width `omr_ad_slot_row('homepage-top', 2)` block. Wrapped the Buy & Sell include and a single `omr_ad_slot('homepage-top', '336x280')` in a grid row (`.homepage-buysell-ad-row`) with `65fr 35fr` at 992px and single column below. Right column holds one ad; left column gets the existing Buy & Sell section. This saves vertical space and keeps the ad visible without dominating.

6. **Buy & Sell in a narrower column**  
   When the section sits in the 65% column, three card columns felt cramped. In `homepage-myomr.css`, scoped overrides for `.homepage-buysell-ad-row__left .buy-sell-homepage-section__cards` cap the grid at 2 columns at all breakpoints (no 3-column layout in that context).

7. **Auto-show: first visit + 5‑minute rotation**  
   Used `sessionStorage`: (a) `myomr_cta_first_visit_shown` — if unset, show the first modal after 1.5s and set it so we don’t show again on refresh in the same session; (b) `myomr_cta_rotation_index` — which modal to show next (0, 1, 2). On first visit we show index 0 and set next to 1. A `setInterval(5 * 60 * 1000)` closes any open CTA modal and shows the next in rotation, then increments the stored index. So over time users see Post job → Employer Pack → Subscribe → Post job → …

8. **Git / PowerShell**  
   On Windows PowerShell, command chaining uses `;` not `&&`. For “upload/sync to GitHub”: `git add -A`, `git commit -m "..."`, `git push origin main` (with existing `origin` pointing at the GitHub repo).

### Files involved

- `assets/css/main.css` — defensive hide/show for CTA modal IDs.
- `components/footer.php` — Bootstrap 5 CSS link, `modal-cta-adobe.css` link, Bootstrap 5 JS script, then `modal-cta.php`.
- `assets/css/modal-cta-adobe.css` — new; Adobe-style overrides for CTA modals.
- `components/modal-cta.php` — Bootstrap modal markup; JS for triggers, close, first-visit pop, 5‑min rotation.
- `index.php` — removed top ad block; added `.homepage-buysell-ad-row` with left (Buy & Sell include) and right (single ad slot).
- `assets/css/homepage-myomr.css` — `.homepage-buysell-ad-row` (grid 65fr/35fr, stacked on small screens), `.homepage-buysell-ad-row__right` and ad slot styles, Buy & Sell max 2 columns when inside `__left`.

### Takeaway

When a **UI component depends on a framework** (e.g. Bootstrap) that isn’t loaded on every page: either load that framework on every page that includes the component (e.g. in the footer before the component) or accept that the component will break there. Add **defensive CSS** (hide by default, show only when the framework adds a “shown” class) so the component never appears as raw markup in the flow. For **homepage layout**, merging an ad with a content section in one row (e.g. 65/35) can reduce vertical space and keep the ad visible; limit the content column’s internal grid when it’s narrower so cards stay readable. For **recurring modals**, first-visit (sessionStorage) + fixed-interval rotation (setInterval + stored index) is a simple, session-scoped pattern.
