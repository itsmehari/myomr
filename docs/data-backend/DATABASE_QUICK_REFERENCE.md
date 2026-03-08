# 🗄️ MyOMR Database - Quick Reference

## 📊 All Tables at a Glance

| #   | Table Name                 | Purpose             | Key Columns                 | Records (Est.) |
| --- | -------------------------- | ------------------- | --------------------------- | -------------- |
| 1   | `news_bulletin`            | News articles       | title, date, image          | 100-500        |
| 2   | `events`                   | Community events    | title, event_date, location | 50-200         |
| 3   | `omr_restaurants`          | Restaurant listings | name, locality, rating      | 50-100         |
| 4   | `omrschoolslist`           | Schools directory   | schoolname, address         | 100-200        |
| 5   | `omrhospitalslist`         | Hospitals directory | hospitalname, address       | 50-100         |
| 6   | `omrbankslist`             | Banks directory     | bankname, branch, ifsc      | 30-50          |
| 7   | `omratmslist`              | ATM locations       | bankname, location          | 100-200        |
| 8   | `omritcompanieslist`       | IT companies        | companyname, address        | 50-100         |
| 9   | `omrindustrieslist`        | Industries          | industryname, type          | 20-50          |
| 10  | `omrparkslist`             | Parks & recreation  | parkname, facilities        | 20-40          |
| 11  | `omrgovernmentofficeslist` | Govt offices        | officename, department      | 30-50          |
| 12  | `List of Areas`            | OMR localities      | Areas                       | 20-30          |
| 13  | `businesses`               | Featured businesses | name, category              | 20-50          |
| 14  | `gallery`                  | Photo gallery       | image_url, caption          | 100-500        |
| 15  | `admin_users`              | Admin accounts      | username, password          | 2-5            |

---

## 🔍 Common Queries Cheat Sheet

### News & Events

```sql
-- Latest news
SELECT * FROM news_bulletin ORDER BY date DESC LIMIT 10;

-- Upcoming events
SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date LIMIT 5;

-- News by tag
SELECT * FROM news_bulletin WHERE tags LIKE '%metro%';
```

### Directory Searches

```sql
-- Schools in area
SELECT * FROM omrschoolslist WHERE address LIKE '%Thoraipakkam%';

-- Top rated restaurants
SELECT * FROM omr_restaurants ORDER BY rating DESC LIMIT 10;

-- IT companies
SELECT * FROM omritcompanieslist ORDER BY companyname;

-- Banks with IFSC
SELECT bankname, branch, ifsc FROM omrbankslist WHERE ifsc IS NOT NULL;
```

### Admin Operations

```sql
-- Count records in each table
SELECT
  (SELECT COUNT(*) FROM news_bulletin) AS news,
  (SELECT COUNT(*) FROM events) AS events,
  (SELECT COUNT(*) FROM omr_restaurants) AS restaurants,
  (SELECT COUNT(*) FROM omrschoolslist) AS schools;

-- Recent additions
SELECT 'news' as type, title as name, created_at FROM news_bulletin
UNION ALL
SELECT 'event' as type, title as name, created_at FROM events
ORDER BY created_at DESC LIMIT 10;
```

---

## 📝 Table Naming Patterns

### Pattern 1: `omr[entity]list`

- `omrschoolslist`
- `omrhospitalslist`
- `omrbankslist`
- `omratmslist`
- `omritcompanieslist`
- `omrindustrieslist`
- `omrparkslist`
- `omrgovernmentofficeslist`

**Columns:** slno, [entity]name, address, contact, landmark

### Pattern 2: `omr_[entity]`

- `omr_restaurants`

**Features:** Advanced (ratings, geolocation, filters)

### Pattern 3: Singular names

- `news_bulletin`
- `events`
- `gallery`
- `businesses`

---

## 🎯 Field Types by Purpose

### Text Content

- **Short:** `VARCHAR(100)` - Names, titles
- **Medium:** `VARCHAR(255)` - Addresses, URLs
- **Long:** `VARCHAR(500)` - Long addresses, image paths
- **Very Long:** `TEXT` - Descriptions, content

### Numbers

- **IDs:** `INT` AUTO_INCREMENT
- **Counts:** `INT`
- **Money:** `INT` (cost_for_two in rupees)
- **Ratings:** `DECIMAL(2,1)` (0.0 to 5.0)

### Dates & Times

- **Dates:** `DATE` (YYYY-MM-DD)
- **Times:** `TIME` (HH:MM:SS)
- **Timestamps:** `TIMESTAMP` (automatic)

### Special Types

- **Location:** `POINT` (geolocation)
- **Boolean:** `TINYINT(1)` or `ENUM('yes','no')`
- **Status:** `ENUM('active','cancelled','past')`

---

## 🔐 Direct Database Access

### Via cPanel

```
1. Log into cPanel: https://myomr.in:2083/
2. Click "phpMyAdmin" in Databases section
3. Select database: metap8ok_myomr
4. Browse tables or run queries
```

### Via Command Line (SSH)

```bash
# Connect to server
ssh YOUR_USERNAME@myomr.in

# Access MySQL
mysql -u metap8ok_myomr_admin -p
# Enter password: myomr@123

# Use database
USE metap8ok_myomr;

# Show tables
SHOW TABLES;

# Describe table
DESCRIBE news_bulletin;
```

### Via Local Tools (with SSH Tunnel)

```
1. Start SSH tunnel: .\start-ssh-tunnel.ps1
2. Open HeidiSQL/MySQL Workbench
3. Connect to localhost:3307
4. Enter credentials
```

---

## 🛠️ Export Database Schema

### Method 1: Use PHP Script

```
1. Visit: http://localhost/export-database-schema.php
2. Copy output
3. Save to: docs/database-schema.sql
```

### Method 2: Command Line

```bash
# Export schema only (no data)
mysqldump -u metap8ok_myomr_admin -p --no-data metap8ok_myomr > schema.sql

# Export specific table
mysqldump -u metap8ok_myomr_admin -p metap8ok_myomr news_bulletin > news_bulletin.sql

# Export with data
mysqldump -u metap8ok_myomr_admin -p metap8ok_myomr > full_backup.sql
```

### Method 3: phpMyAdmin

```
1. Log into phpMyAdmin
2. Select database: metap8ok_myomr
3. Click "Export" tab
4. Choose format (SQL recommended)
5. Click "Go"
```

---

## 📈 Database Statistics

### Get Table Sizes

```sql
SELECT
  table_name AS 'Table',
  ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)',
  table_rows AS 'Rows'
FROM information_schema.TABLES
WHERE table_schema = 'metap8ok_myomr'
ORDER BY (data_length + index_length) DESC;
```

### Get Column Count

```sql
SELECT
  table_name,
  COUNT(*) as column_count
FROM information_schema.COLUMNS
WHERE table_schema = 'metap8ok_myomr'
GROUP BY table_name;
```

---

## 🚨 Emergency Commands

### Backup Before Changes

```sql
-- Quick backup of important tables
CREATE TABLE news_bulletin_backup AS SELECT * FROM news_bulletin;
CREATE TABLE events_backup AS SELECT * FROM events;
```

### Restore from Backup

```sql
-- Replace current with backup
TRUNCATE TABLE news_bulletin;
INSERT INTO news_bulletin SELECT * FROM news_bulletin_backup;
```

### Check for Corruption

```sql
CHECK TABLE news_bulletin;
CHECK TABLE events;
CHECK TABLE omr_restaurants;
```

### Repair Tables

```sql
REPAIR TABLE news_bulletin;
REPAIR TABLE events;
```

---

## 📞 Quick Help

### Connection Issues?

1. Check: `test-remote-db-connection.php`
2. Verify: SSH tunnel is running
3. Test: Port 3307 is correct
4. Read: `docs/DATABASE_CONNECTION_SUMMARY.md`

### Query Errors?

1. Check: Table and column names (case-sensitive)
2. Use: Backticks for reserved words
3. Test: Query in phpMyAdmin first
4. Review: `docs/DATABASE_STRUCTURE.md`

### Need Schema?

1. Run: `export-database-schema.php`
2. Or: Use phpMyAdmin export
3. Read: `docs/DATABASE_STRUCTURE.md`

---

**Quick Links:**

- Full Documentation: `docs/DATABASE_STRUCTURE.md`
- Connection Setup: `docs/LOCAL_TO_REMOTE_DATABASE_SETUP.md`
- Security Guide: `core/security-helpers.php`

---

**Last Updated:** December 26, 2024  
**Total Tables:** 15+  
**Database:** metap8ok_myomr
