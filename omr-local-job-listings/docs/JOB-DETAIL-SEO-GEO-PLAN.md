# Job Detail Page — User-, SEO- & Geo-Friendly Plan

**Goal:** Make job detail pages human-friendly, SEO-friendly, and geo-friendly (OMR/Chennai). No raw `?id=15` in URLs or in visible content.

---

## What Was Implemented

### 1. **Clean URLs (human-friendly)**

- **Before:** `https://myomr.in/omr-local-job-listings/job-detail-omr.php?id=15`
- **After:** `https://myomr.in/omr-local-job-listings/job/15`

- **`.htaccess`** in `omr-local-job-listings/`: rewrites `job/15` → `job-detail-omr.php?id=15`.
- **301 redirect:** Visiting the old URL (`job-detail-omr.php?id=15`) redirects to the clean URL so search engines and links converge on one canonical URL.
- **Helpers:** `getJobDetailUrl($id)` (full URL for canonical/schema), `getJobDetailPath($id)` (relative `job/15` for internal links). All listing and internal links now use these.

### 2. **SEO**

- **Canonical:** Every job detail page uses the clean URL as canonical.
- **Title:** `Job Title at Company – OMR Chennai | MyOMR` (geo hint).
- **Meta description:** Trimmed job description (155 chars).
- **Keywords:** Job title, location, "jobs in OMR Chennai", "Old Mahabalipuram Road jobs", category.
- **Open Graph / Twitter:** Title, description, canonical URL, `og:site_name` "MyOMR – Jobs in OMR Chennai", `og:locale` en_IN.
- **Structured data (JobPosting):**
  - `url` — canonical job URL.
  - `validThrough` — application deadline or +45 days.
  - `directApply` — true.
  - Existing: title, description, datePosted, employmentType, hiringOrganization (with address), jobLocation (Place + PostalAddress), baseSalary, industry, jobBenefits, qualifications.
- **BreadcrumbList** schema unchanged (Home → Jobs in OMR → Category → Job title).

### 3. **Geo-friendly**

- **Meta:** `geo.region` = IN-TN (Tamil Nadu).
- **JobPosting.jobLocation:** Place with full PostalAddress (streetAddress, addressLocality, addressRegion, postalCode, addressCountry) from `resolveJobPostalAddress()` and OMR locality map (e.g. Thoraipakkam, Perungudi, Sholinganallur, Chennai).
- **Copy:** "OMR Chennai" in title and meta so local search and “jobs near me” can align.

### 4. **User-friendly**

- No visible raw ID in page content; breadcrumb and headings use job title and category.
- Clear hierarchy: breadcrumb → company → H1 (job title) → highlights (location, type, salary) → description → apply CTAs.
- Apply by form or WhatsApp; share (WhatsApp, LinkedIn, Email) use the clean URL.

---

## Files Touched

| File | Change |
|------|--------|
| `omr-local-job-listings/.htaccess` | **New.** Rewrite `job/ID` → job-detail-omr.php?id=ID. |
| `includes/job-functions-omr.php` | `getJobDetailUrl()`, `getJobDetailPath()`. |
| `job-detail-omr.php` | 301 from old URL; canonical = getJobDetailUrl(); title/OG/keywords/geo meta. |
| `includes/seo-helper.php` | JobPosting schema: url, validThrough, directApply. |
| `index.php` | Links use getJobDetailPath() / getJobDetailUrl(). |
| `application-submitted-omr.php` | job-functions include; link uses getJobDetailPath(). |
| `view-applications-omr.php` | Links use getJobDetailPath(). |
| `my-posted-jobs-omr.php` | Links use getJobDetailPath(). |
| `edit-job-omr.php` | Preview link uses getJobDetailPath(). |
| `includes/landing-page-template.php` | job-functions guard; links use getJobDetailPath(). |
| `api/jobs.php` | JSON url and HTML links use getJobDetailUrl/getJobDetailPath. |
| `process-application-omr.php` | Redirect after error uses `job/ID`. |
| `admin/manage-jobs-omr.php` | job-functions include; view link uses getJobDetailPath(). |

---

## Optional Next Steps

- **Slug in URL:** e.g. `/job/15/femtosoft-technologies-internship` (requires slug column or generated slug; 301 from `/job/15` if you want one canonical slug URL).
- **GeoCoordinates:** Add lat/long to `getOmrPostalMap()` and to JobPosting `jobLocation.geo` for richer local SEO.
- **Sitemap:** Ensure `generate-sitemap.php` (or main sitemap) lists job URLs as `https://myomr.in/omr-local-job-listings/job/ID` using `getJobDetailUrl()`.
