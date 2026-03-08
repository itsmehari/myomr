# 🎯 MyOMR.in - Master Project Documentation

**Version:** 2.0.0  
**Last Updated:** December 26, 2024  
**Status:** Production Active  
**Database:** Live (metap8ok_myomr)

---

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Critical Rules & Guidelines](#critical-rules--guidelines)
3. [Project Structure](#project-structure)
4. [Development Workflow](#development-workflow)
5. [Database Management](#database-management)
6. [Deployment Guidelines](#deployment-guidelines)
7. [Security Rules](#security-rules)
8. [AI Assistant Guidelines](#ai-assistant-guidelines)
9. [Major Updates Log](#major-updates-log)
10. [Quick Reference](#quick-reference)

---

## 🎯 Project Overview

### **What is MyOMR.in?**

A digital community hub for Old Mahabalipuram Road (OMR) corridor in Chennai, India. Connects residents, businesses, and visitors with local news, events, jobs, and services.

### **Core Technologies**

- **Frontend:** HTML5, CSS3, Bootstrap 5, Vanilla JavaScript
- **Backend:** PHP 7.4+ (Procedural)
- **Database:** MySQL 5.7+ (metap8ok_myomr)
- **Hosting:** cPanel Shared Hosting (myomr.in)
- **Local Dev:** Windows 10/11 via SSH tunnel

### **Key Features**

- 📰 Local news & community updates
- 📅 Events & happenings
- 🏢 Business directory (15+ categories)
- 💼 Job & property listings
- 🗺️ Location-based services
- 🔐 Admin panel for content management

---

## ⚠️ Critical Rules & Guidelines

### **🚨 NEVER DO THESE:**

1. **❌ NEVER deploy `dev-tools/` folder to production**

   - Contains local development tools only
   - Protected by .htaccess (localhost only)
   - Exposes database access if deployed

2. **❌ NEVER commit passwords to Git**

   - Use environment variables
   - Keep credentials in config files (gitignored)
   - Never push database dumps with data

3. **❌ NEVER test destructive queries on live database**

   - Always backup first
   - Test on local environment via SSH tunnel
   - Use transactions for critical operations

4. **❌ NEVER skip input validation**

   - Always use prepared statements
   - Sanitize all user input
   - Validate on both client and server side

5. **❌ NEVER force push to main/master**
   - Linear history preferred
   - No rewriting published history
   - Use feature branches for changes

### **✅ ALWAYS DO THESE:**

1. **✅ ALWAYS separate dev tools from production code**

   - Dev tools: `dev-tools/` folder
   - Production: Root and subdirectories
   - Clear separation of concerns

2. **✅ ALWAYS use prepared statements for database queries**

   ```php
   // CORRECT:
   $stmt = $conn->prepare('SELECT * FROM table WHERE id = ?');
   $stmt->bind_param('i', $id);

   // WRONG:
   $sql = "SELECT * FROM table WHERE id = $id";
   ```

3. **✅ ALWAYS backup before schema changes**

   ```bash
   mysqldump -u user -p database > backup_$(date +%Y%m%d).sql
   ```

4. **✅ ALWAYS test locally before deploying**

   - Use SSH tunnel to test with live data
   - Verify in local environment first
   - Check for errors in browser console

5. **✅ ALWAYS update documentation**
   - Update CHANGELOG.md for version changes
   - Update relevant docs/ files for features
   - Keep PROJECT_MASTER.md current

---

## 📁 Project Structure

### **Production Files (Deploy These)**

```
Root/
├── index.php                    ← Homepage
├── admin/                       ← Admin panel
│   ├── login.php
│   ├── dashboard.php
│   ├── news-add.php
│   ├── restaurants-add.php
│   └── ...
├── omr-listings/                ← Public directories
│   ├── schools.php
│   ├── restaurants.php
│   └── directory-*.php
├── local-news/                  ← News articles
├── events/                      ← Events pages
├── listings/                    ← Job/property
├── components/                  ← Reusable UI
│   ├── main-nav.php
│   ├── footer.php
│   └── ...
├── core/                        ← Core logic
│   ├── omr-connect.php         ← Production DB connection
│   └── security-helpers.php
└── assets/                      ← CSS, JS, images
    ├── css/main.css
    └── js/
```

### **Development Files (NEVER Deploy)**

```
dev-tools/                       ← Development tools only
├── index.php                    ← Dev dashboard
├── config-remote.php            ← Dev DB connection
├── crud-helper.php              ← CRUD operations
├── test-remote-db-connection.php ← Connection tester
├── export-database-schema.php  ← Schema exporter
├── start-ssh-tunnel.ps1         ← SSH tunnel script
├── .htaccess                    ← Localhost protection
└── README.md                    ← Dev tools docs
```

### **Documentation Files**

```
docs/
├── DATABASE_INDEX.md            ← Database docs hub
├── DATABASE_STRUCTURE.md        ← Complete schemas
├── DATABASE_QUICK_REFERENCE.md  ← Quick queries
├── DATABASE_VISUAL_MAP.md       ← Visual diagrams
├── LOCAL_TO_REMOTE_DATABASE_SETUP.md ← Connection guide
├── ARCHITECTURE.md              ← System architecture
├── USER_GUIDE_V2.md            ← End user guide
├── TODO.md                      ← Current tasks
└── CHANGELOG.md                 ← Version history
```

---

## 🛠️ Development Workflow

### **Setup (One-Time)**

1. **Clone Repository:**

   ```bash
   git clone https://github.com/yourusername/myomr-root.git
   cd myomr-root
   ```

2. **Setup Local Environment:**

   - Install XAMPP/WAMP (Apache + PHP + MySQL)
   - Or use PHP built-in server: `php -S localhost:80`

3. **Configure SSH Tunnel:**

   ```powershell
   cd dev-tools
   .\start-ssh-tunnel.ps1
   # Enter cPanel username and password
   ```

4. **Verify Connection:**
   ```
   Visit: http://localhost/dev-tools/
   Check: Green "Connected" status
   ```

### **Daily Workflow**

```
Morning:
1. Start SSH tunnel: .\dev-tools\start-ssh-tunnel.ps1
2. Start local server: php -S localhost:80 (or XAMPP)
3. Visit: http://localhost/dev-tools/
4. Verify connection is green

Development:
1. Make changes to production files
2. Test locally: http://localhost/
3. Use dev tools for database work
4. Check browser console for errors

End of Day:
1. Commit changes: git add . && git commit -m "message"
2. Push to repo: git push origin main
3. Close SSH tunnel (Ctrl+C in PowerShell)
4. Stop local server
```

### **Making Database Changes**

```
1. Backup first:
   Visit: http://localhost/dev-tools/
   Click: "Backup Tool"

2. Use CRUD tool or Quick Query

3. Test the change:
   Visit: http://localhost/
   Verify data displays correctly

4. Document in CHANGELOG.md if schema changed
```

---

## 🗄️ Database Management

### **Database Details**

- **Name:** metap8ok_myomr
- **Host:** localhost (via SSH tunnel: 127.0.0.1:3307)
- **Engine:** MySQL 5.7+
- **Charset:** utf8mb4
- **Collation:** utf8mb4_unicode_ci

### **15+ Tables**

- Content: news_bulletin, events, gallery, businesses
- Directories: omr_restaurants, omrschoolslist, omrhospitalslist, omrbankslist, omratmslist, omritcompanieslist, omrindustrieslist, omrparkslist, omrgovernmentofficeslist
- System: List of Areas, admin_users

### **Connection Methods**

**Production (Live Website):**

```php
// Uses: core/omr-connect.php
$servername = "localhost:3306";
$conn = new mysqli($servername, $username, $password, $database);
```

**Development (Local via SSH):**

```php
// Uses: dev-tools/config-remote.php
$servername = "127.0.0.1:3307";  // SSH tunnel
$dev_conn = new mysqli($servername, $username, $password, $database);
```

### **Database Access**

| Method           | Purpose               | Access                      |
| ---------------- | --------------------- | --------------------------- |
| **phpMyAdmin**   | Web-based management  | cPanel → phpMyAdmin         |
| **Dev Tools**    | Local CRUD operations | http://localhost/dev-tools/ |
| **HeidiSQL**     | Desktop client        | Via SSH tunnel              |
| **Command Line** | Direct MySQL access   | SSH → mysql command         |

### **Documentation**

- Full Reference: `docs/DATABASE_STRUCTURE.md`
- Quick Queries: `docs/DATABASE_QUICK_REFERENCE.md`
- Visual Map: `docs/DATABASE_VISUAL_MAP.md`
- Connection Setup: `docs/LOCAL_TO_REMOTE_DATABASE_SETUP.md`

---

## 🚀 Deployment Guidelines

### **What to Deploy**

**✅ Deploy:**

- `index.php` and all production PHP files
- `admin/` folder (complete)
- `omr-listings/` folder
- `local-news/` folder
- `events/` folder
- `components/` folder
- `core/omr-connect.php` (production DB only)
- `assets/` folder (CSS, JS, images)
- `.htaccess` (production rules)

**❌ NEVER Deploy:**

- `dev-tools/` folder (entire folder!)
- `start-ssh-tunnel.ps1`
- `test-remote-db-connection.php` (if in root)
- `crud-helper.php` (if in root)
- `export-database-schema.php` (if in root)
- `core/omr-connect-remote.php`
- `.env` files with credentials
- Database backups
- Log files

### **Deployment Checklist**

```
Pre-Deployment:
- [ ] Test all changes locally
- [ ] Check browser console for errors
- [ ] Verify database queries work
- [ ] Review linter warnings
- [ ] Update CHANGELOG.md
- [ ] Commit and push to Git

Deployment:
- [ ] Backup live database
- [ ] Backup live files via cPanel
- [ ] Upload changed files via FTP/File Manager
- [ ] Exclude dev-tools/ folder
- [ ] Verify file permissions (755 for folders, 644 for files)
- [ ] Test live site immediately

Post-Deployment:
- [ ] Check live site homepage
- [ ] Test admin panel
- [ ] Verify directory pages
- [ ] Check browser console
- [ ] Monitor error logs
```

### **Rollback Plan**

If deployment fails:

```
1. Restore files from backup
2. Restore database from backup (if schema changed)
3. Clear cache (browser + server)
4. Test site functionality
5. Document what went wrong
```

---

## 🔒 Security Rules

### **File Security**

1. **Sensitive Files:**

   ```
   - core/omr-connect.php (644 permissions)
   - admin/*.php (session checks required)
   - dev-tools/.htaccess (blocks remote access)
   ```

2. **Directory Protection:**

   ```apache
   # In .htaccess
   Options -Indexes
   <FilesMatch "\.(md|log|sql|bak)$">
       Require all denied
   </FilesMatch>
   ```

3. **File Permissions:**
   ```
   Folders: 755
   PHP files: 644
   Config files: 640 (if supported)
   ```

### **Database Security**

1. **Always use prepared statements:**

   ```php
   $stmt = $conn->prepare('SELECT * FROM table WHERE id = ?');
   $stmt->bind_param('i', $id);
   $stmt->execute();
   ```

2. **Sanitize all input:**

   ```php
   $input = htmlspecialchars(strip_tags(trim($_POST['input'])), ENT_QUOTES, 'UTF-8');
   ```

3. **Validate data types:**
   ```php
   $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
   $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
   ```

### **Session Security**

```php
// All admin pages must have:
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}
```

### **Security Helper Functions**

Available in `core/security-helpers.php`:

- `sanitize_input()` - Clean user input
- `validate_email()` - Email validation
- `validate_phone()` - Phone validation
- `csrf_token()` - CSRF protection
- `hash_password()` - Password hashing
- `verify_password()` - Password verification
- `rate_limit()` - Rate limiting
- `log_security_event()` - Security logging

---

## 🤖 AI Assistant Guidelines

### **For AI Systems (Claude, GPT, etc.)**

When working with this project, follow these rules:

#### **1. File Organization**

- ✅ Development tools go in `dev-tools/` folder
- ✅ Production code stays in root/subdirectories
- ✅ Never mix dev and production files
- ✅ Always check for stray files after creating tools

#### **2. Database Operations**

- ✅ Use prepared statements for ALL queries
- ✅ Use `$dev_conn` for dev tools (from config-remote.php)
- ✅ Use `$conn` for production (from omr-connect.php)
- ✅ Always include error handling
- ✅ Provide rollback options for destructive operations

#### **3. Code Standards**

```php
// File naming: kebab-case
database-helper.php ✅
databaseHelper.php ❌

// Function naming: snake_case
function get_user_data() {} ✅
function getUserData() {} ❌

// Class naming: PascalCase
class DatabaseHelper {} ✅
class database_helper {} ❌

// Constants: UPPER_SNAKE_CASE
define('DB_NAME', 'myomr'); ✅
define('dbName', 'myomr'); ❌
```

#### **4. Documentation Updates**

When making changes, update:

- `CHANGELOG.md` - Version history
- `docs/TODO.md` - Task completion
- `PROJECT_MASTER.md` - This file (major changes)
- Relevant `docs/*.md` files
- Code comments (inline documentation)

#### **5. Safety Checks**

Before suggesting code:

- ✅ Is it using prepared statements?
- ✅ Is input validated?
- ✅ Is output escaped?
- ✅ Are errors handled gracefully?
- ✅ Is it documented?
- ✅ Does it follow project conventions?

#### **6. Creating New Tools**

```
New dev tool? → Place in dev-tools/
New admin feature? → Place in admin/
New public page? → Place in appropriate folder
New reusable component? → Place in components/
```

#### **7. Database Schema Changes**

```
1. Document current schema
2. Create backup script
3. Write migration query
4. Test on local
5. Document in DATABASE_STRUCTURE.md
6. Update CHANGELOG.md
7. Provide rollback query
```

---

## 📊 Major Updates Log

### **Version 2.0.0 (December 2024)**

**🎯 Database Documentation:**

- ✅ Complete database structure documented (15+ tables)
- ✅ Created 5 comprehensive documentation files
- ✅ Database visual maps and diagrams
- ✅ Quick reference guides
- ✅ Connection setup guides

**🛠️ Development Tools:**

- ✅ Created `dev-tools/` folder structure
- ✅ CRUD operations interface
- ✅ SSH tunnel automation scripts
- ✅ Database testing tools
- ✅ Schema export tools
- ✅ Backup utilities

**📁 Project Organization:**

- ✅ Separated dev tools from production code
- ✅ Migrated stray files to proper locations
- ✅ Added .htaccess protection for dev tools
- ✅ Clean root directory structure

**🔒 Security Enhancements:**

- ✅ Created `core/security-helpers.php`
- ✅ Prepared statement templates
- ✅ Input validation functions
- ✅ CSRF protection
- ✅ Rate limiting
- ✅ Security event logging

**📖 Documentation:**

- ✅ 20+ documentation files created
- ✅ Complete database reference
- ✅ Development workflow guides
- ✅ Deployment checklists
- ✅ Security guidelines
- ✅ This master file (PROJECT_MASTER.md)

### **Version 1.x (Prior to December 2024)**

- Basic website functionality
- Admin panel for content management
- Directory pages (schools, hospitals, etc.)
- News and events system
- Restaurant ratings with geolocation

---

## 🎯 Quick Reference

### **Essential Commands**

```powershell
# Start development
cd dev-tools
.\start-ssh-tunnel.ps1
php -S localhost:80

# Access tools
http://localhost/dev-tools/

# Backup database
mysqldump -u metap8ok_myomr_admin -p metap8ok_myomr > backup.sql

# Git workflow
git add .
git commit -m "Description"
git push origin main
```

### **Essential Links**

| Resource          | Link                        |
| ----------------- | --------------------------- |
| **Live Site**     | https://myomr.in/           |
| **Admin Panel**   | https://myomr.in/admin/     |
| **Dev Dashboard** | http://localhost/dev-tools/ |
| **phpMyAdmin**    | https://myomr.in:2083/      |
| **Documentation** | `/docs/DATABASE_INDEX.md`   |

### **Essential Files**

| File                     | Purpose                          |
| ------------------------ | -------------------------------- |
| `PROJECT_MASTER.md`      | **This file** - Master reference |
| `README.md`              | Project introduction             |
| `CHANGELOG.md`           | Version history                  |
| `docs/DATABASE_INDEX.md` | Database docs hub                |
| `docs/ARCHITECTURE.md`   | System architecture              |
| `docs/TODO.md`           | Current tasks                    |

### **Database Quick Stats**

```sql
-- Get all table counts
SELECT
  (SELECT COUNT(*) FROM news_bulletin) AS news,
  (SELECT COUNT(*) FROM events) AS events,
  (SELECT COUNT(*) FROM omr_restaurants) AS restaurants,
  (SELECT COUNT(*) FROM omrschoolslist) AS schools;
```

### **Common Issues & Solutions**

| Issue                | Solution                                                                    |
| -------------------- | --------------------------------------------------------------------------- |
| Connection failed    | Start SSH tunnel: `.\dev-tools\start-ssh-tunnel.ps1`                        |
| Port 3307 in use     | Kill process: `netstat -ano \| findstr :3307` then `taskkill /PID [PID] /F` |
| Dev tools 403 error  | Access from localhost only                                                  |
| Database query error | Check prepared statement syntax                                             |
| Files not deploying  | Check .gitignore and deployment exclusions                                  |

---

## 🔄 Maintenance Schedule

### **Daily**

- Monitor error logs
- Check site functionality
- Review security logs

### **Weekly**

- Review slow queries
- Check disk space
- Test backup restoration

### **Monthly**

- Optimize database tables
- Review and update documentation
- Security audit
- Performance review

### **Quarterly**

- Clean old data
- Review and update dependencies
- Comprehensive security review
- User feedback review

### **Annually**

- Full database review
- Architecture assessment
- Major version upgrade planning
- Comprehensive documentation review

---

## 📞 Support & Contact

**Technical Issues:**

- Email: myomrnews@gmail.com
- Check: `docs/` folder for specific guides
- Review: Error logs in cPanel

**Documentation:**

- Main Hub: `PROJECT_MASTER.md` (this file)
- Database: `docs/DATABASE_INDEX.md`
- Architecture: `docs/ARCHITECTURE.md`

---

## ✅ Final Checklist

Before any major work, verify:

- [ ] Read this PROJECT_MASTER.md file
- [ ] Understand dev vs production separation
- [ ] SSH tunnel working
- [ ] Database connection verified
- [ ] Relevant documentation reviewed
- [ ] Backup strategy in place
- [ ] Security guidelines understood
- [ ] Deployment process clear

---

**This is the master reference document. Keep it updated with all major changes!**

**Version:** 2.0.0  
**Last Updated:** December 26, 2024  
**Status:** Living Document - Update as project evolves  
**Owner:** MyOMR Development Team
