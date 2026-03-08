## Events Admin Quick‑Start (MyOMR)

Login
- Go to `/admin/login.php` → Username: admin → Password: [configured]
- Open `/admin/` → click Events module

Moderation
- Manage submissions: `/omr-local-events/admin/manage-events-omr.php`
- Approve → publishes to listings with unique slug
- Pause/Resume → toggles `archived` ↔ `scheduled`
- Unapprove → moves back to submissions
- Delete → removes from listings

Content
- Create a weekend roundup: `/local-news/this-weekend-in-omr.php`
- Add recap photos under `/omr-local-events/uploads/recaps/{event_slug}/`

SEO/Indexing
- Ensure `https://myomr.in/sitemap.xml` is submitted (see `SEARCH-CONSOLE-SUBMISSION.md`)

Logs
- See `/weblog/events-errors.log` for errors during first week post‑launch


