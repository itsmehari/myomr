# Coworking Spaces Workflow

- **Last updated:** 12 November 2025  
- **Owner:** Partnerships & Content Team  
- **Applies to:** `/omr-coworking-spaces/` directory, pricing/plan pages, enquiry forms  
- **Prerequisites:** Partner intake sheet, pricing updates, access to module PHP files and assets, CRM for lead capture

## 1. Overview

- **Purpose:** Onboard and maintain coworking partners along OMR, ensuring accurate pricing tiers, amenities, and contact options.
- **Trigger:** New partner signs up, pricing changes, or periodic audit of listings.
- **Participants:** Partner manager, Content editor, Web producer, QA reviewer, Sales/CRM owner.

## 2. Flow Diagram

```mermaid
flowchart TD
    A[Partner outreach / signup] --> B{Partner approved?}
    B -- No --> B1[Record feedback<br/>Revisit later] --> END1[Closed]
    B -- Yes --> C[Collect assets: pricing, photos, amenities]
    C --> D{Assets complete?}
    D -- No --> D1[Request missing info<br/>Follow up with partner] --> C
    D -- Yes --> E[Update /omr-coworking-spaces/ content]
    E --> F[Add partner card + pricing tiers]
    F --> G{Lead form & CTAs working?}
    G -- No --> G1[Fix form action / mail target<br/>Retest] --> F
    G -- Yes --> H[Publish + QA]
    H --> I{QA pass (mobile, SEO, structured data)?}
    I -- No --> I1[Adjust layout/meta<br/>Repeat QA] --> H
    I -- Yes --> J[Notify partner & sales team]
    J --> K[Monitor leads + update worklog]
```

## 3. Step-by-Step

1. **Partner intake**
   - Gather company name, location, amenities, desk/office pricing, photos.
   - Create/update partner entry in master sheet; tag status (new, pending assets, live).

2. **Content update**
   - Duplicate existing card pattern in `/omr-coworking-spaces/*.php` (e.g., `index.php`, `pricing.php`).
   - Update hero sections, plan comparisons, testimonials if provided.
   - Ensure CTAs (contact button, WhatsApp, phone) point to correct partner or MyOMR lead form.

3. **Lead capture & routing**
   - Verify forms submit to CRM/email (`/omr-coworking-spaces/contact.php` or embedded form).
   - Update hidden fields with partner name for source tracking.
   - Test confirmation messages and autoresponders if configured.

4. **Quality assurance**
   - Mobile/tablet responsiveness (grid, pricing tables).
   - SEO metadata: unique title/description, structured data if used.
   - Check images for compression and alt text.
   - Confirm internal links (Discover pages, blog articles) still relevant.

5. **Launch & notify**
   - Push updates to staging → production.
   - Inform partner with live links; share analytics expectations.
   - Notify sales/CRM team to monitor incoming leads.

6. **Ongoing maintenance**
   - Schedule quarterly pricing review.
   - Log changes in `docs/worklogs/`.
   - Update `content-update-pipeline.md` (once available) if process evolves.

## 4. Checklists

**Before publish**
- [ ] Partner contract/details confirmed.
- [ ] Pricing tiers updated and currency consistent.
- [ ] CTAs point to correct email/phone/form.
- [ ] Images optimized and credited.

**After publish**
- [ ] Page renders correctly on desktop/tablet/mobile.
- [ ] Lead form submissions tested (test enquiry reaches inbox/CRM).
- [ ] Sitemap regenerated if new standalone page added.
- [ ] Worklog entry created.

## 5. Edge Cases & Recovery

- **Price change mid-cycle:** Update pricing table immediately; notify partner when published.
- **Partner pause/cancel:** Comment out card or set visibility flag; keep entry in sheet as inactive.
- **Broken form:** Revert to previous version or use fallback email link until resolved.
- **Overlapping offers:** Coordinate with marketing to avoid duplicate promotions.

## 6. References

- Module files: `/omr-coworking-spaces/index.php`, `pricing.php`, `contact.php`, related includes.
- Assets: check `/assets/css/` and module-specific CSS for shared styles.
- Documentation: `docs/content-projects/` coworking summaries, `docs/worklogs/`, upcoming `content-update-pipeline.md`.
- Marketing tie-ins: `docs/strategy-marketing/` (landing page strategies, outreach kits).


