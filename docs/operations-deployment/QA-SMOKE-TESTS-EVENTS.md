## Events – Device/Browser Smoke Tests

Devices: Desktop (Chrome/Edge), iPhone Safari, Android Chrome

Checklist
- Listing (/omr-local-events/)
  - Filters (search/category/locality/date range) apply and clear
  - Pagination works and preserves filters
  - Quick pills (Today/Weekend/Month) load
- Detail (/omr-local-events/event/{slug})
  - Map link opens; share buttons work; ICS/Calendar links function
  - OG/Twitter show correct title/description/image
- Submission
  - Form validation on empty required fields
  - Poster upload type/size limits enforced
  - Success page shows submission ID and manage links
- Admin
  - Approve → listing appears; Pause/Resume; Unapprove; Delete (super_admin only)
- Accessibility
  - Visible focus outline; headings are semantic; color contrast acceptable


