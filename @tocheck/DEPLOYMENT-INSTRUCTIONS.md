# Deployment Instructions - Canonical Tags Fix

## Files to Upload to Server

### ✅ REQUIRED - Core Files (Upload These)

#### 1. New File - Must Upload
```
core/url-helpers.php
```
**Action:** Upload this new file to your server at `core/url-helpers.php`

#### 2. Modified Core Component
```
components/meta.php
```
**Action:** Upload to replace existing file at `components/meta.php`

#### 3. Server Configuration
```
.htaccess
```
**Action:** Upload to replace existing file at root `.htaccess`
**Important:** This file contains redirect rules - make sure it's uploaded correctly

---

### ✅ REQUIRED - Page Files (Upload These)

#### 4. Homepage
```
index.php
```
**Action:** Upload to replace existing file at root `index.php`

#### 5. Contact Page
```
contact-my-omr-team.php
```
**Action:** Upload to replace existing file at root `contact-my-omr-team.php`

#### 6. Events Page
```
events/index.php
```
**Action:** Upload to replace existing file at `events/index.php`

---

### ✅ REQUIRED - Discover MyOMR Pages (Upload All 7)

```
discover-myomr/overview.php
discover-myomr/pricing.php
discover-myomr/areas-covered.php
discover-myomr/community.php
discover-myomr/features.php
discover-myomr/getting-started.php
discover-myomr/support.php
```
**Action:** Upload all 7 files to replace existing files in `discover-myomr/` directory

---

### 📄 OPTIONAL - Documentation (Not Required for Functionality)

```
CANONICAL-TAGS-FIX-SUMMARY.md
DEPLOYMENT-INSTRUCTIONS.md
```
**Action:** These are documentation files - you can upload them for reference, but they're not needed for the site to work

---

## Quick Upload Checklist

### Step 1: Upload New File
- [ ] `core/url-helpers.php` (NEW FILE - must create on server)

### Step 2: Upload Modified Files
- [ ] `components/meta.php`
- [ ] `.htaccess` (IMPORTANT - contains redirects)
- [ ] `index.php`
- [ ] `contact-my-omr-team.php`
- [ ] `events/index.php`

### Step 3: Upload Discover MyOMR Pages
- [ ] `discover-myomr/overview.php`
- [ ] `discover-myomr/pricing.php`
- [ ] `discover-myomr/areas-covered.php`
- [ ] `discover-myomr/community.php`
- [ ] `discover-myomr/features.php`
- [ ] `discover-myomr/getting-started.php`
- [ ] `discover-myomr/support.php`

---

## Files That Do NOT Need to Be Run

**Important:** None of these files need to be "run" or executed manually. They are all PHP files that will automatically work when accessed via web browser:

- ✅ All PHP files will execute automatically when someone visits the page
- ✅ `.htaccess` will be automatically processed by Apache web server
- ✅ No command-line execution needed
- ✅ No database migrations needed
- ✅ No special setup scripts needed

---

## Testing After Upload

### 1. Test Redirects
Visit these URLs and verify they redirect correctly:
- `http://www.myomr.in/` → Should redirect to `https://myomr.in/`
- `http://myomr.in/` → Should redirect to `https://myomr.in/`
- `http://www.myomr.in/contact-my-omr-team.php` → Should redirect to `https://myomr.in/contact-my-omr-team.php`

### 2. Test Canonical Tags
View page source (Ctrl+U or Cmd+U) on these pages and verify you see:
```html
<link rel="canonical" href="https://myomr.in/...">
```

Test pages:
- `https://myomr.in/`
- `https://myomr.in/contact-my-omr-team.php`
- `https://myomr.in/discover-myomr/overview.php`
- `https://myomr.in/events/index.php`
- `https://myomr.in/local-news/{any-article-slug}`

### 3. Test Contact Page with Query String
Visit:
- `https://myomr.in/contact-my-omr-team.php?subject=test`

View page source and verify canonical tag shows:
```html
<link rel="canonical" href="https://myomr.in/contact-my-omr-team.php">
```
(Should NOT include the query string)

---

## Upload Methods

### Option 1: FTP/SFTP Client (Recommended)
1. Connect to your server via FTP/SFTP
2. Navigate to your website root directory
3. Upload files maintaining the same directory structure
4. Make sure file permissions are correct (644 for files, 755 for directories)

### Option 2: cPanel File Manager
1. Log into cPanel
2. Open File Manager
3. Navigate to your website root
4. Upload files one by one or as a zip and extract

### Option 3: Git (If Using Version Control)
```bash
git add .
git commit -m "Add canonical tags and redirects for SEO"
git push
# Then pull on server
```

---

## Important Notes

1. **Backup First:** Always backup your existing files before uploading replacements
2. **File Permissions:** Ensure `.htaccess` has proper permissions (usually 644)
3. **Test Immediately:** Test redirects and canonical tags right after upload
4. **No Downtime:** These changes won't cause any downtime - they're just file updates

---

## Troubleshooting

### If redirects don't work:
- Check that `.htaccess` file was uploaded correctly
- Verify Apache mod_rewrite is enabled
- Check file permissions on `.htaccess` (should be 644)

### If canonical tags don't appear:
- Clear browser cache
- Check that `core/url-helpers.php` was uploaded
- Verify `components/meta.php` was updated
- Check PHP error logs for any errors

### If pages show errors:
- Check that `core/url-helpers.php` exists and is readable
- Verify all file paths are correct
- Check PHP error logs

---

## Summary

**Total Files to Upload: 12 files**
- 1 new file (`core/url-helpers.php`)
- 11 modified files (replace existing)

**No files need to be "run"** - everything works automatically once uploaded!

