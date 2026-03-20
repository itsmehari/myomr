# OMR Classified Ads — product spec (locked)

**Status:** Discovery answers consolidated — March 2026  
**Related:** Parallel module to [omr-buy-sell](../../omr-buy-sell/) (“Buy & Sell”).  
**Implementation plan:** [docs/plans/OMR-CLASSIFIED-ADS-LAUNCH-PLAN.md](../plans/OMR-CLASSIFIED-ADS-LAUNCH-PLAN.md)  
**Liquidity / growth:** [OMR-CLASSIFIED-ADS-LIQUIDITY-PLAYBOOK.md](OMR-CLASSIFIED-ADS-LIQUIDITY-PLAYBOOK.md)

## One-liner

General **newspaper-style** local classifieds on MyOMR — mixed categories (services, wanted, community notices), **not** a jobs-only or goods-only product.

## Positioning vs other hubs

| Hub | Role |
|-----|------|
| **OMR Classified Ads** (new) | Broad classifieds; mixed board. |
| Buy & Sell | Used goods C2C. |
| Jobs | Formal employment. |
| Rent & Lease | Property rent/lease. |

**Cross-posting:** Allowed — same poster may use Buy & Sell and OMR Classified Ads; user chooses hub. (Watch for duplicate SEO and spam; moderate.)

## Rules

- **Geography:** Listings may be from anywhere; **OMR** is brand/SEO focus — support a visible **location** field (city/area).
- **Default listing life:** **10 days** then expired (cron); renewal policy TBD (manual repost vs extend).
- **Moderation:** New listings **pending** until admin approves.
- **Banned (hard):** Illegal/harmful content; **full-time job** posts (redirect to Jobs); **property rent/sale** (redirect to Rent & Lease).
- **Price:** Optional on all listing types.
- **Images:** Not required for v1; optional upload can be phase 2.

## Categories (seed — combine all three lanes)

**Services / gigs:** Tuition, home repair, moving help, event help, pet care, cleaning, other services.  
**Wanted / ISO:** Electronics, furniture, vehicles, books, miscellaneous wanted.  
**Community:** Lost & found, community alert, giveaway, recommendation, other notices.

_Admin can extend via category table; keep slugs stable for URLs._

## Language

- UI: **English** primary.
- **Tamil** on key headings and CTAs (bilingual-friendly labels).
- Listing body: user language allowed (moderation applies).

## Identity & contact

- **Posting:** Requires **logged-in account** (no anonymous posts).
- **Target:** Single user linked to **Google**, **phone**, and **email** — **implementation phased** (see below).
- **Phone on listing:** **Reveal** button only (rate-limited / logged).

### Auth phasing (engineering)

1. **Launch:** Email + password (or magic link) + verification; posts tied to `user_id`.
2. **Next:** Google OAuth, link to account (email match or explicit connect).
3. **Next:** SMS OTP provider; verify phone; link to same `user_id`.

## Browse & post UX

- **Browse:** Same filter **parity** as Buy & Sell index: search, category, locality, price min/max, sort, pagination.
- **Post (v1):** Minimal fields — title, description, category, location, optional price; **no image**.
- **After submit:** Thank-you page + link to listing (state clearly **pending approval**).

## Discovery & marketing

- **Name:** **OMR Classified Ads**
- **Homepage headline (EN):** “Have something to offer or need? Post in 2 minutes.”
- **Placement:** Homepage section + **footer** + **topic hubs** — **no** new primary item in main nav (v1).
- **90-day channels:** WhatsApp/Telegram **and** Instagram/Facebook.
- **Seeding:** Light (~10–20 legitimate listings). Use web/social **only for research** (demand, copy ideas) — do **not** copy others’ ads without permission.

## Success metrics (initial)

- Listings submitted / approved per week  
- Time to approve  
- Reveal-phone events  
- New registered users  

## Open items (non-blocking)

- Tamil copy deck for all CTAs (translate headline D + buttons).  
- Exact admin URL: module subfolder vs central `/admin/`.  
- Renewal after 10 days: one-click extend vs new post.
