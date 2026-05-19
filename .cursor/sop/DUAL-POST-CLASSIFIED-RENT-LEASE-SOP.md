# DUAL-POST-CLASSIFIED-RENT-LEASE-SOP

**Publish properties to both classified ads + rent-lease without duplication.**

---

## Scope

Properties can be cross-posted to classified ads (services) and rent-lease (housing). Prevent slug collision and duplicate entries.

---

## When to Use Which

| Entity Type | Table | URL | When |
|---|---|---|---|
| Housing (rent/sale) | `properties` | `/property/{id}/{slug}` | Always use rent-lease |
| Services wanted/offered | `classifieds` | `/classified/{id}/{slug}` | Use classifieds |
| Property as service | `classifieds` + `properties` | Both URLs | Use BOTH (cross-post) |

**Example:** "2BHK for rent" → publish to `properties` (primary) + `classifieds` (cross-post).

---

## Procedure

### 1. Prepare Data

- [ ] Property details ready (title, description, price, location)
- [ ] Determine if dual-post needed
- [ ] Generate slug (unique per locality)

### 2. Check Collision (CRITICAL)

**Before inserting, check BOTH tables:**

```php
// Check rent-lease table:
$exists_rent = $db->query(
    "SELECT id FROM properties WHERE slug = ? AND locality_id = ?",
    [$slug, $locality_id]
);

// Check classifieds table:
$exists_class = $db->query(
    "SELECT id FROM classifieds WHERE slug = ?"  // global unique
    [$slug]
);

if ($exists_rent || $exists_class) {
    die("Slug collision! Use -v2");
}
```

### 3. Insert to Primary Table

Insert to `properties` or `classifieds` first (primary).

### 4. If Cross-Posting: Insert to Secondary Table

If dual-posting:
- [ ] Use same slug (or -v2 if collision)
- [ ] Add cross-link in description (e.g., "Also listed at [rent-lease URL]")
- [ ] Insert second entry with same data

### 5. Verify Both URLs Work

- [ ] Primary URL renders: ✅
- [ ] Secondary URL renders: ✅
- [ ] Both in sitemaps: ✅

---

## Example: 2BHK Cross-Post

```php
// 1. Insert to properties (rent-lease):
$rental_id = insert_property([
    'title' => '2BHK Thiruvanmiyur',
    'slug' => '2bhk-thiruvanmiyur',
    'locality_id' => 3,
    'price' => 15000,
    'description' => 'Modern 2BHK apartment...'
]);
// Result: https://myomr.in/property/89/2bhk-thiruvanmiyur

// 2. Insert to classifieds:
$classified_id = insert_classified([
    'title' => '2BHK for Rent - Thiruvanmiyur',
    'slug' => '2bhk-thiruvanmiyur',  // Same slug (global unique OK)
    'category_id' => 5,  // rental-housing
    'description' => 'Modern 2BHK apartment...  
                      Also listed: https://myomr.in/property/89/2bhk-thiruvanmiyur'
]);
// Result: https://myomr.in/classified/156/2bhk-thiruvanmiyur
```

---

## Validation

- [ ] Slug unique in rent-lease table
- [ ] Slug unique in classifieds table (no collision)
- [ ] Both URLs accessible
- [ ] Both in sitemaps
- [ ] Cross-links in descriptions

---

**Last Updated:** 2026-05-19
