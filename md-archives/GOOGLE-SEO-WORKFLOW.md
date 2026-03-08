# 🎯 Google SEO Workflow

## 📋 How Google Finds Your Articles

### **1. Sitemap.xml (Primary Method)**

```
Your sitemap.xml lists all article URLs:
- https://myomr.in/local-news/pallikaranai-ramsar-complete-guide-omr-residents
- https://myomr.in/local-news/article-2-slug
- https://myomr.in/local-news/article-3-slug

Google crawls these URLs and indexes them!
```

### **2. Internal Linking**

```
Homepage (index.php) displays article cards:
→ Links to articles
→ Google follows links
→ Discovers new content
```

### **3. Google Search Console Submission**

```
You can manually submit URLs or the sitemap:
1. Go to: https://search.google.com/search-console
2. Add property: myomr.in
3. Verify ownership
4. Submit sitemap: https://myomr.in/sitemap.xml
```

---

## 🤖 Semi-Automated System

### **Tools I Created:**

#### **1. sitemap-generator.php**

- ✅ Auto-generates sitemap from database
- ✅ Includes all published articles
- ✅ Proper dates and priorities
- ✅ Just visit: `https://myomr.in/sitemap-generator.php`

#### **2. ADD-NEW-ARTICLE.php**

- ✅ Easy form to add articles
- ✅ No SQL knowledge needed
- ✅ Auto-updates database
- ✅ **Security:** Change password in file!

#### **3. Updated home-page-news-cards.php**

- ✅ Shows all database articles
- ✅ Clean URLs (no .php needed)
- ✅ Proper links for SEO

---

## 📊 Complete Workflow

### **Step 1: Add Article**

**Via phpMyAdmin:**

1. Insert into `articles` table
2. Fill: title, slug, summary, content, status='published'

**Via ADD-NEW-ARTICLE.php:**

1. Upload `ADD-NEW-ARTICLE.php` to server
2. Visit: `https://myomr.in/ADD-NEW-ARTICLE.php`
3. Fill form, submit
4. Done!

### **Step 2: Generate Sitemap**

1. Visit: `https://myomr.in/sitemap-generator.php`
2. Copy generated XML
3. Save as `sitemap.xml` (replace old one)
4. Upload to root directory

### **Step 3: Submit to Google**

1. Open Google Search Console
2. Go to Sitemaps section
3. Submit: `https://myomr.in/sitemap.xml`
4. Google crawls automatically!

---

## ✨ What Happens Automatically:

✅ Homepage shows new articles (via home-page-news-cards.php)
✅ Articles appear on news listing page
✅ Sitemap includes all published articles
✅ Google finds articles via sitemap
✅ Users find articles via Google search

---

## 🎯 SEO Best Practices

**For Each Article:**

- ✅ Use descriptive, keyword-rich titles
- ✅ Write unique, original content
- ✅ Include relevant tags
- ✅ Use proper HTML structure
- ✅ Add internal links
- ✅ Include external references

**Example Good Title:**
"70-Meter Crack Splits Perungudi Road, Chennai: Residents Panic as Construction Blamed"

❌ **Not Good:** "Road Crack"

---

## 📝 Quick Reference

**Add article → Generate sitemap → Submit to Google**

**Repeat for every new article!** 🚀
