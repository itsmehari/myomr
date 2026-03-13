# 🔍 Search Console Performance Audit — MyOMR.in

> **⚠️ SUPERSEDED:** See **[SEARCH-CONSOLE-AUDIT-REVALIDATED.md](SEARCH-CONSOLE-AUDIT-REVALIDATED.md)** for a granular re-audit that validates solutions against live site and project structure. Use the revalidated document for implementation decisions.

**Date:** March 2026  
**Purpose:** Identify critical issues affecting Search Console coverage, indexing, and performance. Actionable fixes to improve rankings and organic visibility.

---

## Executive Summary

| Severity | Count | Impact |
|----------|-------|--------|
| 🔴 Critical | 3 | 404s, wrong URLs in sitemap, coverage errors |
| 🟠 Medium | 5 | Duplicates, schema warnings, crawl inefficiency |
| 🟡 Low | 4 | Optimization opportunities |

---

## 🔴 Critical Issues

### 1. **info/sitemap.xml — Outdated URLs Causing 404s & Redirects**

**Location:** `info/sitemap.xml`  
**Included in:** Main sitemap index (`generate-sitemap-index.php`)

**Problems:**
- URLs use **wrong paths** (e.g. `news-highlights-from-omr-road.php` at root vs actual `/local-news/news-highlights-from-omr-road.php`)
- Contains **legacy path** `news-old-mahabalipuram-road-omr/` — articles may have moved to `local-news/` or DB
- Lists **index.php** separately → duplicate of homepage
- Contains **commonlogin.php** → should not be indexed (login/admin)
- **search-and-post-jobs...** → now 301-redirects to `/omr-local-job-listings/`
- Many URLs likely return 404 or redirect

**Search Console impact:** Coverage report will show "URL not found (404)", "Redirect", or "Alternate page with proper canonical" — wastes crawl budget and confuses indexing.

**Fix:**
1. Regenerate or replace `info/sitemap.xml` with correct, current URLs.
2. OR remove it from the main sitemap index and let `sitemap-pages.xml` + module sitemaps handle all pages.
3. Exclude login, admin, and deprecated URLs.

---

### 2. **sitemap-pages.xml — May 404**

**Location:** Referenced as `https://myomr.in/sitemap-pages.xml` in `generate-sitemap-index.php`  
**Status:** No generator or rewrite rule. File is in `.gitignore` (assumed static/generated elsewhere).

**Problem:** If `sitemap-pages.xml` does not exist on the server, Google gets a **404** when fetching it from the sitemap index. This creates a "Couldn't fetch" error in Search Console.

**Fix:**
1. Create `generate-sitemap-pages.php` that outputs static/core pages (homepage, about, contact, discover-myomr, job landing pages, etc.).
2. Add `.htaccess` rule: `RewriteRule ^sitemap-pages\.xml$ generate-sitemap-pages.php [L]`
3. OR ensure the static file exists on deployment.

---

### 3. **robots.txt — Crawl-delay Misuse**

**Location:** `robots.txt`  
**Line:** `Crawl-delay: 1`

**Problem:** `Crawl-delay` is **not supported by Google** (only Bing, Yandex). Having it is misleading and adds no benefit for Google. Some crawlers may honor it and slow down needlessly.

**Fix:** Remove `Crawl-delay: 1` from robots.txt for cleaner configuration. Google will ignore it anyway.

---

## 🟠 Medium Issues

### 4. **Duplicate Homepage URLs**

**Issue:** Both `/` and `/index.php` may be indexed. `info/sitemap.xml` lists both. Canonical should consolidate to one.

**Fix:**
- Ensure `index.php` redirects 301 to `/` (or vice versa) or has `<link rel="canonical" href="https://myomr.in/">`
- Homepage already sets `$canonical_url = 'https://myomr.in/'` in `index.php` — verify it outputs correct canonical tag.

---

### 5. **Legacy News URL Confusion**

**Issue:** Mix of:
- Clean URLs: `/local-news/{slug}` (article.php, DB-driven)
- Standalone .php files: `/local-news/Some-Article.php`, `/local-news/news-highlights-from-omr-road.php`
- Old path in info/sitemap: `/news-old-mahabalipuram-road-omr/...`

**Risk:** Duplicate content if same article exists in both DB (clean URL) and standalone .php file.

**Fix:**
- Audit which old .php files still exist and either redirect to canonical or remove from sitemaps.
- Standardize: DB articles → `/local-news/slug`; static pages → keep as-is but ensure canonical.

---

### 6. **JobPosting Schema — Past Warnings**

**Context:** Worklog 10-Nov-2025 noted GSC warnings for 13 job listings missing address/validThrough. Fixes were applied via `seo-helper.php`.

**Action:** After any job template changes, re-validate in Search Console (Indexing → Job Postings). Ensure all job detail pages output valid JobPosting JSON-LD with required fields.

---

### 7. **Hostels/PGs property-detail — Query String Canonical**

**Issue:** `omr-hostels-pgs/property-detail.php?id=X` uses query string in canonical. Cleaner URL would help (e.g. `/omr-hostels-pgs/property/slug`).

**Priority:** Low unless GSC shows indexing issues. Current setup is valid; clean URLs are a future improvement.

---

### 8. **Event Schema**

**Status:** Phase 5 SEO analysis noted Event schema "should be implemented" for events module.

**Action:** Add Event JSON-LD to `omr-local-events/event-detail-omr.php` for rich results in search.

---

## 🟡 Low-Priority Improvements

### 9. **Bing Verification**

`core/article-seo-meta.php` has empty `msvalidate.01`. Add Bing site verification if you use Bing Webmaster Tools.

---

### 10. **Sitemap lastmod Accuracy**

`generate-sitemap-index.php` uses hardcoded dates for some sitemaps (e.g. sitemap-pages `2026-03-06`). Use `date('Y-m-d')` for auto-updating lastmod when possible.

---

### 11. **404 Page — noindex**

404 page correctly uses `<meta name="robots" content="noindex, nofollow">`. ✅ Keep as-is.

---

### 12. **Core Web Vitals & Page Experience**

Not directly in sitemap/meta scope. Use Search Console → Experience → Core Web Vitals to identify slow or poor-CLS pages. Optimize images, defer non-critical JS, reduce layout shift.

---

## ✅ What's Already Good

- Canonical URLs on most pages
- HTTPS + www redirect
- NewsArticle, BreadcrumbList, JobPosting, Organization schemas
- 404 page with noindex
- Main sitemap index dynamically lists module sitemaps
- local-news, jobs, events, hostels sitemaps are dynamic
- robots.txt blocks admin, process scripts, dev-tools

---

## 📋 Recommended Action Order

| # | Action | Effort | Impact |
|---|--------|--------|--------|
| 1 | Fix or remove `info/sitemap.xml` from sitemap index | Medium | High |
| 2 | Ensure `sitemap-pages.xml` exists or add generator | Medium | High |
| 3 | Remove `Crawl-delay` from robots.txt | 1 min | Low |
| 4 | Add Event schema to event detail pages | Low | Medium |
| 5 | Audit legacy news URLs (redirect/remove duplicates) | Medium | Medium |
| 6 | Validate Job Postings in GSC after any schema change | Low | Medium |

---

## Next Steps

1. **In Search Console:** Check Coverage report for "Not found (404)", "Redirect", "Excluded" URLs. Cross-reference with `info/sitemap.xml` URLs.
2. **Implement fixes** in order above.
3. **Request re-indexing** for priority pages after changes.
4. **Use Indexing API** (with your new service account) to request indexing for new articles/jobs automatically.

---

**Last Updated:** March 2026  
**Related:** `GOOGLE-SEARCH-CONSOLE-SUBMISSION-GUIDE.md`, `SEO-ANALYTICS-ANALYSIS.md`
