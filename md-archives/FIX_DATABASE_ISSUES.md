# 🔧 Fix Database Issues - Step by Step Guide

## 📋 Issues Found

1. ❌ **Missing Tables**: `businesses` and `gallery` tables don't exist
2. ❌ **Articles Not Showing**: Only published articles display (17 total but only a few showing)

---

## 🚀 Solution Options

### **Option A: Run SQL in phpMyAdmin (Easiest)**

#### **Step 1: Create Missing Tables**

1. Open phpMyAdmin on your live server
2. Go to your database: `metap8ok_myomr`
3. Click the **SQL** tab
4. Run this SQL:

```sql
-- Create businesses table
CREATE TABLE IF NOT EXISTS `businesses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `contact_url` varchar(255) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_featured` (`featured`),
  KEY `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create gallery table
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_url` varchar(500) NOT NULL,
  `caption` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### **Step 2: Check Article Status**

Run this SQL to see article status:

```sql
-- Check total articles
SELECT COUNT(*) as total_articles FROM articles;

-- Check status distribution
SELECT status, COUNT(*) as count FROM articles GROUP BY status;

-- See which articles are published vs not
SELECT id, title, status, published_date
FROM articles
ORDER BY published_date DESC;
```

#### **Step 3: Show All Articles (Choose ONE Option)**

**Option 1: Publish All Articles**

```sql
-- This will mark ALL articles as published
UPDATE articles SET status = 'published' WHERE status IS NULL OR status != 'published';
```

**Option 2: Show All Articles Without Status Filter**

Edit `home-page-news-cards.php` line 7, change from:

```php
$sql = "SELECT id, title, slug, summary, published_date, image_path FROM articles WHERE status = 'published' ORDER BY published_date DESC LIMIT 12";
```

To:

```php
$sql = "SELECT id, title, slug, summary, published_date, image_path FROM articles ORDER BY published_date DESC LIMIT 20";
```

---

### **Option B: Use Development Tools (If SSH Tunnel is Running)**

If you have SSH tunnel active, you can use:

1. **Check Status**:

   - Visit: `http://localhost/dev-tools/check-articles-and-tables.php`

2. **Create Tables**:

   - Visit: `http://localhost/dev-tools/create-missing-tables.php`

3. **Update Configuration**:
   - Edit: `dev-tools/config-remote.php`

---

## 📁 Files to Upload

After running the SQL, upload these fixed files to your server:

### **Must Upload:**

1. ✅ `index.php` - Has error handling for missing tables
2. ✅ `home-page-news-cards.php` - Fixed to use `articles` table

### **Optional Files:**

3. `create-missing-tables.sql` - SQL file (can run in phpMyAdmin)
4. `home-page-news-cards-SHOW-ALL.php` - Version that shows all articles

---

## ✅ Verification Steps

After running SQL and uploading files:

1. **Visit homepage** - Should load without errors
2. **Check table count**:
   ```sql
   SELECT COUNT(*) FROM businesses;
   SELECT COUNT(*) FROM gallery;
   ```
3. **Check articles** - Should now show all 17 articles
4. **View sections** - "Featured Businesses" and "Photo Gallery" should render (even if empty)

---

## 🎯 Quick Summary

**What was wrong:**

- Code was querying `news_bulletin` table (doesn't exist) → Fixed: Now uses `articles`
- Code was querying `businesses` table (doesn't exist) → Fixed: Added error handling
- Code was querying `gallery` table (doesn't exist) → Fixed: Added error handling
- Only showing published articles (filtered by status) → Fixed: Can show all or publish all

**Files changed:**

- ✅ `home-page-news-cards.php` - Uses `articles` table
- ✅ `index.php` - Protected businesses/gallery sections

**Next steps:**

1. Run SQL in phpMyAdmin to create missing tables
2. Choose to either publish all articles or remove status filter
3. Upload `index.php` and `home-page-news-cards.php` to server

---

**Need Help?** Check `dev-tools/README.md` for remote database setup.
