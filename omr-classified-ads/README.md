# OMR Classified Ads

General local classifieds (services, wanted, community notices) parallel to **Buy & Sell** (used goods).

## Setup

1. **Migrations (core + auth tables):** from repo root, with DB credentials as in `core/omr-connect.php` (or override `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME` for remote runs):

   ```powershell
   $env:DB_HOST='myomr.in'
   php dev-tools/migrations/run_classified_ads_migration.php
   ```

   Applies [2026_03_20_create_omr_classified_ads.sql](../dev-tools/migrations/2026_03_20_create_omr_classified_ads.sql) and [2026_03_21_classified_ads_auth_linking.sql](../dev-tools/migrations/2026_03_21_classified_ads_auth_linking.sql).

2. Ensure `omr-classified-ads/uploads/rate-limit/` is writable (post rate limits).

## Auth: Google + phone (phase 1b)

Set these **on the server** (cPanel env, `.env` loader, or `putenv` in a private bootstrap — **do not commit secrets**):

| Env | Purpose |
|-----|---------|
| `MYOMR_CLASSIFIED_GOOGLE_CLIENT_ID` | Google OAuth |
| `MYOMR_CLASSIFIED_GOOGLE_CLIENT_SECRET` | Google OAuth |
| `MYOMR_CLASSIFIED_GOOGLE_REDIRECT_URI` | Optional; default `https://myomr.in/omr-classified-ads/auth/google-callback-omr.php` |
| `MYOMR_TWILIO_ACCOUNT_SID` | SMS OTP |
| `MYOMR_TWILIO_AUTH_TOKEN` | SMS OTP |
| `MYOMR_TWILIO_FROM` | Twilio sender (E.164) |
| `MYOMR_CA_OTP_PEPPER` | Long random string (hashing OTP codes; use same value on all workers) |

**Google Cloud Console:** Authorized redirect URI must match the callback URL exactly.

**Endpoints:**

- Start Google: `/omr-classified-ads/auth/google-start-omr.php`
- Callback: `/omr-classified-ads/auth/google-callback-omr.php`
- Phone login: `/omr-classified-ads/phone-login-omr.php`
- Account (link phone): `/omr-classified-ads/account-omr.php`

Without Twilio, OTP SMS will fail (in `DEVELOPMENT_MODE`, the code is logged to the PHP error log for testing).

## URLs

- `/omr-classified-ads/` — Browse
- `/omr-classified-ads/listing/{id}/{slug}` — Detail (clean URL via root `.htaccess`)
- `/omr-classified-ads/post-listing-omr.php` — Post (verified email required)
- `/omr-classified-ads/register-omr.php` / `login-omr.php` / `logout-omr.php`
- `/omr-classified-ads/guidelines.php`
- Admin: `/omr-classified-ads/admin/manage-listings-omr.php` (also listed under **Admin dashboard → OMR Classified Ads**)

## Email verification

- **Development / auto-verify:** `DEVELOPMENT_MODE` true ([core/env.php](../core/env.php)) or `MYOMR_CA_AUTO_VERIFY=1`.
- **Production:** `verify-email-omr.php?token=…` (configure outbound mail on host).

## Cron (10-day expiry after approval)

```text
0 2 * * * php /path/to/omr-classified-ads/cron-expire-listings.php
```

Or web: `EXPIRE_CRON_KEY` + `cron-expire-listings.php?key=SECRET`.

## Liquidity (marketing)

See [docs/product/OMR-CLASSIFIED-ADS-LIQUIDITY-PLAYBOOK.md](../docs/product/OMR-CLASSIFIED-ADS-LIQUIDITY-PLAYBOOK.md).

## Conventions

- Bootstrap: `includes/bootstrap.php`
- Slug URLs: `listing/{id}/{slug}`
- Canonical base: `https://myomr.in`
- Product spec: [docs/product/OMR-CLASSIFIED-ADS-SPEC.md](../docs/product/OMR-CLASSIFIED-ADS-SPEC.md)
