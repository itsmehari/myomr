# Search Console Audit — Granular Revalidation (Deep Dive)

**Date:** March 2026  
**Purpose:** Re-validate the initial audit against live site behavior, project structure, and deployment reality. No changes recommended until each solution is verified safe.  
**Risk:** Implementations affect the live site directly — validate before deploying.

---

## 1. Verification Methods Used

| Method | Result |
|--------|--------|
| Live URL fetch (sitemap.xml, sitemap-pages.xml, info/sitemap.xml) | See §2 |
| Project file/structure grep | Actual paths, rewrites, generators |
| .htaccess rewrite rules | URL routing logic |
| Cross-reference docs | sitemap-generator.php referenced but **does not exist** |

---

## 2. Live Site Verification (as of audit date)

| URL | Result | Implication |
|-----|--------|-------------|
| https://myomr.in/ | 200 OK | Homepage works |
| https://myomr.in/sitemap.xml | **500** | `generate-sitemap-index.php` or server config issue |
| https://myomr.in/sitemap-pages.xml | **404** | No file, no rewrite — confirmed missing |
| https://myomr.in/info/sitemap.xml | **404** | File exists in repo at `info/sitemap.xml`; may not be deployed or path differs on live |
| https://myomr.in/local-news/sitemap.xml | **500** | `local-news/generate-sitemap.php` — DB/PHP/server issue |
| https://myomr.in/omr-local-job-listings/sitemap.xml | **500** | Same pattern |
| https://myomr.in/robots.txt | **404** | File in repo; deployment or path issue |

**Note:** 500/404 from fetch may be transient, server limits, or deployment differences. Treat as *likely* issues, not absolute proof.

---

## 3. Project Structure — Sitemap Architecture

### 3.1 Current Flow

```
Request: https://myomr.in/sitemap.xml
  → .htaccess: RewriteRule ^sitemap\.xml$ generate-sitemap-index.php [L]
  → generate-sitemap-index.php outputs <sitemapindex> with child sitemap URLs
```

### 3.2 Child Sitemaps in Index

| Child URL | .htaccess rewrite? | Generator / file | In repo? | Notes |
|-----------|--------------------|------------------|----------|-------|
| /sitemap-pages.xml | **No** | None | In .gitignore (static) | No rule; needs physical file or new generator + rule |
| /info/sitemap.xml | No (direct file) | Static file | Yes `info/sitemap.xml` | Physical file; may 404 if info/ not deployed |
| /local-news/sitemap.xml | Yes | `local-news/generate-sitemap.php` | Yes | DB + echo XML |
| /omr-listings/sitemap.xml | Yes | `omr-listings/generate-listings-sitemap.php` | Yes | |
| /it-parks/sitemap.xml | Yes | `omr-listings/generate-it-parks-sitemap.php` | Yes | |
| /omr-local-events/sitemap.xml | Yes | `omr-local-events/generate-events-sitemap.php` | Yes | |
| /omr-local-job-listings/sitemap.xml | Yes | `omr-local-job-listings/generate-sitemap.php` | Yes | **Writes to file, echoes text** — response may not be valid XML |
| /omr-hostels-pgs/sitemap.xml | Yes | `omr-hostels-pgs/generate-sitemap.php` | Yes | Echoes XML; also `file_put_contents` |
| /omr-coworking-spaces/sitemap.xml | Yes | `omr-coworking-spaces/generate-sitemap.php` | Yes | |
| /pentahive/sitemap.xml | Yes | `pentahive/generate-sitemap.php` | Yes | |
| /election-blo-details/sitemap.xml | Yes (rule exists) | `election-blo-details/generate-blo-sitemap.php` | Yes | **Not in generate-sitemap-index.php** — missing from index |

### 3.3 sitemap-generator.php

- **Docs reference:** `sitemap-generator.php` in CRON, SITEMAP-IMPLEMENTATION-SUMMARY, worklogs.
- **Status:** File **does not exist** in project.
- **`dev-tools/tasks/build-sitemap.php`:** Tries `include sitemap-generator.php` → would fail.
- **`.htaccess`:** Only `generate-sitemap-index.php` serves `/sitemap.xml`.
- **Conclusion:** Docs are stale; production uses the index-based model.

---

## 4. info/sitemap.xml — URL-by-URL Validation

Each URL checked against .htaccess rules and file presence.

| info/sitemap URL | .htaccess / physical file | Verdict |
|------------------|---------------------------|---------|
| https://myomr.in/ | Served by index.php | OK |
| https://myomr.in/index.php | Physical file or DirectoryIndex | Duplicate of / |
| https://myomr.in/About-My-OMR-Local-News-Network-of-Old-Mahabalipuram-Road.php | No file found in repo | Likely 404 |
| https://myomr.in/news-highlights-from-omr-road.php | File at `local-news/news-highlights-from-omr-road.php`; no root rewrite | **Wrong path** → 404. Correct: `/local-news/news-highlights-from-omr-road.php` |
| https://myomr.in/image-video-gallery-old-mahabalipuram-road-news.php | Need to verify | Unclear |
| https://myomr.in/search-and-post-jobs-job-vacancy-employment-platform-for-omr-chennai.php | File at `listings/search-and-post-jobs...`; redirect only for `listings/search-and-post-jobs...` | Root URL: **404**. Correct: `/listings/...` (then 301 to jobs) |
| https://myomr.in/Omr-Road-Events-and-Happenings.html | Need to verify | Unclear |
| https://myomr.in/citizens-charter-old-mahabali-puram-road.php | Rewrite to `info/citizens-charter...` | OK |
| https://myomr.in/search-OMR-old-mahabali-puram-road.php | Need to verify | Unclear |
| https://myomr.in/contact-my-omr-team.php | Rewrite to `weblog/contact-my-omr-team.php` | OK |
| https://myomr.in/omr-road-database-list.php | Physical file at root | Verify |
| https://myomr.in/omr-news-list-of-areas-covered.php | Likely in local-news/ or root | Verify |
| https://myomr.in/commonlogin.php | Root file; **login** — should not be in sitemap | Do not index |
| https://myomr.in/sell-rent-property-house-plot-omr-chennai.php | Verify at root or listings | Unclear |
| https://myomr.in/news-old-mahabalipuram-road-omr/*.php | **No folder** `news-old-mahabalipuram-road-omr` in repo | **All 404** |

**Conclusion:** info/sitemap contains many wrong or obsolete URLs. If Google can fetch it, coverage will show 404s and redirects.

---

## 5. Revalidation of Originally Proposed Solutions

### 5.1 info/sitemap.xml — "Fix or Remove"

| Option | Pros | Cons | Risk | Recommendation |
|--------|------|------|------|----------------|
| **A. Remove from index** | Stops Google fetching broken child sitemap; fewer reported errors | Lose any correct URLs in it | Low, if most URLs are bad | **Prefer** — only after confirming GSC shows errors from this sitemap |
| **B. Replace with corrected file** | Keeps valid pages discoverable | Requires full audit; risk of new mistakes | Medium | Only if A causes loss of important pages |
| **C. Do nothing** | No change | Continues to submit bad URLs if fetched | Medium | Avoid |

**Validation before A:**
1. In Search Console → Sitemaps → check if `info/sitemap.xml` is listed and has errors.
2. In Coverage → Excluded/Error → see if any URLs trace back to info/sitemap.
3. If info/sitemap 404s on live, it is not fetched; removing from index only cleans the index, no behavioral change.

### 5.2 sitemap-pages.xml — "Create Generator or Remove"

| Option | Pros | Cons | Risk | Recommendation |
|--------|------|------|------|----------------|
| **A. Add generate-sitemap-pages.php + htaccess rule** | Dynamic, correct URLs, matches project pattern | New code; must maintain URL list | Medium | **Preferred if** static pages are not covered elsewhere |
| **B. Remove from index** | Quick; eliminates 404 for this child | Lose coverage of core static pages (homepage, about, jobs, discover-myomr) | **High** — job landing pages etc. come from `omr-local-job-listings/generate-sitemap.php`; homepage/index and others may not be in any sitemap | **Do not remove** without confirming coverage |
| **C. Create static file and commit** | Simple | Manual upkeep; .gitignore excludes it | Low, but goes against current gitignore | Possible stopgap only |

**Coverage check:**
- Job landing pages: in `omr-local-job-listings/generate-sitemap.php`.
- Homepage: not in job sitemap; could be in sitemap-pages or info.
- Discover-myomr, about, contact, etc.: likely only in info/sitemap or sitemap-pages.
- If both sitemap-pages and info are broken/404, many static pages may have **no sitemap coverage**.

**Recommendation:** Create `generate-sitemap-pages.php` and an htaccess rule. Derive URL list from `docs/analytics-seo/SITEMAP-COMPLETE-LIST.md` and .htaccess rewrites. Test on staging before production.

### 5.3 Crawl-delay in robots.txt — "Remove"

| Fact | Notes |
|------|-------|
| Google ignores `Crawl-delay` | No impact on Googlebot |
| Bing/Yandex honor it | May reduce their crawl rate |
| Value is 1 second | Very light throttle |

| Option | Risk | Recommendation |
|--------|------|----------------|
| Remove | Very low | Safe; simplifies config |
| Keep | None for Google | Optional; no harm |

### 5.4 Add election-blo-details to sitemap index

- Rule exists; generator exists.
- Currently **omitted** from `generate-sitemap-index.php`.
- **Action:** Add to index to improve coverage of BLO pages.
- **Risk:** Low.

---

## 6. Risks and Edge Cases

### 6.1 Removing info/sitemap from index

- If live server **does** serve `info/sitemap.xml` (e.g. different deployment), removal stops discovery of those URLs.
- If it 404s, removal only changes the index; Google already cannot fetch it.
- **Mitigation:** Check live `https://myomr.in/info/sitemap.xml` before changing the index.

### 6.2 Creating generate-sitemap-pages.php

- Must not duplicate URLs from other sitemaps (e.g. job landing pages).
- Job sitemap already has jobs-in-*.php, omr-local-job-listings/, etc.
- sitemap-pages should cover: homepage, about, contact, discover-myomr, info, legal, rent/lease, digital-marketing-landing, etc. — **excluding** job landing pages.
- **Validation:** Cross-check against `omr-local-job-listings/generate-sitemap.php` base pages.

### 6.3 omr-local-job-listings generate-sitemap.php behavior

- Script writes XML to file and echoes a success message.
- Rewrite sends `sitemap.xml` requests to this script → response is text, not XML.
- **Impact:** Sitemap URL may return invalid content for search engines.
- **Fix:** Change script to send XML (e.g. header + echo $xml), not only write to file and echo success. Verify other module sitemaps do not have the same pattern.

---

## 7. Pre-Implementation Checklist

Before any change:

1. [ ] Confirm live status of sitemap.xml, sitemap-pages.xml, info/sitemap.xml, robots.txt (from your network / GSC).
2. [ ] In GSC → Sitemaps: note which child sitemaps have errors.
3. [ ] In GSC → Coverage: note "Not found (404)", "Redirect", and "Couldn't fetch" URLs.
4. [ ] Run changes on staging or a test URL if possible.
5. [ ] After deployment, re-check URLs and GSC after 24–48 hours.

---

## 8. Revised Recommendation Summary

| Action | Confidence | Prerequisite |
|--------|------------|--------------|
| Remove `Crawl-delay` from robots.txt | High | None |
| Add election-blo-details to sitemap index | High | Verify generator works |
| Remove info/sitemap from index | Medium | Confirm it 404s or causes errors in GSC |
| Create generate-sitemap-pages.php + htaccess rule | Medium | URL list from SITEMAP-COMPLETE-LIST + rewrites; no duplication with job sitemap |
| Do not remove sitemap-pages from index without replacement | High | Avoid loss of static page coverage |
| Fix omr-local-job-listings sitemap to return XML | Medium | Code review of generate-sitemap.php |

---

## 9. Do Not Do (without further validation)

1. **Do not remove sitemap-pages from the index** without either a working generator or proof that all its URLs are covered elsewhere.
2. **Do not bulk-edit info/sitemap.xml** without checking each URL against live paths and rewrites.
3. **Do not assume docs are accurate** — e.g. sitemap-generator.php is missing; trust the codebase and .htaccess.
4. **Do not change production** before testing; use staging or manual checks where possible.

---

**Last updated:** March 2026  
**Supersedes:** SEARCH-CONSOLE-PERFORMANCE-AUDIT.md (use this document for implementation decisions)
