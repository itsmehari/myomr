# News Section SEO Audit & Gaps Filled

**Date:** 25 February 2026  
**Scope:** Local news articles (database-driven), homepage news cards, News Highlights page, rich snippets, internal linking, sitemap stability.

---

## 1. Audit Summary

| Area                                            | Status | Notes                                                                                          |
| ----------------------------------------------- | ------ | ---------------------------------------------------------------------------------------------- |
| **Article meta (title, description, keywords)** | Done   | `core/article-seo-meta.php` – per-article, 155-char description                                |
| **Canonical URL**                               | Done   | Clean URL: `https://myomr.in/local-news/{slug}`                                                |
| **Open Graph / Twitter Card**                   | Done   | og:type article, image, dates; twitter:card summary_large_image                                |
| **Geo / local SEO**                             | Done   | geo.region (IN-TN), geo.placename, geo.position, ICBM                                          |
| **Structured data – NewsArticle**               | Done   | headline, image, datePublished, dateModified, author, publisher, about (Place), dateline       |
| **Structured data – BreadcrumbList**            | Done   | Home → Local News (news-highlights) → Article                                                  |
| **Structured data – ItemList (related)**        | Done   | “More Articles” section outputs ItemList with ListItems                                        |
| **Internal links – homepage**                   | Done   | Featured strip via `components/featured-news-links.php`                                        |
| **Internal links – News Highlights**            | Done   | Same featured strip on highlights page                                                         |
| **Internal links – article to article**         | Done   | Related articles block + “View All” to news highlights                                         |
| **URLs / sitemap stability**                    | Stable | Article URLs are slug-based; do not change slugs after GSC submit                              |
| **BLO / election articles**                     | Done   | Extra FAQPage + HowTo + GovernmentService schema in `article.php` when slug/title indicate BLO |

---

## 2. Rich Snippets in Place

- **NewsArticle (JSON-LD)**  
  Every article page: headline, description, image, datePublished, dateModified, author, publisher, mainEntityOfPage, articleSection, keywords, articleBody (excerpt), inLanguage, about (Place with geo), dateline.

- **BreadcrumbList (JSON-LD)**  
  Home → Local News (links to news-highlights page) → Current article.

- **ItemList (JSON-LD)**  
  “More Articles” related block: ItemList with name “More OMR News”, numberOfItems, itemListElement[] (ListItem with position, url, name).

- **BLO articles**  
  Additional FAQPage, HowTo, GovernmentService when article is BLO-related.

---

## 3. Internal Linking

- **Homepage**
  - News cards (from DB, link to `/local-news/{slug}`).
  - Featured strip: links to OMR infrastructure and 28.3 lakh voters articles (configurable in `components/featured-news-links.php`).

- **News Highlights** (`/local-news/news-highlights-from-omr-road.php`)
  - Same news cards + same featured strip.

- **Article page**
  - “More Articles” (up to 6) → other articles.
  - “View All Articles” → news-highlights page.

---

## 4. Gaps Filled (This Pass)

1. **Featured internal links**  
   Added `components/featured-news-links.php` and included it on the homepage and News Highlights so key articles (e.g. OMR infrastructure, 28.3 lakh voters) get explicit internal links.

2. **Breadcrumb “Local News” target**  
   BreadcrumbList “Local News” item now points to `https://myomr.in/local-news/news-highlights-from-omr-road.php` (valid page).

3. **ItemList for related articles**  
   Related articles section on the article page now outputs ItemList JSON-LD so the “More Articles” list is eligible for list rich results and clearer crawling.

4. **Geo and “about”**  
   Already present from earlier: geo meta tags and NewsArticle `about` (Place) for Chennai/OMR.

---

## 5. Checklist for New Articles

- [ ] Insert into `articles` with unique `slug` (do not change after submission).
- [ ] Set `status = 'published'`, `published_date` (date you want for “newest first”).
- [ ] Add to featured strip in `components/featured-news-links.php` if it should be spotlighted.
- [ ] Ensure sitemap includes article URLs (if using a sitemap that pulls from `articles`).
- [ ] Submit URL (or sitemap) in Google Search Console; fix any coverage errors.
- [ ] Optionally request indexing for 1–2 most important new URLs.

---

## 6. Files Touched (News SEO)

| File                                           | Change                                                                                 |
| ---------------------------------------------- | -------------------------------------------------------------------------------------- |
| `core/article-seo-meta.php`                    | Geo meta; Breadcrumb “Local News” → news-highlights URL; NewsArticle `about`, dateline |
| `components/featured-news-links.php`           | New – featured article links (slug + title list)                                       |
| `index.php`                                    | Include featured-news-links after news cards                                           |
| `local-news/news-highlights-from-omr-road.php` | Include featured-news-links after news cards                                           |
| `local-news/article.php`                       | Related articles: fetch to array; output ItemList JSON-LD; then HTML; close conn once  |

---

## 7. Keep Stable

- **Do not change article slugs** after pages are submitted to GSC.
- **Keep sitemap logic as-is** if it already includes `/local-news/{slug}` URLs.
- **Featured list** is the only place that hardcodes specific slugs; update `components/featured-news-links.php` when you want to change which articles are in the strip.
