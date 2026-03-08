# 🚀 Final Deployment Guide - Complete BLO Feature

**Date:** November 6, 2025  
**Status:** ✅ Ready for Production

---

## 📤 FILES TO UPLOAD TO SERVER

### **1. Root Level Files (Upload to root directory `/`)**
```
✅ generate-sitemap-index.php          (UPDATED - references new folder)
✅ sitemap-generator.php                (UPDATED - references new page)
✅ sitemap.xml                          (UPDATED - includes new page)
✅ .htaccess                            (UPDATED - added rewrite rule)
```

### **2. Info Folder (Upload to `/info/` folder)**
```
✅ info/find-blo-officer.php            (NEW - renamed from election-blo-search-omr.php)
✅ info/process-blo-subscription.php    (NEW - subscription email handler)
```

### **3. Election BLO Details Folder**
**IMPORTANT:** First rename the folder on server:
- **OLD:** `omr-election-blo/` 
- **NEW:** `election-blo-details/`

Then upload these files to `/election-blo-details/`:
```
✅ election-blo-details/generate-blo-sitemap.php    (NEW - renamed from generate-sitemap.php)
✅ election-blo-details/process-blo-csv-data.php    (NEW - renamed from parse-blo-csv.php)
```

### **4. Local News Folder (Upload to `/local-news/` folder)**
```
✅ local-news/article.php               (UPDATED - added community section + SEO schemas)
```

### **5. Other Modified Files (if not already uploaded)**
```
✅ home-page-news-cards.php             (UPDATED - filters Tamil articles)
```

---

## 🗄️ SQL FILES TO RUN IN phpMyAdmin

### **Step 1: Create Database Table** (Run ONLY if table doesn't exist)
**File:** `election-blo-details/create-blo-database.sql`
- **Purpose:** Creates the `omr_election_blo` table
- **When to run:** First time setup only
- **Check first:** `SHOW TABLES LIKE 'omr_election_blo';`
- **Status:** ⚠️ Check if table already exists before running

### **Step 2: Fix Column Size** (Run ONLY if you got truncation warnings)
**File:** `election-blo-details/adjust-section-column.sql`
- **Purpose:** Increases `section_no` column from VARCHAR(20) to VARCHAR(100)
- **When to run:** Only if you saw "Data truncated" warnings during import
- **Check first:** `DESCRIBE omr_election_blo;` (look for section_no column type)
- **Status:** ⚠️ Check if already fixed (should be VARCHAR(100))

### **Step 3: Import BLO Records** (Run if data not imported yet)
**File:** `election-blo-details/import-blo-records.sql`
- **Purpose:** Inserts 653 BLO records for AC Shozhinganallur
- **When to run:** If `omr_election_blo` table is empty
- **Check first:** `SELECT COUNT(*) FROM omr_election_blo;`
- **Status:** ⚠️ Check if data already exists (should have 653 records)

### **Step 4: Import News Articles** (RUN THIS - Adds English & Tamil articles)
**File:** `election-blo-details/import-blo-news-articles.sql`
- **Purpose:** Adds 2 news articles (English + Tamil) about BLO feature
- **When to run:** To add the news articles to your `articles` table
- **Check first:** `SELECT COUNT(*) FROM articles WHERE slug LIKE '%blo%';`
- **Status:** ✅ **RUN THIS** - Adds news articles with links to search page

---

## 📋 COMPLETE DEPLOYMENT STEPS

### **Phase 1: Folder & File Upload**

1. **Rename folder on server:**
   - Go to cPanel File Manager
   - Find folder: `omr-election-blo`
   - Rename to: `election-blo-details`

2. **Upload root level files (4 files):**
   - `generate-sitemap-index.php`
   - `sitemap-generator.php`
   - `sitemap.xml`
   - `.htaccess`

3. **Upload info folder files (2 files):**
   - `info/find-blo-officer.php`
   - `info/process-blo-subscription.php`

4. **Upload election-blo-details folder files (2 files):**
   - `election-blo-details/generate-blo-sitemap.php`
   - `election-blo-details/process-blo-csv-data.php` (optional - for future CSV processing)

5. **Upload local-news folder file (1 file):**
   - `local-news/article.php`

6. **Upload other modified files (if not already on server):**
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
   - If **COUNT = 653** → Data already imported, skip

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
   - If **COUNT = 2** → Articles already exist, check if you want to update

---

## ✅ QUICK CHECKLIST

### **Files to Upload (Total: 9 files)**
- [ ] Root: `generate-sitemap-index.php`
- [ ] Root: `sitemap-generator.php`
- [ ] Root: `sitemap.xml`
- [ ] Root: `.htaccess`
- [ ] `info/find-blo-officer.php`
- [ ] `info/process-blo-subscription.php`
- [ ] `election-blo-details/generate-blo-sitemap.php`
- [ ] `local-news/article.php`
- [ ] `home-page-news-cards.php` (if not already uploaded)

### **SQL Files to Run (Check first, then run if needed)**
- [ ] `create-blo-database.sql` - Only if table doesn't exist
- [ ] `adjust-section-column.sql` - Only if you got truncation warnings
- [ ] `import-blo-records.sql` - Only if data not imported
- [ ] `import-blo-news-articles.sql` - **RUN THIS** to add news articles

### **Server Actions**
- [ ] Rename folder: `omr-election-blo` → `election-blo-details`

---

## 🔍 VERIFICATION AFTER DEPLOYMENT

### **1. Test BLO Search Page:**
- Visit: `https://myomr.in/info/find-blo-officer.php`
- Test location filter
- Test polling station filter
- Verify results display correctly

### **2. Test Subscription Form:**
- Fill in email and submit
- Check for success message
- Verify email received at harikrishnanhk1988@gmail.com
- Check CSV log: `weblog/blo-subscriptions.csv`

### **3. Test News Article:**
- Visit: `https://myomr.in/local-news/find-your-blo-officer-shozhinganallur-electoral-roll-revision`
- Verify community section appears
- Test subscription form on article page
- Check language link (English/Tamil)

### **4. Test Sitemap:**
- Visit: `https://myomr.in/election-blo-details/sitemap.xml`
- Verify it loads correctly

### **5. Database Verification:**
```sql
-- Check BLO records
SELECT COUNT(*) FROM omr_election_blo; -- Should be 653

-- Check news articles
SELECT slug, title FROM articles WHERE slug LIKE '%blo%'; -- Should show 2 articles

-- Check table structure
DESCRIBE omr_election_blo; -- Verify section_no is VARCHAR(100)
```

---

## 📊 SUMMARY

### **Must Upload:**
- ✅ 9 PHP/XML files
- ✅ 1 folder rename (omr-election-blo → election-blo-details)

### **Must Run SQL:**
- ⚠️ `create-blo-database.sql` - Only if table doesn't exist
- ⚠️ `import-blo-records.sql` - Only if data not imported
- ⚠️ `adjust-section-column.sql` - Only if you got truncation warnings
- ✅ `import-blo-news-articles.sql` - **RUN THIS** to add news articles

---

## 🆘 TROUBLESHOOTING

**If BLO search page shows error:**
- Check if `omr_election_blo` table exists
- Check if data is imported (should have 653 records)
- Verify database connection in `core/omr-connect.php`

**If subscription form doesn't work:**
- Check if `info/process-blo-subscription.php` is uploaded
- Verify email function works (check `core/email.php`)
- Check file permissions on `weblog/` folder

**If sitemap doesn't work:**
- Verify folder is renamed to `election-blo-details`
- Check `.htaccess` rewrite rule is uploaded
- Verify `generate-blo-sitemap.php` is in correct folder

**If news articles don't show:**
- Run `import-blo-news-articles.sql` in phpMyAdmin
- Check `articles` table for records with slug containing 'blo'
- Verify `status = 'published'` in database

---

## 📝 FILES SUMMARY

### **New Files Created:**
1. `info/find-blo-officer.php` - BLO search page
2. `info/process-blo-subscription.php` - Subscription handler
3. `election-blo-details/generate-blo-sitemap.php` - Sitemap generator

### **Files Modified:**
1. `generate-sitemap-index.php` - Added new sitemap reference
2. `sitemap-generator.php` - Added new page + excluded Tamil articles
3. `sitemap.xml` - Added new page
4. `.htaccess` - Added rewrite rule
5. `local-news/article.php` - Added community section + SEO schemas
6. `home-page-news-cards.php` - Filter Tamil articles

### **SQL Files:**
1. `create-blo-database.sql` - Create table (run if needed)
2. `adjust-section-column.sql` - Fix column (run if needed)
3. `import-blo-records.sql` - Import data (run if needed)
4. `import-blo-news-articles.sql` - Import articles (**RUN THIS**)

---

**Ready to deploy!** 🚀

