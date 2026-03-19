# Banner Ads Implementation Plan for mycovai.in

**Purpose:** Replicate the MyOMR.in banner ad system on **mycovai.in**. Use this document (and the referenced MyOMR code) when working in the mycovai.in codebase to implement banner ads.

**Source:** MyOMR.in (`_Root`) — analysis date March 2026.

---

## Part A — How MyOMR Implements Banner Ads

### Architecture (high level)

1. **Registry** — Central list of ads with slot eligibility and sizes.
2. **Component** — One PHP component that renders a single ad slot (random pick from eligible ads).
3. **CSS** — One stylesheet for all slot/layout/size and per-advertiser “design” variants.
4. **Integration** — Pages include a bootstrap/component file, then call `omr_ad_slot($slot_id, $size)` or `omr_ad_slot_row($slot_id, $count)` where they want ads.

No database: ads are defined in a PHP array. Structure is monetization-ready (could later move to DB).

---

### Files in MyOMR (copy/adapt paths for mycovai)

| File | Role |
|------|------|
| **core/ad-registry.php** | Defines `$omr_ad_slot_ids` (all slot IDs used site-wide) and `$omr_ads` (array of ad records: id, advertiser, url, slot_ids, sizes, design, headline, tagline, active). |
| **components/ad-banner-slot.php** | Defines `omr_ad_slot($slot_id, $size)` and `omr_ad_slot_row($slot_id, $count)`. Loads registry, filters by slot + size, picks one ad at random, outputs HTML (with optional “Ad” label). Also defines `_omr_ad_icon($design)` for Font Awesome icon per design. Injects `ad-banners.css` once per page via `OMR_AD_BANNERS_CSS_LOADED`. |
| **assets/css/ad-banners.css** | Styles: `.omr-ad-zone`, `.omr-ad-zone--row`, `.omr-ad-slot`, `.omr-ad-slot__label`, `.omr-ad-banner`, size classes (e.g. `.omr-ad-banner--728x90`, `--336x280`, `--300x250`, `--320x50`), and per-design classes (e.g. `.omr-ad-banner--resumedoctor`, `--mycovai`, `--colourchemist`, `--bseri`). |

### Component bootstrap

- **components/component-includes.php** (or equivalent) ensures `omr_ad_slot` is available: it `require_once`’s `ad-banner-slot.php` when `omr_ad_slot` is not defined. Any page that uses the modular bootstrap and includes this file can call `omr_ad_slot()` / `omr_ad_slot_row()`.

### Ad record shape (in registry)

```php
[
    'id'         => 'unique-id',
    'advertiser' => 'Display Name',
    'url'        => 'https://example.com',
    'slot_ids'   => ['article-top', 'homepage-top', ...],  // where this ad can show
    'sizes'      => ['728x90', '336x280', '300x250', '320x50'],
    'design'     => 'key',  // maps to CSS class .omr-ad-banner--key and _omr_ad_icon('key')
    'headline'   => 'CTA text',
    'tagline'    => 'Supporting line',
    'active'     => true,
]
```

### Standard sizes

- **728x90** — Leaderboard (horizontal).
- **336x280** — Large rectangle (in-content).
- **300x250** — Medium rectangle.
- **320x50** — Mobile leaderboard.

### Conventions

- All ad links: `rel="sponsored noopener noreferrer"`.
- Visible “Ad” label above each slot (`aria-label` / “Sponsored content” where applicable).
- Random selection per slot via `array_rand($eligible)`.
- Debug: if `?omr_ad_debug=1` and registry missing or no eligible ad, component can output HTML comments for troubleshooting.

### Where MyOMR places slots (for reference)

- **Homepage:** `homepage-top` (336x280 in buysell row), `homepage-mid` (336x280 after news).
- **Articles:** `article-top` (728x90), `article-mid` (336x280).
- **Jobs:** `jobs-index-top`, `jobs-index-mid`, `jobs-detail-mid`.
- **Hostels:** `hostels-index-top`, `hostels-index-mid`, `hostels-detail-mid`.
- **Rent/lease, buy-sell, events, listings, discover, elections:** index-top, index-mid or detail-mid as needed.
- **Global:** `global-top` (728x90) on jobs hub.

---

## Part B — Prompt Summary for Implementing on mycovai.in

Use the following as a **prompt or brief** when you implement banner ads in the mycovai.in project. Run this in the mycovai.in codebase (or paste into Cursor there).

---

### Prompt: “Implement banner ads like MyOMR”

**Context:** We want the same banner ad system as MyOMR.in: registry-driven, slot-based, random ad per slot, standard IAB sizes, one component + one CSS file.

**Steps:**

1. **Analyse this codebase (mycovai.in):**
   - Where is the root path defined (e.g. `ROOT_PATH`)? Where are global includes (e.g. `include-path.php`, `page-bootstrap.php`)?
   - Where are reusable components loaded (e.g. a `component-includes.php` or similar that pulls in nav/footer)? We will add the ad component there so any page can call the ad function.
   - What is the canonical assets path (e.g. `/assets/css/`)? We will add `ad-banners.css` there.
   - List main page types: homepage, article/news, listings, detail pages, any hubs. For each, decide where at least one ad slot would go (e.g. “article-top”, “homepage-mid”).

2. **Add the three core pieces:**
   - **Ad registry** (e.g. `core/ad-registry.php`): Define a list of slot IDs used across the site. Define `$omr_ads` (or `$covai_ads` / equivalent) with at least one test ad (advertiser, url, slot_ids, sizes, design, headline, tagline, active). Reuse the ad record shape from Part A.
   - **Ad component** (e.g. `components/ad-banner-slot.php`): Implement `omr_ad_slot($slot_id, $size)` (and optionally `omr_ad_slot_row($slot_id, $count)`). Logic: require registry; filter ads by `active`, `slot_ids`, `sizes`; pick one with `array_rand`; output one `<div class="omr-ad-slot">` with “Ad” label and an `<a class="omr-ad-banner ...">` (headline, tagline, CTA, icon). Use `rel="sponsored noopener noreferrer"`. Add a small helper like `_omr_ad_icon($design)` for icon per design. Ensure ROOT_PATH (or project root) is used for including the registry and that the ad CSS is linked once per page (e.g. via a constant).
   - **Ad CSS** (e.g. `assets/css/ad-banners.css`): Copy/adapt from MyOMR: base slot and zone styles, size classes (728x90, 336x280, 300x250, 320x50), and at least one design class (e.g. default or mycovai). Use CSS variables for theme (e.g. `--omr-max-width`) if the project already has them; otherwise use fallback values.

3. **Wire the component into the project:**
   - In the file that loads shared components (e.g. `component-includes.php`), ensure the ad component is loaded when the ad function is first needed (e.g. `if (!function_exists('omr_ad_slot')) { require_once ... ad-banner-slot.php }`).
   - Use the same pattern as MyOMR: pages that already include this component file can call `omr_ad_slot('slot-id', '728x90')` or `omr_ad_slot('slot-id', '336x280')` with no extra includes.

4. **Place slots on key pages:**
   - Homepage: at least one slot (e.g. `homepage-top` or `homepage-mid`, 336x280).
   - Article/news template: e.g. `article-top` (728x90), `article-mid` (336x280).
   - Listing index and detail pages: one slot each (e.g. `listing-top`, `detail-mid`) as appropriate.
   - Use a wrapper div with a container class if the rest of the site uses one (e.g. `<div class="container py-3"><?php omr_ad_slot('article-top', '728x90'); ?></div>`).

5. **Naming and conventions:**
   - You can keep function names `omr_ad_slot` / `omr_ad_slot_row` for drop-in compatibility, or rename to `covai_ad_slot` and update the component and all call sites. Keep slot IDs and sizes the same so the registry and CSS remain interchangeable.
   - Keep “Ad” label and `rel="sponsored noopener noreferrer"` for clarity and SEO.

6. **Optional:** Add a Cursor rule or short doc in mycovai (e.g. `.cursor/rules/ad-banner-component.mdc` or `docs/ads.md`) that points to the registry, component, and CSS and explains how to add a new advertiser (new entry in registry, new `_omr_ad_icon` case if needed, new design class in CSS).

---

## Part C — Checklist (for mycovai.in)

- [ ] Root/bootstrap and component include path identified.
- [ ] `core/ad-registry.php` (or equivalent) created with slot IDs and at least one ad.
- [ ] `components/ad-banner-slot.php` (or equivalent) implements `omr_ad_slot` (and optionally `omr_ad_slot_row`).
- [ ] `assets/css/ad-banners.css` added and linked once per page from the component.
- [ ] Component loaded via shared component include so all relevant pages can call the ad function.
- [ ] Homepage has at least one ad slot.
- [ ] Article/news template has article-top and article-mid (or equivalent).
- [ ] Other key templates (listings, detail pages) have one slot each where appropriate.
- [ ] All ad links use `rel="sponsored noopener noreferrer"` and visible “Ad” label.
- [ ] Optional: doc or Cursor rule for adding new advertisers.

---

## Part D — Reference: MyOMR file paths

When you need to copy or compare logic, use these paths in the **MyOMR** repo:

- `core/ad-registry.php`
- `components/ad-banner-slot.php`
- `assets/css/ad-banners.css`
- `components/component-includes.php` (ad function wiring)
- `index.php` (homepage slots: `homepage-top`, `homepage-mid`)
- `local-news/article.php` (article-top, article-mid)
- `.cursor/rules/ad-banner-component.mdc` (rule for registry + component + CSS)

This document can be copied into the mycovai.in project (e.g. `docs/BANNER-ADS-IMPLEMENTATION-PLAN.md`) and used with “analyse this codebase” plus the steps above to implement banner ads there.
