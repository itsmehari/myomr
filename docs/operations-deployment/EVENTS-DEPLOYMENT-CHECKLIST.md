## Events Module – Deployment Checklist (Shared Hosting)

Pre‑deploy
- [ ] Verify DB creds in `core/omr-connect.php`
- [ ] Ensure `/weblog/` directory exists and writable
- [ ] Confirm `.htaccess` at root contains Events routes
- [ ] Confirm `robots.txt` points to root `sitemap.xml`

Deploy
- [ ] Upload/overwrite changed files under `omr-local-events/`, `components/`, root `.htaccess`, `robots.txt`, `generate-sitemap-index.php`
- [ ] Invalidate PHP opcache (touch files or via cPanel)

Post‑deploy
- [ ] Visit `/omr-local-events/` (listing) and an event detail URL to smoke test
- [ ] Check `/omr-local-events/sitemap.xml` and root `/sitemap.xml`
- [ ] Submit `/sitemap.xml` in Search Console (if not already)
- [ ] Create GA dashboard per `docs/GA-DASHBOARD-EVENTS.md`
- [ ] Verify header CTA and homepage/news widgets render

Rollout
- [ ] Publish “This Weekend in OMR” roundup
- [ ] Seed 3–5 example events (see `dev-tools/migrations/2025-10-31_seed_example_events.sql`)
- [ ] Share links on WhatsApp/Telegram with UTM

Triage (first week)
- [ ] Review `/weblog/events-errors.log` daily
- [ ] Watch Search Console coverage and fix any 404s
- [ ] Track GA funnel (views → shares/tickets → submissions)


