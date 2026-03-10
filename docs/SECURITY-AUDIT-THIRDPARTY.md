# Third-Party Script Security Audit (CISO / Ethical Hacker)

## Objective

Identify which third-party script causes the `directfwd` / `sk-jspark_init.php` malware injection (mixed content / blocked requests).

## Method: Binary Elimination

1. **Baseline**: Load page with **zero** third parties. Check DevTools → Network + Console.
2. **Add one script at a time**.
3. **First test where `directfwd` appears** = culprit.

## Test Harness

**URL**: `https://myomr.in/security-audit-thirdparty.php`

| Param      | Third Party           | Notes                    |
|-----------|------------------------|--------------------------|
| (none)    | None (baseline)        | Pure HTML                |
| `?add=bootstrap`    | Bootstrap CSS          | jsDelivr CDN             |
| `?add=fontawesome`  | Font Awesome           | cdnjs Cloudflare         |
| `?add=googlefonts`  | Google Fonts           | fonts.googleapis.com     |
| `?add=bootstrapjs`  | Bootstrap JS           | jsDelivr CDN             |
| `?add=jquery`       | jQuery                 | code.jquery.com          |
| `?add=ga`           | Google Analytics (gtag)| googletagmanager.com     |
| `?add=all`          | All of the above       | Full stack               |

## Procedure

1. Open Chrome/Firefox DevTools (F12).
2. Go to **Network** tab → filter by `directfwd` or `jsinit`.
3. Visit each URL in order (baseline first).
4. Record in the table: **directfwd present? YES / NO**.
5. The first row where YES appears → that third party is the source (or a downstream dependency).

## Suspects (priority order)

| # | Script         | Risk | Reason |
|---|----------------|------|--------|
| 1 | Google Analytics | High | GA4/GTM can load third-party scripts; compromised GA property or tag could inject malicious URLs. |
| 2 | jQuery         | Medium | Code.jquery.com is trusted; low likelihood but possible CDN compromise. |
| 3 | Bootstrap      | Low  | jsDelivr is widely used; low likelihood. |
| 4 | Font Awesome   | Low  | cdnjs is Cloudflare; low likelihood. |
| 5 | Google Fonts   | Low  | Google; low likelihood. |

## After Audit

- **If GA is culprit**: Review GA4 property settings; remove custom scripts/tags; consider self-hosted analytics or Plausible/Simple Analytics.
- **Restrict or remove** `security-audit-thirdparty.php` in production once done:
  - Delete the file, or
  - Add `.htaccess` rule: `RewriteRule ^security-audit-thirdparty\.php$ - [F,L]`

## References

- `directfwd` / `cdn.jsinit.directfwd.com` is known malware (script injection).
- Often introduced via: compromised GA/GTM tags, ad injectors, or browser extensions.
