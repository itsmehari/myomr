### Cron setup: Nightly sitemap refresh (cPanel)

Goal: Regenerate `sitemap.xml` daily using the project generator.

Files used
- `sitemap-generator.php` (produces XML when executed)
- `dev-tools/tasks/build-sitemap.php` (captures generator output and writes `sitemap.xml`)

Recommended cron command (PHP CLI)
```
php -q /home/USERNAME/public_html/dev-tools/tasks/build-sitemap.php >/home/USERNAME/cron-logs/sitemap.log 2>&1
```

Steps (cPanel)
1) Create folder `cron-logs/` under your user home (optional) for logs.
2) Open cPanel → Cron Jobs → Add New Cron Job.
3) Schedule: Once per day at 02:10 (server time).
4) Command: use the PHP CLI command above (replace USERNAME with your cPanel username; adjust path if your docroot differs).

Alternative (curl)
```
curl -s https://myomr.in/sitemap-generator.php > /home/USERNAME/public_html/sitemap.xml
```

Notes
- Ensure file permissions allow writing `sitemap.xml` in the web root (build script handles this when run via CLI).
- After first run, submit the sitemap URL in Google Search Console.
- If the project is deployed in a subfolder, adjust paths accordingly.


