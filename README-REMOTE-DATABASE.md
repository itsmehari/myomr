# Remote Database Connections (MyOMR)

This document explains how to connect from your local dev machine to the **remote MySQL database** (e.g. on cPanel) and what you need on both the local side and the server.

---

## How the connection works

When you run a PHP script with the database host set to your server (e.g. `DB_HOST=myomr.in`):

1. **From your machine:** PHP’s **mysqli** extension opens a **TCP connection** to the server on port **3306** (MySQL’s default port).
2. **Over the internet:** The path is: your machine → router → ISP → internet → your hosting server (e.g. cPanel where myomr.in is hosted).
3. **On the server:** MySQL accepts the connection only if your **current public IP address** is allowed in cPanel’s **Remote MySQL®** access list.

So the “remote connection” is a **direct MySQL client connection** from your laptop to the server’s MySQL port. No MySQL server is required on your laptop; PHP’s mysqli is the client. Credentials are taken from `core/omr-connect.php` or from environment variables.

---

## Prerequisites on the local dev environment

| Requirement | Details |
|-------------|---------|
| **PHP CLI** | PHP installed and on PATH so `php` runs in the terminal. |
| **mysqli extension** | Must be enabled in PHP. Check: run `php -m` and look for `mysqli` (Windows: `findstr mysqli` in the output). |
| **Outbound port 3306** | Your network (home/office/campus) must allow **outbound** TCP to port 3306. Some corporate or public Wi‑Fi firewalls block it. |
| **DB_HOST** | For remote runs, set `DB_HOST` to your MySQL host (e.g. `myomr.in`). See “Commands” below. |

---

## Prerequisites on the server (cPanel)

| Step | Where | What to do |
|------|--------|------------|
| **1. Remote MySQL** | cPanel → **Remote MySQL®** (or “Remote MySQL” under Databases) | Add the **public IP address** of each machine (or network) you want to allow. You can add `%` to allow any host (less secure). |
| **2. MySQL user** | Same as in `core/omr-connect.php` | User must have the right privileges on the database (e.g. CREATE, INSERT). Your existing site user is usually enough. |
| **3. Port 3306** | Server / hosting firewall | Port 3306 must be open for **inbound** from the IPs you added. Many hosts allow this automatically when you add an IP in Remote MySQL. |

---

## Why it works on one machine but not another

Common reasons another laptop or PC fails to connect:

### 1. Different public IP not allowed in cPanel

- Each network (different Wi‑Fi, different location) has its own **public IP**.
- **Fix:** On the **machine that fails**, open [whatismyip.com](https://www.whatismyip.com/) and note the IP. In **cPanel → Remote MySQL**, add **that** IP.

### 2. Port 3306 blocked on that network

- Some networks block outbound port 3306 (e.g. corporate firewall, school Wi‑Fi).
- **Check (PowerShell):**  
  `Test-NetConnection -ComputerName myomr.in -Port 3306`  
  Or: `telnet myomr.in 3306`  
  If it times out or “connection refused”, the path to 3306 is blocked.
- **Workaround:** Run the script from a machine that can connect, or from the server (e.g. SSH and run PHP there), or use phpMyAdmin on the server.

### 3. PHP not installed or not on PATH

- **Check:** Run `php -v`. If “command not found”, install PHP or add it to PATH.
- **Check mysqli:** Run `php -m` and look for `mysqli`.

### 4. Wrong hostname or credentials

- **Host:** Must match what cPanel shows for Remote MySQL (often your domain, e.g. `myomr.in`, or a host like `server123.hosting.com`).
- **User / password / database:** Scripts use `core/omr-connect.php` unless you set `DB_USER`, `DB_PASS`, `DB_NAME`. Ensure the other machine has the same repo or the same env vars.

### 5. Firewall or antivirus on the laptop

- Windows Firewall or antivirus can block outbound PHP or port 3306. Try allowing `php.exe` or temporarily disabling to test.

---

## Commands to run with remote DB

Replace `myomr.in` with your actual MySQL host if different. Run from the **repo root**.

**PowerShell (Windows):**
```powershell
$env:DB_HOST='myomr.in'
cd E:\OneDrive\_myomr\_Root
php elections-2026/dev-tools/run-election-2026-migration.php
```

**Cmd (Windows):**
```cmd
set DB_HOST=myomr.in
cd E:\OneDrive\_myomr\_Root
php elections-2026/dev-tools/run-election-2026-migration.php
```

**Bash (Mac/Linux):**
```bash
export DB_HOST=myomr.in
cd /path/to/repo/root
php elections-2026/dev-tools/run-election-2026-migration.php
```

Optional env vars: `DB_PORT` (default 3306), `DB_USER`, `DB_PASS`, `DB_NAME` if you need to override `core/omr-connect.php`.

---

## Quick checklist when connection fails on another laptop

1. On that laptop, open [whatismyip.com](https://www.whatismyip.com/) and note the **public IP**.
2. In **cPanel → Remote MySQL**, add that IP and save.
3. On that laptop run: `php -v` and `php -m | findstr mysqli`.
4. From that laptop run: `Test-NetConnection -ComputerName myomr.in -Port 3306` (PowerShell). If it fails, the network or server is blocking 3306.
5. Run your script with `DB_HOST=myomr.in` from the repo root.

If it still fails, the exact error from the script (“connection refused”, “connection timed out”, “access denied”) will show whether the issue is network, firewall, or credentials.
