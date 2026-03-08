## Security Headers – MyOMR (Shared Hosting)

Enabled in root `.htaccess`:
- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: SAMEORIGIN`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy: geolocation=(), microphone=(), camera=()`
- (Optional) `Content-Security-Policy` – add after CDN inventory

Notes
- HSTS (`Strict-Transport-Security`) can be enabled after verifying full HTTPS
- Test pages after tightening CSP; allow necessary CDNs only


