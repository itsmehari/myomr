# Events Submission & Publishing Workflow

- **Last updated:** 12 November 2025
- **Owner:** Events Operations Team
- **Applies to:** `/omr-local-events/` module, `event_submissions` & `event_listings` tables, admin moderation dashboard
- **Prerequisites:** Organizer support email, admin login, database access, calendar export credentials

## 1. Overview

- **Purpose:** Manage community event submissions from intake through moderation to live listings and post-event marketing.
- **Trigger:** Event organizer submits form or internal team plans featured event.
- **Participants:** Organizer, Events moderator, Content designer (for hero assets), QA reviewer, Newsletter/marketing lead.

## 2. Flow Diagram (Optional)

```mermaid
flowchart TD
    A[Organizer submits event form] --> B[`process-event-omr.php` stores submission]
    B --> C{Validation passed?}
    C -- No --> C1[Show errors to organizer<br/>Encourage resubmit] --> A
    C -- Yes --> D[Status = submitted]
    D --> E[Admin reviews in manage-events-omr.php]
    E --> F{Approve event?}
    F -- Reject --> F1[Send rejection email<br/>Log reason] --> END1[Submission closed]
    F -- Approve --> G[Promote to event_listings]
    G --> H{Schedule conflicts / duplicates?}
    H -- Yes --> H1[Update schedule or merge<br/>Re-review] --> E
    H -- No --> I[Event published to /omr-local-events/]
    I --> J{QA pass (detail, ICS, filters)?}
    J -- No --> J1[Fix metadata / assets<br/>Re-test] --> I
    J -- Yes --> K[Marketing add-ons (newsletter, share playbook)]
    K --> L[Post-event status update & analytics]
    L --> END2[Archive/monitor]
```

## 3. Step-by-Step

1. **Intake**

   - Organizer uses `/omr-local-events/post-event-omr.php` (multi-step form, includes photos, dates, location, categories).
   - `process-event-omr.php` validates input, stores entry in `event_submissions` with `status='submitted'`.
   - Organizer receives confirmation at `event-submitted-success-omr.php`.

2. **Admin moderation**

   - Admin logs into `/omr-local-events/admin/index.php` (dashboard summarises pending/approved/scheduled/ongoing).
   - Reviews submission via `admin/manage-events-omr.php`, edits details, attaches hero image if needed.
   - Approves event using `admin/process-approve-event.php` (moves record to `event_listings` with scheduling metadata) or rejects with reason (`process-reject-event.php`).
   - For time-sensitive listings, set status `scheduled` / `ongoing`; use `process-pause-listing.php` or `process-unapprove-listing.php` for maintenance.

3. **Public publishing**

   - Approved events appear on `/omr-local-events/index.php`, filtered views (`/today.php`, `/weekend.php`, `/month.php`, `/category.php`, `/venue.php`, `/locality.php`).
   - Detail pages served by `event-detail-omr.php`, offering ICS download (`event-ics.php`).
   - Generate sitemap via `/omr-local-events/generate-events-sitemap.php` post batch approval.

4. **Marketing add-ons**

   - Use `admin/share-playbook.php` for Friday social templates.
   - Export calendar/ICS via `admin/calendar-export.php` or `export-events-ics.php`.
   - Email digest creation through `admin/email-digest.php`.

5. **Post-event wrap-up**
   - Update status to `completed`/`archived` as per guidelines (if implemented).
   - Review analytics (`assets/events-analytics.js`) for attendance leads.
   - Document key learnings in worklog or marketing report.

## 4. Checklists

**Submission QA**

- [ ] Dates (start/end) valid; no past-only events without archival intent.
- [ ] Venue + locality resolved; map preview works.
- [ ] Cover image meets aspect ratio.
- [ ] Organizer contact info verified.

**Approval QA**

- [ ] Event detail renders correctly (rich text, ticket buttons, CTA).
- [ ] Structured data (Event JSON-LD) validated via Rich Results.
- [ ] ICS download functional.
- [ ] Listing appears in relevant filters (category/locality).
- [ ] Sitemap regenerated if more than 5 events added.

**Post-event**

- [ ] Analytics exported / appended to marketing dataset.
- [ ] Newsletter/social snippets updated.
- [ ] Event status updated to avoid stale “ongoing” entries.

## 5. Edge Cases & Recovery

- **Duplicate submission:** Use email + datetime check in admin view; merge manually by editing description and deleting duplicate.
- **Spam / low quality:** Reject with reason via moderation panel; template stored in `admin/manage-events-omr.php`.
- **Time zone offsets:** `process-event-omr.php` normalizes to IST; confirm time conversion for multi-day events.
- **Attachment issues:** If image upload fails, request organizer to resend; admin can manually attach file via FTP and update listing.

## 6. References

- Public entry points: `post-event-omr.php`, `process-event-omr.php`, `event-detail-omr.php`, `generate-events-sitemap.php`
- Admin utilities: `admin/index.php`, `admin/manage-events-omr.php`, `process-approve-event.php`, `process-reject-event.php`, `calendar-export.php`, `share-playbook.php`
- Documentation: `EVENTS-FLOW-MATRIX.md`, `EVENTS-FEATURE-WORK-BREAKDOWN-31-10-2025.md`, `docs/product-prd/PRD-Events-Growth-MyOMR.md`
- Worklogs & checklists: `docs/worklogs/*` for events sessions, `docs/operations-deployment/EVENTS-DEPLOYMENT-CHECKLIST.md`
