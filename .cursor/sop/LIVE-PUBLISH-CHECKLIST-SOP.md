# LIVE-PUBLISH-CHECKLIST-SOP

**Master checklist for publishing any entity to live MyOMR.in database.**

---

## Scope

Publishing jobs, events, news, classifieds, or properties to live cPanel MySQL. Covers all entity types + pre/post-flight validation.

---

## RACI

- **Responsible:** Builder (implements using template)
- **Accountable:** Architect (approves IMPLEMENTATION-SUMMARY before live)
- **Consulted:** QA (verifies acceptance criteria)
- **Informed:** Project lead (tracks PROJECT-STATE)

---

## Preconditions

- [ ] Architect has created sprint REQUIREMENTS + BLUEPRINT + HANDOFF-PROMPT
- [ ] Entity data is ready (copy, image files, metadata)
- [ ] Developer IP whitelisted on cPanel Remote MySQL (see `README-REMOTE-DATABASE.md`)
- [ ] PHP CLI + mysqli extension available locally
- [ ] Git repo cloned locally

---

## Procedure

### Pre-Flight (Before Dry Run)

1. **Determine entity type** (job, event, news, classified, property)
   - Go to [`.cursor/sop/DEV-TOOLS-TEMPLATES.md`](./DEV-TOOLS-TEMPLATES.md)
   - Find template path for this entity type

2. **Find/create template script**
   - Location: `dev-tools/{entity_type}/insert_*.php` or `dev-tools/sql/run-*-article.php`
   - If exists: review + populate variables
   - If new: copy from similar template + adapt

3. **Validate database connection**
   ```bash
   mysql -h myomr.in -u {DB_USER} -p -e "SELECT 1;"
   ```
   - Should return: 1 row
   - If fails: Check IP whitelisted, port 3306 open

4. **Check schema matches**
   ```bash
   php dev-tools/schema-diff.php {entity_type}
   # or manually compare against docs/data-model.md
   ```
   - Verify all required columns exist
   - Enum values valid (e.g., category, locality)

5. **Check slug uniqueness** (jobs, rentals, news, events, classifieds)
   ```php
   // In template before INSERT:
   $existing = $db->query("SELECT id FROM {table} WHERE slug = ? AND locality_id = ?", [$slug, $locality_id]);
   if ($existing) {
       die("Slug collision! Use -v2 suffix.");
   }
   ```

6. **Verify privacy flags** (if job)
   - Check: `$hide_employer_phone` is 0 or 1
   - Reminder: This flag must be checked on ALL UI surfaces (detail, sidebar, email)

7. **Check image paths** (if applicable)
   - Images stored in: `local-news/omr-news-images/`, `omr-local-events/assets/`, etc.
   - Verify paths match stored location
   - Images must exist before publishing

### Dry Run (No DB Changes)

8. **Execute template with test mode**
   ```bash
   # (method depends on template; usually a flag at top)
   $test_mode = true; // prevents DB INSERT
   php dev-tools/{entity}/insert_*.php
   ```
   - Expected: SQL logged to console (no DB changes)
   - Check: No errors, SQL looks correct

9. **Create IMPLEMENTATION-SUMMARY.md**
   - Save in sprint folder: `planning/sprints/sprint-NNN/IMPLEMENTATION-SUMMARY.md`
   - Include: Entity ID, slug, category, URL, any edge cases handled

### Architect Approval

10. **Wait for architect review**
    - Architect reads IMPLEMENTATION-SUMMARY.md
    - Architect verifies against ACCEPTANCE-CRITERIA.md
    - Architect approves or requests changes (go back to step 8)

### Live Execution (if Approved)

11. **Run template on remote DB** (actual INSERT)
    ```bash
    php dev-tools/{entity}/insert_*.php
    ```
    - Expected: Entity created, ID printed
    - Note: ID or confirmation returned

12. **Verify detail page renders**
    - URL: `https://myomr.in/{module}/{id}/{slug}`
    - Check: Page loads, no 404, no errors
    - Check: Canonical URL correct
    - Check: Privacy flags respected (if job)

13. **Verify sitemap updated**
    ```bash
    php {module}/generate-{entity}-sitemap.php
    grep "{id}/{slug}" {module}/sitemap.xml
    ```
    - Expected: URL in sitemap
    - Check: URL matches canonical

14. **Track GA4 event** (if applicable)
    - GA4 Admin → Real-Time
    - Load detail page
    - Expected: `page_view` event appears (within 5 sec)
    - If publish event tracked: expect `entity_published` event

15. **Send email notification** (if applicable)
    - Check email queue: were subscribers notified?
    - Verify: Job title in email, CTA link works, unsubscribe link works

---

## Validation

- [ ] Dry run completed without errors
- [ ] IMPLEMENTATION-SUMMARY created
- [ ] Architect approved
- [ ] Detail page accessible (no 404)
- [ ] Canonical URL correct
- [ ] Sitemap includes URL
- [ ] GA4 Real-Time shows page_view event
- [ ] Email sent to subscribers (if applicable)
- [ ] Privacy flags checked (if job)
- [ ] No schema errors in logs

---

## Rollback

**If something goes wrong after live:**

1. **Delete entity from DB**
   ```bash
   php dev-tools/rollback.php {entity_type} {id}
   ```
   Or manually:
   ```sql
   DELETE FROM {table} WHERE id = {id};
   ```

2. **Regenerate sitemap**
   ```bash
   php {module}/generate-{entity}-sitemap.php
   ```

3. **Ping Search Console** (if URL indexed)
   - Use [`dev-tools/mcp/search_console_mcp.py`](../../dev-tools/mcp/search_console_mcp.py)

---

## Evidence

After successful publish, document:

- [ ] Entity ID: {ID}
- [ ] URL: https://myomr.in/{path}
- [ ] Detail page screenshot (shows info correctly)
- [ ] Sitemap verification (URL in sitemap.xml)
- [ ] GA4 Real-Time screenshot (page_view event)
- [ ] IMPLEMENTATION-SUMMARY.md in sprint folder

---

## Related References

- [`sop/README.md`](./README.md) — SOP index
- [`sop/DEV-TOOLS-TEMPLATES.md`](./DEV-TOOLS-TEMPLATES.md) — Template paths by entity
- [`docs/data-model.md`](../docs/data-model.md) — Schema reference
- [`docs/validation.md`](../docs/validation.md) — Quality gates
- [`planning/DOMAIN.md`](../planning/DOMAIN.md) — Business rules (enums, constraints)
- [`README-REMOTE-DATABASE.md`](../../README-REMOTE-DATABASE.md) — Remote MySQL setup
- [`KNOWN-ISSUES.md`](../KNOWN-ISSUES.md) — Common gotchas + fixes

---

**Last Updated:** 2026-05-19
