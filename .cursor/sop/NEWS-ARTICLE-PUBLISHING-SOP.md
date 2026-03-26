# News Article Publishing SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Editorial (content) + Engineering (DB/deploy)

---

## 1. Scope

End-to-end workflow for publishing **database-driven** local news articles on MyOMR.in.

**In scope:** Draft → SQL or admin → publish → SEO/sitemap follow-up.  
**Out of scope:** Static one-off PHP files in `/local-news/` (deprecated for new stories). See [docs/workflows-pipelines/news-publication-workflow.md](../../docs/workflows-pipelines/news-publication-workflow.md).

---

## 2. Ownership

| Role | Responsibility |
|------|------------------|
| Writer / editor | Content quality, slug, summary, images, Tamil pair if needed |
| Web producer | SQL insert or admin publish, deploy SQL to production |
| Engineering | Router issues, schema edge cases, sitemap errors |

---

## 3. Preconditions

- Access to MySQL (phpMyAdmin or remote CLI per [REMOTE-DB-MIGRATION-SOP.md](REMOTE-DB-MIGRATION-SOP.md) if running from dev)
- Understanding of `articles` table columns
- Hero image path under site (e.g. `/local-news/omr-news-images/...`) or full HTTPS URL
- Unique **slug** (lowercase, hyphens; English + optional `-tamil` sibling)

---

## 4. Procedure

### 4.1 Prepare content

1. Final title, summary (meta description length ~150–160 chars), HTML body.
2. Choose **category** and **tags** (used for affiliate targeting and internal discovery).
3. Set **published_date** (and timezone awareness: store as site expects).
4. **status:** `published` or `draft`.

### 4.2 Build SQL or use admin

**Path A — SQL (typical for bulk / dev-tools)**

1. Create or extend a file under `dev-tools/sql/` (e.g. `YYYY-MM-DD-topic.sql`).
2. `INSERT` into `articles` with at minimum:
   - `title`, `slug`, `summary`, `content`
   - `published_date`, `author`, `category`, `tags`
   - `image_path`, `status`
3. Escape HTML safely; use transactions in phpMyAdmin if multiple statements.
4. Commit SQL to git for audit trail.

**Path B — Admin**

1. Use `admin/articles/` add/edit flows when available (`admin/articles/add.php`, `edit.php`).
2. Verify slug uniqueness before save.

### 4.3 Tamil / bilingual pair (optional)

1. English slug: `my-article-slug`
2. Tamil slug: `my-article-slug-tamil`
3. Publish both rows; `local-news/article.php` shows language switch when pair exists.

### 4.4 Deploy database change

1. Run SQL on **production** via phpMyAdmin or approved migration path.
2. Confirm row: `SELECT slug, status FROM articles WHERE slug = '...'`.

### 4.5 Post-publish

1. Open live URL: `https://myomr.in/local-news/{slug}` (clean URL).
2. Regenerate or verify **local-news sitemap** per [SEO-CANONICAL-SITEMAP-SOP.md](SEO-CANONICAL-SITEMAP-SOP.md).
3. Optional: request indexing or sitemap refresh per [SEARCH-CONSOLE-API-OPERATIONS-SOP.md](SEARCH-CONSOLE-API-OPERATIONS-SOP.md).

---

## 5. Validation checklist

- [ ] Slug is unique and URL-safe
- [ ] `status = published` for go-live
- [ ] Summary present; OG image resolves (200, correct dimensions)
- [ ] Article loads on canonical URL without query string for primary UX
- [ ] No broken inline images in `content`
- [ ] Related articles section populated (DB has other published rows)

---

## 6. Rollback and failure handling

- **Wrong content live:** `UPDATE articles SET status = 'draft' WHERE slug = '...';` then redeploy fix and republish.
- **Wrong slug:** Prefer new slug + 301 strategy only if already indexed; otherwise fix before promotion.
- **Duplicate slug:** MySQL unique constraint may fail; resolve before re-run.

---

## 7. Evidence and logging

- Commit hash for SQL file
- Optional entry in `.cursor/RECENT-UPDATES.md` for major launches

---

## 8. Related references

- Router: [local-news/article.php](../../local-news/article.php)
- SEO meta: [core/article-seo-meta.php](../../core/article-seo-meta.php)
- Workflow doc: [docs/workflows-pipelines/news-publication-workflow.md](../../docs/workflows-pipelines/news-publication-workflow.md)
- QA: [NEWS-ARTICLE-DETAIL-QA-SOP.md](NEWS-ARTICLE-DETAIL-QA-SOP.md)
