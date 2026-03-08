## GA Dashboard – Events Funnel (Views → Shares/Tickets → Submissions)

### Required events (already wired)
- events_view (detail view) – label: slug
- events_map (mapClicked)
- events_ticket (ticketClicked)
- events_share (shareClicked: channel)
- submit_start / submit_success (submissionStart/submissionSuccess)

### Dashboard widgets (GA4 Explorations)
1) Funnel: List → Detail → Share/Ticket → Submission
   - Steps: page path contains `/omr-local-events/`, event=events_view, event in {events_share, events_ticket}, event=submit_success
2) Top Landing Pages: by Sessions, CTR to details
   - Dimension: Landing page + subsequent events_view count
3) Top Hubs: today/weekend/month/category/locality
   - Filter page path regex `/omr-local-events/(today|weekend|month|category|locality)`
4) Shares by Channel
   - Breakdown: `event_param:channel`
5) Submissions trend (weekly)

### UTM policy
- Internal promo: `utm_source=events|news`, `utm_medium=internal|social`, `utm_campaign=weekend_roundup`
- External shares use the same UTM to unify measurement.

### Save & Share
- Save the Exploration as “Events Funnel – MyOMR” and pin to a Collections folder


