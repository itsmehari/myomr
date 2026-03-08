# 📤 R Karthika Article - Server Upload Checklist

## 📋 Files to Upload to Server

### **✅ Required Files (Must Upload)**

#### **1. New Article Content File (SQL)**
**File:** `dev-tools/sql/ADD-R-KARTHIKA-KABADDI-ARTICLE.sql`
- **Action:** Run SQL in phpMyAdmin (NOT upload)
- **Location:** Execute in phpMyAdmin SQL tab
- **Database:** `metap8ok_myomr`
- **Purpose:** Adds R Karthika article to `articles` table

#### **2. Sports SEO Enhancement File (NEW)**
**File:** `local-news/article-sports-seo-enhancement.php`
- **Action:** Upload to server
- **Server Path:** `/local-news/article-sports-seo-enhancement.php`
- **Purpose:** Adds Person, SportsEvent, and FAQPage schemas for sports articles

#### **3. Updated Article Router (MODIFIED)**
**File:** `local-news/article.php`
- **Action:** Upload to server (replace existing)
- **Server Path:** `/local-news/article.php`
- **Purpose:** Includes sports SEO enhancement for sports articles

---

### **🖼️ Image File (If Available)**

#### **Article Hero Image**
**File:** `local-news/omr-news-images/r-karthika-kabaddi-gold-medal-2025.webp`
- **Action:** Upload to server (if you have the image)
- **Server Path:** `/local-news/omr-news-images/r-karthika-kabaddi-gold-medal-2025.webp`
- **Purpose:** Hero image for the article
- **Note:** If image doesn't exist, article will use placeholder logo

---

### **📝 Documentation Files (NOT Required for Server)**

These files are for your reference only - **DO NOT upload** to server:

- ❌ `docs/analytics-seo/R-KARTHIKA-ARTICLE-SEO-OPTIMIZATION.md`
- ❌ `docs/analytics-seo/GOOGLE-RANKING-STRATEGY-R-KARTHIKA.md`
- ❌ `docs/analytics-seo/R-KARTHIKA-ARTICLE-SEO-SUMMARY.md`
- ❌ `docs/analytics-seo/COMPLETE-SEO-ENSURED-R-KARTHIKA.md`
- ❌ `docs/analytics-seo/SPORTS-SEO-ENHANCEMENT-SAFETY.md`
- ❌ `dev-tools/sql/QUICK-FIX-KARTHIKA-NOT-SHOWING.md`
- ❌ `dev-tools/sql/DIAGNOSE-AND-FIX-KARTHIKA-ARTICLE.sql`
- ❌ `dev-tools/sql/HOW-TO-ADD-R-KARTHIKA-ARTICLE.md`

---

## 🚀 Step-by-Step Upload Instructions

### **Step 1: Upload PHP Files**

#### **A. Upload Sports SEO Enhancement File**

1. **Via FTP/cPanel File Manager:**
   - Navigate to: `/local-news/` folder
   - Upload: `article-sports-seo-enhancement.php`
   - Verify: File exists at `/local-news/article-sports-seo-enhancement.php`

#### **B. Upload Updated Article Router**

1. **Via FTP/cPanel File Manager:**
   - Navigate to: `/local-news/` folder
   - **Backup existing:** `article.php` (rename to `article.php.backup`)
   - Upload: `article.php` (replace existing)
   - Verify: File exists at `/local-news/article.php`

---

### **Step 2: Add Article to Database**

1. **Open phpMyAdmin:**
   - Log in to cPanel
   - Open phpMyAdmin
   - Select database: `metap8ok_myomr`

2. **Run SQL:**
   - Click "SQL" tab
   - Copy contents of: `dev-tools/sql/ADD-R-KARTHIKA-KABADDI-ARTICLE.sql`
   - Paste into SQL textarea
   - Click "Go" or "Execute"

3. **Verify Article Added:**
   ```sql
   SELECT id, title, slug, status, published_date 
   FROM articles 
   WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';
   ```
   - Should return 1 row with `status = 'published'`

---

### **Step 3: Upload Image (Optional)**

1. **If you have the image:**
   - Navigate to: `/local-news/omr-news-images/` folder (create if doesn't exist)
   - Upload: `r-karthika-kabaddi-gold-medal-2025.webp`
   - Verify: File exists and is accessible

2. **If image doesn't exist:**
   - Article will use placeholder: `/My-OMR-Logo.jpg`
   - You can upload image later and update database:
     ```sql
     UPDATE articles 
     SET image_path = '/local-news/omr-news-images/r-karthika-kabaddi-gold-medal-2025.webp'
     WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';
     ```

---

## ✅ Verification Steps

### **1. Verify Files Are Uploaded**

**Check via FTP/cPanel:**
- [ ] `/local-news/article-sports-seo-enhancement.php` exists
- [ ] `/local-news/article.php` is updated (check file modification date)

### **2. Verify Article in Database**

**Run in phpMyAdmin:**
```sql
SELECT id, title, slug, status, published_date, image_path 
FROM articles 
WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';
```

**Expected Result:**
- 1 row returned
- `status` = `'published'`
- `published_date` = `'2025-01-10 00:00:00'` (or current date)
- `image_path` = `/local-news/omr-news-images/r-karthika-kabaddi-gold-medal-2025.webp` (or placeholder)

### **3. Verify Article Displays**

**Visit these URLs:**
- [ ] Homepage: `https://myomr.in/` - Article should appear in news cards
- [ ] News Highlights: `https://myomr.in/local-news/news-highlights-from-omr-road.php` - Article should appear
- [ ] Article Page: `https://myomr.in/local-news/from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history` - Full article should display

### **4. Verify SEO Schemas**

**Check Page Source:**
1. Visit article page
2. View page source (Right-click → View Page Source)
3. Search for: `"@type": "Person"`
4. Should find Person schema with R Karthika details
5. Search for: `"@type": "SportsEvent"`
6. Should find SportsEvent schema with Asian Youth Games details
7. Search for: `"@type": "FAQPage"`
8. Should find FAQPage schema with 5 questions

**Or use Rich Results Test:**
- Visit: https://search.google.com/test/rich-results
- Enter: `https://myomr.in/local-news/from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history`
- Should show: NewsArticle, Person, SportsEvent, FAQPage, BreadcrumbList schemas

---

## 🐛 Troubleshooting

### **Article Not Showing on Homepage?**

**Run diagnostic SQL:**
```sql
-- Check article status
SELECT id, title, slug, status, published_date, is_featured 
FROM articles 
WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';

-- Fix if needed
UPDATE articles
SET 
    status = 'published',
    published_date = '2025-01-10 00:00:00',
    is_featured = 1
WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';
```

**Then:**
- Hard refresh browser: `Ctrl + Shift + R` (Windows) or `Cmd + Shift + R` (Mac)
- Clear browser cache
- Check again

---

### **Schemas Not Showing?**

**Check:**
1. `article-sports-seo-enhancement.php` is uploaded correctly
2. `article.php` includes the sports SEO file (check line 77)
3. Article category is "Sports" (check database)
4. Article title contains "kabaddi" or "Karthika" (check database)

**Verify in Database:**
```sql
SELECT category, title, tags 
FROM articles 
WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';
```

**Expected:**
- `category` = `'Sports'`
- `title` contains `'Karthika'` or `'kabaddi'`
- `tags` contains `'Kabaddi'` or `'sport'`

---

### **PHP Errors?**

**Check:**
1. PHP syntax is correct (no errors in file)
2. File permissions are correct (644 or 755)
3. File paths are correct (relative paths work)

**Test:**
- Visit article page directly
- Check for PHP error messages
- Check server error logs if available

---

## 📊 Upload Summary

### **Files to Upload:**
1. ✅ `local-news/article-sports-seo-enhancement.php` (NEW)
2. ✅ `local-news/article.php` (UPDATE existing)

### **SQL to Run:**
1. ✅ `dev-tools/sql/ADD-R-KARTHIKA-KABADDI-ARTICLE.sql` (in phpMyAdmin)

### **Images (Optional):**
1. ⚠️ `local-news/omr-news-images/r-karthika-kabaddi-gold-medal-2025.webp` (if available)

### **Total Files to Upload:**
- **2 PHP files** (1 new, 1 update)
- **1 SQL file** (run in phpMyAdmin, not upload)
- **1 image file** (optional)

---

## 🎯 Quick Upload Checklist

- [ ] Upload `local-news/article-sports-seo-enhancement.php` to server
- [ ] Upload `local-news/article.php` to server (backup existing first)
- [ ] Run SQL from `ADD-R-KARTHIKA-KABADDI-ARTICLE.sql` in phpMyAdmin
- [ ] Upload image `r-karthika-kabaddi-gold-medal-2025.webp` (if available)
- [ ] Verify article appears on homepage
- [ ] Verify article page loads correctly
- [ ] Verify SEO schemas are present (check page source)
- [ ] Submit article to Google Search Console

---

**Last Updated:** January 15, 2025  
**Status:** Ready for deployment  
**Estimated Upload Time:** 5-10 minutes

