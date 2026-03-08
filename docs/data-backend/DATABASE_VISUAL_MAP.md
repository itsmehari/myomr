# 🗺️ MyOMR Database Visual Map

## 📊 Database Structure Visualization

```
┌─────────────────────────────────────────────────────────────┐
│                  metap8ok_myomr Database                     │
│                     MySQL 5.7+ (UTF-8)                        │
└─────────────────────────────────────────────────────────────┘
                               │
      ┌────────────────────────┼────────────────────────┐
      │                        │                        │
┌─────▼─────┐          ┌──────▼──────┐         ┌──────▼──────┐
│  CONTENT  │          │ DIRECTORIES │         │   SYSTEM    │
│  TABLES   │          │   TABLES    │         │   TABLES    │
└───────────┘          └─────────────┘         └─────────────┘

```

---

## 📰 Content Tables (4 Tables)

```
┌──────────────────────────────────┐
│        news_bulletin             │
│━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━│
│ PK: id (INT AUTO)                │
│ • title (VARCHAR 255)            │
│ • summary (TEXT)                 │
│ • date (DATE)                    │
│ • tags (VARCHAR 255)             │
│ • image (VARCHAR 500)            │
│ • article_url (VARCHAR 500)      │
│ • created_at (TIMESTAMP)         │
└──────────────────────────────────┘
         ↓ Used by: News page

┌──────────────────────────────────┐
│            events                │
│━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━│
│ PK: id (INT AUTO)                │
│ • title (VARCHAR 255)            │
│ • description (TEXT)             │
│ • event_date (DATE)              │
│ • event_time (TIME)              │
│ • location (VARCHAR 255)         │
│ • organizer (VARCHAR 255)        │
│ • status (ENUM)                  │
│ • created_at (TIMESTAMP)         │
└──────────────────────────────────┘
         ↓ Used by: Events page

┌──────────────────────────────────┐
│           gallery                │
│━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━│
│ PK: id (INT AUTO)                │
│ • image_url (VARCHAR 500)        │
│ • caption (VARCHAR 255)          │
│ • category (VARCHAR 100)         │
│ • created_at (TIMESTAMP)         │
└──────────────────────────────────┘
         ↓ Used by: Gallery page

┌──────────────────────────────────┐
│          businesses              │
│━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━│
│ PK: id (INT AUTO)                │
│ • name (VARCHAR 255)             │
│ • category (VARCHAR 100)         │
│ • description (TEXT)             │
│ • contact_url (VARCHAR 255)      │
│ • featured (TINYINT 1)           │
│ • created_at (TIMESTAMP)         │
└──────────────────────────────────┘
         ↓ Used by: Homepage

```

---

## 🏢 Directory Tables (11 Tables)

### 🍽️ Advanced Directory (with Geolocation)

```
┌──────────────────────────────────────────────────┐
│              omr_restaurants                     │
│━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━│
│ PK: id (INT AUTO)                                │
│ • name (VARCHAR 255)          ◄──┐               │
│ • address (VARCHAR 500)          │ Filter by    │
│ • locality (VARCHAR 100)      ◄──┤ Locality     │
│ • cuisine (VARCHAR 255)       ◄──┤ Cuisine      │
│ • cost_for_two (INT)          ◄──┤ Price Range  │
│ • rating (DECIMAL 2,1)        ◄──┤ Rating       │
│ • availability (VARCHAR 255)     │               │
│ • accessibility (VARCHAR 255)    │               │
│ • reviews (TEXT)                 │               │
│ • imagelocation (VARCHAR 500)    │               │
│ • geolocation (POINT) ◄──────────┤ GPS Location │
│ • created_at (TIMESTAMP)         │               │
└──────────────────────────────────┴───────────────┘
         ↓ Used by: restaurants.php
         ↓ Features: Filtering, Pagination, Maps

```

### 🏫 Standard Directory Pattern (8 Tables)

All follow this pattern:

```
┌───────────────────────────────┐
│     omr[entity]list          │
│━━━━━━━━━━━━━━━━━━━━━━━━━━━━│
│ PK: slno (INT AUTO)          │
│ • [entity]name (VARCHAR 255) │
│ • address (VARCHAR 500)      │
│ • contact (VARCHAR 100)      │
│ • landmark (VARCHAR 255)     │
│ • [entity-specific fields]   │
│ • created_at (TIMESTAMP)     │
└───────────────────────────────┘
```

#### **Specific Tables:**

```
1. omrschoolslist
   ├─ board (CBSE/ICSE/State)
   └─ grades (PreK-12)

2. omrhospitalslist
   ├─ speciality (Medical dept)
   └─ emergency (yes/no)

3. omrbankslist
   ├─ branch (Branch name)
   └─ ifsc (IFSC code)

4. omratmslist
   ├─ availability (24/7)
   └─ services (Cash/Card)

5. omritcompanieslist
   ├─ website (Company URL)
   └─ employees (Company size)

6. omrindustrieslist
   └─ type (Industry category)

7. omrparkslist
   ├─ facilities (Amenities)
   └─ timings (Open hours)

8. omrgovernmentofficeslist
   ├─ department (Govt dept)
   ├─ services (Services list)
   └─ timings (Office hours)
```

---

## ⚙️ System Tables (2 Tables)

```
┌──────────────────────────────────┐
│        List of Areas             │
│━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━│
│ PK: id (INT AUTO)                │
│ • Areas (VARCHAR 255)            │
│                                  │
│ Sample Data:                     │
│ • Perungudi                      │
│ • Kandanchavadi                  │
│ • Thoraipakkam                   │
│ • Karapakkam                     │
│ • Sholinganallur                 │
│ • Navalur                        │
│ • Kelambakkam                    │
└──────────────────────────────────┘
         ↓ Used by: Homepage dropdown

┌──────────────────────────────────┐
│         admin_users              │
│━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━│
│ PK: id (INT AUTO)                │
│ UQ: username (VARCHAR 100)       │
│ • password (VARCHAR 255)         │
│ • email (VARCHAR 255)            │
│ • full_name (VARCHAR 255)        │
│ • role (VARCHAR 50)              │
│ • last_login (TIMESTAMP)         │
│ • created_at (TIMESTAMP)         │
└──────────────────────────────────┘
         ↓ Used by: Admin panel login

```

---

## 🔗 Data Flow Visualization

```
┌──────────────┐
│    USER      │
└──────┬───────┘
       │
       ▼
┌─────────────────────────────────────────┐
│           PHP Application               │
│  (index.php, admin/*.php, omr-listings) │
└──────────────┬──────────────────────────┘
               │
               │ mysqli queries
               │
               ▼
    ┌──────────────────────────┐
    │  core/omr-connect.php    │
    │  (Database Connection)   │
    └──────────┬───────────────┘
               │
               │ localhost:3306 (live)
               │ localhost:3307 (local via SSH)
               │
               ▼
    ┌──────────────────────────┐
    │   MySQL Database         │
    │   metap8ok_myomr         │
    │                          │
    │   15+ Tables             │
    └──────────────────────────┘
```

---

## 🎯 Query Patterns

### **Homepage (index.php)**

```
news_bulletin ─────► Latest 10 news items
    │
events ────────────► Upcoming 6 events
    │
businesses ────────► Featured 6 businesses
    │
gallery ───────────► Latest 8 photos
    │
List of Areas ─────► Area dropdown menu
```

### **Directory Pages**

```
omr-listings/schools.php ─────► omrschoolslist
omr-listings/hospitals.php ───► omrhospitalslist
omr-listings/banks.php ───────► omrbankslist
omr-listings/atms.php ────────► omratmslist
omr-listings/restaurants.php ─► omr_restaurants
omr-listings/it-companies.php ► omritcompanieslist
omr-listings/industries.php ──► omrindustrieslist
omr-listings/parks.php ───────► omrparkslist
omr-listings/govt-offices.php ► omrgovernmentofficeslist
```

### **Admin Panel**

```
admin/news-add.php ──────► INSERT INTO news_bulletin
admin/news-edit.php ─────► UPDATE news_bulletin
admin/news-list.php ─────► SELECT FROM news_bulletin

admin/restaurants-add.php ───► INSERT INTO omr_restaurants
admin/restaurants-edit.php ──► UPDATE omr_restaurants
admin/restaurants-list.php ──► SELECT FROM omr_restaurants

admin/events-add.php ────► INSERT INTO events
admin/events-edit.php ───► UPDATE events
admin/events-list.php ───► SELECT FROM events
```

---

## 📦 Table Size Estimates

```
Large Tables (100+ records):
┌──────────────────────┬─────────┐
│ news_bulletin        │ 100-500 │
│ gallery              │ 100-500 │
│ omrschoolslist       │ 100-200 │
│ omratmslist          │ 100-200 │
└──────────────────────┴─────────┘

Medium Tables (50-100 records):
┌──────────────────────┬─────────┐
│ events               │  50-200 │
│ omr_restaurants      │  50-100 │
│ omrhospitalslist     │  50-100 │
│ omritcompanieslist   │  50-100 │
└──────────────────────┴─────────┘

Small Tables (< 50 records):
┌──────────────────────┬─────────┐
│ omrbankslist         │  30-50  │
│ omrgovernmentoffices │  30-50  │
│ List of Areas        │  20-30  │
│ omrindustrieslist    │  20-50  │
│ omrparkslist         │  20-40  │
│ businesses           │  20-50  │
│ admin_users          │   2-5   │
└──────────────────────┴─────────┘
```

---

## 🔐 Access Permissions

```
┌─────────────────────────────────────────────┐
│              PUBLIC ACCESS                  │
│  (SELECT only, no authentication)           │
│━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━│
│ • news_bulletin (read all)                  │
│ • events (read active events)               │
│ • All directory tables (read all)           │
│ • gallery (read all)                        │
│ • businesses (read featured only)           │
│ • List of Areas (read all)                  │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│              ADMIN ACCESS                   │
│  (Full CRUD, session authenticated)         │
│━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━│
│ • news_bulletin (CREATE, READ, UPDATE, DEL) │
│ • events (CREATE, READ, UPDATE, DELETE)     │
│ • omr_restaurants (CREATE, READ, UPDATE, DEL│
│ • admin_users (READ, UPDATE only)           │
└─────────────────────────────────────────────┘
```

---

## 🎨 Color-Coded Table Types

```
🟢 Green = Content Tables
   news_bulletin, events, gallery, businesses

🔵 Blue = Directory Tables (Standard)
   omrschoolslist, omrhospitalslist, omrbankslist,
   omratmslist, omritcompanieslist, omrindustrieslist,
   omrparkslist, omrgovernmentofficeslist

🟣 Purple = Directory Tables (Advanced)
   omr_restaurants (with geolocation & filters)

🟡 Yellow = System Tables
   List of Areas, admin_users
```

---

## 📍 Geolocation Feature (omr_restaurants)

```
┌─────────────────────────────────────┐
│      Geolocation Workflow           │
└─────────────────────────────────────┘

1. INSERT
   ↓
   ST_GeomFromText('POINT(lng lat)')
   ↓
   Stored as POINT type

2. QUERY
   ↓
   ST_Distance_Sphere(geolocation, point)
   ↓
   Returns distance in meters

3. DISPLAY
   ↓
   Google Maps integration
   ↓
   Show restaurant on map
```

---

## 🚀 Performance Indexes

```
Primary Keys (AUTO_INCREMENT):
- All tables have PK on id/slno

Frequently Queried Columns:
┌─────────────────────┬──────────────────┐
│ Table               │ Indexed Columns  │
├─────────────────────┼──────────────────┤
│ news_bulletin       │ date, tags       │
│ events              │ event_date,status│
│ omr_restaurants     │ locality,cuisine,│
│                     │ rating,geolocation│
│ omrschoolslist      │ schoolname       │
│ omrhospitalslist    │ hospitalname     │
│ omrbankslist        │ bankname, ifsc   │
└─────────────────────┴──────────────────┘
```

---

## 📊 Database Statistics (Estimated)

```
Total Tables: 15+
Total Columns: ~150
Total Records: 1,500-3,000
Database Size: 10-50 MB
Index Size: 2-5 MB
Average Response Time: <100ms
```

---

## 🔄 Backup Strategy

```
┌──────────────────────┐
│   DAILY BACKUPS      │
│  (Automated cron)    │
└──────┬───────────────┘
       │
       ├─► Full backup (mysqldump)
       ├─► Schema only (--no-data)
       └─► Critical tables only
              └─► news_bulletin
              └─► events
              └─► omr_restaurants
              └─► admin_users
```

---

**Quick Links:**

- Complete Reference: `docs/DATABASE_STRUCTURE.md`
- Quick Guide: `docs/DATABASE_QUICK_REFERENCE.md`
- Connection Setup: `docs/LOCAL_TO_REMOTE_DATABASE_SETUP.md`
- Export Tool: `export-database-schema.php`

---

**Last Updated:** December 26, 2024  
**Database:** metap8ok_myomr  
**Version:** 2.0
