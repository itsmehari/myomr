# MyOMR News Workflow — End-to-End Process Map

**Purpose:** Capture the complete news lifecycle from idea to Google Search Console and live testing, for future automation planning.  
**Last updated:** March 2025  
**Owner:** Editorial Team

## 1. High-Level Process Overview

```mermaid
graph TB
    subgraph IDEA["1. Idea and Planning"]
        A1[News idea or assignment] --> A2[Editorial brief]
        A2 --> A3[Research and sources]
        A3 --> A4[Keywords and OMR angle]
    end
    subgraph DRAFT["2. Drafting"]
        B1[Write article in docs or editor] --> B2[Prepare hero image 1200x630]
        B2 --> B3[Create HTML content]
        B3 --> B4[Optional Tamil translation]
    end
    subgraph PUBLISH["3. Publication"]
        C1[Create SQL insert file] --> C2[Run SQL in phpMyAdmin]
        C2 --> C3[Upload image to omr-news-images]
        C3 --> C4[Verify article live at URL]
    end
    subgraph SEO["4. SEO and Discovery"]
        D1[Sitemap includes article auto] --> D2[Ping Search Console or request indexing]
        D2 --> D3[Verify meta tags and JSON-LD]
    end
    subgraph QA["5. QA and Live Testing"]
        E1[Test homepage card appears] --> E2[Test Read More link]
        E2 --> E3[Test share previews]
        E3 --> E4[Test Tamil link if applicable]
    end
    subgraph PROMOTE["6. Promotion"]
        F1[Social channels] --> F2[Newsletter]
        F2 --> F3[Worklog entry]
    end
    IDEA --> DRAFT --> PUBLISH --> SEO --> QA --> PROMOTE
```

---

## 2. Detailed Phase Flow

### Phase 1: Idea to Draft Approval

```mermaid
graph TD
    Start([News trigger]) --> T1{Source type}
    T1 -->|Editorial calendar| P1[Assigned story]
    T1 -->|Breaking news| P2[Monitor and capture]
    T1 -->|Community tip| P3[Verify and brief]
    P1 --> D1[Create editorial plan]
    P2 --> D1
    P3 --> D1
    D1 --> D2[Capture in docs content-projects]
    D2 --> D3[Keywords and target audience]
    D3 --> D4[Required visuals list]
    D4 --> D5{Editor greenlight}
    D5 -->|No| H1[Hold or drop]
    D5 -->|Yes| Phase2[Proceed to Drafting]
    H1 --> End1([Closed])
```

### Phase 2: Drafting and Asset Preparation

```mermaid
graph TD
    D2S[Drafting start] --> W1[Write article body]
    W1 --> W2[Add OMR-specific angle]
    W2 --> W3[Cite sources]
    W3 --> W4{Need Tamil version}
    W4 -->|Yes| W5[Translate title summary content]
    W4 -->|No| W6[Prepare assets]
    W5 --> W6
    W6 --> W7[Hero image 1200x630 for OG]
    W7 --> W8[Store in omr-news-images]
    W8 --> W9[Convert content to HTML]
    W9 --> W10{Draft approved}
    W10 -->|No| W11[Revise and resubmit] --> W1
    W10 -->|Yes| Phase3[Proceed to Publication]
```

### Phase 3: SQL Publication Pipeline

```mermaid
graph TD
    P3S[Publication start] --> S1[Create ADD-TOPIC-ARTICLE.sql]
    S1 --> S2[Set slug unique and URL-safe]
    S2 --> S3[Insert title summary content]
    S3 --> S4[Set published_date author category tags]
    S4 --> S5[Set image_path is_featured status]
    S5 --> S6[Escape single quotes in content]
    S6 --> S7{Create Tamil version}
    S7 -->|Yes| S8[Create ADD-TOPIC-ARTICLE-TAMIL.sql]
    S7 -->|No| S9[Run English SQL in phpMyAdmin]
    S8 --> S9
    S9 --> S10{Has Tamil}
    S10 -->|Yes| S11[Run Tamil SQL in phpMyAdmin]
    S10 -->|No| S12[Upload image if new]
    S11 --> S12
    S12 --> S13[Verify at local-news slug]
    S13 --> Phase4[Proceed to SEO]
```

### Phase 4: Sitemap and Search Console

```mermaid
graph TD
    SEO1[SEO phase start] --> SEO2[Article in articles table]
    SEO2 --> SEO3{How is sitemap built}
    SEO3 -->|Dynamic from DB| SEO4[generate-articles-sitemap includes articles]
    SEO3 -->|Module sitemap| SEO5[local-news sitemap if configured]
    SEO4 --> SEO6[Articles auto-included in sitemap]
    SEO5 --> SEO6
    SEO6 --> SEO7[Sitemap index at myomr.in]
    SEO7 --> SEO8{Articles sitemap in index}
    SEO8 -->|Not yet| SEO9[Add articles-sitemap to generate-sitemap-index]
    SEO8 -->|Yes| SEO10[Submit sitemap in Search Console]
    SEO9 --> SEO10
    SEO10 --> SEO11[Request indexing for new article URL]
    SEO11 --> SEO12[Verify meta tags via View Page Source]
    SEO12 --> Phase5[Proceed to QA]
```

### Phase 5: Live Testing Checklist

```mermaid
graph TD
    QA1[QA phase start] --> QA2[Homepage loads]
    QA2 --> QA3[News card shows new article]
    QA3 --> QA4[Image displays correctly]
    QA4 --> QA5[Read More link works]
    QA5 --> QA6[Article page loads at clean URL]
    QA6 --> QA7[Related articles section shows]
    QA7 --> QA8{Tamil version}
    QA8 -->|Yes| QA9[Tamil link appears on English page]
    QA8 -->|No| QA10[Share preview test]
    QA9 --> QA10
    QA10 --> QA11[Facebook Debugger or Twitter Card Validator]
    QA11 --> QA12[Accessibility check headings alt text]
    QA12 --> QA13{All passed}
    QA13 -->|No| QA14[Fix and re-verify] --> QA2
    QA13 -->|Yes| Phase6[Proceed to Promotion]
```

---

## 3. System Component Map

```mermaid
graph LR
    subgraph INPUTS["Inputs"]
        I1[Editorial brief]
        I2[Hero image]
        I3[HTML content]
    end
    subgraph DB["Database"]
        DB1[(articles table)]
    end
    subgraph FILES["Key Files"]
        F1[dev-tools sql ADD-ARTICLE.sql]
        F2[home-page-news-cards.php]
        F3[local-news article.php]
        F4[article-seo-meta.php]
        F5[generate-articles-sitemap.php]
    end
    subgraph OUTPUTS["Outputs"]
        O1[Homepage news cards]
        O2[Article page at local-news slug]
        O3[Sitemap XML]
        O4[Google Search Console]
    end
    I1 --> F1
    I2 --> I3
    I3 --> F1
    F1 --> DB1
    DB1 --> F2
    DB1 --> F3
    DB1 --> F5
    F2 --> O1
    F3 --> O2
    F4 --> O2
    F5 --> O3
    O3 --> O4
```

---

## 4. URL and Routing Flow

```mermaid
sequenceDiagram
    participant User
    participant Homepage
    participant Cards
    participant DB
    participant Htaccess
    participant Article
    User->>Homepage: Visit index.php
    Homepage->>Cards: Include home-page-news-cards
    Cards->>DB: SELECT from articles
    DB-->>Cards: Rows with slug title summary
    Cards-->>Homepage: Render cards with href
    Homepage-->>User: Show news cards
    User->>Homepage: Click Read More
    Homepage->>User: Navigate to local-news slug
    User->>Htaccess: Request local-news article-slug
    Htaccess->>Htaccess: RewriteRule to article.php
    Htaccess->>Article: Invoke article.php with slug
    Article->>DB: SELECT from articles
    DB-->>Article: Full article row
    Article->>Article: Include article-seo-meta
    Article-->>User: Render full article with SEO
```

---

## 5. Automation Readiness Matrix

| Phase | Step | Current | Automatable? | Blocker |
|-------|------|---------|--------------|---------|
| 1 | Editorial brief | Manual doc | Partial | Editorial judgment |
| 2 | Draft writing | Manual | Partial | AI assist possible |
| 3 | SQL file creation | Manual | **Yes** | Template + content merge |
| 4 | phpMyAdmin run | Manual | **Yes** | CLI mysql or API |
| 5 | Image upload | Manual FTP/cPanel | **Yes** | File API |
| 6 | Sitemap refresh | Auto from DB | N/A | Already dynamic |
| 7 | Search Console ping | Manual | **Yes** | Indexing API |
| 8 | QA testing | Manual | Partial | Playwright/Cypress |

---

## 6. Automation Pipeline Concept (Future)

```mermaid
graph TB
    subgraph TRIGGER["Trigger"]
        T1[New article in docs or form]
        T2[Webhook or scheduled job]
    end
    subgraph AUTOMATE["Automation Steps"]
        A1[Parse content and metadata]
        A2[Generate SQL from template]
        A3[Execute SQL via mysql CLI or PDO]
        A4[Upload image via cPanel API or FTP]
        A5[Ping sitemap or request indexing]
        A6[Run headless browser QA]
    end
    subgraph NOTIFY["Notifications"]
        N1[Slack or email on success]
        N2[Error alert on failure]
        N3[Search Console status report]
    end
    T1 --> A1
    T2 --> A1
    A1 --> A2 --> A3 --> A4 --> A5 --> A6
    A6 --> N1
    A3 -->|Error| N2
    A5 --> N3
```

---

## 7. Sitemap Configuration Gap (As of March 2025)

The main sitemap index (`weblog/generate-sitemap-index.php` or root `generate-sitemap-index.php`) does **not** yet include the articles sitemap. To expose new articles to Google:

1. **Add .htaccess rule** (if missing):
   ```apache
   RewriteRule ^weblog/articles-sitemap\.xml$ weblog/generate-articles-sitemap.php [L]
   ```
2. **Add to sitemap index** in `generate-sitemap-index.php`:
   ```php
   $base . '/weblog/articles-sitemap.xml',
   ```
3. Alternatively, articles may be included in a monolithic sitemap (`@tocheck/sitemap-generator.php`) — verify which generator is used in production.

---

## 8. File Reference Quick List

| Purpose | File Path |
|---------|-----------|
| News cards on homepage | `weblog/home-page-news-cards.php` or `home-page-news-cards.php` |
| Article router | `local-news/article.php` |
| SEO meta generator | `core/article-seo-meta.php` |
| SQL examples | `dev-tools/sql/ADD-*-ARTICLE.sql` |
| Articles sitemap | `weblog/generate-articles-sitemap.php` |
| Sitemap index | `weblog/generate-sitemap-index.php` |
| URL rewrite | `.htaccess` lines 228-232 |
| News images | `local-news/omr-news-images/` |

---

## 9. Mermaid Compatibility Notes

This document uses Mermaid diagram types optimized for MD viewer compatibility:

- `graph` (TB, TD, LR) — legacy keyword, broad viewer support
- `sequenceDiagram` — for request/response flows
- Ampersands replaced with "and" to avoid XML/HTML parsing issues
- Question marks removed from node labels where they caused parse errors

**Avoided:** `erDiagram`, `flowchart` (use `graph`), special chars in labels. See `docs/MERMAID-AND-CHROME-EXTENSION-TROUBLESHOOTING.md` if diagrams still do not render.
