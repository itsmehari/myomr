# Incident Debug and Triage SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Engineering (on-call)

---

## 1. Scope

Structured response when **production** shows blank pages, 500 errors, fatal PHP errors, or broken critical paths (homepage, news, jobs).

---

## 2. Severity guide (lightweight)

| Level | Example | Action |
|-------|---------|--------|
| S1 | Site down, homepage 500 | Immediate fix or rollback |
| S2 | Major module broken (e.g. jobs index) | Fix within hours |
| S3 | Single article or edge URL | Normal backlog |

---

## 3. Triage steps (order matters)

### 3.1 Reproduce

1. Confirm URL, HTTP status, browser vs curl.
2. Try incognito (rule out extension).
3. Note exact error: blank vs 500 vs partial HTML.

### 3.2 Compare environments

1. Is failure **only production**? → deploy drift, missing file, PHP version.
2. **Git:** compare last deploy commit vs known good.

### 3.3 Common production causes (MyOMR)

| Cause | How to detect |
|-------|----------------|
| Missing file on server | Fatal "Failed to open stream" in error log |
| Partial deploy | `.cpanel.yml` missing new folder/file |
| DB migration not run | SQL errors in logs, missing column |
| Bootstrap require | Unconditional `require` of optional file — see LEARNINGS hostels |

### 3.4 Logs

1. cPanel **Errors** / PHP error log / `weblog/logfile.txt` if configured.
2. Do not log secrets when adding debug.

### 3.5 Fix patterns

1. **Missing include:** restore file from repo; redeploy [CPANEL-DEPLOYMENT-SOP.md](CPANEL-DEPLOYMENT-SOP.md).
2. **Code regression:** `git revert` bad commit; push; redeploy.
3. **DB:** run missing migration [REMOTE-DB-MIGRATION-SOP.md](REMOTE-DB-MIGRATION-SOP.md) with backup first.

### 3.6 Verify

1. Homepage, one article, one job detail, one module index.
2. Sitemap XML still valid if `.htaccess` or generators touched.

---

## 4. Communication

- Short internal note: impact, cause, fix, follow-up.
- If user-facing: post status in agreed channel.

---

## 5. Post-incident

- [ ] Root cause documented in `.cursor/LEARNINGS.md` if pattern is new
- [ ] Add test or SOP check to prevent recurrence (e.g. `.cpanel.yml` checklist)

---

## 6. Related references

- [.cursor/LEARNINGS.md](../LEARNINGS.md) — Hostels bootstrap fatal example
- [CPANEL-DEPLOYMENT-SOP.md](CPANEL-DEPLOYMENT-SOP.md)
- [404-ERROR-HANDLING-SOP.md](404-ERROR-HANDLING-SOP.md)
