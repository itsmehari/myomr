# Canonical URL Methodology — MyOMR.in

**Last Updated:** March 2026  
**Purpose:** Single source of truth for how canonical URLs are set and output across the project.

---

## 1. Principles

| Rule | Description |
|------|-------------|
| **Base domain** | Always `https://myomr.in` (no www) |
| **Variable name** | Always use `$canonical_url` |
| **Output format** | `<link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">` |
| **og:url** | Always equal to canonical: `$og_url = $canonical_url` |
| **Prefer slug URLs** | Detail pages use clean slugs; avoid query-string in canonical when slug exists |

---

## 2. When to Use What

### 2.1 Pages Using `components/meta.php`

**Apply to:** Most pages that include the shared meta component.

1. Set `$canonical_url` **before** including `meta.php`:
   ```php
   $canonical_url = 'https://myomr.in/omr-hostels-pgs/';
   require_once ROOT_PATH . '/components/meta.php';
   ```

2. If you omit `$canonical_url`, `meta.php` falls back to `get_canonical_url()` from `core/url-helpers.php` (infers from `$_SERVER['REQUEST_URI']`). Only rely on this when the current URL is already canonical.

3. Set `$og_url = $canonical_url` if you need explicit OG; otherwise meta.php uses `$canonical_url` for `og:url` by default.

### 2.2 Pages with Custom Head (no meta.php)

**Apply to:** Pages with full custom head (job-detail, event-detail, job index, rent-lease index).

1. Compute canonical (use module helpers when available):
   - Jobs: `$canonical_url = getJobDetailUrl($id, $title);`
   - Events: `$canonical_url = 'https://myomr.in/omr-local-events/event/' . urlencode($slug);`
   - Listings: `$canonical_url = 'https://myomr.in/omr-rent-lease/';`

2. Output in head:
   ```php
   <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">
   <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url); ?>">
   ```

3. Always use `htmlspecialchars()` for safety.

### 2.3 Articles (local-news)

**Apply to:** DB-driven articles via `local-news/article.php` and `core/article-seo-meta.php`.

1. Set `$article_url` (not `$canonical_url` — article-seo-meta uses its own variable):
   ```php
   $article_url = 'https://myomr.in/local-news/' . $article['slug'];
   require_once ROOT_PATH . '/core/article-seo-meta.php';
   ```

2. `article-seo-meta.php` outputs canonical and og:url from `$article_url`. No change needed there; just ensure `$article_url` follows the slug format.

### 2.4 Paginated / Query-String Pages

| Scenario | Canonical |
|----------|-----------|
| List index with `?page=2` | Base URL for page 1; for page 2 use `$canonical_url . '?page=' . (int)$page` |
| Filter pages (e.g. hostels with ?search=) | Use base path (no query) unless filters change content meaning — see Search Console audit |
| Detail with required id | Prefer slug URL; if only `?id=X` exists, use that but plan slug migration |

---

## 3. Format Rules

- **Base:** `https://myomr.in` (no trailing slash for domain)
- **Paths:** No trailing slash except for root: `https://myomr.in/`
- **Slug format:** Lowercase, hyphens, no special chars: `/local-news/my-article-slug`
- **Detail URLs:** Prefer `/{module}/{entity}/{id}/{slug}` per slug-urls-detail-pages skill

---

## 4. Helpers

| Helper | Location | Use |
|--------|----------|-----|
| `get_canonical_url($path)` | `core/url-helpers.php` | Build `https://myomr.in` + path; strips index.php, trailing slash |
| `getJobDetailUrl($id, $title)` | `omr-local-job-listings/includes/job-functions-omr.php` | Job detail canonical |
| `getJobDetailPath($id, $title)` | Same | Relative path for internal links |

---

## 5. Migration Notes

Many legacy files still use:
- `$canonical` instead of `$canonical_url`
- Inline canonical without `htmlspecialchars`
- Query-string URLs where slug URLs exist

When editing a page:
1. Standardize variable to `$canonical_url`
2. Use meta.php when possible
3. Output with `htmlspecialchars($canonical_url)`
4. Prefer slug URLs for detail pages

---

## 6. Sitemap–Canonical Alignment

**Sitemaps must list the same URLs as canonical tags.** Mismatches cause duplicate indexing, 404s, or "Alternate page with proper canonical" in Search Console.

| Rule | Description |
|------|-------------|
| **Same URL** | Sitemap `<loc>` = page’s `<link rel="canonical" href="...">` |
| **Use canonical format** | Prefer slug URLs in sitemap (e.g. `/omr-local-job-listings/job/15/title-slug`) |
| **No duplicates** | Don’t list both `/` and `/index.php`; list canonical only |
| **Module helpers** | Jobs: `getJobDetailUrl()`; articles: `https://myomr.in/local-news/{slug}` |

When adding or editing sitemap generators, ensure each `<loc>` matches the canonical URL the page outputs.

---

## 7. Checklist for New Pages (and Sitemaps)

- [ ] Set `$canonical_url` (or `$article_url` for articles) before head
- [ ] Use `meta.php` if page uses shared layout; otherwise output canonical tag
- [ ] Set `$og_url = $canonical_url` (or rely on meta.php fallback)
- [ ] Always use `htmlspecialchars()` when outputting
- [ ] Prefer slug/clean URL over query-string when available
- [ ] Use `get_canonical_url($path)` or module helpers when applicable
