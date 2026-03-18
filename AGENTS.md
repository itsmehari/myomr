# Agent Guidelines for MyOMR.in

**Last Updated:** March 2026  
**Purpose:** High-level guidance for AI agents working on this codebase.

---

## Project Overview

MyOMR.in is a local community platform for Old Mahabalipuram Road (OMR), Chennai. It provides news, events, jobs, hostels/PGs, rent-lease listings, and local business directories.

- **Stack:** PHP (procedural), MySQL, Bootstrap 5, Vanilla JS
- **Hosting:** cPanel shared hosting with Git deployment
- **Primary rules:** `.cursor/rules/cursor-ai-rules.md`

---

## Recent Work (March 2026)

### Modular bootstrap

- All pages use **ROOT_PATH**, `core/include-path.php`, `components/page-bootstrap.php`
- Layout: `omr_nav()` and `omr_footer()` from `component-includes.php`
- Subfolder modules (jobs, hostels, rent-lease, events) use `includes/bootstrap.php` that loads root bootstrap + module helpers

### Homepage composition (index.php)

- **Section-by-section**: Fetch `$recent_jobs` and `$recent_events` at top; include components that expect them in scope.
- **Conditional sections**: Jobs/Events banners only shown if data exists (`<?php if (!empty($recent_jobs)): ?>`).
- **Flag-driven CSS**: `$omr_css_homepage = true` before `head-includes.php` for homepage styles.
- Components: `homepage-jobs-scroll-banner.php`, `homepage-events-widget.php`, `myomr-news-bulletin.php`, `featured-news-links.php`.

### Job portal overhaul

- Migrated employer/application pages to modular bootstrap
- Ensured `footer.css` is loaded on all pages
- Security audit; slug URLs for job detail pages

### Employer Pack (B2B job package)

- **Product:** MyOMR Employer Pack — 10 jobs/month, featured placement, repeatable for any OMR business.
- **DB:** `employers.plan_type`, `plan_start_date`, `plan_end_date`; migration in `dev-tools/migrations/2026-03-employer-pack-plan-columns.sql`.
- **Logic:** Plan cap and helpers in `omr-local-job-listings/includes/job-functions-omr.php`; cap enforced in `process-job-omr.php`; admin auto-features package jobs in `admin/manage-jobs-omr.php`.
- **Docs:** Package rules, pricing, subscriber lifecycle: `docs/product/EMPLOYER-PACK-PRODUCT.md`.

### Hostels & PGs

- Component-based layout: `hero-hostels.php`, `filters-bar.php`, `property-cards.php`, `cta-owner.php`
- Module bootstrap in `includes/bootstrap.php`
- SEO schema, canonical URLs, WCAG-aligned accessibility

### Rent & Lease

- New module at `/omr-rent-lease/`, same structure as hostels
- See `docs/inbox/RENT-LEASE-OVERHAUL-PLAN.md`

### cPanel deployment

- `.cpanel.yml` for Git deployment
- Exclude uploads, sessions, .env from rsync delete

### SEO

- Search Console audit: fix sitemaps, remove `Crawl-delay` from robots.txt
- Slug URLs for detail pages (see skill `slug-urls-detail-pages`)

### Sitelinks + Search Console automation

- Implemented sitelink signal hardening:
  - shared hub nav source (`core/site-navigation.php`)
  - root-absolute footer links (`components/footer.php`)
  - `SiteNavigationElement` JSON-LD (`components/meta.php`)
  - hub cross-link component (`components/omr-topic-hubs.php`) across major hubs/spokes
- Canonical sitemap entrypoint normalized: legacy `info/sitemap.xml` redirected to `/sitemap.xml`.
- Search Console API submissions performed for root + child sitemaps using service-account auth.
- Added local MCP server script for Search Console operations at:
  - `dev-tools/mcp/search_console_mcp.py`

### Analytics

- **Single include:** All analytics run from `components/analytics.php` (included in `<head>`).
- **Google Analytics 4:** Measurement ID `G-JYSF141J1H` — page views, content groups, optional custom params and user properties.
- **Microsoft Clarity:** Project ID `vnpelcljv4` — session recordings, heatmaps. Loaded from the same component.
- New public pages must include `<?php include ROOT_PATH . '/components/analytics.php'; ?>` in the head; do not add duplicate or alternate analytics scripts unless explicitly required.

---

## Agent Priorities

1. **Follow the modular bootstrap pattern** — use ROOT_PATH, page-bootstrap, omr_nav, omr_footer
2. **Use slug-based detail URLs** — `/{module}/{entity}/{id}/{slug}`
3. **Load footer.css** — all pages need it for correct footer styling
4. **Canonical URLs** — always `$canonical_url`, base `https://myomr.in`, set before meta.php, output with `htmlspecialchars()`. See `docs/analytics-seo/CANONICAL-URL-METHODOLOGY.md`
5. **Consult docs** — `/docs`, module READMEs, and `.cursor/skills/` contain current patterns
6. **For SERP/sitelinks work** — keep header/footer/schema hub links aligned from one shared source; avoid nav-label drift
7. **For Search Console ops** — prefer API/MCP verification (`isPending`, `errors`, `warnings`, `lastSubmitted`) over unreliable direct HTTP checks

---

## Skills to Use When Relevant

| Skill                    | Use when                                                                 |
| ------------------------ | ------------------------------------------------------------------------ |
| `slug-urls-detail-pages` | Creating or refactoring detail pages (jobs, events, hostels, properties) |
| `dashboard-designer`     | Building admin dashboards, employer panels                               |
| `frontend-design`        | Designing UI, landing pages, layouts                                     |

---

## File Naming & Conventions

- **Kebab-case** for filenames: `job-detail-omr.php`, `add-property-omr.php`
- **Omr suffix** for module pages: `post-job-omr.php`, `employer-login-omr.php`

---

## Canonical URL Standard

- **Variable:** `$canonical_url` (or `$article_url` for articles)
- **Base:** `https://myomr.in` (no www)
- **Set before meta:** Pages using `meta.php` must set `$canonical_url` before include
- **Output:** `<link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">`
- **og:url** = canonical
- Full methodology: `docs/analytics-seo/CANONICAL-URL-METHODOLOGY.md`
- **Sitemap URLs = canonical URLs** — sitemap `<loc>` must match page canonical

## 404 Error Handling

- Use `core/serve-404.php` when entity not found; never redirect or output bare HTML.
- See `docs/ERROR-HANDLING-404-RULES.md`.

## Security

- Use prepared statements only
- Sanitize GET/POST with `htmlspecialchars()` or equivalent
- Protect `/admin/` with `$_SESSION['admin_logged_in']`
- Restrict file uploads (type, size, folder)
- Never commit service-account JSON keys; rotate keys immediately if private key content is exposed in logs/chat/editor
