# 🔍 Error Debug Guide - MyOMR Job Portal

**Date:** October 29, 2025

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

### **Step 1: Run Test File**

Visit: `https://myomr.in/omr-local-job-listings/test-connection.php`

This will:

- ✅ Check if database connection file exists
- ✅ Test database connection
- ✅ Verify all tables exist
- ✅ Test job_categories query
- ✅ Test getJobCategories() function

**What to look for:**

- Any ❌ (red X) marks indicate issues
- Read the error messages carefully
- Check which test fails first

### **Step 2: Check Error Display**

With error reporting enabled, errors should appear:

- At the top of the page (red error boxes)
- In browser console (JavaScript errors)
- In error log: `/weblog/job-portal-errors.log`

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

**If Function Error:**

- Check if `includes/job-functions-omr.php` exists
- Verify path: `__DIR__ . '/includes/job-functions-omr.php'`
- Check file permissions (644 for files, 755 for folders)

---

## 📋 Error Report Format

When errors appear, they will show:

```
PHP Error:
Type: [Error Type]
Message: [Error Message]
File: [File Path]
Line: [Line Number]
```

**Save this information** to help fix the issue.

---

## 🔍 Check These Files First

1. ✅ `core/omr-connect.php` - Database connection
2. ✅ `includes/job-functions-omr.php` - Helper functions
3. ✅ `includes/error-reporting.php` - Error display
4. ✅ Database tables exist
5. ✅ File paths are correct

---

## 📞 Need Help?

1. Run `test-connection.php`
2. Copy error message (if any)
3. Check error log file
4. Verify file paths
5. Check database connection

---

**Last Updated:** October 29, 2025
