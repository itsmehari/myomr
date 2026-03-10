# Modular Include System — MyOMR

**Purpose:** One bootstrap + one head include, with per-page control via flags.  
**Replaces:** Multiple separate `require_once` calls on each page.

---

## Quick Start

```php
<?php
// 1. Set flags (optional — defaults work for most pages)
$page_nav = 'homepage';      // or 'main' | 'megamenu'
$omr_css_homepage = true;    // add homepage styles
$page_analytics = true;      // include GA4 (default: true)

// 2. Bootstrap (paths + component helpers)
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__;  // use __DIR__ . '/..' for 1 level deep
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

// 3. Page logic, DB, etc.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Page Title</title>
  <?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
</head>
<body>
  <?php omr_nav(); ?>
  <main>...</main>
  <?php omr_footer(); ?>
</body>
</html>
```

---

## Control Flags (set before bootstrap / head-includes)

### Layout (body)

| Flag | Default | Effect |
|------|---------|--------|
| `$page_nav` | `'main'` | Nav variant: `'homepage'` \| `'main'` \| `'megamenu'` |
| `$page_footer` | `true` | Set `false` to hide footer |

### Head (head-includes.php)

| Flag | Default | Effect |
|------|---------|--------|
| `$omr_css_megamenu` | `true` | Megamenu CSS + Manrope/Source Sans fonts |
| `$omr_css_homepage` | `false` | Homepage section styles |
| `$omr_css_core` | `false` | Legacy Bootstrap 4 + core/tokens/components |
| `$page_analytics` | `true` | Include Google Analytics 4 |

### Analytics pass-through (when `$page_analytics = true`)

| Flag | Type | Example |
|------|------|---------|
| `$ga_content_group` | string | Override auto content group |
| `$ga_custom_params` | array | `['article_category' => 'Local News']` |
| `$ga_user_properties` | array | `['user_type' => 'employer']` |
| `$ga_user_id` | int | Cross-device user ID |

---

## File Roles

| File | Role |
|------|------|
| `core/include-path.php` | Defines `ROOT_PATH` |
| `components/page-bootstrap.php` | Loads component-includes (omr_nav, omr_footer) |
| `components/head-includes.php` | Bundles CSS + Analytics, controlled by flags |
| `components/css-includes.php` | CSS output (used by head-includes) |
| `components/analytics.php` | GA4 (used by head-includes when `$page_analytics`) |
| `components/component-includes.php` | Defines omr_nav(), omr_footer() |

---

## Example: Minimal Page

```php
<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__;
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';
?>
<!DOCTYPE html>
<html><head><title>Minimal</title>
<?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
</head><body>
<?php omr_nav(); ?>
<main><p>Content</p></main>
<?php omr_footer(); ?>
</body></html>
```

## Example: No Footer

```php
<?php $page_footer = false; ?>
<?php omr_nav(); ?>
<main>...</main>
<?php omr_footer(); ?>
```

## Example: Job Page with Custom Analytics

```php
<?php
$page_nav = 'main';
$page_analytics = true;
$ga_custom_params = ['job_category' => $filters['category'] ?? ''];
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';
?>
<head>
  <?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
</head>
```

## Example: Page Without Analytics

```php
<?php $page_analytics = false; ?>
<?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
```

---

## Migration from Old Pattern

| Old | New |
|-----|-----|
| `require include-path.php` + `require component-includes.php` | `require include-path.php` + `require page-bootstrap.php` |
| `require css-includes.php` + `require analytics.php` | `require head-includes.php` (with flags) |
| `omr_nav('homepage')` | `$page_nav = 'homepage';` … `omr_nav();` |

---

## References

- `docs/workflows-pipelines/ASSET-INCLUDES.md` — Asset patterns
- `components/analytics.php` — GA4 component
