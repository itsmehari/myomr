# Team Guide: How to Use the Architect-Builder System

**Quick reference for architects, builders, and project leads.**

---

## For Architects (Planning)

Your job: Define what to build, how to build it, and what done looks like.

### Workflow

1. **Understand the context:**
   - Read `planning/PROJECT-STATE.md` (where are we?)
   - Read `planning/DOMAIN.md` (business rules)
   - Read relevant SOP for this entity type

2. **Create sprint folder:**
   ```bash
   mkdir -p .cursor/planning/sprints/sprint-NNN-{name}
   ```

3. **Fill 4 documents:**
   - **REQUIREMENTS.md** — What + why + success metric
   - **BLUEPRINT.md** — How to build it + edge cases
   - **ACCEPTANCE-CRITERIA.md** — Done checklist
   - **HANDOFF-PROMPT.md** — Detailed builder instructions

4. **Get builder approval:**
   - Share HANDOFF-PROMPT in builder chat
   - Builder creates IMPLEMENTATION-SUMMARY
   - Review + approve or request changes

5. **Update PROJECT-STATE.md:**
   - Mark sprint as "in progress" → "approved" → "complete"

### Template to Copy

Start with: `.cursor/planning/sprints/sprint-00-template/`

Copy all 4 docs, fill in your task details.

### Quick Checklist

- [ ] REQUIREMENTS.md has success metric
- [ ] BLUEPRINT.md includes edge cases + risks
- [ ] ACCEPTANCE-CRITERIA.md is a checklist
- [ ] HANDOFF-PROMPT.md includes schema snippets + SOP reference
- [ ] Builder has clear, specific instructions

---

## For Builders (Implementation)

Your job: Read the sprint docs, implement carefully, and hand off summary for review.

### Workflow

1. **Read everything:**
   - Read HANDOFF-PROMPT.md (architect's instructions)
   - Read relevant SOP (detailed checklist)
   - Skim schema from `docs/data-model.md`

2. **Dry run:**
   - Use template from `.cursor/sop/DEV-TOOLS-TEMPLATES.md`
   - Set `$test_mode = true` (no DB changes)
   - Run and verify no errors
   - **DO NOT** go live yet

3. **Create IMPLEMENTATION-SUMMARY.md:**
   - What did you build?
   - Entity ID/slug/category
   - Any edge cases you handled?
   - Questions/concerns for architect?

4. **Wait for architect approval:**
   - Architect reviews your summary
   - If approved: You may go live
   - If requests changes: Go back to step 2

5. **Go live (if approved):**
   - Set `$test_mode = false`
   - Run template against remote DB
   - Verify detail page renders
   - Capture URL + screenshots as evidence

6. **Commit:**
   ```bash
   git add .cursor/planning/sprints/sprint-NNN/
   git add dev-tools/...
   git commit -m "feat: sprint-NNN complete (entity published)"
   git push
   ```

### Key Discipline

- ❌ **Never skip dry run** — Always test without DB changes first
- ❌ **Never skip architect approval** — Always wait for review before going live
- ❌ **Never ignore SOP checklist** — Follow it exactly
- ✅ **Always verify detail page** — Click the URL before declaring done

---

## For Project Leads (Oversight)

Your job: Keep work moving, unblock risks, keep PROJECT-STATE updated.

### Weekly Checklist

- [ ] Review `planning/PROJECT-STATE.md` — What's in progress?
- [ ] Review `planning/RISKS.md` — Any new risks?
- [ ] Check sprint status files — Anyone blocked?
- [ ] Verify git commits — Are sprint docs being committed?

### Monthly Checklist

- [ ] Update `RECENT-UPDATES.md` — What shipped?
- [ ] Review `DECISIONS.md` — Any new decisions to log?
- [ ] Update `planning/RISKS.md` — Reassess risks
- [ ] Check `.cursor/` is being kept current

### When Blocked

1. Identify the blocker (schema issue? Approval delay? Missing info?)
2. Check `.cursor/planning/RISKS.md` — Is it a known risk?
3. Check `.cursor/KNOWN-ISSUES.md` — Is there a workaround?
4. If new issue: Document it + add to RISKS
5. Get architect + builder aligned on solution

---

## Common Questions

**Q: Where do I find the template?**
A: `.cursor/sop/DEV-TOOLS-TEMPLATES.md` — Index by entity type (jobs, events, news, etc.)

**Q: What's the schema for jobs?**
A: `.cursor/docs/data-model.md` → Search for "jobs table"

**Q: How do I publish a job?**
A: `.cursor/sop/JOB-INSERT-AND-SEO-SOP.md` — Step-by-step guide

**Q: What if I find a bug?**
A: Add it to `.cursor/KNOWN-ISSUES.md` with workaround, commit to git

**Q: Can I skip the dry run?**
A: **No.** Dry run prevents DB errors. Always do it.

**Q: What does "architect approval" mean?**
A: Architect reviews IMPLEMENTATION-SUMMARY and says "OK, go live" (or asks for changes)

**Q: How long should a sprint take?**
A: Depends. Job publish = 30 min. News article = 1-2 hours. Feature = 1-2 days.

---

## Checklist Template (Copy & Paste)

### Sprint: {Name}

**Architect:**

- [ ] REQUIREMENTS.md written (what + why)
- [ ] BLUEPRINT.md written (how + edge cases)
- [ ] ACCEPTANCE-CRITERIA.md written (done checklist)
- [ ] HANDOFF-PROMPT.md written (builder instructions)
- [ ] PROJECT-STATE.md updated → "in progress"

**Builder:**

- [ ] Read HANDOFF-PROMPT.md + SOP
- [ ] Dry run completed (no errors)
- [ ] IMPLEMENTATION-SUMMARY.md created
- [ ] Waiting for architect approval

**Architect Review:**

- [ ] Reviewed IMPLEMENTATION-SUMMARY.md
- [ ] ✅ Approved (go live) OR ❌ Requested changes

**Builder Execution (if approved):**

- [ ] Live published
- [ ] Detail page verified
- [ ] Commit to git
- [ ] PROJECT-STATE.md updated → "complete"

---

## File Locations (Quick Reference)

| Need | File |
|------|------|
| Current sprint? | `planning/PROJECT-STATE.md` |
| Business rules? | `planning/DOMAIN.md` |
| Job template? | `dev-tools/jobs/insert_*.php` |
| Job SOP? | `sop/JOB-INSERT-AND-SEO-SOP.md` |
| Job schema? | `docs/data-model.md` (search "jobs table") |
| Past decisions? | `planning/DECISIONS.md` |
| Known bugs? | `KNOWN-ISSUES.md` |
| All SOPs? | `sop/README.md` |
| Setup help? | `SETUP.md` |

---

**Last Updated:** 2026-05-19
