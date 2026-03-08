# ⚡ Quick Fix: R Karthika Article Not Showing

## 🔍 Problem
Article was added to database but not showing on:
- Homepage (`index.php`)
- News Highlights page (`news-highlights-from-omr-road.php`)

## ✅ Solution (3 Steps)

### Step 1: Run Diagnostic SQL

Run this in phpMyAdmin to check the article status:

```sql
-- Check article status and diagnose issues
SELECT 
    id,
    title,
    slug,
    status,
    published_date,
    is_featured,
    image_path,
    CASE 
        WHEN status IS NULL THEN '❌ STATUS IS NULL'
        WHEN status = 'draft' THEN '❌ STATUS IS DRAFT'
        WHEN status != 'published' THEN CONCAT('❌ STATUS IS: ', status)
        WHEN published_date IS NULL THEN '❌ PUBLISHED DATE IS NULL'
        WHEN published_date > NOW() THEN '❌ PUBLISHED DATE IS IN FUTURE'
        ELSE '✅ SHOULD BE VISIBLE'
    END as diagnosis
FROM articles 
WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history'
   OR slug LIKE '%karthika%';
```

**Expected Result:** Should show the article with diagnosis message

---

### Step 2: Fix the Article (Most Common Issues)

Run this SQL to fix the most common issues:

```sql
-- Fix article visibility
UPDATE articles
SET 
    status = 'published',
    published_date = '2025-01-10 00:00:00',
    is_featured = 1,
    updated_at = NOW()
WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history'
   OR slug LIKE '%karthika%';
```

**What this does:**
- ✅ Sets status to 'published'
- ✅ Sets published_date to January 10, 2025
- ✅ Sets is_featured to 1 (appears prominently)
- ✅ Updates timestamp

---

### Step 3: Verify the Fix

Run this to verify the article is now visible:

```sql
-- Verify article is now visible
SELECT 
    id,
    title,
    slug,
    status,
    published_date,
    is_featured,
    CASE 
        WHEN status = 'published' AND published_date <= NOW() THEN '✅ SHOULD BE VISIBLE'
        ELSE '❌ STILL HAS ISSUES'
    END as visibility
FROM articles 
WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';
```

**Expected Result:** Should show `✅ SHOULD BE VISIBLE`

---

## 🔄 After Running SQL

1. **Clear Browser Cache:**
   - Press `Ctrl + Shift + R` (Windows) or `Cmd + Shift + R` (Mac)
   - Or: `Ctrl + F5`

2. **Check Homepage:**
   - Visit: `https://myomr.in/`
   - Article should appear in news cards section

3. **Check News Highlights Page:**
   - Visit: `https://myomr.in/local-news/news-highlights-from-omr-road.php`
   - Article should appear in news grid

4. **Check Article Page:**
   - Visit: `https://myomr.in/local-news/from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history`
   - Article should display correctly

---

## 🚨 Still Not Showing?

### Check Image Path

If the image path is missing or incorrect:

```sql
-- Check current image path
SELECT image_path FROM articles 
WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';

-- Fix image path if missing (use placeholder if image doesn't exist)
UPDATE articles
SET image_path = '/local-news/omr-news-images/r-karthika-kabaddi-gold-medal-2025.webp'
WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';

-- OR use placeholder:
-- UPDATE articles
-- SET image_path = '/My-OMR-Logo.jpg'
-- WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history';
```

### Check Database Connection

Verify the homepage is using the correct database query:

- File: `weblog/home-page-news-cards.php`
- Query should be:
  ```php
  SELECT id, title, slug, summary, published_date, image_path 
  FROM articles 
  WHERE status = 'published' 
  AND slug NOT LIKE '%-tamil' 
  ORDER BY published_date DESC 
  LIMIT 20
  ```

---

## 📋 Complete Diagnostic Query

Run this to see everything about your article:

```sql
-- Complete diagnostic
SELECT 
    id,
    title,
    slug,
    status,
    published_date,
    is_featured,
    image_path,
    category,
    tags,
    created_at,
    updated_at,
    -- Visibility checks
    CASE WHEN status = 'published' THEN 'YES' ELSE 'NO' END as is_published,
    CASE WHEN published_date <= NOW() THEN 'YES' ELSE 'NO' END as is_date_valid,
    CASE WHEN image_path IS NOT NULL AND image_path != '' THEN 'YES' ELSE 'NO' END as has_image,
    CASE WHEN slug NOT LIKE '%-tamil' THEN 'YES' ELSE 'NO' END as is_english,
    -- Final verdict
    CASE 
        WHEN status = 'published' 
         AND published_date <= NOW() 
         AND slug NOT LIKE '%-tamil' 
        THEN '✅ SHOULD BE VISIBLE'
        ELSE '❌ NOT VISIBLE - CHECK ISSUES ABOVE'
    END as final_status
FROM articles 
WHERE slug = 'from-kannagi-nagar-to-the-podium-r-karthika-u18-girls-kabaddi-history'
   OR slug LIKE '%karthika%'
   OR title LIKE '%Karthika%';
```

---

## ✅ Success Checklist

After running the fixes, verify:

- [ ] Article exists in database (Step 1 shows 1 row)
- [ ] Status is 'published'
- [ ] Published date is today or in the past
- [ ] Image path is set (or using placeholder)
- [ ] Browser cache cleared (hard refresh)
- [ ] Article appears on homepage
- [ ] Article appears on news-highlights page
- [ ] Article page loads correctly

---

**Last Updated:** January 15, 2025  
**Created By:** MyOMR Editorial Team
