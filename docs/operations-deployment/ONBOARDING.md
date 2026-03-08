# Onboarding Guide for MyOMR.in

Welcome to the MyOMR.in project! This guide will help you get started as a contributor or maintainer.

## Where to Start

- **Project Overview:** See [`README.md`](../README.md) for a summary of the project and its key features.
- **Project Context:** [`context.md`](../context.md) is the living source of truth for structure, best practices, and ongoing decisions.
- **Change History:** [`CHANGELOG.md`](../CHANGELOG.md) tracks major changes and migrations.

## Setup Basics

1. Ensure you have access to a cPanel/Linux hosting environment with PHP and MySQL.
2. Clone or download the repository.
3. Place public files in `public_html/` and sensitive files outside it if possible.
4. Configure your database connection as described in `context.md`.
5. Test locally before deploying to production.

## Contributing

- Follow the checklists and best practices in `context.md`.
- Document any significant changes in `CHANGELOG.md` and `context.md`.
- Ask questions if anything is unclear—collaboration is encouraged!

## Need Help?

- Contact the project lead at myomrnews@gmail.com
- Or open an issue in the repository (if using version control)

---

## Recent Progress & Next Steps (as of June 2024)

- **Homepage Redesign:** The main homepage (`index.php`) was redesigned for a modern, mobile-friendly, modular layout. All dynamic PHP logic was preserved and integrated.
- **Documentation System:** Comprehensive documentation was established (`README.md`, `CHANGELOG.md`, `docs/ONBOARDING.md`, `docs/ARCHITECTURE.md`, `docs/TODO.md`). `context.md` is the living source of truth for project context and structure.
- **CSS Consolidation:** All custom CSS is being merged into `assets/css/main.css`. Footer styles have been migrated; other sections are in progress. All HTML/PHP files will be updated to reference only the new main CSS file.
- **Directory Pages Modularization:** Plan to modularize OMR directory pages (banks, ATMs, IT companies, hospitals, parks, etc.) using a single template and centralized data for easier maintenance.
- **Dependency Map:** A full dependency map will be generated after CSS consolidation.

**If you are returning after a break, check `context.md` and this section for the latest project status and next steps.**

Let's build a better OMR community together!

## Note on Directory Pages (June 2024)

- All OMR directory/listing pages have been moved to the `omr-listings/` folder.
- All navigation, internal, and cross-links have been updated and verified.
- All dependencies and includes are up to date and error-free as of June 2024.
- See `docs/ARCHITECTURE.md` and `docs/CHANGELOG.md` for details.

## Note on Listings Folder (June 2024)

- All business ads, job postings, real estate/rental, and education/class files are now grouped in the `listings/` folder.
- All navigation, internal, and cross-links have been updated and verified.
- All dependencies and includes are up to date and error-free as of June 2024.
- See `docs/ARCHITECTURE.md` and `docs/CHANGELOG.md` for details.

## Note on Local News Folder (June 2024)

- All news, community, and gallery files are now grouped in the `local-news/` folder.
- All navigation, internal, and cross-links have been updated and verified.
- All dependencies and includes are up to date and error-free as of June 2024.
- See `docs/ARCHITECTURE.md` and `docs/CHANGELOG.md` for details.
