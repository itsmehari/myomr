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

## If deployment is disabled or “not activated”

cPanel only shows **Deploy** when all of the following are true:

1. **`.cpanel.yml` is in the repo root** and has been **pushed** to the remote GitHub uses for “Update from Remote”.
2. **YAML is valid** (spaces only for indentation—no tabs). On Windows, use **LF** line endings for `.cpanel.yml` (this repo sets that via `.gitattributes`).
3. **`DEPLOYPATH` in `.cpanel.yml`** must match the **Deployment Path** you set in cPanel (same absolute path, trailing slash).  
   - Primary domain: often `/home/USERNAME/public_html/`  
   - Addon domain (e.g. `myomr.in`): often `/home/USERNAME/myomr.in/` or the path under **Domains** → *domain* → **Document Root** in cPanel.
4. **Clean state** in the cPanel clone (no uncommitted deploy errors). Pull latest, then **Deploy HEAD Commit** again.

If you change only `DEPLOYPATH`, edit the first `export` line in `.cpanel.yml`, commit, push, then redeploy from cPanel.

---

## Verify Before Deployment

- `.env` and database config are excluded via `.gitignore` – ensure production config is set on the server.
- `uploads/` content is ignored – uploaded files must exist or be handled separately on the server.
- After first deploy, confirm `omr-connect.php` or equivalent uses correct production DB credentials.
