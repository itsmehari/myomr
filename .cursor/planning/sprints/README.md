# Sprint Planning & Documentation

All development work is organized into **sprints**, each with a standardized 5-document structure.

---

## Sprint Structure (5-Document Pattern)

Each sprint folder contains:

```
sprint-NNN-{name}/
├── REQUIREMENTS.md          ← What to build + why
├── BLUEPRINT.md             ← How to build it
├── ACCEPTANCE-CRITERIA.md   ← Done checklist
├── HANDOFF-PROMPT.md        ← Builder instructions (detailed)
├── WORKLOG.md               ← Issues found + fixes applied (added after implementation)
└── STATUS.md                ← Current state (optional, updated as sprint progresses)
```

---

## Document Purposes

| Document | Purpose | Owner | When |
|----------|---------|-------|------|
| **REQUIREMENTS.md** | What needs to be built and why; success metrics | Architect | Before sprint starts |
| **BLUEPRINT.md** | Technical design, architecture, edge cases, risks | Architect | Before sprint starts |
| **ACCEPTANCE-CRITERIA.md** | Functional + quality criteria defining "done" | Architect | Before sprint starts |
| **HANDOFF-PROMPT.md** | Detailed builder instructions (references all above) | Architect | Before sprint starts |
| **WORKLOG.md** | Issues discovered during implementation; how they were fixed | Builder | After dry run approval / before live |
| **STATUS.md** | Sprint progress (in progress → dry run → approved → complete) | Builder | Throughout sprint |

---

## 4→5 Document Evolution

**Before May 2026:** Sprints had 4 documents (Requirements, Blueprint, Acceptance, Handoff)

**May 2026 onwards:** Sprints include **WORKLOG.md** as the 5th document

**Why?** To preserve the work history and decision-making process during implementation, helping future teams learn from past issues and fixes.

---

## Workflow

### 1. Architect Prepares Sprint (Days 1-2)

1. Write **REQUIREMENTS.md** — What + why
2. Write **BLUEPRINT.md** — How + architecture
3. Write **ACCEPTANCE-CRITERIA.md** — Done checklist
4. Write **HANDOFF-PROMPT.md** — Builder instructions
5. Update `.cursor/planning/PROJECT-STATE.md` — Current sprint status
6. Builder reviews all 4 documents, asks clarifications

### 2. Builder Implements (Days 3-5)

1. Read all 4 documents in sprint folder
2. Read `ARCHITECTURE.md`, `DATA-MODEL.md` for context
3. Implement feature/fix per BLUEPRINT
4. Update `STATUS.md` as work progresses (in progress → dry run)
5. Run ACCEPTANCE-CRITERIA checklist
6. Create `WORKLOG.md` documenting any issues found and how fixed

### 3. Architect Reviews & Approves (Day 6)

1. Review `WORKLOG.md` — Verify all issues properly documented
2. Review code against BLUEPRINT
3. Check ACCEPTANCE-CRITERIA all met
4. Approve dry run or request changes
5. Update `STATUS.md` (approved)

### 4. Go Live (Day 7)

1. Deploy per SOP checklist
2. Verify acceptance criteria on live
3. Update `STATUS.md` (complete)
4. Mark sprint folder complete

---

## WORKLOG.md Template

See `WORKLOG-TEMPLATE.md` in this folder.

---

## Cross-References

- **Current sprint:** `../PROJECT-STATE.md`
- **All worklogs:** `../WORKLOG-ARCHIVE.md`
- **How to use worklogs:** `../WORKLOG-SYSTEM.md`
- **Historical worklogs:** See WORKLOG-ARCHIVE for links

---

## Examples

### Example: sprint-06-modal-browser-fixes/

```
REQUIREMENTS.md
├─ What: Fix modal popup display issues on browsers
├─ Why: Users reporting modal not showing, disappearing during scroll, close not working
└─ Success: Modal displays + closes consistently on Chrome, Safari, Firefox, iOS

BLUEPRINT.md
├─ How: Update modal.css (transitions) + modal.js (scroll detection)
├─ Edge cases: Mobile Safari needs -webkit- prefixes; scroll momentum behavior
└─ Risks: CSS changes may break existing modals; need regression testing

ACCEPTANCE-CRITERIA.md
├─ Modal displays correctly on Desktop (Chrome, Firefox, Safari)
├─ Modal displays correctly on Mobile (iOS Safari, Chrome, Android)
├─ Close button and skip button work
├─ No regression on other page modals

HANDOFF-PROMPT.md
├─ Implement scroll velocity detection (don't show during fast scroll)
├─ Add -webkit- prefixes to modal.css
├─ Test on actual devices (iOS Safari is critical)
└─ Document any gotchas in WORKLOG.md

WORKLOG.md
├─ Issue 1: CSS not loading in index.php
│  ├─ Fix: Added <link> tag to head
│  ├─ Why: REQUIREMENTS mentioned "modal displays consistently"
│  └─ Verified: Live page shows correct styling
├─ Issue 2: Safari transforms not working
│  ├─ Fix: Added -webkit- vendor prefixes
│  ├─ Why: BLUEPRINT mentioned "edge cases: Mobile Safari needs prefixes"
│  └─ Verified: Tested on iOS device
└─ Lessons Learned: Document vendor prefix requirement in KNOWN-ISSUES.md
```

---

## Tips

✅ **Before sprint starts:** Architect writes all 4 initial documents  
✅ **During sprint:** Builder updates STATUS.md daily  
✅ **After implementation:** Builder writes WORKLOG.md (issues + fixes)  
✅ **After approval:** Architect reviews WORKLOG.md and marks sprint complete  
✅ **Knowledge preservation:** WORKLOG.md content feeds into KNOWN-ISSUES.md, LEARNINGS.md, DECISIONS.md

---

## Finding a Sprint

1. Check `../PROJECT-STATE.md` — What's the current sprint?
2. Go to `sprint-NNN-{name}/` folder
3. Read `REQUIREMENTS.md` → `BLUEPRINT.md` → `ACCEPTANCE-CRITERIA.md`
4. For implementation details, read `HANDOFF-PROMPT.md`
5. For what was actually built/fixed, read `WORKLOG.md`

---

## Maintenance

- Archive completed sprints (keep folder, mark STATUS complete)
- Retroactively assign old worklogs to sprint folders (see WORKLOG-ARCHIVE.md)
- Link lessons from WORKLOG.md → KNOWN-ISSUES.md, LEARNINGS.md

---

**Last Updated:** 2026-05-19  
**Pattern Introduced:** May 2026 (WORKLOG.md added)  
**Maintained By:** Architect + Builder team
