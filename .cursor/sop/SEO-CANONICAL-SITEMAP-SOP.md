# SEO: Canonical URLs and Sitemaps SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Engineering + SEO

---

## 1. Scope

Defines how canonical URLs, sitemap entrypoints, and redirects must behave for MyOMR.in.

**Canonical methodology:** [docs/analytics-seo/CANONICAL-URL-METHODOLOGY.md](../../docs/analytics-seo/CANONICAL-URL-METHODOLOGY.md)

---

## 2. Principles

| Topic | Rule |
|-------|------|
| Base host | `https://myomr.in` (no `www`) |
| Article URLs | `https://myomr.in/local-news/{slug}` |
| Meta canonical | Must match intended index URL; use `htmlspecialchars()` in PHP |
| Sitemap `loc` | Must match canonical for that resource |
| Sitemap XML | Valid XML only — no PHP notices/HTML mixed in |

---

## 3. Preconditions

- Access to regenerate sitemap scripts (PHP CLI on server or local with DB)
- `.htaccess` awareness for redirects

---

## 4. Procedure

### 4.1 New page or article

1. Set `$canonical_url` (or `$article_url`) **before** including meta components.
2. Output: `<link rel="canonical" href="...">` and align `og:url`.

### 4.2 News sitemap

1. News articles are included via sitemap generators under `local-news/` / `weblog/` as applicable.
2. Run the project’s article sitemap generator (e.g. `weblog/generate-articles-sitemap.php` or `local-news/generate-sitemap.php` — confirm active script in repo).
3. Validate output: open XML in browser; ensure no PHP warnings in output.

### 4.3 Root sitemap index

1. Entry point: `https://myomr.in/sitemap.xml` ([generate-sitemap-index.php](../../generate-sitemap-index.php)).
2. Legacy `info/sitemap.xml` should redirect to canonical index per site config.

### 4.4 Robots

1. Do not use `Crawl-delay` for Google (removed per project history).
2. `robots.txt` should reference sitemap index if that is the standard.

### 4.5 After bulk URL changes

1. Update internal links in nav/footer to match `core/site-navigation.php` single source.
2. Regenerate sitemaps.
3. Submit in Search Console per [SEARCH-CONSOLE-API-OPERATIONS-SOP.md](SEARCH-CONSOLE-API-OPERATIONS-SOP.md).

---

## 5. Validation checklist

- [ ] Canonical on sample pages matches browser URL path
- [ ] Sitemap XML validates (no stray bytes)
- [ ] New article URL appears in child sitemap after generation
- [ ] No duplicate `http` vs `https` or `www` variants in sitemaps

---

## 6. Rollback

Revert `.htaccess` or generator change; regenerate sitemaps; resubmit if needed.

---

## 7. Evidence

Search Console sitemap status (`lastSubmitted`, `errors`); sample URL Inspection for new URLs.

---

## 8. Related references

- [AGENTS.md](../../AGENTS.md) — Canonical URL standard
- [generate-sitemap-index.php](../../generate-sitemap-index.php)
- [docs/analytics-seo/SITEMAP-IMPLEMENTATION-SUMMARY.md](../../docs/analytics-seo/SITEMAP-IMPLEMENTATION-SUMMARY.md) (if present)
