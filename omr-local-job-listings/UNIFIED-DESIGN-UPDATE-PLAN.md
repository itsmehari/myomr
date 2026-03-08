# Unified Modern Design Update Plan

## Pages to Update

1. ✅ **post-job-omr.php** - Partially done (Company section modernized)

   - Need to update: Job Details section, Review & Submit section

2. **index.php** - Job listings page

   - Add modern hero section
   - Update job cards to use modern styling
   - Modernize search/filter UI

3. **job-detail-omr.php** - Single job view

   - Modern hero section
   - Modern job detail card
   - Modern application form

4. **job-posted-success-omr.php** - Success page

   - Modern success card design
   - Icon animation
   - Modern buttons

5. **application-submitted-omr.php** - Application success

   - Modern success card
   - Consistent with job-posted-success

6. **employer-login-omr.php** - Login page

   - Modern login card
   - Form styling consistent with post-job

7. **my-posted-jobs-omr.php** - Employer dashboard
   - Modern dashboard cards
   - Job list cards

## Design System Components

### CSS Files

- `post-job-form-modern.css` - Form-specific modern styles ✅
- `omr-jobs-unified-design.css` - Shared components (needs fixes)
- Both should be included in all pages

### Common Classes to Use

- `.hero-modern` or `.hero-section` - Hero sections
- `.card-modern` - Base card component
- `.job-card-modern` - Job listing cards
- `.form-section` - Form sections with icon headers
- `.btn-modern`, `.btn-modern-primary`, `.btn-modern-secondary` - Buttons
- `.form-control-modern`, `.form-select-modern` - Form inputs
- `.alert-modern` - Info alerts
- `.success-card-modern` - Success pages
- `.security-badge` - Security messaging

### Required Head Elements

- Google Fonts (Inter)
- Bootstrap 5.3.0
- Font Awesome 6.0.0
- `job-listings-omr.css` (base styles)
- `post-job-form-modern.css` (modern form components)
- `omr-jobs-unified-design.css` (shared components)
