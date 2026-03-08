# OMR Classifieds – Pre-Feasibility Analysis

> **Status:** Pre-Feasibility / Planning  
> **Last Updated:** 25 February 2026  
> **Owner:** Product / PM  
> **Design Reference:** Newspaper Classifieds Mart layout (standard listings + premium “Classifieds Special” section)

---

## Executive Summary

This document outlines the pre-feasibility analysis for introducing **OMR Classifieds** to MyOMR.in, inspired by traditional newspaper classifieds. It covers pages design, features, user interaction, posting flows, pricing, and varieties of classifieds, plus technical, operational, and strategic planning considerations. The analysis aligns with the existing MyOMR.in stack (PHP procedural, MySQL, Bootstrap 5, cPanel) and project structure.

**Recommendation:** Proceed to detailed PRD and technical design phase once stakeholders approve scope and priorities.

---

## 1. Pages Design

### 1.1 Layout Model (Based on Reference Design)

| Zone | Description | Digital Adaptation |
|------|-------------|--------------------|
| **Main Content (Left / Wider)** | Standard text-based classifieds by category | Primary listing area; responsive grid/list |
| **Special/Premium (Right / Narrower)** | Larger, image-rich display ads | “Featured Classifieds” sidebar or top banner strip |
| **Header** | “CLASSIFIEDS MART” + advertise CTA | “OMR Classifieds” + “Post Your Ad” / “To Advertise” CTA |
| **Category Headers** | Bold blue/white dividers | Bootstrap cards or section headers; Poppins font |

### 1.2 Proposed Page Structure

| Page | Purpose | Max Width | Components |
|------|---------|-----------|------------|
| **Classifieds Hub** | Main listing by category; featured strip | 1280px | Nav, breadcrumbs, category filters, listing grid, featured sidebar |
| **Category Listing** | e.g. `/omr-classifieds/jobs`, `/omr-classifieds/rentals` | 1280px | Category intro, filters, paginated list (9 per page) |
| **Ad Detail** | Single ad view | 1280px | Title, description, images, contact (Call/WhatsApp/Email), map if location given |
| **Post Ad** | Submission form | 1280px | Step or single form; category selector, content, contact, optional images |
| **Post Success** | Confirmation + next steps | 1280px | Thank-you message, moderation notice, link back to listing |

### 1.3 Design Conventions (per .cursorrules)

- **Font:** Poppins primary  
- **Max container width:** 1280px  
- **Grid:** Bootstrap 5 responsive  
- **Meta:** `og:image`, `twitter:card`, canonical URLs  
- **Reuse:** `components/meta.php`, `components/main-nav.php`, `components/footer.php`, `components/analytics.php`

### 1.4 Folder Placement

Align with existing patterns:

- **Option A:** `/omr-classifieds/` (mirrors `/omr-local-job-listings/`, `/omr-local-events/`)  
- **Option B:** `/listings/` (extend existing listings folder; add classifieds-specific subpages)

**Recommendation:** Use `/omr-classifieds/` for clarity and SEO consistency with other OMR modules.

---

## 2. Features Design Planning and Functionality

### 2.1 Core Features (MVP)

| Feature | Description | Priority |
|---------|-------------|----------|
| **Category & subcategory system** | Hierarchical categories (e.g. Personal, Situation Vacant, Services, Property) | P0 |
| **Text-only listings** | Short ad content, title, contact, optional price | P0 |
| **Image support (basic)** | 1–3 images per ad for premium/display ads | P0 |
| **Search & filter** | By category, keyword, locality, price range, date | P0 |
| **Contact options** | Phone, Email, WhatsApp (link generation) | P0 |
| **Moderation queue** | Admin review before publish (status: pending → active) | P0 |
| **Expiry & renewal** | e.g. 30-day default; optional renewal workflow | P1 |

### 2.2 Secondary Features (Post-MVP)

| Feature | Description | Priority |
|---------|-------------|----------|
| **Featured / premium placement** | Highlighted ads in “Classifieds Special” zone | P1 |
| **Saved searches / alerts** | Email when new ads match criteria (if user auth exists) | P2 |
| **Map integration** | Location pins for property/service ads (Google Maps) | P1 |
| **Report / flag** | User report for spam/abuse | P1 |
| **Analytics** | GA events for view, click, share | P1 |

### 2.3 Admin Capabilities

- Manage categories and subcategories  
- Approve/reject/edit/delete ads  
- Featured toggle, expiry extension  
- View moderation stats, spam ratio  
- Basic reporting (ads per category, per period)

---

## 3. User Interaction

### 3.1 Browsing

- **Entry:** Nav link “Classifieds” → `/omr-classifieds/`  
- **Navigation:** Category tabs/filters; optional locality filter  
- **Listing view:** Card or compact list; title, excerpt, price, date, thumbnail  
- **Detail:** Click → full ad with contact buttons (Call, WhatsApp, Email)

### 3.2 Contacting Advertisers

- **Primary:** Phone (tel: link), WhatsApp (wa.me with pre-filled message)  
- **Secondary:** Email (mailto: or masked for spam protection)  
- **Future:** In-app contact form if user accounts are added

### 3.3 Posting Ads (Advertisers)

- **Entry:** “Post Your Ad” CTA on hub and listing pages  
- **Flow:** Category selection → content form → optional images → contact → preview → submit  
- **Post-submit:** “Ad submitted. You will be notified once approved.”

---

## 4. Posting Flows

### 4.1 Submission Flow (High-Level)

```
User clicks "Post Your Ad"
  → Select category (and subcategory if applicable)
  → Enter title, description, contact (phone, email), optional price
  → Upload images (0–3 for basic; more for premium)
  → Preview
  → Submit
  → Backend validates, saves with status=pending
  → Redirect to success page
  → Moderator reviews in admin
  → Approve → status=active, ad goes live
  → Reject → notify user (email if available)
```

### 4.2 Alignment with Existing Workflows

- **Free Ads Workflow:** `docs/workflows-pipelines/free-ads-workflow.md` describes a similar flow; OMR Classifieds can follow the same pattern (submit → validate → pending → moderate → publish).  
- **Job Portal Workflow:** Job posting flow (`post-job-omr.php` → process → moderation) provides a reference for form structure and admin tools.

### 4.3 Technical Flow

| Step | Component | Notes |
|------|-----------|-------|
| Form | `post-classified-omr.php` or `post-ad-omr.php` | Category, title, description, contact, price, images |
| Handler | `process-classified-omr.php` | Validation, sanitization, INSERT with status=pending |
| Admin | `/admin/manage-classifieds-omr.php` | Review queue, approve/reject, edit |
| Listing | `omr-classifieds/index.php`, `category.php`, `detail.php` | Query `omr_classifieds` WHERE status=active |

---

## 5. Pricing Mechanisms

### 5.1 Tiered Model (Inspired by Reference Design)

| Tier | Description | Price | Placement |
|------|-------------|-------|-----------|
| **Basic / Free** | Text-only; standard listing | ₹0 | Main listing grid |
| **Featured** | 1–3 images; highlighted placement | ₹X per ad per period | “Classifieds Special” zone |
| **Display** | Custom layout, logo, premium placement | Contact for quote | Top/banner positions |

### 5.2 Duration-Based Options

- Free: 15–30 days  
- Featured: 15 / 30 / 60 days (configurable)  
- Renewal: Same price or discount for repeat advertisers

### 5.3 Payment Integration (Future)

- Use same pattern as `discover-myomr/pricing.php` (3-tier plans + CTA)  
- Integrate Razorpay/Instamojo or similar for paid ads  
- **MVP:** Start with free-only; add paid tiers in Phase 2

### 5.4 Alignment with Existing Pricing

- `discover-myomr/pricing.php` shows Daily/Monthly/Yearly/Lifetime plans  
- Classifieds pricing can be separate or linked (e.g. “Lifetime members get 1 free featured ad/month”)

---

## 6. Varieties of Classifieds and Design Fit

### 6.1 Category Mapping

| Category (Reference) | OMR Classifieds | Design Fit |
|----------------------|-----------------|------------|
| **Personal** (Change of Name, Thanksgiving) | Announcements, lost & found | Plain text block; compact list item |
| **Situation Vacant** | Job listings | **Note:** Already have `/omr-local-job-listings/`. Either integrate or keep separate; recommend separate for now, with cross-links |
| **Old Age Home, Paintings, Pest Control** | Services | Card with title, brief description, contact; optional image |
| **Educational** | Tuitions, courses | Card with location, subject, contact; similar to existing listings |
| **Finance** | Loans, investments | Text block; compliance disclaimers |
| **Property** | Rent, Sell | Structured card; price, locality, bedrooms; link to `/listings/rent-house-omr.php`, `sell-property-omr.php` for consistency |

### 6.2 Layout by Ad Type

| Type | Layout | Components |
|------|--------|------------|
| **Text-only** | Compact row or small card | Title, 2–3 line excerpt, contact, date |
| **With image** | Card with thumbnail | Image, title, excerpt, price, contact |
| **Featured** | Larger card or banner | Image, logo, full description snippet, CTA |
| **Display** | Custom block | As per advertiser; admin-approved HTML/creative |

### 6.3 Overlap with Existing Listings

- **Jobs:** `/omr-local-job-listings/` + `/listings/post-job-omr.php`  
- **Property:** `/listings/rent-house-omr.php`, `sell-property-omr.php`, `rent-land-omr.php`  
- **Business:** `/listings/post-business-ad-omr.php`  
- **Tuitions:** `/listings/tutions-classes-courses-training-centers-in-omr-chennai.php`

**Recommendation:**  
- Classifieds = broad catch-all for ads that don’t fit the structured job/property/business flows  
- Add cross-links: “Post a job” → job portal; “Rent/Sell property” → property forms  
- Optionally surface “Recent Classifieds” on listings index for cross-discovery

---

## 7. Other Planning Considerations (Pre-Feasibility)

### 7.1 Database

| Table | Purpose |
|------|---------|
| `omr_classifieds` | Ad records: title, description, category_id, contact_phone, contact_email, price, images_json, status, locality, expiry_date, created_at, etc. |
| `omr_classified_categories` | Hierarchical categories (id, name, slug, parent_id) |
| `omr_classified_images` (optional) | Separate image table if not storing paths in JSON |
| `omr_classified_pricing_plans` (future) | Plan definitions for paid tiers |

**Update:** `docs/data-backend/DATABASE_STRUCTURE.md`, `DATABASE_INDEX.md` when schema is finalised.

### 7.2 User Authentication

- **Current:** No public user accounts; admin uses `$_SESSION['admin_logged_in']`  
- **Classifieds MVP:** Anonymous submission with name, phone, email (no login)  
- **Future:** Optional registration for “My Ads” dashboard, renewal, saved searches

### 7.3 Admin Dashboard

- New section under `/admin/`  
- `manage-classifieds-omr.php`: list, filter (pending/active/expired), approve, reject, edit, delete  
- Category management (CRUD)  
- Basic stats: ads per category, pending count

### 7.4 URL Structure

- Base: `https://myomr.in/omr-classifieds/`  
- Category: `https://myomr.in/omr-classifieds/category/{slug}` or `category.php?slug={slug}`  
- Detail: `https://myomr.in/omr-classifieds/{slug}` or `detail.php?id={id}`  
- Post: `https://myomr.in/omr-classifieds/post-ad-omr.php`  
- `.htaccess` rules for clean URLs (consistent with existing mod_rewrite usage)

### 7.5 SEO

- JSON-LD: `Offer`, `JobPosting` (for job-type ads), `LocalBusiness` where relevant  
- `core/article-seo-meta.php` pattern for meta tags  
- Canonical URLs, sitemap inclusion  
- `components/analytics.php` for GA events

### 7.6 Security

- Sanitize all inputs (`htmlspecialchars()` or equivalent)  
- Prepared statements for all DB queries  
- File upload restrictions: type (jpg, png, webp), size (e.g. 2MB), folder  
- Rate limiting / CAPTCHA for submission form (reduce spam)  
- Admin pages check `$_SESSION['admin_logged_in']`

### 7.7 Image Handling

- Upload to `/uploads/classifieds/` or similar  
- Resize/optimise server-side (or use existing image pipeline)  
- Store paths in DB (or `images_json`)

### 7.8 Content Moderation

- Policy document: prohibited items, language, contact rules  
- Process: Review queue → approve/reject with reason  
- Abuse: Report flow, block repeat offenders (IP/phone)

### 7.9 Documentation Updates

- **Product:** PRD in `docs/product-prd/`  
- **Workflow:** Extend or create `docs/workflows-pipelines/classifieds-workflow.md`  
- **Database:** `docs/data-backend/DATABASE_STRUCTURE.md`, `DATABASE_INDEX.md`  
- **Architecture:** `docs/ARCHITECTURE.md` (new module)  
- **Changelog:** `CHANGELOG.md` on release

### 7.10 Dependencies

- **PHP 7.4+**, **MySQL 5.7+**, **Bootstrap 5** (existing)  
- **Optional:** Payment gateway for paid ads  
- **Optional:** Email service for moderation notifications (Brevo/Mailchimp if available)

### 7.11 Risks and Mitigations

| Risk | Mitigation |
|------|------------|
| Spam / low quality | Moderation, CAPTCHA, rate limiting |
| Duplicate ads | Deduplication rules; optional phone/email checks |
| Legal/compliance | Disclaimers for finance/real estate; policy doc |
| Scope creep | MVP = free text + basic image; defer paid, maps, alerts |
| Overlap with jobs/property | Clear positioning; cross-links; consider consolidation later |

### 7.12 Success Metrics (Proposed)

- Submissions per week  
- Approval rate, time-to-approve  
- Page views on classifieds hub and detail pages  
- Contact button clicks (Call, WhatsApp)  
- Bounce rate, time on page

---

## 8. Next Steps

1. **Stakeholder sign-off** on scope (MVP vs Phase 2)  
2. **PRD** for OMR Classifieds (detailed requirements, acceptance criteria)  
3. **Database schema** design and migration script  
4. **UI mockups** for hub, category, detail, post form  
5. **Workflow doc** `classifieds-workflow.md`  
6. **Dev implementation** – Phase 1: free ads, moderation, basic SEO  
7. **QA** – forms, moderation, listing, SEO checks  
8. **Launch** – soft launch, then promotion  
9. **Phase 2** – paid tiers, featured placement, map integration

---

## References

- `docs/workflows-pipelines/free-ads-workflow.md` – Free ads flow  
- `docs/workflows-pipelines/job-portal-workflow.md` – Job posting flow  
- `docs/product-prd/PRD-Events-Growth-MyOMR.md` – Events PRD patterns  
- `docs/README.md` – Documentation structure  
- `docs/ARCHITECTURE.md` – Project architecture  
- `docs/data-backend/DATABASE_INDEX.md` – Database docs  
- `.cursorrules` – Tech stack, conventions, security  
- `discover-myomr/pricing.php` – Pricing page pattern
