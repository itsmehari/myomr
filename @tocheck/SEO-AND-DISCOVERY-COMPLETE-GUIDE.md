# 🔍 Complete SEO & Search Engine Discovery Guide for MyOMR Articles

## 📋 Overview

This guide explains how your article system is set up for SEO and search engine discovery, and what mechanisms ensure Google and other search engines can find and index your articles automatically.

---

## 🏗️ Current SEO Architecture

### **1. Article Display System**

When a user visits `https://myomr.in/local-news/article-slug`, here's what happens:

1. **Request arrives** → `local-news/article.php` handles it
2. **Database query** → Fetches article by `slug`
3. **SEO meta tags loaded** → `core/article-seo-meta.php` generates all SEO tags
4. **Article displayed** → Full content with proper HTML structure

---

## ✅ SEO Mechanisms Already in Place

### **A. Meta Tags (Auto-Generated)**

**File:** `core/article-seo-meta.php`

**What it generates:**

- ✅ **Title Tag**: `<title>Article Title | MyOMR Chennai</title>`
- ✅ **Meta Description**: 155-char excerpt from summary
- ✅ **Meta Keywords**: Tags from database
- ✅ **Canonical URL**: Prevents duplicate content issues
- ✅ **Robots Meta**: `index, follow, max-image-preview:large`
- ✅ **Open Graph Tags**: For Facebook/LinkedIn sharing
- ✅ **Twitter Cards**: Large image cards for Twitter

### **B. Structured Data (JSON-LD Schema)**

**What it includes:**

```json
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "Article Title",
  "description": "Article summary",
  "image": "https://myomr.in/image-path.jpg",
  "datePublished": "2025-10-29T21:00:00+05:30",
  "dateModified": "2025-10-29T21:00:00+05:30",
  "author": { "@type": "Organization", "name": "MyOMR Editorial Team" },
  "publisher": { "@type": "Organization", "name": "MyOMR", "logo": {...} },
  "mainEntityOfPage": { "@type": "WebPage", "@id": "https://myomr.in/local-news/slug" }
}
```

**Why this matters:**

- ✅ Google recognizes this as a news article
- ✅ Rich snippets in search results
- ✅ Better visibility in Google News
- ✅ Enhanced appearance in search results

### **C. Sitemap Generation**

**File:** `sitemap-generator.php`

**What it does:**

- Automatically includes ALL published articles from database
- Generates `sitemap.xml` with proper priorities
- Includes `lastmod` dates for freshness signals
- Articles have priority `0.最低` (higher than events)

**URL Pattern in Sitemap:**

```
https://myomr.in/local-news/article-slug
```

---

## 🚀 Discovery Mechanisms

### **1. Sitemap Submission**

**How to submit:**

1. **Generate Sitemap:**

   ```
   Visit: https://myomr.in/sitemap-generator.php
   Save as: sitemap.xml (or view source and save)
   ```

2. **Submit to Google Search Console:**

   - Go to: https://search.google.com/search-console
   - Select property: `myomr.in`
   - Navigate to: **Sitemaps** section
   - Enter: `https://myomr.in/sitemap.xml`
   - Click: **Submit**

3. **What happens:**
   - Google crawls sitemap within 24-48 hours
   - All articles listed are discovered
   - Google indexes pages based on priority and freshness

### **2. URL Inspection Tool (Fast Indexing)**

**For immediate indexing of new articles:**

1. Go to: **Google Search Console** → **URL Inspection**
2. Enter: `https://myomr.in/local-news/your-article-slug`
3. Click: **Request Indexing**
4. Result: Crawled within minutes (not days)

**This is the FASTEST way to get a new article indexed!**

### **3. Internal Linking**

**How articles are linked:**

- ✅ Homepage (`home-page-news-cards.php`) links to all articles
- ✅ News listing page shows all articles
- ✅ Each article has "Related Articles" section
- ✅ Navigation menu includes news section

**Why this matters:**

- Google crawlers follow internal links
- Articles discovered naturally through site structure
- Distributes page rank/authority

### **4. robots.txt Configuration**

**What it should contain:**

```
User-agent: *
Allow: /
Disallow: /admin/
Disallow: /dev-tools/

Sitemap: https://myomr.in/sitemap.xml
```

**Status:** Currently missing from root. **ACTION REQUIRED** (see below)

---

## 📝 Article Workflow After Adding to Database

### **Step-by-Step Process:**

#### **Step 1: Insert Article to Database**

```sql
INSERT INTO articles (title, slug, summary, content, published_date, status, ...)
VALUES (...);
```

#### **Step 2: Verify Article is Live**

Visit: `https://myomr.in/local-news/your-article-slug`

Check:

- ✅ Article loads correctly
- ✅ SEO meta tags are present (view page source)
- ✅ Structured data is present (JSON-LD)
- ✅ Images load properly

#### **Step 3: Update Sitemap**

```
Visit: https://myomr.in/sitemap-generator.php
Verify: New article appears in sitemap
```

#### **Step 4: Submit to Google**

**Option A: Sitemap (Batch)**

- Submit updated sitemap.xml to Google Search Console
- Google crawls all new articles within 24-48 hours

**Option B: Individual URL (Fast)**

- Use URL Inspection tool for immediate indexing
- Article crawled within minutes

#### **Step 5: Monitor Indexing**

- **24 hours later**: Check Google Search Console → Coverage
- **48 hours later**: Search `site:myomr.in "article title"`
- **Fix any errors** if article not indexed

---

## 🔧 Required Actions to Complete SEO Setup

### **1. Create robots.txt**

**Location:** `public_html/robots.txt`

**Content:**

```
User-agent: *
Allow: /
Disallow: /admin/
Disallow: /dev-tools/
Disallow: /weblog/
Disallow: /backups/

# Sitemap location
Sitemap: https://myomr.in/sitemap.xml
```

**Why needed:**

- Tells search engines where sitemap is
- Prevents crawling of admin areas
- Improves crawl efficiency

### **2. Add URL Rewriting (Optional but Recommended)**

**File:** `.htaccess` (root directory)

**Add this for clean URLs:**

```apache
RewriteEngine On
RewriteBase /

# Redirect article.php?slug=xxx to /local-news/xxx
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^local-news/([^/]+)/?$ local-news/article.php?slug=$1 [L,QSA]
```

**Why needed:**

- Clean URLs are better for SEO
- No query parameters in URLs
- Easier to remember and share

### **3. Verify Canonical URLs**

**Check:**

- All article pages have `<link rel="canonical">` tag
- Canonical points to clean URL (no trailing slash or parameters)
- No duplicate content issues

**Already implemented:** ✅ `core/article-seo-meta.php` includes canonical

### **4. Enable HTTPS**

**Check:**

- Site uses HTTPS (not HTTP)
- All internal links use HTTPS
- Canonical URLs use HTTPS

**Already implemented:** ✅ URLs use `https://myomr.in`

---

## 📊 SEO Checklist for New Articles

### **Before Publishing:**

- [ ] Title includes target keywords
- [ ] Title is 50-60 characters
- [ ] Summary is 150-160 characters (compelling)
- [ ] Tags include relevant keywords objectively
- [ ] Image path is correct and image exists
- [ ] Content includes H2/H3 headings
- [ ] Content includes internal links to other articles
- [ ] Content includes external links to authoritative sources

### **After Publishing:**

- [ ] Article accessible via clean URL
- [ ] SEO meta tags present (view page source)
- [ ] JSON-LD structured data present
- [ ] Sitemap regenerated
- [ ] URL submitted to Google Search Console
- [ ] Article shared on social media (optional but helpful)

---

## 🎯 Advanced SEO Enhancements

### **1. RSS Feed for News Articles**

**Create:** `rss.xml` or `feed.php`

**Why:**

- Google News can consume RSS feeds
- Faster discovery of new articles
- Subscribers get updates automatically

**Would you like me to create this?**

### **2. Article Schema Enhancements**

**Add to JSON-LD:**

- `articleBody` (full content text)
- `keywords` (from tags field)
- `wordCount` (content length)
- `inLanguage` (English)

### **3. Breadcrumb Schema**

**Add breadcrumb navigation schema:**

```json
{
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "https://myomr.in"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Local News",
      "item": "https://myomr.in/local-news"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "Article Title",
      "item": "https://myomr.in/local-news/slug"
    }
  ]
}
```

### **4. Image Schema**

**Add ImageObject schema for article images:**

- Helps with Google Images search
- Better image search rankings

---

## 🔍 Testing & Verification

### **Test SEO Implementation:**

1. **View Page Source:**

   - Check for `<title>` tag
   - Check for `<meta name="description">`
   - Check for `<link rel="canonical">`
   - Check for JSON-LD script tag

2. **Google Rich Results Test:**

   - Go to: https://search.google.com/test/rich-results
   - Enter article URL
   - Verify structured data is valid

3. **Google Mobile-Friendly Test:**

   - Go to: https://search.google.com/test/mobile-friendly
   - Verify article is mobile-friendly

4. **PageSpeed Insights:**
   - Go to: https://pagespeed.web.dev
   - Check loading speed
   - Optimize images if slow

---

## 📈 Monitoring & Analytics

### **Google Search Console Monitoring:**

1. **Coverage Report:**

   - See which pages are indexed
   - Identify crawling errors
   - Fix indexing issues

2. **Performance Report:**

   - See which keywords rank
   - Track click-through rates
   - Identify top-performing articles

3. **Core Web Vitals:**
   - Monitor page speed
   - Fix usability issues
   - Improve user experience

---

## ✅ Summary: What Happens Automatically

### **When you add an article to database:**

1. ✅ **SEO meta tags** automatically generated by `article-seo-meta.php`
2. ✅ **Structured data** automatically added (JSON-LD)
3. ✅ **Canonical URL** automatically set
4. ✅ **Open Graph tags** automatically generated for social sharing
5. ✅ **Article accessible** via clean URL pattern

### **What you need to do manually:**

1. 🔄 **Regenerate sitemap** (visit `sitemap-generator.php`)
2. 📤 **Submit to Google Search Console** (URL Inspection or Sitemap)
3. 📱 **Optional:** Share on social media

---

## 🚨 Action Items Right Now

1. **Create `robots.txt`** (see template above)
2. **Verify sitemap-generator.php works** (test it)
3. **Submit sitemap to Google Search Console** (if not done)
4. **Test article URL** after inserting Amazon article
5. **Submit Amazon article URL** via URL Inspection tool

---

## 📞 Need Help?

**Files to check:**

- `core/article-seo-meta.php` - SEO meta tags generator
- `local-news/article.php` - Article display handler
- `sitemap-generator.php` - Sitemap generator
- `home-page-news-cards.php` - Homepage article listing

**Tools to use:**

- Google Search Console: https://search.google.com/search-console
- Rich Results Test: https://search.google.com/test/rich-results
- Mobile-Friendly Test: https://search.google.com/test/mobile-friendly

---

**Last Updated:** October 29, 2025
