---
title: Workflows & Pipelines Index
last_updated: 12 November 2025
status: Draft
summary: Master index for MyOMR development workflows, operational pipelines, and rollout playbooks.
---

# Workflows & Pipelines Hub

Welcome! This folder captures every major workflow and content pipeline that keeps MyOMR moving—from day-to-day development to scheduled releases and data ingestion. Use this README as the navigation hub before diving into specific playbooks.

> 📌 **Context First:** If you haven’t already, read `docs/README.md` to understand the full documentation layout. This folder extends the project’s documentation ecosystem with process-oriented guides.

---

## 🎯 Scope & Goals

**Why this folder exists**

- Provide a single source of truth for our development lifecycle, content update routines, and admin operations.
- Help teammates and AI systems follow the exact steps for staging deployments, batch imports, QA, and rollouts.
- Preserve tribal knowledge and highlight handoff points between engineering, content, and operations.

**What you’ll find**

- End-to-end workflow descriptions (local dev → staging → production).
- Content ingestion pipelines (manual SQL batches, scripted imports, admin QA).
- Admin and support checklists for post-import verification and ongoing maintenance.
- Release and QA playbooks to ensure consistent rollouts.

**What you won’t find**

- Detailed feature specs (see `docs/product-prd/`).
- Implementation summaries (see `docs/content-projects/`).
- Day-to-day worklogs (see `docs/worklogs/`).
  Instead, this folder focuses on _how_ we execute recurring processes.

---

## 📁 Folder Structure & Naming

```
docs/workflows-pipelines/
├── README.md                      # This index
├── hostels-pgs-workflow.md        # Hostel & PGs ingestion + admin verification
├── job-portal-workflow.md         # Employer onboarding, moderation, public listings
├── events-workflow.md             # Community event submission → publishing
├── news-publication-workflow.md   # Editorial planning → article deployment
├── directory-ops-workflow.md      # Banks/schools/parks/etc. directory updates
├── coworking-spaces-workflow.md   # Partner onboarding and pricing updates
├── free-ads-workflow.md           # Classifieds submission → moderation
├── election-blo-workflow.md       # BLO data refresh and QA
├── landing-pages-workflow.md      # Discover MyOMR, Pentahive & info page updates
├── documentation-housekeeping-workflow.md # Keeping docs organised & archival rules
├── dev-platform-workflow.md       # Local → staging → production flow (planned)
├── content-update-pipeline.md     # Cross-module content ingestion plays (planned)
├── admin-ops-workflow.md          # Admin dashboard duties & escalation (planned)
├── qa-release-checklist.md        # Pre/post release verification (planned)
└── assets/                        # Optional diagrams (planned)
```

Naming follows our kebab-case convention. Each document covers one major workflow; break out sub-pipelines into their own files when they grow complex (e.g., `data-import-pipeline-hostels.md`, `analytics-reporting-cycle.md` down the road).

> 🗺️ **Flow Visuals:** Each workflow now includes Mermaid diagrams that map both happy-path and failure/recovery branches to make decision points explicit.

---

## 🧭 Navigation & Cross-References

| Topic                 | Look here first              | Related docs                                                                                                  |
| --------------------- | ---------------------------- | ------------------------------------------------------------------------------------------------------------- |
| Hostels & PGs imports | `hostels-pgs-workflow.md`    | `dev-tools/sql/hostel-*-manual.sql`, `omr-hostels-pgs/admin/manage-properties.php`, `docs/worklogs/`          |
| Job marketplace       | `job-portal-workflow.md`     | `/omr-local-job-listings/` module files, employer admin tools, `LEARNINGS.md` (10 Nov)                        |
| Events submissions    | `events-workflow.md`         | `/omr-local-events/` module, `EVENTS-FLOW-MATRIX.md`, admin quick links                                      |
| News articles         | `news-publication-workflow.md` | `/local-news/` templates, editorial plans in `docs/content-projects/`, sitemap documentation                   |
| Public directories    | `directory-ops-workflow.md`  | `/admin/manage-*.php`, `/omr-listings/`, relevant SQL templates                                              |
| Coworking partners    | `coworking-spaces-workflow.md` | `/omr-coworking-spaces/`, assets, pricing tables, CRM forms                                                  |
| Free ads & classifieds| `free-ads-workflow.md`       | `/free-ads-chennai/` module, moderation policies                                                             |
| Election BLO lookup   | `election-blo-workflow.md`   | `omr-election-blo/`, data spreadsheets, BLO articles                                                         |
| Landing/static pages  | `landing-pages-workflow.md`  | `discover-myomr/`, `info/`, `pentahive/`, campaign briefs                                                    |
| Documentation upkeep  | `documentation-housekeeping-workflow.md` | `@tocheck/`, `docs/archive/`, `docs/README.md`                                                               |
| Development lifecycle | `dev-platform-workflow.md`   | `operations-deployment/ONBOARDING.md`, `operations-deployment/DEPLOYMENT-CHECKLIST.md`, `LEARNINGS.md`        |
| Content ingestion     | `content-update-pipeline.md` | `dev-tools/sql/…`, `product-prd/PRD-Hostels-PGs.md`, `content-projects/HOSTELS-PGS-IMPLEMENTATION-SUMMARY.md` |
| Admin operations      | `admin-ops-workflow.md`      | `omr-hostels-pgs/admin/manage-properties.php`, `admin/` module docs                                           |
| Release QA            | `qa-release-checklist.md`    | `operations-deployment/*`, `LEARNINGS.md`                                                                     |

When drafting a new pipeline:

1. Drop a draft into `docs/inbox/` if it’s not ready for indexing.
2. Once finalised, move it here and update this README.
3. Add cross-links from feature PRDs, implementation summaries, or scripts that belong in the pipeline.

---

## 🧪 Document Template for New Pipelines

Use this starter layout to keep process docs consistent:

```markdown
# [Workflow / Pipeline Name]

- **Last updated:** DD Month YYYY
- **Owner:** Role or Team
- **Applies to:** Environment / Module / Release type
- **Prerequisites:** Accounts, scripts, permissions required

## 1. Overview

- Purpose and success criteria
- Trigger (what event starts this workflow)
- Participants / roles

## 2. Flow Diagram (Optional)

- Mermaid sequence chart or link to asset

## 3. Step-by-Step

1. Step name — details, tooling, expected output
2. …

## 4. Checklists

- Pre-conditions
- During execution
- Post-completion

## 5. Edge Cases & Recovery

- Common failure modes and mitigations
- Escalation points

## 6. References

- Related documentation
- Scripts / SQL files
- Ticket templates / forms
```

---

## 🛠️ Maintenance Guidelines

- **Keep timestamps fresh.** Update `last_updated` front matter whenever changes land.
- **Review quarterly** (or after major process changes) to ensure steps still reflect reality.
- **Log changes** in the daily worklog (`docs/worklogs/…`) when you modify or add a workflow doc.
- **Use diagrams judiciously.** When visual aids clarify complex flows, store them under an `assets/` subfolder and reference them inline.

---

## ✅ Next Actions (as of 12 Nov 2025)

1. Draft `dev-platform-workflow.md` outlining local → staging → production flow including documentation touchpoints.
2. Finalise `content-update-pipeline.md` to cross-reference hostels, jobs, events, news, directories, and ads ingestion steps.
3. Sketch `admin-ops-workflow.md` to map responsibilities across all admin dashboards (hostels, jobs, events, directories, ads).
4. Create `qa-release-checklist.md` consolidating regression + smoke test steps for dashboard and public modules.
5. Add diagrams (if needed) to `assets/` that visualise complex pipelines (e.g., combined content ingestion).

Let’s treat this folder as the evolving playbook for how MyOMR is built, updated, and maintained. When in doubt, document the steps you just followed so the next teammate—or future you—can repeat them with confidence.

---
