# NEWS-ARTICLE-HTML-QA-SOP

**Quality assurance gate for news articles before live publishing.**

---

## Scope

Validate HTML content, metadata, and media before publishing articles to live MyOMR.

---

## RACI

- **Responsible:** Builder (runs QA checks)
- **Accountable:** Architect (approves publish)
- **Consulted:** Content lead (OMR angle verification)
- **Informed:** Project lead

---

## Preconditions

- [ ] Article HTML file prepared (saved as UTF-8 no BOM)
- [ ] Images collected and stored in `local-news/omr-news-images/`
- [ ] Meta title and description written (< 160 chars)
- [ ] Article slug determined (unique, lowercase, hyphens)

---

## Procedure

### 1. HTML Validation

**Check file encoding:**
```bash
file local-news/articles/draft.html
# Should show: UTF-8 Unicode text
# NOT: UTF-8 Unicode (with BOM) text
```

If BOM present, re-save as UTF-8 no BOM in editor (VS Code → bottom right → choose "UTF-8").

**Validate HTML structure:**
- [ ] All opening tags have closing tags (no `<div>` without `</div>`)
- [ ] No editor artifacts: `</motion>`, `<o:p>`, `<!--[if mso]>`, etc.
- [ ] Images have `alt` text: `<img alt="description" src="...">`
- [ ] Links have `href`: `<a href="url">text</a>`
- [ ] Lists proper: `<ul><li>item</li></ul>` (not loose `<li>` tags)

**Browser validation (DevTools):**
```javascript
// Open browser console on local dev page, run:
const html = document.documentElement.outerHTML;
const parser = new DOMParser();
const doc = parser.parseFromString(html, 'text/html');
console.log(doc.querySelectorAll('*').length, 'tags found');
// Should show no parsing errors in console
```

### 2. OMR Angle Verification

- [ ] Article mentions OMR, Chennai, or local impact
- [ ] First paragraph explains relevance to OMR community
- [ ] Example: "... affects OMR residents because..."
- [ ] NOT generic news (must have local angle)

### 3. Source Attribution & Links

- [ ] Source cited (e.g., "Source: [News9 Tamil](url)")
- [ ] If original PDF/document available: Include download link
- [ ] Attribution visible to readers (not hidden in code comments)
- [ ] Links have `rel="nofollow"` or appropriate rel tags

### 4. Image Validation

- [ ] All image paths start with `local-news/omr-news-images/`
- [ ] All image files exist (verify in file system)
- [ ] Image file names: lowercase, hyphens, no spaces
- [ ] Image alt text descriptive (not "image" or blank)
- [ ] Image size reasonable (< 500KB per image)

**Check image references:**
```php
// In PHP template before publish:
preg_match_all('/<img[^>]+src="([^"]+)"/', $html, $matches);
foreach ($matches[1] as $path) {
    if (!file_exists(ROOT_PATH . '/' . $path)) {
        die("Missing image: " . $path);
    }
}
```

### 5. Meta Tags & SEO

- [ ] Title tag: 50-60 chars, keyword-rich
- [ ] Meta description: 150-160 chars, includes OMR
- [ ] Slug: lowercase, hyphens, URL-safe
- [ ] Keywords in first paragraph (for readability)

**Example:**
```
Title: "Tamil Nadu Cabinet Portfolios 2026: OMR Chief Minister Updates"
Description: "Details of 2026 Tamil Nadu cabinet ministers and ministers of state, including OMR constituency representatives."
Slug: "tamil-nadu-cabinet-portfolios-2026"
```

### 6. Tamil Version (if applicable)

- [ ] If Tamil version exists: Check same validations
- [ ] Rewritten (not literal translation)
- [ ] Tamil characters render correctly (UTF-8 no BOM)
- [ ] Same images + media used

### 7. Affiliate Products Check (if news article)

- [ ] Affiliate products placed after mid-article ad
- [ ] Links have `rel="sponsored nofollow noopener noreferrer"`
- [ ] Affiliate disclosure visible ("As an Amazon Associate...")
- [ ] Max 3 products per article

### 8. Dry Run (Local)

```bash
# Save article to local DB (test):
php dev-tools/sql/run_test_article.php

# Check rendering:
# 1. Navigate to http://localhost:8000/article/{id}/slug (or live URL)
# 2. Verify: Title, images, links display correctly
# 3. Verify: No console errors
# 4. Verify: Mobile responsive (open DevTools → mobile view)
```

### 9. Pre-Publish Checklist

**Run before marking ready for live:**

- [ ] HTML valid (no unclosed tags)
- [ ] Encoding UTF-8 no BOM
- [ ] OMR angle clear in first paragraph
- [ ] Source cited with link
- [ ] All images exist + have alt text
- [ ] Meta title/description correct
- [ ] Slug unique + lowercase
- [ ] No affiliate products OR products properly disclosed
- [ ] Dry run successful (no DB errors)
- [ ] Page renders correctly
- [ ] Mobile responsive

---

## Validation

**Checklist for approver (architect):**

- [ ] HTML validation passed
- [ ] OMR angle clear
- [ ] Images + sources correct
- [ ] Meta tags optimized
- [ ] Pre-publish checklist signed off
- [ ] Ready for live

---

## Rollback

If article published with errors:

```sql
DELETE FROM news_articles WHERE id = {id};
```

Regenerate sitemap + notify Search Console.

---

## Evidence

Document for team:

- [ ] Screenshot: Browser rendering (desktop + mobile)
- [ ] Screenshot: DevTools console (no errors)
- [ ] Copy of meta tags + slug
- [ ] List of images used
- [ ] QA checklist signed off

---

## Related References

- [NEWS-ARTICLE-PUBLISHING-SOP.md](./NEWS-ARTICLE-PUBLISHING-SOP.md) — End-to-end publishing
- [LIVE-PUBLISH-CHECKLIST-SOP.md](./LIVE-PUBLISH-CHECKLIST-SOP.md) — Master checklist
- [docs/validation.md](../docs/validation.md) — HTML validation rules
- [KNOWN-ISSUES.md](../KNOWN-ISSUES.md) — #003, #004 (HTML gotchas)

---

**Last Updated:** 2026-05-19
