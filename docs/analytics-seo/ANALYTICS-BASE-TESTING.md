# Google Analytics Base Component — Usage & Testing

**File:** `components/analytics.php`  
**Measurement ID:** G-JYSF141J1H  
**Used by:** index.php, jobs-in-omr-chennai.php, local-news/article.php, omr-local-job-listings, and many other pages.

---

## Include in a Page

Place in `<head>`, after ROOT_PATH is defined:

```php
<?php require_once ROOT_PATH . '/components/analytics.php'; ?>
```

For root-level pages:
```php
<?php include 'components/analytics.php'; ?>
```

---

## What It Tracks (Base)

- **Page views** — automatic on every page load
- **Content group** — auto-detected from URL path (Homepage, Job Listings, Local News, etc.)

---

## Optional Variables (set before include)

| Variable | Type | Example |
|----------|------|---------|
| `$ga_content_group` | string | Override auto-detected section |
| `$ga_custom_params` | array | `['article_category' => 'Local News', 'article_author' => 'Editor']` |
| `$ga_user_properties` | array | `['user_type' => 'employer']` |
| `$ga_user_id` | int | `$employerId` for cross-device tracking |

---

## How to Test

### 1. GA4 DebugView (recommended)

1. Install [Google Analytics Debugger](https://chrome.google.com/webstore/detail/google-analytics-debugger/jnkmfdileelhofjcijamephohjechhna) or use Chrome DevTools.
2. Visit your page (e.g. `https://myomr.in/` or local dev URL).
3. In GA4: **Admin → DebugView**.
4. Verify `page_view` events appear in real time.

### 2. Network Tab

1. Open DevTools → Network.
2. Filter by `gtag` or `collect`.
3. Load page — you should see requests to `www.googletagmanager.com`.

### 3. Console Check

```javascript
// After page load
typeof gtag === 'function'  // should be true
```

### 4. Real-time Report

1. GA4 → **Reports → Real-time**.
2. Visit the page.
3. Your visit should show within seconds.

---

## Pages That Include Analytics

| Page | Include Path |
|------|--------------|
| index.php | ROOT_PATH |
| jobs-in-omr-chennai.php | components/analytics.php |
| local-news/article.php | ROOT_PATH |
| omr-local-job-listings/index.php | ROOT_PATH |
| Many local-news/*.php | ../components/analytics.php |
| omr-listings/*, omr-hostels-pgs/*, etc. | ../components/analytics.php |

---

## Custom Events (on top of base)

Pages can call `gtag('event', ...)` after analytics loads:

```html
<button onclick="if(typeof gtag==='function') gtag('event','subscribe',{'event_category':'Newsletter'});">
  Subscribe
</button>
```
