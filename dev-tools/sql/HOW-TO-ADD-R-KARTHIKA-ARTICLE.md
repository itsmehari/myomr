# 📰 How to Add R Karthika Kabaddi Article to MyOMR

## Quick Deployment Guide

This guide will walk you through adding the R Karthika kabaddi victory article to your MyOMR website using the database-driven article system.

---

## 📋 Prerequisites

- Access to phpMyAdmin or database management tool
- FTP/cPanel File Manager access to upload images
- Database name: `metap8ok_myomr`
- Table name: `articles`

---

## 🚀 Step-by-Step Instructions

### Step 1: Prepare the Image

1. **Locate the Image:**
   - Image file: `r-karthika-kabaddi-gold-medal-2025.webp`
   - Recommended size: 1200x630px (for Open Graph tags)
   - Format: WebP or JPG

2. **Upload Image:**
   - Using FTP or cPanel File Manager
   - Navigate to: `/local-news/omr-news-images/`
   - Upload: `r-karthika-kabaddi-gold-medal-2025.webp`
   - Verify image is accessible at: `/local-news/omr-news-images/r-karthika-kabaddi-gold-medal-2025.webp`

**Alternative:** If you don't have the image yet, you can:
- Use a placeholder image temporarily
- Update the image path later in the database

---

### Step 2: Run the SQL File

1. **Open phpMyAdmin:**
   - Login to your cPanel
   - Click on "phpMyAdmin"
   - Select database: `metap8ok_myomr`

2. **Open SQL Tab:**
   - Click on the "SQL" tab in phpMyAdmin
   - You should see a text area for SQL queries

3. **Copy and Paste SQL:**
   - Open file: `dev-tools/sql/ADD-R-KARTHIKA-KABADDI-ARTICLE.sql`
   - Copy the entire SQL content
   - Paste into the phpMyAdmin SQL text area

4. **Execute SQL:**
   - Click "Go" or press Ctrl+Enter
   - Check for success message: "1 row inserted"
   - If error occurs, check:
     - Database name is correct
     - Table `articles` exists
     - Slug is unique (no duplicate)

---

### Step 3: Verify the Article

1. **Check Database:**
   ```sql
   SELECT id, title, slug, status, published_date 
   FROM articles 
   WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';
   ```
   - Should return 1 row with status = 'published'

2. **Check Homepage:**
   - Visit: `https://myomr.in/`
   - Look for the article card in the news section
   - Should appear as one of the latest articles

3. **Check Article Page:**
   - Visit: `https://myomr.in/local-news/from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history`
   - Article should display with:
     - Title
     - Hero image
     - Full article content
     - Interactive elements (share buttons, stats)
     - Related articles section
     - Community awareness section

4. **Verify SEO:**
   - Check page source (View > Page Source)
   - Look for:
     - Meta title tag
     - Meta description tag
     - Open Graph tags (`og:title`, `og:description`, `og:image`)
     - Twitter Card tags
     - JSON-LD structured data (NewsArticle schema)

---

### Step 4: Test Social Sharing

1. **Facebook Debugger:**
   - Visit: https://developers.facebook.com/tools/debug/
   - Enter article URL
   - Click "Debug"
   - Verify image, title, and description display correctly

2. **Twitter Card Validator:**
   - Visit: https://cards-dev.twitter.com/validator
   - Enter article URL
   - Verify card preview

---

## 🔧 Troubleshooting

### Article Not Showing on Homepage

**Possible Issues:**
- Status is not 'published'
- Published date is in the future
- Slug contains special characters

**Solution:**
```sql
UPDATE articles 
SET status = 'published', 
    published_date = NOW() 
WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';
```

---

### Image Not Displaying

**Possible Issues:**
- Image file not uploaded
- Incorrect image path in database
- File permissions issue

**Solution:**
1. Verify image exists at: `/local-news/omr-news-images/r-karthika-kabaddi-gold-medal-2025.webp`
2. Check image path in database:
   ```sql
   SELECT image_path FROM articles 
   WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';
   ```
3. Update if needed:
   ```sql
   UPDATE articles 
   SET image_path = '/local-news/omr-news-images/r-karthika-kabaddi-gold-medal-2025.webp' 
   WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';
   ```

---

### SQL Error: Duplicate Entry

**Error Message:**
```
Duplicate entry 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history' for key 'slug'
```

**Solution:**
1. Check if article already exists:
   ```sql
   SELECT * FROM articles WHERE slug LIKE '%karthika%';
   ```
2. Delete existing entry if needed:
   ```sql
   DELETE FROM articles WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';
   ```
3. Run SQL insert again

---

## 📊 Article Details

**Article Information:**
- **Title:** From Kannagi Nagar to the Podium: How R Karthika and the U-18 Girls' Kabaddi Team Made History
- **Slug:** `from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history`
- **Category:** Sports
- **Tags:** R Karthika, Kabaddi, Kannagi Nagar, Asian Youth Games 2025, U-18 Girls Kabaddi, Chennai Sports, OMR Sports, Tamil Nadu Kabaddi, Sports Achievement, Community Sports
- **Image:** `/local-news/omr-news-images/r-karthika-kabaddi-gold-medal-2025.webp`
- **Status:** published
- **Featured:** Yes (is_featured = 1)

**URL:**
- Article URL: `https://myomr.in/local-news/from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history`

---

## ✅ Success Checklist

- [ ] Image uploaded to `/local-news/omr-news-images/`
- [ ] SQL file executed successfully
- [ ] Article appears on homepage
- [ ] Article page displays correctly
- [ ] SEO meta tags present
- [ ] Social sharing buttons work
- [ ] Interactive features (stats, animations) work
- [ ] Related articles section displays
- [ ] Facebook/Twitter preview works
- [ ] No errors in browser console

---

## 📝 Notes

- **Interactive Features:** The article includes JavaScript for:
  - Social sharing buttons (Facebook, Twitter, WhatsApp)
  - Smooth scroll animations
  - Lazy loading images
  - Intersection Observer for animations

- **SEO Features:**
  - Automatic meta tag generation via `core/article-seo-meta.php`
  - JSON-LD structured data (NewsArticle schema)
  - Breadcrumb structured data
  - Open Graph and Twitter Card tags

- **Content:** The article has been expanded from the original draft with:
  - More detailed tournament statistics
  - Community impact metrics
  - Future goals and aspirations
  - Broader impact on sports infrastructure
  - Enhanced interactive elements

---

## 🆘 Need Help?

If you encounter any issues:
1. Check the troubleshooting section above
2. Review error messages in phpMyAdmin
3. Check browser console for JavaScript errors
4. Verify database connection in `core/omr-connect.php`

---

**Last Updated:** January 10, 2025  
**Created By:** MyOMR Editorial Team
