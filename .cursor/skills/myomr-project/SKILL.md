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
