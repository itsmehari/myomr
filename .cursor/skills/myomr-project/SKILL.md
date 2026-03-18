---
name: myomr-project
description: Single source of project knowledge for MyOMR.in — tech stack, folder structure, bootstrap pattern, canonical URLs, 404 handling, security, hosting, and file conventions. Use when working on MyOMR.in, adding new pages or modules, or needing project context.
---

# MyOMR Project

Project skill for MyOMR.in: local community platform for Old Mahabalipuram Road (OMR), Chennai. News, events, jobs, hostels, rent-lease, buy/sell, directories.

## Tech stack and environment

- **Frontend:** HTML5, CSS3, Bootstrap 5, Vanilla JS. Poppins font; max-width 1280px; mobile-first.
- **Backend:** PHP (procedural), MySQL (phpMyAdmin), mysqli prepared statements only.
- **Hosting:** cPanel shared hosting; Git deploy via `.cpanel.yml`; deploy path `public_html`.
- **Primary rules:** [AGENTS.md](AGENTS.md), [.cursor/rules/cursor-ai-rules.md](.cursor/rules/cursor-ai-rules.md).

## Bootstrap and include pattern

1. **ROOT_PATH** via `core/include-path.php` — load first.
2. **page-bootstrap.php** — loads component-includes (`omr_nav`, `omr_footer`).
3. **head-includes.php** — CSS + Analytics; flags: `$omr_css_homepage`, `$page_nav`, `$page_analytics`.
4. Subfolder modules: `includes/bootstrap.php` loads root bootstrap + module helpers.
5. Always load **footer.css** on all pages.

```php
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__;
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';
// Then: head-includes, omr_nav(), content, omr_footer()
```

## Folder structure and file placement

| Folder | Purpose |
|--------|---------|
| `/admin/` | Admin CRUD; protect with `_bootstrap.php` + `requireAdmin()` from `core/admin-auth.php` |
| `/assets/css/` | Custom styles (main.css, footer.css, homepage-myomr.css) |
| `/components/` | Nav, footer, news bulletin, jobs banner, meta, analytics |
| `/core/` | DB (omr-connect.php), path (include-path.php), serve-404, url-helpers, admin-auth |
| `/omr-local-job-listings/` | Job portal |
| `/omr-hostels-pgs/` | Hostels & PGs |
| `/omr-rent-lease/` | Rent & lease |
| `/omr-buy-sell/` | Buy & sell classifieds |
| `/omr-local-events/` | Events |
| `/local-news/` | Articles (article.php) |
| `/omr-listings/` | Directories (schools, banks, etc.) |
| `/docs/` | Documentation (ARCHITECTURE, workflows, analytics) |

## File naming and new-file rules

- **Kebab-case:** `job-detail-omr.php`, `add-property-omr.php`.
- **Omr suffix:** Use for module pages (`post-job-omr.php`, `listing-detail-omr.php`).
- New PHP pages: use modular bootstrap; set `$canonical_url`; include analytics in head; load footer.css.

## Canonical URLs

- **Variable:** `$canonical_url` (or `$article_url` for articles).
- **Base:** `https://myomr.in` (no www).
- Set before meta.php; output: `<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>`.
- Detail pages: prefer slug URLs `/{module}/{entity}/{id}/{slug}` — use skill **slug-urls-detail-pages**.
- Sitemap URLs must match canonical URLs.

## Sitelinks and SERP architecture (March 2026)

- Google sitelinks are algorithmic; optimize signals rather than expecting manual control.
- Keep one crawl-critical hub set consistent across header, footer, and structured data:
  - `Home` -> `/`
  - `Jobs` -> `/omr-local-job-listings/`
  - `Events` -> `/omr-local-events/`
  - `Explore Places` -> `/omr-listings/index.php`
  - `Buy & Sell` -> `/omr-buy-sell/`
  - `News Highlights` -> `/local-news/news-highlights-from-omr-road.php`
  - `Contact` -> `/contact-my-omr-team.php`
- Use `core/site-navigation.php` as single source for these hub links.
- Footer links should be root-absolute (`/path`), not context-derived base URLs.
- Generate `SiteNavigationElement` schema from the same nav source used by UI.
- For non-branded intent (`omr road`, `omr`), add shared hub cross-links using `components/omr-topic-hubs.php`.

## 404 error handling

- Use `require_once ROOT_PATH . '/core/serve-404.php'; exit;` when entity not found.
- Never redirect to 404; never output bare `<h1>404</h1>`.
- See [docs/ERROR-HANDLING-404-RULES.md](docs/ERROR-HANDLING-404-RULES.md).

## Analytics

- Single include: `components/analytics.php` in `<head>`.
- GA4: G-JYSF141J1H; Microsoft Clarity: vnpelcljv4.
- Do not add duplicate analytics scripts.

## Security

- Prepared statements only; sanitize GET/POST with `htmlspecialchars()`.
- Protect `/admin/` via `admin/_bootstrap.php` (calls `requireAdmin()` from `core/admin-auth.php`).
- Restrict file uploads (type, size, folder).
- Use CSRF: `generate_csrf_token()` and `verify_csrf_token()` from `core/security-helpers.php` on state-changing forms.

## Deployment (cPanel)

- Git deploy; `.cpanel.yml` drives copy; repository outside `public_html`.
- Exclude uploads, sessions, .env from delete. `omr-buy-sell` and `elections-2026` are in the deploy list.
- Refs: [CPANEL-CHECKLIST.md](CPANEL-CHECKLIST.md), [DEPLOYMENT-CPANEL.md](DEPLOYMENT-CPANEL.md).

## Search Console API runbook (March 2026)

- Use service-account JSON outside deploy/public folders (for local automation only).
- Add service-account `client_email` as **Full** user in Search Console property.
- Domain property identifier for automation: `sc-domain:myomr.in`.
- Python tooling used:
  - `google-api-python-client`
  - `google-auth`
  - `google-auth-httplib2`
- Submit/re-submit sitemaps with Search Console API:
  - root index: `https://myomr.in/sitemap.xml`
  - child sitemaps from `generate-sitemap-index.php`
- Prefer API status (`isPending`, `errors`, `warnings`, `lastSubmitted`) over ad-hoc HTTP checks when environment/network middleware returns non-representative responses.
- Security: do not commit credential JSON; rotate keys if private key material is exposed.

### Cursor MCP integration (Search Console)

- Expected MCP script path in this repo:
  - `dev-tools/mcp/search_console_mcp.py`
- Typical Cursor MCP server config:
  - command: `python`
  - args: `E:/OneDrive/_myomr/_Root/dev-tools/mcp/search_console_mcp.py`
  - env:
    - `GSC_CREDENTIALS_PATH` = absolute path to service-account JSON
    - `GSC_SITE_URL` = `sc-domain:myomr.in`
    - optional `GSC_CHILD_SITEMAPS` = comma-separated sitemap URLs
- If Cursor logs show: `can't open file .../search_console_mcp.py`, create/check this file path first.

## Documentation map

- [docs/README.md](docs/README.md) — hub
- [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) — structure
- [docs/analytics-seo/CANONICAL-URL-METHODOLOGY.md](docs/analytics-seo/CANONICAL-URL-METHODOLOGY.md) — canonical rules
- [docs/ERROR-HANDLING-404-RULES.md](docs/ERROR-HANDLING-404-RULES.md) — 404 rules
- [docs/workflows-pipelines/MODULAR-INCLUDES.md](docs/workflows-pipelines/MODULAR-INCLUDES.md) — bootstrap flags
- [docs/workflows-pipelines/ASSET-INCLUDES.md](docs/workflows-pipelines/ASSET-INCLUDES.md) — CSS/JS and nav/footer standard
- Module READMEs: `omr-hostels-pgs/README.md`, `omr-buy-sell/README.md`

## Related skills

- **slug-urls-detail-pages** — detail page URLs (id + slug)
- **dashboard-designer** — admin/employer panels
- **frontend-design** — UI, landing pages

## Homepage composition (index.php)

- Section-by-section: fetch `$recent_jobs`, `$recent_events`; include components that expect them in scope.
- Conditional: `<?php if (!empty($recent_jobs)): ?>` for data-dependent sections.
- Set `$omr_css_homepage = true` before head-includes for homepage styles.

## Standard patterns (resolved)

- **Admin:** One pattern — `admin/_bootstrap.php` then `requireAdmin()` from `core/admin-auth.php`; redirect to `/admin/login.php`.
- **Canonical:** One variable `$canonical_url`; output with `htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8')`.
- **404:** One entry point — `require_once ROOT_PATH . '/core/serve-404.php'; exit;`
- **Includes:** Use `ROOT_PATH` for all root-level includes (components, core).
- **New pages:** Use `omr_nav()`, `omr_footer()`, and `head-includes.php`.

## WhatsApp community group CTA

- **Constant:** `MYOMR_WHATSAPP_GROUP_URL` in `core/include-path.php` — single source for the group invite link.
- **Usage:** Use the constant (with fallback if needed) for any "Join our WhatsApp group" link:  
  `<?php echo htmlspecialchars(defined('MYOMR_WHATSAPP_GROUP_URL') ? MYOMR_WHATSAPP_GROUP_URL : 'https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi'); ?>`
- **Placements:** Homepage hero, article detail (share bar + community section), job detail sidebar, footer (Follow us). See [.cursor/LEARNINGS.md](../../LEARNINGS.md) and [.cursor/RECENT-UPDATES.md](../../RECENT-UPDATES.md).
- **Footer icon:** Class `footer-social__link--wa` in `assets/css/footer.css` (green #25d366).

## Facebook group CTA

- **Constant:** `MYOMR_FACEBOOK_GROUP_URL` in `core/include-path.php` — single source for Facebook community group links.
- **Usage:** Use the constant (with fallback if needed) for any "Join our Facebook group" link:  
  `<?php echo htmlspecialchars(defined('MYOMR_FACEBOOK_GROUP_URL') ? MYOMR_FACEBOOK_GROUP_URL : 'https://www.facebook.com/groups/416854920508620'); ?>`
- **Placements:** Homepage hero, article detail (compact CTA near share bar), job detail sidebar, footer (Follow us).
- **Footer icon:** Class `footer-social__link--fb-group` in `assets/css/footer.css` (Facebook blue #1877f2).

## Known legacy

- **head-resources.php** and **Bootstrap 4** — used by omr-listings and many local-news pages; migrate when touching.
- Some pages still use relative includes or **navbar.php**; prefer `omr_nav()` / `omr_footer()` when editing.
- **Slug URLs** — not yet on hostels, rent-lease, coworking (they use query-string canonicals); prefer slug when adding new modules or refactoring.
- Legacy `info/sitemap.xml` may exist in repo/history; canonical sitemap entry point is `/sitemap.xml` (index). Maintain redirects and avoid dual sitemap sources.

## Jobs module patterns (March 2026)

- **Work segment model:** Jobs now support `work_segment` classification:
  - `office`
  - `manpower`
  - `hybrid`
- **Where used:** detail page, listing filters, post/edit forms, API cards, segment landing pages, sitemap.
- **UI labels:** Office Jobs, Manpower Jobs, Hybrid Jobs.

### Migration and safety

- Remote DB migration introduced `job_postings.work_segment` with backfill + default + index.
- Keep helper fallback behavior for mixed deployments:
  - `jobPostingsHasColumn()`
  - `inferJobSegmentFromData()`
- Segment landing pages must not hard-fail if helper updates lag; use `function_exists()` guarded fallback definitions as needed.

### High-conversion detail page standards

- Prioritize above-the-fold:
  1. Role + employer context
  2. Primary apply CTA
  3. WhatsApp secondary CTA
  4. Social proof and urgency
- Include a scannable facts block (type, segment, salary, location) before long-form description.
- Avoid inline styles; place reusable UI classes in `omr-local-job-listings/assets/job-portal-2026.css`.

### API/SSR parity rule

- If card/listing markup changes in `index.php`, mirror structure in:
  - `omr-local-job-listings/api/jobs.php`
  - `omr-local-job-listings/assets/job-ajax-search.js`
- This prevents style mismatch during AJAX filtering and pagination.

### Tracking

- For job detail conversion tracking, use:
  - `job_apply_cta_click`
  - `job_whatsapp_click`
- Detail page should provide dataset context on `<body>`:
  - `data-job-id`
  - `data-job-title`
  - `data-company-name`
