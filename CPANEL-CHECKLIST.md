# cPanel Git Deployment Checklist

Use this checklist for first-time setup.

---

## GitHub

- [ ] Create a new repository on GitHub (do not initialize with README if you already have local code)
- [ ] Note the clone URL: `https://github.com/YOUR_USERNAME/YOUR_REPO.git`

---

## Local Push to GitHub

- [ ] Add remote (if needed):  
  `git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git`
- [ ] Ensure branch is main:  
  `git branch -M main`
- [ ] Push:  
  `git push -u origin main`

---

## cPanel Git Version Control

- [ ] Log in to cPanel
- [ ] Open **Git Version Control**
- [ ] Ensure **Clone a Repository** is selected
- [ ] Paste GitHub Clone URL: `https://github.com/YOUR_USERNAME/YOUR_REPO.git`
- [ ] Set **Repository Path** to:  
  `/home3/CPANEL_USERNAME/repositories/REPO_NAME`
- [ ] Set **Repository Name** to: `REPO_NAME`
- [ ] Click **Create**

---

## Configure Deployment

- [ ] Click **Manage** next to the new repository
- [ ] Open **Deploy** tab
- [ ] Set **Deploy Path** to:  
  `/home3/CPANEL_USERNAME/public_html/`
- [ ] Save settings

---

## First Deployment

- [ ] Run **Pull or Update** to fetch latest from GitHub
- [ ] Run **Deploy** to copy files into `public_html/`

---

## Subsequent Deployments

1. Locally: `bash deploy.sh` or `git push origin main`
2. cPanel: **Pull or Update** → **Deploy**
