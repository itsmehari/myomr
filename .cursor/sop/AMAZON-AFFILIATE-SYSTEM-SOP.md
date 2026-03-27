# Amazon Affiliate System SOP (MyOMR)

Operational SOP for managing the Amazon affiliate recommendation system used on news article detail pages.

---

## 1) Scope

This SOP covers:

- Product catalog management
- Category/tag targeting rules
- Rotation behavior
- Tracking verification
- Compliance checks before deployment

Primary placement: `local-news/article.php`.

---

## 2) Architecture and files

- `core/affiliate-registry.php`
  - Source of truth for products and targeting rules.
- `components/amazon-affiliate-spotlight.php`
  - Selection logic + rendering of recommendation cards.
- `components/component-includes.php`
  - Loads the affiliate component.
- `local-news/article.php`
  - Placement and GA4 click tracking payload.

---

## 3) Product catalog format

Each product entry in `core/affiliate-registry.php` should include:

- `id` (unique, stable)
- `network` (`amazon`)
- `title` (real product name)
- `benefit` (short conversion-focused line)
- `url` (affiliate shortlink)
- `image_url` (product thumbnail URL)
- `active` (`true`/`false`)

Guidelines:

- Use clear, human-readable titles.
- Keep benefit line to one sentence.
- Prefer image URLs that load reliably over HTTPS.
- Set `active=false` instead of deleting entries if you need to pause products.

---

## 4) Targeting rules

Targeting map key structure:

- segment name (`career`, `education`, `business`, `default`)
- `match` keyword list
- `product_ids` list

How targeting works:

1. Component builds article context from title + category + tags.
2. If any segment keyword matches article context, that segment's `product_ids` become eligible.
3. If no segment matches, component uses `default` pool.
4. Inactive products are always excluded.

Recommendation:

- Keep segment keywords lowercase.
- Start broad, then refine based on GA4 performance.

---

## 5) Rotation logic

- Rotation seed uses article slug + current date.
- Output is deterministic for that day (same article -> same product order).
- Up to **three** cards are rendered when the product pool has at least three active items (otherwise two or one).
  - Positions 1–3 map to consecutive pool indices (wrap-around), no duplicate products when the pool size allows.

This balances UX consistency and freshness.

---

## 6) Tracking and validation

Event name:

- `affiliate_link_click`

Expected event params:

- `event_label` (product id)
- `affiliate_network` (amazon)
- `affiliate_position` (1, 2, or 3)
- `article_title`

Quick validation:

1. Open any article detail page.
2. Click each visible affiliate card.
3. Verify GA4 DebugView receives `affiliate_link_click`.
4. Confirm position values are distinct (1, 2, 3 when three cards show).

---

## 7) Compliance checklist (must pass)

Before deploy, confirm:

- [ ] Each affiliate card shows visible disclosure text.
- [ ] Links use `rel="sponsored nofollow noopener noreferrer"`.
- [ ] No misleading pricing/claims are hardcoded unless regularly maintained.
- [ ] Broken or unresolved products are marked clearly and/or deactivated.

---

## 8) Content ops workflow

When adding/updating affiliate products:

1. Add or update product entry in `core/affiliate-registry.php`.
2. Map product id into one or more segment `product_ids`.
3. Keep `default` pool healthy (at least 4 active products).
4. Smoke test 2-3 article categories.
5. Monitor GA4 for 3-7 days:
   - CTR by product id
   - CTR by position
   - CTR by segment intent

Optimization cycle:

- Promote high CTR products into relevant segments.
- Deactivate low performers.
- Refresh benefit lines quarterly.

---

## 9) Failure handling

If recommendations do not appear:

1. Verify registry file exists: `core/affiliate-registry.php`.
2. Verify at least one product has `active=true`.
3. Verify `omr_amazon_affiliate_spotlight(...)` is called in `local-news/article.php`.
4. Check PHP errors/logs for include path or syntax issues.

If product appears without image:

- Check `image_url` is valid and publicly reachable.
- Keep fallback icon behavior as acceptable temporary state.

---

## 10) Ownership

- Engineering owns component logic, tracking payload, and render stability.
- Content/marketing owns product metadata quality and segment mapping quality.
- Both teams jointly review performance monthly.
