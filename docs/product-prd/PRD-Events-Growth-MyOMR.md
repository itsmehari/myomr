## PRD: MyOMR Events – Growth, SEO, and Acquisition

### 1. Overview

Build sustainable organic and community growth for the Events module through SEO foundations, programmatic landing pages, internal linking, local SEO, content marketing, partnerships, social/WhatsApp, email capture, and optional paid search. Target: predictable discovery and weekly event submissions from local organizers.

### 2. Goals & KPIs

- Submissions/week: 10+ within 8 weeks
- Published events/week: 8+ within 8 weeks
- Organic sessions to `/omr-local-events/`: +200% in 90 days
- CTR to event details from landing pages: >10%
- Ticket/Map click CTR on details: >8%
- Newsletter signups from events pages: 50/month

### 3. Scope

In-scope: SEO, landing pages, venue pages, widgets, internal linking, social/WhatsApp enablement, email capture, Search Console integration, measurement. Optional: Google Ads.

Out-of-scope v1: Payments/ticketing, user accounts/RSVP management.

### Canonical URL conventions (reference)

- Base: `https://myomr.in/`
- Events root (listing): `https://myomr.in/omr-local-events/`
- Events hubs:
  - Category: `https://myomr.in/omr-local-events/category/{slug}` (or `category.php?slug={slug}` until routes are live)
  - Locality: `https://myomr.in/omr-local-events/locality/{slug}` (or `locality.php?slug={slug}`)
  - Date intents: `https://myomr.in/omr-local-events/{today|weekend|month}` (or `{today.php|weekend.php|month.php}`)
- Job listings root: `https://myomr.in/omr-local-job-listings/`
- Directories root: `https://myomr.in/omr-listings/`

Rules

- All pages must include a canonical tag to the paths above.
- Sitemaps must list only canonical URLs.

### 4. Audience

- Residents of OMR seeking local events
- Event organizers (schools, RWAs, NGOs, small biz)
- MyOMR editors/ops

### 5. Requirements

#### A) SEO Foundations

- Confirm canonical URLs (listing, detail, landing pages)
- JSON-LD: Event on listing/detail, Breadcrumb on detail and list via `$breadcrumbs` (centralized in `components/meta.php`), Organization site-wide
- Open Graph/Twitter for listing/detail/landing pages
- Sitemap inclusion for all events + landing pages; submit in Search Console
- Noindex deep filter combos; allow key intents (today/weekend/month/category/locality)

#### B) Programmatic Landing Pages

- Category hubs: e.g., `/omr-local-events/category/{slug}`
- Locality hubs: e.g., `/omr-local-events/locality/{slug}`
- Date intent hubs: `/omr-local-events/today`, `/weekend`, `/month`
- Each page: title/meta, 100–150 words intro, FAQ (2–3 Qs), internal links to related hubs, paginated event listings

#### C) Venue Pages

- `/omr-local-events/venue/{slug}`: intro copy, map, upcoming events at this venue, contact snippet if available

#### D) Internal Linking & Widgets

- Embed “Featured Events” widget on homepage and news pages
- Add “List your event” CTA on listing top and detail sidebar

#### E) Local SEO

- Create/optimize Google Business Profile for MyOMR (posts weekly with events link)
- Ensure NAP in site footer; consistent citations

#### F) Content Marketing

- Weekly “This Weekend in OMR” post (news format) linking to details
- Post‑event photo stories (gallery + recap) to earn links

#### G) Outreach & Partnerships

- Colleges/RWAs/NGOs/on-campus clubs—embed widget/badge; request backlink
- “Featured for 30 days” to early partners

#### H) Social/WhatsApp

- UTM-tagged share links (already added) surfaced prominently on details
- Friday WhatsApp/Telegram curated drops with top events

#### I) Email Capture

- Newsletter signup on listing + detail; integrate Brevo/Mailchimp; start weekly digest

#### J) Optional Paid Search

- Search campaign for “events in OMR”, “this weekend OMR”; exact/phrase; negative keywords; sitelinks to hubs

#### K) Measurement

- GA events: filter use, view, share, map/ticket clicks, submit start/success
- Track landing page metrics: sessions, CTR to details, signups
- Search Console property for `/omr-local-events/` path

### 6. Work Breakdown Structure (WBS)

#### Phase 1 – Foundations (1–2 weeks)

1. Add Organization schema site‑wide and Breadcrumb on event detail
2. Update robots/noindex rules for deep filter combos; keep key intents indexable
3. Extend `generate-events-sitemap.php` to include hubs/venues
4. Submit sitemap in Search Console; verify rich results

Deliverables: schema deployed; sitemap live; Search Console verified

#### Phase 2 – Programmatic Landing Pages (2–3 weeks)

1. Category hub template + routes
2. Locality hub template + routes
3. Date intent pages (today/weekend/month) with friendly URLs
4. Intro/FAQ blocks, internal links, pagination
5. Add to sitemap; QA indexing

Deliverables: hubs indexed; performance logged; internal link network live

#### Phase 3 – Venue Pages (1–2 weeks)

1. Venue template + map embed + list upcoming events
2. Venue discovery and slugs (from events data); indexation control
3. Add to sitemap and cross-link from event details

Deliverables: venue pages indexed; cross-links in place

#### Phase 4 – Widgets & Conversion (1 week)

1. Embed Featured widget on homepage + news pages
2. Add “List your event” CTA site‑wide (header/listing/detail)
3. Newsletter form on listing/detail; integrate provider

Deliverables: widgets visible; capture active

#### Phase 5 – Content & Outreach (ongoing)

1. Weekly weekend roundup post
2. Post‑event gallery recaps
3. Partner onboarding (RWAs, colleges, NGOs) + embed badge

Deliverables: editorial cadence; partner list and backlinks

#### Phase 6 – Social & Email (ongoing)

1. Friday WhatsApp/Telegram share playbook
2. Weekly email digest workflow

Deliverables: weekly distribution to social/email

#### Phase 7 – Optional Paid Search (2–3 days to pilot)

1. Launch narrowly targeted Search campaign; sitelinks to hubs
2. Monitor; pause if CPA/MQ not met

Deliverables: test report; decision

### 7. Acceptance Criteria (examples)

- Landing pages render unique meta + intro + FAQ; noindex rules applied to deep filters
- JSON-LD validates; breadcrumbs visible; Organization schema passes
- Sitemap lists events + hubs + venues; Search Console shows discovery
- “Featured Events” widget appears on homepage/news; CTA visible on listing/detail
- Newsletter signups captured and stored in provider
- GA events visible for view/share/map/ticket; conversion funnel reports set up

### 8. Risks & Mitigations

- Thin content on hubs → add intro/FAQ; maintain minimum events threshold
- Duplicate URLs with filters → canonical + noindex fallback
- Partner onboarding lag → “Featured for 30 days” incentive; lightweight embed code

### 9. Rollout & Ownership

- Dev: implement templates/routes/schema/sitemap
- Content: write landing copy, weekend roundups, venue intros
- Outreach: partner list, embeds, weekly WhatsApp drops
- Ops: Search Console, GA dashboards, newsletter cadence

### 10. Tracking & Reporting

- Weekly GA/SC snapshot: organic clicks, impressions, CTR, top landing pages
- Submissions → approvals conversion and time‑to‑publish

---

## Execution Checklist (Tick-as-you-go)

Legend: [ ] pending [x] done [~] in progress

### Phase 1 – SEO Foundations

- [x] Event JSON‑LD on listing
- [x] Event JSON‑LD on detail
- [x] Breadcrumb JSON‑LD on detail
- [x] Open Graph + Twitter on listing/detail
- [x] Organization schema site‑wide (logo, sameAs)
- [x] Noindex deep filter combos (keep today/weekend/month/category/locality indexable)
- [x] Events sitemap generator
- [x] Extend sitemap to include hubs/venues
- [ ] Submit sitemap(s) in Search Console and verify

### Phase 2 – Programmatic Landing Pages

- [x] Category hubs: `/omr-local-events/category.php?slug={slug}` (title/meta + intro + FAQ + pagination)
- [x] Locality hubs: `/omr-local-events/locality.php?slug={slug}`
- [x] Date intents: `/omr-local-events/today.php`, `/weekend.php`, `/month.php`
- [x] Internal links between hubs; link from listing to hubs
- [x] Add hubs to sitemap; indexation QA

### Phase 3 – Venue Pages

- [x] Venue template: `/omr-local-events/venue.php?slug={slug}` (routes pending)
- [x] Auto‑discover venue slugs from event data (sitemap distinct locations)
- [x] Map link + upcoming events
- [x] Add venues to sitemap; link from event details

### Phase 4 – Widgets & Conversion

- [x] Embed “Featured Events” widget on homepage
- [x] Embed widget on news pages
- [x] Add “List your event” CTA to header (site‑wide), listing top, detail sidebar
- [x] Newsletter capture on listing + detail (file-based; provider integration pending)

### Phase 5 – Content & Outreach (Ongoing)

- [x] Weekly “This Weekend in OMR” roundup (dynamic page at `/local-news/this-weekend-in-omr.php`)
- [x] Post‑event photo recaps (template at `/local-news/event-recap.php?slug={eventSlug}`; gallery from uploads)
- [x] Partner onboarding pack (landing `/omr-local-events/partners.php` + badge asset `/assets/img/myomr-events-badge.svg` + embed code)

### Phase 6 – Social & Email (Ongoing)

- [ ] Friday WhatsApp/Telegram curated share (UTM links)
- [ ] Weekly email digest automation

### Phase 7 – Optional Paid Search

- [ ] Search campaign for “events in OMR”, “this weekend OMR” (exact/phrase)
- [ ] Sitelinks to hubs; negative keywords; budget cap; 2‑week pilot report

---

### Engineering / Ops – Supporting Tasks

- [x] Public listing page (filters, pagination)
- [x] Event detail page (map, tickets, share, Add to Calendar, ICS)
- [x] Analytics events (view, map, ticket, share, submit start/success)
- [x] UTM‑tagged share links
- [x] ICS endpoint download
- [x] Admin dashboard `/admin/` with stats + quick links
- [x] Approve submissions → publish to listings
- [x] View listings table
- [x] Pause/Resume listing (archived ↔ scheduled)
- [x] Move to Unapproved (listing → submissions)
- [x] Delete listing
- [x] Common footer present on all events pages
- [ ] Clean URLs via `.htaccess` (e.g., `/events/{slug}`)
- [x] Clean URLs via `.htaccess` (events prefix; root-centralized)
- [x] Root sitemap index in place (`/sitemap.xml`); robots points to it
- [x] Admin auth guard for all admin endpoints
- [x] Central error page + consistent logging review process

---

### Tracking & Reporting Checklist

- [ ] GA dashboard for events funnel (views → shares/tickets → submissions)
- [ ] Search Console property for `/omr-local-events/`
- [ ] Weekly KPI sheet: submissions, approvals, organic sessions, CTRs

---

## Daily Progress Log

### 2025-10-31

- Metrics (from Admin Dashboard):
  - Pending Submissions: 0
  - Approved Submissions: 2
  - Scheduled: 2
  - Ongoing: 0
- Completed today:
  - Super-admin dashboard `/omr-local-events/admin/` with stats + quick links
  - Approved listings management: pause/resume, unapprove (move to submissions), delete
  - Approve flow hardening and in-page diagnostics for 500s
  - Auto-approve (temporary) to unblock testing; submission path stabilized
  - Events JSON‑LD (listing/detail), OG/Twitter (listing/detail), sitemap generator
  - UTM share links, GA events (view, map, ticket, share, submit start/success)
  - Common footer verified on all public events pages
  - PRD and checklist consolidated; Learnings linked at project root
- Next actions (high priority):
  - Organization schema + Breadcrumb JSON‑LD on detail
  - Programmatic landing pages (category/locality/date) internal links & QA indexing
  - Venue pages (map + upcoming events) and internal linking
  - Noindex rules for deep filters; keep key intents indexable
  - Newsletter capture on listing/detail; widget on homepage/news

Later updates (same day):

- Master admin login + role scaffold added; guards applied to all events admin endpoints
- Root admin dashboard with module registry
- Breadcrumb JSON‑LD added on event detail; deep filter `noindex` applied on listing
- Sitemap extended to include category/locality/date hubs (venues pending)
- Dev diagnostics panel added across events HTML pages; conditional Organization schema include to prevent fatal if missing
