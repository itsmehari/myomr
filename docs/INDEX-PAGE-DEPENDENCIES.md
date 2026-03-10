# Index.php Dependencies — Latest vs Legacy

## Latest (Active) — Files Used by index.php

| File | Role |
|------|------|
| `index.php` | Main homepage |
| `core/include-path.php` | Defines ROOT_PATH for includes |
| `components/css-includes.php` | Common CSS (Font Awesome, megamenu, main) |
| `components/homepage-header.php` | Nav wrapper → loads megamenu or main-nav |
| `components/megamenu-myomr.php` | Primary nav (full megamenu) |
| `core/megamenu-config.php` | Megamenu structure |
| `components/main-nav.php` | Fallback nav (if megamenu missing) |
| `components/homepage-jobs-scroll-banner.php` | Jobs ticker |
| `components/myomr-news-bulletin.php` | News layout (hero + 2 medium + 9 sidebar) |
| `components/featured-news-links.php` | Editor's picks |
| `components/footer.php` | Footer |
| `assets/css/megamenu-myomr.css` | Nav styles |
| `assets/css/homepage-myomr.css` | Homepage sections, subscribe, jobs banner |
| `assets/css/main.css` | Global + footer |
| `assets/css/myomr-news-bulletin.css` | News bulletin (loaded by component) |
| `assets/css/featured-news-links.css` | Editor's picks (loaded by component) |
| `core/omr-connect.php` | DB connection (for jobs, subscribe) |

## Legacy (Obsolete for index.php)

| File | Status | Replaced By |
|------|--------|-------------|
| `weblog/home-page-news-cards.php` | **REMOVED** | `components/myomr-news-bulletin.php` |
| `weblog/home-page-news-cards-SHOW-ALL.php` | **REMOVED** | `components/myomr-news-bulletin.php` |

## Dead CSS (Removed from homepage-myomr.css)

- `.homepage-news`, `.homepage-news-scroll-*` — old scrollable news grid (used by home-page-news-cards)
- `.homepage-categories`, `.homepage-category-card` — category grid (not used by current index)

## Backup / Dev Files (Keep)

- `index-minimal-backup.php` — minimal test page for debugging
