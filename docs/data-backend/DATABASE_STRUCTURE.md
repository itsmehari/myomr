# MyOMR.in Database Structure Documentation

## 📋 Overview

**Database Name:** `metap8ok_myomr`  
**Database Engine:** MySQL 5.7+  
**Character Set:** UTF-8 (utf8mb4)  
**Collation:** utf8mb4_unicode_ci

---

## 🗄️ Database Tables

### **1. news_bulletin**

Stores news articles and community updates.

**Columns:**

| Column      | Type         | Null | Key | Default | Description               |
| ----------- | ------------ | ---- | --- | ------- | ------------------------- |
| id          | INT          | NO   | PRI | AUTO    | Primary key               |
| title       | VARCHAR(255) | NO   |     |         | News article title        |
| summary     | TEXT         | NO   |     |         | Article summary/excerpt   |
| date        | DATE         | NO   |     |         | Publication date          |
| tags        | VARCHAR(255) | YES  |     | NULL    | Comma-separated tags      |
| image       | VARCHAR(500) | NO   |     |         | Image URL path            |
| article_url | VARCHAR(500) | NO   |     |         | Full article link         |
| created_at  | TIMESTAMP    | NO   |     | CURRENT | Record creation timestamp |
| updated_at  | TIMESTAMP    | YES  |     | NULL    | Last update timestamp     |

**Indexes:**

- PRIMARY KEY (`id`)
- INDEX `idx_date` (`date`)
- INDEX `idx_tags` (`tags`)

**Sample Data:**

```sql
INSERT INTO news_bulletin (title, summary, date, tags, image, article_url)
VALUES (
  'New Metro Station Opens in OMR',
  'The Chennai Metro Rail has inaugurated a new station...',
  '2024-12-26',
  'metro,transportation,infrastructure',
  '/news-bulletin-images/metro-omr.jpg',
  '/local-news/metro-station-opens.php'
);
```

---

### **2. events**

Community events and happenings.

**Columns:**

| Column      | Type         | Null | Key | Default | Description           |
| ----------- | ------------ | ---- | --- | ------- | --------------------- |
| id          | INT          | NO   | PRI | AUTO    | Primary key           |
| title       | VARCHAR(255) | NO   |     |         | Event title           |
| description | TEXT         | NO   |     |         | Event description     |
| event_date  | DATE         | NO   |     |         | Event date            |
| event_time  | TIME         | YES  |     | NULL    | Event time            |
| location    | VARCHAR(255) | NO   |     |         | Event venue           |
| organizer   | VARCHAR(255) | YES  |     | NULL    | Organizer name        |
| contact     | VARCHAR(100) | YES  |     | NULL    | Contact information   |
| image_url   | VARCHAR(500) | YES  |     | NULL    | Event image           |
| status      | ENUM         | NO   |     | active  | active/cancelled/past |
| created_at  | TIMESTAMP    | NO   |     | CURRENT | Record creation       |

**Indexes:**

- PRIMARY KEY (`id`)
- INDEX `idx_event_date` (`event_date`)
- INDEX `idx_status` (`status`)

---

### **3. omr_restaurants**

Restaurant listings with ratings and geolocation.

**Columns:**

| Column        | Type         | Null | Key | Default | Description             |
| ------------- | ------------ | ---- | --- | ------- | ----------------------- |
| id            | INT          | NO   | PRI | AUTO    | Primary key             |
| name          | VARCHAR(255) | NO   |     |         | Restaurant name         |
| address       | VARCHAR(500) | NO   |     |         | Full address            |
| locality      | VARCHAR(100) | NO   |     |         | Area/locality           |
| cuisine       | VARCHAR(255) | NO   |     |         | Type of cuisine         |
| cost_for_two  | INT          | NO   |     |         | Average cost for 2 (₹)  |
| rating        | DECIMAL(2,1) | NO   |     | 0.0     | Rating (0.0 - 5.0)      |
| availability  | VARCHAR(255) | YES  |     | NULL    | Opening hours           |
| accessibility | VARCHAR(255) | YES  |     | NULL    | Accessibility features  |
| reviews       | TEXT         | YES  |     | NULL    | Customer reviews        |
| imagelocation | VARCHAR(500) | NO   |     |         | Image path              |
| geolocation   | POINT        | YES  |     | NULL    | GPS coordinates (POINT) |
| created_at    | TIMESTAMP    | NO   |     | CURRENT | Record creation         |
| updated_at    | TIMESTAMP    | YES  |     | NULL    | Last update             |

**Indexes:**

- PRIMARY KEY (`id`)
- INDEX `idx_locality` (`locality`)
- INDEX `idx_cuisine` (`cuisine`)
- INDEX `idx_rating` (`rating`)
- SPATIAL INDEX `idx_geolocation` (`geolocation`)

**Special Features:**

- Uses MySQL POINT type for geolocation
- Spatial queries supported for proximity search

---

### **4. omrschoolslist**

School directory listings.

**Columns:**

| Column     | Type         | Null | Key | Default | Description          |
| ---------- | ------------ | ---- | --- | ------- | -------------------- |
| slno       | INT          | NO   | PRI | AUTO    | Serial number        |
| schoolname | VARCHAR(255) | NO   |     |         | School name          |
| address    | VARCHAR(500) | NO   |     |         | School address       |
| contact    | VARCHAR(100) | YES  |     | NULL    | Contact number       |
| landmark   | VARCHAR(255) | YES  |     | NULL    | Nearby landmark      |
| board      | VARCHAR(100) | YES  |     | NULL    | CBSE/ICSE/State etc. |
| grades     | VARCHAR(100) | YES  |     | NULL    | Grade levels         |
| website    | VARCHAR(255) | YES  |     | NULL    | School website       |
| created_at | TIMESTAMP    | NO   |     | CURRENT | Record creation      |

**Indexes:**

- PRIMARY KEY (`slno`)
- INDEX `idx_schoolname` (`schoolname`)

---

### **5. omrhospitalslist**

Hospital and healthcare facility directory.

**Columns:**

| Column       | Type         | Null | Key | Default | Description       |
| ------------ | ------------ | ---- | --- | ------- | ----------------- |
| slno         | INT          | NO   | PRI | AUTO    | Serial number     |
| hospitalname | VARCHAR(255) | NO   |     |         | Hospital name     |
| address      | VARCHAR(500) | NO   |     |         | Hospital address  |
| contact      | VARCHAR(100) | YES  |     | NULL    | Contact number    |
| landmark     | VARCHAR(255) | YES  |     | NULL    | Nearby landmark   |
| speciality   | VARCHAR(255) | YES  |     | NULL    | Medical specialty |
| emergency    | ENUM         | YES  |     | NULL    | yes/no            |
| created_at   | TIMESTAMP    | NO   |     | CURRENT | Record creation   |

**Indexes:**

- PRIMARY KEY (`slno`)
- INDEX `idx_hospitalname` (`hospitalname`)
- INDEX `idx_emergency` (`emergency`)

---

### **6. omrbankslist**

Bank branch directory.

**Columns:**

| Column     | Type         | Null | Key | Default | Description     |
| ---------- | ------------ | ---- | --- | ------- | --------------- |
| slno       | INT          | NO   | PRI | AUTO    | Serial number   |
| bankname   | VARCHAR(255) | NO   |     |         | Bank name       |
| branch     | VARCHAR(255) | YES  |     | NULL    | Branch name     |
| address    | VARCHAR(500) | NO   |     |         | Branch address  |
| contact    | VARCHAR(100) | YES  |     | NULL    | Contact number  |
| ifsc       | VARCHAR(20)  | YES  |     | NULL    | IFSC code       |
| landmark   | VARCHAR(255) | YES  |     | NULL    | Nearby landmark |
| created_at | TIMESTAMP    | NO   |     | CURRENT | Record creation |

**Indexes:**

- PRIMARY KEY (`slno`)
- INDEX `idx_bankname` (`bankname`)
- INDEX `idx_ifsc` (`ifsc`)

---

### **7. omratmslist**

ATM location directory.

**Columns:**

| Column       | Type         | Null | Key | Default | Description        |
| ------------ | ------------ | ---- | --- | ------- | ------------------ |
| slno         | INT          | NO   | PRI | AUTO    | Serial number      |
| bankname     | VARCHAR(255) | NO   |     |         | Bank name          |
| location     | VARCHAR(500) | NO   |     |         | ATM location       |
| landmark     | VARCHAR(255) | YES  |     | NULL    | Nearby landmark    |
| availability | VARCHAR(50)  | YES  |     | NULL    | 24/7 or timings    |
| services     | VARCHAR(255) | YES  |     | NULL    | Cash/card services |
| created_at   | TIMESTAMP    | NO   |     | CURRENT | Record creation    |

**Indexes:**

- PRIMARY KEY (`slno`)
- INDEX `idx_bankname` (`bankname`)
- INDEX `idx_location` (`location`)

---

### **8. omritcompanieslist**

IT companies and tech firms directory.

**Columns:**

| Column      | Type         | Null | Key | Default | Description     |
| ----------- | ------------ | ---- | --- | ------- | --------------- |
| slno        | INT          | NO   | PRI | AUTO    | Serial number   |
| companyname | VARCHAR(255) | NO   |     |         | Company name    |
| address     | VARCHAR(500) | NO   |     |         | Office address  |
| contact     | VARCHAR(100) | YES  |     | NULL    | Contact number  |
| website     | VARCHAR(255) | YES  |     | NULL    | Company website |
| landmark    | VARCHAR(255) | YES  |     | NULL    | Nearby landmark |
| employees   | VARCHAR(50)  | YES  |     | NULL    | Company size    |
| created_at  | TIMESTAMP    | NO   |     | CURRENT | Record creation |

**Indexes:**

- PRIMARY KEY (`slno`)
- INDEX `idx_companyname` (`companyname`)

---

### **9. omrindustrieslist**

Industrial units and manufacturing facilities.

**Columns:**

| Column       | Type         | Null | Key | Default | Description      |
| ------------ | ------------ | ---- | --- | ------- | ---------------- |
| slno         | INT          | NO   | PRI | AUTO    | Serial number    |
| industryname | VARCHAR(255) | NO   |     |         | Industry name    |
| address      | VARCHAR(500) | NO   |     |         | Facility address |
| contact      | VARCHAR(100) | YES  |     | NULL    | Contact number   |
| type         | VARCHAR(100) | YES  |     | NULL    | Industry type    |
| landmark     | VARCHAR(255) | YES  |     | NULL    | Nearby landmark  |
| created_at   | TIMESTAMP    | NO   |     | CURRENT | Record creation  |

**Indexes:**

- PRIMARY KEY (`slno`)
- INDEX `idx_industryname` (`industryname`)
- INDEX `idx_type` (`type`)

---

### **10. omrparkslist**

Parks and recreational areas.

**Columns:**

| Column     | Type         | Null | Key | Default | Description          |
| ---------- | ------------ | ---- | --- | ------- | -------------------- |
| slno       | INT          | NO   | PRI | AUTO    | Serial number        |
| parkname   | VARCHAR(255) | NO   |     |         | Park name            |
| location   | VARCHAR(500) | NO   |     |         | Park location        |
| facilities | TEXT         | YES  |     | NULL    | Available facilities |
| timings    | VARCHAR(100) | YES  |     | NULL    | Opening hours        |
| landmark   | VARCHAR(255) | YES  |     | NULL    | Nearby landmark      |
| created_at | TIMESTAMP    | NO   |     | CURRENT | Record creation      |

**Indexes:**

- PRIMARY KEY (`slno`)
- INDEX `idx_parkname` (`parkname`)

---

### **11. omrgovernmentofficeslist**

Government offices and public services.

**Columns:**

| Column     | Type         | Null | Key | Default | Description       |
| ---------- | ------------ | ---- | --- | ------- | ----------------- |
| slno       | INT          | NO   | PRI | AUTO    | Serial number     |
| officename | VARCHAR(255) | NO   |     |         | Office name       |
| department | VARCHAR(255) | YES  |     | NULL    | Government dept   |
| address    | VARCHAR(500) | NO   |     |         | Office address    |
| contact    | VARCHAR(100) | YES  |     | NULL    | Contact number    |
| services   | TEXT         | YES  |     | NULL    | Services provided |
| timings    | VARCHAR(100) | YES  |     | NULL    | Office hours      |
| landmark   | VARCHAR(255) | YES  |     | NULL    | Nearby landmark   |
| created_at | TIMESTAMP    | NO   |     | CURRENT | Record creation   |

**Indexes:**

- PRIMARY KEY (`slno`)
- INDEX `idx_officename` (`officename`)
- INDEX `idx_department` (`department`)

---

### **12. List of Areas**

OMR localities and areas (referenced in index.php).

**Columns:**

| Column | Type         | Null | Key | Default | Description |
| ------ | ------------ | ---- | --- | ------- | ----------- |
| id     | INT          | NO   | PRI | AUTO    | Primary key |
| Areas  | VARCHAR(255) | NO   |     |         | Area name   |

**Sample Data:**

```sql
INSERT INTO `List of Areas` (Areas) VALUES
('Perungudi'), ('Kandanchavadi'), ('Thoraipakkam'),
('Karapakkam'), ('Sholinganallur'), ('Navalur'),
('Kelambakkam'), ('Mettukuppam');
```

---

### **13. businesses** (Optional - Featured)

Featured businesses for homepage.

**Columns:**

| Column      | Type         | Null | Key | Default | Description     |
| ----------- | ------------ | ---- | --- | ------- | --------------- |
| id          | INT          | NO   | PRI | AUTO    | Primary key     |
| name        | VARCHAR(255) | NO   |     |         | Business name   |
| category    | VARCHAR(100) | NO   |     |         | Business type   |
| description | TEXT         | YES  |     | NULL    | Description     |
| contact_url | VARCHAR(255) | YES  |     | NULL    | Contact link    |
| featured    | TINYINT(1)   | NO   |     | 0       | Featured flag   |
| created_at  | TIMESTAMP    | NO   |     | CURRENT | Record creation |

---

### **14. gallery** (Optional - Photo Gallery)

Photo gallery for community images.

**Columns:**

| Column     | Type         | Null | Key | Default | Description      |
| ---------- | ------------ | ---- | --- | ------- | ---------------- |
| id         | INT          | NO   | PRI | AUTO    | Primary key      |
| image_url  | VARCHAR(500) | NO   |     |         | Image path       |
| caption    | VARCHAR(255) | YES  |     | NULL    | Image caption    |
| category   | VARCHAR(100) | YES  |     | NULL    | Gallery category |
| created_at | TIMESTAMP    | NO   |     | CURRENT | Record creation  |

---

### **15. admin_users**

Admin panel user accounts.

**Columns:**

| Column     | Type         | Null | Key | Default | Description      |
| ---------- | ------------ | ---- | --- | ------- | ---------------- |
| id         | INT          | NO   | PRI | AUTO    | Primary key      |
| username   | VARCHAR(100) | NO   | UNI |         | Login username   |
| password   | VARCHAR(255) | NO   |     |         | Hashed password  |
| email      | VARCHAR(255) | YES  |     | NULL    | Admin email      |
| full_name  | VARCHAR(255) | YES  |     | NULL    | Full name        |
| role       | VARCHAR(50)  | NO   |     | admin   | User role        |
| last_login | TIMESTAMP    | YES  |     | NULL    | Last login time  |
| created_at | TIMESTAMP    | NO   |     | CURRENT | Account creation |

**Indexes:**

- PRIMARY KEY (`id`)
- UNIQUE KEY `username` (`username`)

**Note:** Currently uses plain text passwords (should migrate to password_hash)

---

## 📊 Database Relationships

### Entity Relationship Diagram (Conceptual)

```
┌─────────────────┐
│  news_bulletin  │
└─────────────────┘

┌─────────────────┐
│     events      │
└─────────────────┘

┌─────────────────┐     ┌──────────────────┐
│ omr_restaurants │────►│   geolocation    │
└─────────────────┘     └──────────────────┘

┌─────────────────┐
│omrschoolslist   │
└─────────────────┘

┌─────────────────┐
│omrhospitalslist │
└─────────────────┘

┌─────────────────┐
│ omrbankslist    │
└─────────────────┘

┌─────────────────┐
│  omratmslist    │
└─────────────────┘

┌─────────────────┐
│omritcompanieslist│
└─────────────────┘

┌─────────────────┐
│omrindustrieslist│
└─────────────────┘

┌─────────────────┐
│  omrparkslist   │
└─────────────────┘

┌─────────────────┐
│omrgovernmentoffices│
└─────────────────┘

┌─────────────────┐
│ List of Areas   │
└─────────────────┘

┌─────────────────┐
│  admin_users    │
└─────────────────┘
```

**Note:** Currently,most tables are independent (no foreign key relationships). This is typical for directory-style applications.

---

## 🔧 Common Queries

### Get Latest News

```sql
SELECT * FROM news_bulletin
ORDER BY date DESC
LIMIT 10;
```

### Get Upcoming Events

```sql
SELECT * FROM events
WHERE event_date >= CURDATE()
  AND status = 'active'
ORDER BY event_date ASC
LIMIT 6;
```

### Find Restaurants by Locality

```sql
SELECT * FROM omr_restaurants
WHERE locality = 'Thoraipakkam'
ORDER BY rating DESC;
```

### Search Schools

```sql
SELECT * FROM omrschoolslist
WHERE schoolname LIKE '%International%'
   OR address LIKE '%Sholinganallur%';
```

### Get Nearby Restaurants (Geospatial)

```sql
SELECT id, name, locality,
       ST_Distance_Sphere(
         geolocation,
         ST_GeomFromText('POINT(80.2296 12.9066)')
       ) AS distance_meters
FROM omr_restaurants
ORDER BY distance_meters
LIMIT 10;
```

---

## 🔐 Security Considerations

### Current State:

- ❌ Some tables lack proper indexes
- ❌ Admin passwords stored in plain text
- ⚠️ No foreign key constraints
- ⚠️ Limited input validation at database level

### Recommendations:

1. ✅ **Migrate to password_hash()** for admin_users
2. ✅ **Add CHECK constraints** for ratings, dates
3. ✅ **Implement prepared statements** (already in use)
4. ✅ **Add database-level validation** for critical fields
5. ✅ **Regular backups** (daily recommended)

---

## 📈 Performance Optimization

### Existing Optimizations:

- ✅ Primary keys on all tables
- ✅ Indexes on frequently queried columns
- ✅ Spatial index for geolocation queries
- ✅ TIMESTAMP for automatic date tracking

### Recommended Improvements:

- [ ] Add composite indexes for common query patterns
- [ ] Implement full-text search for content columns
- [ ] Consider table partitioning for news_bulletin (by year)
- [ ] Add database query caching
- [ ] Optimize images stored in database (move to CDN)

---

## 🛠️ Database Maintenance

### Regular Tasks:

1. **Daily:** Automated backups
2. **Weekly:** Check slow query log
3. **Monthly:** Optimize tables (`OPTIMIZE TABLE`)
4. **Quarterly:** Review and clean old data
5. **Annually:** Database structure review

### Backup Strategy:

```bash
# Daily backup command
mysqldump -u metap8ok_myomr_admin -p metap8ok_myomr > backup_$(date +%Y%m%d).sql

# Restore command
mysql -u metap8ok_myomr_admin -p metap8ok_myomr < backup_20241226.sql
```

---

## 📞 Database Access

### Connection Details:

- **Host:** localhost (or via SSH tunnel from local)
- **Port:** 3306 (default)
- **Database:** metap8ok_myomr
- **Username:** metap8ok_myomr_admin
- **Connection:** See `core/omr-connect.php`

### Tools:

- **phpMyAdmin:** Via cPanel
- **HeidiSQL:** For Windows local development
- **MySQL Workbench:** Advanced database management

---

## 📝 Change Log

### Version 2.0.0 (December 2024)

- Documented complete database structure
- Identified all tables and relationships
- Added security recommendations
- Created performance optimization guide

### Future Enhancements Planned:

- [ ] Add user_accounts table for frontend users
- [ ] Implement reviews and ratings system
- [ ] Add job_postings table structure
- [ ] Create real_estate_listings table
- [ ] Add audit_log table for tracking changes

---

**Last Updated:** December 26, 2024  
**Database Version:** 2.0  
**Total Tables:** 15+  
**Status:** Production Active

---

_For database connection setup, see `docs/LOCAL_TO_REMOTE_DATABASE_SETUP.md`_  
_For API documentation, see `docs/API_DOCUMENTATION.md` (to be created)_
