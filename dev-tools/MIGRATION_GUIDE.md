# 🔄 Migration Guide: Moving Stray Files to dev-tools

## 📋 Files to Migrate

### **Found Stray Development Files:**

| Current Location                 | Move To       | New Name                 | Status            |
| -------------------------------- | ------------- | ------------------------ | ----------------- |
| `/crud-helper.php`               | `/dev-tools/` | `crud-operations.php`    | ⏳ To Move        |
| `/test-remote-db-connection.php` | `/dev-tools/` | `db-test-connection.php` | ⏳ To Move        |
| `/export-database-schema.php`    | `/dev-tools/` | `db-export-schema.php`   | ⏳ To Move        |
| `/start-ssh-tunnel.ps1`          | `/dev-tools/` | `start-tunnel.ps1`       | ⏳ To Move        |
| `/core/omr-connect-remote.php`   | `/dev-tools/` | `config-remote.php`      | ✅ Already exists |

---

## 🚀 Quick Migration

### **Option 1: Manual Move (Windows Explorer)**

```
1. Open two file explorer windows:
   - Window 1: C:\Users\Admin\OneDrive\_myomr\_Root\
   - Window 2: C:\Users\Admin\OneDrive\_myomr\_Root\dev-tools\

2. Move and rename files:
   crud-helper.php              → dev-tools/crud-operations.php
   test-remote-db-connection.php → dev-tools/db-test-connection.php
   export-database-schema.php   → dev-tools/db-export-schema.php
   start-ssh-tunnel.ps1         → dev-tools/start-tunnel.ps1

3. Delete: core/omr-connect-remote.php (already have config-remote.php)
```

### **Option 2: PowerShell Commands**

```powershell
# Run these commands from project root
cd "C:\Users\Admin\OneDrive\_myomr\_Root"

# Move and rename files
Move-Item "crud-helper.php" "dev-tools/crud-operations.php"
Move-Item "test-remote-db-connection.php" "dev-tools/db-test-connection.php"
Move-Item "export-database-schema.php" "dev-tools/db-export-schema.php"
Move-Item "start-ssh-tunnel.ps1" "dev-tools/start-tunnel.ps1"

# Delete duplicate
Remove-Item "core/omr-connect-remote.php"
```

---

## 🔧 Files Need Updates After Moving

### **1. crud-operations.php (formerly crud-helper.php)**

**Change this line:**

```php
// OLD:
require_once 'core/omr-connect-remote.php';

// NEW:
define('DEV_TOOLS_ACCESS', true);
require_once 'config-remote.php';
```

### **2. db-test-connection.php (formerly test-remote-db-connection.php)**

**Change this line:**

```php
// OLD:
require_once 'core/omr-connect-remote.php';

// NEW:
define('DEV_TOOLS_ACCESS', true);
require_once 'config-remote.php';
```

### **3. db-export-schema.php (formerly export-database-schema.php)**

**Change this line:**

```php
// OLD:
require 'core/omr-connect.php';

// NEW:
define('DEV_TOOLS_ACCESS', true);
require_once 'config-remote.php';
```

**And update connection variable:**

```php
// OLD:
$conn (from omr-connect.php)

// NEW:
$dev_conn (from config-remote.php)
```

---

## ✅ Verification Checklist

After migration, verify:

- [ ] All 4 files moved to `dev-tools/` folder
- [ ] Files renamed correctly
- [ ] `core/omr-connect-remote.php` deleted (duplicate)
- [ ] Start SSH tunnel: `.\dev-tools\start-tunnel.ps1`
- [ ] Test dashboard: `http://localhost/dev-tools/`
- [ ] Test each tool works
- [ ] No errors in browser console
- [ ] Database queries work correctly

---

## 🗑️ Clean Root Folder

**After migration, your root should NOT have:**

- ❌ `crud-helper.php`
- ❌ `test-remote-db-connection.php`
- ❌ `export-database-schema.php`
- ❌ `start-ssh-tunnel.ps1`
- ❌ Any other dev/test files

**Root should ONLY have:**

- ✅ `index.php` (homepage)
- ✅ Production files
- ✅ Folders: `admin/`, `omr-listings/`, `components/`, etc.

---

## 📝 Update .gitignore

Add to `.gitignore`:

```
# Development tools (entire folder except tools themselves)
/dev-tools/backups/
/dev-tools/exports/
/dev-tools/logs/
/dev-tools/.env

# Old stray files (in case they reappear)
/crud-helper.php
/test-remote-db-connection.php
/export-database-schema.php
/start-ssh-tunnel.ps1
/core/omr-connect-remote.php
```

---

## 🚨 Important Notes

1. **Backup First:** Copy files before moving, just in case
2. **Test Everything:** After moving, test all tools
3. **Update Docs:** If you have bookmarks/docs, update paths
4. **Don't Deploy:** Ensure `dev-tools/` never goes to production

---

## 🎯 Final Structure

**After migration, clean structure:**

```
Root/
├── dev-tools/              ← ALL development tools here
│   ├── crud-operations.php
│   ├── db-test-connection.php
│   ├── db-export-schema.php
│   ├── start-tunnel.ps1
│   └── config-remote.php
│
├── index.php               ← Clean root with production files
├── admin/                  ← Production admin panel
├── omr-listings/           ← Production pages
├── components/             ← Production components
└── core/
    └── omr-connect.php     ← Production DB only
```

---

**Last Updated:** December 26, 2024  
**Status:** Ready to migrate  
**Action:** Choose Option 1 or 2 above
