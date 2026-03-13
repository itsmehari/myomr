# 404 Error Handling Rules — MyOMR.in

**Last Updated:** March 2026  
**Purpose:** Single source of truth for 404 handling across the project.

---

## 1. Principles

| Rule | Description |
|------|-------------|
| **Always send HTTP 404** | Use `http_response_code(404)` — never redirect to 404 (redirect = 3xx). |
| **Use shared 404 page** | Call `serve_404()` or `require ROOT_PATH . '/core/serve-404.php'; exit;` — never output bare HTML. |
| **Preserve requested URL** | 404 is served at the original URL so search engines and users see it correctly. |
| **noindex on 404** | All 404 pages must include `<meta name="robots" content="noindex, nofollow">`. |

---

## 2. Implementation

### 2.1 Root 404 (physical file not found)

- `.htaccess`: `ErrorDocument 404 /404.php`
- `404.php` calls `core/serve-404.php` — serves branded 404 with nav, footer, search, links.

### 2.2 Programmatic 404 (entity not found)

When a DB lookup returns no row (invalid ID, slug, deleted entity):

```php
// At top: ensure ROOT_PATH
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__));
}

// When entity not found:
require_once ROOT_PATH . '/core/serve-404.php';
exit;
```

### 2.3 Optional: serve_404() helper

If your page already loads `core/include-path.php` and `components/page-bootstrap.php`:

```php
if (!$entity) {
    require_once ROOT_PATH . '/core/serve-404.php';
    exit;
}
```

---

## 3. What NOT to Do

| Avoid | Why |
|-------|-----|
| `header('Location: /404.php')` | Redirect changes URL and returns 302; search engines see wrong status. |
| `echo '<h1>404 Not Found</h1>'; exit;` | Bare HTML, no layout; inconsistent UX. |
| `die('Not found')` | No HTTP 404, no layout, poor UX. |
| `header("HTTP/1.0 404 Not Found")` | Use `http_response_code(404)` (modern) instead. |

---

## 4. Modules to Use serve-404

Apply to detail pages when entity is not found:

- `local-news/article.php` — article slug not in DB
- `omr-local-job-listings/job-detail-omr.php` — job ID invalid or not approved
- `omr-hostels-pgs/property-detail.php` — property not found
- `omr-rent-lease/property-detail-omr.php` — property not found
- `omr-coworking-spaces/space-detail.php` — space not found
- `omr-listings/*.php` (bank, hospital, school, park, etc.) — entity not found
- `omr-local-events/event-detail-omr.php` — event not found
- `omr-local-events/category.php`, `locality.php`, `venue.php` — taxonomy not found
- `local-news/event-recap.php` — event not found

---

## 5. Exceptions

- **Admin pages:** Redirect to list with flash message (e.g. "Item not found") — different UX.
- **API/CLI scripts:** May return JSON `{"error":"not_found"}` or exit with message.
- **Auth-protected pages:** Redirect to login or list; do not expose 404 for unauthorized access.

---

## 6. Checklist for New Detail Pages

- [ ] When entity not found, call `serve-404.php` and `exit`
- [ ] Never redirect to 404
- [ ] Never output bare `<h1>404</h1>` — use shared page
- [ ] Ensure `http_response_code(404)` is sent (serve-404 does this)
