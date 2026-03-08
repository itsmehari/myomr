# 📁 Rename Folder: discover-myomr → discover-omr-road

## ✅ Benefits of Renaming

1. **Better SEO**: "omr-road" is what people search for
2. **More Descriptive**: Clearer what the section is about
3. **Keyword Rich**: Contains "OMR Road" directly in URL
4. **Human Friendly**: Easier to understand and remember

---

## 🔧 Steps to Rename

### **On Local Machine (Before Uploading):**

1. **In Windows Explorer** (File Manager):
   - Navigate to your project root
   - Right-click the `discover-myomr` folder
   - Select "Rename"
   - Change to: `discover-omr-road`
   - Press Enter

### **After Uploading to Server:**

The folder will be automatically renamed when you upload the files through FTP/cPanel File Manager.

---

## ✅ Files Already Updated

I've already updated all PHP files that reference this folder:

1. ✅ `components/main-nav.php` - All navigation links
2. ✅ `components/discover-nav.php` - Discover section navigation
3. ✅ `index.php` - SDG link
4. ✅ All internal links in the discover folder files use relative paths (no changes needed)

---

## 🔄 Files That Need Folder Rename

**You need to rename the actual folder:**

- `discover-myomr/` → `discover-omr-road/`

**Upload the updated PHP files:**

- `components/main-nav.php`
- `components/discover-nav.php`
- `index.php`

---

## 🎯 Final URL Structure

**Old URLs:**

- `https://myomr.in/discover-myomr/overview.php`
- `https://myomr.in/discover-myomr/pricing.php`

**New URLs (Better for SEO):**

- `https://myomr.in/discover-omr-road/overview.php`
- `https://myomr.in/discover-omr-road/pricing.php`

---

## ⚠️ Important Notes

- **Old URLs will break** - Consider adding redirects in `.htaccess` if you have external links
- **Update sitemap.xml** after renaming
- **Check Google Search Console** for any indexed URLs
- The internal files (PHP includes) use relative paths `../` so no changes needed there

---

## 🚀 Ready to Upload

Upload these updated files to your server:

1. `components/main-nav.php`
2. `components/discover-nav.php`
3. `index.php`
4. Rename folder on server: `discover-myomr` → `discover-omr-road`

Then test all links to make sure they work!
