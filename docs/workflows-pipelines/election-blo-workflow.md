# Election BLO Data Workflow

- **Last updated:** 12 November 2025  
- **Owner:** Civic Data Team  
- **Applies to:** `omr-election-blo/`, `election-blo-details/`, BLO lookup pages & scripts  
- **Prerequisites:** Latest BLO CSV from Election Commission, data cleaning tools, SQL templates, admin verification checklist

## 1. Overview

- **Purpose:** Keep Booth Level Officer (BLO) contact information accurate for residents searching by locality/polling station.
- **Trigger:** New BLO list released, correction request received, or scheduled election update.
- **Participants:** Data analyst, Admin moderator, QA reviewer, Community communications.

## 2. Flow Diagram

```mermaid
flowchart TD
    A[Receive official BLO CSV/Excel] --> B{File format usable?}
    B -- No --> B1[Clean/convert to CSV<br/>Normalize headers] --> A
    B -- Yes --> C[Run data cleaning script]
    C --> D{Validation pass?}
    D -- No --> D1[Fix missing wards/polling station IDs<br/>Re-run script] --> C
    D -- Yes --> E[Generate SQL inserts/updates]
    E --> F[Import into database (phpMyAdmin)]
    F --> G{Import warnings?}
    G -- Yes --> G1[Resolve duplicates/nulls<br/>Re-import] --> E
    G -- No --> H[Verify via admin/diagnostic tool]
    H --> I{Sample lookups accurate?}
    I -- No --> I1[Adjust locality mapping<br/>Re-run validation] --> H
    I -- Yes --> J[Update front-end BLO lookup page & QA]
    J --> K{QA passes (search, filters)?}
    K -- No --> K1[Fix UI/data binding<br/>Retest] --> J
    K -- Yes --> L[Publish update + notify community]
    L --> M[Document in worklog + monitor feedback]
```

## 3. Step-by-Step

1. **Obtain data**
   - Request latest BLO list (Excel/CSV) from official source.
   - Store raw file in `election-blo-details/` with date stamp.

2. **Clean & normalize**
   - Convert to CSV if needed; ensure columns for AC, ward, polling station, BLO name, phone.
   - Use scripts in `omr-election-blo/` or custom Python to standardize case, remove extra whitespace, validate phone formats.

3. **Generate SQL**
   - Use templates like `omr-election-blo/*.sql` to truncate old data (if required) and insert new entries.
   - Compare with previous dataset to catch major changes.

4. **Import & validate**
   - Run SQL via phpMyAdmin; watch for constraint errors.
   - Use admin diagnostics (`omr-election-blo/…` utilities) to test AC/ward combinations.

5. **Front-end QA**
   - Visit BLO lookup page (`/local-news/find-your-blo-officer-…` or dedicated route).
   - Test by locality, polling station, and BL number.
   - Confirm contact info displays with proper formatting and clickable phone links.

6. **Communication & monitoring**
   - Announce update via blog or community channels if substantial.
   - Document changes in `docs/worklogs/`.
  - Collect resident feedback to flag inaccuracies; patch quickly via admin update.

## 4. Checklists

**Before import**
- [ ] Official CSV received and stored with date.
- [ ] Data cleaned (duplicates removed, phone numbers valid).
- [ ] SQL scripts reviewed; backup taken.

**After import**
- [ ] Sample lookups across key localities verified.
- [ ] Front-end search and filters work on desktop/mobile.
- [ ] Blog/news page referencing BLO details updated if needed.
- [ ] Announcement or release note created.

## 5. Edge Cases & Recovery

- **Missing polling stations:** Flag to election authority; leave placeholder with support contact.
- **Phone number obfuscation:** Some lists may redact digits—document in FAQ and provide alternative contact.
- **Admin mismatch:** If front-end still shows old data, clear cache or check for hard-coded fallbacks.
- **Emergency corrections:** Keep a hotfix SQL script to update individual BLO records without reimporting entire dataset.

## 6. References

- Data files: `election-blo-details/`, `omr-election-blo/*.sql`
- Front-end: `/local-news/find-your-blo-officer-shozhinganallur-electoral-roll-revision.php`
- Documentation: `docs/worklogs/`, `LEARNINGS.md` (BLO learnings), `docs/workflows-pipelines/news-publication-workflow.md` (if announcement article needed)


