## Google Search Console – MyOMR (Sitemap and Coverage)

### Submit sitemap
1) Open Search Console → Domain: myomr.in (recommended) or URL prefix
2) Sitemaps → Add a new sitemap: `https://myomr.in/sitemap.xml`
3) Submit. You should see status "Success" after fetch.

### Request indexing (kickstart)
1) URL Inspection → test a few clean URLs and Request Indexing:
   - `https://myomr.in/omr-local-events/`
   - `https://myomr.in/omr-local-events/weekend`
   - `https://myomr.in/omr-local-events/category/{one-slug}`
   - `https://myomr.in/omr-local-events/event/{one-slug}`

### Coverage checks
- Inspect `sitemap.xml` index → ensure it includes `/omr-local-events/sitemap.xml`
- Events sitemap should show hubs and event details using clean URLs.

### Notes
- Keep only the root index in GSC; don't submit module sitemaps separately.
- Re-crawl speed-up: share roundup/news with links to hubs; internal links help discovery.


