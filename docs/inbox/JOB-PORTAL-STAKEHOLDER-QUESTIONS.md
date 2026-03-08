# MyOMR Job Portal – Stakeholder Questions & Success Metrics

_Created: 11 Nov 2025 — Supports WBS Step 1 (“Discovery & Planning”)_

## 1. Stakeholder Groups

| Stakeholder | Primary Concerns (from existing docs/workflows) | Key References |
|-------------|--------------------------------------------------|----------------|
| Product / Founder | Local relevance, adoption vs major portals, roadmap alignment | `docs/product-prd/JOB-FEATURE-PROBLEM-STATEMENTS.md`, `docs/strategy-marketing/ MARKETING-STRATEGY-Job-Feature.md` |
| Marketing / Growth | SEO performance, landing page conversion, content funnel | `docs/strategy-marketing/LANDING-PAGE-STRATEGY-Job-Feature.md`, GA tracking docs |
| Operations / Admin | Moderation throughput, employer verification, support load | Admin workflow mapping (`docs/inbox/JOB-PORTAL-CURRENT-WORKFLOWS.md`) |
| Engineering | Scalability of listings/search, data hygiene, maintainability | Technical baseline (`docs/inbox/JOB-PORTAL-TECHNICAL-BASELINE.md`) |
| Employers | Ease of posting, response quality, trust | Problem statements (employer set) |
| Job Seekers | Discoverability, application experience, communication | Problem statements (job seeker set) |

## 2. Discovery Question Bank

### Product / Founder
1. What market share or traffic targets define success vs national portals?
2. Which WBS streams are non-negotiable for the next release window?
3. How do we prioritise employer vs job-seeker improvements in Q1 2026?

### Marketing / Growth
1. Which landing pages currently drive the most conversions or drop-offs?
2. What SEO metrics (impressions, CTR, ranking for “jobs in OMR”) must improve?
3. Do we require structured data or content updates aligned with new workflows?

### Operations / Admin
1. Average time from job submission to approval? Desired SLA?
2. Pain points in the current moderation tooling (bulk updates, auditability)?
3. What employer verification signals are missing today?

### Engineering
1. Acceptable response-time budgets for listings/search once dataset scales?
2. Are there pending database migrations or cleanup tasks blocking refactors?
3. What logging/monitoring gaps hinder diagnosing incidents?

### Employers
1. Which parts of the posting flow feel confusing or repetitive?
2. Are application notifications timely and actionable?
3. Would premium options (featured listings, resume access) add value?

### Job Seekers
1. Does the search/filter experience surface relevant roles quickly?
2. Are application confirmations and follow-ups satisfactory?
3. What additional job details or tools (salary norms, commute info) would help?

## 3. Proposed Success Metrics

| Theme | Baseline Needed | Target / Direction |
|-------|-----------------|--------------------|
| Listings performance | Page load time, query execution time, memory usage | Reduce full load time by ≥40%, enable true pagination |
| Search engagement | Search-to-detail click-through rate, filter usage stats | Increase CTR by 20%, double filter usage post-redesign |
| Application funnel | Applications per job, drop-off rate, duplicate rate | Increase valid applications/job by 25%, reduce duplicates to <2% |
| Admin throughput | Average approval turnaround, jobs awaiting review | Achieve <24h SLA, keep backlog <10 jobs |
| Employer retention | Repeat postings, time-to-first-response | Grow repeat postings by 30%, target <48h response time |
| Marketing outcomes | Organic traffic to job pages, conversion rate | Improve organic sessions by 50%, lift conversion by 15% |

## 4. Next Discovery Actions

1. Validate availability of analytics data (GA, server logs) for metrics above.
2. Schedule stakeholder interviews/workshops using question bank.
3. Document agreed KPIs in product roadmap and analytics dashboards.

---

_Maintainer_: AI Agent (GPT-5 Codex). Please review and move out of `docs/inbox/` once stakeholders align.

