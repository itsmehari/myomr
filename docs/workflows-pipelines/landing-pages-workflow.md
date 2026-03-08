# Landing & Static Pages Workflow

- **Last updated:** 12 November 2025  
- **Owner:** UX & Content Team  
- **Applies to:** `discover-myomr/`, `info/`, `pentahive/`, `small-businesses/`, marketing landing pages in root (`jobs-in-*.php`, `it-jobs-omr-chennai.php`, etc.)  
- **Prerequisites:** Updated copy deck, design assets, SEO checklist, access to footer/nav components

## 1. Overview

- **Purpose:** Manage updates to static/landing pages that drive awareness, services, and legal compliance.
- **Trigger:** New campaign launch, service change, legal policy update, or periodic refresh.
- **Participants:** Content strategist, Designer, Web producer, QA reviewer, Marketing/Legal reviewer (for policies).

## 2. Flow Diagram

```mermaid
flowchart TD
    A[Receive update request / campaign brief] --> B{Copy & design approved?}
    B -- No --> B1[Iterate on copy/design<br/>Stakeholder review] --> A
    B -- Yes --> C[Update page template (PHP/HTML)]
    C --> D[Insert new assets (images, CSS)]
    D --> E{SEO & analytics aligned?}
    E -- No --> E1[Adjust meta tags, schema, tracking IDs] --> D
    E -- Yes --> F[Run accessibility & responsive QA]
    F --> G{QA pass?}
    G -- No --> G1[Fix layout/content issues<br/>Re-test] --> F
    G -- Yes --> H[Deploy to staging → production]
    H --> I[Update navigation/footer links if needed]
    I --> J[Log change in worklog + notify stakeholders]
```

## 3. Step-by-Step

1. **Brief & planning**
   - Gather goals, target audience, key messaging, CTAs.
   - Draft copy and design mock-ups; confirm with stakeholders.

2. **Implementation**
   - Edit relevant PHP files (e.g., `discover-myomr/*.php`, `pentahive/` pages, root-level landing pages).
   - Update components if nav/footer requires new links (`components/main-nav.php`, `components/footer.php`).
   - Add/modify styles in `/assets/css/` or page-specific CSS as required.

3. **SEO & analytics**
   - Ensure `<title>`, `<meta description>`, canonical URL, OG/Twitter tags aligned.
   - Update structured data (Organization, Breadcrumb) when relevant.
   - Verify analytics snippets (`components/analytics.php`) still present; add campaign tracking if requested.

4. **Quality assurance**
   - Test responsiveness across devices (desktop, tablet, mobile).
   - Check accessibility (skip links, alt text, keyboard nav).
   - Validate forms, CTAs, download links.

5. **Deployment & communication**
   - Move changes to staging; get sign-off.
   - Deploy to production; refresh CDN caches if necessary.
   - Update sitemap for new static pages; inform marketing/SEO.
   - Document in `docs/worklogs/` and notify stakeholders.

## 4. Checklists

**Pre-deploy**
- [ ] Copy/design approved.
- [ ] SEO metadata updated.
- [ ] Analytics/tracking verified.
- [ ] Accessibility checks completed.
- [ ] Footer/nav links updated if new pages added.

**Post-deploy**
- [ ] Page live at intended URL.
- [ ] Sitemap updated (if new page).
- [ ] Internal/external links tested.
- [ ] Announcement or documentation updated.

## 5. Edge Cases & Recovery

- **Campaign rollback:** Keep previous version backup; revert quickly if metrics underperform.
- **Legal/policy updates:** Synchronize across all pages referencing policy; ensure version date visible.
- **Inconsistent styling:** Standardize via shared CSS or component includes; avoid inline styles.
- **Stale nav links:** After removing a page, update nav/footer to prevent 404s.

## 6. References

- Page directories: `discover-myomr/`, `info/`, `pentahive/`, root marketing pages.
- Shared components: `components/main-nav.php`, `components/footer.php`, `components/meta.php`.
- Documentation: `docs/strategy-marketing/` (campaign strategy), `docs/worklogs/`, `LEARNINGS.md`.
- SEO: `docs/analytics-seo/` (canonical guidance, sitemap docs).


