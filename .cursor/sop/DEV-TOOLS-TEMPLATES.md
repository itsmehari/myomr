# DEV-TOOLS-TEMPLATES Index

**All template scripts for publishing entities to MyOMR. Find the right template for your entity type.**

---

## Jobs

**Template Path:** `dev-tools/jobs/insert_*.php`

**What to Expect:**
- Expects: `$job_title`, `$category_id`, `$locality_id`, `$slug`, `$description`, `$employer_id`, `$contact_phone`, `$contact_email`, `$hide_employer_phone`
- Returns: Job ID on successful INSERT
- Remote DB: Yes (set `DB_HOST='myomr.in'`)

**Example:**
```bash
php dev-tools/jobs/insert_senior_developer_omr.php
# Publishes job to live DB
```

**SOP Reference:** `.cursor/sop/JOB-INSERT-AND-SEO-SOP.md`

---

## Events

**Template Path:** `dev-tools/events/insert_*.php`

**What to Expect:**
- Expects: `$event_title`, `$category_id`, `$event_date`, `$event_location`, `$slug`, `$description`
- Returns: Event ID on successful INSERT
- CRITICAL: `$event_date` must be >= TODAY() for featured

**SOP Reference:** `.cursor/sop/EVENT-INSERT-AND-FEATURED-SOP.md`

---

## News Articles

**Template Path:** `dev-tools/sql/run_*_article.php`

**What to Expect:**
- Expects: HTML file at `local-news/articles/{filename}.html`
- Expects: Meta: `$article_title`, `$slug`, `$meta_description`, `$published_date`
- Returns: Article ID on successful INSERT
- HTML must be valid (no unclosed tags, no BOM)

**Example:**
```bash
php dev-tools/sql/run_cabinet_portfolios_article.php
# Publishes article from HTML file to live DB
```

**SOP Reference:** `.cursor/sop/NEWS-ARTICLE-PUBLISHING-SOP.md`, `.cursor/sop/NEWS-ARTICLE-HTML-QA-SOP.md`

---

## Classifieds

**Template Path:** `dev-tools/classifieds/insert_*.php`

**What to Expect:**
- Expects: `$title`, `$description`, `$category_id`, `$slug`, `$contact_phone`, `$contact_email`
- Returns: Classified ID on successful INSERT

---

## Properties (Rent-Lease)

**Template Path:** `dev-tools/rent-lease/insert_*.php`

**What to Expect:**
- Expects: `$title`, `$description`, `$category`, `$locality_id`, `$price`, `$slug`, `$owner_phone`, `$owner_email`
- Returns: Property ID on successful INSERT
- IMPORTANT: Slug unique per locality (check collision with classifieds too)

**SOP Reference:** `.cursor/sop/DUAL-POST-CLASSIFIED-RENT-LEASE-SOP.md`

---

## Past Events Cleanup

**Template Path:** `dev-tools/events/run-close-past-events.php`

**Purpose:** Remove past-date events from "featured" list

**What to Expect:**
- No parameters
- Marks events with `event_date < TODAY()` as `is_featured = 0`
- Should be run as cron job (daily at 00:00)

---

## Common Patterns

### Dry Run (Test Mode)

All templates support dry-run before live:

```php
// At top of template:
$test_mode = true; // Set to false for live

// Later in template:
if ($test_mode) {
    echo "SQL Preview:\n";
    echo $sql . "\n";
    // No DB changes
} else {
    // Execute INSERT
}
```

### Connection

All templates use:

```php
require_once ROOT_PATH . '/core/omr-connect.php';
$db = new mysqli($db_host, $db_user, $db_pass, $db_name);
```

Connection parameters from `core/omr-connect.php` or env vars:
- `DB_HOST` — Default: localhost, override with `'myomr.in'` for remote
- `DB_USER`, `DB_PASS`, `DB_NAME` — From env or config

### Validation

All templates should check:

```php
// Before INSERT, validate:
1. Schema: All columns exist
2. FK refs: Category ID, locality ID, employer ID exist
3. Uniqueness: Slug not already used (if applicable)
4. Enums: Category, status, etc. are valid values
5. Dates: Expiry in future, event_date for events
6. Privacy: hide_employer_phone is 0 or 1
```

### Error Handling

Templates should:

```php
// If error:
die("Error: " . $db->error . "\n");

// On success:
echo "Success! Entity ID: " . $db->insert_id . "\n";
```

---

## Creating a New Template

**If you need to publish an entity that doesn't have a template:**

1. Find similar template (e.g., if adding "coworking spaces", use jobs template as base)
2. Copy to `dev-tools/{entity}/insert_{name}.php`
3. Adapt variables for your entity (see schema in `docs/data-model.md`)
4. Add validation checks
5. Add dry-run support
6. Test locally first (`$test_mode = true`)
7. Test on remote DB with test data
8. Create SOP for future use
9. Update this index

---

## Remote MySQL Setup

**To run templates against live cPanel DB:**

```bash
# 1. Get your public IP:
curl https://api.ipify.org

# 2. Add IP to cPanel Remote MySQL (one-time)
# cPanel → Remote MySQL → Authorize

# 3. Test connection:
mysql -h myomr.in -u {DB_USER} -p -e "SELECT 1;"

# 4. Run template:
php dev-tools/jobs/insert_*.php

# Note: Use DB_HOST env var:
DB_HOST='myomr.in' php dev-tools/jobs/insert_*.php
```

See `README-REMOTE-DATABASE.md` for full setup.

---

## Troubleshooting

| Problem | Solution |
|---------|----------|
| "Column doesn't exist" | Check schema in `docs/data-model.md` vs template |
| "Slug collision" | Use `-v2` suffix or check both tables if dual-post |
| "FK constraint error" | Verify category_id, locality_id, employer_id exist |
| "Connection refused" | Check IP whitelisted on cPanel Remote MySQL |
| "Connection timeout" | Check port 3306 open, network firewall |
| "Can't load template" | Check file path, verify ROOT_PATH set |

---

## Related Documents

- [LIVE-PUBLISH-CHECKLIST-SOP.md](./LIVE-PUBLISH-CHECKLIST-SOP.md) — Master checklist
- [docs/data-model.md](../docs/data-model.md) — All table schemas
- [docs/validation.md](../docs/validation.md) — Quality gates
- [README-REMOTE-DATABASE.md](../../README-REMOTE-DATABASE.md) — Remote MySQL setup
- [KNOWN-ISSUES.md](../KNOWN-ISSUES.md) — Common gotchas

---

**Last Updated:** 2026-05-19  
**Next Update:** When new entity type templates added
