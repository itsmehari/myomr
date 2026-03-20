---
name: Parallel Classifieds Launch
overview: Ship **OMR Classified Ads** at `/omr-classified-ads/` — newspaper-style mixed classifieds parallel to Buy & Sell; 10-day expiry; admin-approved listings; reveal-phone; bilingual EN/Ta CTAs; phased auth (email → Google → SMS). Discovery without a new main-nav item (homepage, footer, topic hubs, category grid).
todos:
  - id: phase0-scope
    content: Locked spec in repo — docs/product/OMR-CLASSIFIED-ADS-SPEC.md (expand category SQL seeds + Tamil CTA strings when implementing)
    status: completed
  - id: phase1-mvp
    content: New folder omr-classified-ads/ — SQL migration, bootstrap, listing-functions (CRUD+filters parity with buy-sell index), index/category/locality/detail slug, post+process, success page, guidelines, admin approve queue, report flow, cron 10-day expiry, generate-sitemap, .htaccess clean URLs
    status: completed
  - id: discovery-ia
    content: components/homepage-classified-ads-section.php + index.php data fetch; core/homepage-categories.php tile; components/footer.php + omr-topic-hubs.php links; omit main-nav link (per spec); optional later — hero unified search option in root index.php
    status: completed
  - id: phase1b-auth
    content: omr_classified_ads_users + session; email+password+verify; Google OAuth + SMS OTP + phone link — single user_id (see omr-classified-ads/README.md)
    status: completed
  - id: phase2-liquidity
    content: WA/TG+IG/FB playbook (docs/product/OMR-CLASSIFIED-ADS-LIQUIDITY-PLAYBOOK.md); GA4 classified_ads events on hub/detail/post/reveal; ops — seed 10–20 listings + approval SLA on cadence
    status: completed
isProject: false
lastUpdated: 2026-03-20
---

# OMR Classified Ads — implementation plan

**Canonical product detail:** [docs/product/OMR-CLASSIFIED-ADS-SPEC.md](../product/OMR-CLASSIFIED-ADS-SPEC.md). This file is the **build checklist + sequencing**; keep spec and plan in sync when requirements change.

**Mirror:** The same plan may exist under Cursor’s `.cursor/plans/` for IDE use; **this repo copy is the version-controlled source.**

---

## Locked product summary

| Area          | Choice                                                                   |
| ------------- | ------------------------------------------------------------------------ |
| Scope         | General mixed classifieds (services + wanted + community); not jobs-only |
| vs Buy & Sell | Duplicates allowed across hubs                                           |
| Lifetime      | **10 days**                                                              |
| Geo           | Any location string; OMR = brand/SEO                                     |
| Moderation    | All **pending** until admin approves                                     |
| Price         | Always optional                                                          |
| Phone         | **Reveal** + rate limit + audit                                          |
| Post v1       | Minimal fields; **no image**                                             |
| Browse        | Filter **parity** with buy-sell index                                    |
| After post    | Thank-you + link (pending copy)                                          |
| Name          | **OMR Classified Ads**                                                   |
| Nav           | **No** new main-nav item — homepage, footer, hubs, category grid         |

---

## Reference implementation

Clone patterns from **`omr-buy-sell/`** (do not share DB tables with buy-sell):

- Bootstrap: `includes/bootstrap.php`
- Listings API: `includes/listing-functions.php` (filters, slug helpers, counts)
- Routing: `listing-detail-omr.php` + root `.htaccess` slug rules (mirror methodology in [docs/analytics-seo/CANONICAL-URL-METHODOLOGY.md](../analytics-seo/CANONICAL-URL-METHODOLOGY.md))
- Post: `post-listing-omr.php`, `process-listing-omr.php`, CSRF from `core/security-helpers.php`
- Rate limit: `omr-buy-sell/includes/rate-limit.php`
- Localities: reuse or generalize `core/omr-localities-buy-sell.php` (or add `core/omr-localities-classified-ads.php` if lists diverge)
- 404: `core/serve-404.php` on missing listing/category

---

## Data model (outline)

- `omr_classified_ads_categories` — seed newspaper-style tree (services / wanted / community)
- `omr_classified_ads_listings` — `user_id`, category, title, slug, description, optional price, location text, contact_phone (for reveal), status (`pending|approved|rejected|expired|sold`), `expiry_date` = created + 10 days, `featured`, timestamps
- `omr_classified_ads_reports` — same idea as buy-sell
- `omr_classified_ads_phone_reveal_log` — listing_id, viewer_user_id or session key, ip, created_at (for abuse review)
- Auth tables — phase 1 minimal user store + session; phase 2+ identity links

---

## Auth phasing (do not collapse into one release)

1. **MVP:** Register/login with email + password (or magic link); email verification flag; session; gated `post-listing` + `reveal` requires login as viewer if you want anti-scrape (spec: reveal — define whether anonymous reveal allowed; default: logged-in only reduces spam).
2. **Phase 2:** Google OAuth; link to user by verified email or account settings “Connect Google”.
3. **Phase 3:** SMS OTP (provider + env secrets); `phone_verified`; optional OTP login.

---

## UI / UX deliverables

- **Index:** Card grid; no image — use icon/placeholder block; show location + optional price + category badge
- **Detail:** Full text, reveal phone, report, breadcrumbs, `ItemListing` or appropriate schema (align with SEO doc)
- **Post:** Short form; success page with “Pending approval” + link to detail (may 403 or show pending state to owner only — decide in build: usually owner sees pending, public 404 until approved)
- **Guidelines:** Bans (jobs, property), 10-day rule, bilingual CTAs
- **Tamil:** Key strings in spec appendix or `lang` snippet as implementation proceeds

---

## Discovery & SEO (no primary nav)

| Surface             | File(s) |
| ------------------- | ------- |
| Homepage strip      | New component + `index.php` queries |
| Category grid       | `core/homepage-categories.php` |
| Footer              | `components/footer.php` |
| Hub cross-links     | `components/omr-topic-hubs.php` |
| Primary hub JSON-LD | **Optional:** omitting `core/site-navigation.php` means this hub is **not** in the sitelink-primary set — **accepted** per spec; footer/hubs still pass PageRank internally |

**Do not** add a top-level `components/main-nav.php` item unless product owner reverses decision.

---

## Ops & monetization hooks

- **Cron:** `omr-classified-ads/cron-expire-listings.php` + `EXPIRE_CRON_KEY` pattern (copy buy-sell)
- **Analytics:** `components/analytics.php` already global; add GA4 events in post + reveal
- **Display ads (optional):** register slots in `core/ad-registry.php` for index + detail
- **Deploy:** add path to `.cpanel.yml` if new folder is not already covered by a parent rule

---

## Suggested build order

1. Migration + seed categories + empty module bootstrap + static `guidelines.php`
2. `listing-functions` + browse `index.php` + category/locality pages + detail + `.htaccess`
3. Post + process + thank-you + pending visibility rules
4. Admin queue (module `admin/` or central admin — pick one; buy-sell uses module admin)
5. Reports + reveal endpoint + rate limit + reveal log table
6. Cron expiry
7. Sitemap generator + wire into root sitemap if applicable
8. Homepage + footer + hubs + homepage-categories
9. Auth MVP (blocks anonymous post if spec requires account)
10. OAuth + SMS later releases

---

## Risks (short)

- **Two classifieds products** — duplicate content; mitigate with copy + moderation
- **Auth delay** — ship email auth before Google/SMS
- **Pending-only listings** — admin workload; track queue depth

---

## Superseded

Earlier “pick one lane” and open discovery questions are closed; changes go through **OMR-CLASSIFIED-ADS-SPEC.md** first, then this plan.
