# Security Checklist SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Engineering (every PR / deploy)

---

## 1. Scope

Minimum security checks for PHP/MySQL changes on MyOMR.in (procedural PHP, cPanel, mysqli).

**Not a penetration test** — a repeatable baseline.

---

## 2. Data access

- [ ] All SQL uses **prepared statements** (`$stmt->prepare`, `bind_param`) — no string-concat SQL with user input
- [ ] DB credentials only in `core/omr-connect.php` or server env — **never** committed in repo

---

## 3. Output and XSS

- [ ] Echo user-controlled strings with `htmlspecialchars($x, ENT_QUOTES, 'UTF-8')` unless passed through a trusted HTML sanitizer
- [ ] Canonical and Open Graph URLs escaped consistently

---

## 4. Authentication and admin

- [ ] Every `/admin/` page uses project bootstrap + `requireAdmin()` from [core/admin-auth.php](../../core/admin-auth.php) (or equivalent)
- [ ] Session fixation: follow existing session patterns; no new public “admin” shortcuts

---

## 5. File uploads

- [ ] Validate MIME/extension and size; store outside web root or non-executable path per project rules
- [ ] Never trust client-supplied filenames for server paths

---

## 6. Secrets and keys

- [ ] No Google service account JSON, DB passwords, or API keys in git
- [ ] Rotate credentials if exposed in logs, tickets, or chat

---

## 7. HTTP and headers

- [ ] External links from site that are paid/partner: appropriate `rel` (e.g. `sponsored` for affiliates)
- [ ] No mixed content (HTTPS site loading large HTTP assets) on new pages

---

## 8. Dependencies

- [ ] Avoid adding unmaintained Composer packages without review (project is mostly vanilla PHP)

---

## 9. Related references

- [AGENTS.md](../../AGENTS.md) — Security section
- [core/security-helpers.php](../../core/security-helpers.php) — CSRF helpers for forms
- [404-ERROR-HANDLING-SOP.md](404-ERROR-HANDLING-SOP.md)
