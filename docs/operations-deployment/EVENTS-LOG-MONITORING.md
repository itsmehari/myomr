## Events Log Monitoring (First Week Triage)

Where
- File: `/weblog/events-errors.log`
- Source: `omr-local-events/includes/error-reporting.php` (DEVELOPMENT_MODE true shows panels; set false after launch)

What to watch
- DB errors in approval/submission
- PHP warnings/notices escalated to errors on prod
- Upload failures (MIME, size)

Process
- Daily review and annotate issues
- Fix critical errors immediately; re‑test submission and approval flows
- Summarize in weekly Ops note


