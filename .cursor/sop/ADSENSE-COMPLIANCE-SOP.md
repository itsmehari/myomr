# ADSENSE-COMPLIANCE-SOP

**Audit ad placements for AdSense policy compliance.**

---

## Scope

Ensure all AdSense placements follow policies to avoid account suspension.

---

## Procedure

### 1. Inventory All Ad Slots

From `core/ad-registry.php`:

```php
$ad_slots = [
    'homepage_hero' => [...],
    'article_midway' => [...],
    'sidebar_primary' => [...],
    // ... all slots
];
```

For each slot, document:
- [ ] Page location (homepage, article detail, list page)
- [ ] Ad network (AdSense, affiliate, etc.)
- [ ] Placement relative to content (top, middle, sidebar)

### 2. Policy Compliance Checklist

**AdSense prohibits:**

- ❌ Ads within 5px of navigation/header
- ❌ Ads on mobile within 3px of click-able elements
- ❌ Ad-blocking anti-adblocker code
- ❌ Deceptive clicks (fake close buttons)
- ❌ Ads covering content
- ❌ Ads with no clear gap from content

**For each ad slot, verify:**

- [ ] NOT adjacent to navigation
- [ ] NOT covering any content
- [ ] Clear gap from clickable elements (links, buttons)
- [ ] Mobile spacing adequate (min 20px padding)
- [ ] Ad transparency (clearly marked as "Ad")

### 3. Site-Wide Audit

```php
// Scan all pages with ads:
// 1. Homepage
// 2. Article detail pages
// 3. Job listing pages
// 4. Event listing pages
// 5. Classifieds pages

// For each, visually inspect:
// - Ad doesn't overlap content
// - Ad has adequate spacing
// - Ad is clearly marked
```

### 4. Screenshot Documentation

Take screenshots for compliance record:

- [ ] Homepage with ads
- [ ] Article page with ads
- [ ] Mobile rendering with ads
- [ ] Sidebar widget with ads

### 5. Create Policy Document

Store in `.cursor/`:

```markdown
# Ad Placement Policy (2026)

## Approved Slots
- Homepage hero: OK (300x250, 50px from header)
- Article midway: OK (300x600, after first paragraph)
- Sidebar: OK (300x250, right column)

## Prohibited Placements
- NOT adjacent to header navigation
- NOT overlaying article text
- NOT autoplaying video ads

## Compliance Status
- Last audit: 2026-05-19
- Violations: 0
- Action items: None
```

### 6. Monitor

After deployment:
- [ ] Check AdSense dashboard (daily)
- [ ] Look for warnings: "Violation detected"
- [ ] Monitor CPM (cost per mille) trends
- [ ] If drops suddenly: Audit for new violations

---

## Remediation

If AdSense flags a violation:

1. Screenshot the warning from AdSense dashboard
2. Identify which ad slot is problematic
3. Fix placement (move, resize, or remove)
4. Request review in AdSense (usually 1-2 weeks)

---

**Last Updated:** 2026-05-19
