# Code Organization & Modularity — Learning Guide

**Purpose:** Topic-wise guide on how top companies organize code, reduce duplication, and improve modularity. Uses the MyOMR `index.php` revamp as the reference model.

**Applies to:** Developers learning enterprise patterns and applying them to PHP projects.

**References:**
- `index.php` — main homepage (revamped)
- `core/include-path.php` — central path definitions
- `components/component-includes.php` — nav/footer helpers
- `components/css-includes.php` — conditional CSS loading
- `docs/workflows-pipelines/ASSET-INCLUDES.md` — asset/include patterns

---

## Topic 1: Single Source of Truth (SSOT)

### What It Means

Every piece of configuration (paths, constants, feature flags) lives in **one place**. All other code depends on that place instead of defining its own copies.

### Why Companies Do This

- **One change, everywhere updated** — change `ROOT_PATH` once, all pages benefit
- **No scattered magic strings** — no `__DIR__ . '/../..'` repeated in 50 files
- **Easier refactoring** — move a folder, update one file

### Your index.php Model

```php
// At top of index.php — bootstrap once
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__;
require_once $root . '/core/include-path.php';  // Defines ROOT_PATH
require_once ROOT_PATH . '/components/component-includes.php';
```

```php
// core/include-path.php — THE single source
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__));
}
```

**Big-company analogy:** `.env` files, config servers (Consul, Vault), or a central `config.php` — same idea.

---

## Topic 2: Modular Composition (Compose, Don’t Copy)

### What It Means

Pages are built from **reusable pieces** (components), not from long monolithic files. Each piece has one job.

### Why Companies Do This

- **Reuse** — footer, nav, subscribe form used on many pages
- **Maintainability** — fix footer once, all pages updated
- **Team scaling** — different people work on different components
- **Testability** — test components in isolation

### Your index.php Model

`index.php` is a composition of sections:

```
index.php
├── Nav      → omr_nav('homepage')
├── Hero     → inline (page-specific)
├── Jobs     → components/homepage-jobs-scroll-banner.php
├── News     → components/myomr-news-bulletin.php + featured-news-links.php
├── Subscribe → inline form + components/subscribe.php
└── Footer   → omr_footer()
```

```php
<?php omr_nav('homepage'); ?>
<!-- Hero (page-specific) -->
<?php include 'components/homepage-jobs-scroll-banner.php'; ?>
<?php include 'components/myomr-news-bulletin.php'; ?>
<?php omr_footer(); ?>
```

**Big-company analogy:** React components, Blade/Laravel components, partials — same idea.

---

## Topic 3: Conditional Loading (Load Only What’s Needed)

### What It Means

Don’t load every asset on every page. Use flags or config to load **only what that page needs**.

### Why Companies Do This

- **Performance** — smaller payload, faster load
- **Clarity** — each page declares its needs
- **Flexibility** — homepage vs. article vs. listing use different stacks

### Your index.php Model

```php
// index.php sets flags BEFORE the include
$omr_css_homepage = true;
require_once ROOT_PATH . '/components/css-includes.php';
```

```php
// css-includes.php — conditional output
$omr_css_homepage = isset($omr_css_homepage) ? (bool) $omr_css_homepage : false;
// ...
<?php if ($omr_css_homepage): ?>
<link rel="stylesheet" href="/assets/css/homepage-myomr.css">
<?php endif; ?>
```

**Big-company analogy:** Code splitting (webpack), lazy loading, tree-shaking — same idea.

---

## Topic 4: Path-Agnostic Includes (Works from Any Depth)

### What It Means

Code works whether the script is at `/index.php`, `/local-news/article.php`, or `/omr-listings/locality/navalur.php`. Paths resolve correctly from any depth.

### Why Companies Do This

- **No brittle relative paths** — `include '../footer.php'` breaks when you move files
- **Consistent includes** — same pattern everywhere
- **Easier restructuring** — move pages without rewriting paths

### Your index.php Model

| Approach       | Example                          | Works from any depth? |
|----------------|----------------------------------|------------------------|
| Root-relative  | `/assets/css/main.css`          | Yes (in HTML)          |
| `ROOT_PATH`    | `ROOT_PATH . '/components/...'` | Yes (in PHP)           |
| `__DIR__ . '/..'` | Page-specific, fragile      | No                     |

```php
// component-includes.php uses ROOT_PATH
require_once $base . '/components/footer.php';
```

**Big-company analogy:** Autoloaders, PSR-4, base URL config — same idea.

---

## Topic 5: Separation of Concerns (Structure vs. Style vs. Logic)

### What It Means

- **Structure** — HTML, layout
- **Style** — CSS
- **Logic** — PHP (data, includes), JS (behavior)

Each layer has a clear role; avoid mixing them more than necessary.

### Why Companies Do This

- **Readability** — easier to find and change layout, styles, or logic
- **Caching** — CSS/JS can be cached separately
- **Reuse** — same CSS for many pages

### Your index.php Model

| Layer  | Where                                   | Example                        |
|--------|------------------------------------------|--------------------------------|
| Logic  | Top of `index.php`                       | DB queries, `$recent_jobs`     |
| Include| `component-includes`, `css-includes`     | `omr_nav()`, CSS links         |
| Markup | Main body of page                        | Hero, sections, subscribe form |
| Style  | `assets/css/` (main, homepage-myomr)     | Page-specific styles in `<style>` when needed |
| Script | Inline hero carousel                     | Self-contained IIFE            |

**Big-company analogy:** MVC, component-based frameworks, design systems — same idea.

---

## Topic 6: Reusable Helper Functions (DRY)

### What It Means

Common operations are wrapped in functions. Call the function instead of repeating the same include/ logic everywhere.

### Why Companies Do This

- **Don’t Repeat Yourself (DRY)** — one implementation, many call sites
- **Consistent API** — same parameters and behavior
- **Easier changes** — change nav source in one place

### Your index.php Model

```php
// component-includes.php
function omr_nav($variant = 'main') { /* ... */ }
function omr_footer() { /* ... */ }
```

```php
// index.php — clean usage
<?php omr_nav('homepage'); ?>
<?php omr_footer(); ?>
```

Instead of:

```php
// Without helpers — fragile, duplicated
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/components/homepage-header.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'; ?>
```

**Big-company analogy:** Service layer, utility modules, helper libraries — same idea.

---

## Topic 7: Single Responsibility (One File, One Job)

### What It Means

Each file has one main purpose. If the name doesn’t describe it, it’s probably doing too much.

### Why Companies Do This

- **Easier debugging** — issue in footer → check `footer.php`
- **Easier testing** — smaller units
- **Easier onboarding** — clear file roles

### Your index.php Ecosystem

| File                    | Job                                  |
|-------------------------|--------------------------------------|
| `include-path.php`      | Define `ROOT_PATH`                   |
| `component-includes.php`| Provide `omr_nav`, `omr_footer`      |
| `css-includes.php`      | Output CSS links (with flags)        |
| `myomr-news-bulletin.php` | News section layout               |
| `footer.php`            | Footer markup                        |

`index.php` stays thin: it composes these pieces and adds page-specific content (hero, subscribe).

---

## Topic 8: Dependency Clarity (Explicit over Implicit)

### What It Means

It should be obvious what a page depends on — DB, config, components, assets. Put these at the top.

### Why Companies Do This

- **Understandability** — new developers see dependencies quickly
- **Order of operations** — DB before queries, paths before includes
- **Testing** — easy to mock or replace dependencies

### Your index.php Model

```php
// 1. Error display (dev)
// 2. Page-specific variables
$recent_jobs = [];
$subscribed = isset($_GET['subscribed']);
// 3. Paths and core
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/component-includes.php';
// 4. DB (if needed)
require_once $core_file;
// 5. Data
$job_res = $conn->query("...");
```

Order: config → helpers → DB → data → HTML.

---

## How Big Projects Scale These Patterns

| Topic             | Small Project       | Big Project                    |
|-------------------|---------------------|--------------------------------|
| SSOT              | One `config.php`    | Config service, env, feature flags |
| Composition       | PHP includes        | Component library, design system   |
| Conditional load  | Flags in includes   | Code splitting, lazy loading       |
| Path-agnostic     | `ROOT_PATH`         | Autoloader, module resolution      |
| Separation        | CSS/JS folders      | BEM, design tokens, CSS-in-JS      |
| Helpers           | `component-includes`| Shared libraries, microservices    |
| Single responsibility | One component file | One module/package per concern     |
| Dependencies      | Top-of-file requires | Dependency injection, DI container |

The principles stay the same; the tools get more powerful.

---

## Checklist: Apply These Patterns to a New Page

1. Bootstrap paths: `require_once ROOT_PATH . '/core/include-path.php'`
2. Load component helpers: `require_once ROOT_PATH . '/components/component-includes.php'`
3. Use shared CSS: `require_once ROOT_PATH . '/components/css-includes.php'` (with flags if needed)
4. Compose with helpers: `omr_nav()`, `omr_footer()`
5. Include section components instead of duplicating markup
6. Keep page-specific logic at top; markup in body
7. Put reusable styles in `assets/css/`, not big inline blocks

---

## Further Reading

- `docs/workflows-pipelines/ASSET-INCLUDES.md` — Asset and include patterns
- `docs/INDEX-PAGE-DEPENDENCIES.md` — Index page structure
- `docs/ARCHITECTURE.md` — Overall project structure
