## MyOMR – Centralized Sitemap and .htaccess Management (Shared Hosting)

### Goals
- Single, root-level sitemap index (submit only one URL in Search Console)
- Centralized clean routes in root `.htaccess` for all modules
- Keep module generators to scale features independently

---

### Files
- `/generate-sitemap-index.php` – builds a `<sitemapindex>` that references module sitemaps
- `/robots.txt` – points to the root sitemap index
- `/.htaccess` – root routing rules (events module included; extend as others go live)
- `/omr-local-events/generate-events-sitemap.php` – module sitemap (already implemented)

---

### Conventions
- Root sitemap index URL: `https://myomr.in/sitemap.xml`
- Include one sitemap per module:
  - `https://myomr.in/omr-local-events/sitemap.xml`
  - Future: jobs, directory, news (add to the index when available)
- Robots: declare only the root `Sitemap:`; do not list module sitemaps directly
- Clean URLs live under each module prefix; routes defined centrally in root `.htaccess`

---

### Add a new module (playbook)
1) Create module sitemap generator, e.g., `/omr-local-job-listings/generate-jobs-sitemap.php`
2) Add route in root `.htaccess`:
```
RewriteRule ^omr-local-job-listings/sitemap\.xml$ omr-local-job-listings/generate-jobs-sitemap.php [L]
```
3) Add clean URLs for the module under its prefix (similar to events)
4) Edit `/generate-sitemap-index.php` → append the module sitemap URL
5) Deploy; verify `https://myomr.in/sitemap.xml` lists the new sitemap
6) No robots change necessary (it already points to the index)

---

### Search Console workflow
- Add property for the whole domain (recommended) or folder-level for large modules
- Submit ONLY: `https://myomr.in/sitemap.xml`
- After deploys, request indexing for a few new URLs and monitor coverage

---

### Notes on per-folder `.htaccess`
- Supported in shared hosting; however centralizing in root avoids rule drift
- Keep per-folder `.htaccess` minimal or remove once central routes are verified

---

### QA checklist
- `https://myomr.in/sitemap.xml` loads and lists module sitemaps
- Each module sitemap loads and contains canonical clean URLs
- Robots disallows admin paths; all public paths allowed
- Event routes resolve: `/omr-local-events/event/{slug}`, `/today`, `/weekend`, `/month`, `/category/{slug}`, `/locality/{slug}`, `/venue/{slug}`


