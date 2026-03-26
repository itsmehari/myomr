# Affiliate Catalog Maintenance SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Content/Marketing + Engineering

---

## 1. Scope

Ongoing maintenance of the **Amazon affiliate product catalog** and **targeting segments** used by [components/amazon-affiliate-spotlight.php](../../components/amazon-affiliate-spotlight.php).

**Source of truth:** [core/affiliate-registry.php](../../core/affiliate-registry.php)

**Companion:** Full system behavior is documented in [AMAZON-AFFILIATE-SYSTEM-SOP.md](AMAZON-AFFILIATE-SYSTEM-SOP.md).

---

## 2. Ownership

| Task | Owner |
|------|--------|
| Product titles, images, benefit copy | Marketing / editorial |
| Segment keywords and pool membership | Marketing + engineering review |
| Code/regression | Engineering |

---

## 3. Product record schema

Each product in `$omr_affiliate_products` should include:

| Field | Required | Notes |
|-------|----------|--------|
| `id` | Yes | Stable id for GA4 `event_label` |
| `network` | Yes | e.g. `amazon` |
| `title` | Yes | Real product name |
| `benefit` | Yes | One short conversion line |
| `url` | Yes | Affiliate link (shortlink OK) |
| `image_url` | Strongly recommended | HTTPS; empty shows icon fallback |
| `active` | Yes | `false` to pause without deleting history |

---

## 4. Targeting map

`$omr_affiliate_targeting` defines:

- **Segments** (e.g. `career`, `education`, `business`) with `match` keyword lists
- **`default`** pool when no segment matches
- **`product_ids`** referencing entries in `$omr_affiliate_products`

**Rules:**

1. Keywords are matched against lowercase concatenation of article title + category + tags.
2. Multiple segments can contribute ids; pool is built in order — avoid huge duplicate lists (dedupe is by pool building logic in component).
3. Keep `default` pool healthy with at least 4 active products when possible.

---

## 5. Monthly optimization workflow

1. Export or review GA4 events: `affiliate_link_click` by `event_label` and `affiliate_position`.
2. Identify low-CTR products: refresh **benefit** line or replace product id in segments.
3. Identify high-CTR products: add ids to relevant segment `product_ids`.
4. Deactivate discontinued or broken links (`active` = false).
5. Update placeholder rows (`*-update` ids) with real metadata after manual verification on Amazon.

---

## 6. Validation checklist

- [ ] Every active product has valid `url` (opens, affiliate tag intact)
- [ ] Images load over HTTPS without mixed content warnings
- [ ] No misleading fixed prices in static copy unless maintained weekly
- [ ] Disclosure remains visible on article pages (component-level)

---

## 7. Rollback

Revert git commit touching `core/affiliate-registry.php` or set `active=false` for bad rows and deploy.

---

## 8. Related references

- [AMAZON-AFFILIATE-SYSTEM-SOP.md](AMAZON-AFFILIATE-SYSTEM-SOP.md)
- [GA4-EVENT-TRACKING-SOP.md](GA4-EVENT-TRACKING-SOP.md)
- [NEWS-ARTICLE-DETAIL-QA-SOP.md](NEWS-ARTICLE-DETAIL-QA-SOP.md)
