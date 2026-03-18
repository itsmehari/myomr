# OMR Buy & Sell

Local C2C classifieds for Old Mahabalipuram Road. Buy and sell used items: electronics, furniture, vehicles, books, and more.

## Setup

1. Run migration: `dev-tools/migrations/run_2026_03_14_create_omr_buy_sell_tables.sql`
   - Or: `php dev-tools/migrations/run_buy_sell_migration.php` (when DB is available)
2. Ensure `omr-buy-sell/uploads/images/` is writable

## Pages

- `/omr-buy-sell/` — Browse listings
- `/omr-buy-sell/listing/{id}/{slug}` — Detail
- `/omr-buy-sell/post-listing-omr.php` — Post ad
- `/omr-buy-sell/category/{slug}/` — By category
- `/omr-buy-sell/locality/{slug}/` — By locality
- Admin: `/omr-buy-sell/admin/`

## Cron

Schedule daily expiry of listings:

```
0 1 * * * php /path/to/omr-buy-sell/cron-expire-listings.php
```

Or via cPanel cron. For web execution, set `EXPIRE_CRON_KEY` in config and call:
`/omr-buy-sell/cron-expire-listings.php?key=YOUR_SECRET`

## Conventions

- Bootstrap: `includes/bootstrap.php`
- Slug URLs: `listing/{id}/{slug}`
- Canonical base: `https://myomr.in`
