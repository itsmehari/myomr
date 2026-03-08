# 🚀 Server Deployment Checklist - BLO Feature

**Date:** November 6, 2025  
**Purpose:** Complete list of files to upload and SQL files to run

---

## 📤 FILES TO UPLOAD TO SERVER

### 1. Root Level Files (Upload to root directory)
```
✅ generate-sitemap-index.php          (UPDATED - references new folder)
✅ sitemap-generator.php                (UPDATED - references new page)
✅ sitemap.xml                          (UPDATED - includes new page)
✅ .htaccess                            (UPDATED - added rewrite rule)
```

### 2. Info Folder (Upload to `/info/` folder)
```
✅ info/find-blo-officer.php            (NEW - renamed from election-blo-search-omr.php)
```

### 3. Election BLO Details Folder
**IMPORTANT:** First rename the folder on server:
- **OLD:** `omr-election-blo/` 
- **NEW:** `election-blo-details/`

Then upload these files to `/election-blo-details/`:
```
✅ election-blo-details/generate-blo-sitemap.php    (NEW - renamed from generate-sitemap.php)
✅ election-blo-details/process-blo-csv-data.php    (NEW - renamed from parse-blo-csv.php)
```

### 4. Other Modified Files (if not already uploaded)
```
✅ local-news/article.php               (UPDATED - language linking feature)
✅ home-page-news-cards.php             (UPDATED - filters Tamil articles)
```

---

## 🗄️ SQL FILES TO RUN IN phpMyAdmin

### **Step 1: Create Database Table** (Run ONLY if table doesn't exist)
**File:** `election-blo-details/create-blo-database.sql`
- **Purpose:** Creates the `omr_election_blo` table
- **When to run:** First time setup only
- **Status:** ⚠️ Check if table already exists before running

### **Step 2: Fix Column Size** (Run ONLY if you got truncation warnings)
**File:** `election-blo-details/adjust-section-column.sql`
- **Purpose:** Increases `section_no` column from VARCHAR(20) to VARCHAR(100)
- **When to run:** Only if you saw "Data truncated" warnings during import
- **Status:** ⚠️ Check if already fixed

### **Step 3: Import BLO Records** (Run if data not imported yet)
**File:** `election-blo-details/import-blo-records.sql`
- **Purpose:** Inserts 653 BLO records for AC Shozhinganallur
- **When to run:** If `omr_election_blo` table is empty
- **Status:** ⚠️ Check if data already exists

### **Step 4: Import News Articles** (Run to add English & Tamil articles)
**File:** `election-blo-details/import-blo-news-articles.sql`
- **Purpose:** Adds 2 news articles (English + Tamil) about BLO feature
- **When to run:** To add the news articles to your `articles` table
- **Status:** ✅ **RUN THIS** - Adds news articles with links to search page

---

## 📋 DEPLOYMENT STEPS

### **Phase 1: Folder & File Upload**

1. **Rename folder on server:**
   - Go to cPanel File Manager
   - Find folder: `omr-election-blo`
   - Rename to: `election-blo-details`

2. **Upload root level files:**
   - `generate-sitemap-index.php`
   - `sitemap-generator.php`
   - `sitemap.xml`
   - `.htaccess`

3. **Upload info folder file:**
   - `info/find-blo-officer.php`

4. **Upload election-blo-details folder files:**
   - `election-blo-details/generate-blo-sitemap.php`
   - `election-blo-details/process-blo-csv-data.php` (optional - for future CSV processing)

5. **Upload other modified files (if not already on server):**
   - `local-news/article.php`
   - `home-page-news-cards.php`

---

### **Phase 2: Database Updates**

1. **Check if `omr_election_blo` table exists:**
   ```sql
   SHOW TABLES LIKE 'omr_election_blo';
   ```
   - If **NOT EXISTS** → Run: `create-blo-database.sql`
   - If **EXISTS** → Skip to Step 2

2. **Check if data is imported:**
   ```sql
   SELECT COUNT(*) FROM omr_election_blo;
   ```
   - If **COUNT = 0** → Run: `import-blo-records.sql`
   - If **COUNT > 0** → Data already imported, skip

3. **Check column size (if you got truncation warnings):**
   ```sql
   DESCRIBE omr_election_blo;
   ```
   - Check `section_no` column type
   - If **VARCHAR(20)** → Run: `adjust-section-column.sql`
   - If **VARCHAR(100)** → Already fixed, skip

4. **Import news articles:**
   ```sql
   SELECT COUNT(*) FROM articles WHERE slug LIKE '%blo%';
   ```
   - If **COUNT = 0** → Run: `import-blo-news-articles.sql`
   - If **COUNT > 0** → Articles may already exist, check if you want to update

---

## ✅ VERIFICATION CHECKLIST

After deployment, verify:

- [ ] Folder renamed: `election-blo-details/` exists on server
- [ ] BLO search page works: `https://myomr.in/info/find-blo-officer.php`
- [ ] Sitemap works: `https://myomr.in/election-blo-details/sitemap.xml`
- [ ] Database has 653 BLO records: `SELECT COUNT(*) FROM omr_election_blo;`
- [ ] News articles exist: Check `articles` table for BLO articles
- [ ] Homepage shows English BLO article (not Tamil)
- [ ] Article page shows Tamil link when viewing English article
- [ ] Search functionality works on BLO page

---

## 🔍 QUICK SQL CHECKS

Run these in phpMyAdmin to verify:

```sql
-- Check if table exists
SHOW TABLES LIKE 'omr_election_blo';

-- Check record count (should be 653)
SELECT COUNT(*) as total_records FROM omr_election_blo;

-- Check if news articles exist
SELECT slug, title FROM articles WHERE slug LIKE '%blo%';

-- Check column size
DESCRIBE omr_election_blo;
-- Look for section_no - should be VARCHAR(100)
```

---

## 📝 SUMMARY

### **Must Upload:**
1. ✅ Root files (4 files)
2. ✅ `info/find-blo-officer.php`
3. ✅ `election-blo-details/generate-blo-sitemap.php`
4. ✅ Rename folder: `omr-election-blo` → `election-blo-details`

### **Must Run SQL:**
1. ⚠️ `create-blo-database.sql` - Only if table doesn't exist
2. ⚠️ `import-blo-records.sql` - Only if data not imported
3. ⚠️ `adjust-section-column.sql` - Only if you got truncation warnings
4. ✅ `import-blo-news-articles.sql` - **RUN THIS** to add news articles

---

## 🆘 TROUBLESHOOTING

**If BLO search page shows error:**
- Check if `omr_election_blo` table exists
- Check if data is imported (should have 653 records)
- Verify database connection in `core/omr-connect.php`

**If sitemap doesn't work:**
- Verify folder is renamed to `election-blo-details`
- Check `.htaccess` rewrite rule is uploaded
- Verify `generate-blo-sitemap.php` is in correct folder

**If news articles don't show:**
- Run `import-blo-news-articles.sql` in phpMyAdmin
- Check `articles` table for records with slug containing 'blo'
- Verify `status = 'published'` in database

---

**Ready to deploy!** 🚀

