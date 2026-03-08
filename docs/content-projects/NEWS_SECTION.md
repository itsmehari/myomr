# MyOMR News Section Documentation

## Overview

The MyOMR news section is a **database-driven**, modular, community-focused system for publishing, displaying, and managing local news, events, and bulletins relevant to the Old Mahabalipuram Road (OMR) corridor. It is designed for clarity, maintainability, and ease of use, both for editors and end-users.

**⚠️ IMPORTANT UPDATE (January 2025):** All news articles are now stored in the database (`articles` table) and displayed dynamically through the article router (`local-news/article.php`). This replaces the previous static PHP file system.

---

## 1. System Structure

### **A. News Bulletin Grid (Homepage Display)**
- **File:** `weblog/home-page-news-cards.php` (used on homepage)
- **File:** `components/myomr-news-bulletin.php` (legacy hardcoded version, still exists)
- **Purpose:** Displays a grid of recent news articles as cards, each with an image, headline, date, summary, tags, and a "Read More" link.
- **Data Source:** Queries the `articles` table from the database
- **Inclusion:** Used on the homepage via:
  ```php
  <?php include 'weblog/home-page-news-cards.php'; ?>
  ```
- **Styling:** Uses `myomr-news-bulletin.css` for a modern, responsive grid layout.
- **Database Query:** Fetches articles with `status = 'published'` ordered by `published_date DESC`

### **B. News Article Pages (Database-Driven Router)**
- **Router File:** `local-news/article.php`
- **URL Format:** `/local-news/{slug}` (clean URLs via `.htaccess` rewrite)
- **Query Format:** `/local-news/article.php?slug={slug}` (fallback)
- **Purpose:** Dynamically displays articles from the database using the slug parameter
- **Structure:** Consistent layout with title, date, images, article body, SEO meta tags, structured data (JSON-LD), and social sharing buttons
- **SEO:** Automatically generates meta tags, Open Graph, Twitter Cards, and JSON-LD structured data via `core/article-seo-meta.php`
- **Database Table:** `articles` with columns: `id`, `title`, `slug`, `summary`, `content`, `published_date`, `author`, `category`, `tags`, `image_path`, `is_featured`, `status`, `created_at`, `updated_at`

### **C. News Images**
- **Location:** `local-news/omr-news-images/`
- **Purpose:** Centralized storage for all news-related images, referenced in both the bulletin and article pages.

### **D. News Bulletin Styles**
- **Files:** 
  - `local-news/myomr-news-bulletin.css`
  - `assets/css/myomr-news-bulletin.css` (sometimes symlinked or copied for global use)
- **Purpose:** Ensures the news grid and cards are visually appealing and mobile-friendly.

---

## 2. How It Works (Database-Driven System)

### **Article Storage:**
- All articles are stored in the `articles` database table
- Each article has a unique `slug` used for URL routing
- Articles are marked as `published` or `draft` using the `status` field
- Content is stored as HTML in the `content` column

### **Display Flow:**
1. **Homepage Display:**
   - `home-page-news-cards.php` queries the `articles` table
   - Fetches latest published articles (excluding Tamil versions: `slug NOT LIKE '%-tamil'`)
   - Displays cards with: image, headline, date, summary, tags
   - "Read More" links point to: `/local-news/{slug}`

2. **Article Detail Page:**
   - User clicks "Read More" → goes to `/local-news/{slug}`
   - `.htaccess` rewrites to `article.php?slug={slug}`
   - `article.php` queries database using the slug
   - Displays full article with SEO meta tags, structured data, and related articles

### **SEO Features:**
- Article pages automatically include:
  - Title, description, keywords meta tags
  - Open Graph tags (og:title, og:description, og:image, og:url)
  - Twitter Card tags
  - Canonical URL
  - JSON-LD structured data (NewsArticle schema, Breadcrumb schema)
  - Related articles section

### **Image Handling:**
- Images stored in `/local-news/omr-news-images/`
- Image paths stored in `articles.image_path` column
- Supports both relative paths (`/local-news/omr-news-images/...`) and absolute URLs

---

## 3. Design & UX

- **Responsive Grid:** The news grid adapts to all screen sizes, showing multiple cards on desktop and stacking them on mobile.
- **Consistent Card Design:** Each news card uses the same structure and style for a unified look.
- **Highlighting Tags:** Tags are color-coded for quick scanning.
- **Social Sharing:** Article pages include sharing buttons for Facebook, Twitter, LinkedIn, and WhatsApp.

---

## 4. Extensibility & Best Practices

- **Separation of Concerns:** News listing (bulletin) and news content (articles) are separated for clarity and maintainability.
- **Reusable Styles:** The CSS is modular and can be reused or extended for other card-based sections.
- **SEO & Accessibility:** Article pages are optimized for search engines and social media previews.
- **Dynamic Paths:** Use of PHP variables for paths ensures that moving directories or restructuring is easy.

---

## 5. File Structure (Sample)

```
/local-news/
  myomr-news-bulletin.php
  omr-news-images/
    [all news images]
  [individual-article].php
  myomr-news-bulletin.css
/assets/css/
  myomr-news-bulletin.css
index.php (includes the bulletin)
```

---

## 6. How to Add or Update News (Database Method)

### **Method 1: SQL Insert (Recommended for New Articles)**

1. **Prepare Article Content:**
   - Write the article in HTML format
   - Prepare hero image (1200x630px recommended for OG tags)
   - Create a unique slug (URL-friendly: lowercase, hyphens instead of spaces)

2. **Create SQL File:**
   - Create SQL file in `dev-tools/sql/` directory
   - Use INSERT statement into `articles` table
   - Example: `ADD-{TOPIC}-ARTICLE.sql`

3. **Run SQL in phpMyAdmin:**
   ```sql
   INSERT INTO `articles` (
     `title`, `slug`, `summary`, `content`, 
     `published_date`, `author`, `category`, `tags`, 
     `image_path`, `is_featured`, `status`
   ) VALUES (
     'Article Title',
     'article-slug',
     'Short summary for homepage cards',
     '<section>Full HTML content here...</section>',
     '2025-01-10 00:00:00',
     'MyOMR Editorial Team',
     'Local News',
     'tag1, tag2, tag3',
     '/local-news/omr-news-images/image.webp',
     1,
     'published'
   );
   ```

4. **Upload Image:**
   - Place the image in `local-news/omr-news-images/`
   - Ensure image path in SQL matches actual file location

5. **Verify:**
   - Check homepage for new article card
   - Visit article at `/local-news/{slug}`
   - Verify SEO meta tags and structured data
   - Test social sharing previews

### **Method 2: Admin Panel (If Available)**
- Use the admin panel at `/admin/news-add.php` (if installed)
- Fill in the form with article details
- System automatically generates slug and inserts into database

### **Method 3: Update Existing Article**
```sql
UPDATE `articles` 
SET `content` = 'Updated HTML content',
    `updated_at` = NOW()
WHERE `slug` = 'existing-article-slug';
```

**⚠️ Note:** The old static PHP file method (creating individual `.php` files) is deprecated. All new articles must use the database-driven system.

---

## 7. Changelog & Update Log

### **2024-06**
- Refactored all news image and article links to use `$newsBase` and `$imgBase` variables for maintainability.
- Moved all news images to `local-news/omr-news-images/` for centralized management.
- Updated all news article links in the bulletin to point to the correct PHP files.
- Improved SEO and social sharing meta tags on all article pages.
- Ensured all news cards in the bulletin have consistent structure and styling.
- Made the news bulletin grid fully responsive and mobile-friendly.
- Added new articles (e.g., Wells Fargo Chennai closure, Panguni Festival, EPIC Padur Seminar, etc.).
- Fixed broken image and article links.
- Enhanced accessibility and color contrast in news card design.

### **2024-05 and Earlier**
- Initial implementation of the news bulletin and article system.
- Established the `local-news/` directory structure.
- Created the first set of news articles and images.
- Added social sharing buttons to article pages.

---

## 8. Current Features & Future Improvements

### **✅ Implemented:**
- **Database-Driven System:** All articles stored in `articles` table
- **Dynamic Router:** Clean URLs via `article.php` router
- **SEO Optimization:** Automatic meta tags, Open Graph, Twitter Cards, JSON-LD
- **Related Articles:** Automatic display of related articles
- **Bilingual Support:** Tamil versions supported (slug ends with `-tamil`)
- **Language Linking:** Automatic links between English and Tamil versions

### **Future Improvements:**
- **Admin Panel Enhancement:** Full-featured CMS for non-technical users
- **Search & Filter:** Add search and tag-based filtering to article listings
- **Pagination:** Implement pagination or "Load More" for article archives
- **Image Optimization:** Automatic image compression and WebP conversion
- **Analytics Integration:** Track article views and engagement
- **Comments System:** User comments on articles
- **Newsletter Integration:** Auto-send new articles to subscribers

---

## 9. References in Codebase

- `local-news/myomr-news-bulletin.php` (main news grid)
- `local-news/omr-news-images/` (images)
- `local-news/[article].php` (individual articles)
- `local-news/myomr-news-bulletin.css` or `assets/css/myomr-news-bulletin.css` (styles)
- `index.php` (includes the bulletin)

---

**Maintained by the MyOMR Team. For questions or suggestions, contact support@myomr.in** 