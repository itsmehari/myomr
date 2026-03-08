# TODO

## Current Status

- Homepage redesign completed and live
- CSS consolidation underway (migrating all styles to `assets/css/main.css`)
- Documentation system in place and up to date
- Most PHP/HTML files reference new main CSS (some pending)

## Immediate Next Steps

- [ ] Update all PHP/HTML files to reference only `assets/css/main.css`
- [ ] Remove redundant/old CSS links from all files
- [ ] Document all dependencies and shared components in `docs/ARCHITECTURE.md`
- [x] Complete migration of directory/listing pages to `omr-listings/` (June 2024)
- [x] All links, includes, and dependencies checked—no errors found
- [x] Group business ads, job postings, real estate, and education/class files into `listings/` (June 2024)
- [x] All links and navigation updated, deleted files removed
- [x] Migrate all news and community-related files to `local-news/` (June 2024)
- [x] All links and navigation updated, deleted files removed

## Bug Fixes Needed (from error log review - Dec 2024)

**Database Issues:**

- [ ] Create or fix `businesses` table in database
  - Table is referenced in index.php and news pages
  - Currently causing fatal errors
  - See: docs/ERROR_LOG_REVIEW.md

**Component File Issues:**

- [ ] Verify all component files exist in `/components/`
  - Missing: admin-sidebar.php, admin-header.php, admin-breadcrumbs.php, admin-flash.php
  - Missing: myomr-nav-bar.html (check if renamed or moved)
  - Update include paths if components were moved

**Session/Header Issues:**

- [ ] Fix session headers in `admin/dashboard.php`
  - Remove any output before `session_start()` (line 3)
  - Check for BOM characters at file start
  - Ensure no whitespace before <?php

**Minor Issues:**

- [ ] Add isset() check for HTTP_REFERER in `local-news/weblog/log.php` (line 4)

**Testing After Fixes:**

- [ ] Test admin panel login and dashboard
- [ ] Test business directory features
- [ ] Test local news pages
- [ ] Monitor for new error logs

## Upcoming Tasks

- [ ] Modularize directory pages (banks, ATMs, IT companies, etc.)
  - Create a single template for directory pages
  - Centralize data for easier updates and maintenance
- [ ] Review and refactor any remaining inline or page-specific CSS
- [ ] Continue to update documentation as changes are made

## Notes

- All progress and changes are being tracked in this file and `CHANGELOG.md` for continuity.
- See `ONBOARDING.md` for how to resume work after a break.

## Recent Progress (as of June 2024)

- Homepage (`index.php`) redesigned for modern, mobile-friendly, modular layout; dynamic PHP logic preserved.
- Documentation system established: `README.md`, `CHANGELOG.md`, `ONBOARDING.md`, `ARCHITECTURE.md`, `TODO.md`, and `context.md`.
- CSS consolidation started: `assets/css/main.css` created, footer styles migrated, other sections in progress.

This file tracks ongoing tasks, feature ideas, and technical debt. Please update as you work on or complete items.

## Ongoing Tasks

- [ ] Refactor legacy PHP scripts into Laravel where possible
- [ ] Move all public assets to public_html/assets/
- [ ] Harden .htaccess for security
- [ ] Optimize images and minify CSS/JS
- [ ] Expand documentation in docs/
- [ ] Add more unit/feature tests

## Feature Ideas

- [ ] User registration and profiles
- [ ] Community forums or discussion boards
- [ ] Enhanced event calendar
- [ ] Business/service review system

## Technical Debt

- [ ] Audit and update all file paths after restructuring
- [ ] Remove unused or duplicate files

---

Add, update, or check off items as the project evolves!
