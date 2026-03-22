# MyOMR.in – cPanel Git Deployment Guide

This document describes the **pull-based** deployment workflow from GitHub to cPanel shared hosting via Git Version Control.

---

## Project Type

**Plain PHP/Static Site.** Deploy the repository source directly to `public_html`. No build step required.

---

## Local Workflow

1. Make changes in Cursor IDE (or your editor).
2. Run deployment:
   - **Windows (PowerShell/WSL):** `bash deploy.sh` or `.\deploy.bat`
   - **Unix/macOS:** `./deploy.sh`
3. Optional custom message: `bash deploy.sh "fix job listings form"`

---

## GitHub Push Workflow

1. Ensure `origin` points to your GitHub repo:
   ```bash
   git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git
   git branch -M main
   git push -u origin main
   ```
2. For later deploys: `git push origin main` (or use `deploy.sh`).

---

## cPanel Repository Setup

### Important: Repository Path

Do **not** create the repository inside `public_html`. Use a separate path and deploy into `public_html`:

| Field | Value Pattern |
|-------|----------------|
| **Clone URL** | `https://github.com/YOUR_USERNAME/YOUR_REPO.git` |
| **Repository Path** | `/home3/CPANEL_USERNAME/repositories/REPO_NAME` |
| **Repository Name** | `REPO_NAME` |

Replace:
- `YOUR_USERNAME` – GitHub username
- `YOUR_REPO` – GitHub repository name
- `CPANEL_USERNAME` – cPanel account username
- `REPO_NAME` – e.g. `myomr` or `myomr-in`

### Deployment Target

| Field | Value |
|-------|-------|
| **Deploy Path** | `/home3/CPANEL_USERNAME/public_html/` |

---

## Step-by-Step in cPanel

1. **Clone a Repository**
   - cPanel → Git Version Control → Clone a Repository
   - Paste GitHub clone URL
   - Repository Path: `/home3/CPANEL_USERNAME/repositories/REPO_NAME`
   - Repository Name: `REPO_NAME`
   - Create

2. **Manage Deployment**
   - Open the repository → **Manage**
   - **Deploy** tab
   - Set deploy path to: `/home3/CPANEL_USERNAME/public_html/`
   - Save

3. **Pull or Update**
   - Run **Pull or Update** to fetch latest from GitHub

4. **Deploy**
   - Run **Deploy** to copy files into `public_html/`

---

## If “Deploy HEAD Commit” is greyed out (cannot deploy)

Per [cPanel: Git Deployment](https://go.cpanel.net/GitDeployment), deployment stays disabled until **both** are true:

1. A **valid** `.cpanel.yml` exists in the **top level of the Git repository** (the clone under `~/repositories/…`, **not** only inside the public site folder).
2. The repository has a **clean working tree** (no uncommitted changes on the checked-out branch).

Seeing `.cpanel.yml` in **File Manager** under `/home3/metap8ok/myomr.in/` only proves the file was copied to the live site; cPanel still validates the copy inside **`/home3/metap8ok/repositories/myomr-main/`** (your repo path).

### Fix A: Clean working tree (most common)

After “Update from Remote”, Git may still report a **dirty** tree (line endings, permissions, or local edits). **SSH / Terminal** (cPanel → Advanced → Terminal), then:

```bash
cd /home3/metap8ok/repositories/myomr-main
git config core.autocrlf false
git fetch origin
git reset --hard origin/main
git clean -fd
git status
```

`git status` must show **“nothing to commit, working tree clean”**. Then open **Git Version Control** again — **Deploy HEAD Commit** should enable.

### If you do not have Terminal or SSH

Many shared plans hide **Terminal** or disable shell login. You can still fix a dirty repo without the command line:

**Option 1 — Re-clone in cPanel (usually the cleanest without SSH)**

1. In **cPanel → Git Version Control**, open your repo (**myomr-main**).
2. Use the interface option to **remove / delete** this repository from cPanel (only the clone under `repositories/…`; it does not delete your GitHub repo or your live site files under `myomr.in`).
3. **Clone the repository again** from the same GitHub URL (`https://github.com/itsmehari/myomr.git`), same or new name, into something like `/home3/metap8ok/repositories/myomr-main` (or the path cPanel suggests).
4. Open **Manage → Deploy** and set **Deployment Path** to your site root again: **`/home3/metap8ok/myomr.in/`** (must match `.cpanel.yml` and your domain’s document root).
5. Use **Update from Remote**, then try **Deploy HEAD Commit**.

A fresh clone starts with a **clean working tree**, which unlocks deploy when `.cpanel.yml` is valid.

**Option 2 — Enable SSH (HostGator / many hosts)**

- In cPanel look under **Security** for **SSH Access** / **Manage SSH Keys** and enable access if the host allows it, or open a ticket asking to **enable jailed SSH** for your account.

**Option 3 — Support ticket**

Ask support to run in your account (paths from your setup):

`cd /home3/metap8ok/repositories/myomr-main && git config core.autocrlf false && git fetch origin && git reset --hard origin/main && git clean -fd`

### Fix B: YAML and path

- **Indentation:** spaces only (no tabs). **LF** line endings for `.cpanel.yml` (see `.gitattributes` in this repo).
- **`DEPLOYPATH`** in `.cpanel.yml` must match **Git → Manage → Deploy** for this repo (e.g. `/home3/metap8ok/myomr.in/` if that is the document root for `myomr.in`).

### After changing `DEPLOYPATH` only

Edit the `export DEPLOYPATH=...` line in `.cpanel.yml`, commit, push to GitHub, run **Update from Remote** in cPanel, then **Deploy HEAD Commit**.

---

## Verify Before Deployment

- `.env` and database config are excluded via `.gitignore` – ensure production config is set on the server.
- `uploads/` content is ignored – uploaded files must exist or be handled separately on the server.
- After first deploy, confirm `omr-connect.php` or equivalent uses correct production DB credentials.
