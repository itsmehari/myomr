# Documentation Housekeeping Workflow

- **Last updated:** 12 November 2025  
- **Owner:** Documentation PM / Tech Lead  
- **Applies to:** `docs/`, `@tocheck/`, `md-archives/`, repository root hygiene  
- **Prerequisites:** Knowledge of documentation categories, rename history, time set aside for cleanup

## 1. Overview

- **Purpose:** Keep documentation organised, prevent stale drafts from lingering, and maintain historical archives.
- **Trigger:** New files dropped into `@tocheck/`, monthly documentation review, or reorganisation request.
- **Participants:** Documentation PM, feature owners, possibly design/content leads for specific files.

## 2. Flow Diagram

```mermaid
flowchart TD
    A[Review @tocheck/ contents] --> B{File needed?\n(duplicate/obsolete?)}
    B -- Obsolete --> B1[Archive or delete<br/>Note in log] --> END1[Removed]
    B -- Needed --> C[Categorise by type (docs/code/assets)]
    C --> D{Belongs in docs/?}
    D -- No (code/assets) --> D1[Move to proper module<br/>Update references] --> END2[Organised]
    D -- Yes --> E[Move to appropriate docs category]
    E --> F[Update docs/README.md & indices]
    F --> G{Cross-references needed?}
    G -- Yes --> G1[Add links in related docs<br/>Update .cursorrules if structure changes] --> H
    G -- No --> H[Record change in worklog]
    H --> I[Empty @tocheck/ when done]
```

## 3. Step-by-Step

1. **Assess `@tocheck/`**
   - List all files (scripts, articles, docs, assets).
   - Decide: keep, archive, or delete.

2. **Categorise**
   - Assign each kept file to a documentation category (`analytics-seo/`, `product-prd/`, etc.) or appropriate module.
   - For code/assets, move to feature folder (`components/`, `assets/`, `dev-tools/`).

3. **Move & update references**
   - Relocate files to final destination.
   - Update references in documentation, code comments, or README tables.

4. **Archive**
   - Obsolete docs → `docs/archive/` with date prefix.
   - Older versions → `md-archives/` for historical reference.
   - Note reason for archiving in header/front matter.

5. **Documentation index updates**
   - Edit `docs/README.md` statistics and tables.
   - Update category-specific indices if present.
   - Adjust `.cursorrules` if new categories introduced.

6. **Logging**
   - Record significant moves in `docs/worklogs/`.
   - Capture learnings or new patterns in `LEARNINGS.md`.

## 4. Checklists

**Cleanup session**
- [ ] `@tocheck/` reviewed entirely.
- [ ] Each file categorised (keep/move/archive/delete).
- [ ] New docs placed in correct folder with consistent naming.
- [ ] Index/README updated.
- [ ] Worklog entry added.

**Monthly maintenance**
- [ ] Verify documentation stats (file count, categories) still accurate.
- [ ] Archive files older than policy threshold if superseded.
- [ ] Ensure `.cursorrules` instructions still align with structure.

## 5. Edge Cases & Recovery

- **Large legacy files:** Zip and store externally if not needed in repo; leave pointer doc.
- **Conflicting names:** Append suffix or rename with clearer context before filing.
- **Uncertain ownership:** Tag stakeholders in tracker or leave note in `docs/inbox/` with question.
- **Breaking references:** Grep for old file paths before deleting/moving.

## 6. References

- Documentation index: `docs/README.md`, `.cursorrules`
- Archives: `docs/archive/`, `md-archives/`
- Root cleanup notes: `LEARNINGS.md`, `docs/worklogs/`
- Prior audits: `@tocheck/WORKFLOW-AUDIT-SUMMARY.md`, `docs/worklogs/worklog-07-11-2025.md`

