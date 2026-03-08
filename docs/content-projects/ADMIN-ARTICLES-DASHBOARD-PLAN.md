# Admin Articles Dashboard – Plan

**Purpose:** Expandable admin dashboard for news (articles table). Start with news; extend later.  
**Last Updated:** 25 February 2026

---

## 1. Architecture (Expandable)

| Layer | Approach |
|-------|----------|
| **Module** | `admin/articles/` – self-contained news module; add `admin/events/`, `admin/jobs/` etc. later |
| **Navigation** | `config/navigation.php` – add "Articles" under Dashboard & Content; future modules plug in same way |
| **Auth** | Existing `requireAdmin()` via `_bootstrap.php` |
| **Layout** | Reuse `admin-sidebar.php`, `admin-header.php`, `admin-breadcrumbs.php`, `admin-flash.php` |

---

## 2. News Module Scope (v1)

| Feature | Description |
|---------|-------------|
| **List** | All articles from `articles` table; status filter; search by title/slug; pagination |
| **Add** | Form: title, slug, summary, content (HTML), category, author, status, published_date, tags, **image** (URL or upload) |
| **Edit** | Same fields; image update via URL or upload |
| **Delete** | Soft or hard delete (hard for now); confirm modal |
| **Image** | **Option A:** Image URL (text) · **Option B:** File upload → save to `local-news/omr-news-images/` |

---

## 3. Image Handling

- **URL option:** Text input; store as `image_path` (e.g. `/local-news/omr-news-images/...` or full URL)
- **Upload option:** File input; validate type (jpg, png, webp), size (max 2MB); save to `local-news/omr-news-images/` with unique name; store path in `image_path`
- **UI:** Radio or tabs: "Enter URL" vs "Upload file". Show only the relevant input.

---

## 4. Files to Create

| File | Purpose |
|------|---------|
| `admin/articles/index.php` | List articles; add/edit/delete links |
| `admin/articles/add.php` | Add article form (image URL + upload) |
| `admin/articles/edit.php` | Edit article form (image URL + upload) |
| `admin/config/navigation.php` | Add Articles module entry |

---

## 5. Database

- **Table:** `articles` (existing)
- **Columns used:** id, title, slug, summary, content, category, author, status, published_date, image_path, tags, is_featured, created_at, updated_at

---

## 6. Future Expansion

- Add `admin/events/`, `admin/jobs/`, etc. in same layout
- Add article categories CRUD
- Add featured toggle on list page
- Add bulk actions (publish, unpublish)
- Add image library/browser
