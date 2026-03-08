## GA Reporting Setup — Funnels and Weekly KPIs (MyOMR)

This guide defines the GA4 reporting configuration for the directory and jobs funnels. It maps implemented events to conversions and outlines saved Explorations and weekly KPI views.

### 1) Prerequisites
- GA4 property access with Edit permissions
- gtag installed via `components/analytics.php` on pages
- Use GA DebugView to verify events after publishing changes

### 2) Events implemented (Directory: IT Companies)
- List page events
  - `search_submit` (params: `q`, `locality`, `sort`, `event_category='it_companies_list'`)
  - `pagination_click` (params: `page`, `event_category='it_companies_list'`)
  - `map_click` (params on list: `company_id`, `company_name`, `event_category='it_companies_list'`)
  - `enquire_click` (params on list: `company_id`, `company_name`, `event_category='it_companies_list'`)
- Detail page events
  - `map_click` (params: `company_id`, `company_name`, `event_category='it_company_detail'`)
  - `enquire_click` (params: `company_id`, `company_name`, `event_category='it_company_detail'`)
- Submission events (Get Listed)
  - `listing_submission_start` (params: `desired_tier`, `event_category='it_company_submit'`, `event_label=company_name`)
  - `listing_submission_success` (params: `desired_tier`, `event_category='it_company_submit'`, `event_label=company_name`)
- Admin events (CMS)
  - `admin_approve_submission` (params: `company_id`, `company_name`, `featured`, `rank`)
  - `admin_reject_submission` (params: `submission_id`)
  - `admin_feature_update` (params: `featured_id`, `rank`)
  - `admin_feature_delete` (params: `featured_id`)

Note: Jobs module has its own set; align naming when extending.

### 3) Mark these as Conversions
- `listing_submission_success` (primary conversion)
- Optional: `enquire_click` (lead intent)

### 4) Custom definitions (GA4 > Configure > Custom Definitions)
Create the following event-scoped dimensions (case-sensitive matches to parameters):
- `company_id`
- `company_name`
- `desired_tier`
- `locality` (if sent on list filters)
- `event_category` (for easy filtering by page context)

### 5) Explorations to Save
Create and save the following Explorations (GA4 > Explore). Use date range “Last 28 days” by default and keep a comparison for “Last period”.

1. IT Funnel — List → Detail → Enquire → Submit Start → Submit Success
   - Technique: Funnel exploration (open)
   - Steps (by event name):
     1) page_view where `page_location` contains `/it-companies` (List)
     2) page_view where `page_location` matches `/it-companies/` detail (contains `/it-companies/` and hyphen-id)
     3) `enquire_click`
     4) `listing_submission_start`
     5) `listing_submission_success`
   - Breakdowns: `locality`, `desired_tier`, `company_id`
   - Filters: `event_category` contains `it_`

2. Top Localities by Conversion
   - Technique: Free form
   - Rows: `locality`
   - Columns: `desired_tier` (optional)
   - Values: `listing_submission_success` event count; rate = success / list views

3. Featured vs Non-Featured Performance
   - Technique: Free form
   - Segments: Featured (company_id in featured list via import or use BigQuery joining later), Non-Featured
   - Values: detail views, `enquire_click`, `listing_submission_success`

4. Search Quality
   - Technique: Free form
   - Rows: `q` (search term param)
   - Values: `search_submit` count, downstream `detail views`, `enquire_click`

5. Weekly KPI Board
   - Technique: Free form or Overview summary
   - Metrics: Users, Sessions, IT list views, IT detail views, `enquire_click`, `listing_submission_success`, conversion rate
   - Breakdown: Week (ISO week), `locality`

Naming: Prefix with `MyOMR –` (e.g., `MyOMR – IT Funnel`).

### 6) Weekly KPI checklist (every Monday)
- Export weekly metrics (Users, Sessions, List Views, Detail Views, Enquiries, Submissions, Conversion Rate)
- Segment by `locality`
- Call out top/bottom performing localities and companies
- Track Featured vs Non-Featured lift

### 7) Optional: BigQuery/Data API (next phase)
- Export GA4 to BigQuery; join with featured roster for precision
- Scheduled SQL to compute weekly KPIs and email via Looker Studio

### 8) QA Steps
- Open site with `?gtm_debug=x` or GA DebugView
- Trigger list search, detail clicks, enquiries, submissions
- Verify parameters and ensure `listing_submission_success` is marked as Conversion


