# Learnings

Recorded learnings from MyOMR.in development — patterns, decisions, and gotchas.

---

## WhatsApp community group CTA (March 2026)

### What we did

- Added "Join our WhatsApp group" for connectivity and updates in multiple places.
- Introduced a **single source of truth** for the group link so it can be changed in one place.

### Key decisions

1. **Central constant**  
   The WhatsApp group invite URL is defined in `core/include-path.php` as `MYOMR_WHATSAPP_GROUP_URL`. Any new CTA (hero, article, job, footer, etc.) should use this constant with a fallback for edge cases where include-path wasn’t loaded:
   `defined('MYOMR_WHATSAPP_GROUP_URL') ? MYOMR_WHATSAPP_GROUP_URL : 'https://chat.whatsapp.com/...'`

2. **Where we placed CTAs (and why)**  
   - **Homepage hero:** Next to "Join the Community" — high visibility for new visitors.  
   - **Article detail:** After share bar (compact link) + in existing community section — readers already engaged.  
   - **Job detail:** Sidebar block after "Share This Job" — separate from "Apply via WhatsApp" (employer); clear "community" vs "apply" distinction.  
   - **Footer:** WhatsApp icon in "Follow us" — one consistent place site-wide.

3. **Analytics**  
   Footer’s existing GA4 click tracker matches `wa.me` and `api.whatsapp.com`. The group link is `chat.whatsapp.com`, so "Join group" clicks are not currently sent as `whatsapp_click`. To track them, extend the condition or add a dedicated event.

### Files involved

- `core/include-path.php` — constant.
- `index.php` — hero CTA.
- `local-news/article.php` — share-bar CTA + community section link.
- `omr-local-job-listings/job-detail-omr.php` — sidebar block.
- `components/footer.php` — Follow us icon.
- `assets/css/footer.css` — `.footer-social__link--wa`.

### Takeaway

For any **site-wide link** (e.g. community group, main contact), define it once in `core/` and reference it everywhere so updates are trivial and consistent.

---

## Facebook group CTA (March 2026)

### What we did

- Added "Join our Facebook group" CTAs in high-visibility community touchpoints.
- Followed the same single-source pattern used for WhatsApp group links.

### Key decisions

1. **Central constant**  
   The Facebook group URL is defined in `core/include-path.php` as `MYOMR_FACEBOOK_GROUP_URL`. Any new CTA should use this constant with a fallback for safety:
   `defined('MYOMR_FACEBOOK_GROUP_URL') ? MYOMR_FACEBOOK_GROUP_URL : 'https://www.facebook.com/groups/416854920508620'`

2. **Where we placed CTAs (and why)**  
   - **Homepage hero:** Added next to existing community and WhatsApp CTAs for maximum visibility.  
   - **Article detail:** Added compact link next to the share-bar WhatsApp CTA line.  
   - **Job detail:** Added dedicated sidebar block after WhatsApp community block.  
   - **Footer:** Added dedicated Facebook group icon/link in "Follow us" without removing existing social links.

### Files involved

- `core/include-path.php` — `MYOMR_FACEBOOK_GROUP_URL`.
- `index.php` — hero "Join Facebook Group" CTA.
- `local-news/article.php` — compact Facebook group CTA near share bar.
- `omr-local-job-listings/job-detail-omr.php` — Facebook group sidebar block.
- `components/footer.php` — Facebook group icon/link in Follow us.
- `assets/css/footer.css` — `.footer-social__link--fb-group`.

### Takeaway

All site-wide community links (WhatsApp, Facebook group, and future channels) should live in `core/include-path.php` and be consumed via constants with fallbacks.
