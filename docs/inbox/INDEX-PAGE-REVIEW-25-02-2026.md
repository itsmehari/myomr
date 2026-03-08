# Index Page Review – 25 February 2026

## Summary

Review of the MyOMR.in index page after MyCovai-style upgrade. Several broken links and flaws were found and fixed.

---

## Fixes Applied

### 1. Broken Category Links

**Issue:** Homepage category grid linked to non-existent files.

| Category | Broken URL | Fixed URL |
|----------|------------|-----------|
| Schools | `/omr-listings/schools-list.php` | `/omr-listings/schools.php` |
| Hospitals | `/omr-listings/hospitals-list.php` | `/omr-listings/hospitals.php` |
| Banks | `/omr-listings/banks-list.php` | `/omr-listings/banks.php` |

**File:** `core/homepage-categories.php`

### 2. Hero Search Form Parameter

**Issue:** Form used `name="q"` but the jobs page expects `$_GET['search']`.

**Fix:** Changed `name="q"` to `name="search"`.

**File:** `index.php`

### 3. Job Count Table

**Issue:** `homepage-categories.php` queried `job_listings`, `omr_job_listings`, `jobs` but not `job_postings` (the actual table).

**Fix:** Added `job_postings` as the first table to check for job counts.

**File:** `core/homepage-categories.php`

### 4. Footer Links

| Issue | Fix |
|-------|-----|
| Logo linked to `index.html` | Changed to `index.php` |
| Copyright link `http//www.myomr.in` (missing colon) | Changed to `https://www.myomr.in` |
| Facebook icon linked to Instagram URL | Fixed to Facebook URL |
| Instagram icon linked to `#` | Fixed to Instagram URL |

**File:** `components/footer.php`

### 5. Root-Level Pages

**Issue:** `/contact-my-omr-team.php` and `/about-myomr-omr-community-portal.php` are linked from header but files live in subfolders.

**Fix:** Added `.htaccess` rewrite rules:
- `/contact-my-omr-team.php` → `weblog/contact-my-omr-team.php`
- `/about-myomr-omr-community-portal.php` → `@tocheck/about-myomr-omr-community-portal.php`

**File:** `.htaccess`

### 6. Script Path

**Issue:** `components/myomr-news-bulletin.js` used a relative path.

**Fix:** Changed to absolute path `/components/myomr-news-bulletin.js`.

**File:** `index.php`

---

## Suggested Improvements (Not Yet Applied)

### 1. About Page Location

The About page is in `@tocheck/`, which may be excluded from deployment. If `/about-myomr-omr-community-portal.php` returns 404 on production:

- **Option A:** Copy `@tocheck/about-myomr-omr-community-portal.php` to project root.
- **Option B:** Update `.htaccess` rewrite target if the file is moved (e.g. to `weblog/` or `info/`).

### 2. Footer Subscribe Form

The footer has a separate subscribe form with `action="#"` that does not submit anywhere. Consider either:

- Pointing `action` to `#subscribe` to scroll to the main subscribe block, or
- Pointing to `/core/subscribe.php` with the same behaviour as the main subscribe form.

### 3. Hero Search – Events/Places

The hero search form currently posts to `/omr-local-job-listings/` only. The category dropdown (Jobs, Events, Places) does not route to different pages. Consider:

- Routing by category: jobs → `/omr-local-job-listings/`, events → `/omr-local-events/`, places → `/omr-listings/` (or `/omr-listings/index.php`).

### 4. Subscribe Redirect

`core/subscribe.php` redirects to `thank-you.html` on success and `index.html?error=invalid_email` on error. If you rely on `index.php`:

- Update success redirect to `/thank-you.php` or `/thank-you.html` (depending on what exists).
- Update error redirect to `/?error=invalid_email` or `/index.php?error=invalid_email`.

### 5. Core Subscribe Handler

`core/subscribe.php` may fail when included after output has started (e.g. after `index.php` has sent headers). Consider moving subscribe handling to the top of the page (or a dedicated endpoint) before any HTML output.

---

## Verification Checklist

- [ ] Schools, Hospitals, Banks category links open correct listing pages
- [ ] Hero search with “search” parameter filters jobs on `/omr-local-job-listings/`
- [ ] Footer logo returns to homepage
- [ ] Footer social links open correct Facebook, Twitter, Instagram pages
- [ ] `/contact-my-omr-team.php` loads contact page
- [ ] `/about-myomr-omr-community-portal.php` loads about page
- [ ] News bulletin JS loads correctly
- [ ] Job count appears for Jobs category when `job_postings` has data

---

## File Changes Summary

| File | Changes |
|------|---------|
| `core/homepage-categories.php` | Fixed Schools, Hospitals, Banks URLs; added `job_postings` to job count |
| `index.php` | Search param `q` → `search`; script path made absolute |
| `components/footer.php` | Logo `index.html` → `index.php`; copyright URL; social link fixes |
| `.htaccess` | Rewrite rules for contact and about pages |
