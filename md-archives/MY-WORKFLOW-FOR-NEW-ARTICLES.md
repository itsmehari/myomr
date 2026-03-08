# 🤖 My Standard Workflow for Adding Articles

## 📋 When You Request a New Article

### **1. I Will Create:**

✅ **SQL file** (`ADD-{topic}-ARTICLE.sql`)
- Proper INSERT statement
- News-article style content
- No SQL errors (proper escaping)
- Complete fields

✅ **Instructions file** (`HOW-TO-ADD-{topic}.md`)
- Step-by-step guide
- What file to run
- Where to upload files
- How to test

---

## 📝 Content I Generate:

### **Article Structure:**
```html
<section style="padding: 20px 0;">
  <div class="container">
    <p>Opening paragraph with key info</p>
    
    <p><strong>Main Heading</strong></p>
    <p>Supporting paragraphs...</p>
    
    <p><strong>Additional Info</strong></p>
    <p>More content...</p>
    
    <div style="background:#e7f5e7; border-left:4px solid #22c55e; ...">
      <strong>Important Note Box</strong>
      Content here
    </div>
    
    <p><strong>Official References</strong></p>
    <p>Links to sources...</p>
    
    <p><em>Disclaimer text</em></p>
  </div>
</section>
```

### **Content Guidelines:**
- ✅ News article style (not comprehensive guide)
- ✅ 5-8 paragraphs typically
- ✅ Simple HTML with inline styles
- ✅ No single quotes (use &apos;)
- ✅ SEO-friendly keywords
- ✅ Links to official sources
- ✅ Readable and engaging

---

## 🎯 What You Need to Do:

**After I create files:**

1. **Run SQL** in phpMyAdmin
2. **Upload** `sitemap.xml` if needed
3. **Test** article URL
4. **Done!**

---

## ✨ Benefits of This Workflow:

✅ Consistent quality
✅ SEO optimized
✅ No SQL errors
✅ Quick to add
✅ Google-friendly
✅ Professional format

**Just ask: "Add article about X" and I'll handle everything!** 🚀

