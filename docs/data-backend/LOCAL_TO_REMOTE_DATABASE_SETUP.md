# Local Windows to Remote Database Connection Guide

## 📋 Overview

This guide explains how to connect your **local Windows development environment** to the **live MySQL database** on your cPanel hosting server.

---

## 🎯 Current Configuration

**Live Database Details:**

- **Server:** myomr.in (cPanel hosting)
- **Database:** `metap8ok_myomr`
- **Username:** `metap8ok_myomr_admin`
- **Password:** `myomr@123`
- **Port:** 3306 (default MySQL)

**Current File:** `core/omr-connect.php`

- Currently configured for `localhost:3306`
- This won't work from Windows without setup

---

## ✅ Option 1: SSH Tunnel (RECOMMENDED - Most Secure)

### Why Use SSH Tunnel?

- ✅ **Most Secure** - Encrypted connection
- ✅ **No cPanel Changes** - Works immediately
- ✅ **Port Forwarding** - Routes traffic securely
- ✅ **Industry Standard** - Best practice for remote DB access

### Prerequisites

1. **SSH Access to your server**

   - cPanel username and password
   - OR SSH key (more secure)

2. **SSH Client on Windows**
   - Windows 10/11 has OpenSSH built-in
   - Alternative: PuTTY

### Step-by-Step Setup

#### **Step 1: Get Your cPanel SSH Details**

From your hosting provider, you need:

- **SSH Username:** Usually your cPanel username
- **SSH Host:** myomr.in (or server IP)
- **SSH Port:** Usually 22 (or check with hosting provider)

#### **Step 2: Enable SSH in cPanel (if not enabled)**

1. Log into cPanel
2. Search for "SSH Access" or "Terminal"
3. Generate SSH keys or enable password access
4. Note your SSH username

#### **Step 3: Open Windows PowerShell or Command Prompt**

Press `Win + X` → Select "Windows PowerShell" or "Command Prompt"

#### **Step 4: Create SSH Tunnel**

Run this command (replace USERNAME with your cPanel username):

```bash
ssh -L 3307:localhost:3306 USERNAME@myomr.in
```

**What this does:**

- `-L 3307:localhost:3306` = Forward local port 3307 to remote port 3306
- `USERNAME@myomr.in` = Connect to your server

**You'll be prompted for:**

- Password (enter your cPanel password)
- Or SSH key passphrase if using keys

**Keep this window open!** The tunnel only works while this connection is active.

#### **Step 5: Update Database Connection**

**Option A: Use the new remote config file**

Replace includes in your PHP files:

```php
// Change from:
include 'core/omr-connect.php';

// To:
include 'core/omr-connect-remote.php';
```

**Option B: Modify existing file**

Edit `core/omr-connect.php`:

```php
$servername = "localhost:3307"; // Changed from 3306 to 3307
$username = "metap8ok_myomr_admin";
$password = "myomr@123";
$database = "metap8ok_myomr";
```

#### **Step 6: Test Connection**

Create a test file `test-connection.php`:

```php
<?php
require 'core/omr-connect-remote.php';
echo "Connected successfully to database: " . $database;
?>
```

Visit: `http://localhost/test-connection.php`

---

## ✅ Option 2: Direct Remote MySQL Access

### ⚠️ Warning

This is **less secure** than SSH tunnel but easier to set up.

### Step-by-Step Setup

#### **Step 1: Add Your IP to Remote MySQL in cPanel**

1. **Get Your Public IP Address**

   - Visit: https://whatismyipaddress.com/
   - Copy your IPv4 address (e.g., 123.456.789.012)

2. **Log into cPanel**

   - URL: https://myomr.in:2083/
   - Username: Your cPanel username
   - Password: Your cPanel password

3. **Navigate to Remote MySQL®**

   - Search for "Remote MySQL" in cPanel search
   - Or find under "Databases" section

4. **Add Access Host**
   - In "Add Access Host" section
   - Enter your IP address: `123.456.789.012`
   - Click "Add Host"
   - You should see confirmation

#### **Step 2: Update Database Connection**

Edit `core/omr-connect.php`:

```php
$servername = "myomr.in:3306"; // Use domain or server IP
$username = "metap8ok_myomr_admin";
$password = "myomr@123";
$database = "metap8ok_myomr";
```

#### **Step 3: Test Connection**

Same as Option 1, Step 6

### Troubleshooting Direct Connection

**Issue: Connection times out**

- Check if your IP changed (dynamic IP)
- Re-add new IP in cPanel Remote MySQL
- Check if port 3306 is open in firewall

**Issue: Access denied**

- Verify username/password
- Check if user has remote access permissions
- Contact hosting provider

---

## ✅ Option 3: Use HeidiSQL or MySQL Workbench

### Install Database Client

**HeidiSQL (Recommended for Windows)**

- Download: https://www.heidisql.com/download.php
- Free and easy to use
- Supports SSH tunnel

**MySQL Workbench**

- Download: https://dev.mysql.com/downloads/workbench/
- Official MySQL tool
- More features but heavier

### Setup SSH Tunnel in HeidiSQL

1. Open HeidiSQL
2. Click "New" to create new session
3. **Session settings:**

   - Network type: MySQL (TCP/IP)
   - Hostname: localhost
   - User: metap8ok_myomr_admin
   - Password: myomr@123
   - Port: 3307
   - Database: metap8ok_myomr

4. **SSH tunnel settings:**

   - Click "SSH tunnel" tab
   - Check "Use SSH tunnel"
   - SSH host: myomr.in
   - SSH port: 22
   - Username: [Your cPanel username]
   - Password: [Your cPanel password]
   - Local port: 3307

5. Click "Save" and "Open"

---

## 🔧 Testing Your Connection

### Test Script

Create `test-db.php` in your project root:

```php
<?php
// Test Remote Database Connection
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>Database Connection Test</h2>";

// Method 1: Using omr-connect-remote.php
echo "<h3>Test 1: Using Remote Config</h3>";
try {
    require 'core/omr-connect-remote.php';
    echo "✅ Connected successfully!<br>";
    echo "Database: " . $database . "<br>";

    // Test query
    $result = $conn->query("SELECT COUNT(*) as count FROM news_bulletin");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "✅ Query test passed! Found " . $row['count'] . " news items.<br>";
    }

} catch (Exception $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "<br>";
}

// Close connection
if (isset($conn)) {
    $conn->close();
}

echo "<hr>";
echo "<h3>Connection Details</h3>";
echo "Server: " . $servername . "<br>";
echo "Database: " . $database . "<br>";
echo "Username: " . $username . "<br>";
?>
```

Visit: `http://localhost/test-db.php`

---

## 📊 Comparison Table

| Method                 | Security   | Setup Time | Requires cPanel Changes | Best For       |
| ---------------------- | ---------- | ---------- | ----------------------- | -------------- |
| **SSH Tunnel (CLI)**   | ⭐⭐⭐⭐⭐ | 5 min      | No                      | Developers     |
| **Direct Remote**      | ⭐⭐⭐     | 10 min     | Yes                     | Quick testing  |
| **HeidiSQL/Workbench** | ⭐⭐⭐⭐⭐ | 15 min     | No                      | Visual DB work |

---

## 🔥 Quick Start (PowerShell Command)

For fastest setup, run this in PowerShell:

```powershell
# Create SSH tunnel (replace USERNAME)
ssh -L 3307:localhost:3306 USERNAME@myomr.in

# Keep this window open and test your PHP files
```

Then update your database connection to use port 3307.

---

## 🚨 Common Errors & Solutions

### Error: "Connection refused"

**Solution:**

- Ensure SSH tunnel is running
- Check port 3307 is not in use: `netstat -ano | findstr :3307`
- Try different local port (e.g., 3308)

### Error: "Access denied for user"

**Solution:**

- Verify username/password
- Check SSH credentials are correct
- Ensure database user exists in cPanel

### Error: "Unknown database"

**Solution:**

- Verify database name: `metap8ok_myomr`
- Check database exists in cPanel → MySQL Databases

### Error: "Can't connect to MySQL server"

**Solution:**

- For SSH: Ensure tunnel is active
- For Direct: Check IP in Remote MySQL
- Verify MySQL service is running on server

### Error: "Connection timed out"

**Solution:**

- Check firewall settings
- Verify hosting provider allows remote MySQL
- Try different network (VPN, mobile hotspot)

---

## 💡 Best Practices

### Security

1. ✅ **Always use SSH tunnel** for production database access
2. ✅ **Never commit passwords** to version control
3. ✅ **Use environment variables** for credentials
4. ✅ **Limit IP access** in Remote MySQL settings
5. ✅ **Use strong passwords**

### Development

1. ✅ **Keep SSH tunnel running** while developing
2. ✅ **Use separate config** for local vs remote
3. ✅ **Test queries** before running on live data
4. ✅ **Backup database** before major changes
5. ✅ **Use transactions** for data modifications

---

## 🔄 Switching Between Local and Remote

### Create Environment-Aware Config

Edit `core/omr-connect-remote.php`:

```php
<?php
// Auto-detect environment
$is_local = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);

if ($is_local) {
    // Local development with SSH tunnel
    $servername = "localhost:3307";
    $username = "metap8ok_myomr_admin";
    $password = "myomr@123";
    $database = "metap8ok_myomr";
} else {
    // Production server
    $servername = "localhost:3306";
    $username = "metap8ok_myomr_admin";
    $password = "myomr@123";
    $database = "metap8ok_myomr";
}

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

---

## 📝 Your Current Setup Checklist

- [ ] Choose connection method (SSH tunnel recommended)
- [ ] Set up SSH access to cPanel (if using tunnel)
- [ ] Create SSH tunnel connection
- [ ] Update database connection file
- [ ] Test connection with test-db.php
- [ ] Verify queries work correctly
- [ ] Document your setup for future reference

---

## 🆘 Need Help?

### Check These Resources:

1. **cPanel SSH Documentation:** Your hosting provider's knowledge base
2. **Windows OpenSSH:** https://docs.microsoft.com/en-us/windows-server/administration/openssh/openssh_install_firstuse
3. **HeidiSQL Forum:** https://www.heidisql.com/forum.php
4. **MySQL Remote Access:** https://dev.mysql.com/doc/

### Contact Support:

- **Hosting Provider:** For SSH access issues
- **cPanel Support:** For Remote MySQL configuration
- **Database Issues:** Check error logs in cPanel

---

## ✅ Success Criteria

You know it's working when:

1. ✅ No connection errors in PHP
2. ✅ Can query live database tables
3. ✅ Data from live database displays correctly
4. ✅ Can run SELECT queries without issues
5. ✅ Connection is stable and doesn't timeout

---

**Status:** Ready to implement  
**Recommended Method:** SSH Tunnel (Option 1)  
**Estimated Setup Time:** 5-10 minutes  
**Last Updated:** December 26, 2024

---

_Keep this document handy! You'll reference it every time you start development._
