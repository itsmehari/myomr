# 🎓 MyOMR – Project Learnings (Consolidated Daily Log)

<div align="center">

**Consolidated lessons learned document for the MyOMR project**

![Status](https://img.shields.io/badge/Status-Active-success)
![Last Updated](https://img.shields.io/badge/Last%20Updated-November%2015%2C%202025-blue)
![Total Sections](https://img.shields.io/badge/Sections-13%20Core-orange)

</div>

---

> **📚 Documentation Hub:** For comprehensive project documentation, see [`docs/README.md`](docs/README.md) - This is the main navigation hub for all documentation, organized into 8 category folders with clear structure and AI-friendly navigation.

---

## 📋 Table of Contents

<details>
<summary><b>Click to expand navigation menu</b></summary>

### [Core Engineering & Module Learnings](#-core-engineering--module-learnings)

| #   | Section                                                                    | Status |
| --- | -------------------------------------------------------------------------- | ------ |
| 1   | [Engineering Process & Flow Design](#1-engineering-process--flow-design)   | ✅     |
| 2   | [PHP Error Handling & Observability](#2-php-error-handling--observability) | ✅     |
| 3   | [MySQLi & SQL Hygiene](#3-mysqli--sql-hygiene)                             | ✅     |
| 4   | [Security Basics](#4-security-basics-every-feature)                        | 🔒     |
| 5   | [URL/Paths & Routing](#5-urlpaths--routing)                                | ✅     |
| 6   | [UX & Design System](#6-ux--design-system)                                 | ✅     |
| 7   | [SEO & Analytics](#7-seo--analytics)                                       | ✅     |
| 8   | [Articles/News Learnings](#8-articlesnews-learnings)                       | ✅     |
| 9   | [Jobs Module Learnings](#9-jobs-module-learnings)                          | ✅     |
| 10  | [Events Module Learnings](#10-events-module-learnings)                     | ✅     |
| 11  | [Content & Admin Ops](#11-content--admin-ops)                              | ✅     |
| 12  | [DevOps & Environments](#12-devops--environments)                          | ✅     |
| 13  | [Documentation & Checklists](#13-documentation--checklists)                | ✅     |

### [Quick Reference](#-quick-reference)

- 📝 [Canonical Code Patterns](#canonical-code-patterns)
- 🔗 [Common Patterns Index](#common-patterns-index)

### [Daily Learnings by Topic](#-daily-learnings-by-topic)

- 🔍 [SEO & Structured Data](#seo--structured-data)
- 🎛️ [Admin & Navigation](#admin--navigation)
- 🎨 [UI/UX & Modals](#uiux--modals)
- 💾 [Database & Data Management](#database--data-management)
- 📚 [Documentation & Workflows](#documentation--workflows)

### [Daily Learnings Log (Chronological)](#-daily-learnings-log-chronological)

</details>

---

## 📚 Core Engineering & Module Learnings

### 1️⃣ Engineering Process & Flow Design

> **💡 Key Principle:** Always start with a concise requirements brief and a WBS; add a "Flow Mapping & Edge Cases" phase up front.

#### ✅ Best Practices

- ✅ Always start with a concise requirements brief and a **WBS** (Work Breakdown Structure)
- ✅ Add a **"Flow Mapping & Edge Cases"** phase up front
- ✅ Maintain an **implementation tracker** and mark items complete as soon as they're done
- ✅ Before coding, list **happy paths** and **failure modes**:
  - Validation errors
  - Authentication failures
  - Network issues
  - Database errors
  - Permission problems
  - Empty states
- ✅ Define expected user messages for each scenario

---

### 2️⃣ PHP Error Handling & Observability

> **⚠️ Critical:** Centralize dev error reporting; expose readable in-page blocks in dev and always log to file.

#### 🎯 Implementation Checklist

- [x] Centralize dev error reporting
- [x] Expose readable in-page blocks in dev
- [x] Always log to file
- [x] Use custom exception/err handlers
- [x] Add shutdown handler for fatals
- [x] Capture stack traces in logs
- [x] Return generic messages to users
- [x] Log exact errors for ops

#### 📝 Pattern

```php
// Custom error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // Log to file
    error_log("[$errno] $errstr in $errfile:$errline");

    // Show user-friendly message
    if (defined('DEBUG') && DEBUG) {
        echo "<div class='error'>$errstr</div>";
    } else {
        echo "<div class='error'>An error occurred. Please try again.</div>";
    }
});
```

---

### 3️⃣ MySQLi & SQL Hygiene

> **🔐 Security Note:** Match `bind_param` signatures precisely; type order must equal placeholders.

#### ⚠️ Common Pitfalls

| Issue           | Solution                                          |
| --------------- | ------------------------------------------------- |
| Type mismatch   | Match `bind_param` signatures precisely           |
| Argument order  | Type order must equal placeholders                |
| Spread args     | Avoid mixing spread args with positional ones     |
| Nullable FKs    | Use `NULLIF(?,0)` or bind `NULL` when appropriate |
| Missing indexes | Add helpful indexes for frequent filters          |

#### ✅ Best Practices

- ✅ Match `bind_param` signatures precisely
- ✅ Type order must equal placeholders
- ✅ Build `$bindValues` first then unpack
- ✅ Handle nullable FKs with `NULLIF(?,0)` or bind `NULL`
- ✅ Add helpful indexes for frequent filters:
  - Dates
  - Status
  - Category
  - Featured
- ✅ Order by indexed columns when possible

#### 📝 Example

```php
// ✅ Good: Build params array first
$params = [$status, $category];
$types = 'ss';
$stmt->bind_param($types, ...$params);

// ❌ Bad: Mixing spread and positional
$stmt->bind_param('ss', $status, ...[$category]);
```

---

### 4️⃣ Security Basics (Every Feature) 🔒

> **🚨 CRITICAL:** Security must be implemented in every feature, not as an afterthought.

#### 🛡️ Security Checklist

| Security Layer       | Implementation                                                           |
| -------------------- | ------------------------------------------------------------------------ |
| **Input Validation** | Validate and sanitize inputs on both client and server                   |
| **CSRF Protection**  | Use CSRF tokens for all mutating actions                                 |
| **Rate Limiting**    | Add honeypot + basic rate limiting for public forms                      |
| **File Uploads**     | Validate MIME using `finfo` + size limits + dedicated upload directories |
| **Output Escaping**  | Escape output with `htmlspecialchars`; never trust DB strings in HTML    |

#### ⚠️ Security Rules

> **⚠️ NEVER:**
>
> - Trust user input
> - Trust database strings in HTML
> - Skip CSRF tokens on mutating actions
> - Allow unrestricted file uploads

> **✅ ALWAYS:**
>
> - Validate and sanitize inputs
> - Use CSRF tokens
> - Validate file MIME types
> - Escape output with `htmlspecialchars`

---

### 5️⃣ URL/Paths & Routing

> **🌐 Best Practice:** Prefer absolute, site-rooted hrefs in nav to avoid relative path breakage across folders.

#### ✅ Guidelines

- ✅ Prefer **absolute, site-rooted hrefs** in nav
- ✅ Avoid relative path breakage across folders
- ✅ Keep a plan for **clean URLs** via `.htaccess` rewrites
- ✅ Align **canonical tags** with the final routes
- ✅ Use **consistent slugging**
- ✅ Enforce **uniqueness** and collision handling (`base`, `base-2`, …)

#### 📝 Example

```apache
# .htaccess rewrite rule
RewriteRule ^local-news/([^/]+)/?$ local-news/article.php?slug=$1 [L,QSA]
```

---

### 6️⃣ UX & Design System

> **🎨 Design Philosophy:** Establish a shared style system and reuse across modules.

#### 🎯 Design Principles

- ✅ Establish a **shared style system** (glassmorphism + unified components)
- ✅ Reuse across modules
- ✅ Add modern, accessible forms:
  - Clear labels
  - Helper text
  - Real-time validation
  - Focused error states
- ✅ Ensure responsive grids for two-column layouts
- ✅ Treat mobile single-column as first-class

---

### 7️⃣ SEO & Analytics

> **🔍 SEO Strategy:** Treat discoverability as a flow step: canonical, meta description, OG/Twitter, JSON-LD.

#### 📊 SEO Checklist

| SEO Element          | Implementation                    |
| -------------------- | --------------------------------- |
| **Canonical URLs**   | Always include canonical tags     |
| **Meta Description** | 155 characters, keyword-rich      |
| **Open Graph**       | OG tags for social sharing        |
| **Twitter Cards**    | Twitter Card tags                 |
| **JSON-LD**          | Structured data schemas           |
| **Sitemaps**         | Generate sitemaps for new modules |
| **Search Console**   | Resubmit after launches           |
| **Analytics**        | GA events for business funnels    |
| **UTM Tracking**     | Use UTM on share links            |

#### 🎯 Analytics Events

Track these business funnels:

- Filter use
- View events
- Share actions
- Ticket clicks
- Submit start/success

---

### 8️⃣ Articles/News Learnings

> **📰 Article System:** Prefer DB-driven articles with a router template (`article.php`) for clean URLs and centralized SEO.

#### ✅ Core Principles

- ✅ Prefer **DB-driven articles** with a router template (`article.php`)
- ✅ Use **clean URLs** and centralized SEO
- ✅ When inserting complex HTML into SQL, beware of quoting
- ✅ Simplify HTML or escape consistently
- ✅ Build a **sitemap generator** for articles
- ✅ Verify canonical URLs align with routed paths

---

#### 8.1️⃣ Conditional Schema Generation for Article Types

> **🎯 Problem:** Need to add article-specific structured data (Person, SportsEvent, FAQPage) only for certain article types (sports articles) without affecting other articles.

> **✅ Solution:** Multi-layer conditional system with file inclusion check, data detection check, and content matching check.

##### 🔧 Implementation Pattern

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

##### 💡 Why This Matters

| Benefit                   | Description                                  |
| ------------------------- | -------------------------------------------- |
| **Prevents Invalid Data** | Schemas added only when relevant data exists |
| **Maintains SEO Quality** | No empty or incorrect schemas                |
| **Safe for All Articles** | Non-sports articles have zero impact         |
| **Graceful Fallback**     | No errors if conditions don't match          |
| **Scalable Pattern**      | Can add more article types easily            |

##### 🛡️ Safety Guarantees

| Article Type                 | Impact                                                  |
| ---------------------------- | ------------------------------------------------------- |
| **Non-sports articles**      | File not included → **Zero impact**                     |
| **General sports articles**  | File included but no schemas match → **Minimal impact** |
| **Specific sports articles** | File included AND schemas match → **Enhanced SEO**      |

##### 📚 Lesson

- ✅ Use multiple conditional layers for article-specific enhancements
- ✅ Always check data existence before adding schemas
- ✅ Maintain backward compatibility with existing articles
- ✅ Document safety guarantees for each enhancement

---

#### 8.2️⃣ Article Visibility Issues - Database Status Fields

> **⚠️ Problem:** Article added to database but not showing on homepage/news highlights page, even after hard refresh.

> **🔍 Root Cause:** Article visibility depends on multiple database fields.

##### 📋 Visibility Requirements

| Field            | Requirement                           | Common Issues           |
| ---------------- | ------------------------------------- | ----------------------- |
| `status`         | Must be `'published'`                 | `'draft'` or `NULL`     |
| `published_date` | Must be current date or in the past   | Future date             |
| `is_featured`    | Should be `1` for homepage prominence | `0` or `NULL`           |
| `slug`           | Must NOT be like `'%-tamil'`          | Tamil versions excluded |

##### 🔧 Solution: Diagnostic SQL

```sql
-- Diagnostic: Check article status
SELECT
    id,
    title,
    slug,
    status,
    published_date,
    is_featured,
    image_path,
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

##### 💡 Why This Matters

- Article display depends on multiple database fields
- Hard refresh won't fix database issues
- Need diagnostic tools to identify the problem
- Common issues: `status='draft'`, `published_date` in future, `is_featured=0`

##### 📚 Lesson

- ✅ Always verify database fields after inserting articles
- ✅ Create diagnostic SQL for troubleshooting
- ✅ Document visibility requirements clearly
- ✅ Check status, date, featured flag when articles don't appear

---

#### 8.3️⃣ Sports Content SEO - Multiple Structured Data Schemas

> **🎯 Problem:** Need comprehensive SEO for sports articles with athlete profiles, event details, and FAQ content.

> **✅ Solution:** Implement multiple JSON-LD schemas conditionally.

##### 📊 Schema Types

| Schema             | When Added                    | Purpose             |
| ------------------ | ----------------------------- | ------------------- |
| **NewsArticle**    | Always present                | Base article schema |
| **Person**         | When athlete name detected    | Athlete profile     |
| **SportsEvent**    | When event detected           | Tournament details  |
| **FAQPage**        | When specific person detected | Questions/answers   |
| **BreadcrumbList** | Always present                | Navigation          |

##### 🔧 Implementation Pattern

```json
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

##### 🎯 SEO Benefits

| Feature                   | Schema         | Benefit                           |
| ------------------------- | -------------- | --------------------------------- |
| **Featured Snippet**      | FAQPage        | Eligibility for featured snippets |
| **Knowledge Graph**       | Person         | Panel eligibility                 |
| **Event Rich Snippets**   | SportsEvent    | Event display in search           |
| **Image Thumbnails**      | NewsArticle    | Image in search results           |
| **Breadcrumb Navigation** | BreadcrumbList | Navigation in search              |

##### 💡 Why This Matters

- Person schema enables Knowledge Graph eligibility
- SportsEvent schema enables event-rich snippets
- FAQPage schema enables featured snippet eligibility
- Multiple schemas increase rich snippet opportunities
- Better understanding for search engines

##### 📚 Lesson

- ✅ Use multiple structured data schemas for comprehensive SEO
- ✅ Conditionally add schemas based on content type and data availability
- ✅ Person schema for athlete/person profiles
- ✅ SportsEvent schema for tournament/sports event coverage
- ✅ FAQPage schema for question-answer content
- ✅ Always validate schemas with Rich Results Test before deployment

---

### 9️⃣ Jobs Module Learnings

> **💼 Module Structure:** Create a dedicated module namespace with `includes/`, `assets/`, `admin/` and helper functions.

#### ✅ Best Practices

- ✅ Create a dedicated module namespace with `includes/`, `assets/`, `admin/` and helper functions
- ✅ For selects like categories, implement fallbacks (inactive/all) and dev diagnostics when empty
- ✅ Add GA custom event tracking for job flows
- ✅ Include structured data (JobPosting) where relevant
- ✅ Normalize JobPosting JSON-LD with helper utilities that infer:
  - Mailing address
  - Salary units
  - `validThrough` from raw job data
- ✅ Ensure Search Console validates every listing

---

### 🔟 Events Module Learnings

> **📅 Event System:** Separate `event_submissions` (pending) from `event_listings` (published) with a clear moderation path.

#### ✅ Best Practices

- ✅ Separate `event_submissions` (pending) from `event_listings` (published)
- ✅ Implement clear moderation path
- ✅ Implement end-to-end affordances on detail pages:
  - Share (UTM)
  - Map integration
  - Tickets
  - Add to Calendar
  - ICS download
- ✅ Use JSON-LD Event on listing and detail
- ✅ Ensure dates/locations are complete

---

### 1️⃣1️⃣ Content & Admin Ops

> **⚙️ Admin Operations:** Provide admin queues with clear statuses; wire Approve/Reject endpoints and emit diagnostics on failures.

#### ✅ Best Practices

- ✅ Provide admin queues with clear statuses
- ✅ Wire Approve/Reject endpoints
- ✅ Emit diagnostics on failures
- ✅ Auto-approve can be used temporarily to unblock UAT, but gate behind a config
- ✅ Keep a cross-promo widget (featured items) for homepage or section pages

---

### 1️⃣2️⃣ DevOps & Environments

> **🔧 Environment Notes:** On shared hosting, some PHP functions/operators differ; avoid unpacking + positional arg combos that older engines mishandle.

#### ⚠️ Shared Hosting Considerations

- ⚠️ Some PHP functions/operators differ on shared hosting
- ⚠️ Avoid unpacking + positional arg combos that older engines mishandle
- ⚠️ If using opcode cache, touch/redeploy changed files when debugging
- ⚠️ Logs are authoritative
- ⚠️ For remote DB development, document SSH tunnel requirements
- ⚠️ Document local scripts that run on the target host when needed

---

### 1️⃣3️⃣ Documentation & Checklists

> **📚 Documentation:** Maintain: WBS, Implementation Tracker, SEO/Analytics checklist, Learnings, Deployment/Human Testing guides.

#### ✅ Documentation Checklist

- ✅ Maintain WBS (Work Breakdown Structure)
- ✅ Maintain Implementation Tracker
- ✅ Maintain SEO/Analytics checklist
- ✅ Maintain Learnings (this document)
- ✅ Maintain Deployment/Human Testing guides
- ✅ Put code snippets and common patterns in docs for reuse:
  - `bind_param` examples
  - Sanitizers
  - Error panel patterns

---

#### 1️⃣3️⃣.1️⃣ Learnings File Organization & Rich Markdown

> **🎯 Problem:** Learnings file became large and difficult to navigate, lacking visual hierarchy and quick reference sections.

> **✅ Solution:** Reorganized `LEARNINGS.md` and created `LEARNINGS-REORGANIZED.md` with rich markdown features.

##### 📋 Organization Structure

| Component                               | Purpose                                                        |
| --------------------------------------- | -------------------------------------------------------------- |
| **Table of Contents**                   | Comprehensive navigation with links to all sections            |
| **Quick Reference**                     | Canonical code patterns and common patterns index              |
| **Daily Learnings by Topic**            | Grouped learnings (SEO, Admin, UI/UX, Database, Documentation) |
| **Daily Learnings Log (Chronological)** | Historical entries preserved                                   |
| **Search & Quick Find**                 | Quick links to common searches                                 |

##### 🎨 Rich Markdown Features

| Feature               | Implementation                                          |
| --------------------- | ------------------------------------------------------- |
| **Header Badges**     | Status, Last Updated, Total Sections                    |
| **Collapsible TOC**   | Using `<details>` tag                                   |
| **Visual Hierarchy**  | Emoji headers (1️⃣, 2️⃣, 3️⃣, etc.)                        |
| **Callout Boxes**     | Key principles and warnings (`> **💡 Key Principle:**`) |
| **Rich Tables**       | Quick reference and comparisons                         |
| **Code Blocks**       | Syntax highlighting (PHP, SQL, JSON)                    |
| **Status Indicators** | ✅, ⚠️, 🔒, 📚                                          |
| **Checklists**        | Implementation checklists                               |
| **Centered Sections** | Important information highlighting                      |

##### 💡 Why This Matters

| Benefit               | Description                                    |
| --------------------- | ---------------------------------------------- |
| **Better Navigation** | Quick access to all sections                   |
| **Quick Reference**   | Common patterns at a glance                    |
| **Topic Grouping**    | Easy access to related learnings               |
| **Visual Hierarchy**  | Improved readability                           |
| **Enhanced UX**       | Rich markdown features improve user experience |
| **Reduced Clutter**   | Collapsible sections clean up interface        |

##### 📚 Lesson

- ✅ Organize learnings by both topic and chronology
- ✅ Use rich markdown features for better readability
- ✅ Create quick reference sections for common patterns
- ✅ Use tables for comparison and quick lookups
- ✅ Add search & quick find for discoverability
- ✅ Update organization structure as content grows

---

## 🎯 Quick Reference

### 📝 Canonical Code Patterns

#### Safe Input Sanitizer

```php
function sanitizeInput($input) {
    $input = trim($input);                    // Remove whitespace
    $input = strip_tags($input);              // Remove HTML tags
    $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input); // Remove control chars
    $input = preg_replace('/\s+/', ' ', $input); // Collapse whitespace
    return $input;
}
```

**Pattern:** `trim → strip_tags → remove control chars → collapse whitespace`

---

#### Safe Prepared Statements

```php
// Build $params array first
$params = [$status, $category, $limit];
$types = 'ssi';

// Then unpack
$stmt->bind_param($types, ...$params);
```

**Pattern:** `Build $params array → append paging → bind_param($types, ...$params)`

---

#### Approve Flow with Nullable FKs

```php
// Use NULLIF(?,0) and bind zero when unknown
$stmt = $conn->prepare("INSERT INTO table (fk_id) VALUES (NULLIF(?, 0))");
$fk_id = $unknown ? 0 : $actual_id; // DB stores NULL when 0
$stmt->bind_param('i', $fk_id);
```

**Pattern:** `Use NULLIF(?,0) and bind zero when unknown; DB stores NULL`

---

### 🔗 Common Patterns Index

| Pattern                           | Location                                                                   | Description                                                 |
| --------------------------------- | -------------------------------------------------------------------------- | ----------------------------------------------------------- |
| **Conditional Schema Generation** | [Section 8.1](#81-conditional-schema-generation-for-article-types)         | Multi-layer conditional system for article-specific schemas |
| **Article Visibility Debugging**  | [Section 8.2](#82-article-visibility-issues---database-status-fields)      | Diagnostic SQL for article visibility issues                |
| **Sports Content SEO**            | [Section 8.3](#83-sports-content-seo---multiple-structured-data-schemas)   | Multiple structured data schemas for sports articles        |
| **Learnings File Organization**   | [Section 13.1](#13-1-learnings-file-organization--rich-markdown)           | Rich markdown features for better documentation navigation  |
| **Modal Scroll Detection**        | [Daily Learnings - UI/UX & Modals](#uiux--modals)                          | Scroll velocity tracking for non-intrusive modals           |
| **Navigation Centralization**     | [Daily Learnings - Admin & Navigation](#admin--navigation)                 | Single source of truth for navigation metadata              |
| **JobPosting Schema**             | [Daily Learnings - Database & Data Management](#database--data-management) | Centralized schema generation for job listings              |
| **Sitemap Patterns**              | [Daily Learnings - SEO & Structured Data](#seo--structured-data)           | Sitemap index pattern and best practices                    |

---

## 📅 Daily Learnings by Topic

> **💡 Note:** Detailed chronological entries are preserved below. This section groups learnings by topic for quick reference.

---

### 🔍 SEO & Structured Data

#### Multiple Schema Types for Comprehensive SEO

- ✅ Use **NewsArticle, Person, SportsEvent, FAQPage, BreadcrumbList** schemas together
- ✅ Conditionally add schemas based on content type
- ✅ Validate with **Rich Results Test** before deployment

#### Conditional Schema Generation

- ✅ Multi-layer conditional system (file inclusion → data detection → content matching)
- ✅ Safe for all article types (zero impact on non-matching articles)
- ✅ Scalable pattern for future article types

#### Article Visibility Debugging

- ✅ Check `status`, `published_date`, `is_featured` fields
- ✅ Create diagnostic SQL for troubleshooting
- ✅ Hard refresh won't fix database issues

#### Sitemap Best Practices

- ✅ Use sitemap index pattern for multi-module sites
- ✅ Exclude admin files, processing files, test files
- ✅ Use canonical clean URLs only (no duplicates)
- ✅ Filter out alternate language versions
- ✅ Conduct systematic project-wide audits

---

### 🎛️ Admin & Navigation

#### Centralized Navigation Metadata

- ✅ Single source of truth for navigation (`admin/config/navigation.php`)
- ✅ Reusable across sidebar, dashboard, and future admin surfaces
- ✅ Support both modules and child actions (quick actions)

#### Admin UI Enhancements

- ✅ Sticky headers, filters, bulk actions
- ✅ Responsive design with mobile drawer
- ✅ Manual QA still essential for routing/auth validation

---

### 🎨 UI/UX & Modals

#### Scroll Velocity Detection

- ✅ Track scroll velocity in real-time using `requestAnimationFrame`
- ✅ Don't show modals during active scrolling
- ✅ Different delays for desktop vs mobile

#### Cross-Browser CSS Compatibility

- ✅ Use `opacity/visibility` instead of `display` for transitions
- ✅ Add vendor prefixes for transform animations
- ✅ Test specifically on mobile Safari

#### Modal Accessibility

- ✅ Always provide visible close button
- ✅ Support Escape key for keyboard users
- ✅ Use proper ARIA dialog attributes
- ✅ Lock body scroll on mobile Safari

---

### 💾 Database & Data Management

#### JobPosting Schema Data Quality

- ✅ Centralize schema generation in helper functions
- ✅ Derive postal data from locality strings
- ✅ Backfill `validThrough` safely with default values

#### Batch Import Workflows

- ✅ Run owner + listing inserts together
- ✅ Activate listings post-import with UPDATE query
- ✅ Use transactions for data integrity

#### Article Status Management

- ✅ Multiple fields affect visibility (`status`, `published_date`, `is_featured`)
- ✅ Create diagnostic SQL for troubleshooting
- ✅ Document visibility requirements clearly

---

### 📚 Documentation & Workflows

#### Documentation Organization

- ✅ Category-based structure (8 categories: analytics-seo, data-backend, content-projects, etc.)
- ✅ Complete file inventory in README
- ✅ AI-friendly structure with explicit navigation guides

#### Learnings File Organization

- ✅ Table of Contents for comprehensive navigation
- ✅ Quick Reference section for common patterns
- ✅ Daily Learnings by Topic (grouped by subject)
- ✅ Rich markdown features for better readability ([Section 13.1](#13-1-learnings-file-organization--rich-markdown))
- ✅ Collapsible sections, tables, callout boxes
- ✅ Status indicators and checklists

#### Workflow Playbooks

- ✅ Feature-specific workflow documents
- ✅ Diagram both happy paths and recovery routes
- ✅ Cross-link for discoverability

---

## 📅 Daily Learnings Log (Chronological)

> **📖 Detailed chronological entries preserved for historical reference. For topic-based quick reference, see [Daily Learnings by Topic](#-daily-learnings-by-topic) above.**

---

### 📅 November 15, 2025 - R Karthika Article SEO & Rich Snippets + Documentation Reorganization

<div align="center">

**Project:** MyOMR News Article System & Documentation  
**Session Focus:** R Karthika kabaddi article creation, comprehensive SEO implementation, sports-specific structured data, and learnings file reorganization

</div>

#### 📋 Summary

**1. R Karthika Article:**

- ✅ Created comprehensive 1800+ word news article about R Karthika and U-18 Girls' Kabaddi Team victory
- ✅ Implemented advanced SEO with 5 structured data schemas (NewsArticle, Person, SportsEvent, FAQPage, BreadcrumbList)
- ✅ Enhanced article system with sports-specific SEO enhancements (conditional, safe for all articles)
- ✅ Created comprehensive SEO documentation and ranking strategy

**2. Documentation Reorganization:**

- ✅ Reorganized `LEARNINGS.md` with improved structure and navigation
- ✅ Created `LEARNINGS-REORGANIZED.md` with rich markdown features

#### 🎯 Key Learnings

- ✅ **Multi-layer conditional schema generation** for article types ([Section 8.1](#81-conditional-schema-generation-for-article-types))
- ✅ **Article visibility debugging** - database status fields ([Section 8.2](#82-article-visibility-issues---database-status-fields))
- ✅ **Sports content SEO** with multiple structured data schemas ([Section 8.3](#83-sports-content-seo---multiple-structured-data-schemas))
- ✅ **Learnings file organization** - rich markdown features for better navigation ([Section 13.1](#13-1-learnings-file-organization--rich-markdown))

#### 📚 Related Documentation

- 📄 **Detailed Worklog:** [`docs/worklogs/worklog-15-11-2025.md`](docs/worklogs/worklog-15-11-2025.md)
- 📄 **SEO Strategy:** [`docs/analytics-seo/GOOGLE-RANKING-STRATEGY-R-KARTHIKA.md`](docs/analytics-seo/GOOGLE-RANKING-STRATEGY-R-KARTHIKA.md)
- 📄 **Safety Documentation:** [`docs/analytics-seo/SPORTS-SEO-ENHANCEMENT-SAFETY.md`](docs/analytics-seo/SPORTS-SEO-ENHANCEMENT-SAFETY.md)
- 📄 **Learnings File:** [`LEARNINGS.md`](../LEARNINGS.md) (Reorganized structure)
- 📄 **Learnings Reorganized:** [`LEARNINGS-REORGANIZED.md`](../LEARNINGS-REORGANIZED.md) (Rich markdown version)

#### 📊 Files Created/Modified

| Type               | Count | Examples                                                                   |
| ------------------ | ----- | -------------------------------------------------------------------------- |
| **New Files**      | 11    | `article-sports-seo-enhancement.php`, SEO docs, `LEARNINGS-REORGANIZED.md` |
| **Modified Files** | 3     | `article.php`, SQL file, `LEARNINGS.md`                                    |

---

> **📝 Note:** The rest of the chronological daily learnings entries follow below. See specific dates in the original file structure.

---

## 🔍 Search & Quick Find

### Common Searches

| Search Term                | Quick Links                                                                                                                                  |
| -------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------- |
| **Structured Data**        | [Section 8.1](#81-conditional-schema-generation-for-article-types), [Section 8.3](#83-sports-content-seo---multiple-structured-data-schemas) |
| **Article Issues**         | [Section 8.2](#82-article-visibility-issues---database-status-fields)                                                                        |
| **SEO Patterns**           | [Section 7](#7-seo--analytics), [Section 8.3](#83-sports-content-seo---multiple-structured-data-schemas)                                     |
| **Admin Navigation**       | [Daily Learnings - Admin & Navigation](#admin--navigation)                                                                                   |
| **Modal Patterns**         | [Daily Learnings - UI/UX & Modals](#uiux--modals)                                                                                            |
| **Database Patterns**      | [Section 3](#3-mysqli--sql-hygiene), [Daily Learnings - Database & Data Management](#database--data-management)                              |
| **JobPosting Schema**      | [Daily Learnings - Database & Data Management](#database--data-management)                                                                   |
| **Sitemap Patterns**       | [Daily Learnings - SEO & Structured Data](#seo--structured-data)                                                                             |
| **Learnings Organization** | [Section 13.1](#13-1-learnings-file-organization--rich-markdown)                                                                             | Rich markdown features for documentation navigation |

---

<div align="center">

---

**Last Updated:** November 15, 2025  
**Total Learnings Entries:** 13 core sections + daily learnings by topic  
**Maintenance:** Update after each significant work session  
**Status:** ✅ Active

---

_This document is continuously updated with new learnings and best practices._

</div>
