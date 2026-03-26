# cPanel Git Deployment SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Engineering / Ops

---

## 1. Scope

Deploying code from the Git repository to **shared hosting** using the project’s **cPanel deploy** configuration.

**Config file:** [.cpanel.yml](../../.cpanel.yml)

---

## 2. How it works

- cPanel **Git Version Control** (or similar) runs tasks in `.cpanel.yml` after pull.
- Tasks copy directories and selected root files into `DEPLOYPATH` (example in file: `/home3/.../myomr.in/`).

---

## 3. Preconditions

- Commit pushed to branch tracked by cPanel
- `.cpanel.yml` paths updated if new top-level folders or root PHP files are added
- Sensitive files **excluded** from delete/sync per hosting config (uploads, `.env`, sessions — per AGENTS.md)

---

## 4. Procedure

1. **Merge** changes to the deployment branch (often `main`).
2. **Push** to `origin`.
3. In cPanel: **Pull** or **Deploy** per host UI.
4. Watch deploy log for `cp` errors (missing path, permission denied).

---

## 5. Adding new deploy paths

If you add:

- A **new top-level directory** (e.g. new module): add `- /bin/cp -R new-folder $DEPLOYPATH` (or fold into existing `-R` line if grouped).
- A **new root PHP file**: add to the explicit `cp` line for single files in `.cpanel.yml`.

Omitting a path causes **production missing file** fatals (see LEARNINGS: hostels bootstrap).

---

## 6. Post-deploy smoke tests

- [ ] Homepage loads
- [ ] One news article, one job detail, one module index
- [ ] `/sitemap.xml` returns valid XML
- [ ] Admin login page loads (if touched)

---

## 7. Rollback

1. Revert commit locally and push; redeploy **or**
2. Host restore from backup if DB + files mixed failure

---

## 8. Related references

- [AGENTS.md](../../AGENTS.md) — Deployment section
- [MODULAR-BOOTSTRAP-NEW-PAGE-SOP.md](MODULAR-BOOTSTRAP-NEW-PAGE-SOP.md)
- [INCIDENT-DEBUG-TRIAGE-SOP.md](INCIDENT-DEBUG-TRIAGE-SOP.md)
