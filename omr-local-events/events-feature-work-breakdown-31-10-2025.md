## MyOMR Events Feature · Work Breakdown Structure

This document guides the end-to-end build of the public “List an Event” capability for MyOMR. Tasks are grouped by phases we can execute sequentially; each phase has clear deliverables and dependencies. Check items off here as we progress.

---

### Phase 0 · Alignment & Discovery

- [x] Confirm feature goals, KPIs, and success metrics with stakeholders (coverage, submissions, approvals, engagement)
- [x] Define core user personas (event host, local resident/attendee, MyOMR editor)
- [x] Inventory existing content/routes to consolidate (e.g., `/events/` directory)
- [x] Capture regulatory/compliance needs (permits, community guidelines, consent)
- [x] Approve naming convention: folder `omr-local-events/` mirroring job module style

**Deliverable:** Requirement brief + updated sitemap entries.

---

### Phase 0.5 · Flow Mapping & Edge Cases (NEW)

- [x] Produce end‑to‑end flow matrix covering happy and error paths for:
  - Submission (start → validate → submit → pending/auto‑approve → success screen)
  - Moderation (list → inspect → edit → approve → publish to listings → reject with note)
  - Listing UX (filters → pagination → empty states → no upcoming events → past/archived)
  - Detail UX (map/tickets/share/calendar/ICS) and failure handling
  - Media (poster too large/invalid), links (invalid URL), date logic (end < start), free/paid pricing
  - Permissions (admin only paths), rate limiting & spam, CSRF
  - Discoverability (sitemap refresh, canonical, JSON‑LD present)
- [x] Define user‑visible error messages for each failure mode
- [x] Define logging/observability for each step (server logs + GA events)

**Deliverable:** Flow matrix checklist linked from this WBS; all flows validated before launch.

See: `omr-local-events/EVENTS-FLOW-MATRIX.md`

---

### Phase 1 · Information Architecture & Data Model

- [x] Draft ER diagram for core tables: `event_categories`, `event_listings`, `event_submissions`, `event_attendees` (optional), `organizers`
- [x] Define status workflow (`draft`, `submitted`, `approved`, `rejected`, `archived`)
- [x] Plan slug strategy (`omr-event/{slug}`) and URL rewrite updates
- [x] Enumerate form fields (public view vs. submission) with validation rules
- [x] Review storage needs for images/attachments and decide on upload policy

**Deliverable:** `CREATE-EVENTS-DATABASE.sql` initial draft + schema notes.

---

### Phase 2 · Backend Foundation

- [x] Spin up folder skeleton:
  - `omr-local-events/index.php`
  - `omr-local-events/event-detail-omr.php`
  - `omr-local-events/post-event-omr.php`
  - `omr-local-events/process-event-omr.php`
  - `omr-local-events/admin/…`
  - `omr-local-events/includes/event-functions-omr.php`
  - `omr-local-events/assets/…`
- [x] Port `includes/error-reporting.php` for dev visibility
- [x] Implement database connection guards & helper bootstrap similar to jobs module
- [x] Build reusable utilities (slugger, sanitizer, pagination, filtering)

**Deliverable:** Autoload-ready helper file with stubs + folder structure committed.

---

### Phase 3 · Public Event Discovery Experience

- [x] Design & implement `index.php` listing page with filters (date range, category, locality, free/paid)
- [x] Integrate pagination, featured badge logic, and map/CTA placeholders
- [x] Craft modern hero + announcement banner, consistent with unified design system
- [x] Add structured data (`Event` schema per list item) and meta tags
- [x] Wire “List an Event” CTA to submission flow

**Deliverable:** Fully styled, responsive events listing page pulling sample data.

---

### Phase 4 · Event Submission Workflow

- [x] Build multi-step form (`post-event-omr.php`) aligned with glassmorphism UI components
- [x] Implement client-side validation (dates, contact info, rich descriptions)
- [x] Add server-side validation, CSRF tokens, and spam mitigation (CSRF + honeypot + rate limit)
- [x] Handle optional media uploads (poster image) with type/size checks
- [x] Create confirmation screen (`event-submitted-success-omr.php`) mirroring job success UX

**Deliverable:** Secure submission pipeline storing data in `event_submissions` with pending status.

---

### Phase 5 · Admin & Moderation Tools

- [x] Develop admin dashboard (`admin/manage-events-omr.php`) showing queues, filters, bulk actions (initial queue ✅; actions wired)
- [x] Allow approval/rejection (basic). Editing and email notifications pending
- [ ] Provide calendar view/export (ICS or CSV)
- [ ] Implement audit logging (who approved, timestamps)
- [ ] Add “My Submitted Events” page for authenticated organizers (optional v1.1)

**Deliverable:** Admin workflow enabling content team to vet and publish events safely.

---

### Phase 6 · Publishing & Automation

- [ ] Automate approved event publication (move from submissions to listings table)
- [x] Generate event detail pages with canonical slugs and share cards
- [x] Produce events sitemap (`generate-events-sitemap.php`)
- [ ] Hook into newsletter/notifications pipeline (optional: Mailchimp/Brevo integration)
- [ ] Implement `robots.txt` adjustments for new paths

**Deliverable:** Fully automated publication pipeline + discoverability assets.

---

### Phase 7 · Analytics, SEO & Outreach

- [x] Integrate Google Analytics events (submission start, completion, share/map/ticket clicks)
- [x] Build UTM-ready share links (WhatsApp, Twitter/X, Facebook)
- [ ] Craft SEO checklist doc (keywords, meta, local targeting, cross-links)
- [x] Add OG/Twitter cards for event detail pages and listing
- [x] Plan cross-promotion widgets (widget added: `components/top-featured-events-widget.php`)

**Deliverable:** `SEO-ANALYTICS-CHECKLIST.md` created; GA events wired; UTM shares live.

**Deliverable:** Measurable funnel from traffic to submissions with SEO guardrails.

---

### Phase 8 · QA, Security & Accessibility

- [ ] Run security review (SQL injection, XSS, CSRF, file uploads)
- [ ] Validate accessibility (WCAG 2.1 AA): focus states, semantic headings, ARIA alerts
- [ ] Execute device/browser QA checklist (desktop/mobile/tablet)
- [ ] Test failure scenarios (invalid data, large uploads, network interruptions)
- [ ] Create regression test scripts mirroring job feature coverage

**Deliverable:** QA sign-off checklist + remediation log.

---

### Phase 9 · Launch & Operations

- [x] Update homepage quick-action button with live event submission URL
- [x] Add deployment checklist specific to events module
- [x] Schedule initial content seeding (seed 3–5 example events)
- [x] Train content/admin team using quick-start guide
- [x] Monitor logs (`/weblog/events-errors.log`) for first-week triage

**Deliverable:** Launch-ready feature with monitoring + handover completed.

---

### Phase 10 · Documentation & Continuous Improvement

- [ ] Maintain CHANGELOG entries and update `README.md`
- [ ] Capture lessons learned vs. job portal for future modules
- [ ] Define backlog for v1.1 enhancements (ticketing, RSVP tracking, recurring events)
- [ ] Archive this WBS once feature is live and post-mortem is complete

**Deliverable:** Clean documentation set + prioritized backlog for follow-up iterations.

---

> **Keep this document in sync**: Update statuses, add notes or blockers per phase, and check items off as we execute.
