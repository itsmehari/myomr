## Events Flow Matrix – Happy Paths, Errors, and Observability

This document enumerates user flows, failure modes, expected user-facing messages, and logging/analytics hooks.

---

### 1) Submission Flow (Public)

Happy path:

1. Open `/omr-local-events/post-event-omr.php`
2. Fill form → client validation passes
3. POST → CSRF verified; honeypot empty; rate-limit ok
4. Server validation ok; optional poster validated and stored
5. Row inserted into `event_submissions` (status: submitted)
6. Success page shown with submission ID

Failure modes → message → log

- Invalid CSRF → “Session expired. Please reload and resubmit.” → error_log + GA `event_submit_attempt`
- Honeypot filled → “Spam detected.” → error_log + GA
- Rate limit → “Please wait a minute before submitting another event.” → error_log + GA
- Missing required field(s) → “Please correct highlighted fields.” → GA validation fail
- Poster invalid type/size → “Invalid poster file. Only JPG/PNG/WebP up to 2MB.” → error_log
- DB insert error → “Submission failed. Please try again later.” → error_log with SQL error
 - End date < start date → “End date cannot be before start date.” → GA validation fail
 - Invalid URL (tickets/map/website) → “Enter a valid URL (https://…).” → GA validation fail
 - Paid event without price or negative price → “Please enter a valid price.” → GA validation fail

Observability

- GA events: `event_submit_start`, `event_submit_attempt`, `event_submit_success`
- Server log: all exceptions/errors with stack traces

---

### 2) Moderation Flow (Admin)

Happy path:

1. Open `/admin/manage-events-omr.php`
2. Review submission → Approve
3. Copy to `event_listings` (status: scheduled) with unique slug
4. Update submission to `approved`
5. Listing appears on public pages and sitemap

Failure modes → message → log

- Invalid ID → 400 with “Invalid ID” → error_log
- DB unavailable → 500 panel with MySQL error (dev) → error_log
- Duplicate slug → regenerate with `-2`, `-3` etc. → info log

Observability

- Admin action logs (next step): approve/reject/pause/unapprove/delete with timestamp and admin id
- GA: optional admin action events (not public)

---

### 3) Listing UX (Public)

Happy path:

1. Open `/omr-local-events/` → filters/pagination
2. Click event → detail page with share, map, calendar, ICS

Failure modes → message → log

- No results → “No events found. Try different filters or list an event.”
- Deep filter URLs → `noindex` meta set → n/a
 - Weekend/date hub with zero results → “No events for this date. Try another range.” → n/a

Observability

- GA events: `events_filter` with querystring; `event_view`; `event_map`; `event_ticket`; `event_share`; `event_calendar_add`; `event_ics_download`

---

### 4) Detail UX

Happy path: loads event data; OG/Twitter; JSON‑LD Event + Breadcrumb; share/UTM; Map; Calendar/ICS.

Failure modes → message → log

- Invalid slug/not found → 404 “Event not found.” → error_log

---

### 5) State Management (Listings)

- Pause → `status=archived` (public pages hide); Resume → `status=scheduled`
- Unapprove → copy to `event_submissions` (submitted) and delete from listings
- Delete → remove listing

User messages

- Pause/Resume: “Listing updated.”
- Unapprove: “Moved back to submissions.”
- Delete: “Listing deleted.”

---

### 6) Discoverability

- Sitemap includes: listing page, date hubs (today/weekend/month), category hubs, locality hubs, event details
- Search Console: submit sitemap and verify coverage
- JSON‑LD: Event (listing/detail), Breadcrumb (detail), Organization (site‑wide)
- Robots/noindex: deep filter combinations `noindex,follow`; hubs and key intents indexable

---

### 7) Error Messages (Canonical)

- “Session expired. Please reload and resubmit.”
- “Spam detected.”
- “Please wait a minute before submitting another event.”
- “Please correct highlighted fields.”
- “Invalid poster file. Only JPG/PNG/WebP up to 2MB.”
- “Submission failed. Please try again later.”
- “Invalid ID” / “DB not available” (admin)
- “Approval failed. Please check logs.” (dev shows detail)
- “Event not found.”

---

### 8) Logging & Review Cadence

- PHP: all errors to `/weblog/events-errors.log`; dev mode prints panels
- Weekly log review; extract recurring issues into backlog
- GA dashboards: events funnel and hubs performance
 - Search Console: weekly coverage/indexing review for `/omr-local-events/` paths

---

### 9) Landing Hubs UX (Category · Locality · Date Intents)

Happy path:

1. Open hub (e.g., `/omr-local-events/category.php?slug={slug}`)
2. Page renders intro copy (100–150 words) + 2–3 FAQ items
3. Paginated event list with internal links to related hubs

Failure modes → message → log

- Invalid or unknown slug → 404 “Page not found.” → error_log
- Empty results → “No events yet for this hub. Explore other categories/localities or list an event.” → n/a

Observability

- GA events: `events_hub_view`, `events_hub_filter`, `events_hub_paginate`, `events_hub_empty`
- Included in events sitemap; canonical set to hub URL; `noindex` on deep filtered states

---

### 10) Venue Pages UX

Happy path:

1. Open `/omr-local-events/venue.php?slug={slug}`
2. Page shows venue intro copy, map embed, upcoming events at venue
3. Cross-links to related hubs and event details

Failure modes → message → log

- Unknown venue slug → 404 “Venue not found.” → error_log
- No upcoming events → “No upcoming events at this venue.” → n/a

Observability

- GA events: `venue_view`, `venue_map`, `venue_paginate`
- Included in sitemap; canonical set; breadcrumbs shown on details

---

### 11) Newsletter Capture & Widgets

Happy path:

1. Listing/detail pages render newsletter capture form (file-based storage initially)
2. Homepage and news pages display “Featured Events” widget

Failure modes → message → log

- Invalid email → “Please enter a valid email address.” → GA validation fail
- Provider/API error (later) → “We couldn’t subscribe you right now. Please try again.” → error_log

Observability

- GA events: `newsletter_submit_attempt`, `newsletter_submit_success`, `widget_impression`

---

### 12) Clean URLs & Canonicals

- Preferred routes:
  - Listing: `/omr-local-events/`
  - Detail: `/omr-local-events/{slug}` (fallback: `event-detail-omr.php?slug={slug}`)
  - Hubs: `/omr-local-events/{category|locality|today|weekend|month}` (fallback: `*.php?slug=` forms)
- All pages emit canonical to the preferred route; sitemaps list only canonical URLs
- Deep filter combinations receive `noindex,follow` while hubs and details are indexable

---

### 13) Admin Audit Logging (Next)

- Log admin actions with timestamp and admin id: approve, reject, pause, resume, unapprove, delete
- Store in a dedicated table or append-only log; surface in admin UI for accountability
- Periodic review to detect misuse and to build operational analytics
