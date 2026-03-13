# Events Module (omr-local-events) – Structure & Flows

**Module path:** `/omr-local-events/`  
**Purpose:** Local events directory for OMR Chennai – browse, filter, submit, and promote events.

---

## Structure

| File / folder | Purpose |
|---------------|--------|
| `index.php` | Main listing: filters (search, category, locality, free, date), featured block, quick links (Today / Weekend / Month / Free), paginated grid, SEO intro, FAQ block |
| `today.php`, `weekend.php`, `month.php` | Time-based views; all use `components/event-card.php` |
| `event-detail-omr.php` | Single event: schema, breadcrumbs, Get Tickets, Add to Calendar, ICS download, venue/locality/category links, map embed |
| `event-ics.php` | ICS download for calendar (query: `?slug=`) |
| `post-event-omr.php` → `process-event-omr.php` | Submit event flow |
| `category.php`, `locality.php`, `venue.php` | Taxonomy listing pages (`?slug=`) |
| `includes/event-functions-omr.php` | DB helpers: getEvents, getEventCount, getCategoryCounts, getLocalityCounts, getFeaturedEvents, getEventBySlug, etc. |
| `components/event-card.php` | Reusable card: image, Free/price badge, date, location, locality + venue links, View Details, Get Tickets, map link |
| `components/homepage-events-widget.php` | Used on site homepage (root `index.php`); expects `$recent_events` |
| `assets/events-dashboard.css` | Event card and dashboard styles |
| `assets/events-analytics.js` | GA event tracking: viewClicked, getTickets, addToCalendar, icsDownloaded, shareClicked, filterUsed |

---

## Data

- **Tables:** `event_listings`, `event_submissions`, `event_categories`, `organizers`, `event_attendees`
- **Listing fields:** title, slug, category_id, location, locality, start/end datetime, is_free, price, tickets_url, image_url, featured, status (`scheduled` / `ongoing` shown)

---

## Integration

- **Homepage:** Root `index.php` loads `$recent_events` from DB and includes `components/homepage-events-widget.php` when non-empty; hero search with category “Events” routes to `/omr-local-events/`.
- **Analytics:** `components/analytics.php` maps `/omr-local-events/` to content group “Local Events”. `events-analytics.js` binds to `[data-analytics]` and `[data-analytics-label]` for event/ticket/calendar/share tracking.
- **Accessibility:** Skip-link and `id="main-content"` on index, today, weekend, month, event-detail.

---

## References

- `docs/inbox/EVENTS-FEATURE-REVAMP-PHASED-PLAN.md`
- `omr-local-events/EVENTS-FLOW-MATRIX.md`, `SEO-ANALYTICS-CHECKLIST.md`
