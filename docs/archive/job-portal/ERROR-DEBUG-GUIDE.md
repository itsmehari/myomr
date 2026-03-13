# 🔍 Error Debug Guide - MyOMR Job Portal

**Date:** October 29, 2025  
**Archived:** March 2026

---

## ✅ Error Reporting Enabled

All job portal files now have **error reporting enabled** for development. Errors will display directly in the browser.

---

## 🐛 Common Errors & Solutions

### **Error 500 - Internal Server Error**

**Possible Causes:**

1. **Database Connection Failed**

   - Check `core/omr-connect.php` credentials
   - Verify database server is accessible
   - Check if database name is correct

2. **Missing Files/Includes**

   - Verify all files uploaded correctly
   - Check file paths in includes
   - Ensure folder structure maintained

3. **PHP Syntax Error**

   - Check PHP version (need PHP 7.4+)
   - Verify all files have proper PHP tags
   - Check for missing semicolons, brackets

4. **Table Not Found**

   - Verify `CREATE-JOBS-DATABASE.sql` was run
   - Check if tables exist in database
   - Verify table names match exactly

5. **Function Not Defined**
   - Check if `job-functions-omr.php` is included
   - Verify function names are correct
   - Check for typos in function calls

---

## 🔧 Diagnostic Steps

### **Step 1: Check Error Log**

Error log: `/weblog/job-portal-errors.log` (or server error log)

### **Step 2: Check Error Display**

With error reporting enabled, errors may appear in the browser during development.

### **Step 3: Common Fixes**

**If Database Connection Fails:**

```php
// Check core/omr-connect.php
$servername = "localhost:3306"; // or your server
$username = "your_username";
$password = "your_password";
$database = "your_database";
```

**If Table Not Found:**

```sql
-- Run this in phpMyAdmin to verify tables exist:
SHOW TABLES LIKE 'job_%';
SHOW TABLES LIKE 'employer%';
```

---

**Note:** test-connection.php was removed in March 2026 cleanse. Use server error logs and docs/inbox/JOB-PORTAL-OVERHAUL-PLAN.md for diagnostics.
