# Events Feature Revamp – Phased Plan

**Created:** 10 Mar 2026  
**Status:** Draft – Triage  
**Scope:** Content, Design, Code, Modularity, SEO – Full Revamp of `/omr-local-events/`

---

## 1. Competitor Analysis

### 1.1 Competitors Reviewed

| Competitor | URL | Focus |
|------------|-----|-------|
| **Chennai Central** | chennaicentral.in/events/ | Chennai city events, location & type filters |
| **EventSeeker** | eventseeker.com/chennai | Multi-city, category tabs, ticket links, collections |
| **BookMyShow** | in.bookmyshow.com/explore/events-chennai | Ticket booking, price display, venue, SEO content |

*Note: Event Always (eventalways.com/chennai) was blocked by Cloudflare during analysis.*

---

### 1.2 Competitor Structure & Best Features

#### Chennai Central
- **Layout:** Left sidebar filters + grid of event cards
- **Filters:** Event Locations (Nandanam, Teynampet, ECR, **OMR**, etc.) with event counts per location
- **Event Types:** Entertainment, Music, Art, Fashion, Food, Health, Shopping – with counts
- **Cards:** Large image, title, Start/End date clearly shown
- **SEO:** H1 "Events in Chennai", keyword-rich intro, long-tail list (events in Chennai today, concerts, weekend events, etc.)

#### EventSeeker
- **Layout:** Category tabs as primary navigation (Concerts, Theater, Sports, Arts & Museums, Dance, Nightlife, Educational, Festivals, Family, Community, Business, Tours, Online)
- **Featured:** Hero-style featured event with ticket CTA
- **Popular Events:** Date-based list with calendar visual
- **Collections:** "Best Annual Events in Chennai", "Best Food Festivals"
- **User features:** Sync accounts (Spotify, Deezer), Newsletter, Mobile app download
- **Ticket integration:** Direct links to ticket partners (e.g. Fever)

#### BookMyShow
- **Layout:** Event cards with venue, category, price
- **Cards show:** Title, Venue, Category, **Price** (e.g. "₹ 3999 onwards", "₹ 250 onwards")
- **Categories:** Comedy, Music, Workshop, Online streaming, Art Exhibitions, Education, etc.
- **SEO:** Dedicated content blocks – "How to book", "Top 3 Event Categories", "Hassle-free experience"
- **Routing:** Category filters, Today/Tomorrow/This Weekend links

---

## 2. MyOMR Events – Current State

### 2.1 Structure
- **Index:** `/omr-local-events/index.php` – filter form (search, category, locality, free, date range) + paginated grid
- **Detail:** `event-detail-omr.php` – Event schema, breadcrumbs, CTA
- **Time-based views:** `today.php`, `weekend.php`, `month.php`
- **Taxonomy views:** `category.php`, `locality.php`, `venue.php`
- **Submit:** `post-event-omr.php` → `process-event-omr.php`
- **Admin:** Manage, approve, reject, export CSV/ICS
- **Widget:** `top-featured-events-widget.php` (used on news page, **not** on homepage)

### 2.2 Database
- `event_listings`, `event_submissions`, `event_categories`, `organizers`, `event_attendees`
- Fields: title, slug, category_id, location, locality, start/end datetime, is_free, price, tickets_url, image_url, featured, status

### 2.3 Routing (.htaccess)
- `/omr-local-events/event/{slug}`
- `/omr-local-events/category/{slug}`
- `/omr-local-events/locality/{slug}`
- `/omr-local-events/venue/{slug}`
- `/omr-local-events/today`, `/weekend`, `/month`
- `/omr-local-events/post` → post form

---

## 3. Gap Analysis

### 3.1 Content
| Gap | Competitor | MyOMR Now |
|-----|------------|-----------|
| SEO landing content | BMS, Chennai Central have keyword-rich intros | Minimal intro on index |
| Collections / curated lists | EventSeeker | None |
| "Events this weekend" prominence | Chennai Central, BMS | Page exists but not surfaced on index |
| Long-tail keyword pages | Chennai Central | Limited |

### 3.2 Design
| Gap | Competitor | MyOMR Now |
|-----|------------|-----------|
| Price on cards | BookMyShow | Not shown on listing cards |
| Large event images on cards | Chennai Central, BMS | May use smaller thumbnails |
| Location filter with counts | Chennai Central | Locality filter exists, no counts |
| Category filter with counts | Chennai Central | Category dropdown, no counts |
| Featured/hero event block | EventSeeker | `featured` in DB, underutilized in UI |
| Sidebar filters vs toolbar | Chennai Central | Single toolbar form |
| "View Details" / "Book" CTA on cards | All | Links via title only |

### 3.3 Code & Modularity
| Gap | Area | MyOMR Now |
|-----|------|-----------|
| Events widget on homepage | index.php | **None** – Jobs have scroll banner, News have bulletin, Events only in hero link |
| Hero search routing | index.php | Search form submits to **jobs** only; Events option in select not routed |
| Reusable event card component | – | Card markup repeated in index, weekend, etc. |
| Config for categories/counts | – | Categories from DB; filter counts not displayed |

### 3.4 SEO
| Gap | Area |
|-----|------|
| Index page meta | Basic; filtered views noindex (OK) |
| Event schema | Implemented on detail page ✓ |
| Sitemap | Events sitemap ✓ |
| Keyword pages | No dedicated "events in OMR today", "weekend events OMR" content blocks |
| Internal links | Limited cross-linking from news/places to events |

---

## 4. Modularity vs index.php Homepage

### 4.1 Current index.php Sections
1. **Hero** – Slides, search (→ jobs), quick links, CTA
2. **Jobs scroll banner** – Conditional on `$recent_jobs`
3. **News bulletin** – `myomr-news-bulletin.php` + `featured-news-links.php`
4. **Subscribe** – Form + social
5. **Footer** – Shared component

### 4.2 Events on Homepage
- **Present:** Hero quick link "Events" → `/omr-local-events/`
- **Present:** Hero slide "Discover Local Events"
- **Missing:** No dedicated events section (unlike Jobs banner, News bulletin)
- **Missing:** Hero search does not route to events when category=events

**Recommendation:** Add modular events section (e.g. `homepage-events-widget.php`) similar to jobs banner or a compact "Upcoming Events" strip.

---

## 5. Phased Revamp Plan

### Phase 1: Content & SEO (Low Risk)
**Goal:** Improve discoverability and keyword coverage.

| Task | Details |
|------|---------|
| 1.1 | Add keyword-rich intro block on events index (OMR events, community events, workshops, etc.) |
| 1.2 | Add small content blocks for "Events in OMR today", "Weekend events", "Free events" with links |
| 1.3 | Surface Today / Weekend / Month as prominent quick links on index |
| 1.4 | Add `events-in-omr` / `weekend-events-omr` etc. to internal link hubs |
| 1.5 | Review meta titles/descriptions for today, weekend, month, category pages |
| 1.6 | Add FAQ / "How to list an event" block (like BMS) for SEO |

**Effort:** ~1–2 days  
**Files:** `omr-local-events/index.php`, `today.php`, `weekend.php`, `month.php`, `components/internal-links-hubs.php`

---

### Phase 2: Design – Cards & Filters (Medium Risk)
**Goal:** Match competitor card quality and filter UX.

| Task | Details |
|------|---------|
| 2.1 | Redesign event cards: larger image, price badge (if paid), venue/locality, clear date, "View Details" button |
| 2.2 | Add filter counts next to category and locality options |
| 2.3 | Add sidebar or collapsible filters (location + type) with counts, like Chennai Central |
| 2.4 | Add featured/hero event block at top of index (1–2 events) |
| 2.5 | Create reusable `event-card.php` component |
| 2.6 | Add "Free" badge on cards when `is_free = 1` |
| 2.7 | Ensure responsive grid (3 cols desktop, 2 tablet, 1 mobile) |

**Effort:** ~2–3 days  
**Files:** `omr-local-events/index.php`, `assets/events-dashboard.css`, new `components/event-card.php`, `event-functions-omr.php` (count queries)

---

### Phase 3: Modularity & Homepage Integration (Medium Risk)
**Goal:** Align events with Jobs and News modularity on homepage.

| Task | Details |
|------|---------|
| 3.1 | Create `components/homepage-events-widget.php` – top 3–6 upcoming events, compact layout |
| 3.2 | Include events widget in `index.php` (below news or alongside jobs) |
| 3.3 | Fix hero search: when `category=events`, submit to `/omr-local-events/?search=...` |
| 3.4 | Add Rent & Lease to `homepage-categories.php` if not present; ensure Events count displayed |
| 3.5 | Use `event-card.php` (or compact variant) in homepage widget and events index |

**Effort:** ~1–2 days  
**Files:** `index.php`, `core/homepage-categories.php`, new `components/homepage-events-widget.php`

---

### Phase 4: Feature Enhancements (Higher Risk)
**Goal:** Add high-value features from competitors.

| Task | Details |
|------|---------|
| 4.1 | Venue pages: ensure `/venue/{slug}` is linked from cards and detail page |
| 4.2 | Add "Get Tickets" / "Register" CTA when `tickets_url` present |
| 4.3 | Add collections/curated pages (e.g. "Community events in OMR", "Free weekend events") |
| 4.4 | Add "Add to Calendar" (.ics) link on detail page (check `event-ics.php`) |
| 4.5 | Newsletter signup specific to events (leverage `newsletter-signup.php`) |
| 4.6 | Consider map embed on detail page for location |

**Effort:** ~2–3 days  
**Files:** `event-detail-omr.php`, `event-ics.php`, `components/event-card.php`, new collection pages

---

### Phase 5: Code Quality & Performance
**Goal:** Clean architecture, performance, maintainability.

| Task | Details |
|------|---------|
| 5.1 | Centralize event card rendering in `event-card.php` |
| 5.2 | Add filter count helpers in `event-functions-omr.php` |
| 5.3 | Lazy-load images on event cards |
| 5.4 | Ensure event list queries use indexes (dates, category, status) |
| 5.5 | Review and deduplicate CSS (events-dashboard.css vs jobs-unified) |
| 5.6 | Document events module in `docs/` (structure, flows) |

**Effort:** ~1–2 days  
**Files:** `event-functions-omr.php`, `components/event-card.php`, `assets/`, docs

---

### Phase 6: Analytics & Conversion
**Goal:** Track events feature usage and conversions.

| Task | Details |
|------|---------|
| 6.1 | GA4 content group for `/omr-local-events/` (already present – verify) |
| 6.2 | Track "View Details", "Get Tickets", "Add to Calendar" as events |
| 6.3 | Track filter usage (category, locality) if meaningful |
| 6.4 | Add UTM params for partner/embed links |

**Effort:** ~0.5–1 day  
**Files:** `components/analytics.php`, `event-detail-omr.php`, `assets/events-analytics.js`

---

## 6. Implementation Order

| Phase | Dependency | Priority |
|-------|-------------|----------|
| Phase 1: Content & SEO | None | High |
| Phase 2: Design | None | High |
| Phase 3: Modularity | Phase 2 (card component) | High |
| Phase 4: Features | Phase 2, 3 | Medium |
| Phase 5: Code Quality | Phase 2–4 | Medium |
| Phase 6: Analytics | Phase 2–4 | Low |

**Recommended start:** Phase 1 + Phase 2 in parallel (content + card redesign), then Phase 3.

---

## 7. Success Metrics

- Events index organic traffic growth
- Increase in "View Details" and "Get Tickets" clicks
- Increase in event submissions
- Time on events pages
- Bounce rate on events index vs competitors

---

## 8. References

- `omr-local-events/EVENTS-FLOW-MATRIX.md`
- `omr-local-events/EVENTS-REQUIREMENTS-BRIEF.md`
- `omr-local-events/SEO-ANALYTICS-CHECKLIST.md`
- `docs/ARCHITECTURE.md`
- `core/homepage-categories.php`
- `components/myomr-news-bulletin.php` (modularity pattern)
