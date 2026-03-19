# Remote database connection – how it works and prerequisites

## How the connection is achieved

When you run the migration script with `DB_HOST=myomr.in`, this is what happens:

1. **From your laptop:** PHP’s `mysqli` extension opens a **TCP connection** to `myomr.in` on port **3306** (MySQL’s default port).
2. **Over the internet:** The request goes: your machine → your router → ISP → internet → your cPanel server (where myomr.in is hosted).
3. **On the server:** MySQL (or the cPanel “Remote MySQL” layer) accepts the connection only if your **current public IP address** is allowed in cPanel’s “Remote MySQL®” access list.

So the “remote connection” is a direct **MySQL client connection from your dev machine to the server’s MySQL port**, using the same credentials as in `core/omr-connect.php` (or env overrides).

---

## Prerequisites on the local dev environment

| Requirement | Details |
|-------------|---------|
| **PHP CLI** | PHP installed and on PATH so `php` runs from the terminal. |
| **mysqli extension** | Enabled in PHP (usually enabled by default). Check with: `php -m \| findstr mysqli` (Windows) or `php -m \| grep mysqli` (Mac/Linux). |
| **Outbound port 3306** | Your network (home/office/campus) must allow **outbound** TCP to port 3306. Some corporate or public Wi‑Fi firewalls block this. |
| **DB_HOST set** | For remote run: set `DB_HOST` to your MySQL host (e.g. `myomr.in`). See commands below. |

No MySQL server needs to be installed on your laptop; PHP’s mysqli acts as the client.

---

## Prerequisites on the server (cPanel)

| Step | Where | What to do |
|------|--------|------------|
| **1. Remote MySQL** | cPanel → **Remote MySQL®** (or “Remote MySQL” under Databases) | Add the **public IP address** of each machine (or network) from which you want to connect. You can add `%` to allow any host (less secure). |
| **2. MySQL user** | Same as used in `omr-connect.php` | User must have privileges on the database (CREATE, INSERT, etc.). Your existing site user is usually enough. |
| **3. Port 3306** | Server / hosting firewall | Port 3306 must be open for **inbound** from the IPs you added. Many hosts already allow this when you add an IP in Remote MySQL. |

---

## Why it might work on one laptop but not another

Common reasons the **other laptop** fails:

### 1. Different public IP not allowed in cPanel

- Each network (home, office, other laptop on different Wi‑Fi) has its own **public IP**.
- **Fix:** In cPanel → Remote MySQL, add the **current public IP** of the laptop that fails. Find that IP by opening [whatismyip.com](https://www.whatismyip.com/) (or similar) **on that laptop**, then add it in Remote MySQL.

### 2. Port 3306 blocked on that network

- Some networks block outbound port 3306 (e.g. corporate firewall, school Wi‑Fi).
- **Check:** From that laptop run:  
  `Test-NetConnection -ComputerName myomr.in -Port 3306` (PowerShell) or  
  `telnet myomr.in 3306`  
  If it times out or “connection refused”, the path to 3306 is blocked.
- **Workaround:** Run the migration from the laptop that works, or from the server (SSH + `php run-election-2026-migration.php` without `DB_HOST`), or use phpMyAdmin on the server to run the SQL files.

### 3. PHP not installed or not on PATH on the other laptop

- **Check:** On that laptop run `php -v` in a terminal. If “command not found”, install PHP or add it to PATH.
- **Check mysqli:** `php -m` and look for `mysqli`.

### 4. Wrong hostname or credentials

- **Host:** Must match what cPanel shows for “Remote MySQL” (often your domain, e.g. `myomr.in`, or a host like `server123.hosting.com`). Try the same `DB_HOST` that worked on the first laptop.
- **User / password / database:** Script uses same as `core/omr-connect.php` unless you set `DB_USER`, `DB_PASS`, `DB_NAME`. Ensure the other laptop has the same repo (same credentials in omr-connect) or set env vars.

### 5. Firewall or antivirus on the other laptop

- Windows Firewall or antivirus can block outbound PHP or outbound port 3306. Temporarily allow `php.exe` or test with firewall disabled to see if that’s the cause.

---

## Commands to run the migration (remote)

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

---

## Quick checklist for the other laptop

1. On the **other laptop**, open [whatismyip.com](https://www.whatismyip.com/) and note the **public IP**.
2. In **cPanel → Remote MySQL**, add that IP (and save).
3. On that laptop run: `php -v` and `php -m | findstr mysqli`.
4. From that laptop run: `Test-NetConnection -ComputerName myomr.in -Port 3306` (PowerShell); if it fails, the network or server is blocking 3306.
5. Run the migration with `DB_HOST=myomr.in` from the repo root on that laptop.

If it still fails, the exact error message from `php run-election-2026-migration.php` (e.g. “connection refused”, “connection timed out”, “access denied”) will show whether the problem is network, firewall, or credentials.
