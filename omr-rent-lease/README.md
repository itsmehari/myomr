# OMR Rent & Lease

Property listings for rent and lease in OMR Chennai (houses, apartments, land).

**Model:** Same structure as omr-hostels-pgs and omr-local-job-listings.

## Setup

1. Run migration: `dev-tools/migrations/run_2026_03_10_create_rent_lease_tables.sql` in phpMyAdmin
2. Visit `/omr-rent-lease/`

## Files

- `index.php` - Main listings
- `add-property-omr.php` - (Phase 3) Post property form
- `property-detail-omr.php` - (Phase 2) Single property view
- `includes/property-functions.php` - DB helpers
- `includes/error-reporting.php`

See `docs/inbox/RENT-LEASE-OVERHAUL-PLAN.md` for full plan.
