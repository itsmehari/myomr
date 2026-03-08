# 📰 How MyOMR News System Works

## 🎯 News System Flow

### **1. Homepage (index.php)**

```
User visits homepage
    ↓
Displays news snippets from database
    ↓
home-page-news-cards.php queries articles table
    ↓
Shows 12-20 latest articles with:
- Title
- Image
- Date
- Summary
- "Read More" link
```

### **2. News Listing Page**

```
User clicks "News Highlights" menu
    ↓
Goes to /local-news/news-highlights-from-omr-road.php
    ↓
Shows all news articles (pagination optional)
```

### **3. Individual News Article**

```
User clicks "Read More" on a news card
    ↓
Goes to /local-news/{article-slug}.php
    ↓
Loads full detailed article content
```

---

## 📊 How I've Set Up Pallikaranai Article

### **Step 1: Database Entry (articles table)**

When you run `RUN-THIS-SQL-IN-PHPMYADMIN.sql`, it will:

- ✅ Add entry to `articles` table with slug: `pallikaranai-ramsar-complete-guide-omr-residents`
- ✅ Set status = `published`
- ✅ Include summary and basic content

### **Step 2: Standalone Article File**

I created: `local-news/pallikaranai-ramsar-complete-guide-omr-residents.php`

**This is the full article content** - just like your existing files:

- `EVs-Take-a-hit-Catches-Fire.php`
- `Fire-Breaks-Out-At-Corporation-Dump-Yard-Perungudi-2022.php`
- etc.

### **Step 3: The Connection**

In the database entry, the slug points to the file:

```php
// Database has: slug = 'pallikaranai-ramsar-complete-guide-omr-residents'
// File exists at: local-news/pallikaranai-ramsar-complete-guide-omr-residents.php
```

---

## 🔄 Complete Flow

### **What Happens Now:**

1. **Homepage shows snippet:**

   - Uses data from `articles` table
   - Shows title, summary, image
   - Link points to: `/local-news/pallikaranai-ramsar-complete-guide-omr-residents.php`

2. **User clicks "Read More":**

   - Goes to the standalone PHP file
   - Shows full detailed article content

3. **Database vs File:**
   - **Database:** Stores metadata (title, summary, date, image, tags)
   - **Standalone file:** Contains the full article content (HTML/CSS)

---

## 📁 Files Created

1. ✅ `RUN-THIS-SQL-IN-PHPMYADMIN.sql` - Run this in phpMyAdmin
2. ✅ `local-news/pallikaranai-ramsar-complete-guide-omr-residents.php` - Full article
3. ✅ `info/pallikaranai-marsh-ramsar-wetland.php` - Informational page (no news)

---

## 🚀 Next Steps

1. **Run the SQL** in phpMyAdmin
2. **Upload the files** to your server:
   - `local-news/pallikaranai-ramsar-complete-guide-omr-residents.php`
   - `info/pallikaranai-marsh-ramsar-wetland.php`
   - Updated `index.php`, `components/main-nav.php`, `sitemap.xml`
3. **Test:**
   - Visit homepage - should see article in news cards
   - Click "Read More" - should load full article
   - Visit `/info/pallikaranai-marsh-ramsar-wetland.php` - should show informational page

---

## 💡 Why Two Pages?

- **News Article:** Regulatory/news focus (NGT orders, buyer guides)
- **Info Page:** Educational focus (biodiversity, natural benefits)

Both serve different purposes and attract different audiences!
