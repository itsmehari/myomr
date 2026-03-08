# Unwanted Files Identification

**Last Updated:** February 2026  
**Total Files Reviewed:** 52 (Phase 1)  
**Potential Duplicates:** 3  
**Files Needing Review:** 5

---

## 🔄 Potential Duplicate Files

### 1. Subscribe Component Duplicate

**Files:**
- `core/subscribe.php`
- `components/subscribe.php`
- `core/subscribe.css`
- `components/subscribe.css`

**Status:** ⚠️ Needs verification

**Action:**
- [ ] Check which file is actually used
- [ ] Search codebase for `require_once` or `include` statements
- [ ] Consolidate to single location if duplicate
- [ ] Update all references

**Recommendation:**
- Keep in `components/` folder (more appropriate for UI components)
- Remove from `core/` if duplicate

---

### 2. Navigation Component Duplicate

**Files:**
- `components/main-nav.php` (491 lines, comprehensive)
- `components/navbar.php` (unknown size)

**Status:** ⚠️ Needs verification

**Action:**
- [ ] Check if `navbar.php` is used anywhere
- [ ] Compare functionality
- [ ] Remove if duplicate

**Recommendation:**
- Keep `main-nav.php` (more comprehensive)
- Remove `navbar.php` if duplicate

---

### 3. Email Functionality Duplicate

**Files:**
- `core/email.php`
- `core/mailer.php`

**Status:** ⚠️ Needs verification

**Action:**
- [ ] Check both files for functionality
- [ ] Determine which is primary
- [ ] Consolidate if duplicate

**Recommendation:**
- Keep `mailer.php` (has `myomrSendMail()` function)
- Review `email.php` and remove if duplicate

---

## ❓ Files Needing Review

### 1. File Without Extension

**File:** `core/news-old-mahabalipuram-road`

**Issues:**
- No file extension
- Unclear purpose
- Potentially leftover from migration

**Action:**
- [ ] Open and review file contents
- [ ] Determine if still needed
- [ ] Add appropriate extension if needed
- [ ] Delete if unused/obsolete

**Status:** 🔍 Review Required

---

### 2. Pricing Template Files

**Files:**
- `core/pricing.html`
- `core/pricing.css`
- `core/pricing.js`

**Issues:**
- Located in `core/` folder (should be in templates or assets)
- HTML file in core folder is unusual
- May be unused

**Action:**
- [ ] Search for references to these files
- [ ] Check if used in any pages
- [ ] Move to appropriate folder if used
- [ ] Delete if unused

**Status:** 🔍 Review Required

---

### 3. Search Services Template

**File:** `core/search-services.html`

**Issues:**
- HTML file in core folder
- Unclear purpose
- May be unused

**Action:**
- [ ] Search for references
- [ ] Check if used anywhere
- [ ] Move or delete accordingly

**Status:** 🔍 Review Required

---

### 4. Generic JavaScript File

**File:** `core/script.js`

**Issues:**
- Generic filename
- Unclear purpose
- May be unused or legacy

**Action:**
- [ ] Review file contents
- [ ] Check for usage
- [ ] Rename if needed
- [ ] Delete if unused

**Status:** 🔍 Review Required

---

### 5. Database List Utility

**File:** `core/omr-road-database-list.php`

**Issues:**
- Unclear purpose from filename
- May be utility or legacy file

**Action:**
- [ ] Review file contents
- [ ] Check for usage
- [ ] Document purpose if used
- [ ] Delete if unused

**Status:** 🔍 Review Required

---

## 📦 Other Files to Review

### Component Files

**File:** `components/nav-footer-styles.css`

**Issues:**
- May overlap with `footer.css`
- Could be consolidated

**Action:**
- [ ] Compare with `footer.css`
- [ ] Consolidate if overlapping
- [ ] Remove redundant styles

---

## 📊 Summary Statistics

| Category | Count | Status |
|----------|-------|--------|
| Potential Duplicates | 3 | ⚠️ Needs verification |
| Files Needing Review | 5 | 🔍 Review required |
| Clear Issues | 0 | ✅ None identified |

---

## 🎯 Action Plan

### Immediate Actions

1. **Verify Duplicates:**
   - [ ] Search codebase for all `subscribe.php` references
   - [ ] Search codebase for `navbar.php` references
   - [ ] Compare `email.php` and `mailer.php` functionality

2. **Review Unclear Files:**
   - [ ] Open `core/news-old-mahabalipuram-road` and determine purpose
   - [ ] Search for `pricing.html` references
   - [ ] Search for `search-services.html` references
   - [ ] Search for `script.js` references

3. **Clean Up:**
   - [ ] Remove confirmed duplicates
   - [ ] Delete unused files
   - [ ] Move files to appropriate locations
   - [ ] Update all references

### Recommended File Organization

```
core/
├── omr-connect.php ✅
├── url-helpers.php ✅
├── security-helpers.php ✅
├── admin-auth.php ✅
├── admin-config.php ✅
├── error-handler.php ✅
├── env.php ✅
├── mailer.php ✅ (if email.php is duplicate, remove email.php)
├── app-secrets.php ✅
├── cache-helpers.php ✅
├── article-seo-meta.php ✅
└── [review and remove unused files]

components/
├── main-nav.php ✅ (if navbar.php is duplicate, remove navbar.php)
├── subscribe.php ✅ (if core/subscribe.php is duplicate, remove core version)
├── footer.php ✅
├── meta.php ✅
└── [keep all other components]
```

---

## ✅ Verification Checklist

Before deleting any files:

- [ ] Search entire codebase for file references
- [ ] Check `include` and `require` statements
- [ ] Check HTML `<script>` and `<link>` tags
- [ ] Check database references
- [ ] Verify backup exists
- [ ] Test application after removal

---

**Next Review:** After Phase 2-8 analysis (complete codebase review)

---

**Priority:**
1. 🔴 **HIGH:** Verify and remove duplicate files
2. 🟡 **MEDIUM:** Review unclear files
3. 🟢 **LOW:** Consolidate CSS files

