# 📝 How to Prompt Me to Add Articles

## 🎯 When You Want to Add an Article, Say:

**"Add article: [Your topic/title]"**

**OR**

**"Create article about: [Your topic]"**

---

## 🤖 What I Should Do:

### **Step 1: Ask Clarifying Questions**
```
1. What's the article title?
2. What date should it be published? (or use today)
3. What category? (Local News, Events, etc.)
4. Any specific images?
5. What's the focus/angle of the story?
```

### **Step 2: Create the SQL**
```
File format: ADD-{SLUG}-ARTICLE.sql

Include:
- INSERT INTO articles
- Title, slug, summary, content, category, author, status, date
- News-article style content (simple paragraphs)
- Use &apos; instead of single quotes
- Proper formatting
```

### **Step 3: Generate Content**
```
Write content as:
- News article style (not comprehensive guide)
- 4-8 paragraphs
- Include key facts
- Links to references if applicable
- SEO-friendly
```

### **Step 4: Provide Instructions**
```
Tell user:
1. Run [SQL file] in phpMyAdmin
2. Upload updated sitemap.xml
3. Generate new sitemap with sitemap-generator.php
4. Test the article URL
```

---

## ✅ Example Prompt:

**You say:**
> "Add article about new metro line opening on OMR"

**I should:**
1. Ask: When, what details, which station, any official announcement?
2. Create: `ADD-metro-line-omr-opening.sql`
3. Generate: News-style article content
4. Provide: Complete instructions

---

## 📋 Article Checklist (My Job):

✅ SQL file ready to run
✅ Content formatted as news article
✅ No single quote issues (use &apos;)
✅ Summary is catchy and SEO-friendly
✅ Proper slug (lowercase, hyphens)
✅ Status = published
✅ Includes references/links
✅ Instructions clear and simple

---

## 🎯 Works Every Time!

Just say: **"Add article: [topic]"** and I'll handle the rest! 🚀

