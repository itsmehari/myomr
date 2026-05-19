# EXTERNAL-SQL-IMPORT-SOP

**Safely import external SQL data (migrations, bulk uploads) into MyOMR.**

---

## Scope

Importing CSV exports, SQL dumps, or bulk data without schema mismatches or data corruption.

---

## Procedure

### 1. Diff Columns (CRITICAL)

**Before import, compare source vs. target schema:**

```bash
# Export source columns:
# Get CSV header row or SQL CREATE TABLE statement

# Target schema:
# From docs/data-model.md or:
DESCRIBE {table};

# Diff them manually or script:
# Source: pin_code, locality, price
# Target: pincode, location, amount
# MISMATCH! Need mapping
```

### 2. Create Mapping

Map source columns to target table:

| Source Column | Target Column | Type | Notes |
|---|---|---|---|
| pin_code | pincode | INT | Rename needed |
| city_name | locality_id | INT | Need FK lookup |
| monthly_rent | price | INT | Direct copy |

### 3. Create Temp Table

```sql
CREATE TEMP TABLE temp_import LIKE {target_table};
```

### 4. Load Data

```sql
LOAD DATA INFILE '/path/to/file.csv' 
INTO TABLE temp_import 
FIELDS TERMINATED BY ',' 
IGNORE 1 ROWS;
```

### 5. Validate

```sql
-- Check row count
SELECT COUNT(*) FROM temp_import;

-- Check for NULLs in required fields
SELECT * FROM temp_import WHERE id IS NULL;

-- Check FK constraints
SELECT * FROM temp_import t 
WHERE t.category_id NOT IN (SELECT id FROM categories);
```

### 6. Manual Review

- [ ] Sample 5-10 rows look correct
- [ ] No unexpected NULLs
- [ ] FK references exist
- [ ] Data types match

### 7. Insert into Live

```sql
INSERT INTO {target_table} 
SELECT * FROM temp_import 
WHERE <validation_checks>;
```

### 8. Verify

```sql
SELECT COUNT(*) FROM {target_table};
-- Compare to pre-import count + imported count
```

---

## Rollback

```sql
DELETE FROM {table} WHERE id > {last_id_before_import};
```

---

## Common Issues

| Issue | Solution |
|---|---|
| Duplicate column names | Check CSV header matches schema |
| FK constraint error | Verify referenced ID exists |
| Data type mismatch | Convert in SELECT (e.g., CAST(price AS INT)) |
| Encoding error (UTF-8, BOM) | Re-save CSV as UTF-8 no BOM |

---

**Last Updated:** 2026-05-19
