# Phase 5 — GA4 Advanced Reporting & Looker Studio
**MyOMR.in | Google Analytics 4 Implementation**

---

## 1. Looker Studio Dashboard Setup

### Step 1 — Connect GA4 as a Data Source
1. Go to [lookerstudio.google.com](https://lookerstudio.google.com) → **Create** → **Report**
2. Search for "**Google Analytics 4**" connector → Select your **MyOMR GA4 property** (`G-JYSF141J1H`)
3. Click **Add to Report**

### Step 2 — Recommended Scorecard Metrics (Header Row)
| Metric | Dimension | Description |
|---|---|---|
| Total Users | — | All site visitors |
| Engaged Sessions | — | Sessions with >10s or 2+ page views |
| Engagement Rate | — | % engaged sessions |
| Conversions | event_name = `job_applied` | Job applications |
| Conversions | event_name = `generate_lead` | All inquiry leads |
| Conversions | event_name = `subscribe` | Newsletter sign-ups |

### Step 3 — Recommended Charts

**Chart 1: Traffic by Content Group (Pie)**
- Dimension: `content_group`
- Metric: `Sessions`
- Shows: which section of the site drives the most traffic

**Chart 2: Job Board Funnel (Table)**
| Step | Event | What it measures |
|---|---|---|
| 1 | `page_view` on `/omr-local-job-listings/` | Board visits |
| 2 | `page_view` on job detail | Listing views |
| 3 | `form_start` (form_name = post_job) | Form engagement |
| 4 | `job_applied` | Completed applications |

**Chart 3: Locality Heatmap (Table)**
- Dimension: `locality` (custom dimension)
- Metric: `Sessions`, `Conversions`
- Shows: which OMR localities drive most leads

**Chart 4: Listing Type Performance (Bar)**
- Dimension: `listing_type` (custom dimension)
- Metric: `Sessions`, `phone_call`, `get_directions`
- Shows: which directory category drives most CTAs

**Chart 5: Search Terms (Table)**
- Dimension: `search_term`
- Metric: `Event count` (filter: `event_name = search`)
- Shows: top search queries on job board and events

---

## 2. GA4 Funnel Explorations

### Funnel A — Job Application Funnel
In GA4 → **Explore** → **Funnel exploration** → Create new

| Step | Condition |
|---|---|
| 1 | Event name = `page_view` AND page_location contains `/omr-local-job-listings/` |
| 2 | Event name = `page_view` AND page_location contains `job-detail-omr` |
| 3 | Event name = `form_start` AND form_name = `post_job` |
| 4 | Event name = `job_applied` |

**Breakdown by:** `job_category` custom dimension  
**Goal:** Measure drop-off at each step → identify where users abandon the flow

---

### Funnel B — Event Submission Funnel

| Step | Condition |
|---|---|
| 1 | Event name = `page_view` AND page_location contains `/omr-local-events/` |
| 2 | Event name = `page_view` AND page_location contains `post-event-omr` |
| 3 | Event name = `form_start` AND form_name = `post_event` |
| 4 | Event name = `generate_lead` AND conversion_type = `event_submission` |

---

### Funnel C — Coworking Space Inquiry Funnel

| Step | Condition |
|---|---|
| 1 | Event name = `page_view` AND content_group = `Coworking Spaces` |
| 2 | Event name = `page_view` AND page_location contains `space-detail` |
| 3 | Event name = `form_start` AND form_name = `add_coworking_space` |
| 4 | Event name = `generate_lead` AND conversion_type = `space_inquiry` |

---

### Funnel D — Employer Registration Funnel

| Step | Condition |
|---|---|
| 1 | Event name = `page_view` AND page_location contains `employer-landing` |
| 2 | Event name = `page_view` AND page_location contains `employer-register` |
| 3 | Event name = `sign_up` AND method = `employer_registration` |
| 4 | Event name = `job_posted` |

---

## 3. GA4 Audience Segments (for Remarketing)

In **GA4 Admin → Audiences**, create:

| Audience Name | Definition | Use For |
|---|---|---|
| **Active Job Seekers** | viewed job-detail in last 30 days + NOT converted | Google Ads retargeting |
| **Employer Prospects** | visited employer-landing but did NOT register | Nurture campaigns |
| **Event Enthusiasts** | viewed 3+ event detail pages in 30 days | Event promotion ads |
| **High-Intent Listing Viewers** | phone_call OR get_directions in last 14 days | Local service ads |
| **Newsletter Subscribers** | event = subscribe | Exclusion list (already converted) |

---

## 4. Weekly Alert Setup (GA4 Anomaly Detection)

In GA4 → **Home** → turn on **Insights & Alerts**:

- Alert: Sessions drop >30% week-over-week → investigate traffic issue
- Alert: Conversions drop >20% → investigate form/UX issue
- Alert: 404 events spike → investigate broken links/deployments

---

## 5. Google Search Console Integration

1. **GA4 Admin → Property Settings → Search Console Links** → Add
2. Select your Search Console property for `myomr.in`
3. Wait 48h → data appears in GA4 under **Reports → Acquisition → Search Console**

Benefits:
- See which organic keywords drive traffic to which pages
- Cross-reference search impressions with GA4 engagement metrics
- Identify pages with high impressions but low CTR (fix meta titles/descriptions)

---

## 6. Custom Dimensions Registration Checklist

Complete this in **GA4 Admin → Custom definitions** before data is lost:

- [ ] `listing_type` — Event scope
- [ ] `locality` — Event scope
- [ ] `job_category` — Event scope
- [ ] `job_type` — Event scope
- [ ] `experience_level` — Event scope
- [ ] `event_category` — Event scope
- [ ] `article_category` — Event scope
- [ ] `article_author` — Event scope
- [ ] `industry_type` — Event scope
- [ ] `cuisine` — Event scope
- [ ] `user_type` — **User** scope

---

## 7. Conversion Events Checklist

In **GA4 Admin → Events**, mark these as conversions:

- [ ] `job_applied`
- [ ] `job_posted`
- [ ] `generate_lead`
- [ ] `subscribe`
- [ ] `sign_up`
- [ ] `space_listed`
- [ ] `property_listed`
- [ ] `phone_call`
- [ ] `whatsapp_click`

---

*Generated: March 2026 | MyOMR Analytics Implementation — Phase 5*
