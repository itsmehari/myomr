# 📊 Google Analytics Event Tracking Guide

This guide explains how to track specific events using Google Analytics on MyOMR.in.

## 🎯 Overview

We have a centralized analytics tracking system located at `/assets/js/analytics-tracking.js` that provides easy-to-use functions for tracking various user interactions.

## 📋 Prerequisites

- Google Analytics (gtag.js) must be loaded on the page (already included via `components/analytics.php`)
- The analytics tracking script must be included: `<script src="/assets/js/analytics-tracking.js"></script>`

## 🚀 Common Use Cases

### 1. Track "Read More" Clicks on News Articles

**Current Implementation:** Already added to `home-page-news-cards.php`

```html
<a href="/local-news/article.php?slug=<?php echo htmlspecialchars($row['slug']); ?>" 
   class="news-read-more-link" 
   data-article-title="<?php echo htmlspecialchars($row['title']); ?>"
   data-article-slug="<?php echo htmlspecialchars($row['slug']); ?>"
   onclick="trackNewsReadMore(event, '<?php echo htmlspecialchars($row['title']); ?>', '<?php echo htmlspecialchars($row['slug']); ?>');">
  Read More
</a>
```

**What gets tracked:**
- Event Name: `read_more_click`
- Category: `News`
- Label: Article Title
- Additional event: `article_read_more` with article slug and URL

---

### 2. Track Button Clicks

```html
<button onclick="trackButtonClick('Contact Us', 'homepage');">
  Contact Us
</button>
```

Or using data attributes:

```html
<button class="track-click" 
        data-button-name="Contact Us" 
        data-button-location="homepage">
  Contact Us
</button>

<script>
document.querySelectorAll('.track-click').forEach(function(btn) {
  btn.addEventListener('click', function() {
    trackButtonClick(
      this.getAttribute('data-button-name'),
      this.getAttribute('data-button-location')
    );
  });
});
</script>
```

---

### 3. Track Form Submissions

```html
<form onsubmit="trackFormSubmit('Contact Form', 'contact');">
  <!-- form fields -->
  <button type="submit">Submit</button>
</form>
```

---

### 4. Track External Link Clicks

```html
<a href="https://example.com" 
   onclick="trackExternalLink('https://example.com', 'External Partner'); return true;">
  Visit Partner Site
</a>
```

---

### 5. Track Job Portal Actions

```php
<!-- Track job application -->
<a href="/apply-job.php?id=<?php echo $job_id; ?>" 
   onclick="trackJobAction('apply', '<?php echo htmlspecialchars($job_title); ?>', '<?php echo $job_id; ?>');">
  Apply Now
</a>

<!-- Track job view -->
<script>
// Track when job detail page loads
trackJobAction('view', '<?php echo htmlspecialchars($job_title); ?>', '<?php echo $job_id; ?>');
</script>
```

---

### 6. Track Employer Actions

```php
<!-- Track job posting -->
<a href="/post-job.php" 
   onclick="trackEmployerAction('post_job', '<?php echo htmlspecialchars($employer_email); ?>');">
  Post a Job
</a>
```

---

### 7. Track Search Queries

```javascript
// In your search form handler
function handleSearch(query) {
  trackSearch(query, 'news'); // or 'job', 'business', etc.
  // ... rest of search logic
}
```

---

### 8. Track File Downloads

```html
<a href="/downloads/report.pdf" 
   onclick="trackDownload('Annual Report 2024', 'pdf');">
  Download Report
</a>
```

---

### 9. Track Video Plays

```html
<video onplay="trackVideoPlay('Welcome Video', '/videos/welcome.mp4');">
  <!-- video source -->
</video>
```

---

## 🛠️ Advanced: Custom Event Tracking

For custom events, use the `trackEvent()` function directly:

```javascript
trackEvent(
    'event_name',        // Event name (e.g., 'custom_action')
    'Event Category',    // Category (e.g., 'Feature')
    'Event Label',       // Label (e.g., 'Feature Name')
    optionalValue        // Optional numeric value
);
```

**Example:**

```javascript
// Track when user expands a section
function expandSection(sectionName) {
    trackEvent('section_expand', 'User Interaction', sectionName);
    // ... expand logic
}

// Track with value
trackEvent('purchase', 'E-commerce', 'Product Name', 99.99);
```

---

## 📈 Viewing Events in Google Analytics

1. **Go to Google Analytics Dashboard**
2. **Navigate to:** Reports → Engagement → Events
3. **Filter by:**
   - Event Name: `read_more_click`, `button_click`, etc.
   - Event Category: `News`, `Button`, `Form`, etc.
   - Event Label: Specific article titles, button names, etc.

---

## 📝 Event Naming Conventions

### Standard Events:
- `read_more_click` - User clicks "Read More" on news articles
- `button_click` - User clicks any tracked button
- `form_submit` - User submits a form
- `external_link_click` - User clicks external link
- `search` - User performs a search
- `file_download` - User downloads a file
- `video_play` - User plays a video
- `job_apply`, `job_view`, `job_save` - Job portal actions
- `employer_post_job`, `employer_edit_job` - Employer actions

### Categories:
- `News` - News/article related events
- `Button` - Button click events
- `Form` - Form submission events
- `External Link` - External link clicks
- `Search` - Search events
- `Download` - File download events
- `Video` - Video play events
- `Job Portal` - Job listing events
- `Job Portal - Employer` - Employer-specific events

---

## ✅ Best Practices

1. **Always include the tracking script** before using tracking functions
2. **Use descriptive labels** that will help you identify the specific action
3. **Test in Google Analytics Real-Time** view to verify events are being tracked
4. **Don't track sensitive information** (passwords, personal data) in event labels
5. **Be consistent** with event naming and categories across the site

---

## 🔍 Testing Your Events

1. **Open your site** in a browser
2. **Open Google Analytics** in another tab
3. **Go to:** Reports → Realtime → Events
4. **Perform the action** you want to track (e.g., click "Read More")
5. **Check Real-Time Events** - you should see your event appear within seconds

---

## 📞 Need Help?

If you need to track a new type of event not covered here, you can:
1. Use the generic `trackEvent()` function
2. Add a new helper function to `/assets/js/analytics-tracking.js`
3. Follow the existing patterns in the file

---

## 🎯 Current Implementations

### ✅ Already Implemented:
- **News "Read More" clicks** - `home-page-news-cards.php`
- **Job Promotion Modal** - `index.php` (shown, closed, button clicks)

### 📋 To Implement:
- Form submissions (contact forms, job applications)
- External link clicks
- Search queries
- Job portal actions (apply, view, save)
- Employer actions (post job, edit job, view applications)

---

**Last Updated:** 2025-11-02

