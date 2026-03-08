# MyOMR – Project Learnings (Consolidated Daily Log)

This is the consolidated lessons learned document for the MyOMR project. Record daily learnings, patterns, and best practices here.

> **📚 Documentation Hub:** For comprehensive project documentation, see [`docs/README.md`](docs/README.md) - This is the main navigation hub for all documentation, organized into 8 category folders with clear structure and AI-friendly navigation.

---

## 📋 Table of Contents

### [Core Engineering & Module Learnings](#-core-engineering--module-learnings)

1. [Engineering Process & Flow Design](#1-engineering-process--flow-design)
2. [PHP Error Handling & Observability](#2-php-error-handling--observability)
3. [MySQLi & SQL Hygiene](#3-mysqli--sql-hygiene)
4. [Security Basics](#4-security-basics-every-feature)
5. [URL/Paths & Routing](#5-urlpaths--routing)
6. [UX & Design System](#6-ux--design-system)
7. [SEO & Analytics](#7-seo--analytics)
8. [Articles/News Learnings](#8-articlesnews-learnings)
9. [Jobs Module Learnings](#9-jobs-module-learnings)
10. [Events Module Learnings](#10-events-module-learnings)
11. [Content & Admin Ops](#11-content--admin-ops)
12. [DevOps & Environments](#12-devops--environments)
13. [Documentation & Checklists](#13-documentation--checklists)

### [Quick Reference](#-quick-reference)

- [Canonical Code Patterns](#canonical-code-patterns)
- [Common Patterns Index](#common-patterns-index)

### [Daily Learnings by Topic](#-daily-learnings-by-topic)

- [SEO & Structured Data](#seo--structured-data)
- [Admin & Navigation](#admin--navigation)
- [UI/UX & Modals](#uiux--modals)
- [Database & Data Management](#database--data-management)
- [Documentation & Workflows](#documentation--workflows)

### [Daily Learnings Log (Chronological)](#-daily-learnings-log-chronological)

---

## 📚 Core Engineering & Module Learnings

### 1) Engineering Process & Flow Design

- Always start with a concise requirements brief and a WBS; add a “Flow Mapping & Edge Cases” phase up front.
- Maintain an implementation tracker and mark items complete as soon as they’re done.
- Before coding, list happy paths and failure modes (validation, auth, network, DB, permissions, empty states) and define expected user messages.

### 2) PHP Error Handling & Observability

- Centralize dev error reporting; expose readable in-page blocks in dev and always log to file.
- Use custom exception/err handlers plus a shutdown handler for fatals; capture stack traces in logs.
- When returning generic messages to users, still log exact errors for ops.

### 3) MySQLi & SQL Hygiene

- Match `bind_param` signatures precisely; type order must equal placeholders.
- Avoid mixing spread args with positional ones; build `$bindValues` first then unpack.
- Handle nullable FKs with `NULLIF(?,0)` or bind `NULL` when appropriate to prevent constraint errors.
- Add helpful indexes for frequent filters (dates, status, category, featured) and order by indexed columns when possible.

### 4) Security Basics (Every Feature)

- Validate and sanitize inputs on both client and server.
- Use CSRF tokens for all mutating actions; add honeypot + basic rate limiting for public forms.
- For file uploads, validate MIME using `finfo` + size limits + dedicated upload directories with restrictive permissions.
- Escape output with `htmlspecialchars`; never trust DB strings in HTML.

### 5) URL/Paths & Routing

- Prefer absolute, site-rooted hrefs in nav to avoid relative path breakage across folders.
- Keep a plan for clean URLs via `.htaccess` rewrites; align canonical tags with the final routes.
- Use consistent slugging; enforce uniqueness and collision handling (`base`, `base-2`, …).
- **Apache 2.4:** Use `Require all denied` in FilesMatch blocks, not `Order allow,deny` + `Deny from all`.

### 6) UX & Design System

- Establish a shared style system (glassmorphism + unified components) and reuse across modules.
- Add modern, accessible forms: clear labels, helper text, real-time validation, focused error states.
- Ensure responsive grids for two-column layouts; treat mobile single-column as first-class.

### 7) SEO & Analytics

- Treat discoverability as a flow step: canonical, meta description, OG/Twitter, JSON-LD.
- **meta.php pattern:** Set `$page_title`, `$canonical_url`, `$og_*` before `include meta.php`; never hardcode `<title>` or meta after; always include meta.php inside `<head>`.
- Generate sitemaps for new modules; resubmit in Search Console after launches.
- Add GA events that reflect business funnels (filter use, view, share, ticket click, submit start/success).
- Use UTM on share links to attribute traffic sources.

### 8) Articles/News Learnings

- Prefer DB-driven articles with a router template (`article.php`) for clean URLs and centralized SEO.
- When inserting complex HTML into SQL, beware of quoting—simplify HTML or escape consistently.
- Build a sitemap generator for articles; verify canonical URLs align with routed paths.

#### 8.1) Conditional Schema Generation for Article Types

**Problem:** Need to add article-specific structured data (Person, SportsEvent, FAQPage) only for certain article types (sports articles) without affecting other articles.

**Solution:** Multi-layer conditional system with file inclusion check, data detection check, and content matching check.

**Implementation Pattern:**

```php
// Layer 1: File inclusion check (in article.php)
$is_sports_article = (
    strtolower($article_category) === 'sports' ||
    strpos(strtolower($article['title']), 'sport') !== false ||
    strpos(strtolower($article['title']), 'kabaddi') !== false ||
    strpos(strtolower($article['tags'] ?? ''), 'sport') !== false
);

if ($is_sports_article):
    require_once '../local-news/article-sports-seo-enhancement.php';
endif;

// Layer 2: Schema generation check (in article-sports-seo-enhancement.php)
// Person schema: Only if athlete name detected AND article is about specific sport
if (!empty($athlete_name) && $is_kabaddi_article):
    // Person schema added
endif;

// SportsEvent schema: Only if event name matches AND sport matches
if (!empty($event_name) && stripos($event_name, 'Asian Youth Games') !== false && $is_kabaddi_article):
    // SportsEvent schema added
endif;

// FAQPage schema: Only if athlete name matches specific person
if (!empty($athlete_name) && stripos($athlete_name, 'Karthika') !== false && $is_kabaddi_article):
    // FAQPage schema added
endif;
```

**Why This Matters:**

- Prevents invalid structured data (schemas added only when relevant data exists)
- Maintains SEO quality (no empty or incorrect schemas)
- Safe for all article types (non-sports articles have zero impact)
- Graceful fallback (no errors if conditions don't match)
- Scalable pattern (can add more article types easily)

**Safety Guarantees:**

- Non-sports articles: File not included → Zero impact
- General sports articles: File included but no schemas match → Minimal impact
- Specific sports articles: File included AND schemas match → Enhanced SEO

**Lesson:**
- Use multiple conditional layers for article-specific enhancements
- Always check data existence before adding schemas
- Maintain backward compatibility with existing articles
- Document safety guarantees for each enhancement

#### 8.2) Article Visibility Issues - Database Status Fields

**Problem:** Article added to database but not showing on homepage/news highlights page, even after hard refresh.

**Root Cause:** Article visibility depends on multiple database fields:
1. `status` must be `'published'` (not 'draft' or NULL)
2. `published_date` must be current date or in the past (not future)
3. `is_featured` should be `1` for homepage prominence
4. `slug NOT LIKE '%-tamil'` (Tamil versions excluded from homepage)

**Solution:** Create diagnostic SQL to check and fix all visibility conditions:

```sql
-- Diagnostic: Check article status
SELECT id, title, slug, status, published_date, is_featured, image_path,
    CASE 
        WHEN status IS NULL OR status = 'draft' THEN '❌ NOT PUBLISHED'
        WHEN published_date IS NULL THEN '❌ NO DATE'
        WHEN published_date > NOW() THEN '❌ DATE IN FUTURE'
        WHEN is_featured = 0 THEN '⚠️ NOT FEATURED'
        ELSE '✅ OK'
    END as diagnosis
FROM articles 
WHERE slug = 'article-slug';

-- Fix: Update all visibility fields
UPDATE articles
SET 
    status = 'published',
    published_date = '2025-01-10 00:00:00', -- Current date or past
    is_featured = 1,
    updated_at = NOW()
WHERE slug = 'article-slug';
```

**Why This Matters:**

- Article display depends on multiple database fields
- Hard refresh won't fix database issues
- Need diagnostic tools to identify the problem
- Common issues: status='draft', published_date in future, is_featured=0

**Lesson:**
- Always verify database fields after inserting articles
- Create diagnostic SQL for troubleshooting
- Document visibility requirements clearly
- Check status, date, featured flag when articles don't appear

#### 8.3) Sports Content SEO - Multiple Structured Data Schemas

**Problem:** Need comprehensive SEO for sports articles with athlete profiles, event details, and FAQ content.

**Solution:** Implement multiple JSON-LD schemas conditionally:
1. **NewsArticle schema** (base - always present)
2. **Person schema** (athlete profile - when athlete name detected)
3. **SportsEvent schema** (tournament details - when event detected)
4. **FAQPage schema** (questions/answers - when specific person detected)
5. **BreadcrumbList schema** (navigation - always present)

**Implementation Pattern:**

```php
// Person Schema - Athlete Profile
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "R Karthika",
  "jobTitle": "Kabaddi Player, Vice-Captain",
  "worksFor": {
    "@type": "SportsTeam",
    "name": "Indian U-18 Girls Kabaddi Team",
    "sport": "Kabaddi"
  },
  "birthPlace": { "@type": "City", "name": "Chennai" },
  "homeLocation": { "@type": "Place", "name": "Kannagi Nagar, Chennai" }
}

// SportsEvent Schema - Tournament Details
{
  "@context": "https://schema.org",
  "@type": "SportsEvent",
  "name": "2025 Asian Youth Games - Kabaddi (Girls U-18)",
  "startDate": "2025-01-08",
  "endDate": "2025-01-15",
  "location": { "@type": "Place", "name": "Bahrain" },
  "sport": "Kabaddi",
  "competitor": [
    {
      "@type": "SportsTeam",
      "name": "Indian U-18 Girls Kabaddi Team",
      "position": 1,
      "award": "Gold Medal"
    }
  ]
}

// FAQPage Schema - Questions & Answers
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Who is R Karthika?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "R Karthika is an Indian kabaddi player..."
      }
    }
  ]
}
```

**Why This Matters:**

- Person schema enables Knowledge Graph eligibility
- SportsEvent schema enables event-rich snippets
- FAQPage schema enables featured snippet eligibility
- Multiple schemas increase rich snippet opportunities
- Better understanding for search engines

**SEO Benefits:**

- Featured snippet eligibility (FAQ schema)
- Knowledge Graph panel (Person schema)
- Event-rich snippets (SportsEvent schema)
- Image thumbnails (NewsArticle schema)
- Breadcrumb navigation (BreadcrumbList schema)

**Lesson:**
- Use multiple structured data schemas for comprehensive SEO
- Conditionally add schemas based on content type and data availability
- Person schema for athlete/person profiles
- SportsEvent schema for tournament/sports event coverage
- FAQPage schema for question-answer content
- Always validate schemas with Rich Results Test before deployment

### 9) Jobs Module Learnings

- Create a dedicated module namespace with `includes/`, `assets/`, `admin/` and helper functions.
- For selects like categories, implement fallbacks (inactive/all) and dev diagnostics when empty.
- Add GA custom event tracking for job flows; include structured data (JobPosting) where relevant.
- Normalize JobPosting JSON-LD with helper utilities that infer mailing address, salary units, and `validThrough` from raw job data so Search Console validates every listing.

### 10) Events Module Learnings

- Separate `event_submissions` (pending) from `event_listings` (published) with a clear moderation path.
- Implement end-to-end affordances on detail pages: share (UTM), map, tickets, Add to Calendar, ICS.
- Use JSON-LD Event on listing and detail; ensure dates/locations are complete.

### 11) Content & Admin Ops

- Provide admin queues with clear statuses; wire Approve/Reject endpoints and emit diagnostics on failures.
- Auto-approve can be used temporarily to unblock UAT, but gate behind a config.
- Keep a cross-promo widget (featured items) for homepage or section pages.

### 12) DevOps & Environments

- On shared hosting, some PHP functions/operators differ; avoid unpacking + positional arg combos that older engines mishandle.
- If using opcode cache, touch/redeploy changed files when debugging; logs are authoritative.
- For remote DB development, document SSH tunnel requirements and local scripts that run on the target host when needed.

### 13) Documentation & Checklists

- Maintain: WBS, Implementation Tracker, SEO/Analytics checklist, Learnings, Deployment/Human Testing guides.
- Put code snippets and common patterns (bind_param examples, sanitizers, error panel) in docs for reuse.

#### 13.1) Learnings File Organization & Rich Markdown

**Problem:** Learnings file became large and difficult to navigate, lacking visual hierarchy and quick reference sections.

**Solution:** Reorganized `LEARNINGS.md` and created `LEARNINGS-REORGANIZED.md` with rich markdown features.

**Organization Structure:**
1. **Table of Contents** - Comprehensive navigation with links to all sections
2. **Quick Reference** - Canonical code patterns and common patterns index
3. **Daily Learnings by Topic** - Grouped learnings (SEO, Admin, UI/UX, Database, Documentation)
4. **Daily Learnings Log (Chronological)** - Historical entries preserved
5. **Search & Quick Find** - Quick links to common searches

**Rich Markdown Features:**
- Header badges (Status, Last Updated, Total Sections)
- Collapsible Table of Contents using `<details>` tag
- Enhanced visual hierarchy with emoji headers (1️⃣, 2️⃣, 3️⃣, etc.)
- Callout boxes for key principles and warnings (`> **💡 Key Principle:**`)
- Rich tables for quick reference and comparisons
- Enhanced code blocks with syntax highlighting
- Status indicators (✅, ⚠️, 🔒, 📚)
- Implementation checklists
- Centered sections for important information

**Why This Matters:**
- Better navigation and discoverability
- Quick reference for common patterns
- Topic-based grouping for easy access
- Visual hierarchy improves readability
- Rich markdown features enhance user experience
- Collapsible sections reduce visual clutter

**Lesson:**
- Organize learnings by both topic and chronology
- Use rich markdown features for better readability
- Create quick reference sections for common patterns
- Use tables for comparison and quick lookups
- Add search & quick find for discoverability
- Update organization structure as content grows

---

## 🎯 Quick Reference

### Canonical Code Patterns

- Safe input sanitizer:
  - trim → strip_tags → remove control chars → collapse whitespace.
- Safe prepared statements:
  - Build `$params` array → append paging → `bind_param($types, ...$params)`.
- Approve flow with nullable FKs:
  - Use `NULLIF(?,0)` and bind zero when unknown; DB stores NULL.

### Common Patterns Index

- **Conditional Schema Generation** → [Section 8.1](#81-conditional-schema-generation-for-article-types)
- **Article Visibility Debugging** → [Section 8.2](#82-article-visibility-issues---database-status-fields)
- **Sports Content SEO** → [Section 8.3](#83-sports-content-seo---multiple-structured-data-schemas)
- **Learnings File Organization** → [Section 13.1](#131-learnings-file-organization--rich-markdown)
- **Modal Scroll Detection** → [Daily Learnings - UI/UX & Modals](#uiux--modals)
- **Navigation Centralization** → [Daily Learnings - Admin & Navigation](#admin--navigation)
- **JobPosting Schema** → [Daily Learnings - Database & Data Management](#database--data-management)
- **Sitemap Patterns** → [Daily Learnings - SEO & Structured Data](#seo--structured-data)

---

## 📅 Daily Learnings by Topic

> **Note:** Detailed chronological entries are preserved below. This section groups learnings by topic for quick reference.

### SEO & Structured Data

#### Multiple Schema Types for Comprehensive SEO

- Use NewsArticle, Person, SportsEvent, FAQPage, BreadcrumbList schemas together
- Conditionally add schemas based on content type
- Validate with Rich Results Test before deployment

#### Conditional Schema Generation

- Multi-layer conditional system (file inclusion → data detection → content matching)
- Safe for all article types (zero impact on non-matching articles)
- Scalable pattern for future article types

#### Article Visibility Debugging

- Check `status`, `published_date`, `is_featured` fields
- Create diagnostic SQL for troubleshooting
- Hard refresh won't fix database issues

#### Sitemap Best Practices

- Use sitemap index pattern for multi-module sites
- Exclude admin files, processing files, test files
- Use canonical clean URLs only (no duplicates)
- Filter out alternate language versions
- Conduct systematic project-wide audits

#### Full SEO Fix Plan Implementation (Mar 2026) — COMPLETED

**Context:** Bing Webmaster Tools flagged redundant `<title>` tags and other SEO issues. A project-wide audit produced a 4-phase fix plan; all items have been implemented.

**What was fixed:**

| Phase | Scope | Summary |
|-------|-------|---------|
| **1** | Critical | Added `$twitter_card` to meta.php; removed duplicate titles from 10+ pages; moved meta.php inside `<head>` for 8 old articles; fixed wrong/dead canonical URLs |
| **2** | High | Fixed 9 listing detail pages (school, hospital, bank, etc.) — set `$page_title` from DB, mapped `$canonical_url`, removed duplicates; migrated standalone pages to meta.php; fixed H1 hierarchy (duplicate H1 → H2, article H2 → H1); added descriptive `alt` to content images |
| **3** | Medium | Cleaned .htaccess (single RewriteEngine, merged mod_expires, HSTS, ErrorDocument 500); created `generate-sitemap-index.php` and `sitemap-pages.xml`; added NewsArticle JSON-LD to 22 articles; added rel=prev/next to it-companies & schools; added pagination (9/page) to schools |
| **4** | Low | Standardized Bootstrap 5.3.3 in head-resources.php; added defer to modal.js; migrated 7 discover-myomr pages to meta.php; added ItemList schema to schools, atms, restaurants; fixed subdirectory .htaccess (Order allow,deny → Require all denied; removed deprecated X-XSS-Protection); fixed schools breadcrumb URL to clean `/schools` |

**Key patterns:**

- **Single source of truth:** All pages set `$page_title`, `$canonical_url`, `$og_*` before `include meta.php`; no hardcoded `<title>` after include.
- **meta.php placement:** Must be inside `<head>`, never before `<!DOCTYPE html>`.
- **Breadcrumbs:** Use clean canonical URLs (e.g. `/schools`) in breadcrumb schema, not physical paths.
- **Apache 2.4:** Use `Require all denied` instead of `Order allow,deny` + `Deny from all` in FilesMatch.

**Reference:** See `docs/analytics-seo/` and the SEO Fix Plan for full details.

### Admin & Navigation

#### Centralized Navigation Metadata

- Single source of truth for navigation (`admin/config/navigation.php`)
- Reusable across sidebar, dashboard, and future admin surfaces
- Support both modules and child actions (quick actions)

#### Admin UI Enhancements

- Sticky headers, filters, bulk actions
- Responsive design with mobile drawer
- Manual QA still essential for routing/auth validation

### UI/UX & Modals

#### Scroll Velocity Detection

- Track scroll velocity in real-time using `requestAnimationFrame`
- Don't show modals during active scrolling
- Different delays for desktop vs mobile

#### Cross-Browser CSS Compatibility

- Use `opacity/visibility` instead of `display` for transitions
- Add vendor prefixes for transform animations
- Test specifically on mobile Safari

#### Modal Accessibility

- Always provide visible close button
- Support Escape key for keyboard users
- Use proper ARIA dialog attributes
- Lock body scroll on mobile Safari

### Database & Data Management

#### JobPosting Schema Data Quality

- Centralize schema generation in helper functions
- Derive postal data from locality strings
- Backfill `validThrough` safely with default values

#### Batch Import Workflows

- Run owner + listing inserts together
- Activate listings post-import with UPDATE query
- Use transactions for data integrity

#### Article Status Management

- Multiple fields affect visibility (`status`, `published_date`, `is_featured`)
- Create diagnostic SQL for troubleshooting
- Document visibility requirements clearly

### Documentation & Workflows

#### Documentation Organization

- Category-based structure (8 categories: analytics-seo, data-backend, content-projects, etc.)
- Complete file inventory in README
- AI-friendly structure with explicit navigation guides

#### Learnings File Organization

- Table of Contents for comprehensive navigation
- Quick Reference section for common patterns
- Daily Learnings by Topic (grouped by subject)
- Rich markdown features for better readability (Section 13.1)
- Collapsible sections, tables, callout boxes
- Status indicators and checklists

#### Workflow Playbooks

- Feature-specific workflow documents
- Diagram both happy paths and recovery routes
- Cross-link for discoverability

---

## 📅 Daily Learnings Log (Chronological)

> **Detailed chronological entries preserved for historical reference. For topic-based quick reference, see [Daily Learnings by Topic](#-daily-learnings-by-topic) above.**

### March 2026 - Full SEO Fix Plan Implementation (COMPLETED)

**Trigger:** Bing Webmaster Tools flagged redundant `<title>` tags on multiple pages (homepage, government-offices, industries, schools, it-companies, events, etc.).

**Actions taken:** Implemented full 4-phase SEO fix plan across 80+ files:
- **Phase 1:** meta.php enhancements, duplicate title removal, meta.php moved inside `<head>` for 8 old articles, canonical URL fixes
- **Phase 2:** Listing detail pages meta vars, H1 hierarchy fixes, alt text, migration to meta.php
- **Phase 3:** .htaccess cleanup, sitemap index generator, NewsArticle schema, pagination + rel=prev/next
- **Phase 4:** Bootstrap 5.3.3 standardization, discover-myomr migration, ItemList schema, subdirectory .htaccess (Apache 2.4), breadcrumb URL fix

**Outcome:** All duplicate title/canonical/meta issues addressed; single source of truth via meta.php; Bing/Google compliance improved. See [Full SEO Fix Plan Implementation](#full-seo-fix-plan-implementation-mar-2026--completed) in Daily Learnings by Topic.

### November 15, 2025 - R Karthika Article SEO & Rich Snippets + Documentation Reorganization

**Project:** MyOMR News Article System & Documentation  
**Session Focus:** R Karthika kabaddi article creation, comprehensive SEO implementation, sports-specific structured data, and learnings file reorganization

**Key Learnings:**

- Multi-layer conditional schema generation for article types (Section 8.1)
- Article visibility debugging - database status fields (Section 8.2)
- Sports content SEO with multiple structured data schemas (Section 8.3)
- Documentation organization best practices - rich markdown features for better navigation

**Work Completed:**

1. **R Karthika Article:**
   - Created comprehensive 1800+ word article
   - Implemented 5 structured data schemas (NewsArticle, Person, SportsEvent, FAQPage, BreadcrumbList)
   - Added sports-specific SEO enhancements (conditional, safe for all articles)
   - Created comprehensive SEO documentation and ranking strategy

2. **Documentation Reorganization:**
   - Reorganized `LEARNINGS.md` with improved structure:
     - Added comprehensive Table of Contents
     - Created Quick Reference section with Common Patterns Index
     - Added Daily Learnings by Topic section (grouped by topic)
     - Enhanced Search & Quick Find section
   - Created `LEARNINGS-REORGANIZED.md` with rich markdown features:
     - Header badges (Status, Last Updated, Total Sections)
     - Collapsible Table of Contents
     - Enhanced visual hierarchy with emoji headers
     - Callout boxes for key principles
     - Rich tables for quick reference
     - Status indicators and checklists

**Files Created/Modified:**
- **New:** 11 files (PHP, documentation, SQL tools, learnings)
- **Modified:** 3 files (article.php, SQL file, LEARNINGS.md)

See detailed worklog: [`docs/worklogs/worklog-15-11-2025.md`](docs/worklogs/worklog-15-11-2025.md)

---

### November 12, 2025 - Admin Navigation & Dashboard Revamp

**Project:** MyOMR Admin Experience  
**Session Focus:** Centralised navigation data, UX refresh for Hostels & PGs admin, dashboard command centre, manual QA

#### 🎯 Key Lessons Learned

##### 1. **Centralise Navigation Metadata for Reuse**

**Problem:** Sidebar, dashboard, and future admin surfaces each hard-coded their own menus, leading to drift and duplicated maintenance.

**Solution:** Created `admin/config/navigation.php` to house section metadata, module paths, icons, quick-action links, and tags. Sidebar and dashboard now consume this single source (via `admin/modules.php` flattening) so any new module is declared once.

**Lesson:**

- Keep navigation configuration in one structured registry.
- Expose both high-level modules and child actions (e.g., “Add Event”) so different UIs can render them contextually.
- Provide tags and section keys up front to unlock search/filter without extra wiring.
- When legacy code expects old structures, adapt centrally (flattened array) rather than duplicating data.

**Implementation Pattern:**

```php
// admin/config/navigation.php
return [
  [
    'key' => 'dashboard_content',
    'label' => 'Dashboard & Content',
    'icon' => 'fa-gauge-high',
    'modules' => [
      [
        'key' => 'hostels_pgs',
        'name' => 'Hostels & PGs',
        'path' => '/omr-hostels-pgs/admin/manage-properties.php',
        'icon' => 'fa-building-user',
        'tags' => ['hostel', 'pg', 'property'],
        'actions' => [
          ['label' => 'Add Event', 'path' => '/admin/events/events-add.php']
        ]
      ],
      // ...
    ]
  ]
];
```

##### 2. **Enhance Admin UI Without Breaking Existing Flows**

**Problem:** Hostels & PGs manage page was table-only; admins lacked filters, bulk actions, responsive layout, and status tools, making curation slow.

**Solution:** Rebuilt `manage-properties.php` with sticky stat header, multi-filter toolbar, bulk-status form, responsive density toggle, tooltips, and action pills while keeping the underlying approval endpoints intact. Added `bulk-update-properties.php` for status updates keyed by selection.

**Lesson:**

- Wrap UI enhancements around existing data flows; only introduce new endpoints when workflow demands (bulk status update).
- Surface slugs, statuses, and featured markers directly in the grid to support admin QA.
- Provide keyboard-accessible controls (aria-expanded, tooltips, focus states) when modernising UI.
- Add helper JS for filters/density toggles but keep server-rendered fallback state (table markup still accessible).

**Implementation Pattern:**

```php
// Bulk status handler
$ids = array_filter(array_map('intval', explode(',', $_POST['ids'] ?? '')));
$status = $allowedActions[$_POST['action'] ?? ''] ?? null;

if ($status && $ids) {
    $idList = implode(',', $ids);
    $stmt = $conn->prepare("UPDATE hostels_pgs SET status = ? WHERE id IN ($idList)");
    $stmt->bind_param('s', $status);
    $stmt->execute();
}
```

##### 3. **Manual QA Still Catches Routing & Auth Nuances**

**Problem:** After refactors it was unclear whether every dashboard card or quick action resolved correctly within their respective modules (hostels, jobs, directories, IT tools).

**Solution:** Performed a manual pass through each link exposed by the dashboard and sidebar, noting expected redirects (e.g., modules enforcing their own login) and confirming file existence.

**Lesson:**

- Even with shared config, manually exercising each route post-change confirms auth guards, redirects, and module boundaries.
- Document expected behaviours (e.g., jobs admin requires module login) so future QA doesn’t confuse intended redirects for failures.
- Use the shared registry to generate checklists for smoke testing before deployment.

##### 4. **Document Work Immediately to Preserve Context**

**Problem:** Without same-day documentation, rationale for navigation refactors and UI changes becomes fuzzy, making future handoffs harder.

**Solution:** Logged the day’s changes in `docs/worklogs/worklog-12-11-2025.md`, capturing files touched, rationale, and remaining action items.

**Lesson:**

- Tie technical changes to written worklogs the same day to keep institutional memory fresh.
- Reference shared config files (e.g., `admin/config/navigation.php`) in the log so future maintainers know where to adjust.
- Worklogs complement LEARNINGS.md by preserving actionable history, while this file captures distilled patterns.

### November 12, 2025 - Workflow Playbooks & Documentation Ops

**Project:** MyOMR Documentation & Process Library  
**Session Focus:** Mapping end-to-end userflows for every major feature, introducing failure recovery branches, and aligning docs infrastructure.

#### 🎯 Key Lessons Learned

##### 1. **Feature Workflows Deserve Dedicated Playbooks**

**Problem:** Processes for directories, coworking partners, free ads, BLO data, landing pages, and documentation upkeep lived in tribal knowledge, causing inconsistent execution.

**Solution:** Created a `docs/workflows-pipelines/` category and authored detailed guides (with Mermaid success/failure diagrams) for each major module.

**Lesson:**

- When a product area has repeatable steps, codify them as a workflow so new teammates and AI systems can execute confidently.
- Use consistent templates (overview → steps → checklists → edge cases → references) to make playbooks scannable.

##### 2. **Diagram Both Happy Paths and Recovery Routes**

**Problem:** Text-only docs hid decision points; teams lacked clarity on what to do when validation fails or data imports warn.

**Solution:** Embedded Mermaid flowcharts in each workflow showing approval/rejection loops, error handling, and handoff points.

**Lesson:**

- Visualising failure branches highlights where guardrails are needed (e.g., SQL warnings, QA failures, spam reports).
- Diagrams accelerate onboarding by showing how to recover when something breaks.

##### 3. **Documentation Index Must Evolve with New Categories**

**Problem:** After adding workflows, main documentation index still referenced only the original eight categories, confusing navigation.

**Solution:** Updated `docs/README.md` and `.cursorrules` to include the new category, refreshed counts, and listed the key workflow files.

**Lesson:**

- Any structural change to documentation requires updating global indexes, stats, and AI hints so discovery stays reliable.

##### 4. **Cross-Linking Keeps Processes Discoverable**

**Problem:** Workflow docs can become siloed if they aren’t referenced elsewhere.

**Solution:** Added references from worklogs, LEARNINGS, and module-specific docs so each playbook is discoverable from relevant contexts.

**Lesson:**

- Cross-references act as backlinks; whenever you create a new process doc, link it from PRDs, implementation summaries, or admin guides to keep the web of knowledge tight.


### November 10, 2025 - JobPosting Schema Data Quality Fix

**Project:** MyOMR Job Listings  
**Session Focus:** Enriching structured data to satisfy Google JobPosting requirements

#### 🎯 Key Lessons Learned

##### 1. **Centralize Schema Generation**
**Problem:** Individual job pages hand-crafted JSON-LD, leading to missing fields (`addressLocality`, `addressRegion`, `postalCode`, `validThrough`) flagged by Search Console.  
**Solution:** Extended `includes/seo-helper.php` with `generateJobPostingSchema()` plus helper functions to sanitize text, resolve OMR postal codes, infer deadlines, and parse salary ranges.  
**Lesson:** Keep schema logic centralized so every template reuses the same validated output.

##### 2. **Derive Postal Data from Locality Strings**
**Problem:** Database only stored free-form `location`, so structured data lacked granular address fields.  
**Solution:** Built an OMR locality map that matches substrings like `Navalur`, `Siruseri`, `Kelambakkam`, etc., returning standardized locality, state, and PIN codes.  
**Lesson:** When address data is inconsistent, use a curated lookup map tuned to the service area to auto-populate schema-required values.

##### 3. **Backfill `validThrough` Safely**
**Problem:** Many job postings lack a deadline, triggering warnings.  
**Solution:** Helper calculates `validThrough` from `application_deadline` when present, otherwise adds +45 days to `created_at`.  
**Lesson:** Define an explicit expiry fallback to avoid blank values while ensuring listings don’t appear perpetually open.

##### 4. **Keep Listings and Detail Views Consistent**
**Problem:** Only detail pages exposed JSON-LD; grid cards still emitted partial microdata.  
**Solution:** Injected hidden microdata (`itemprop="jobLocation"`, employer address, salary quantitative value) for each card using the same helpers as JSON-LD.  
**Lesson:** Reuse helper outputs across detail and listing templates to prevent schema drift.

#### 🔧 Technical Patterns
- `resolveJobPostalAddress()` → Returns `streetAddress`, `addressLocality`, `addressRegion`, `postalCode`, `addressCountry`.
- `parseSalaryRangeForSchema()` → Detects pay period keywords (`month`, `annum`) and extracts min/max numeric values.
- `generateJobPostingSchema($job)` → Produces complete JSON-LD with `industry`, `jobBenefits`, `qualifications`, and nested monetary amounts compliant with Google Jobs.
- Listing cards wrap helper results in `<meta itemprop>` blocks to preserve structured data even without JavaScript.

#### ✅ Success Metrics
- Search Console warnings (`addressLocality`, `addressCountry`, `streetAddress`, `addressRegion`, `postalCode`, `validThrough`) resolved for the 13 affected jobs.
- Detail pages and listing cards now emit matching structured data, simplifying QA.
- Future jobs automatically inherit complete schema with no manual edits required.

### November 10, 2025 - Hostels & PGs Batch Import + Detail Page Debugging

**Project:** MyOMR Hostels & PGs  
**Session Focus:** Manual batch import, status activation, schema helper fixes, and routing hardening

#### 🎯 Key Lessons Learned

##### 1. **Run Owner + Listing Inserts Together**
**Problem:** Importing `hostels_pgs` rows without matching `property_owners` broke FK constraints and left listings orphaned.  
**Solution:** Created paired scripts (`hostel-owner-batch01-manual.sql`, `hostel-listings-batch01-manual.sql`) and documented execution order (owners → listings → status activation).  
**Lesson:** Always seed parent tables first; ship batch scripts in the order they must be executed and include an activation step if the module hides `status='pending'`.

##### 2. **Activate Listings Post-Import**
**Problem:** Frontend pulls only `status='active'` rows, so imported properties remained invisible.  
**Solution:** Added `UPDATE hostels_pgs SET status='active' WHERE status='pending'` after inserts.  
**Lesson:** Mirror job portal behaviour—ensure data scripts explicitly set display flags for newly seeded content to make QA obvious.

##### 3. **Centralize Schema Helpers on Detail Pages**
**Problem:** `property-detail.php` called `generatePropertySchema()` without loading the helper file, triggering fatal errors.  
**Solution:** Required `includes/seo-helper.php` alongside other includes.  
**Lesson:** Whenever templates call helper functions, double-check includes; consider creating a single loader to avoid future misses.

##### 4. **Use Absolute Paths for Cross-Page Links**
**Problem:** Relative links like `property-detail.php?id=5` broke when clicked from `/omr-hostels-pgs/page/2`, producing incorrect URLs and endless spinners.  
**Solution:** Updated buttons to `/omr-hostels-pgs/property-detail.php?id=…` and `/omr-hostels-pgs/index.php?...`.  
**Lesson:** For modules served from subfolders, prefer root-relative URLs to keep pagination and nested routes stable.

#### 🔧 Technical Patterns
- SQL batches document execution order and include activation queries for display.
- Detail page includes explicitly load `seo-helper.php`.
- Listing cards and related widgets now link via `/omr-hostels-pgs/...` paths to avoid pagination bleed.

#### ✅ Success Metrics
- `/omr-hostels-pgs/` now lists 25+ properties immediately after import.
- Property detail pages render without fatal errors or infinite spinners.
- Related property cards navigate correctly from any pagination page.

---

### November 7, 2025 - Canonical URL & Redirect Standardization

**Project:** MyOMR SEO Hygiene Improvements  
**Session Focus:** Canonical tags, URL helpers, HTTPS/WWW redirects, deployment checklist

#### 🎯 Key Lessons Learned

##### 1. **Centralized Canonical URL Generation**

**Problem:** Multiple pages had hardcoded or missing canonical tags, causing duplicate URLs in Google Search Console.

**Solution:** Created `core/url-helpers.php` with `get_canonical_url()` and used it inside `components/meta.php`, allowing any page to override `$canonical_url` when needed.

**Lesson:**

- Keep URL logic in one helper so every page stays consistent.
- Default to HTTPS, non-www, no query string unless explicitly required.
- Shared components (`meta.php`) should fail-safe to canonical helpers.
- Per-page overrides are still easy (`$canonical_url = 'https://myomr.in/...';`).

**Implementation Pattern:**

```php
if (!function_exists('get_canonical_url')) {
    require_once __DIR__ . '/../core/url-helpers.php';
}
$canonical_url = isset($canonical_url) ? $canonical_url : get_canonical_url();
```

##### 2. **Server-Level Redirect Enforcement**

**Problem:** Google indexed HTTP and www variants which diluted ranking.

**Solution:** Updated root `.htaccess` to force HTTPS and strip `www` before any other rules execute.

**Lesson:**

- Place redirect rules at top of `.htaccess`.
- Force HTTPS first, then strip `www`.
- Keep rules simple; avoid duplicate `RewriteEngine On` blocks.
- Retest sitemap routes and clean URLs after modifying redirects.

**Implementation Pattern:**

```apache
RewriteCond %{HTTPS} !=on
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
RewriteRule ^(.*)$ https://%1/$1 [R=301,L]
```

##### 3. **Page-Level Canonical Overrides**

**Problem:** Contact page with query parameters needed a single canonical target.

**Solution:** Set `$canonical_url` in `contact-my-omr-team.php` before including `meta.php` so all parameter variants resolve to one URL.

**Lesson:**

- Always normalize pages that rely on query strings.
- Setting the variable once prevents duplicate parameter-based canonicals.
- Works beautifully alongside shared helper.

**Implementation Pattern:**

```php
<?php
$canonical_url = 'https://myomr.in/contact-my-omr-team.php';
?>
<?php include 'components/meta.php'; ?>
```

##### 4. **Deployment Checklist Discipline**

**Problem:** Needed clarity on which files changed to avoid incomplete production uploads.

**Solution:** Created `DEPLOYMENT-INSTRUCTIONS.md` listing new/modified files plus testing steps.

**Lesson:**

- Document new helper additions so ops remembers to upload them.
- Distinguish between files that run automatically vs. scripts needing execution.
- Provide post-deploy validation (redirect tests, canonical checks).
- Keep docs in repo for repeatability.

**Implementation Pattern:**

```markdown
### Step 1: Upload New File

- [ ] core/url-helpers.php

### Step 2: Upload Modified Files

...
```

#### 📝 Best Practices Established

1. **Centralize URL normalization** to remove duplication and mistakes.
2. **Enforce HTTPS and non-www** at the server boundary for every request.
3. **Explicit canonical overrides** for pages that accept query strings.
4. **Ship with deployment notes** so production stays in sync.
5. **Verify via Search Console list** to confirm all affected URLs are covered.

#### 🔧 Technical Patterns

- **Helper-based canonical:** `get_canonical_url()` strips query strings, trailing slashes, `index.php`.
- **Redirect order:** HTTPS rewrite ➜ www rewrite ➜ rest of rules.
- **Contact page override:** `$canonical_url` variable + shared meta component.

#### 🚨 Common Pitfalls to Avoid

1. ❌ Leaving canonical tags blank in custom templates — *Resolved Mar 2026: all discover-myomr pages now use meta.php.*
2. ❌ Forgetting to upload new helper files to production.
3. ❌ Duplicating `RewriteEngine On` blocks causing unpredictable behavior.
4. ❌ Assuming query strings belong in canonical URLs.
5. ❌ Using `Order allow,deny` / `Deny from all` in Apache 2.4 — use `Require all denied` instead.
6. ❌ Adding deprecated `X-XSS-Protection` header — removed by modern browsers; can introduce vulnerabilities.

#### ✅ Success Metrics

- ✅ 23 flagged URLs consolidated under canonical targets.
- ✅ All `discover-myomr` pages now emit canonical links (via meta.php, Mar 2026).
- ✅ HTTP/www variants 301 to `https://myomr.in/...`.
- ✅ Deployment checklist ready for handoff.

---

### November 7, 2025 - Root Directory File Organization & Project Structure Cleanup

**Project:** MyOMR Codebase Organization  
**Session Focus:** Organizing stray files from root directory into dedicated review folder

#### 🎯 Key Lessons Learned

##### 1. **Root Directory Minimalism Principle**

**Problem:** Root directory had 60+ stray files of various formats (PHP pages, SQL files, documentation, assets, data files) making it cluttered and difficult to navigate.

**Solution:** Moved all non-essential files to `@tocheck` folder, keeping only essential configuration files in root.

**Lesson:**

- Root directory should contain only essential files: configuration files (`.htaccess`, `.gitignore`), entry points (`index.php`), and critical system files (`robots.txt`, `sitemap.xml`)
- All content pages, documentation, SQL files, and assets should be organized in appropriate module folders
- Use dedicated review folders (like `@tocheck`) for files that need evaluation before proper organization
- Maintain a whitelist of essential files that must stay in root

**Implementation Pattern:**

```powershell
# Define essential files whitelist
$keep = @(
    '.deployignore',
    '.gitignore',
    '.htaccess',
    'index.php',
    'robots.txt',
    'sitemap.xml'
)

# Move all other files to review folder
Get-ChildItem -File | Where-Object {
    $keep -notcontains $_.Name
} | Move-Item -Destination '@tocheck'
```

##### 2. **Systematic File Organization Strategy**

**Problem:** Files were scattered in root without clear organization, making it hard to find and maintain code.

**Solution:** Created systematic approach: identify file types → categorize → move to appropriate locations → document changes.

**Lesson:**

- Categorize files by type before organizing (PHP pages, SQL files, documentation, assets, data files)
- Use PowerShell/scripting to automate bulk file operations
- Create review folders for files that need evaluation before permanent placement
- Document what was moved and why for future reference
- Verify file operations completed successfully before proceeding

**Implementation Pattern:**

```markdown
# File Organization Checklist:

1. Identify all files in root directory
2. Categorize by type (PHP, SQL, docs, assets, data)
3. Create review folder (@tocheck)
4. Define essential files whitelist
5. Move non-essential files to review folder
6. Verify moved files are accessible
7. Document changes in work log
```

##### 3. **Project Structure Best Practices**

**Problem:** Mixed file types in root directory violated project organization principles.

**Solution:** Established clear separation: configuration files in root, content in modules, documentation in `docs/`, assets in `assets/`.

**Lesson:**

- **Root directory:** Only configuration and entry points
- **Module folders:** Content pages specific to features (`/omr-local-events/`, `/omr-listings/`, etc.)
- **Documentation:** All `.md` files should be in `/docs/` or appropriate subfolders
- **SQL files:** Should be in `/dev-tools/migrations/` or module-specific folders
- **Assets:** CSS, JS, images should be in `/assets/` or module-specific asset folders
- **Data files:** Should be in appropriate data folders or module folders

**Implementation Pattern:**

```
project-root/
├── .htaccess (config)
├── .gitignore (config)
├── index.php (entry point)
├── robots.txt (config)
├── sitemap.xml (config)
├── @tocheck/ (review folder)
├── assets/ (shared assets)
├── components/ (reusable components)
├── core/ (core functions)
├── docs/ (documentation)
├── module-name/ (feature-specific files)
└── ...
```

##### 4. **File Review Workflow**

**Problem:** Files moved to `@tocheck` need systematic review to determine final placement.

**Solution:** Established review workflow: categorize → evaluate → relocate or delete.

**Lesson:**

- Review folder should be temporary - files should be processed and moved to proper locations
- Categorize files in review folder by type and purpose
- Evaluate each file: needed? belongs in module? can be deleted?
- Move files to appropriate permanent locations
- Delete obsolete or duplicate files
- Update any broken references after moving files

**Implementation Pattern:**

```markdown
# File Review Process:

1. List all files in @tocheck
2. Categorize by type and purpose
3. For each category:
   - Determine if file is still needed
   - Identify proper location (module folder, docs/, assets/, etc.)
   - Move to appropriate location
   - Or delete if obsolete
4. Update references in code/docs
5. Remove @tocheck folder when empty
```

#### 📝 Best Practices Established

1. **Keep root directory minimal** - Only essential configuration and entry point files
2. **Use dedicated folders** - Create folders like `@tocheck` for files needing review
3. **Systematic organization** - Categorize files before moving
4. **Document changes** - Track what was moved and why
5. **Automate when possible** - Use PowerShell/scripting for bulk operations
6. **Maintain whitelist** - Keep list of essential files that must stay in root
7. **Review systematically** - Process review folders regularly
8. **Update references** - Fix broken links after moving files

#### 🔧 Technical Patterns

##### Pattern 1: Essential Files Whitelist

```powershell
# Define whitelist of essential root files
$essentialFiles = @(
    '.deployignore',
    '.gitignore',
    '.htaccess',
    'index.php',
    'robots.txt',
    'sitemap.xml'
)
```

##### Pattern 2: File Organization Script

```powershell
# Create review folder
New-Item -ItemType Directory -Path "@tocheck" -Force

# Move non-essential files
Get-ChildItem -File | Where-Object {
    $essentialFiles -notcontains $_.Name
} | Move-Item -Destination '@tocheck'
```

##### Pattern 3: Project Structure Template

```
root/
├── Config files (.htaccess, .gitignore, etc.)
├── Entry points (index.php)
├── System files (robots.txt, sitemap.xml)
├── Module folders (feature-specific)
├── Shared folders (assets/, components/, core/)
└── Documentation (docs/)
```

#### 🚨 Common Pitfalls to Avoid

1. ❌ Keeping too many files in root directory
2. ❌ Not maintaining a whitelist of essential files
3. ❌ Moving files without documenting changes
4. ❌ Not reviewing files in review folders
5. ❌ Breaking references when moving files
6. ❌ Mixing file types in root directory
7. ❌ Not categorizing files before organizing
8. ❌ Forgetting to update documentation after reorganization

#### ✅ Success Metrics

- ✅ 60+ stray files moved to `@tocheck` folder
- ✅ Root directory cleaned to 9 essential files only
- ✅ Clear separation between configuration and content files
- ✅ Systematic approach established for future organization
- ✅ Documentation created for file organization process

---

### November 4, 2025 - WCAG Level AA Compliance Implementation

**Project:** MyOMR.in Website Accessibility Enhancement  
**Session Focus:** WCAG 2.1 Level AA Compliance Assessment & Fixes

#### 🎯 Key Lessons Learned

##### 1. **Main Landmark Element for Skip Links**

**Problem:** Skip links (`<a href="#main-content">`) were pointing to non-existent elements, making them useless for screen reader users.

**Solution:** Always wrap main content in semantic `<main id="main-content">` element.

**Lesson:**

- Skip links require actual target elements to function
- Use semantic HTML5 elements (`<main>`, `<nav>`, `<footer>`) for better accessibility
- Skip links should be the first focusable element on the page
- Always test skip link functionality with keyboard navigation

**Implementation Pattern:**

```html
<a href="#main-content" class="skip-link">Skip to main content</a>
<nav>...</nav>
<main id="main-content">
  <!-- Main page content here -->
</main>
<footer>...</footer>
```

##### 2. **ARIA Labels for Icon-Only Buttons**

**Problem:** Icon-only buttons (like WhatsApp float button) had no accessible name, making them invisible to screen readers.

**Solution:** Always add `aria-label` to icon-only buttons and `aria-hidden="true"` to decorative icons.

**Lesson:**

- Every interactive element must have an accessible name
- `aria-label` provides text alternative for icon-only elements
- Decorative icons should be hidden from screen readers with `aria-hidden="true"`
- Visual icons + text is better than icon-only for accessibility

**Implementation Pattern:**

```html
<!-- Icon-only button -->
<a href="..." class="float" aria-label="Join MyOMR WhatsApp community">
  <i class="fa fa-whatsapp" aria-hidden="true"></i>
</a>

<!-- Button with icon + text -->
<button>
  <i class="fas fa-search" aria-hidden="true"></i>
  Search
</button>
```

##### 3. **Form Label Accessibility Pattern**

**Problem:** Form inputs without visible labels are inaccessible to screen reader users.

**Solution:** Use visible labels (with `.sr-only` class if needed) + `aria-label` + `aria-describedby` for errors.

**Lesson:**

- Always associate labels with inputs using `for` attribute
- Use `.sr-only` class for labels that should be visually hidden but accessible
- Link error messages to inputs using `aria-describedby`
- Use `aria-invalid="true"` when validation fails
- Use `role="alert"` and `aria-live="polite"` for error messages

**Implementation Pattern:**

```html
<label for="email-subscribe" class="sr-only">Email Address</label>
<input
  type="email"
  id="email-subscribe"
  name="email"
  aria-label="Email address for newsletter subscription"
  aria-describedby="email-error"
  aria-invalid="false"
  required
/>
<div id="email-error" role="alert" aria-live="polite" class="error-message">
  Please enter a valid email address.
</div>
```

##### 4. **Modal Accessibility Pattern**

**Problem:** Modals weren't properly announced to screen readers and focus wasn't managed.

**Solution:** Use proper ARIA attributes and manage focus/keyboard events.

**Lesson:**

- Always use `role="dialog"` and `aria-modal="true"` for modals
- Link modal to title using `aria-labelledby`
- Link modal to description using `aria-describedby`
- Update `aria-hidden` state when modal opens/closes
- Trap focus within modal when open
- Allow Escape key to close modal

**Implementation Pattern:**

```html
<div
  class="modal"
  id="jobPromoModal"
  role="dialog"
  aria-modal="true"
  aria-labelledby="modal-title"
  aria-describedby="modal-description"
  aria-hidden="true"
>
  <h2 id="modal-title">Modal Title</h2>
  <p id="modal-description">Modal description text</p>
</div>

<script>
  // Update ARIA when modal shown
  modal.attr("aria-hidden", "false");

  // Update ARIA when modal hidden
  modal.attr("aria-hidden", "true");
</script>
```

##### 5. **Focus Indicator Requirements**

**Problem:** Default browser focus indicators weren't visible enough or were removed by CSS.

**Solution:** Always provide visible focus indicators (minimum 2px outline, 3:1 contrast ratio).

**Lesson:**

- Focus indicators are required for keyboard navigation (WCAG 2.4.7)
- Use `:focus-visible` pseudo-class for better UX (only shows on keyboard focus)
- Minimum 2px outline width, but 3px is better for visibility
- Use high-contrast color (yellow #ffbf47 works well)
- Never remove outline without providing alternative

**Implementation Pattern:**

```css
/* Global focus styles */
*:focus-visible {
  outline: 3px solid #ffbf47;
  outline-offset: 2px;
}

/* Specific focus styles for navigation */
.main-navbar a:focus,
.main-navbar button:focus {
  outline: 3px solid #ffbf47;
  outline-offset: 2px;
}
```

##### 6. **Link Purpose Accessibility**

**Problem:** Links with generic text like "Read More" or "Click here" lack context when read out of context.

**Solution:** Use descriptive link text or add `aria-label` with context.

**Lesson:**

- Link text should be descriptive on its own
- `aria-label` can provide additional context
- For repeated patterns (like "Read More"), include article title in aria-label
- Avoid "click here" or "read more" without context

**Implementation Pattern:**

```html
<!-- Good: Descriptive link text -->
<a href="...">Read more about Job Opportunities in OMR</a>

<!-- Also good: aria-label provides context -->
<a
  href="..."
  aria-label="Read more about <?php echo htmlspecialchars($title); ?>"
>
  Read More
</a>
```

##### 7. **Navigation Dropdown Accessibility**

**Problem:** Dropdown menus weren't properly announced and keyboard navigation wasn't supported.

**Solution:** Add ARIA attributes for dropdown state and keyboard support.

**Lesson:**

- Use `aria-haspopup="true"` to indicate dropdown
- Use `aria-expanded="true/false"` to indicate state
- Update `aria-expanded` when dropdown opens/closes
- Support keyboard navigation (Enter/Space to open, Arrow keys to navigate)
- Support Escape key to close

**Implementation Pattern:**

```html
<li class="dropdown">
  <a
    href="#"
    aria-label="Services menu"
    aria-haspopup="true"
    aria-expanded="false"
  >
    Services ▾
  </a>
  <div class="dropdown-content">
    <!-- Dropdown items -->
  </div>
</li>

<script>
  // Update aria-expanded on toggle
  button.setAttribute("aria-expanded", !isExpanded);
</script>
```

##### 8. **Screen Reader Only Class Pattern**

**Problem:** Needed labels that are visible to screen readers but not visually.

**Solution:** Create `.sr-only` utility class for visually hidden content.

**Lesson:**

- `.sr-only` class hides content visually but keeps it accessible
- Use for form labels when design requires minimal UI
- Must maintain accessibility without visual clutter
- Standard pattern used across accessibility frameworks

**Implementation Pattern:**

```css
.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border-width: 0;
}
```

#### 🔧 Best Practices Established

1. **Always include main landmark** - Required for skip links
2. **Label all interactive elements** - Buttons, links, inputs must have accessible names
3. **Hide decorative icons** - Use `aria-hidden="true"` on decorative Font Awesome icons
4. **Manage ARIA state** - Update `aria-expanded`, `aria-hidden` dynamically
5. **Visible focus indicators** - Never remove, always enhance
6. **Descriptive link text** - Or use `aria-label` for context
7. **Form error association** - Use `aria-describedby` and `role="alert"`
8. **Modal accessibility** - Always include proper ARIA dialog attributes

#### 📚 Resources Used

- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)
- [ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
- [WAVE Browser Extension](https://wave.webaim.org/extension/)

#### ✅ Compliance Checklist

- [x] Main landmark element present
- [x] All interactive elements have accessible names
- [x] Focus indicators visible on all elements
- [x] Form labels properly associated
- [x] Error messages properly announced
- [x] Modals properly structured with ARIA
- [x] Links have descriptive text or aria-label
- [x] Decorative icons hidden from screen readers
- [ ] Color contrast verified (recommended manual check)
- [ ] Screen reader testing completed (recommended)

---

### November 2, 2025 - Job Portal Development

**Project:** MyOMR Job Portal Feature Development  
**Session Focus:** Database Query Reliability & Employer Workflow Implementation

#### 🎯 Key Lessons Learned

##### 1. **Direct SQL Queries vs Prepared Statements**

**Problem:** Job listings were not displaying despite data existing in the database. Functions using prepared statements were failing silently.

**Solution:** Switched to direct SQL queries (like `test-jobs.php`) for critical data retrieval operations.

**Lesson:**

- Direct queries (`$conn->query()`) are more reliable for simple SELECT operations
- Prepared statements can fail silently if there are connection or binding issues
- Always have a working test file (`test-jobs.php`) to verify database connectivity and query execution
- Use direct queries as the primary approach, then enrich data with JOINs if needed

**Implementation Pattern:**

```php
// Primary: Direct query
$directQuery = "SELECT * FROM job_postings WHERE status = 'approved'";
$result = $conn->query($directQuery);

// Fallback: If direct fails, try with different conditions
if (!$result || $result->num_rows === 0) {
    $fallbackQuery = "SELECT * FROM job_postings WHERE LOWER(TRIM(status)) = 'approved'";
    $result = $conn->query($fallbackQuery);
}
```

##### 2. **Employer ID Verification Strategy**

**Problem:** Session `employer_id` didn't always match the database, causing authorization failures.

**Solution:** Implemented multi-level verification:

1. First check by `employer_id` from session
2. Fallback to email lookup (most reliable identifier)
3. Update session with correct ID if mismatch found

**Lesson:**

- Email is more reliable than ID for user identification (it's what users login with)
- Always verify ownership by both ID and email when possible
- Update session data if discrepancies are found
- Never trust session data alone - always verify against database

**Implementation Pattern:**

```php
// Primary: Lookup by email (most reliable)
$emailQuery = "SELECT * FROM employers WHERE email = '{$email}'";
$result = $conn->query($emailQuery);
if ($result && $result->num_rows > 0) {
    $employer = $result->fetch_assoc();
    // Update session if ID differs
    if ($employerId !== $employer['id']) {
        $_SESSION['employer_id'] = $employer['id'];
    }
}
```

##### 3. **Filter Application Logic**

**Problem:** Filters were being applied even when empty, filtering out all results.

**Solution:** Check if filters actually have values before applying them.

**Lesson:**

- `!empty($filters)` is not sufficient - an array with empty strings is still "not empty"
- Use `array_filter()` to check for actual values before applying filters
- Apply filters in PHP after fetching data if SQL filtering is unreliable

**Implementation Pattern:**

```php
// Check if filters have actual values
$hasFilters = !empty(array_filter($filters, function($v) {
    return $v !== '' && $v !== null;
}));

// Only apply filters if they have values
if ($hasFilters) {
    // Apply filtering logic
}
```

##### 4. **Status Field Handling**

**Problem:** Status comparisons failing due to whitespace or case differences.

**Solution:** Use multiple fallback approaches:

1. Direct comparison: `status = 'approved'`
2. Fallback: Query without status check
3. Last resort: `LOWER(TRIM(status)) = 'approved'`

**Lesson:**

- Never assume data is clean - always handle edge cases
- Provide multiple fallback queries
- Use `LOWER(TRIM())` for string comparisons when data quality is uncertain
- Log which query succeeded for debugging

##### 5. **Error Logging and Debugging**

**Problem:** Errors were happening silently, making debugging difficult.

**Solution:** Added comprehensive error logging at each step:

- Before query execution
- After query execution
- Check result counts
- Log which fallback was used

**Lesson:**

- Always log query execution attempts
- Log result counts to verify data retrieval
- Include context (file, function, line) in error messages
- Use `error_log()` extensively during development

**Implementation Pattern:**

```php
error_log("File: function(): Executing query: " . $query);
$result = $conn->query($query);
if ($result) {
    error_log("File: function(): Query succeeded, returned " . $result->num_rows . " rows");
} else {
    error_log("File: function(): Query failed: " . $conn->error);
}
```

##### 6. **Ownership Verification Pattern**

**Problem:** Need to ensure employers can only access their own data.

**Solution:** Always verify ownership before displaying or modifying data:

1. Check `employer_id` matches session
2. Fallback to email verification
3. Redirect if unauthorized

**Lesson:**

- Never trust URL parameters alone
- Always verify ownership at the start of protected pages
- Use multiple verification methods
- Redirect immediately if unauthorized

**Implementation Pattern:**

```php
// Verify ownership
$verifyQuery = "SELECT * FROM job_postings WHERE id = {$job_id} AND employer_id = {$employerId}";
$verifyResult = $conn->query($verifyQuery);
if (!$verifyResult || $verifyResult->num_rows === 0) {
    header('Location: my-posted-jobs-omr.php?error=unauthorized');
    exit;
}
```

##### 7. **Code Reusability Through Patterns**

**Problem:** Similar code duplicated across multiple files.

**Solution:** Establish consistent patterns that can be copied:

- Direct query approach from `test-jobs.php`
- Employer verification pattern
- Error handling pattern

**Lesson:**

- Create a working reference file (`test-jobs.php`) that demonstrates the correct approach
- Use consistent patterns across similar files
- Copy working code rather than trying to fix broken code
- Document patterns for future reference

##### 8. **User Experience - Modal Promotion**

**Problem:** Users weren't aware of new job features.

**Solution:** Created an attractive modal that appears on homepage to promote job features.

**Lesson:**

- Visual modals are effective for feature promotion
- Use localStorage to show modals only once per user
- Include clear call-to-action buttons
- Make modals dismissible but track engagement
- Use animations for better UX

#### 📝 Best Practices Established

1. **Always use direct queries for initial data retrieval**
2. **Verify ownership using both ID and email**
3. **Implement multiple fallback queries**
4. **Log extensively during development**
5. **Check filter values before applying**
6. **Handle status fields with LOWER(TRIM())**
7. **Update session data when discrepancies found**
8. **Create working test files as reference**

#### 🔧 Technical Patterns

##### Pattern 1: Direct Query with Fallbacks

```php
// Try multiple approaches
$query1 = "SELECT * FROM table WHERE condition = 'value'";
$result1 = $conn->query($query1);

if (!$result1 || $result1->num_rows === 0) {
    $query2 = "SELECT * FROM table WHERE LOWER(TRIM(condition)) = 'value'";
    $result2 = $conn->query($query2);
    // Use result2
}
```

##### Pattern 2: Employer Verification

```php
// 1. Verify by email (most reliable)
// 2. Update session if needed
// 3. Use verified ID for queries
```

##### Pattern 3: Filter Application

```php
// Only apply filters if they have actual values
$hasFilters = !empty(array_filter($filters, fn($v) => $v !== '' && $v !== null));
```

#### 🚨 Common Pitfalls to Avoid

1. ❌ Trusting prepared statements without testing
2. ❌ Using session ID without verification
3. ❌ Applying filters without checking for values
4. ❌ Assuming data is clean (whitespace, case)
5. ❌ Not logging query execution
6. ❌ Not verifying ownership before data access
7. ❌ Not providing fallback queries

#### ✅ Success Metrics

- ✅ All job listings now display correctly
- ✅ Employer-specific jobs show correctly
- ✅ Job detail pages load reliably
- ✅ Applications viewable by employers
- ✅ Edit functionality works
- ✅ All pages verify ownership properly

---

## 📝 Template for Daily Entries

Copy this template when adding new daily learnings:

````markdown
### [Date] - [Project/Feature Name]

**Project:** [Project Name]  
**Session Focus:** [What you worked on today]

#### 🎯 Key Lessons Learned

##### [Lesson Number]. **[Lesson Title]**

**Problem:** [Describe the problem encountered]

**Solution:** [Describe the solution implemented]

**Lesson:**

- [Key takeaway 1]
- [Key takeaway 2]
- [Key takeaway 3]

**Implementation Pattern:**

```php
// Code example here
```
````

#### 📝 Best Practices Established

1. **Best practice 1**
2. **Best practice 2**

#### 🔧 Technical Patterns

[Pattern descriptions and code examples]

#### 🚨 Common Pitfalls to Avoid

1. ❌ Pitfall 1
2. ❌ Pitfall 2

#### ✅ Success Metrics

- ✅ Metric 1
- ✅ Metric 2

````

---

### November 5, 2025 - News Article System & Database Maintenance

**Project:** MyOMR News Article System
**Session Focus:** Article Creation Workflow, Database Connection Handling, Related Articles Feature Fix

#### 🎯 Key Lessons Learned

##### 1. **Database Connection Lifecycle Management**

**Problem:** "More Articles" section was not displaying any articles. The database connection was closed before the related articles query could execute.

**Solution:** Keep database connection open until all queries are complete. Don't close connection after fetching main article if you need to fetch related data.

**Lesson:**
- Database connections should only be closed after ALL queries are complete
- If you need multiple queries on the same page, keep connection open
- Plan your query sequence before closing connections
- Connection closing should be the last step, not after each query

**Implementation Pattern:**
```php
// Fetch main article
$stmt = $conn->prepare("SELECT * FROM articles WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();
$stmt->close();
// DON'T close $conn here - we need it for related articles

// Later: Fetch related articles using same connection
$related_stmt = $conn->prepare("SELECT * FROM articles WHERE slug != ? LIMIT 6");
$related_stmt->bind_param("s", $slug);
$related_stmt->execute();
$related_result = $related_stmt->get_result();
// ... process results ...
$related_stmt->close();

// NOW close connection - all queries are done
$conn->close();
````

##### 2. **Article Router URL Format Consistency**

**Problem:** Article links in "More Articles" section were using wrong URL format (`/local-news/{slug}`) instead of the actual router format (`/local-news/article.php?slug={slug}`).

**Solution:** Always use the correct router format that matches how articles are actually accessed.

**Lesson:**

- Always verify the actual URL format used by your routing system
- Check existing working links to understand the correct format
- Don't assume clean URLs - verify with actual implementation
- Test links after creating them to ensure they work

**Implementation Pattern:**

```php
// Correct format for article router
$article_url = "/local-news/article.php?slug=" . htmlspecialchars($slug);

// NOT: "/local-news/" . $slug (won't work if router uses query parameter)
```

##### 3. **NULL Field Management in Database**

**Problem:** Multiple existing articles had NULL values for author, category, and tags fields, affecting SEO and content organization.

**Solution:** Create comprehensive UPDATE SQL statements to fill all NULL fields based on article content analysis.

**Lesson:**

- Regularly audit database for NULL fields
- Create UPDATE scripts to standardize metadata
- Assign appropriate categories based on article content analysis
- Generate SEO-optimized tags for all articles
- Standardize author attribution across all content
- Use verification queries to check for remaining NULLs

**Implementation Pattern:**

```php
// Update multiple articles with NULL fields
UPDATE articles SET
    author = 'MyOMR Editorial Team',
    category = 'Local News',
    tags = 'OMR news, Chennai, local events, community'
WHERE id = 1 AND (author IS NULL OR category IS NULL OR tags IS NULL);

// Verification query
SELECT id, title, author, category, tags
FROM articles
WHERE author IS NULL OR category IS NULL OR tags IS NULL;
```

##### 4. **SQL Escaping for Article Content**

**Problem:** Article content contains quotes, apostrophes, and special characters that break SQL INSERT statements.

**Solution:** Use proper SQL escaping and prepared statements. For content with HTML, handle quotes carefully.

**Lesson:**

- Use `&apos;` instead of single quotes in HTML content for SQL
- Use `&quot;` for double quotes in HTML attributes
- Always escape user input with `htmlspecialchars()` for display
- For SQL INSERT, use prepared statements or proper escaping
- Test SQL statements with complex content before deployment

**Implementation Pattern:**

```php
// For HTML content in SQL
$content = str_replace("'", "&apos;", $html_content); // HTML apostrophe
$content = str_replace('"', "&quot;", $content); // HTML quote

// Or use prepared statements
$stmt = $conn->prepare("INSERT INTO articles (title, content) VALUES (?, ?)");
$stmt->bind_param("ss", $title, $content); // No escaping needed
```

##### 5. **Related Articles Feature Implementation**

**Problem:** Related articles section was empty due to connection issues and incorrect URL format.

**Solution:** Fixed connection handling, corrected URL format, and enhanced display with images, summaries, and dates.

**Lesson:**

- Always fetch related articles using same connection as main article
- Use correct URL format matching your router
- Display related articles with images and summaries for better UX
- Include proper error handling for empty results
- Show meaningful messages when no articles available
- Limit related articles (6-8 is good for layout)
- Order by published_date DESC for most recent articles

**Implementation Pattern:**

```php
// Fetch related articles (exclude current article)
$related_sql = "SELECT slug, title, summary, image_path, published_date
                FROM articles
                WHERE status = 'published' AND slug != ?
                ORDER BY published_date DESC
                LIMIT 6";
$related_stmt = $conn->prepare($related_sql);
$related_stmt->bind_param("s", $slug);
$related_stmt->execute();
$related_result = $related_stmt->get_result();

$article_count = 0;
while ($related = $related_result->fetch_assoc()) {
    $article_count++;
    // Display article card with image, title, summary, date
    echo '<a href="/local-news/article.php?slug=' . htmlspecialchars($related['slug']) . '">';
    // ... display content ...
}
```

##### 6. **Article Content Enhancement Patterns**

**Problem:** Article display needs better visual presentation with images, summaries, and proper formatting.

**Solution:** Enhanced article cards with images, truncated summaries, dates, and hover effects.

**Lesson:**

- Always include article images if available (with fallback)
- Truncate summaries to 120-150 characters for card display
- Show published dates for context
- Use hover effects for better interactivity
- Make cards clickable as entire units
- Use responsive grid layouts for article listings
- Provide fallback images for articles without images

**Implementation Pattern:**

```php
// Enhanced article card display
$image = !empty($article['image_path'])
    ? htmlspecialchars($article['image_path'])
    : '/My-OMR-Logo.jpg'; // Fallback image

$summary = !empty($article['summary'])
    ? htmlspecialchars(substr($article['summary'], 0, 120)) . '...'
    : '';

$date = date('M j, Y', strtotime($article['published_date']));

// Display card with image, title, date, summary
```

##### 7. **News Article Creation Workflow**

**Problem:** Need a systematic approach to create news articles from source material.

**Solution:** Established workflow: analyze source → create editorial plan → write content → generate SQL → test.

**Lesson:**

- Always start with an editorial plan before writing
- Analyze source material for key facts, figures, and quotes
- Create comprehensive article structure (8-10 sections)
- Write engaging opening with vivid imagery
- Include visual elements (info boxes, bullet lists)
- Add actionable information for readers
- Generate SQL with proper escaping
- Test article display before deployment

**Implementation Pattern:**

```php
// Article creation workflow:
// 1. Editorial Plan (structure, SEO, keywords)
// 2. Content Creation (HTML format, 1000+ words)
// 3. SQL Generation (proper escaping, all fields)
// 4. Testing (verify display, links work)
// 5. Deployment (phpMyAdmin, sitemap, Search Console)
```

##### 8. **Work Log Documentation**

**Problem:** Need to track daily work and maintain project history.

**Solution:** Create detailed work logs documenting all tasks, fixes, and improvements.

**Lesson:**

- Maintain daily work logs with detailed task breakdown
- Document all bug fixes and their root causes
- Include file changes and SQL statements
- Track workflow steps for reproducibility
- Document impact and success metrics
- Keep work logs updated as work progresses

**Implementation Pattern:**

```markdown
# Work Log - [Date]

## Overview

Brief summary of day's work

## Tasks Completed

- Detailed breakdown of each task
- Files created/modified
- Bug fixes and improvements

## Next Steps

- Action items for follow-up
```

#### 📝 Best Practices Established

1. **Keep database connections open until all queries complete**
2. **Always verify URL formats match your routing system**
3. **Regularly audit and fill NULL database fields**
4. **Use proper SQL escaping for HTML content**
5. **Enhanced article cards with images, summaries, and dates**
6. **Follow systematic article creation workflow**
7. **Maintain detailed work logs for project tracking**
8. **Test all article links after implementation**
9. **Implement comprehensive structured data for rich snippets**
10. **Use consistent URL format across all pages and meta tags**
11. **Design navigation with clear visual hierarchy**
12. **Use pill-style buttons for call-to-action items**

#### 🔧 Technical Patterns

##### Pattern 1: Multi-Query Connection Handling

```php
// Open connection once
require_once 'core/omr-connect.php';

// Query 1: Main data
$stmt1 = $conn->prepare("SELECT ...");
// ... execute and process ...

// Query 2: Related data (same connection)
$stmt2 = $conn->prepare("SELECT ...");
// ... execute and process ...

// Close connection after ALL queries
$conn->close();
```

##### Pattern 2: Enhanced Article Card Display

```php
// Article card with image, summary, date
<div class="article-card">
    <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>">
    <h4><?php echo $title; ?></h4>
    <p class="date"><?php echo $date; ?></p>
    <p class="summary"><?php echo $summary; ?></p>
</div>
```

##### Pattern 3: NULL Field Audit and Update

```php
// Find NULL fields
SELECT id, title, author, category, tags
FROM articles
WHERE author IS NULL OR category IS NULL OR tags IS NULL;

// Update with appropriate values
UPDATE articles SET author = '...', category = '...', tags = '...' WHERE id = ?;
```

##### 9. **SEO Rich Snippets & Structured Data Enhancement**

**Problem:** Basic structured data existed but lacked important fields for rich snippets, limiting search result appearance.

**Solution:** Enhanced JSON-LD structured data with comprehensive fields, added breadcrumb schema, and improved Open Graph/Twitter Card tags.

**Lesson:**

- Add `articleBody` field for article content preview
- Include image dimensions (1200x630) for better social sharing
- Add `keywords` field from article tags
- Include `inLanguage` for locale-specific results
- Add BreadcrumbList schema for navigation structure
- Enhance publisher and author objects with URLs
- Use `addslashes()` for proper JSON escaping in PHP

**Implementation Pattern:**

```php
// Enhanced NewsArticle schema
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "<?php echo addslashes($article_title); ?>",
  "articleBody": "<?php echo addslashes(substr($article_content, 0, 500)); ?>",
  "image": {
    "@type": "ImageObject",
    "url": "<?php echo $article_image; ?>",
    "width": 1200,
    "height": 630
  },
  "keywords": "<?php echo addslashes($article_tags); ?>",
  "inLanguage": "en-IN"
}

// BreadcrumbList schema
{
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://myomr.in"},
    {"@type": "ListItem", "position": 2, "name": "Local News", "item": "https://myomr.in/local-news"},
    {"@type": "ListItem", "position": 3, "name": "<?php echo addslashes($article_title); ?>", "item": "<?php echo $article_url; ?>"}
  ]
}
```

##### 10. **Navigation Redesign - Two-Tier Menu Structure**

**Problem:** Navigation bar was cluttered with too many links, making it difficult to prioritize important actions.

**Solution:** Created a two-tier navigation structure with top secondary menu bar and main navigation bar, plus quick action pills.

**Lesson:**

- Separate primary actions from secondary navigation
- Use sticky positioning for frequently accessed menu items
- Create visual hierarchy with different menu levels
- Use pill-style buttons for call-to-action items
- Implement gradient backgrounds for visual appeal
- Add hover animations for better UX
- Ensure mobile responsiveness with flex-wrap and appropriate breakpoints

**Implementation Pattern:**

```css
/* Top Secondary Menu - Sticky, 60px height */
.top-secondary-menu {
  background: #1e7e34;
  height: 60px;
  position: sticky;
  top: 0;
  z-index: 1000;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* Quick Action Pills - Below main nav */
.action-pill {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1.25rem;
  border-radius: 50px; /* Pill shape */
  background: linear-gradient(135deg, #color1, #color2);
  transition: all 0.3s ease;
}

.action-pill:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(color, 0.4);
}
```

##### 11. **URL Format Consistency for SEO**

**Problem:** Mixed URL formats (query parameters vs clean URLs) causing canonical URL mismatches and SEO issues.

**Solution:** Standardized all URLs to use clean format, updated canonical tags, and ensured all internal links use consistent format.

**Lesson:**

- Always use clean URL format in canonical tags: `/local-news/slug`
- Ensure `.htaccess` rewrite rules match canonical URLs
- Update all internal links to use same format
- Test URL format with actual router implementation
- Verify canonical URLs match actual accessible URLs
- Use consistent format across meta tags (OG, Twitter, JSON-LD)

**Implementation Pattern:**

```php
// Use clean URL format consistently
$article_url = 'https://myomr.in/local-news/' . $article['slug'];

// In links
<a href="/local-news/<?php echo htmlspecialchars($slug); ?>">

// In canonical tag
<link rel="canonical" href="<?php echo $article_url; ?>">

// In structured data
"mainEntityOfPage": {
  "@type": "WebPage",
  "@id": "<?php echo $article_url; ?>"
}
```

#### 🚨 Common Pitfalls to Avoid

1. ❌ Closing database connection before all queries complete
2. ❌ Using wrong URL format for article links
3. ❌ Leaving NULL fields in database (hurts SEO)
4. ❌ Not escaping quotes in SQL content
5. ❌ Not including images in article cards
6. ❌ Creating articles without editorial planning
7. ❌ Not testing article links after creation
8. ❌ Not maintaining work logs
9. ❌ Incomplete structured data (missing fields for rich snippets)
10. ❌ Inconsistent URL formats (canonical vs actual URLs)
11. ❌ Cluttered navigation without visual hierarchy
12. ❌ Not using proper JSON escaping in PHP (causes JSON-LD errors)

#### ✅ Success Metrics

- ✅ "More Articles" section now displays 6 related articles
- ✅ Article cards show images, summaries, and dates
- ✅ All article links work correctly
- ✅ Database NULL fields standardized
- ✅ Article creation workflow established
- ✅ Work logs maintained and updated
- ✅ SEO rich snippets implemented with comprehensive structured data
- ✅ Navigation redesigned with modern two-tier structure
- ✅ URL format consistency across all pages

---

### November 6, 2025 - Sitemap Audit & SEO Implementation

**Project:** MyOMR.in Complete Sitemap Audit & Implementation  
**Session Focus:** Project-wide sitemap review, Pentahive industry pages, SEO structure

#### 🎯 Key Lessons Learned

##### 1. **Sitemap Index Pattern for Scalability**

**Problem:** Need to organize hundreds of pages across multiple modules (events, jobs, listings, hostels, coworking, pentahive) in a maintainable way for Google Search Console.

**Solution:** Implemented sitemap index pattern with main index referencing module-specific sub-sitemaps.

**Lesson:**

- Sitemap index allows modular organization by feature/domain
- Single submission point (`sitemap.xml`) for Google Search Console
- Each module maintains its own sitemap generator
- Easy to add new modules without modifying main sitemap
- Better organization for large sites (100+ pages)
- Sub-sitemaps can be updated independently

**Implementation Pattern:**

```php
// Main sitemap index (generate-sitemap-index.php)
$sitemaps = [
  $base . '/omr-local-events/sitemap.xml',
  $base . '/omr-listings/sitemap.xml',
  $base . '/omr-local-job-listings/sitemap.xml',
  $base . '/omr-hostels-pgs/sitemap.xml',
  $base . '/omr-coworking-spaces/sitemap.xml',
  $base . '/pentahive/sitemap.xml',
];

echo "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach ($sitemaps as $s) {
  echo "  <sitemap><loc>" . htmlspecialchars($s, ENT_XML1) . "</loc></sitemap>\n";
}
echo "</sitemapindex>\n";
```

##### 2. **Admin Files Exclusion Strategy**

**Problem:** Need to ensure admin interfaces, test files, and development tools are never indexed by search engines.

**Solution:** Established comprehensive exclusion list and systematic review process.

**Lesson:**

- Admin files should NEVER be included in sitemaps (security best practice)
- Create exclusion checklist: `/admin/`, `/process-*.php`, `/test-*.php`, `/dev-tools/`, etc.
- Review entire project structure systematically before sitemap generation
- Document exclusions clearly for future reference
- Use robots.txt to reinforce exclusions
- Separate public-facing pages from internal tools

**Implementation Pattern:**

```php
// Exclusion checklist for sitemap generation:
// ❌ /admin/ (entire folder)
// ❌ /process-*.php (form handlers)
// ❌ /test-*.php (test files)
// ❌ /dev-tools/ (development tools)
// ❌ /backups/ (backup files)
// ❌ /components/ (reusable includes)
// ❌ /core/ (backend functions)
// ❌ /assets/ (CSS, JS, images)
// ❌ /includes/ (templates)
// ❌ /weblog/ (log files)
```

##### 3. **Canonical URLs in Sitemaps**

**Problem:** Both `.php` and clean URL versions of pages could be indexed, causing duplicate content issues.

**Solution:** Include only canonical clean URLs in sitemaps, exclude `.php` versions.

**Lesson:**

- Always use canonical URLs in sitemaps (clean URLs preferred)
- Don't include both `.php` and clean URL versions
- Match sitemap URLs to canonical meta tags on pages
- Use `.htaccess` rewrite rules to support clean URLs
- Prevents duplicate content penalties from search engines
- Clean URLs are more user-friendly and SEO-friendly

**Implementation Pattern:**

```php
// ✅ Good: Clean URLs only
$pages = [
  ['loc' => '/pentahive/', 'priority' => '1.0'],
  ['loc' => '/pentahive/google-ads', 'priority' => '0.8'],
];

// ❌ Bad: Duplicate URLs
$pages = [
  ['loc' => '/pentahive/index.php', 'priority' => '1.0'],
  ['loc' => '/pentahive/', 'priority' => '1.0'], // Duplicate!
];
```

##### 4. **Dynamic Content Exclusion in Sitemaps**

**Problem:** Tamil language versions of articles were being included in sitemap, creating duplicate content.

**Solution:** Filter out language-specific versions using SQL WHERE clause.

**Lesson:**

- Exclude alternate language versions from main sitemap
- Use SQL filtering to exclude patterns (e.g., `slug NOT LIKE '%-tamil'`)
- Create separate sitemaps for different languages if needed
- Document language-specific sitemap strategy
- Prevents duplicate content issues across languages

**Implementation Pattern:**

```php
// Exclude Tamil versions from main sitemap
$sql = "SELECT slug, published_date, updated_at
        FROM articles
        WHERE status = 'published'
        AND slug NOT LIKE '%-tamil'
        ORDER BY published_date DESC";
```

##### 5. **Project-Wide Sitemap Audit Process**

**Problem:** Need systematic approach to identify all public-facing pages across entire project.

**Solution:** Conducted comprehensive project manager-style audit with documentation.

**Lesson:**

- Review entire project structure systematically
- Categorize pages by module/feature
- Document all public-facing pages in master list
- Separate static pages from dynamic content
- Track which pages belong in which sitemap
- Maintain documentation for future updates
- Use glob patterns to find all PHP files, then filter

**Implementation Pattern:**

```markdown
# Sitemap Audit Process:

1. List all directories and files
2. Categorize by module/feature
3. Identify public-facing vs internal
4. Document exclusion rules
5. Create master list
6. Organize into sitemap structure
7. Update sitemap generators
8. Test all sitemaps
9. Document for future reference
```

##### 6. **Industry-Specific Landing Page Pattern**

**Problem:** Need to create multiple landing pages for different industries while maintaining consistency.

**Solution:** Established reusable pattern for industry-specific pages with consistent structure.

**Lesson:**

- Use consistent page structure across all industry pages
- Include: Hero, Problems, Features, FAQ, CTA sections
- Link all industry pages to main service landing page
- Use SEO-friendly naming: `[industry]-website-design-maintenance.php`
- Place industry pages in root for better SEO
- Maintain consistent design and messaging
- Each page targets specific industry keywords

**Implementation Pattern:**

```php
// Industry page structure:
// 1. Hero section (industry-specific headline)
// 2. Problem statements (6-8 industry-specific problems)
// 3. Features section (what's included)
// 4. FAQ section (industry-specific questions)
// 5. CTA section (link to main landing page)
// 6. SEO meta tags (industry keywords)
// 7. JSON-LD structured data
```

##### 7. **Sitemap Route Configuration in .htaccess**

**Problem:** Need to route sitemap requests to PHP generators while maintaining clean URLs.

**Solution:** Add specific rewrite rules for each module's sitemap in root `.htaccess`.

**Lesson:**

- Centralize sitemap routing in root `.htaccess`
- Use pattern: `RewriteRule ^module/sitemap\.xml$ module/generate-sitemap.php [L]`
- Keep routing rules organized by module
- Test each sitemap route after adding
- Document routing structure for future modules
- Ensure all module sitemaps are accessible

**Implementation Pattern:**

```apache
# Module: Pentahive – sitemap
RewriteRule ^pentahive/sitemap\.xml$ pentahive/generate-sitemap.php [L]

# Module: Job Portal – sitemap
RewriteRule ^omr-local-job-listings/sitemap\.xml$ omr-local-job-listings/generate-sitemap.php [L]

# Module: Hostels & PGs – sitemap
RewriteRule ^omr-hostels-pgs/sitemap\.xml$ omr-hostels-pgs/generate-sitemap.php [L]
```

##### 8. **Documentation for Sitemap Maintenance**

**Problem:** Need to maintain sitemap documentation for future updates and team reference.

**Solution:** Created comprehensive documentation files tracking all pages and structure.

**Lesson:**

- Maintain master list of all pages (`SITEMAP-COMPLETE-LIST.md`)
- Document sitemap structure and organization
- Track which pages belong in which sitemap
- Document exclusion rules clearly
- Include implementation summary for reference
- Update documentation when adding new pages
- Use documentation as single source of truth

**Implementation Pattern:**

```markdown
# Documentation Structure:

1. SITEMAP-COMPLETE-LIST.md - Master inventory
2. SITEMAP-IMPLEMENTATION-SUMMARY.md - Implementation details
3. Update both when adding new pages
4. Reference documentation before making changes
```

#### 📝 Best Practices Established

1. **Use sitemap index pattern for multi-module sites**
2. **Never include admin files in sitemaps**
3. **Use canonical clean URLs only (no duplicates)**
4. **Filter out alternate language versions**
5. **Conduct systematic project-wide audits**
6. **Maintain consistent industry page patterns**
7. **Centralize sitemap routing in .htaccess**
8. **Document sitemap structure comprehensively**
9. **Test all sitemaps before Search Console submission**
10. **Update documentation when adding new pages**

#### 🔧 Technical Patterns

##### Pattern 1: Sitemap Index Structure

```php
// Main index references sub-sitemaps
// Each module has its own generator
// Single submission point for Google
```

##### Pattern 2: Module Sitemap Generator

```php
// Each module generates its own sitemap
// Includes static pages + dynamic content
// Uses clean URLs only
// Proper priorities and change frequencies
```

##### Pattern 3: Industry Landing Page

```php
// Consistent structure across all industries
// Industry-specific problems and features
// Links to main service landing page
// SEO-optimized with keywords
```

#### 🚨 Common Pitfalls to Avoid

1. ❌ Including admin files in sitemaps
2. ❌ Including both `.php` and clean URL versions
3. ❌ Not filtering alternate language versions
4. ❌ Not documenting sitemap structure
5. ❌ Not testing sitemaps before submission
6. ❌ Including test/debug files
7. ❌ Not maintaining exclusion checklist
8. ❌ Not updating documentation when adding pages
9. ❌ Submitting individual sub-sitemaps instead of main index
10. ❌ Not verifying canonical URLs match sitemap URLs

#### ✅ Success Metrics

- ✅ Complete project audit completed
- ✅ All public-facing pages documented
- ✅ 7 sub-sitemaps organized and configured
- ✅ Admin files properly excluded
- ✅ Clean URLs used throughout
- ✅ Comprehensive documentation created
- ✅ Sitemap index structure implemented
- ✅ Ready for Google Search Console submission

---

### November 6, 2025 - Election BLO Feature: File Naming, SEO Enhancement & Brand Awareness

**Project:** MyOMR.in Election BLO Feature Implementation  
**Session Focus:** File/folder renaming, comprehensive SEO enhancements, brand awareness integration

#### 🎯 Key Lessons Learned

##### 1. **Human-Readable File Naming Strategy**

**Problem:** Technical file names like `parse-blo-csv.php` and `INSERT-BLO-DATA.sql` were not immediately understandable to humans, making maintenance and collaboration difficult.

**Solution:** Renamed all files to use action-oriented, descriptive names that clearly indicate their purpose.

**Lesson:**

- Use action verbs in file names: `process-`, `import-`, `generate-`, `create-`
- Be specific about what the file does: `process-blo-csv-data.php` vs `parse-blo-csv.php`
- Use kebab-case consistently for readability
- Folder names should reflect module purpose: `election-blo-details/` vs `omr-election-blo/`
- Update ALL references when renaming (sitemaps, includes, links, documentation)
- Document renames for team reference

**Implementation Pattern:**

```php
// File naming convention:
// Action-Verb + Subject + Format
// Examples:
// process-blo-csv-data.php (processes CSV data)
// import-blo-records.sql (imports records)
// generate-blo-sitemap.php (generates sitemap)
// create-blo-database.sql (creates database)
// adjust-section-column.sql (adjusts column)
```

##### 2. **Comprehensive SEO with Multiple Structured Data Schemas**

**Problem:** Basic SEO existed but lacked rich snippets and multiple schema types for maximum search engine visibility.

**Solution:** Implemented multiple JSON-LD schemas (WebPage, FAQPage, HowTo, GovernmentService, Organization) with comprehensive fields.

**Lesson:**

- Use multiple schema types for different purposes (WebPage for structure, FAQPage for questions, HowTo for instructions)
- Include all required and recommended fields for each schema type
- Add geographic targeting (geo.region, geo.placename) for local SEO
- Include image dimensions (1200x630) for better social sharing
- Use `addslashes()` for proper JSON escaping in PHP
- Test schemas with Google Rich Results Test tool
- FAQPage schema enables expandable FAQs in search results
- HowTo schema enables step-by-step guide display in search results

**Implementation Pattern:**

```php
// Multiple schemas for comprehensive SEO
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "...",
  "breadcrumb": { "@type": "BreadcrumbList", ... },
  "potentialAction": { "@type": "SearchAction", ... },
  "mainEntity": { "@type": "GovernmentService", ... }
}
</script>

<script type="application/ld+json">
{
  "@type": "FAQPage",
  "mainEntity": [ /* questions */ ]
}
</script>

<script type="application/ld+json">
{
  "@type": "HowTo",
  "step": [ /* step-by-step instructions */ ]
}
</script>
```

##### 3. **Conditional Structured Data for Article Types**

**Problem:** Need to add article-specific structured data (FAQPage, HowTo) only for certain article types (BLO articles) without hardcoding.

**Solution:** Use conditional logic to detect article type and add schemas only when appropriate.

**Lesson:**

- Use slug or title pattern matching to detect article type
- Keep generic schemas (NewsArticle, BreadcrumbList) for all articles
- Add specialized schemas conditionally based on content type
- Maintain flexibility for future article types
- Document which schemas apply to which article types

**Implementation Pattern:**

```php
// Detect article type
$is_blo_article = (strpos($article['slug'], 'blo') !== false ||
                   strpos(strtolower($article['title']), 'blo') !== false);

// Add schemas conditionally
<?php if ($is_blo_article): ?>
  <!-- FAQPage Schema -->
  <!-- HowTo Schema -->
  <!-- GovernmentService Schema -->
<?php endif; ?>
```

##### 4. **Brand Awareness Integration on Trending Pages**

**Problem:** Need to leverage high-traffic trending pages (like BLO search) for brand awareness and subscriber growth.

**Solution:** Added prominent community awareness section with subscription form to trending pages.

**Lesson:**

- Identify high-traffic or trending pages for brand awareness placement
- Create visually striking sections that stand out (gradient backgrounds, clear CTAs)
- Explain value proposition clearly (what users get)
- Make subscription process simple (minimal fields, clear benefits)
- Track source of subscriptions for analytics
- Use generic content that works across all pages (not page-specific)
- Position section prominently but not intrusively

**Implementation Pattern:**

```php
// Generic community section (works for all pages)
<?php if ($show_community_section): ?>
  <div class="community-awareness-section">
    <!-- What MyOMR is -->
    <!-- Benefits list -->
    <!-- Subscription form -->
    <!-- Social media links -->
  </div>
<?php endif; ?>

// Track source dynamically
<input type="hidden" name="source" value="News Article - <?php echo htmlspecialchars($article['title']); ?>">
```

##### 5. **Email Subscription Handler with Source Tracking**

**Problem:** Need to capture email subscriptions with context about where users subscribed from.

**Solution:** Created subscription handler that sends emails with source tracking and logs to CSV.

**Lesson:**

- Always include source tracking (which page user came from)
- Send email notifications for immediate awareness
- Log to CSV file for backup and analytics
- Include honeypot field for spam protection
- Validate email addresses server-side
- Provide clear success/error messages
- Redirect back to original page with status parameters

**Implementation Pattern:**

```php
// Subscription handler
$source = htmlspecialchars(trim($_POST['source'] ?? 'Unknown'));
$page_url = htmlspecialchars(trim($_POST['page_url'] ?? ''));

// Send email with source info
$body = '<p><strong>Source:</strong> ' . $source . '</p>';
$body .= '<p><strong>Page:</strong> ' . $page_url . '</p>';

// Log to CSV
$logEntry = date('Y-m-d H:i:s') . ',' . $email . ',' . $name . ',' . $source . "\n";
file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);

// Redirect with status
header('Location: ' . $redirectUrl . '?subscribed=1');
```

##### 6. **Generic vs. Specific Content in Shared Templates**

**Problem:** Added BLO-specific content to article.php which is used for ALL articles, causing inappropriate content on non-BLO articles.

**Solution:** Made community awareness section generic and applicable to all articles, while keeping BLO-specific structured data conditional.

**Lesson:**

- Always consider if content is generic or specific when adding to shared templates
- Use conditional logic for specific content (like BLO schemas)
- Make promotional/brand awareness content generic (works for all pages)
- Test with different article types to ensure content is appropriate
- Document which features are generic vs. specific

**Implementation Pattern:**

```php
// Generic feature (shows on all articles)
$show_community_section = true; // Always true

// Specific feature (shows only for BLO articles)
$is_blo_article = (strpos($article['slug'], 'blo') !== false);

// Use appropriately
<?php if ($show_community_section): ?>
  <!-- Generic community section -->
<?php endif; ?>

<?php if ($is_blo_article): ?>
  <!-- BLO-specific schemas -->
<?php endif; ?>
```

##### 7. **Search Functionality Simplification**

**Problem:** Too many search filters (location, part number, BLO name, mobile) made interface cluttered and confusing.

**Solution:** Simplified to only essential filters (location and polling station) that users actually need.

**Lesson:**

- Less is more - fewer filters = better UX
- Focus on filters users actually use
- Remove redundant or rarely-used filters
- Test with actual users to identify essential filters
- Keep interface clean and focused
- Provide clear labels and instructions

**Implementation Pattern:**

```php
// Simplified search - only essential filters
$search_location = isset($_GET['location']) ? trim($_GET['location']) : '';
$search_polling_station = isset($_GET['polling_station']) ? trim($_GET['polling_station']) : '';

// Build query with only these filters
if (!empty($search_location)) {
    $where[] = "location LIKE ?";
}
if (!empty($search_polling_station)) {
    $where[] = "polling_station_name LIKE ?";
}
```

##### 8. **File Rename Impact Analysis**

**Problem:** Renaming files requires updating all references across the codebase, which can be missed.

**Solution:** Systematic approach: find all references → update all → verify with grep.

**Lesson:**

- Always search for all references before renaming
- Update references in: includes, links, sitemaps, documentation, SQL files
- Use grep to find all occurrences
- Update documentation files as well
- Test after renaming to ensure nothing breaks
- Create rename summary document for reference

**Implementation Pattern:**

```bash
# Find all references
grep -r "old-filename" .

# Update in order:
# 1. Code files (PHP, includes)
# 2. Configuration files (.htaccess, sitemaps)
# 3. Documentation files
# 4. SQL files (if they contain file references)
# 5. Test all functionality
```

#### 📝 Best Practices Established

1. **Use human-readable, action-oriented file names**
2. **Implement multiple structured data schemas for comprehensive SEO**
3. **Use conditional logic for article-specific features**
4. **Leverage trending pages for brand awareness**
5. **Track subscription sources for analytics**
6. **Keep shared templates generic, use conditionals for specifics**
7. **Simplify search interfaces - less is more**
8. **Systematically update all references when renaming files**
9. **Test schemas with Google Rich Results Test**
10. **Document file renames and their impact**

#### 🔧 Technical Patterns

##### Pattern 1: Human-Readable File Naming

```php
// Convention: Action-Verb + Subject + Format
// process-blo-csv-data.php
// import-blo-records.sql
// generate-blo-sitemap.php
// create-blo-database.sql
```

##### Pattern 2: Multiple SEO Schemas

```php
// WebPage schema (structure)
// FAQPage schema (questions)
// HowTo schema (instructions)
// GovernmentService schema (service details)
// Organization schema (site info)
```

##### Pattern 3: Conditional Article Features

```php
// Generic feature (all articles)
$show_community_section = true;

// Specific feature (BLO articles only)
$is_blo_article = (strpos($slug, 'blo') !== false);
```

##### Pattern 4: Source Tracking

```php
// Track where subscription came from
$source = "News Article - " . $article_title;
$page_url = $article_url;

// Include in email and CSV log
```

#### 🚨 Common Pitfalls to Avoid

1. ❌ Using technical/abbreviated file names
2. ❌ Not updating all references when renaming files
3. ❌ Adding page-specific content to shared templates
4. ❌ Not tracking subscription sources
5. ❌ Too many search filters (clutters interface)
6. ❌ Incomplete structured data (missing fields)
7. ❌ Not testing schemas with validation tools
8. ❌ Hardcoding content that should be generic
9. ❌ Not documenting file renames
10. ❌ Forgetting to update documentation files

#### ✅ Success Metrics

- ✅ All files renamed to human-readable names
- ✅ All code references updated successfully
- ✅ Comprehensive SEO with multiple schema types
- ✅ Rich snippets enabled (FAQ, HowTo, Article)
- ✅ Brand awareness section on trending pages
- ✅ Subscription system with source tracking
- ✅ Generic community section for all articles
- ✅ Simplified search interface
- ✅ Mobile-responsive design
- ✅ Ready for production deployment

---

### November 7, 2025 - Documentation Organization & Project Management

**Project:** MyOMR Documentation Reorganization  
**Session Focus:** Systematic documentation organization, folder structure planning, maintainability

#### 🎯 Key Lessons Learned

##### 1. **Systematic Documentation Organization Strategy**

**Problem:** Documentation folder had 70+ files unorganized, making it difficult to find relevant information quickly.

**Solution:** Created category-based folder structure with 8 organized buckets: analytics-seo, data-backend, content-projects, operations-deployment, product-prd, strategy-marketing, worklogs, archive, inbox.

**Lesson:**

- Organize documentation by function/purpose, not by date or project
- Create clear folder names that indicate content type (analytics-seo, data-backend, etc.)
- Keep frequently accessed docs in root (ARCHITECTURE.md, CHANGELOG.md)
- Use archive folder for obsolete/superseded documents
- Maintain inbox folder for new documents needing triage
- Update README.md to reflect new organization structure

**Implementation Pattern:**

```
docs/
├── analytics-seo/        # Analytics, SEO, Search Console
├── data-backend/         # Database, infrastructure, security
├── content-projects/     # Article drafts, phase summaries
├── operations-deployment/# Onboarding, checklists, workflows
├── product-prd/          # PRDs, feature definitions
├── strategy-marketing/   # Marketing strategies, playbooks
├── worklogs/             # Daily development logs
├── archive/              # Obsolete documents
└── inbox/                # New docs needing triage
```

##### 2. **Documentation Index Maintenance**

**Problem:** After reorganizing files, all links in README.md were broken.

**Solution:** Systematically updated all file paths in README.md to reflect new folder structure.

**Lesson:**

- Always update index/documentation files when reorganizing
- Use relative paths consistently
- Test all links after reorganization
- Update statistics (file counts, categories) in documentation
- Keep README.md as single source of truth for documentation structure

**Implementation Pattern:**

```markdown
# Before reorganization

[ONBOARDING.md](ONBOARDING.md)

# After reorganization

[operations-deployment/ONBOARDING.md](operations-deployment/ONBOARDING.md)
```

##### 3. **Folder Naming Conventions for Documentation**

**Problem:** Need clear, intuitive folder names that indicate content type.

**Solution:** Used kebab-case with descriptive prefixes: `analytics-seo`, `data-backend`, `operations-deployment`.

**Lesson:**

- Use kebab-case for folder names (matches file naming convention)
- Make folder names self-explanatory
- Group related documents together
- Avoid overly specific names that limit future additions
- Use consistent naming pattern across all folders

**Implementation Pattern:**

```
✅ Good: analytics-seo, data-backend, operations-deployment
❌ Bad: analytics, backend, ops (too vague)
❌ Bad: google-analytics-seo-only (too specific)
```

##### 4. **Documentation Lifecycle Management**

**Problem:** No clear process for handling obsolete documents or new documents needing review.

**Solution:** Created archive folder for obsolete docs and inbox folder for new documents.

**Lesson:**

- Establish clear lifecycle: inbox → organized folder → archive
- Archive obsolete documents instead of deleting (preserve history)
- Use inbox as temporary holding for unorganized docs
- Schedule regular documentation cleanup (monthly/quarterly)
- Document organization rules in README.md

**Implementation Pattern:**

```
New document → inbox/ → Review → Move to appropriate folder
Obsolete document → archive/ (with date prefix)
```

##### 5. **Maintaining Documentation Statistics**

**Problem:** Documentation statistics in README.md were outdated after reorganization.

**Solution:** Updated statistics to reflect new organization: 70+ files, 8 categories, updated date.

**Lesson:**

- Keep documentation statistics current
- Update file counts when adding/removing documents
- Update "Last Updated" date when making changes
- Include category breakdown in statistics
- Make statistics visible in README for quick reference

**Implementation Pattern:**

```markdown
## 📊 Documentation Statistics

- **Total Documentation Files:** 70+
- **Total Words:** ~100,000+
- **Categories:** 8 organized folders
- **Last Updated:** January 2025
- **Status:** Organized & Complete ✅
```

#### 📝 Best Practices Established

1. **Organize documentation by function, not by date**
2. **Create clear, self-explanatory folder names**
3. **Update all index files when reorganizing**
4. **Maintain documentation lifecycle (inbox → organized → archive)**
5. **Keep README.md as single source of truth**
6. **Update statistics and dates when reorganizing**
7. **Test all links after reorganization**
8. **Document organization rules for future reference**

#### 🔧 Technical Patterns

##### Pattern 1: Category-Based Organization

```
docs/
├── [category-name]/  # Grouped by function
│   ├── file1.md
│   └── file2.md
├── README.md         # Index with links
└── [root-level-files] # Frequently accessed
```

##### Pattern 2: Documentation Lifecycle

```
New → inbox/ → Review → [category]/ → archive/ (when obsolete)
```

##### Pattern 3: README.md Structure

```markdown
# Documentation Organization

## Quick Start

## Documentation Overview (by category)

## Find What You Need

## Statistics
```

#### 🚨 Common Pitfalls to Avoid

1. ❌ Organizing by date instead of function
2. ❌ Not updating index files after reorganization
3. ❌ Using vague folder names
4. ❌ Not maintaining documentation statistics
5. ❌ Deleting obsolete docs instead of archiving
6. ❌ Not testing links after reorganization
7. ❌ Not documenting organization rules

#### ✅ Success Metrics

- ✅ 70+ files organized into 8 clear categories
- ✅ All links in README.md updated and working
- ✅ Clear folder structure established
- ✅ Documentation statistics updated
- ✅ Archive and inbox folders created for lifecycle management

---

### November 7, 2025 - Documentation README Enhancement for AI Systems

**Project:** MyOMR Documentation README Enhancement  
**Session Focus:** Making `docs/README.md` comprehensive and AI-friendly, ensuring Cursor AI understands documentation structure

#### 🎯 Key Lessons Learned

##### 1. **AI-Friendly Documentation Structure**

**Problem:** `docs/README.md` needed to be comprehensive enough for AI systems to understand the complete documentation structure, organization principles, and how to navigate the docs folder.

**Solution:** Enhanced README.md with AI-specific sections, complete file inventory, explicit navigation guides, and structured metadata.

**Lesson:**

- AI systems need explicit context about documentation purpose and structure
- Add dedicated "AI System Context & Overview" section explaining what the docs folder is
- Include complete file counts and inventory for each category
- Provide explicit navigation strategies for AI systems
- Document code-to-documentation mapping relationships
- Explain documentation maintenance lifecycle clearly
- Use structured metadata and clear section headings

**Implementation Pattern:**

```markdown
## 🤖 AI System Context & Overview

**Purpose of This Documentation Folder:** [Clear explanation]
**Project Context:** [Technology stack, deployment info]
**Documentation Philosophy:** [Organization approach]
**For AI Systems:** [How AI should use this documentation]

## 📋 Documentation Metadata
- Project Name, Version, File Counts, Organization System

## 🎯 Documentation Purpose & Relevance
- What documentation covers
- How it relates to codebase
- Maintenance lifecycle
```

##### 2. **Complete File Inventory in README**

**Problem:** README only listed key files, not all files in each category, making it incomplete for comprehensive understanding.

**Solution:** Added file counts per category and "All Files" lists showing every file in major categories.

**Lesson:**

- Include file counts in category breakdown table
- List all files in major categories (not just key files)
- Distinguish between "Key Files" (important) and "All Files" (complete inventory)
- Update file counts when adding/removing documentation
- Note that complete listings are available in category folders

**Implementation Pattern:**

```markdown
### **Analytics & SEO** 📊 (12 files)

**Key Files:**
- [List of important files with links]

**All Files:** [Complete comma-separated list of all files]
```

##### 3. **Documentation Creation Workflow Documentation**

**Problem:** No clear guidance on how to create new documentation or where it should go.

**Solution:** Added comprehensive "Documentation Creation Workflow" section with step-by-step process.

**Lesson:**

- Document the complete workflow for creating new documentation
- Explain the inbox → review → categorize → move process
- Provide clear guidance on which category different doc types belong to
- Include update procedures for README and index files
- Make workflow explicit and actionable

**Implementation Pattern:**

```markdown
### **Documentation Creation Workflow**

1. Create document in `docs/inbox/`
2. Review and categorize
3. Move to appropriate folder
4. Update README.md if key document
5. Update category index if exists
6. Cross-reference from related docs
```

##### 4. **Cursor AI Rules File (.cursorrules)**

**Problem:** Cursor AI needed explicit instructions to read `docs/README.md` when working with documentation.

**Solution:** Created `.cursorrules` file with prominent section instructing Cursor to read `docs/README.md` first for any docs-related tasks.

**Lesson:**

- `.cursorrules` file is read by Cursor when analyzing the project
- Place critical instructions at the top with prominent warnings
- Explicitly state what file to read and when
- Explain what context will be gained by reading the file
- Reinforce instructions in multiple sections (top, workflow, maintenance)
- Make it clear that `docs/README.md` is the primary source of truth

**Implementation Pattern:**

```markdown
## ⚠️ IMPORTANT: Working with the `docs/` Folder

**When you encounter any task related to the `docs/` folder, you MUST:**

1. **Read `docs/README.md` FIRST** - This file explains:
   - Complete structure and organization
   - What each category contains
   - Documentation conventions
   - How documentation relates to codebase
   - What should be done in docs folder

2. **Gain Context** - By reading `docs/README.md`, you will understand:
   - Where to place new documentation
   - How to navigate existing documentation
   - Documentation maintenance procedures
```

##### 5. **Cross-Reference Strategy for Discoverability**

**Problem:** `docs/README.md` needed to be discoverable from other key files in the project.

**Solution:** Added references to `docs/README.md` in `LEARNINGS.md` and `.cursorrules` file.

**Lesson:**

- Reference key documentation files from multiple locations
- Add prominent links in frequently accessed files
- Use `.cursorrules` to ensure AI systems know about documentation
- Cross-reference creates multiple discovery paths
- Makes documentation more likely to be found

**Implementation Pattern:**

```markdown
# In LEARNINGS.md:
> **📚 Documentation Hub:** For comprehensive project documentation, 
> see [`docs/README.md`](docs/README.md)

# In .cursorrules:
**`docs/README.md` is your primary source of truth for understanding 
the documentation structure.**
```

##### 6. **Comprehensive Statistics and Metadata**

**Problem:** Documentation statistics were incomplete and didn't show full picture of documentation structure.

**Solution:** Enhanced statistics section with detailed file distribution, category breakdown, and complete counts.

**Lesson:**

- Include detailed file distribution breakdown
- Show file counts per category
- Update statistics when documentation changes
- Make statistics visible and easy to find
- Include note about complete file listings

**Implementation Pattern:**

```markdown
## 📊 Documentation Statistics

- **Total Documentation Files:** 77+ files
- **File Distribution:**
  - Analytics & SEO: 12 files
  - Data & Backend: 11 files
  - Content Projects: 14 files
  - [etc...]
- **Note:** Tables show key files; browse folders for complete listings
```

##### 7. **Documentation Notes for AI Systems Section**

**Problem:** Needed explicit section explaining documentation completeness, accuracy, conventions, and maintenance status for AI systems.

**Solution:** Added dedicated "Documentation Notes for AI Systems" section at the end of README.

**Lesson:**

- Explicitly state documentation completeness and accuracy
- Document conventions (file paths, date formats, technical terms)
- Explain cross-reference patterns
- State maintenance status clearly
- Provide confidence level for AI systems

**Implementation Pattern:**

```markdown
## 📝 Documentation Notes for AI Systems

**Documentation Completeness:** [Status]
**Documentation Accuracy:** [How it's maintained]
**Documentation Conventions:** [Formatting rules]
**Cross-References:** [How docs link to each other]
**Maintenance Status:** [Update frequency]
```

#### 📝 Best Practices Established

1. **Make README comprehensive** - Include complete file inventory, not just key files
2. **Add AI-specific sections** - Explicit context and navigation guides for AI systems
3. **Document creation workflow** - Clear process for adding new documentation
4. **Use .cursorrules file** - Explicit instructions for Cursor AI
5. **Cross-reference from key files** - Multiple discovery paths
6. **Maintain complete statistics** - File counts, distribution, categories
7. **Explicit metadata sections** - Project context, purpose, philosophy
8. **Documentation notes for AI** - Completeness, accuracy, conventions

#### 🔧 Technical Patterns

##### Pattern 1: AI-Friendly README Structure

```markdown
# AI System Context & Overview
# Documentation Metadata
# Documentation Purpose & Relevance
# Documentation Organization (with file counts)
# AI System Navigation Guide
# Complete File Inventory
# Documentation Creation Workflow
# Documentation Notes for AI Systems
```

##### Pattern 2: .cursorrules Integration

```markdown
## ⚠️ IMPORTANT: Working with the `docs/` Folder

**When you encounter any task related to the `docs/` folder, you MUST:**

1. Read `docs/README.md` FIRST
2. Gain Context about structure
3. Follow the Structure when working
```

##### Pattern 3: Complete File Inventory

```markdown
### Category Name (File Count)

**Key Files:**
- [Important files with links]

**All Files:** [Complete comma-separated list]
```

#### 🚨 Common Pitfalls to Avoid

1. ❌ Only listing key files without complete inventory
2. ❌ Not providing AI-specific context and navigation
3. ❌ Missing documentation creation workflow
4. ❌ Not using .cursorrules to guide AI systems
5. ❌ Incomplete statistics (missing file counts)
6. ❌ Not cross-referencing from other key files
7. ❌ Assuming AI systems will automatically find documentation
8. ❌ Not explaining documentation purpose and relevance

#### ✅ Success Metrics

- ✅ README.md enhanced with AI-friendly structure
- ✅ Complete file inventory added (77+ files documented)
- ✅ Documentation creation workflow documented
- ✅ .cursorrules file created with explicit instructions
- ✅ Cross-references added in LEARNINGS.md
- ✅ Comprehensive statistics with file distribution
- ✅ AI System Navigation Guide added
- ✅ Documentation Notes for AI Systems section added
- ✅ README.md now sufficient for covering entire docs folder

---

### November 6, 2025 - Modal Popup Browser Compatibility & Scroll Detection

**Project:** MyOMR Modal Popup System  
**Session Focus:** Cross-browser compatibility, scroll detection, user experience improvements

#### 🎯 Key Lessons Learned

##### 1. **Scroll Velocity Detection for Non-Intrusive Modals**

**Problem:** Modal appeared during active scrolling, interrupting user experience and causing frustration.

**Solution:** Implemented real-time scroll velocity tracking using `requestAnimationFrame` and smart timing logic.

**Lesson:**

- Track scroll velocity in real-time using `requestAnimationFrame` for performance
- Monitor both wheel events (desktop) and touch events (mobile)
- Don't show modal if user is actively scrolling (velocity > 50px)
- Wait for scrolling to stop before showing modal
- Cancel pending modal if fast scrolling detected (>100px velocity)
- Use different delays for desktop (150ms) vs mobile (200-300ms) momentum
- Set maximum timeout (10 seconds) to prevent infinite waiting

**Implementation Pattern:**

```javascript
let scrollVelocity = 0;
let lastScrollTop = 0;
let lastScrollTime = Date.now();

function trackScroll() {
  const now = Date.now();
  const timeDelta = now - lastScrollTime;
  const scrollDelta = Math.abs(window.scrollY - lastScrollTop);

  scrollVelocity = scrollDelta / (timeDelta / 1000); // px per second
  lastScrollTop = window.scrollY;
  lastScrollTime = now;

  requestAnimationFrame(trackScroll);
}

// Only show modal if scroll velocity < 50px/s
if (scrollVelocity < 50) {
  showModal();
}
```

##### 2. **Cross-Browser CSS Transition Compatibility**

**Problem:** Modal transitions not working smoothly across all browsers, especially older browsers and mobile Safari.

**Solution:** Used `opacity/visibility/pointer-events` instead of `display: none/flex` and added vendor prefixes.

**Lesson:**

- Use `opacity` and `visibility` for transitions instead of `display` (display can't be animated)
- Add `-webkit-` vendor prefixes for transform animations (older browser support)
- Use `pointer-events: none` to prevent interaction when hidden
- Fix conflicting `display` properties in pseudo-elements
- Test on mobile Safari specifically (has unique quirks)

**Implementation Pattern:**

```css
/* ❌ Bad: display can't be animated */
.modal {
  display: none;
  transition: display 0.3s; /* Won't work */
}

/* ✅ Good: opacity/visibility can be animated */
.modal {
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
  transition: opacity 0.3s, visibility 0.3s;
}

.modal.show {
  opacity: 1;
  visibility: visible;
  pointer-events: auto;
}
```

##### 3. **Mobile Safari Scroll Lock Implementation**

**Problem:** Body scroll lock not working properly on iOS Safari, allowing background scrolling when modal open.

**Solution:** Enhanced scroll lock with `position: fixed`, scroll position restoration, and iOS-specific fixes.

**Lesson:**

- Use `position: fixed` on body when modal open (prevents scrolling)
- Save scroll position before locking
- Restore scroll position when modal closes
- Add `-webkit-fill-available` for full-height on iOS
- Use `overscroll-behavior: none` to prevent elastic scrolling
- Add `touch-action: none` to prevent pull-to-refresh interference
- Handle scroll momentum on mobile devices

**Implementation Pattern:**

```javascript
let savedScrollPosition = 0;

function lockScroll() {
  savedScrollPosition = window.scrollY;
  document.body.style.position = "fixed";
  document.body.style.top = `-${savedScrollPosition}px`;
  document.body.style.width = "100%";
  document.body.style.overflow = "hidden";
}

function unlockScroll() {
  document.body.style.position = "";
  document.body.style.top = "";
  document.body.style.width = "";
  document.body.style.overflow = "";
  window.scrollTo(0, savedScrollPosition);
}
```

##### 4. **Robust Modal Close Function with Fallbacks**

**Problem:** Skip button and close icon not working reliably across all browsers, especially when Bootstrap/jQuery unavailable.

**Solution:** Created close function with multiple fallback methods and explicit DOM manipulation.

**Lesson:**

- Never rely on single method for critical functionality
- Provide multiple fallback methods (Bootstrap method → jQuery → vanilla JS)
- Use explicit DOM manipulation as final fallback
- Handle errors gracefully with try-catch blocks
- Remove backdrop and body classes properly
- Support Escape key for accessibility
- Clean up localStorage and modal state

**Implementation Pattern:**

```javascript
function closeModal() {
  try {
    // Method 1: Bootstrap modal method
    if (typeof $ !== "undefined" && $("#modal").modal) {
      $("#modal").modal("hide");
      return;
    }
  } catch (e) {}

  try {
    // Method 2: Direct DOM manipulation
    const modal = document.getElementById("modal");
    modal.style.display = "none";
    modal.classList.remove("show");
    document.body.classList.remove("modal-open");

    // Remove backdrop
    const backdrop = document.querySelector(".modal-backdrop");
    if (backdrop) backdrop.remove();

    // Cleanup
    unlockScroll();
    localStorage.setItem("modalShown", "true");
  } catch (e) {
    console.error("Error closing modal:", e);
  }
}
```

##### 5. **Accessible Close Controls**

**Problem:** Modal lacked visible close button and skip button wasn't accessible.

**Solution:** Added circular close icon (×) in top right corner with proper ARIA labels and Escape key support.

**Lesson:**

- Always provide visible close button (× icon in top right)
- Make close button accessible with ARIA labels
- Support Escape key for keyboard users
- Style close button prominently (white background, shadow, hover effects)
- Make close button responsive (smaller on mobile)
- Ensure close button works with all close methods

**Implementation Pattern:**

```html
<button class="modal-close" aria-label="Close modal" onclick="closeModal()">
  ×
</button>

<script>
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && modalIsOpen) {
      closeModal();
    }
  });
</script>
```

#### 📝 Best Practices Established

1. **Track scroll velocity to prevent intrusive modals**
2. **Use opacity/visibility for CSS transitions (not display)**
3. **Add vendor prefixes for cross-browser compatibility**
4. **Implement robust scroll lock for mobile Safari**
5. **Create close functions with multiple fallback methods**
6. **Always provide visible, accessible close controls**
7. **Support Escape key for keyboard accessibility**
8. **Test on mobile Safari specifically (has unique quirks)**

#### 🔧 Technical Patterns

##### Pattern 1: Scroll Velocity Detection

```javascript
// Track velocity in real-time
// Only show modal when velocity < threshold
// Handle desktop and mobile differently
```

##### Pattern 2: CSS Transitions

```css
/* Use opacity/visibility, not display */
/* Add vendor prefixes */
/* Handle mobile Safari quirks */
```

##### Pattern 3: Scroll Lock

```javascript
// Save position → Lock → Restore position
// Handle iOS Safari specifically
```

##### Pattern 4: Modal Close with Fallbacks

```javascript
// Try Bootstrap → Try jQuery → Use vanilla JS
// Handle errors gracefully
// Clean up properly
```

#### 🚨 Common Pitfalls to Avoid

1. ❌ Using `display: none/flex` for animated transitions
2. ❌ Not tracking scroll velocity before showing modals
3. ❌ Relying on single close method (Bootstrap only)
4. ❌ Not handling mobile Safari scroll lock properly
5. ❌ Missing vendor prefixes for older browsers
6. ❌ Not providing visible close button
7. ❌ Not supporting Escape key
8. ❌ Not testing on mobile Safari

#### ✅ Success Metrics

- ✅ Modal no longer interrupts fast scrolling
- ✅ Works reliably across all browsers (Chrome, Firefox, Safari, Edge, mobile)
- ✅ Skip button and close icon work in all browsers
- ✅ Proper scroll lock on mobile Safari
- ✅ Smooth transitions across all devices
- ✅ Escape key support for accessibility

---

---

---

## 🗓️ Session Learning — News Card Layout Redesign (Mar 6, 2026)

**Task:** Redesign `myomr-news-bulletin.php` from a flat uniform grid to a Wire-inspired editorial layout with hero, medium cards, and a compact sidebar list. Then run a full audit and fix all issues.

---

### UI/UX — News Layout Design

#### Inspiration Source: The Wire (thewire.in)

When redesigning a news section for a local portal, studying real editorial publications reveals layout patterns that create reading hierarchy. The Wire's homepage uses:

1. **Hero + sidebar list split** — one dominant story (large image, headline, excerpt) paired with a compact list of 4–5 secondary stories. Readers immediately know what's "most important."
2. **Category color badges** — uppercase labels (`LOCAL`, `INFRA`, `EVENTS`, `ALERT`) before each headline. Gives context before a reader even reads the title.
3. **Section headers with "View More →"** — bold section title (left) + pill button link (right), separated by a 3px colored underline. Clean editorial zone.
4. **No excerpt in compact/sidebar cards** — only badge + headline + date. Keeps the sidebar scannable and fast.
5. **Author / date meta in muted small text** — clearly secondary to the headline.

**Key translation for MyOMR:**
- Adapted the national-news cold palette into a warm, community-friendly feel (brand green accents, off-white backgrounds)
- Used 6 badge colors mapped to local categories: `LOCAL` (green), `EVENTS` (orange), `INFRA` (blue), `COMMUNITY` (teal), `BUSINESS` (amber), `ALERT` (red)

---

### CSS — Responsive Patterns

#### The Gap+Background Grid Trick for Clean Dividers

**Problem:** When a sidebar list switches to a 2-column grid on tablet, using `border-bottom` on items creates an inconsistent result — the last item in the *longer* column loses its border while the same-row item in the *shorter* column keeps one.

**Bad pattern (fragile nth-child math):**
```css
.news-compact:nth-last-child(-n+2) {
    border-bottom: none; /* Wrong: removes from row-2 col-2, not just last row */
}
```

**Correct pattern (gap + background trick):**
```css
.newsroom-sidebar {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1px;
    background: #f0f0f0; /* becomes the "border" colour */
    overflow: hidden;
}

.news-compact {
    background: #fff; /* white fills the cells; 1px gap shows through as dividers */
    border: none;
}
```
This produces perfectly consistent row and column dividers regardless of item count, with zero nth-child calculation.

---

### Accessibility — Focus Ring Rule

**Never use `outline: none` on `:focus-visible`.** It removes the keyboard navigation indicator completely, which is a WCAG 2.1 failure.

**Correct pattern:**
```css
/* BAD */
.button:focus-visible {
    outline: none;
}

/* GOOD — replace with a custom branded ring */
.button:focus-visible {
    outline: 2px solid #005c26;
    outline-offset: 3px;
}
```

Always keep `:hover` and `:focus-visible` as **separate rules** so the focus ring can differ from the hover state.

---

### Performance — Hero Image Loading

**Problem:** `loading="lazy"` on the hero/LCP image delays the browser from fetching it during initial page load, directly hurting the Lighthouse LCP score.

**Rule:** The first visible image on any page — especially a hero — must never be lazy-loaded.

```html
<!-- BAD: delays LCP -->
<img src="hero.jpg" loading="lazy">

<!-- GOOD: hints browser to fetch immediately, high priority -->
<img src="hero.jpg" loading="eager" fetchpriority="high">
```

Apply `loading="lazy"` only to images that are **below the fold** (not visible on first render).

---

### SEO — `<time>` datetime Attribute

Every `<time>` element used for publication dates must carry a machine-readable `datetime` attribute. Without it, search engines and structured data parsers cannot extract the date.

```html
<!-- BAD: human-readable only -->
<time>Apr 1, 2025</time>

<!-- GOOD: both human and machine readable -->
<time datetime="2025-04-01">Apr 1, 2025</time>
```

Format: `YYYY-MM-DD` (ISO 8601). Required for Google's `NewsArticle` structured data parsing.

---

### HTML — Heading Hierarchy in News Components

When a reusable component has its own section title (`<h2>`), the content headings inside it must step down:

| Element | Tag | Notes |
|---|---|---|
| Section title | `<h2>` | `.newsroom-header__title` |
| Hero card headline | `<h3>` | Most prominent story |
| Medium card headline | `<h4>` | Secondary stories |
| Compact sidebar headline | `<h5>` | Tertiary list items |

**Why it matters:** Screen readers build a document outline from headings. Two `<h2>` elements in the same scope (section title + hero) make them appear as peers to assistive tech, confusing navigation. Even though HTML5 article/section elements create heading scopes, best practice is to step down explicitly.

---

### PHP/URLs — Always Use Root-Relative Paths in Reusable Components

**Problem:** A component with `href="omr-news.php"` works fine on the homepage but silently breaks when included from `/events/page.php` — it resolves to `/events/omr-news.php`.

**Rule:** Any link or asset path inside a reusable `include` or component must be root-relative:

```php
<!-- BAD: breaks when component is included from subfolders -->
<a href="omr-news.php">View All</a>
<link rel="stylesheet" href="myomr-news-bulletin.css">

<!-- GOOD: always resolves to the correct absolute path -->
<a href="/omr-news.php">View All</a>
<link rel="stylesheet" href="/assets/css/myomr-news-bulletin.css">
```

This applies to: links, image `src`, CSS `href`, JS `src`, and form `action` attributes inside any PHP include/component.

---

### Code Hygiene — Don't Leave Dead CSS Files

When a CSS file's canonical location moves (e.g. from `/components/myomr-news-bulletin.css` to `/assets/css/myomr-news-bulletin.css`), the old file must be cleared — not deleted (it may be git-tracked) but emptied with a redirect comment:

```css
/*
 * This file is intentionally empty.
 * Styles live in: /assets/css/myomr-news-bulletin.css
 * Do not add styles here.
 */
```

Leaving stale CSS creates silent conflicts if anyone ever accidentally references the old path.

---

### CSS — Transitions Should Cover All Animated Properties

When a button changes multiple visual properties on hover, all animated properties must be listed in `transition`:

```css
/* BAD: border-color flashes instantly while background fades */
transition: background 0.2s ease, color 0.2s ease;

/* GOOD: all three properties animate together */
transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
```

---

## 🔍 Search & Quick Find

**Common Searches:**

- **Structured Data:** [Section 8.1](#81-conditional-schema-generation-for-article-types), [Section 8.3](#83-sports-content-seo---multiple-structured-data-schemas)
- **Article Issues:** [Section 8.2](#82-article-visibility-issues---database-status-fields)
- **SEO Patterns:** [Section 7](#7-seo--analytics), [Section 8.3](#83-sports-content-seo---multiple-structured-data-schemas)
- **Admin Navigation:** [Daily Learnings - Admin & Navigation](#admin--navigation)
- **Modal Patterns:** [Daily Learnings - UI/UX & Modals](#uiux--modals)
- **Database Patterns:** [Section 3](#3-mysqli--sql-hygiene), [Daily Learnings - Database & Data Management](#database--data-management)
- **JobPosting Schema:** [Daily Learnings - Database & Data Management](#database--data-management)
- **Sitemap Patterns:** [Daily Learnings - SEO & Structured Data](#seo--structured-data)
- **News Card Layout:** [Session Learning — News Card Layout Redesign (Mar 6, 2026)](#️-session-learning--news-card-layout-redesign-mar-6-2026)

---

**Last Updated:** March 6, 2026  
**Total Learnings Entries:** 13 core sections + daily learnings by topic + session learnings  
**Maintenance:** Update after each significant work session  
**Status:** ✅ Active
