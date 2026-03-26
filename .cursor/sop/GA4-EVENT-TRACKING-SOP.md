# GA4 Event Tracking SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Engineering + Analytics

---

## 1. Scope

Browser-side event tracking via **gtag** (GA4) on MyOMR.in. Excludes GA4 Data API server reports (see [AGENTS.md](../../AGENTS.md) and `.cursor/rules/ga4-google-cloud.mdc`).

**Measurement ID:** `G-JYSF141J1H` (loaded from [components/analytics.php](../../components/analytics.php))

---

## 2. Principles

| Rule | Detail |
|------|--------|
| Single analytics include | Use `components/analytics.php` in `<head>`; no duplicate gtag |
| Event naming | Prefer `snake_case` for custom events (e.g. `affiliate_link_click`) |
| Params | Use consistent keys: `event_category`, `event_label`, plus domain-specific names |
| Helper | [assets/js/analytics-tracking.js](../../assets/js/analytics-tracking.js) exposes `trackEvent()`, `trackExternalLink()`, etc. |

---

## 3. Preconditions

- Page includes `components/analytics.php`
- For DebugView: GA4 Debug mode or browser extension as per Google docs

---

## 4. Procedure

### 4.1 Add a new custom event

1. Choose a unique event name and list required parameters.
2. Implement in JS (inline on page or shared `assets/js/...`).
3. Call `gtag('event', 'event_name', { ... })` or wrap with `trackEvent()` for UA-style mapping.
4. Document in this repo: `.cursor/LEARNINGS.md` or relevant SOP.

**Example (affiliate on article detail):**

- Event: `affiliate_link_click`
- Params: `event_category`, `event_label` (product id), `affiliate_network`, `affiliate_position`, `article_title`

### 4.2 Register in GA4 (optional)

1. In GA4 Admin → Events: mark as key event if conversion.
2. Create explorations or reports as needed.

### 4.3 Validate

1. Chrome: GA4 DebugView (or Tag Assistant).
2. Trigger action (click, submit).
3. Confirm event name and parameters match spec.

---

## 5. Validation checklist

- [ ] Only one gtag load per page
- [ ] Event fires on intended action only (no double handlers)
- [ ] PII not sent in event params (emails, full phone in clear text)
- [ ] `affiliate_*` / `job_*` events documented for stakeholders

---

## 6. Failure handling

| Issue | Action |
|-------|--------|
| `gtag is not defined` | Ensure `analytics.php` in head; check ad blockers for local testing |
| Duplicate events | Remove second listener or consolidate |

---

## 7. GA4 Data API (reporting only)

- Property ID ≠ Measurement ID — see `core/analytics-config.php`
- Snapshot CLI: `php dev-tools/analytics/fetch-ga4-snapshot.php` → local markdown (gitignored)

---

## 8. Related references

- [components/analytics.php](../../components/analytics.php)
- [assets/js/analytics-tracking.js](../../assets/js/analytics-tracking.js)
- [AMAZON-AFFILIATE-SYSTEM-SOP.md](AMAZON-AFFILIATE-SYSTEM-SOP.md)
