## Obsolete Audit — 2025-10-31 (MyOMR)

This note tracks documentation items that became outdated due to recent platform updates. Each item includes what changed and where to look now.

1) Asset stack references
- Status: Obsolete
- Previously: Mixed fonts (Libre Baskerville, Playfair, Josefin) and Font Awesome v4 + v6 across pages.
- Now: Standardized to Poppins + Font Awesome v6; core assets live under `/assets` and included in `components/head-resources.php`.
- Action: Update any docs/code samples that suggest older fonts or FA v4.

2) Caching guidance
- Status: Updated
- Previously: No explicit output caching; generic advice only.
- Now: `.htaccess` static caching configured; output cache helper `core/cache-helpers.php` enabled for IT list.
- Action: Prefer helper for list pages; bypass via `?no_cache=1` when needed.

3) DB indexes for directories
- Status: Updated
- Previously: Manual SQL suggestions per table.
- Now: Runner `dev-tools/migrations/run_2025_10_31_add_indexes_others.php` adds idempotent indexes for name/locality/industry.
- Action: Use the runner; keep `docs/DATABASE_INDEX.md` for rationale and measurement.

4) Sitewide schema and breadcrumbs
- Status: Updated
- Previously: Page-local Breadcrumb JSON-LD in multiple files.
- Now: Centralized via `$breadcrumbs` and `components/meta.php`; Organization JSON-LD sitewide.
- Action: Use `$breadcrumbs` instead of hardcoding BreadcrumbList JSON-LD.

5) IT directory monetization
- Status: Updated
- Previously: Tiers unspecified; featured lifecycle without dates.
- Now: Tiers added to `get-listed.php` (Free/Verified/Featured); `desired_tier`/`logo_url` persisted; featured `start_at`/`end_at` enforced.
- Action: See `docs/PRD-Directory-Platform-MyOMR.md` §3.x for current behavior.

6) Analytics
- Status: Updated
- Previously: Limited events on list.
- Now: Detail, submission, and admin events instrumented; reporting guide at `docs/GA-Reporting-MyOMR.md`.
- Action: Mark `listing_submission_success` as a conversion in GA.

If you find additional outdated guidance, add entries here with date and owner.


