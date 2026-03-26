# News Article Detail Page QA SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Editorial QA + Engineering (for regressions)

---

## 1. Scope

Pre-flight and post-publish QA for pages rendered by [local-news/article.php](../../local-news/article.php) (`/local-news/{slug}`).

**Out of scope:** Listing page `local-news/index.php` (separate checks).

---

## 2. Ownership

- **Editorial:** Copy, images, language switch, share text sanity
- **Engineering:** Meta/schema, ads, affiliate block, performance, 404 behavior

---

## 3. Preconditions

- Article `status = published` in `articles`
- Known slug and optional Tamil pair slug

---

## 4. Procedure

### 4.1 HTTP and routing

1. Load `https://myomr.in/local-news/{slug}` — expect **200**.
2. Invalid slug — expect branded **404** via `core/serve-404.php` (not redirect). See [404-ERROR-HANDLING-SOP.md](404-ERROR-HANDLING-SOP.md).

### 4.2 Meta and social

1. View source: canonical `https://myomr.in/local-news/{slug}` (no `www`).
2. `og:title`, `og:description`, `og:image` present; image URL absolute HTTPS.
3. Twitter card tags present if template includes them (`core/article-seo-meta.php`).

### 4.3 Structured data

1. JSON-LD for NewsArticle (and extras for special article types: sports, FAQ, BLO — if applicable).
2. No JSON syntax errors in page source (breaks rich results).

### 4.4 Content body

1. Headings hierarchy sensible (`h2`/`h3`); TOC block appears if enough `h2`s (JS-driven).
2. Images in content: `max-width` behavior; no broken hotlinks.

### 4.5 Engagement blocks

1. **Share bar:** WhatsApp, Facebook, X, copy link work.
2. **WhatsApp / Facebook group CTAs:** URLs resolve (constants from `core/include-path.php`).
3. **Ad slots:** `omr_ad_slot('article-top'|'article-mid')` render without layout break (see [AD-BANNER-SLOTS-REGISTRY-SOP.md](AD-BANNER-SLOTS-REGISTRY-SOP.md)).
4. **Affiliate block:** Two cards max; disclosure visible; links open in new tab with `rel` including sponsored. See [AMAZON-AFFILIATE-SYSTEM-SOP.md](AMAZON-AFFILIATE-SYSTEM-SOP.md).

### 4.6 Analytics

1. `components/analytics.php` loaded in `<head>` (GA4 + Clarity per site standard).
2. Click affiliate CTA → GA4 `affiliate_link_click` in DebugView per [GA4-EVENT-TRACKING-SOP.md](GA4-EVENT-TRACKING-SOP.md).

### 4.7 Responsive

1. Mobile: readable line length, share buttons tappable, affiliate cards stack.
2. Desktop: optional two-column affiliate grid.

### 4.8 Related articles

1. Section shows when peer articles exist; cards link to `/local-news/{slug}`.

---

## 5. Validation checklist

- [ ] 200 OK; canonical correct
- [ ] OG image loads
- [ ] JSON-LD valid
- [ ] Share + community CTAs work
- [ ] Ads + affiliate + disclosure
- [ ] Mobile spot-check
- [ ] Invalid slug → 404 branded page

---

## 6. Rollback

Content issues: unpublish or fix DB row.  
Code regressions: revert deploy per [CPANEL-DEPLOYMENT-SOP.md](CPANEL-DEPLOYMENT-SOP.md).

---

## 7. Evidence

Screenshot or ticket ID; note GA4 event verification for affiliate changes.

---

## 8. Related references

- [core/article-seo-meta.php](../../core/article-seo-meta.php)
- [docs/ERROR-HANDLING-404-RULES.md](../../docs/ERROR-HANDLING-404-RULES.md)
