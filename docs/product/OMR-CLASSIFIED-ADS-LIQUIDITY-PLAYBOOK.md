# OMR Classified Ads — liquidity playbook (90 days)

**Goal:** More live, approved listings and repeat posters on `/omr-classified-ads/`.

## 1. Launch sequence (week 1)

1. Run DB migrations (see `omr-classified-ads/README.md`).
2. Configure **Google OAuth** + **Twilio SMS** on the server (env vars below) so all sign-in paths work.
3. Post **10–20 seed ads** yourself (real services, wanted, one lost/found) across categories — avoids empty browse.
4. Approve quickly in **Admin →** `/omr-classified-ads/admin/manage-listings-omr.php`.

## 2. Channels (your spec: WhatsApp/Telegram + Instagram/Facebook)

### WhatsApp / Telegram

- **Pinned message:** “Free local ads on MyOMR — services, wanted, community notices: https://myomr.in/omr-classified-ads/”
- **Weekly bump:** “New this week on OMR Classified Ads:” + 3 titles + link.
- **Template (copy-paste):**  
  `Post a free ad on OMR Classified Ads (OMR Chennai) — tuition, repairs, wanted items, lost & found: https://myomr.in/omr-classified-ads/ — takes ~2 min after register.`

### Instagram / Facebook

- **Story:** Screenshot of category grid + sticker link.
- **Post caption:** Lead with “Have something to offer or need?” + guidelines link + browse link.
- **Pin / featured:** Classified Ads post for 2 weeks after launch.

## 3. Content ideas

- **Reels / short video:** “How to post” (register → post → pending → live).
- **FAQ graphic:** “Not for full-time jobs or rent/sale — use Jobs & Rent hubs.”
- **Tamil line in every post:** e.g. `உள்ளூர் விளம்பரங்கள் — இலவசம்`

## 4. Metrics (GA4)

Already fired from the hub:

- `login` (method `Google` | `Phone` | email via standard login — add if desired)
- `sign_up` (method `email`) after email register
- `post_listing_start`, `post_listing_submit`, `search`, `listing_view`, `reveal_phone`

**Weekly review:** approved listings count, time-to-approve, `reveal_phone` count, registrations.

## 5. Moderation SLA

- Aim **&lt; 24h** for pending queue; faster in first month builds trust.

## 6. Ethical seeding

- Use **your own** or **permission-based** copy only — do not scrape other sites’ ads.

---

## Env vars (auth + SMS)

| Variable | Purpose |
|----------|---------|
| `MYOMR_CLASSIFIED_GOOGLE_CLIENT_ID` | Google OAuth client ID |
| `MYOMR_CLASSIFIED_GOOGLE_CLIENT_SECRET` | Google OAuth secret |
| `MYOMR_CLASSIFIED_GOOGLE_REDIRECT_URI` | Optional; default production callback URL |
| `MYOMR_TWILIO_ACCOUNT_SID` | Twilio |
| `MYOMR_TWILIO_AUTH_TOKEN` | Twilio |
| `MYOMR_TWILIO_FROM` | E.164 sender number |
| `MYOMR_CA_OTP_PEPPER` | Long random string — **same value** on all app servers (hashes OTP codes) |

Google **Authorized redirect URI** must match exactly:  
`https://myomr.in/omr-classified-ads/auth/google-callback-omr.php`

On **cPanel**, set env vars in the control panel if available, or in a **non-public** PHP bootstrap that defines `putenv()` before includes (do not commit secrets).
