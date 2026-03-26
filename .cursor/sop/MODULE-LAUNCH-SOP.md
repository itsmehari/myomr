# New Module Launch SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Engineering + Product

---

## 1. Scope

Checklist for launching a **new site module** (e.g. jobs, hostels, classifieds pattern): URL space, DB, admin, SEO, sitemap, deploy.

**Examples in repo:** `omr-classified-ads/`, `omr-rent-lease/`, `omr-local-job-listings/`

---

## 2. Architecture patterns

| Layer | Pattern |
|-------|---------|
| Public pages | Module folder under web root; `includes/bootstrap.php` loading root `core/include-path.php` + helpers |
| Admin | `module/admin/...` with `admin/_bootstrap.php` + `requireAdmin()` |
| Data | Migrations in `dev-tools/migrations/` or dated SQL in `dev-tools/sql/` |
| Docs | `docs/product/` or `module/README.md` |

---

## 3. Launch procedure

### 3.1 Plan

1. Define module slug prefix (URL path), entities, and listing vs detail flows.
2. Choose slug URLs per skill `slug-urls-detail-pages` where applicable.

### 3.2 Database

1. Write migration SQL; test on local copy of schema.
2. Run on production via [REMOTE-DB-MIGRATION-SOP.md](REMOTE-DB-MIGRATION-SOP.md) or phpMyAdmin.
3. Deploy application code that depends on schema **after** migration (or same window).

### 3.3 Application code

1. Listing page + detail page + forms as needed.
2. Prepared statements only; sanitize output.
3. `$canonical_url` on every indexable page.

### 3.4 Admin / moderation

1. Protect admin routes; CSRF on mutating forms where standard helpers exist.

### 3.5 SEO

1. Add module to hub navigation if crawl-critical — [core/site-navigation.php](../../core/site-navigation.php) / footer alignment per AGENTS.
2. Add sitemap generator script or extend index; valid XML only.
3. Follow [SEO-CANONICAL-SITEMAP-SOP.md](SEO-CANONICAL-SITEMAP-SOP.md).

### 3.6 Deployment

1. Add module folder to [.cpanel.yml](../../.cpanel.yml) if new top-level directory.
2. Deploy and smoke-test module URLs.

### 3.7 Search Console

1. Submit new child sitemap if applicable — [SEARCH-CONSOLE-API-OPERATIONS-SOP.md](SEARCH-CONSOLE-API-OPERATIONS-SOP.md).

---

## 4. Validation checklist

- [ ] DB schema matches code (columns, indexes)
- [ ] 404 for unknown entities via `serve-404.php`
- [ ] Admin-only actions blocked for anonymous users
- [ ] Sitemap valid and linked from index
- [ ] Mobile navigation to module from main nav or hubs

---

## 5. Related references

- [docs/ARCHITECTURE.md](../../docs/ARCHITECTURE.md)
- Product specs under `docs/product/` and module `README.md` files where present
