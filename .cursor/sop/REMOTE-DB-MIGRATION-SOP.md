# Remote Database Migration SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Engineering / DBA

---

## 1. Scope

Running **MySQL migrations or SQL scripts** from a developer machine against the **remote** cPanel database (not only localhost).

**Primary doc:** [README-REMOTE-DATABASE.md](../../README-REMOTE-DATABASE.md) (repo root)

---

## 2. Preconditions

| Requirement | Detail |
|-------------|--------|
| Network | Outbound **TCP 3306** allowed from dev machine |
| cPanel | **Remote MySQL®** lists dev machine **public IP** (or `%` if policy allows) |
| PHP | `mysqli` enabled for CLI |
| Credentials | Same as production app: from `core/omr-connect.php` or env vars |

---

## 3. Environment variables

Typical overrides:

- `DB_HOST` — e.g. `myomr.in` (hostname from hosting)
- `DB_PORT` — optional, default 3306
- `DB_USER`, `DB_PASS`, `DB_NAME` — if not using defaults from omr-connect

Scripts that support remote: e.g. `elections-2026/dev-tools/run-election-2026-migration.php` — see script header.

---

## 4. Procedure

1. **Backup** production DB (phpMyAdmin export or host backup) before destructive changes.
2. Note current git commit and migration file name.
3. From repo root (example):  
   `set DB_HOST=myomr.in` (PowerShell: `$env:DB_HOST='myomr.in'`)  
   `php path/to/migration.php`
4. Verify: run `SELECT` checks described in migration or spot-check tables.
5. Deploy any **code** that depends on new columns **after** or **with** migration (order matters).

---

## 5. Validation checklist

- [ ] Migration completes without SQL errors
- [ ] Application pages using new schema do not fatal
- [ ] Rollback plan documented if multi-step migration

---

## 6. Failure handling

| Symptom | Likely cause |
|---------|----------------|
| Connection refused | Firewall or wrong host/port |
| Access denied | Wrong user/password |
| Host not allowed | Add current public IP in Remote MySQL |

**Second laptop doesn’t connect:** different public IP — add that IP.

---

## 7. Security

- Do not commit `.env` with production passwords
- Do not paste DB credentials in chat

---

## 8. Related references

- [README-REMOTE-DATABASE.md](../../README-REMOTE-DATABASE.md)
- [elections-2026/dev-tools/README-REMOTE-DB.md](../../elections-2026/dev-tools/README-REMOTE-DB.md)
- [.cursor/LEARNINGS.md](../LEARNINGS.md) — Remote database section
