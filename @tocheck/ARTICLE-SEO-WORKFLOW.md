# 🚀 Quick Workflow: Adding Articles with Full SEO

## ✅ When You Add a New Article (Like Amazon Layoffs)

### **Step 1: Insert Article to Database**

```sql
-- Run your SQL INSERT statement in phpMyAdmin
-- Example: amazon-layoffs-omr-insert.sql
```

**What happens:**

- ✅ Article added to `articles` table
- ✅ Status: `published`
- ✅ Slug: `amazon-india-to-axe-up-to-1100-jobs-part-of-biggest-global-layoff-since-2022-omr-impact`

---

### **Step 2: Verify Article is Live**

**Test URL:**

```
https://myomr.in/local-news/amazon-india-to-axe-up-to-1100-jobs-part-of-biggest-global-layoff-since-2022-omr-impact
```

**Check:**

- ✅ Page loads without errors
- ✅ All content displays correctly
- ✅ Images load properly

---

### **Step 3: Verify SEO Tags (Critical)**

**View Page Source** (Right-click → View Page Source)

**Look for:**

1. ✅ `<title>` tag with article title
2. ✅ `<meta name="description">` with article summary
3. ✅ `<link rel="canonical">` pointing to article URL
4. ✅ Open Graph tags (`og:title`, `og:description`, `og:image`)
5. ✅ JSON-LD structured data (`<script type="application/ld+json">`)

**If tags are missing:**

- Check that `core/article-seo-meta.php` is included in `article.php`
- Verify article data is being loaded correctly

---

### **Step 4: Update Sitemap**

**Option A: Visit Sitemap Generator**

```
https://myomr.in/sitemap-generator.php
```

**What it does:**

- ✅ Automatically includes your new article
- ✅ Sets priority to 0.9 (high)
- ✅ Sets lastmod date to article's published_date

**Option B: Manual Sitemap Update**

- Download current `sitemap.xml`
- Add new article entry:

```xml
<url>
  <loc>https://myomr.in/local-news/amazon-india-to-axe-up-to-1100-jobs-part-of-biggest-global-layoff-since-2022-omr-impact</loc>
  <lastmod>2025-10-29</lastmod>
  <changefreq>monthly</changefreq>
  <priority>0.9</priority>
</url>
```

---

### **Step 5: Submit to Google (2 Methods)**

#### **Method 1: URL Inspection Tool (FASTEST - Minutes)**

1. Go to: https://search.google.com/search-console
2. Select property: `myomr.in`
3. Click: **URL Inspection** (top search bar)
4. Enter: `https://myomr.in/local-news/amazon-india-to-axe-up-to-1100-jobs-part-of-biggest-global-layoff-since-2022-omr-impact`
5. Click: **Request Indexing**
6. Wait: 1-5 minutes for Google to crawl

**Result:** Article indexed within minutes! ✅

#### **Method 2: Sitemap Submission (24-48 Hours)**

1. Go to: https://search.google.com/search-console
2. Select property: `myomr.in`
3. Navigate to: **Sitemaps**
4. If already added: Click **Resubmit**
5. If not added: Enter `https://myomr.in/sitemap.xml` and click **Submit**

**Result:** All articles in sitemap crawled within 24-48 hours

---

### **Step 6: Verify Indexing (24 Hours Later)**

**Check 1: Google Search Console**

- Go to: **Coverage** report
- Look for your article URL
- Status should be "Indexed" ✅

**Check 2: Google Search**

```
site:myomr.in "Amazon India to Axe Up to 1,100 Jobs"
```

**Check 3: Rich Results Test**

- Go to: https://search.google.com/test/rich-results
- Enter article URL
- Verify structured data is Valid ✅

```

---

## 📋 Quick Checklist

Copy this checklist for each new article:

- [ ] SQL INSERT executed successfully
- [ ] Article accessible via clean URL
- [ ] Page loads without errors
- [ ] SEO meta tags present (view source)
- [ ] JSON-LD structured data present
- [ ] Image loads correctly
- [ ] Sitemap regenerated
- [ ] URL submitted to Google Search Console
- [ ] Article appears in Google Search (after 24-48h)
- [ ] No errors in Search Console

---

## 🎯 SEO Factors Checklist

**Content Quality:**
- [ ] Title includes target keywords
- [ ] Title is 50-60 characters
- [ ] Summary is compelling and 150-160 characters
- [ ] Content has proper H2/H3 headings
- [ ] Internal links to other MyOMR articles
- [ ] External links to authoritative sources
- [ ] Images have descriptive alt text

**Technical SEO:**
- [ ] Canonical URL set correctly
- [ ] No duplicate content
- [ ] Mobile-friendly design
- [ ] Fast page load speed
- [ ] HTTPS enabled

**Social Sharing:**
- [ ] Open Graph tags present
- [ ] Twitter Card tags present
- [ ] Featured image (1200x630px recommended)
- [ ] Social sharing buttons (optional)

---

## 🔧 Troubleshooting

### **Article Not Appearing in Search Results**

**Check:**
1. ✅ Is article status `published` (not `draft`)?
2. ✅ Is robots.txt blocking the page?
3. ✅ Are meta robots tags set to `index, follow`?
4. ✅ Is canonical URL correct?
5. ✅ Was URL submitted to Search Console?

**Fix:**
- Resubmit URL via URL Inspection tool
- Check Search Console for errors
- Verify sitemap includes the article

### **SEO Tags Not Showing**

**Check:**
1. ✅ Is `core/article-seo-meta.php` included in `article.php`?
2. ✅ Are article variables set correctly?
3. ✅ Is article data loading from database?

**Fix:**
- View page source to see what's actually output
- Check PHP error logs
- Verify database connection

### **Sitemap Not Updating**

**Check:**
1. ✅ Did you run `sitemap-generator.php`?
2. ✅ Is article status `published`?
3. ✅ Does SQL query in sitemap-generator work?

**Fix:**
- Manually add article to sitemap
- Check database query in sitemap-generator.php
- Verify article slug in database matches URL

---

## 📊 Expected Timeline

| Action | Time |
|--------|------|
| Insert article to DB | Immediate |
| Article live on site | Immediate |
| Generate sitemap | Immediate |
| Submit via URL Inspection | 1-5 minutes |
| Google crawls page | 1-5 minutes (via Inspection) |
| Google indexes page | Within 1 hour (via Inspection) |
| Sitemap submission crawl | 24-48 hours |
| Appear in search results | 1-3 days |

---

## 💡 Pro Tips

1. **Submit immediately** via URL Inspection tool for fastest indexing
2. **Share on social media** - signals freshness to Google
3. **Link from homepage** - helps with discovery
4. **Monitor Search Console** - catch errors early
5. **Update old articles** - add links to new articles

---

**Last Updated:** October 29, 2025

```
