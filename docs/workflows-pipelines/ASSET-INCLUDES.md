# Asset & Component Includes — MyOMR

**Last updated:** March 2026  
**Purpose:** Single source of truth for CSS, JS, and component include patterns.  
**Applies to:** All PHP pages at any directory depth.

---

## 1. Overview

MyOMR uses shared include files so CSS, JS, and common components work from any page, regardless of subdirectory depth (root, `local-news/`, `omr-listings/`, `omr-local-job-listings/includes/`, etc.).

### Path Strategy

| Asset Type | Output Format | Example | Why |
|------------|---------------|---------|-----|
| CSS, JS (in HTML) | Root-relative (`/...`) | `/assets/css/main.css` | Works from any depth |
| PHP includes | `ROOT_PATH` | `ROOT_PATH . '/components/footer.php'` | Resolves to project root filesystem path |

### Core Files

| File | Role |
|------|------|
| `core/include-path.php` | Defines `ROOT_PATH` constant |
| `components/page-bootstrap.php` | Loads component-includes (use after include-path) |
| `components/head-includes.php` | **Modular:** CSS + Analytics with per-page flags |
| `components/css-includes.php` | Common CSS (Font Awesome, megamenu, main.css) |
| `components/js-includes.php` | Common JS (extend as project standardizes) |
| `components/component-includes.php` | Helper functions for footer, nav, etc. |

**See `docs/workflows-pipelines/MODULAR-INCLUDES.md` for the modular approach.**

---

## 2. Bootstrap: Load ROOT_PATH (Required First)

**Before any asset or component includes**, load the path helper:

```php
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__;
require_once $root . '/core/include-path.php';
```

**Directory depth:** For root-level pages (`index.php`), `__DIR__` is the project root. For subdirectories, `$_SERVER['DOCUMENT_ROOT']` is preferred; it always points to the web root regardless of script location.

| Page location | `$root` expression | Notes |
|---------------|--------------------|-------|
| Root (`index.php`) | `$_SERVER['DOCUMENT_ROOT'] ?? __DIR__` | Both resolve to project root |
| 1 level (`local-news/article.php`) | `$_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..'` | `__DIR__` = `local-news/`, so `..` = root |
| 2 levels (`omr-listings/locality/navalur.php`) | `$_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/../..'` | Use `$_SERVER['DOCUMENT_ROOT']` when possible |

**Recommendation:** Use `$_SERVER['DOCUMENT_ROOT'] ?? __DIR__` everywhere. On standard Apache/nginx setups, `DOCUMENT_ROOT` is set; fallback works for root-level scripts.

---

## 3. CSS Includes

### Basic Usage

```php
<?php require_once ROOT_PATH . '/components/css-includes.php'; ?>
```

Outputs: Font Awesome, megamenu CSS, Google fonts, `main.css` (which imports footer.css).

### Conditional Loading (Optional Flags)

Set variables **before** including to add section-specific CSS:

```php
<?php
$omr_css_megamenu = true;   // default: true
$omr_css_homepage = true;   // add homepage-myomr.css
$omr_css_core    = false;   // add core/tokens/components (legacy stack)
require_once ROOT_PATH . '/components/css-includes.php';
?>
```

| Flag | Default | Effect |
|------|---------|--------|
| `$omr_css_megamenu` | `true` | Megamenu styles |
| `$omr_css_homepage` | `false` | Homepage section styles |
| `$omr_css_core` | `false` | Legacy core.css, tokens.css, components.css |

### Page-Specific CSS

Add after the include:

```php
<?php require_once ROOT_PATH . '/components/css-includes.php'; ?>
<link rel="stylesheet" href="/assets/css/homepage-myomr.css">
```

---

## 4. JS Includes

### Basic Usage

```php
<?php require_once ROOT_PATH . '/components/js-includes.php'; ?>
```

Place **before `</body>`** for scripts that run after DOM.

### Current Contents

`js-includes.php` is minimal. Add Bootstrap, jQuery, analytics, or custom scripts as the project standardizes.

---

## 5. Component Includes

### Helper Functions

Include once, then call helpers:

```php
<?php
require_once ROOT_PATH . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';
?>
<!DOCTYPE html>
...
<body>
  <?php omr_nav('homepage'); ?>
  <main>...</main>
  <?php omr_footer(); ?>
</body>
</html>
```

| Function | Purpose | Parameters |
|----------|---------|------------|
| `omr_footer()` | Output footer | — |
| `omr_nav($variant)` | Output nav | `'homepage'` \| `'main'` \| `'megamenu'` |

### Direct Include (Alternative)

Without helpers:

```php
<?php require_once ROOT_PATH . '/components/footer.php'; ?>
```

Always use `ROOT_PATH` so includes work from any depth.

---

## 6. Stack Comparison

| Stack | Used by | CSS | JS |
|-------|---------|-----|-----|
| **Homepage** | `index.php` | css-includes + homepage-myomr.css | Inline hero script |
| **Legacy / Listings** | omr-listings, many local-news | `head-resources.php` (Bootstrap 4, MDB, core) | head-resources (jQuery, Bootstrap JS) |

The new include system targets the **homepage stack** and new pages. Legacy pages using `head-resources.php` can be migrated gradually.

### 6.1 Standard for new and touched pages

**New pages and pages under active development** should use `omr_nav()`, `omr_footer()`, and `head-includes.php` (or `page-bootstrap.php` + `head-includes.php`). Legacy pages may still use `main-nav.php` or `navbar.php` via relative include until migrated. When touching a file (e.g. for canonical or security), migrate that page to the modular stack if it is public-facing.

---

## 7. Migration Checklist

When adding the include system to a new page:

1. [ ] Add bootstrap block near top: `$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__; require_once $root . '/core/include-path.php';` (use `__DIR__ . '/..'` for 1 level deep)
2. [ ] Add `require_once ROOT_PATH . '/components/component-includes.php';`
3. [ ] Replace `require main-nav.php` with `<?php omr_nav('main'); ?>` (or `omr_nav('homepage')` for homepage)
4. [ ] Replace `require footer.php` with `<?php omr_footer(); ?>`
5. [ ] Replace relative includes (`../components/...`) with `ROOT_PATH . '/components/...'`
6. [ ] Replace duplicated `<link>` tags with `<?php require_once ROOT_PATH . '/components/css-includes.php'; ?>` (optional, if using new CSS stack)
7. [ ] Test at different viewport sizes

### Migrated Pages (index.php model)

| Page | Bootstrap | omr_nav/omr_footer | ROOT_PATH includes |
|------|-----------|--------------------|---------------------|
| `index.php` | ✅ | ✅ | ✅ |
| `jobs-in-omr-chennai.php` | ✅ | ✅ | — |
| `local-news/article.php` | ✅ | ✅ | ✅ head-resources, analytics, article-seo-meta |
| `omr-local-job-listings/index.php` | ✅ | ✅ | ✅ analytics, sdg-badge |

---

## 8. Error reporting

- **Development:** Use `error_reporting(E_ALL)` or a module `error-reporting.php` (e.g. hostels, rent-lease) that sets E_ALL in dev.
- **Production:** Use a shared helper that suppresses display and logs (e.g. `error_reporting(E_ALL & ~E_NOTICE)` and log to file). Avoid `error_reporting(0)` so issues are at least logged.

## 9. References

- `docs/INDEX-PAGE-DEPENDENCIES.md` — Index page structure
- `components/head-resources.php` — Legacy head block (Bootstrap 4 stack)
- `docs/ARCHITECTURE.md` — Overall file organization
- `core/security-helpers.php` — CSRF: use `generate_csrf_token()` and `verify_csrf_token()` on all state-changing forms (admin CRUD and public post forms).
