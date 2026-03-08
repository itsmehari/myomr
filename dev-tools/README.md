# 🛠️ Development Tools for MyOMR Database

This folder contains **LOCAL DEVELOPMENT TOOLS ONLY** - not for production use!

---

## 📁 Contents

### **Database Management Tools**

| File                     | Purpose                        | Usage                                             |
| ------------------------ | ------------------------------ | ------------------------------------------------- |
| `crud-operations.php`    | Full CRUD interface            | http://localhost/dev-tools/crud-operations.php    |
| `db-test-connection.php` | Test remote DB connection      | http://localhost/dev-tools/db-test-connection.php |
| `db-export-schema.php`   | Export CREATE TABLE statements | http://localhost/dev-tools/db-export-schema.php   |
| `db-quick-query.php`     | Run quick SQL queries          | http://localhost/dev-tools/db-quick-query.php     |
| `db-backup-tool.php`     | Create database backups        | http://localhost/dev-tools/db-backup-tool.php     |

### **SSH Tunnel Scripts**

| File               | Purpose                      | Usage                          |
| ------------------ | ---------------------------- | ------------------------------ |
| `start-tunnel.ps1` | Start SSH tunnel (Windows)   | `.\dev-tools\start-tunnel.ps1` |
| `start-tunnel.sh`  | Start SSH tunnel (Mac/Linux) | `./dev-tools/start-tunnel.sh`  |

### **Configuration**

| File                | Purpose                     | Usage                |
| ------------------- | --------------------------- | -------------------- |
| `config-remote.php` | Remote DB connection config | Include in dev tools |
| `.htaccess`         | Block public access         | Auto-protection      |

---

## 🚀 Quick Start

### **Step 1: Start SSH Tunnel**

```powershell
# Windows
cd dev-tools
.\start-tunnel.ps1

# Mac/Linux
cd dev-tools
./start-tunnel.sh
```

### **Step 2: Access Tools**

```
Visit: http://localhost/dev-tools/
```

### **Step 3: Choose Your Tool**

- 🔍 **Test Connection** - Verify SSH tunnel works
- ✏️ **CRUD Operations** - Add/Edit/Delete records
- 📤 **Export Schema** - Get CREATE TABLE statements
- ⚡ **Quick Query** - Run SQL queries
- 💾 **Backup** - Create database backups

---

## 🔒 Security

**This folder is protected:**

- ✅ `.htaccess` blocks remote access (localhost only)
- ✅ Password protection on all tools
- ✅ No access from live website
- ✅ Git ignored (sensitive data)

**Never deploy this folder to production!**

---

## 📝 Adding to .gitignore

Add to your `.gitignore`:

```
# Development tools (local only)
/dev-tools/backups/
/dev-tools/exports/
/dev-tools/logs/
/dev-tools/.env
```

---

## 🔧 Configuration

Edit `config-remote.php` if needed:

```php
// Local tunnel port
define('DEV_DB_HOST', '127.0.0.1:3307');

// Same credentials as production
define('DEV_DB_USER', 'metap8ok_myomr_admin');
define('DEV_DB_PASS', 'myomr@123');
define('DEV_DB_NAME', 'metap8ok_myomr');
```

---

## 📖 Documentation

- Full Setup: `/docs/LOCAL_TO_REMOTE_DATABASE_SETUP.md`
- Quick Start: `/docs/REMOTE_DB_QUICK_START.md`
- Database Docs: `/docs/DATABASE_INDEX.md`

---

**Last Updated:** December 26, 2024  
**For:** Local Development Only  
**Status:** Do Not Deploy to Production ⚠️
