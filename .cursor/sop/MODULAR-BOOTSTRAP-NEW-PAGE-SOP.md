# Modular Bootstrap: New Page SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Engineering

---

## 1. Scope

Standard pattern for new **root-level** or shallow PHP pages using `ROOT_PATH`, shared nav/footer, and analytics.

**Reference:** [docs/workflows-pipelines/MODULAR-INCLUDES.md](../../docs/workflows-pipelines/MODULAR-INCLUDES.md), [AGENTS.md](../../AGENTS.md)

---

## 2. Minimal page skeleton (root-level example)

```php
<?php
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__;
require_once $root . '/core/include-path.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$canonical_url = 'https://myomr.in/your-page.php';
$page_title = 'Page Title';
// Optional: $page_nav = 'main';

require_once ROOT_PATH . '/components/head-includes.php'; // or head-resources.php if legacy stack
?>
<?php omr_nav('main'); ?>

<main class="container py-4">
  <!-- content -->
</main>

<?php omr_footer(); ?>
<link rel="stylesheet" href="/assets/css/footer.css">
```

**Adjust:** Some modules use `head-resources.php` (Bootstrap 4) — match sibling pages in the same folder.

---

## 3. Checklist for every new page

- [ ] `core/include-path.php` first to define `ROOT_PATH`
- [ ] `components/page-bootstrap.php` OR `components/component-includes.php` for `omr_nav` / `omr_footer`
- [ ] `$canonical_url` set before meta include; base `https://myomr.in`
- [ ] `components/analytics.php` in `<head>` (via `head-includes.php` or explicit include)
- [ ] `footer.css` linked if using `omr_footer()` (per project standard)
- [ ] No duplicate Bootstrap loads unless intentional

---

## 4. Module pages (subfolders)

Subfolder modules (jobs, hostels, events, etc.) typically use:

- `module/includes/bootstrap.php` loading root bootstrap + module helpers

Copy the pattern from an existing file in the same module (e.g. `omr-local-job-listings/`).

---

## 5. Optional helpers

- `omr_ad_slot($slot_id, $size)` — see [AD-BANNER-SLOTS-REGISTRY-SOP.md](AD-BANNER-SLOTS-REGISTRY-SOP.md)
- `omr_flash_message()` — if using flash pattern

---

## 6. Deployment

If the new file lives at repo root, add to [.cpanel.yml](../../.cpanel.yml) `cp` line if not already covered by a folder copy. Root-level one-offs must be listed explicitly.

---

## 7. Related references

- [components/page-bootstrap.php](../../components/page-bootstrap.php)
- [core/include-path.php](../../core/include-path.php)
- [CPANEL-DEPLOYMENT-SOP.md](CPANEL-DEPLOYMENT-SOP.md)
