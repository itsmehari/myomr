## MyOMR Events – Phase 0 Alignment & Discovery

### Goals & KPIs
- Increase community engagement via event discovery and submissions
- KPIs: submissions/week, approval rate, published events, event page views, shares, CTR to ticket links

### Personas
- Event Host/Organizer: needs simple submission and visibility in OMR
- Resident/Attendee: needs quick filtering by date, locality, category, free/paid
- MyOMR Editor: needs fast moderation, publish/reject, minimal friction

### Scope & Routes
- Public listing: `/omr-local-events/`
- Submit event: `/omr-local-events/post-event-omr.php`
- Event detail: `/omr-local-events/event-detail-omr.php?slug={slug}` (clean URLs next)
- Admin moderation: `/omr-local-events/admin/manage-events-omr.php`
- Sitemap: `/omr-local-events/generate-events-sitemap.php`

### Compliance & Policies
- Only OMR-relevant events; no prohibited content
- Organizer responsibility for accuracy; MyOMR may edit for clarity/SEO
- Optional media uploads (2MB max; JPEG/PNG/WebP)

### Success Criteria (v1)
- Secure submission with CSRF + spam protection
- Moderation to publish events; detail pages with JSON-LD and share tools
- Discoverability: sitemap, OG/Twitter, GA event tracking


