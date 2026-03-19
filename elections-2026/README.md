# Elections 2026 Subsite

One place for OMR and Chennai South: Tamil Nadu Assembly election 2026 – key dates, constituency finder, BLO, candidates, news and FAQ.

## URLs

- **Hub:** `/elections-2026/`
- **Know your constituency:** `/elections-2026/know-your-constituency.php`
- **Find BLO:** `/elections-2026/find-blo.php` (redirects to `/info/find-blo-officer.php`)
- **Key dates:** `/elections-2026/dates.php` (with “Add to calendar” → `dates-2026.ics.php`)
- **How to vote:** `/elections-2026/how-to-vote.php`
- **FAQ:** `/elections-2026/faq.php`
- **Candidates:** `/elections-2026/candidates.php`
- **News:** `/elections-2026/news.php`
- **Announcements:** `/elections-2026/announcements.php`
- **Newsletter:** `/elections-2026/newsletter.php`
- **Tamil hub:** `/elections-2026/index-tamil.php`
- **Quiz:** `/elections-2026/quiz.php`
- **Results 2026:** `/elections-2026/results-2026.php`
- **Constituency pages:** `/elections-2026/constituency/sholinganallur.php`, `velachery.php`, `thiruporur.php`
- **Sitemap:** `/elections-2026/sitemap.xml` (via `generate-sitemap.php`)

**Plan and context for AI:** See `docs/ELECTIONS-2026-PLAN.md` (full plan) and `docs/ELECTIONS-2026-SUBPROJECT-RECORD.md` (what was done in the Phase 3 thread).

## Optional database tables

Tables live in this module. You can either run SQL by hand or use the migration script.

**Option A – From repo (when DB is reachable):**  
From repo root: `php elections-2026/dev-tools/run-election-2026-migration.php`  
For **remote DB** (cPanel), set `DB_HOST=myomr.in` (or your MySQL host) and run the script. See **[dev-tools/README-REMOTE-DB.md](dev-tools/README-REMOTE-DB.md)** for how the connection works, prerequisites (PHP, mysqli, outbound port 3306, cPanel Remote MySQL), and why it may fail on another laptop (different IP, blocked port, etc.).

**Option B – phpMyAdmin:**  
1. Run **`elections-2026/dev-tools/create-election-2026-tables.sql`** to create:
   - `election_2026_candidates` – ac_slug, party, candidate_name, bio, announced_at, sort_order
   - `election_2026_announcements` – announcement_date, title, source, summary, url
2. Run **`elections-2026/dev-tools/seed-election-2026.sql`** to load NTK candidates (OMR) and ECI/NTK announcements.

Without these tables, candidates and announcements pages show static/fallback content. To refresh candidates after nominations, use ECI affidavit portal (affidavit.eci.gov.in) and party announcements.

**Verify tables on live:** From repo root run: `DB_HOST=myomr.in php elections-2026/dev-tools/verify-election-tables-live.php` (PowerShell: `$env:DB_HOST='myomr.in'; php elections-2026/dev-tools/verify-election-tables-live.php`). The script reports whether both tables exist and their row counts.

## Newsletter

Subscription is stored in `omr_newsletter_subscribers` with `source_page = 'elections-2026'`.

## Bootstrap

All pages use `includes/bootstrap.php` (ROOT_PATH, component-includes, omr-connect). Same pattern as omr-hostels-pgs and other modules.
