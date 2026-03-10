# Root Files Analysis & Modularity Plan

**Created:** March 2026  
**Purpose:** Analyze root-level files, identify obsolete/unused, map dependencies, plan modular refactor per index.php model, then code review.  
**Status:** Plan — awaiting approval before execution.

---

## Phase 1: Root Files Inventory

### 1.1 Root-Level Files (from File Explorer)

| File | Type | Purpose | Status |
|------|------|---------|--------|
| `.cpanel.yml` | Config | cPanel deployment | **KEEP** |
| `.cursorrules` | Config | AI/editor rules | **KEEP** |
| `.deployignore` | Config | Deployment exclude | **KEEP** |
| `.gitignore` | Config | Git exclude | **KEEP** |
| `.htaccess` | Config | Routing, rewrites, security | **KEEP** |
| `.htaccess.no_htaccess.phpupgrader` | Legacy | PHP upgrade tool leftover | **REVIEW** — likely obsolete |
| `.processed_property_names.txt` | Data | Unknown — check usage | **REVIEW** |
| `404.php` | Page | Custom 404 handler | **KEEP** |
| `500.php` | Page | Custom 500 handler | **KEEP** |
| `CPANEL-CHECKLIST.md` | Doc | Deployment checklist | **KEEP** |
| `debug-status.php` | Utility | Diagnose 403/500 (requires ?debug=1) | **KEEP** (dev tool; restrict in prod) |
| `deploy.bat` / `deploy.sh` | Script | Deployment scripts | **KEEP** |
| `DEPLOYMENT-CPANEL.md` | Doc | Deployment guide | **KEEP** |
| `digital-marketing-landing.php` | Page | Marketing landing | **KEEP** |
| `First-Set-OMR-Hostel-PG.csv` | Data | CSV data — check if needed | **REVIEW** |
| `fresher-jobs-omr-chennai.php` | Page | Job landing | **KEEP** |
| `generate-sitemap-index.php` | Utility | Master sitemap (served at /sitemap.xml) | **KEEP** |
| `healthcare-jobs-omr-chennai.php` | Page | Job landing | **KEEP** |
| `index-minimal-backup.php` | Backup | Minimal test page | **KEEP** (per INDEX-PAGE-DEPENDENCIES) |
| `index.php` | Page | Homepage | **KEEP** |
| `it-jobs-omr-chennai.php` | Page | Job landing | **KEEP** |
| `jobs-in-kelambakkam-omr.php` | Page | Job landing | **KEEP** |
| `jobs-in-navalur-omr.php` | Page | Job landing | **KEEP** |
| `jobs-in-omr-chennai.php` | Page | Main job portal | **KEEP** |
| `jobs-in-perungudi-omr.php` | Page | Job landing | **KEEP** |
| `jobs-in-sholinganallur-omr.php` | Page | Job landing | **KEEP** |
| `jobs-in-thoraipakkam-omr.php` | Page | Job landing | **KEEP** |
| `LEARNINGS*.md` | Docs | Project learnings | **KEEP** (consolidate duplicates?) |
| `myomr-banner-image.png` | Asset | Homepage hero image | **KEEP** |
| `robots.txt` | Config | Crawler rules | **KEEP** |
| `security-audit-thirdparty.php` | Utility | Third-party audit tool | **REVIEW** |
| `sitemap-pages.xml` | Static | Static pages sitemap | **KEEP** |
| `sitemap.xml` | Generated | Rewritten to generate-sitemap-index | **KEEP** (output) |
| `teaching-jobs-omr-chennai.php` | Page | Job landing | **KEEP** |

---

## Phase 2: Obsolete / Remove Candidates

### 2.1 Safe to Remove (After Verification)

| File | Reason |
|------|--------|
| `.htaccess.no_htaccess.phpupgrader` | PHP upgrade tool artifact; not used in deployment |
| `.processed_property_names.txt` | Check content; likely dev artifact |
| `First-Set-OMR-Hostel-PG.csv` | Data file in root — move to `data/` or `dev-tools/` if needed; else remove |
| `LEARNINGS.md.backup` | Duplicate; keep LEARNINGS.md only |
| `LEARNINGS-ORIGINAL-BACKUP.md` | Consolidate into LEARNINGS.md if unique content |

### 2.2 Do NOT Remove

- `index-minimal-backup.php` — documented as debug fallback (INDEX-PAGE-DEPENDENCIES)
- `debug-status.php` — useful for 403/500 diagnosis (add to .gitignore if sensitive)
- `security-audit-thirdparty.php` — utility; keep but restrict access

---

## Phase 3: File Dependencies

### 3.1 Core Dependencies (Shared)

```
core/include-path.php          → ROOT_PATH
core/omr-connect.php           → DB connection
core/error-handler.php         → auto_prepend (via .htaccess)
components/page-bootstrap.php  → include-path + component-includes
components/head-includes.php   → css-includes + analytics
components/component-includes.php → omr_nav(), omr_footer()
components/meta.php            → meta tags, canonical, org schema
components/analytics.php       → GA4
```

### 3.2 Page-Specific Dependencies

| Page | Dependencies |
|------|--------------|
| **index.php** | page-bootstrap, head-includes, meta, skip-link, sdg-badge, js-includes, homepage-jobs-scroll-banner, myomr-news-bulletin, featured-news-links, subscribe, footer |
| **jobs-in-omr-chennai.php** | error-reporting, job-functions-omr, page-bootstrap, omr-connect, omr_nav, omr_footer, analytics, sdg-badge |
| **fresher-jobs-omr-chennai.php** | error-reporting, job-functions-omr, omr-connect, **landing-page-template** |
| **Other job landings** (healthcare, it, teaching, kelambakkam, etc.) | Same pattern as fresher — use **landing-page-template** |
| **digital-marketing-landing.php** | head-resources, analytics, meta, main-nav, footer |
| **generate-sitemap-index.php** | None (standalone XML output) |
| **404.php** | analytics, custom 404 markup |
| **500.php** | Minimal error page |
| **debug-status.php** | None (standalone) |

### 3.3 omr-road-database-list.php (Location Issue)

- **Current:** `core/omr-road-database-list.php`
- **Footer links to:** `omr-road-database-list.php` (root)
- **Action:** Add `.htaccess` rewrite: `RewriteRule ^omr-road-database-list\.php$ core/omr-road-database-list.php [L]` — OR move file to root (breaks core/ logic). **Recommendation:** Add rewrite.

---

## Phase 4: Modularity Rework (index.php Model)

### 4.1 Bootstrap Pattern (Apply to All Pages)

```php
// 1. Set page flags
$page_nav = 'main';  // or 'homepage' | 'megamenu'
$omr_css_homepage = false;  // true for homepage only
$page_analytics = true;

// 2. Bootstrap
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__;  // __DIR__ . '/..' for 1 level deep
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

// 3. Page logic, DB, etc.
```

### 4.2 Head Pattern

```php
<head>
  <?php require_once ROOT_PATH . '/components/meta.php'; ?>
  <?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
</head>
```

### 4.3 Body Pattern

```php
<body>
  <?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
  <?php omr_nav(); ?>
  <main id="main-content">...</main>
  <?php omr_footer(); ?>
  <?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
  <?php include ROOT_PATH . '/components/sdg-badge.php'; ?>
</body>
```

### 4.4 Migration Order (Root Pages)

| Order | File | Current Stack | Target |
|-------|------|---------------|--------|
| 1 | **404.php** | analytics | page-bootstrap, head-includes, meta (if needed) |
| 2 | **500.php** | minimal | Keep minimal; add ROOT_PATH if any includes |
| 3 | **digital-marketing-landing.php** | head-resources | page-bootstrap, head-includes (or $omr_css_core for legacy) |
| 4 | **fresher-jobs-omr-chennai.php** | landing-page-template | Add page-bootstrap; template uses ROOT_PATH |
| 5 | **healthcare-jobs-omr-chennai.php** | Same | Same |
| 6 | **it-jobs-omr-chennai.php** | Same | Same |
| 7 | **teaching-jobs-omr-chennai.php** | Same | Same |
| 8 | **jobs-in-kelambakkam-omr.php** | Same | Same |
| 9 | **jobs-in-navalur-omr.php** | Same | Same |
| 10 | **jobs-in-perungudi-omr.php** | Same | Same |
| 11 | **jobs-in-sholinganallur-omr.php** | Same | Same |
| 12 | **jobs-in-thoraipakkam-omr.php** | Same | Same |
| 13 | **generate-sitemap-index.php** | None | No change (standalone) |
| 14 | **debug-status.php** | None | No change |
| 15 | **security-audit-thirdparty.php** | None | No change |

### 4.5 Landing Page Template Refactor

**landing-page-template.php** currently:
- Uses relative paths (`__DIR__ . '/../../../components/analytics.php'`)
- Does not use page-bootstrap or head-includes
- Duplicates meta, OG, Twitter blocks

**Target:** Refactor template to use ROOT_PATH and head-includes. Root job landing pages (fresher, healthcare, etc.) should:
1. Add page-bootstrap at top
2. Set $page_title, $page_description, $canonical_url, etc.
3. Include landing-page-template (which uses head-includes, meta, omr_nav, omr_footer)

---

## Phase 5: Code Analysis & Fix Plan (Per File)

### 5.1 index.php
- **Status:** Already modular.
- **Checks:** Lint, test subscribe, hero carousel, news bulletin, jobs banner.

### 5.2 jobs-in-omr-chennai.php
- **Status:** Already migrated to page-bootstrap, omr_nav, omr_footer.
- **Checks:** Verify analytics include; add meta.php if missing; add skip-link.

### 5.3 Job Landing Pages (fresher, healthcare, it, teaching, locality)
- **Issues:** Use landing-page-template; template has fragile paths.
- **Fix:** Refactor template to use ROOT_PATH; add page-bootstrap to each landing page before template include.

### 5.4 digital-marketing-landing.php
- **Issues:** Uses head-resources (legacy Bootstrap 4); own head block.
- **Fix:** Migrate to head-includes with $omr_css_core or hybrid; add page-bootstrap; replace main-nav/footer with omr_nav/omr_footer.

### 5.5 404.php / 500.php
- **404:** Uses analytics; ensure gtag available; add meta if needed.
- **500:** Keep minimal; ensure no failing includes.

### 5.6 core/omr-road-database-list.php
- **Issues:** In core/ but linked as root; uses `include 'weblog/log.php'`; old meta block; `get_browser()` (deprecated).
- **Fixes:** Add .htaccess rewrite for root URL; migrate to meta.php, head-includes; replace get_browser if possible; fix log.php path.

### 5.7 generate-sitemap-index.php
- **Status:** Standalone; outputs XML.
- **Checks:** Verify all sitemap URLs resolve; no PHP errors.

### 5.8 debug-status.php
- **Status:** Dev utility; protected by ?debug=1.
- **Checks:** Ensure not linked from production nav; document in ONBOARDING.

### 5.9 security-audit-thirdparty.php
- **Status:** Audit tool.
- **Checks:** Restrict access via .htaccess or auth; document purpose.

---

## Phase 6: Execution Order

1. **Remove obsolete files** (Phase 2) — after manual verification.
2. **Add .htaccess rewrite** for omr-road-database-list.php.
3. **Migrate landing-page-template.php** to ROOT_PATH and head-includes.
4. **Migrate root job landing pages** (fresher, healthcare, it, teaching, locality) to page-bootstrap + refactored template.
5. **Migrate digital-marketing-landing.php** to modular includes.
6. **Migrate 404.php** to page-bootstrap/head-includes.
7. **Migrate core/omr-road-database-list.php** to modular stack.
8. **Code review each file** — lint, security, deprecated functions, path consistency.

---

## Phase 7: Verification Checklist

- [ ] No broken includes (test each migrated page)
- [ ] Footer links work (OMR Database, Contact, etc.)
- [ ] Analytics fires on all pages
- [ ] Skip-link present and styled
- [ ] SDG badge shows where expected
- [ ] Mobile responsiveness
- [ ] No duplicate meta/scripts
- [ ] Sitemap index returns valid XML

---

## References

- `docs/workflows-pipelines/MODULAR-INCLUDES.md`
- `docs/INDEX-PAGE-DEPENDENCIES.md`
- `docs/ARCHITECTURE.md`
- `docs/project-audit/findings/unwanted-files.md`
