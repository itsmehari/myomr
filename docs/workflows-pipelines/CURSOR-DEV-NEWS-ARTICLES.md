# Adding news articles in development (Cursor)

- **Last updated:** 22 March 2026  
- **Audience:** Developers using Cursor (or any editor) against a dev database  
- **Related:** [`news-publication-workflow.md`](news-publication-workflow.md), [`../content-projects/NEWS_SECTION.md`](../content-projects/NEWS_SECTION.md), [`AGENTS.md`](../../AGENTS.md) (DB from local machine)

---

## Quick hint (bookmark this)

| What you need | Fact |
|----------------|------|
| **Source of truth for homepage + article URLs** | MySQL table **`articles`** — not `news_bulletin` |
| **Homepage news grid** | `components/myomr-news-bulletin.php` reads `articles` where `status = 'published'` |
| **Article page URL** | `/local-news/{slug}` → `local-news/article.php` |
| **Preferred add path in dev** | **`/admin/articles/add.php`** (session: `requireAdmin()` via `admin/_bootstrap.php`) |
| **Hero images** | Files under `local-news/omr-news-images/`; path stored in `articles.image_path` |
| **Legacy to ignore for new stories** | **`news_bulletin`** (`/admin/news-*.php`) — separate legacy table; do not use it expecting homepage cards |
| **Do not create** | New static `local-news/*.php` article files — use DB rows only |

---

## 1. Mental model

1. You insert (or update) a **row** in **`articles`** with a unique **`slug`**, HTML **`content`**, **`status`** (`published` or `draft`), and **`published_date`**.
2. The **homepage** and **`/local-news/`** listing pull from that same table.
3. **Cursor** does not “publish” by itself: you still **run PHP against a DB** (local or remote) or **execute SQL** in phpMyAdmin / MySQL client.

---

## 2. Development environment prerequisites

1. **PHP + web root** — Point the document root at this repo (e.g. `E:\OneDrive\_myomr\_Root` or a copy) so `/local-news/...` and `/admin/...` resolve.
2. **MySQL** — Import or sync schema so **`articles`** exists (see `docs/data-backend/DATABASE_STRUCTURE.md`).
3. **`core/omr-connect.php`** — Must connect to a database you are allowed to write to (local instance, or remote cPanel MySQL from your PC as described in **`AGENTS.md`**).
4. **Admin login** — For `/admin/articles/add.php`, use the same admin session as other CMS pages (`admin/_bootstrap.php`).

If the DB connection fails, article pages and the homepage block will show no rows or errors; fix credentials before debugging “missing article”.

---

## 3. Ways to add an article while developing

### A. Admin UI (recommended)

1. Log in to **`/admin/`** and open **`/admin/articles/add.php`**.
2. Fill title, slug, summary, HTML body, dates, category, tags, and image (URL or upload to `local-news/omr-news-images/`).
3. Save as **`published`** when you want it on the homepage grid.
4. Open **`http(s)://<your-dev-host>/local-news/<slug>`** to verify.

This path uses the same patterns as the rest of the admin (prepared statements, upload validation).

### B. SQL script (good for repeatable / team review)

1. Add a file under **`dev-tools/sql/`** (see existing `ADD-*` examples and [`dev-tools/sql/HOW-TO-ADD-R-KARTHIKA-ARTICLE.md`](../../dev-tools/sql/HOW-TO-ADD-R-KARTHIKA-ARTICLE.md) if present).
2. **`INSERT INTO articles (...)`** with properly escaped HTML in **`content`**.
3. Run against your dev database (phpMyAdmin, CLI, or migration runner).

Use this when content is large or you want the insert in version control without clicking through the UI.

### C. Draft in Cursor, then paste

1. Draft **title, slug, summary, and HTML body** in the editor (you can `@`-reference `local-news/article.php`, `core/article-seo-meta.php`, or an existing article row for tone/structure).
2. Paste into **`admin/articles/add.php`** or into a prepared **`INSERT`** statement.
3. Add the image file under **`local-news/omr-news-images/`** and set **`image_path`** (e.g. `/local-news/omr-news-images/your-image.webp`).

### D. `weblog/ADD-NEW-ARTICLE.php` (discouraged)

The repo may contain **`weblog/ADD-NEW-ARTICLE.php`** as a simple legacy form. Prefer **`admin/articles/add.php`** for security and consistency (prepared statements, admin auth). If you ever use the weblog script, protect it and treat it as throwaway tooling.

---

## 4. Cursor-specific tips

1. **Project context** — Load **`.cursor/skills/myomr-project/SKILL.md`** (or **`AGENTS.md`**) so paths like `ROOT_PATH`, `local-news/`, and admin bootstrap stay correct.
2. **Reference code** — Use `@local-news/article.php`, `@components/myomr-news-bulletin.php`, `@admin/articles/add.php` when asking for help generating slugs, HTML structure, or SEO fields.
3. **Slugs** — Lowercase, hyphenated, unique; they become the public path segment.
4. **Tamil / duplicate slugs** — Homepage query excludes slugs ending in `-tamil` in **`myomr-news-bulletin.php`**; follow that convention if you maintain parallel language rows.
5. **After insert** — Regenerate or submit sitemaps per your deployment checklist (`news-publication-workflow.md`); in pure local dev this may be optional.

---

## 5. Verification checklist

- [ ] Row visible in **`articles`** with **`status = 'published'`** and correct **`published_date`**
- [ ] **`/local-news/{slug}`** renders with body and image
- [ ] Homepage section includes the story (order is by date / id per `myomr-news-bulletin.php` query)
- [ ] Image loads (path is root-absolute or `https://` as stored)

---

## 6. Common mistakes

| Mistake | Result |
|---------|--------|
| Using **`/admin/news-add.php`** expecting full articles | That writes **`news_bulletin`** only — legacy, not the main homepage **`articles`** feed |
| New static **`local-news/foo.php`** only | Bypasses router; not the standard DB-driven pipeline |
| **`draft`** status | Row will not appear in the published homepage query |
| Wrong **`image_path`** | Broken hero/OG image on article and cards |

---

## 7. See also

- **Editorial / production workflow:** [`news-publication-workflow.md`](news-publication-workflow.md)  
- **News module overview:** [`../content-projects/NEWS_SECTION.md`](../content-projects/NEWS_SECTION.md)  
- **Database:** [`../data-backend/DATABASE_STRUCTURE.md`](../data-backend/DATABASE_STRUCTURE.md)
