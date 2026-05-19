# Updated Sprint Template (with Worklog)

## Files in Each Sprint Folder

Every sprint should have **5 documents**:

1. **REQUIREMENTS.md** — What to build + why
2. **BLUEPRINT.md** — How to build it
3. **ACCEPTANCE-CRITERIA.md** — Done checklist
4. **HANDOFF-PROMPT.md** — Builder instructions
5. **WORKLOG.md** ← NEW — Issues + fixes (added after implementation)

---

## Example Sprint Folder

```
.cursor/planning/sprints/sprint-06-modal-browser-fixes/
├── REQUIREMENTS.md
├── BLUEPRINT.md
├── ACCEPTANCE-CRITERIA.md
├── HANDOFF-PROMPT.md
├── WORKLOG.md ← Issues found during implementation
└── STATUS.md (optional)
```

---

## WORKLOG.md Template

```markdown
# Sprint {NN}: {Name} — Work Log

**Date(s):** {YYYY-MM-DD to YYYY-MM-DD}
**Builder:** {Name}
**Status:** {In Progress / Complete}

## Summary

{1-2 sentences of what was built and any issues encountered}

## Issues Identified

1. **Issue Title** — Description
   - Impact: {User/system impact}
   - Severity: {Critical / High / Medium / Low}

2. **Issue Title** — Description
   - Impact: {User/system impact}
   - Severity: {Critical / High / Medium / Low}

## Fixes Implemented

### Issue: {Title}

- **File(s):** {filepath(s)}
- **Change:** {What was changed}
- **Why:** {Rationale from REQUIREMENTS or BLUEPRINT}
- **Verification:** {How it was tested}
- **Result:** {Fixed / Workaround / Escalated}

## Decisions Made During Implementation

{Any new decisions or trade-offs made during implementation}

See `.cursor/planning/DECISIONS.md` for full context.

## Lessons Learned

{Patterns or gotchas discovered that should be documented elsewhere}

## Cross-References

- Requirements: REQUIREMENTS.md
- Blueprint: BLUEPRINT.md
- Status: STATUS.md
- Known Issues: `../../KNOWN-ISSUES.md`
- Decisions: `../../planning/DECISIONS.md`
- Learnings: `../../LEARNINGS.md`
```

---

## When to Create WORKLOG.md

**Timing:** After dry run approval, before going live (or immediately after)

**Content:** Issues found + how they'll be fixed (reference REQUIREMENTS + BLUEPRINT for context)

**Owner:** Builder fills it out during implementation

---

## Example: Actual Worklog

See `sprint-06-modal-browser-fixes/WORKLOG.md` for a complete example.

---

**Updated:** 2026-05-19  
**For:** Sprint planning integration
