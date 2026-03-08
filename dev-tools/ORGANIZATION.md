# 📂 File Organization: Development vs Production

## ✅ **Clear Separation of Concerns**

This document explains which files are for **manual development workflow** vs **production website**.

---

## 🛠️ **Development Tools (Manual Workflow)**

**Location:** `dev-tools/` folder

**Purpose:** Tools YOU use locally to manage the database

```
dev-tools/
├── 📄 README.md                    ← Documentation for this folder
├── 📄 ORGANIZATION.md               ← This file
├── 🔒 .htaccess                     ← Security (localhost only)
├── ⚙️ config-remote.php            ← DB connection for dev tools
├── 🏠 index.php                     ← Dev tools dashboard
│
├── 🔧 CRUD Operations
│   └── crud-operations.php          ← Create, Read, Update, Delete interface
│
├── 🔍 Query & Testing
│   ├── db-quick-query.php           ← Run SQL queries
│   └── db-test-connection.php       ← Test SSH tunnel
│
├── 📤 Export Tools
│   ├── db-export-schema.php         ← Export CREATE TABLE statements
│   ├── db-export-data.php           ← Export data to CSV/Excel
│   └── db-backup-tool.php           ← Full database backups
│
├── 🔐 SSH Tunnel Scripts
│   ├── start-tunnel.ps1             ← Windows SSH tunnel
│   └── start-tunnel.sh              ← Mac/Linux SSH tunnel
│
└── 📁 Subfolders
    ├── backups/                     ← Database backups (gitignored)
    ├── exports/                     ← Exported data (gitignored)
    └── logs/                        ← Activity logs (gitignored)
```

**Access:** `http://localhost/dev-tools/`

**Security:**

- ✅ Protected by `.htaccess` (localhost only)
- ✅ Never deployed to production
- ✅ Added to `.gitignore`

---

## 🌐 **Production Website Files**

**Location:** Root and various folders

**Purpose:** Public-facing website and admin panel

```
Root/
├── 🏠 Homepage
│   ├── index.php                    ← Main website homepage
│   └── home-page-news-cards.php     ← News display component
│
├── 🗄️ Database Connection (Production)
│   └── core/
│       └── omr-connect.php          ← Production DB connection
│                                       (uses port 3306, NOT for dev tools)
│
├── 👥 Public Pages
│   ├── omr-listings/                ← Public directory pages
│   │   ├── schools.php
│   │   ├── hospitals.php
│   │   ├── restaurants.php
│   │   └── ...
│   │
│   ├── local-news/                  ← News articles
│   ├── events/                      ← Events pages
│   └── listings/                    ← Job/property listings
│
├── 🔐 Admin Panel (Production)
│   └── admin/                       ← Website admin interface
│       ├── login.php
│       ├── dashboard.php
│       ├── news-add.php
│       ├── restaurants-add.php
│       └── ...
│
└── 🧩 Components
    └── components/                  ← Reusable website parts
        ├── main-nav.php
        ├── footer.php
        └── ...
```

**Access:**

- Public: `https://myomr.in/`
- Admin: `https://myomr.in/admin/`

---

## 🔄 **Workflow Comparison**

### **Development Workflow (YOU)**

```
┌─────────────────────────────────────────┐
│  YOUR COMPUTER (Windows Local)          │
│                                          │
│  1. Run: start-tunnel.ps1               │
│     └─ Opens SSH tunnel to myomr.in     │
│                                          │
│  2. Visit: http://localhost/dev-tools/  │
│     └─ Access dev dashboard              │
│                                          │
│  3. Use Tools:                           │
│     • CRUD Operations                    │
│     • Quick Query                        │
│     • Export Schema                      │
│     • Backup Tool                        │
│                                          │
│  4. Changes go to: LIVE DATABASE        │
│     └─ Via SSH tunnel (port 3307)       │
└─────────────────────────────────────────┘
```

### **Production Workflow (Website Users)**

```
┌─────────────────────────────────────────┐
│  PUBLIC USERS                            │
│                                          │
│  1. Visit: https://myomr.in/            │
│     └─ See homepage, news, listings      │
│                                          │
│  2. Browse:                              │
│     • Schools directory                  │
│     • Restaurants                        │
│     • Events                             │
│     • News articles                      │
│                                          │
│  3. Data comes from: LIVE DATABASE      │
│     └─ Via core/omr-connect.php         │
│        (port 3306, direct connection)   │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  ADMIN USERS                             │
│                                          │
│  1. Visit: https://myomr.in/admin/      │
│     └─ Login to admin panel              │
│                                          │
│  2. Manage Content:                      │
│     • Add/edit news                      │
│     • Add/edit restaurants               │
│     • Add/edit events                    │
│                                          │
│  3. Changes go to: LIVE DATABASE        │
│     └─ Via core/omr-connect.php         │
└─────────────────────────────────────────┘
```

---

## 📋 **Quick Reference Table**

| File                   | Type            | Used By       | Connection             | Port |
| ---------------------- | --------------- | ------------- | ---------------------- | ---- |
| `dev-tools/*`          | **Development** | YOU (local)   | `config-remote.php`    | 3307 |
| `core/omr-connect.php` | **Production**  | Website users | Direct                 | 3306 |
| `admin/*`              | **Production**  | Admin users   | `core/omr-connect.php` | 3306 |
| `index.php`            | **Production**  | Public users  | `core/omr-connect.php` | 3306 |

---

## 🎯 **Key Differences**

### **Development Tools (dev-tools/)**

- ✅ **Location:** `dev-tools/` folder
- ✅ **Access:** `http://localhost/dev-tools/` (local only)
- ✅ **Connection:** SSH tunnel via port 3307
- ✅ **Security:** `.htaccess` blocks remote access
- ✅ **Purpose:** Manual database management
- ✅ **Users:** Only YOU (developers)
- ⚠️ **Never deploy to production!**

### **Production Files (root/various)**

- ✅ **Location:** Root and subdirectories
- ✅ **Access:** `https://myomr.in/` (public)
- ✅ **Connection:** Direct via port 3306
- ✅ **Security:** Session-based authentication
- ✅ **Purpose:** Public website & admin panel
- ✅ **Users:** Website visitors & admins
- ✅ **Always deployed to production**

---

## 🔒 **Security Configuration**

### **1. .htaccess Protection (dev-tools/)**

```apache
# Only allow localhost access
<RequireAll>
    Require ip 127.0.0.1
    Require ip ::1
</RequireAll>
```

### **2. .gitignore Configuration**

Add to your `.gitignore`:

```
# Development tools - DO NOT COMMIT
/dev-tools/backups/
/dev-tools/exports/
/dev-tools/logs/
/dev-tools/.env

# But DO commit the tools themselves
!/dev-tools/*.php
!/dev-tools/*.ps1
!/dev-tools/*.sh
!/dev-tools/README.md
```

### **3. Deployment Configuration**

When deploying to production:

```bash
# DO deploy:
- index.php
- admin/*
- omr-listings/*
- components/*
- core/omr-connect.php

# DO NOT deploy:
- dev-tools/ (entire folder)
- test-remote-db-connection.php (if in root)
- crud-helper.php (if in root)
- export-database-schema.php (if in root)
- start-ssh-tunnel.ps1
```

---

## 📝 **Migration Checklist**

If you want to move existing files to dev-tools:

- [ ] Move `crud-helper.php` → `dev-tools/crud-operations.php`
- [ ] Move `test-remote-db-connection.php` → `dev-tools/db-test-connection.php`
- [ ] Move `export-database-schema.php` → `dev-tools/db-export-schema.php`
- [ ] Move `start-ssh-tunnel.ps1` → `dev-tools/start-tunnel.ps1`
- [ ] Copy `core/omr-connect-remote.php` → `dev-tools/config-remote.php`
- [ ] Update all includes to use `config-remote.php`
- [ ] Test all tools work from new location
- [ ] Add `dev-tools/` to deployment exclusions
- [ ] Update documentation links

---

## ✅ **Summary**

**YES - All manual development tools are now in a single folder:**

```
📁 dev-tools/          ← ONE FOLDER FOR ALL DEVELOPMENT
   ├── All CRUD tools
   ├── All query tools
   ├── All export tools
   ├── SSH tunnel scripts
   ├── Configuration
   └── Documentation
```

**Benefits:**

- ✅ Clean separation from production code
- ✅ Easy to exclude from deployment
- ✅ Secure (localhost only)
- ✅ Organized and documented
- ✅ All tools in one place

**Access:**

```
http://localhost/dev-tools/  ← Your development dashboard
```

---

**Last Updated:** December 26, 2024  
**Purpose:** Organize development vs production files  
**Status:** Clean & Secure Structure ✅
