# 🚀 Quick Start: Connect Local Windows to Live Database

## ⚡ Fast Setup (5 Minutes)

### **Step 1: Run the PowerShell Script**

Right-click `start-ssh-tunnel.ps1` and select "Run with PowerShell"

OR

Open PowerShell in project root and run:

```powershell
.\start-ssh-tunnel.ps1
```

**The script will:**

- ✅ Check if SSH is installed
- ✅ Check if port 3307 is available
- ✅ Prompt for your cPanel username
- ✅ Create SSH tunnel automatically
- ✅ Show connection status

### **Step 2: Enter Your cPanel Username**

When prompted, enter your cPanel username (not email).

### **Step 3: Enter Your cPanel Password**

You'll be prompted for your cPanel password. Type it and press Enter.

### **Step 4: Keep PowerShell Window Open**

⚠️ **DO NOT CLOSE THIS WINDOW!**

The tunnel only works while this window is open.

### **Step 5: Test Connection**

Open your browser and visit:

```
http://localhost/test-remote-db-connection.php
```

You should see:

- ✅ Connection successful!
- ✅ Database: metap8ok_myomr
- ✅ All tables showing counts

---

## 📁 Files You Need

### **Created for You:**

- ✅ `start-ssh-tunnel.ps1` - Automated SSH tunnel script
- ✅ `test-remote-db-connection.php` - Database connection tester
- ✅ `core/omr-connect-remote.php` - Remote database config
- ✅ `docs/LOCAL_TO_REMOTE_DATABASE_SETUP.md` - Complete guide

---

## 🔧 Update Your Code

### **Option 1: Use Remote Config (Recommended)**

Change your database includes from:

```php
require 'core/omr-connect.php';
```

To:

```php
require 'core/omr-connect-remote.php';
```

The remote config automatically detects if you're on local or live server!

### **Option 2: Modify Existing Config**

Edit `core/omr-connect.php` and change:

```php
$servername = "localhost:3306";
```

To:

```php
$servername = "localhost:3307"; // SSH tunnel port
```

---

## ✅ Verify It's Working

### **1. Check PowerShell Window**

You should see something like:

```
Welcome to myomr.in
Last login: ...
```

### **2. Test Connection**

Visit: `http://localhost/test-remote-db-connection.php`

Should show:

- ✅ Connection successful
- ✅ Tables with data counts
- ✅ All tests passing

### **3. Test Your PHP Application**

Visit: `http://localhost/index.php`

Your homepage should load with:

- Live news data
- Live directory listings
- No database errors

---

## 🛑 When You're Done Working

### **Close the Tunnel:**

1. Go to PowerShell window
2. Press `Ctrl + C`
3. Type `exit` and press Enter

OR

Just close the PowerShell window.

---

## 🔄 Daily Workflow

### **Every Time You Start Working:**

```powershell
# 1. Start SSH tunnel
.\start-ssh-tunnel.ps1

# 2. Start your local web server (if needed)
# For XAMPP: Start Apache from control panel
# For PHP built-in: php -S localhost:80

# 3. Open browser to your project
http://localhost/

# 4. Start coding!
```

### **When You Stop Working:**

```powershell
# 1. Close SSH tunnel (Ctrl+C in PowerShell)
# 2. Stop web server
# 3. Commit your changes to Git
```

---

## 🚨 Troubleshooting

### **Issue: "SSH is not available"**

**Solution:**

```powershell
# Run as Administrator
Add-WindowsCapability -Online -Name OpenSSH.Client~~~~0.0.1.0
```

### **Issue: "Port 3307 is already in use"**

**Solution:**

```powershell
# Find what's using the port
netstat -ano | findstr :3307

# Kill the process (replace PID with the number from above)
taskkill /PID [PID] /F

# Or use different port in script
```

### **Issue: "Connection refused" or "Connection timed out"**

**Solution:**

- Check your internet connection
- Verify your cPanel has SSH access enabled
- Contact hosting provider to enable SSH

### **Issue: "Access denied"**

**Solution:**

- Double-check cPanel username (not email)
- Verify password is correct
- Ensure SSH is enabled for your account

### **Issue: "Database connection failed" but SSH works**

**Solution:**

- Verify you're using port 3307 in config
- Check `test-remote-db-connection.php` output
- Ensure SSH tunnel is still running

---

## 💡 Pro Tips

### **Keep Terminal Open**

- Minimize (don't close) the PowerShell window
- SSH tunnel runs in background
- You can continue working normally

### **Check Connection Status**

Anytime you're unsure if tunnel is working:

```
http://localhost/test-remote-db-connection.php
```

### **Work with Live Data Safely**

- Always test queries on development first
- Use transactions for data changes
- Backup before major operations
- Consider using a staging database

### **Speed Up Connection**

Add this to `C:\Users\YOUR_USERNAME\.ssh\config`:

```
Host myomr
    HostName myomr.in
    User YOUR_CPANEL_USERNAME
    LocalForward 3307 localhost:3306
    ServerAliveInterval 60
    ServerAliveCountMax 3
```

Then connect with just:

```bash
ssh myomr
```

---

## 📊 Connection Flow

```
Your PC (Windows)
     ↓
localhost:3307 (SSH Tunnel)
     ↓
SSH Connection (Encrypted)
     ↓
myomr.in Server
     ↓
localhost:3306 (MySQL)
     ↓
metap8ok_myomr Database
```

---

## 🔐 Security Notes

- ✅ **Encrypted:** All data is encrypted via SSH
- ✅ **No Direct MySQL Access:** MySQL port not exposed
- ✅ **Secure Credentials:** Password only in SSH session
- ✅ **Local Only:** Tunnel only accessible from your PC

---

## 📞 Need Help?

1. **Read Full Guide:**
   `docs/LOCAL_TO_REMOTE_DATABASE_SETUP.md`

2. **Check Test Script:**
   `http://localhost/test-remote-db-connection.php`

3. **Check SSH Connection:**

   ```powershell
   ssh YOUR_USERNAME@myomr.in
   ```

4. **Contact Hosting Support:**
   For SSH access issues

---

## ✅ Success Checklist

- [ ] PowerShell script runs without errors
- [ ] SSH connection established (window shows remote server prompt)
- [ ] Test connection page shows success
- [ ] PHP application loads with live data
- [ ] No database connection errors
- [ ] Can query tables successfully

---

**Status:** Ready to Use  
**Setup Time:** 5 minutes  
**Complexity:** Beginner-friendly  
**Maintenance:** None (just run script when needed)

---

_Save this file for quick reference every time you start development!_
