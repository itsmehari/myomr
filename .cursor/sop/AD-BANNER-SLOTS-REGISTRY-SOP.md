# Ad Banner Slots and Registry SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Engineering + Monetization

---

## 1. Scope

How to add, change, and place **programmatic banner ads** using the registry pattern:

- Registry: [core/ad-registry.php](../../core/ad-registry.php)
- Renderer: [components/ad-banner-slot.php](../../components/ad-banner-slot.php)
- Loader: [components/component-includes.php](../../components/component-includes.php) — `omr_ad_slot()`
- Styles: [assets/css/ad-banners.css](../../assets/css/ad-banners.css)

**Rule reference:** [.cursor/rules/ad-banner-component.mdc](../rules/ad-banner-component.mdc)

---

## 2. Concepts

| Concept | Description |
|---------|-------------|
| **Slot ID** | Named placement (e.g. `article-top`, `article-mid`, `homepage-top`) |
| **Size** | `728x90`, `336x280`, `300x250`, `320x50` — must match creative |
| **Ad row** | `omr_ad_slot_row($slot_id, $count)` for multiple random ads in one zone |

Ads are chosen **at random** from active ads that match both `slot_ids` and `sizes`.

---

## 3. Add a new slot ID

1. Add the slot string to `$omr_ad_slot_ids` in [core/ad-registry.php](../../core/ad-registry.php) (documentation + consistency).
2. Call `omr_ad_slot('new-slot-id', '728x90')` (or appropriate size) in the target PHP template.
3. Ensure [assets/css/ad-banners.css](../../assets/css/ad-banners.css) is loaded (component injects link on first use).

---

## 4. Add a new advertiser / creative

1. Append to `$omr_ads` array:

```php
[
  'id'         => 'unique-key',
  'advertiser' => 'Brand',
  'url'        => 'https://...',
  'slot_ids'   => ['article-top', 'article-mid'], // or $omr_ad_slot_ids for site-wide
  'sizes'      => ['728x90', '336x280'],
  'design'     => 'brandkey', // maps to CSS class omr-ad-banner--brandkey
  'headline'   => 'Short headline',
  'tagline'    => 'Optional',
  'active'     => true,
]
```

2. If new **design** key: add case in `_omr_ad_icon()` in [components/ad-banner-slot.php](../../components/ad-banner-slot.php).
3. Add CSS for `.omr-ad-banner--brandkey` in `ad-banners.css`.

---

## 5. Placement examples (existing)

| Location | Typical call |
|----------|----------------|
| News article | `omr_ad_slot('article-top', '728x90');` after share bar; `article-mid` after body |
| Homepage | `homepage-top`, `homepage-mid` in [index.php](../../index.php) |

---

## 6. Validation checklist

- [ ] `active` true only for live campaigns
- [ ] Each ad’s `sizes` includes the size used in `omr_ad_slot` on that page
- [ ] Links use `rel="sponsored noopener noreferrer"` (handled by component)
- [ ] Visible “Ad” label acceptable for placement
- [ ] Mobile: check `320x50` where used

---

## 7. Rollback

Set `active` => false for a bad creative; deploy. For slot removal, delete `omr_ad_slot()` call and redeploy.

---

## 8. Related references

- [.cursor/skills/myomr-project/SKILL.md](../skills/myomr-project/SKILL.md) — Banner ads section
- [NEWS-ARTICLE-DETAIL-QA-SOP.md](NEWS-ARTICLE-DETAIL-QA-SOP.md)
