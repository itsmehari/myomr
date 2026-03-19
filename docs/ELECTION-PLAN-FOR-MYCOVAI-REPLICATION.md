# Election Subsite Plan for mycovai.in — Replicating the myomr.in Approach

**Purpose:** Detailed plan to build an election guide subsite on **mycovai.in** (Coimbatore / Covai) by replicating and adapting what was done on **myomr.in** (Elections 2026). Use this document in the mycovai.in project to implement step by step and to brief AI/Cursor.

**Source:** MyOMR.in Elections 2026 subsite (Phases 1–3, visibility, SEO, rich snippets, DB, docs).  
**Target:** mycovai.in — same Tamil Nadu Assembly election 2026; Coimbatore-relevant constituencies.  
**Last updated:** March 2026

---

## Part A — What MyOMR.in Did (Summary to Replicate)

### 1. Scope

- **One subsite** for the election: e.g. `/elections-2026/` (or `/election-2026/`).
- **Audience:** Local users (OMR = Chennai; for mycovai = Coimbatore and vicinity).
- **Content:** Key dates, know your constituency, find BLO, constituency pages, candidates, how to vote, FAQ, news, announcements, newsletter, quiz, results page, Tamil hub.

### 2. Phases Implemented

| Phase | What was done |
|-------|----------------|
| **Phase 1 – Foundation** | Hub `index.php` with cards; core pages (know-your-constituency, find-blo, dates, how-to-vote, faq, candidates, news, announcements, newsletter); constituency pages (one per AC); module bootstrap; nav/footer; sitemap. |
| **Phase 2 – Data & SEO** | Optional DB tables (candidates, announcements); migration + seed scripts; constituency PHP data (areas, 2021 results); BreadcrumbList + Place + FAQPage JSON-LD; Add to calendar (ICS); FAQ expanded. |
| **Phase 3 – Engagement** | Tamil hub page; countdown on hub; Share CTA (hub + constituency pages); quiz (“Are you ready to vote?”); results placeholder page; sitemap updated. |

### 3. Visibility

- **Nav:** Elections link in main nav and (if applicable) in a central hub list used by top bar + footer.
- **Footer:** Elections in “Useful Links” (from same hub list).
- **Topic hubs:** “Explore more” / hub links on other sections (jobs, events, etc.) include Elections.
- **Homepage:** Hero quick link + dedicated Elections section (heading, blurb, “View guide” CTA).
- **Sitemap:** Subsite sitemap in main sitemap index.

### 4. SEO & Rich Snippets

- **Meta:** Every page: `$page_title`, `$page_description`, `$page_keywords`, `$canonical_url`; **og/twitter:** `$og_title`, `$og_description`, `$og_url` (and twitter_*) so shares are correct.
- **Schema:** WebPage + Event (poll, counting) on hub; Event on dates; HowTo on how-to-vote; FAQPage on FAQ; Place (with `url`) on each constituency page; BreadcrumbList on all via `$breadcrumbs`.
- **Hreflang:** English hub ↔ Tamil hub with `hreflang` and “Read in English” / “தமிழில்”.

### 5. Database (Optional)

- **Tables:** e.g. `election_2026_candidates`, `election_2026_announcements`.
- **Migration script:** Runs CREATE + SEED; supports remote DB via `DB_HOST` (and optional `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME`).
- **Verify script:** CLI script to check tables exist and row counts on live DB.

### 6. Docs for AI / Handoff

- **Plan doc:** One file with overview, phases, URL map, DB, SEO, visibility, key files.
- **Subproject record:** What was done in which thread/sprint (for context when resuming).

---

## Part B — Step-by-Step Plan for mycovai.in

### B.0 — Prerequisites (mycovai.in)

- [ ] Decide **base path** (e.g. `/elections-2026/` or `/election-2026/`).
- [ ] Decide **Coimbatore-relevant ACs** (e.g. Coimbatore North, South, East, West, etc. — match ECI/SEC names and numbers).
- [ ] Confirm **poll and counting dates** (TN 2026: poll 23 Apr, counting 4 May).
- [ ] Ensure mycovai has: root bootstrap (ROOT_PATH, include-path, page-bootstrap), nav/footer components, meta.php (title, description, canonical, og, twitter, BreadcrumbList when `$breadcrumbs` set).

---

### B.1 — Phase 1: Foundation

#### 1.1 Module structure

- [ ] Create folder: `elections-2026/` (or `election-2026/`).
- [ ] Create `elections-2026/includes/bootstrap.php` that:
  - Defines `ROOT_PATH` if not set.
  - Includes root `core/include-path.php` and `components/page-bootstrap.php`.
  - Includes DB connect (e.g. `core/omr-connect.php` or mycovai equivalent).
  - Defines `ELECTIONS_2026_PATH` and `ELECTIONS_2026_BASE_URL` (e.g. `/elections-2026`).

#### 1.2 Hub page

- [ ] `elections-2026/index.php`:
  - Set `$page_title`, `$page_description`, `$page_keywords`, `$canonical_url`, `$og_type`, `$breadcrumbs`.
  - Set `$og_title`, `$og_description`, `$og_url`, `$twitter_title`, `$twitter_description`.
  - One `<h1>` (e.g. “Elections 2026 – Covai & Vicinity”).
  - **Cards/sections** linking to: Key dates, Know your constituency, Find BLO, Constituencies, Candidates, How to vote, FAQ, News, Newsletter.
  - Use site nav and footer (e.g. `covai_nav()`, `covai_footer()` or equivalent).

#### 1.3 Core pages (each with title, description, canonical, breadcrumbs, og/twitter)

- [ ] `know-your-constituency.php` — Locality → AC mapping (e.g. dropdown or text list for Coimbatore ACs).
- [ ] `find-blo.php` — Link to existing BLO finder or placeholder with ECI/SEC link.
- [ ] `dates.php` — Timeline (notification, nominations, withdrawal, poll, counting); same dates as TN 2026.
- [ ] `how-to-vote.php` — EPIC/ID, polling station, dos and don’ts.
- [ ] `faq.php` — 8–10 Q&As (dates, ID, EVM, postal ballot, which ACs cover Covai, MCC, etc.); keep in a PHP array for FAQPage schema.
- [ ] `candidates.php` — List by AC (from DB later or static).
- [ ] `news.php` — Links to local/news tagged election (or placeholder).
- [ ] `announcements.php` — ECI/party announcements (from DB or static).
- [ ] `newsletter.php` — Subscribe form (store with `source_page = 'elections-2026'` or equivalent).

#### 1.4 Constituency pages

- [ ] Create `elections-2026/constituency/` and one PHP file per AC (e.g. `coimbatore-north.php`, `coimbatore-south.php`, …).
- [ ] Each: `$page_title`, `$page_description`, `$canonical_url`, `$breadcrumbs`, og/twitter; `<h1>` with AC name and number; areas/localities; current MLA and 2021 result if available.
- [ ] Central data: `elections-2026/includes/constituency-data.php` — array keyed by AC slug (name, ac_no, district, areas, 2021 winner/runner-up/votes/margin).

#### 1.5 Sitemap

- [ ] `elections-2026/generate-sitemap.php` — Output XML with `<url>` for hub, all core pages, all constituency pages. Register this in the **main sitemap index** (e.g. `generate-sitemap-index.php`) as child sitemap.

---

### B.2 — Phase 2: Data & SEO

#### 2.1 Database (optional)

- [ ] `dev-tools/create-election-2026-tables.sql`: CREATE TABLE for `election_2026_candidates` (ac_slug, party, candidate_name, bio, photo_url, announced_at, sort_order, source, created_at), `election_2026_announcements` (announcement_date, title, source, summary, url, created_at).
- [ ] `dev-tools/seed-election-2026.sql`: INSERT sample candidates/announcements for Coimbatore ACs.
- [ ] `dev-tools/run-election-2026-migration.php`: Run both SQL files; support remote DB via `DB_HOST` (and optional env vars). Use same pattern as myomr (see README-REMOTE-DATABASE.md).
- [ ] `dev-tools/verify-election-tables-live.php`: Connect and report if tables exist and row counts.
- [ ] Update `candidates.php` and `announcements.php` to read from DB when tables exist, else show static/fallback.

#### 2.2 Constituency data

- [ ] Fill `constituency-data.php` with Coimbatore ACs: name, ac_no, district, areas, 2021 results (winner, runner_up, winner_votes, runner_votes, margin) where available.

#### 2.3 Schema and meta

- [ ] **BreadcrumbList:** Ensure every page sets `$breadcrumbs`; meta component outputs JSON-LD (root → Elections 2026 → current).
- [ ] **Place:** On each constituency page output JSON-LD Place (name, description, url = canonical, containedInPlace).
- [ ] **FAQPage:** On `faq.php` output JSON-LD FAQPage with mainEntity = Question/Answer from the FAQ array.
- [ ] **Add to calendar:** `dates-2026.ics.php` (poll + counting in ICS); link from `dates.php` “Add to calendar”.

---

### B.3 — Phase 3: Engagement & Bilingual

#### 3.1 Hub enhancements

- [ ] **Countdown:** On hub header compute “X days to poll” (poll 23 Apr 2026); after poll date show “Poll was on…” and link to results page.
- [ ] **Share CTA:** “Share this guide” card with WhatsApp and Twitter/X links (pre-filled text + canonical URL).
- [ ] **Sidebar/block:** “Are you ready to vote?” → quiz; “Results 2026” → results page.

#### 3.2 Tamil hub

- [ ] `index-tamil.php`: Tamil content mirroring hub (dates, cards for constituency, BLO, candidates, how to vote, FAQ, news). `lang="ta"`, canonical to Tamil URL.
- [ ] Hreflang: English hub has `<link rel="alternate" hreflang="ta" href="...index-tamil.php">`; Tamil hub has `hreflang="en"` and `x-default` to English; “Read in English” / “தமிழில்” links.

#### 3.3 Share on constituency pages

- [ ] Add “Share this guide” (WhatsApp + Twitter) on each constituency page with constituency-specific share text and page canonical URL.

#### 3.4 Quiz

- [ ] `quiz.php`: 4–5 questions (know AC? carry ID? poll date? BLO?). On submit: “You’re ready” or “Do this next” with links to know-your-constituency, how-to-vote, dates, find-blo, FAQ.
- [ ] Link to quiz from `how-to-vote.php`.

#### 3.5 Results page

- [ ] `results-2026.php`: Before counting date show “Counting 4 May 2026” + ECI/TN CEO links; after 4 May show list of ACs with winner slots (fill from ECI/DB when available).

#### 3.6 Sitemap

- [ ] Add to subsite sitemap: `index-tamil.php`, `quiz.php`, `results-2026.php`.

---

### B.4 — Visibility on mycovai.in

- [ ] **Central nav:** Add “Elections 2026” to the main navigation (and if you have a hub list, add it there so top bar + footer stay in sync).
- [ ] **Footer:** Include Elections 2026 in “Useful Links” (from hub list or one explicit link).
- [ ] **Topic hubs:** If you have an “Explore more” / hub-links component on jobs, events, etc., add Elections 2026 there.
- [ ] **Homepage:** Add “Elections 2026” to hero quick links (if any) and a **dedicated Elections section** (heading, one-line blurb, “View guide” button) so the feature is visible on the main index page.

---

### B.5 — SEO & Rich Snippets (Full Pass)

- [ ] **All pages:** Set `$og_title`, `$og_description`, `$og_url`, `$twitter_title`, `$twitter_description` (or equivalent) so shares use election-specific text.
- [ ] **Hub:** WebPage JSON-LD + 2× Event (Poll 23 Apr, Counting 4 May) with startDate/endDate in ISO 8601, location Tamil Nadu, url.
- [ ] **dates.php:** 2× Event JSON-LD (Poll, Counting).
- [ ] **how-to-vote.php:** HowTo JSON-LD (name, description, steps: check roll, carry ID, know station, poll day, verify VVPAT).
- [ ] **faq.php:** FAQPage (already in Phase 2).
- [ ] **Constituency:** Place with `url` = canonical.
- [ ] Validate with [Google Rich Results Test](https://search.google.com/test/rich-results) and [Schema.org Validator](https://validator.schema.org/).

---

### B.6 — Remote DB (If You Use It)

- [ ] Document in repo: how to run migration from local machine against live DB (set `DB_HOST` to live host; cPanel Remote MySQL; IP allowlist; port 3306). Copy or adapt myomr’s `README-REMOTE-DATABASE.md`.
- [ ] Run migration once with `DB_HOST=your-live-host`; run verify script to confirm tables and row counts.

---

### B.7 — Docs to Keep in mycovai.in

- [ ] **Plan doc:** Copy this file (or a shortened version) into mycovai as the single source of truth, e.g. `elections-2026/docs/ELECTIONS-2026-PLAN.md`. Adapt URLs, AC names, and base domain to mycovai.in.
- [ ] **Subproject record:** When you or AI complete a sprint, add a short `ELECTIONS-2026-SUBPROJECT-RECORD.md` (or similar) listing what was done and which files were touched, so the next session has context.

---

## Part C — URL Map Template (Adapt for mycovai.in)

Replace `https://myomr.in` with `https://mycovai.in` (or your domain) and adjust AC slugs to Coimbatore.

| Page | URL |
|------|-----|
| Hub (English) | `/elections-2026/` |
| Tamil hub | `/elections-2026/index-tamil.php` |
| Know your constituency | `/elections-2026/know-your-constituency.php` |
| Find BLO | `/elections-2026/find-blo.php` |
| Key dates | `/elections-2026/dates.php` |
| Add to calendar | `/elections-2026/dates-2026.ics.php` |
| How to vote | `/elections-2026/how-to-vote.php` |
| Quiz | `/elections-2026/quiz.php` |
| FAQ | `/elections-2026/faq.php` |
| Candidates | `/elections-2026/candidates.php` |
| News | `/elections-2026/news.php` |
| Announcements | `/elections-2026/announcements.php` |
| Newsletter | `/elections-2026/newsletter.php` |
| Results 2026 | `/elections-2026/results-2026.php` |
| Constituency (per AC) | `/elections-2026/constituency/{ac-slug}.php` |
| Sitemap | `/elections-2026/sitemap.xml` |

---

## Part D — Improvements Made on MyOMR (Apply These on mycovai)

1. **Single place for elections** — One hub; no scattered links.
2. **Constituency-specific pages** — One page per AC with areas, MLA, previous result, share CTA.
3. **Optional DB** — Candidates and announcements in DB with migration + seed; verify script for live.
4. **Remote DB support** — Migration and verify scripts work with `DB_HOST` for cPanel/remote MySQL.
5. **Full og/twitter per page** — Every elections page sets og_title, og_description, og_url so shares look correct.
6. **Rich snippets** — Event (poll/counting), HowTo (how to vote), FAQPage (FAQ), Place (constituency); BreadcrumbList everywhere.
7. **Tamil hub + hreflang** — Bilingual discovery and SEO.
8. **Countdown** — “X days to poll” on hub (and Tamil hub).
9. **Share CTA** — Hub and every constituency page (WhatsApp, Twitter).
10. **Quiz** — “Are you ready to vote?” with follow-up links.
11. **Results page** — Placeholder before counting; structure for winners after.
12. **Visibility** — Nav, footer, topic hubs, homepage section so the subsite is discoverable.
13. **Plan + subproject record** — One plan doc and one “what we did” doc for AI and handoff.

---

## Part E — Coimbatore-Specific Notes

- **Constituencies:** Confirm exact AC names and numbers from ECI/CEO TN (e.g. Coimbatore North, Coimbatore South, Coimbatore East, Coimbatore West, Singanallur, Sulur, etc.). Use these in `constituency-data.php` and in `know-your-constituency.php` (locality → AC mapping).
- **BLO:** If mycovai already has a BLO finder (e.g. under `/info/`), `find-blo.php` can redirect there; otherwise link to ECI/SEC portal.
- **News/announcements:** Wire `news.php` to local content tagged “election” or “2026”; use same announcements table or static list for ECI/party updates.
- **Newsletter:** Reuse existing newsletter table with a `source_page` or tag for elections so you can segment later.

---

## Quick Checklist (Print or Tick in mycovai)

- [ ] Phase 1: Bootstrap, hub, 9 core pages, constituency pages, sitemap.
- [ ] Phase 2: DB tables (optional), migration + verify, constituency data, Place/FAQPage schema, ICS calendar.
- [ ] Phase 3: Countdown, Share CTA, Tamil hub, quiz, results page, sitemap update.
- [ ] Visibility: Nav, footer, topic hubs, homepage section.
- [ ] SEO: og/twitter on all pages; WebPage + Event on hub/dates; HowTo on how-to-vote; FAQPage on FAQ; Place on constituency.
- [ ] Docs: Plan + subproject record in `elections-2026/docs/`.

You can place this file in the **mycovai.in** project (e.g. `docs/ELECTION-PLAN-FOR-MYCOVAI-REPLICATION.md` or `elections-2026/docs/ELECTIONS-2026-PLAN.md`) and use it to implement and improve the election subsite there.
