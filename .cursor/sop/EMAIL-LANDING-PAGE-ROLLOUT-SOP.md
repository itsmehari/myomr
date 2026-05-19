# EMAIL-LANDING-PAGE-ROLLOUT-SOP

**Create email campaign landing pages (job postings, events, classifieds, etc.).**

---

## Scope

Email landing pages for campaign content (jobs, events, classifieds, rent-lease, hostels).

---

## Procedure

### 1. Canonical Template

**Template:** `omr-local-job-listings/jobs-email/myomr-job-posting-email.html`

### 2. Create Folder Structure

```
{module}/{module}-email/
├── myomr-{entity}-posting-email.html
└── README.md (optional)
```

Examples:
- `omr-local-events/events-email/myomr-event-listing-email.html`
- `omr-classifieds/classifieds-email/myomr-classifieds-posting-email.html`
- `omr-rent-lease/rent-email/myomr-rent-lease-listing-email.html`

### 3. Build HTML Page

**Include:**
- [ ] Header with MyOMR logo
- [ ] Hero section (featured listing)
- [ ] Call-to-action (CTA) button
- [ ] Footer with unsubscribe link

**CTA Button:**
```html
<a href="https://myomr.in/{entity}/{id}/{slug}?utm_source=email&utm_medium=email&utm_campaign={campaign_name}"
   style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none;">
  View {Entity} Details
</a>
```

### 4. UTM Parameters

**Standard params for GA4 tracking:**
```
?utm_source=email
&utm_medium=email
&utm_campaign={campaign_name}
&utm_content={entity_type}
```

Example: `...?utm_source=email&utm_medium=email&utm_campaign=jobs_weekly&utm_content=job_posting`

### 5. Canary Test

- [ ] Send to test email address first
- [ ] Verify: Links work, formatting correct, images load
- [ ] Check: Unsubscribe link works
- [ ] Check: Mobile rendering (mobile email client)

### 6. Rollout

- [ ] Send to 10% of subscribers (canary)
- [ ] Wait 24 hours, check CTR
- [ ] If CTR > threshold: Roll out to full list
- [ ] If CTR < threshold: Tweak and retry

---

## Validation

- [ ] CTA button prominent + clickable
- [ ] UTM params present
- [ ] Unsubscribe link works
- [ ] Mobile responsive
- [ ] Links point to correct entity URL

---

**Last Updated:** 2026-05-19
