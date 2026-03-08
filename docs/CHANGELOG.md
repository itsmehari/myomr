## [Unreleased]

### Added

- Modern homepage redesign completed in `index.php` (all dynamic PHP logic preserved)
- CSS consolidation started: all styles being migrated to `assets/css/main.css`
- Documentation system established: `README.md`, `CHANGELOG.md`, `docs/ONBOARDING.md`, `docs/ARCHITECTURE.md`, `docs/TODO.md`
- Progress and next steps now tracked in `docs/`
- Created `omr-listings/` folder for all directory/listing pages (hospitals, IT companies, etc.)
- Started migration of directory/listing pages into new folder

### Changed

- Updated all PHP/HTML files to reference only `assets/css/main.css` (in progress)
- Began modularization plan for directory pages

### Next Steps

- Complete CSS reference update in all files
- Generate and maintain dependency map in `docs/ARCHITECTURE.md`
- Modularize directory pages using a single template and centralized data

- Migration of all directory/listing pages to `omr-listings/` is now complete (June 2024)
- All internal, cross, and navigation links updated and verified
- No broken links, missing includes, or dependency errors found after migration
- Grouped all business ads, job postings, real estate, and education/class files into the new `listings/` folder (June 2024)
- Updated all cross-links and navigation to reflect new locations
- Removed deleted files: `tutions-classes-courses-training-centers-in-omr-chennai.php`, `digital-marketing-junior-job-vacancy-cleanbios-guindy.php`
- Verified no broken links or missing dependencies after migration
