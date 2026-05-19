# EVENT-INSERT-AND-FEATURED-SOP

**Publish events to MyOMR with featured status and past-date filtering.**

---

## Scope

Publish events (workshops, meetups, sports, cultural) with correct featured logic and automatic date closure.

---

## RACI

- **Responsible:** Builder (creates + validates)
- **Accountable:** Architect (approves)
- **Informed:** Project lead

---

## Preconditions

- [ ] Event data prepared (title, description, date, location, category)
- [ ] Event date is FUTURE (for featured)
- [ ] Category confirmed (workshop, sports, meetup, cultural, educational, community)
- [ ] Image prepared and ready
- [ ] Remote MySQL access working

---

## Procedure

### 1. Prepare Event Data

- [ ] Title: Concise, descriptive
- [ ] Description: Full details (HTML allowed)
- [ ] Event date: YYYY-MM-DD format, >= TODAY()
- [ ] Event location: Physical address or online URL
- [ ] Category: Pick from enum (workshop, sports, meetup, cultural, educational, community)
- [ ] Slug: Lowercase, hyphens (e.g., "namma-ooru-stars-market-padur")

### 2. CRITICAL: Date Validation

**Featured events MUST be future-dated:**

```php
// Before INSERT:
$event_date = strtotime($date);
$today = strtotime(date('Y-m-d'));

if ($event_date < $today) {
    die("ERROR: Event date must be >= today for featured status");
}
```

**Why:** Featured section shows upcoming events. Past events confuse users (Issue #001).

### 3. Check Slug Uniqueness

```php
$existing = $db->query(
    "SELECT id FROM events WHERE slug = ?",
    [$slug]
);
if ($existing) {
    die("Slug collision! Use -v2");
}
```

### 4. Use Template

Path: `dev-tools/events/insert_*.php`

Populate:
```php
$event_title = "Namma Ooru Stars Market";
$category_id = 6; // cultural
$event_date = "2026-06-15";
$event_location = "Padur";
$slug = "namma-ooru-stars-market-padur";
$description = "...full details...";
$is_featured = 1; // Auto-set if date >= TODAY()
```

### 5. Dry Run

```bash
php dev-tools/events/insert_*.php
# Expected: SQL preview, no DB changes
```

### 6. Featured Query Verification

**The featured query MUST include date check:**

```php
// In event list/homepage widget:
$featured = $db->query(
    "SELECT * FROM events WHERE is_featured = 1 AND event_date >= CURDATE()"
);
// NEVER: SELECT * FROM events WHERE is_featured = 1 (would show past events)
```

### 7. Architect Approval

- [ ] IMPLEMENTATION-SUMMARY.md created
- [ ] Date >= TODAY() confirmed
- [ ] Slug unique
- [ ] Architect approves

### 8. Publish to Live

```bash
php dev-tools/events/insert_*.php
# Capture event ID
```

### 9. Verify Detail Page

- [ ] URL: `https://myomr.in/event/{id}/{slug}`
- [ ] Renders without 404
- [ ] Date displays correctly
- [ ] Location correct

### 10. Update Sitemap

```bash
php omr-local-events/generate-events-sitemap.php
```

### 11. Close Past Events (if needed)

**Daily cleanup script (should be cron job):**

```bash
php dev-tools/events/run-close-past-events.php
# Sets is_featured = 0 for events where event_date < TODAY()
```

---

## Key Constraints

- **CRITICAL:** Event date MUST be >= TODAY() for featured
- Slug must be globally unique
- Past events automatically removed from featured (cron job)
- Featured logic in homepage widget must include date filter

---

## Validation

- [ ] Event date >= TODAY()
- [ ] Slug unique
- [ ] Detail page renders
- [ ] Sitemap updated
- [ ] Featured query includes date filter

---

## Related References

- [LIVE-PUBLISH-CHECKLIST-SOP.md](./LIVE-PUBLISH-CHECKLIST-SOP.md)
- [RISKS.md](../planning/RISKS.md) — #001 (featured events gotcha)
- [KNOWN-ISSUES.md](../KNOWN-ISSUES.md) — #001 (past events issue)

---

**Last Updated:** 2026-05-19
