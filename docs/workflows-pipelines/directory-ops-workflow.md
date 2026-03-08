# Directory Listings Workflow

- **Last updated:** 12 November 2025  
- **Owner:** Directory Ops + Admin Team  
- **Applies to:** `/omr-listings/`, `/listings/`, admin directory modules (`manage-banks.php`, `manage-schools.php`, etc.)  
- **Prerequisites:** Access to source spreadsheets/lead forms, phpMyAdmin permissions, admin login, sitemap generation rights

## 1. Overview

- **Purpose:** Keep all public directory pages (banks, schools, hospitals, parks, industries, ATMs, government offices, etc.) up to date with verified information.
- **Trigger:** New data batch (CSV or form submissions), correction request, or scheduled audit.
- **Participants:** Data curator, Admin moderator, QA reviewer.

## 2. Flow Diagram

```mermaid
flowchart TD
    A[Collect source data] --> B{Data clean & complete?}
    B -- No --> B1[Normalize addresses/contacts<br/>Resolve duplicates] --> A
    B -- Yes --> C[Prepare SQL or admin import sheet]
    C --> D[Update database via phpMyAdmin / scripts]
    D --> E{Warnings/errors?}
    E -- Yes --> E1[Fix constraints (slug, ENUM)<br/>Re-run import] --> D
    E -- No --> F[Admin verification (manage-*.php)]
    F --> G{Listings accurate in admin?}
    G -- No --> G1[Edit entries / fix typos<br/>Re-check data] --> F
    G -- Yes --> H[Front-end QA on /omr-listings/]
    H --> I{Appears in filters & maps?}
    I -- No --> I1[Adjust locality/category<br/>Regenerate cache] --> H
    I -- Yes --> J[Regenerate sitemap & update worklog]
```

## 3. Step-by-Step

1. **Data acquisition**
   - Pull latest files from CRM, Google Sheets, or intake forms.
   - De-duplicate by business name + address; flag updates vs new entries.

2. **Preparation**
   - Use SQL templates or import scripts (`dev-tools/sql/…`) to format records for `omr_listings_*` tables.
   - For small edits, plan to use admin interfaces (`/admin/manage-*.php`) instead of SQL.

3. **Database update**
   - Run inserts/updates via phpMyAdmin; respect foreign keys (e.g., locality references).
   - For large batches, wrap statements in transaction to rollback if constraints fail.
   - Record executed scripts in a dated folder inside `dev-tools/sql/archives/` (if practiced).

4. **Admin moderation**
   - Visit appropriate admin page (`manage-banks.php`, `manage-schools.php`, etc.).
   - Spot-check new entries; ensure statuses reflect desired visibility (active/inactive).
   - Update metadata (geo coordinates, descriptions, tags) as needed.

5. **Front-end verification**
   - Browse `/omr-listings/` and deep pages to ensure cards display correctly.
   - Test search & filter by locality/category.
   - Confirm contact links (tel:, mailto:, map) work.

6. **Sitemap & documentation**
   - Re-run sitemap generator if new or removed listings impact SEO (`analytics-seo/SITEMAP-COMPLETE-LIST.md` and module-specific sitemap).
   - Log changes in `docs/worklogs/`.
   - Update `docs/workflows-pipelines/content-update-pipeline.md` (once drafted) if cross-module change.

## 4. Checklists

**Pre-import**
- [ ] Source data validated and deduped.
- [ ] SQL/import scripts reviewed.
- [ ] Backup/export of affected tables taken (optional but recommended).

**During update**
- [ ] Inserts/updates executed without constraint errors.
- [ ] New entries receive correct slug and locality.

**Post-update**
- [ ] Admin pages reflect new listings with accurate status.
- [ ] Public pages show correct data and images.
- [ ] Sitemap updated; Search Console pinged if major change.
- [ ] Worklog entry created.

## 5. Edge Cases & Recovery

- **Slug collisions:** Append locality or unique suffix; update references in admin.
- **Stale cache/CDN:** If front-end still shows old data, clear cache or revalidate CDN.
- **Incorrect geo coordinates:** Use map tool to confirm lat/long before publishing; fix errors via admin edit.
- **Mass corrections:** For large mistakes, restore from database backup or revert transaction.

## 6. References

- Admin tools: `/admin/manage-banks.php`, `/admin/manage-schools.php`, `/admin/manage-hospitals.php`, etc.
- Public listings: `/omr-listings/*`, `/listings/*` (forms and landing pages).
- SQL templates: `dev-tools/sql/` (look for directory-specific scripts).
- Documentation: `docs/product-prd/PRD-Directory-Platform-MyOMR.md`, `docs/analytics-seo/SITEMAP-COMPLETE-LIST.md`, `docs/worklogs/`.


