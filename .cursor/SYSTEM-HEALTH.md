# System Health Dashboard

**Track adoption and quality of the Architect-Builder system.**

---

## Metrics to Track

### 1. Documentation Completeness

| Metric | Target | Current | Status |
|--------|--------|---------|--------|
| Hub files (README, INDEX, CONVENTIONS, SETUP) | 4/4 | 4/4 | ✅ |
| Planning docs (PROJECT-STATE, DOMAIN, DECISIONS, RISKS, KNOWN-ISSUES) | 5/5 | 5/5 | ✅ |
| Tech docs (architecture, data-model, validation, api, permissions) | 5/5 | 5/5 | ✅ |
| SOPs created | 24/24 | 9/24 | 🟡 (38%) |
| Sprints with 4-doc pattern | 10+ | 1 | 🟡 (10%) |
| Git commits with `.cursor/` changes | Regular | 1 | 🟡 (Low) |

### 2. Adoption Metrics

| Metric | Baseline | Goal | Current |
|--------|----------|------|---------|
| % of tasks using sprint template | 0% | 100% | TBD |
| % of builders reading SOP before coding | 0% | 100% | TBD |
| Time to onboard new developer | 4 hours | < 1 hour | TBD |
| Questions "where is template?" per sprint | High | 1 or less | TBD |
| Context loss (agent joins mid-project) | High | Zero | TBD |

### 3. Quality Metrics

| Metric | Baseline | Goal | Current |
|--------|----------|------|---------|
| Code errors caught by dry run | Low | Most | TBD |
| Bugs on live deploy | Higher | Reduced | TBD |
| Schema mismatches on import | Multiple/month | Zero | TBD |
| HTML QA gate failures | Not tracked | Decreasing | TBD |

---

## Monthly Review Checklist

**First Friday of each month:**

- [ ] Review git log: How many `.cursor/` commits?
  ```bash
  git log --oneline --grep="\.cursor" --since="1 month ago" | wc -l
  ```

- [ ] Check PROJECT-STATE: Updated recently?
  ```bash
  git log --oneline .cursor/planning/PROJECT-STATE.md | head -5
  ```

- [ ] Count sprints completed:
  ```bash
  ls -la .cursor/planning/sprints/ | grep "sprint-" | wc -l
  ```

- [ ] Survey team: Any pain points?
  - "Where do you look first for docs?" (answer: `.cursor/README.md`?)
  - "Did the sprint template save time?" (yes/no)
  - "Any missing docs?"

- [ ] Update this dashboard with new numbers

---

## Health Status

### Overall Score: 🟢 GREEN (Phases 1-4 Complete)

**Last Updated:** 2026-05-19

### What's Going Well

✅ Hub created + easily discovered  
✅ Project memory documented (decisions, risks, issues)  
✅ Sprint template working  
✅ SOPs being created (9/24, 38%)  
✅ Team guide + onboarding guide available  

### What Needs Attention

🟡 Complete remaining 15 SOPs (modules, security, deployment)  
🟡 Promote rules/ + slug-urls-detail-pages/ to git  
🟡 Update cross-links (sop/README, AGENTS.md, .cursorrules)  
🟡 Get first real sprint running with 4-doc pattern  
🟡 Measure adoption metrics

---

## Upcoming Improvements

| Priority | Task | Owner | ETA |
|----------|------|-------|-----|
| High | Complete 15 remaining SOPs | Architect | Sprint 02 |
| High | Git-track rules/ + skills/ | Project lead | Sprint 02 |
| Medium | Create email landing pages for all modules | Builder | Sprint 03 |
| Medium | Refine DOMAIN.md with more enums | Architect | Ongoing |
| Low | Add example IMPLEMENTATION-SUMMARY files | Documentation | Sprint 03 |

---

## Action Items

### This Sprint

- [ ] Complete remaining 5 SOPs (NEWS-PUBLISHING, NEWS-HTML-QA, SECURITY, DEPLOY, INCIDENT)
- [ ] Update `.cursor/sop/README.md` with new SOP index
- [ ] Create sprint-02 example using new template

### Next Sprint

- [ ] Measure adoption (survey team)
- [ ] Get team feedback on what's missing
- [ ] Update docs based on feedback
- [ ] Create module-specific sprint templates (events, news, classifieds, rent)

---

## How to Use This Dashboard

1. **Monthly:** Update metrics table (git log counts, sprint counts, etc.)
2. **Quarterly:** Assess health status (GREEN/YELLOW/RED)
3. **Annually:** Plan improvements to the system itself

---

**Dashboard Created:** 2026-05-19  
**Last Reviewed:** 2026-05-19  
**Next Review:** 2026-06-19
