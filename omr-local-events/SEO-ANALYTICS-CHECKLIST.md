## Events SEO & Analytics Checklist (MyOMR)

### Implemented
- Canonical URLs on listing and detail pages
- Open Graph & Twitter meta on listing and detail
- Event JSON-LD on listing (multiple) and detail
- Breadcrumb JSON-LD on listing
- Events sitemap generator (`generate-events-sitemap.php`)
- GA event tracking via `assets/events-analytics.js`:
  - filters used, view click, map click, ticket click
  - submission start/attempt/success
  - share clicks (WhatsApp/Twitter/Facebook)
  - calendar add + ICS download
- UTM-tagged share links on detail page

### Recommended Next
- Add breadcrumbs JSON-LD on detail page (optional)
- Homepage cross-promo: include `components/top-featured-events-widget.php`
- Clean URLs `/events/{slug}` with `.htaccess` rules
- Create dedicated Events XML in root sitemap index (optional)
- Add GA4 custom dimensions for event category/locality (optional)


