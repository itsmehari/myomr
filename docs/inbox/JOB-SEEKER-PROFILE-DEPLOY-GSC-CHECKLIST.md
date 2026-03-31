# Job seeker profile — deploy & Search Console checklist

Do not treat this as final triage location; move under `docs/operations-deployment/` or `docs/analytics-seo/` when reviewed.

## Deploy

1. Run migration: `php dev-tools/sql/run-job-seeker-profiles-migration.php` (or import `dev-tools/migrations/2026-03-31-job-seeker-profiles.sql` in phpMyAdmin).
2. Ensure `omr-local-job-listings/uploads/resumes/` exists on the server and is writable by PHP (not world-writable).
3. Deploy code including `admin/`, `discover-myomr/` new guides, `website-privacy-policy-of-my-omr.php`, `.cpanel.yml` updates, and `generate-sitemap-pages.php`.
4. Smoke: `php dev-tools/qa/candidate-profile-smoke-check.php`
5. Hit `https://myomr.in/omr-local-job-listings/candidate-profile-omr.php` — form loads, Privacy link works.

## Google Search Console

1. Regenerate sitemaps if your process includes `generate-sitemap-pages.php` and job portal `generate-sitemap.php`.
2. Submit sitemap index (or individual sitemaps) in GSC.
3. Inspect URL: `/omr-local-job-listings/candidate-profile-omr.php` — request indexing after first successful deploy.
4. Monitor coverage and impressions for `candidate-profile-omr` and new discover URLs (`job-seeker-profile-walkthrough-myomr.php`, etc.).

## GA4

- Custom events: `job_seeker_profile_view`, `job_seeker_profile_submit` (fired from `candidate-profile-omr.php` when `gtag` is available).
- Use `utm_*` on marketing links (already added on several CTAs) for exploration by source/medium.
