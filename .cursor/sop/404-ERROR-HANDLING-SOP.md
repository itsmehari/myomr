# 404 and Missing Entity Handling SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Engineering

---

## 1. Scope

Unified behavior for **HTTP 404** responses: static missing files and **dynamic** missing entities (slug, id).

**Canonical doc:** [docs/ERROR-HANDLING-404-RULES.md](../../docs/ERROR-HANDLING-404-RULES.md)

---

## 2. Rules (non-negotiable)

| Rule | Detail |
|------|--------|
| Status | Send real **404** with `http_response_code(404)` where applicable |
| No redirect to /404.php | Do not use 302/301 to a generic error URL for missing entities |
| Shared UI | Use `core/serve-404.php` — branded page with nav/footer |
| Robots | 404 pages must be **noindex** |

---

## 3. Programmatic 404 pattern

After DB lookup fails:

```php
require_once ROOT_PATH . '/core/serve-404.php';
exit;
```

Ensure `ROOT_PATH` is defined (via `core/include-path.php`).

---

## 4. Article router edge case

[local-news/article.php](../../local-news/article.php): invalid or missing slug may use early error output for bad requests — align with project rules; for **unknown published slug**, prefer `serve-404.php` (see current implementation).

---

## 5. Validation checklist

- [ ] Missing entity returns 404 (verify in browser devtools Network)
- [ ] Page shows branded layout, not blank or raw text
- [ ] No sensitive details in error message

---

## 6. Anti-patterns

- `header('Location: /404.php');` for soft-not-found
- `die('Not found')` without 404 status
- Echoing bare `<h1>404</h1>` without shared chrome

---

## 7. Related references

- [core/serve-404.php](../../core/serve-404.php)
- [404.php](../../404.php)
- [NEWS-ARTICLE-DETAIL-QA-SOP.md](NEWS-ARTICLE-DETAIL-QA-SOP.md)
