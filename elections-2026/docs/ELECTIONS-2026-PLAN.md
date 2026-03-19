# Elections 2026 – Complete Plan and Structure

**Purpose:** Single reference for the Tamil Nadu Assembly Election 2026 subsite on MyOMR: scope, phases, database, URLs, and how to extend or update. For AI/Cursor context when resuming work.

**Last updated:** March 2026

---

## 1. Overview

- **Subsite:** One place for OMR and Chennai South for TN Assembly election 2026.
- **Key dates (2026):** Poll 23 April, counting 4 May. Notification 30 March, nominations by 6 April, withdrawal by 9 April.
- **OMR-related ACs:** Sholinganallur (AC 27), Velachery (AC 26), Thiruporur (and nearby).
- **Stack:** PHP (procedural), MySQL, Bootstrap 5, same bootstrap as other MyOMR modules (`includes/bootstrap.php`).

---

## 2. Phases (WBS)

### Phase 1 – Foundation (Done)

- Hub page `index.php` with section cards (dates, know your constituency, BLO, constituencies, candidates, how to vote, FAQ, news, newsletter).
- Core pages: `know-your-constituency.php`, `find-blo.php`, `dates.php`, `how-to-vote.php`, `faq.php`, `candidates.php`, `news.php`, `announcements.php`, `newsletter.php`.
- Constituency pages: `constituency/sholinganallur.php`, `velachery.php`, `thiruporur.php` (2021 results, areas, MLA info).
- Bootstrap and path setup: `includes/bootstrap.php`, `ELECTIONS_2026_BASE_URL`, `ROOT_PATH`.
- Navigation and footer via `omr_nav()`, `omr_footer()` from root components.
- Sitemap: `generate-sitemap.php` → `/elections-2026/sitemap.xml`.

### Phase 2 – Data and SEO (Done)

- **Seed data and DB:**
  - Tables: `election_2026_candidates`, `election_2026_announcements`.
  - SQL: `dev-tools/create-election-2026-tables.sql`, `dev-tools/seed-election-2026.sql`.
  - Migration script: `dev-tools/run-election-2026-migration.php` (supports remote DB via `DB_HOST`, etc.).
- **Constituency data (PHP):** `includes/constituency-data.php` – `$elections_2026_constituencies` with keys `sholinganallur`, `velachery`, `thiruporur`; 2021 results (winner, runner-up, votes, margin) where available.
- **SEO:** BreadcrumbList and Place JSON-LD on hub and constituency pages; FAQPage JSON-LD on FAQ; canonical URLs; meta description/keywords.
- **Add to calendar:** `dates-2026.ics.php` (poll 23 Apr, counting 4 May); “Add to calendar” link on `dates.php`.
- **FAQ:** Expanded to 9 questions (constituency finder, MCC violations, etc.).

### Phase 3 – Engagement and Bilingual (Done)

- **Tamil hub:** `index-tamil.php` – Tamil content, `lang="ta"`, canonical to Tamil URL, `hreflang="en"` to English hub; “Read in English” link; countdown in Tamil.
- **English hub:** Countdown (“X days to poll” / “Poll was on…”) in header; “Share this guide” (WhatsApp, Twitter/X); “Are you ready to vote?” quiz card; “Results 2026” sidebar card.
- **Share CTA:** On hub and on all three AC pages (Sholinganallur, Velachery, Thiruporur) – constituency-specific share text.
- **Quiz:** `quiz.php` – 4 questions (constituency, ID, poll date, BLO); “You’re ready” or “Do this next” with links to BLO, EPIC, FAQ, etc.; link from `how-to-vote.php`.
- **Results 2026:** `results-2026.php` – Before 4 May: placeholder + ECI/TN CEO links; after 4 May: structure for winners (to be filled from ECI/DB).
- **Sitemap:** Added `index-tamil.php`, `quiz.php`, `results-2026.php`.

---

## 3. Database Tables (Live / Remote)

### Tables

| Table | Purpose |
|-------|--------|
| `election_2026_candidates` | One row per declared candidate per AC. Columns: `id`, `ac_slug` (sholinganallur, velachery, thiruporur), `party`, `candidate_name`, `bio`, `photo_url`, `announced_at`, `sort_order`, `source`, `created_at`. |
| `election_2026_announcements` | ECI, SEC, party announcements. Columns: `id`, `announcement_date`, `title`, `source`, `summary`, `url`, `created_at`. |

### Deployment to live DB

- **Option A (CLI, from repo):** Set `DB_HOST` to the live MySQL host (e.g. `myomr.in`), then run from repo root:  
  `php elections-2026/dev-tools/run-election-2026-migration.php`  
  See root `README-REMOTE-DATABASE.md` and `elections-2026/dev-tools/README-REMOTE-DB.md` for prerequisites (Remote MySQL in cPanel, IP allowlist, port 3306).
- **Option B (phpMyAdmin):** On the live database, run in order:  
  1) `create-election-2026-tables.sql`,  
  2) `seed-election-2026.sql`.

### Verifying tables on live

- Run the verification script against the live DB (same env as migration):  
  `DB_HOST=myomr.in php elections-2026/dev-tools/verify-election-tables-live.php`  
  It reports whether both tables exist and their row counts. See `elections-2026/dev-tools/README-REMOTE-DB.md` (or root remote DB README) for connection requirements.

---

## 4. URL Map

| Page | URL |
|------|-----|
| Hub (English) | `/elections-2026/` (`index.php`) |
| Tamil hub | `/elections-2026/index-tamil.php` |
| Know your constituency | `/elections-2026/know-your-constituency.php` |
| Find BLO | `/elections-2026/find-blo.php` |
| Key dates | `/elections-2026/dates.php` |
| Add to calendar (ICS) | `/elections-2026/dates-2026.ics.php` |
| How to vote | `/elections-2026/how-to-vote.php` |
| Quiz | `/elections-2026/quiz.php` |
| FAQ | `/elections-2026/faq.php` |
| Candidates | `/elections-2026/candidates.php` |
| News | `/elections-2026/news.php` |
| Announcements | `/elections-2026/announcements.php` |
| Newsletter | `/elections-2026/newsletter.php` |
| Results 2026 | `/elections-2026/results-2026.php` |
| Sholinganallur AC | `/elections-2026/constituency/sholinganallur.php` |
| Velachery AC | `/elections-2026/constituency/velachery.php` |
| Thiruporur | `/elections-2026/constituency/thiruporur.php` |
| Sitemap | `/elections-2026/sitemap.xml` (via `generate-sitemap.php`) |

---

## 5. Key Files

| Role | Path |
|------|------|
| Module bootstrap | `elections-2026/includes/bootstrap.php` |
| Constituency data (2021, areas, MLA) | `elections-2026/includes/constituency-data.php` |
| Create tables | `elections-2026/dev-tools/create-election-2026-tables.sql` |
| Seed data | `elections-2026/dev-tools/seed-election-2026.sql` |
| Run migration | `elections-2026/dev-tools/run-election-2026-migration.php` |
| Verify tables on live | `elections-2026/dev-tools/verify-election-tables-live.php` |
| Remote DB instructions | Root `README-REMOTE-DATABASE.md`, `elections-2026/dev-tools/README-REMOTE-DB.md` |

---

## 6. Future / Post–4 May 2026

- **Results:** Populate `results-2026.php` with winners for Sholinganallur, Velachery, Thiruporur from ECI/CEO TN (or DB if stored).
- **Candidates/announcements:** Refresh from ECI affidavit portal and party announcements; update DB or seed as needed.
- **Newsletter:** Subscriptions in `omr_newsletter_subscribers` with `source_page = 'elections-2026'`.

---

## 7. SEO and rich snippets

### Meta (all elections pages)

- **Title, description, keywords:** Set per page (`$page_title`, `$page_description`, `$page_keywords`); output via `components/meta.php`.
- **Canonical:** `$canonical_url` set on every page; base `https://myomr.in`, no www.
- **Open Graph & Twitter:** All elections pages set `$og_title`, `$og_description`, `$og_url` (and `$twitter_title`, `$twitter_description`) so shares use election-specific text and the correct URL.

### Structured data (JSON-LD)

| Page | Schema | Purpose |
|------|--------|---------|
| **Hub (index.php)** | WebPage + 2× Event | WebPage (name, description, url); Event “Poll” (2026-04-23 07:00–18:00 IST); Event “Counting” (2026-05-04). Supports event rich results. |
| **dates.php** | 2× Event | Poll and Counting events with startDate (ISO 8601), location Tamil Nadu, url. |
| **how-to-vote.php** | HowTo | Name, description, steps (check roll, carry ID, know polling station, poll day, verify VVPAT). HowTo rich results. |
| **faq.php** | FAQPage | mainEntity array of Question/Answer from `$faqs`. FAQ rich results (expandable in SERP). |
| **Constituency (3 ACs)** | Place | name, description, url (canonical), containedInPlace (Chennai South / Chengalpattu). |

### Breadcrumbs

- All pages set `$breadcrumbs`; `meta.php` outputs **BreadcrumbList** JSON-LD when present. Root → Elections 2026 → current page.

### Hreflang (hub)

- English hub: `<link rel="alternate" hreflang="ta" href="...index-tamil.php">`; Tamil hub: `hreflang="en"` and `x-default` to English.

### Validation

- Test with [Google Rich Results Test](https://search.google.com/test/rich-results) and [Schema.org Validator](https://validator.schema.org/). Fix any reported errors.

---

## 8. Visibility on the website

Elections 2026 is surfaced so visitors and search engines can find it:

| Channel | Where | Notes |
|--------|--------|------|
| **Main nav** | Primary menu (main-nav.php) | “Elections 2026” link; active when path contains `elections-2026`. |
| **Top secondary bar** | `site-navigation.php` → `myomr_get_primary_hub_links()` | “Elections 2026” included in hub links; icon `fa-vote-yea` in main-nav. |
| **Footer** | “Useful Links” | From same `myomr_get_primary_hub_links()` (Elections 2026 in central list). |
| **Topic hubs** | `components/omr-topic-hubs.php` | “Explore More OMR Road Hubs” on Jobs, Events, Buy & Sell, Explore Places, etc. includes Elections 2026. |
| **Homepage** | Hero quick links + dedicated strip | Hero: “Elections 2026” in quick links; below buy-sell: Elections 2026 strip (heading, short blurb, “View guide” CTA). |
| **Sitemap** | `generate-sitemap-index.php` | Child sitemap `/elections-2026/sitemap.xml` in index for Search Console. |

To add or change visibility, edit: `core/site-navigation.php`, `components/omr-topic-hubs.php`, `components/main-nav.php`, `components/footer.php`, and `index.php` (hero + Elections strip).

---

## 9. Related Project Docs

- **AGENTS.md** (root): Remote DB, canonical URLs, 404 handling, modular bootstrap.
- **Cursor rules:** `.cursor/rules/cursor-ai-rules.md`.
- **Subproject record (this thread):** `elections-2026/docs/ELECTIONS-2026-SUBPROJECT-RECORD.md`.
