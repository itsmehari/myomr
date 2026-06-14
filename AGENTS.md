---
status: canonical
last_reviewed: 2026-06-06
module: documentation
---

---
status: canonical
last_reviewed: 2026-06-06
module: platform
---

# Agent Guidelines for MyOMR.in

**Last Updated:** June 2026  
**Purpose:** Short entry (~2 min) for AI agents. Deep detail lives in linked docs — do not duplicate here.

---

## Project Overview

MyOMR.in is a local community platform for OMR, Chennai: news, events, jobs, classifieds, rent-lease, hostels, directories.

- **Stack:** PHP (procedural), MySQL, Bootstrap 5, vanilla JS
- **Hosting:** cPanel + Git deploy
- **Architecture:** [`.cursor/docs/architecture.md`](.cursor/docs/architecture.md)
- **Documentation map:** [`docs/KNOWLEDGE-MAP.md`](docs/KNOWLEDGE-MAP.md)
- **Project brain:** [`.cursor/README.md`](.cursor/README.md) · **SOPs:** [`.cursor/sop/README.md`](.cursor/sop/README.md)

### Documentation authority

| Question | Doc |
|----------|-----|
| Agent priorities, canonicals, 404 | This file (`AGENTS.md`) |
| Sprint state, handoff | [`.cursor/planning/PROJECT-STATE.md`](.cursor/planning/PROJECT-STATE.md) |
| How to do X (checklists) | `.cursor/sop/` |
| PRDs, audits, marketing | `docs/` |
| Always-on constraints | `.cursor/rules/*.mdc` (link only — see INDEX) |

---

## Agent Priorities

1. **Modular bootstrap** — `ROOT_PATH`, `page-bootstrap`, `omr_nav()`, `omr_footer()`
2. **Slug detail URLs** — `/{module}/{entity}/{id}/{slug}`
3. **Load footer.css** — via `head-includes.php` or `head-resources.php`
4. **Canonical URLs** — `$canonical_url`, base `https://myomr.in`; see [`docs/analytics-seo/CANONICAL-URL-METHODOLOGY.md`](docs/analytics-seo/CANONICAL-URL-METHODOLOGY.md)
5. **404** — `core/serve-404.php`; see [`docs/ERROR-HANDLING-404-RULES.md`](docs/ERROR-HANDLING-404-RULES.md)
6. **SOPs for ops** — [`.cursor/sop/README.md`](.cursor/sop/README.md)
7. **PHP string safety** — escape `'` in single-quoted strings; `php -l` on every changed `.php` file ([`.cursor/rules/php-single-quote-safety.mdc`](.cursor/rules/php-single-quote-safety.mdc))
8. **GA4 Data API** — property access in GA4 Admin; [`.cursor/rules/ga4-google-cloud.mdc`](.cursor/rules/ga4-google-cloud.mdc)
9. **Search Console** — API/MCP verification over raw HTTP checks
10. **Remote DB** — [`README-REMOTE-DATABASE.md`](README-REMOTE-DATABASE.md)

---

## Key modules (pointers)

| Module | Spec / README |
|--------|---------------|
| Jobs + Employer Pack | [`docs/product/EMPLOYER-PACK-PRODUCT.md`](docs/product/EMPLOYER-PACK-PRODUCT.md), [`docs/job-feature/`](docs/job-feature/) |
| Classified ads | [`docs/product/OMR-CLASSIFIED-ADS-SPEC.md`](docs/product/OMR-CLASSIFIED-ADS-SPEC.md) |
| News | [`.cursor/sop/NEWS-ARTICLE-PUBLISHING-SOP.md`](.cursor/sop/NEWS-ARTICLE-PUBLISHING-SOP.md) |
| Affiliate (articles) | [`.cursor/sop/AMAZON-AFFILIATE-SYSTEM-SOP.md`](.cursor/sop/AMAZON-AFFILIATE-SYSTEM-SOP.md) |

Full map: [`docs/KNOWLEDGE-MAP.md`](docs/KNOWLEDGE-MAP.md).

---

## Security

- Prepared statements; sanitize output with `htmlspecialchars()`
- Protect `/admin/` with `$_SESSION['admin_logged_in']`
- Never commit service-account JSON keys

---

## Skills (when relevant)

| Skill | Use when |
|-------|----------|
| `slug-urls-detail-pages` | Detail page URLs |
| `myomr-project` | Bootstrap, folder layout |
| `dashboard-designer` | Admin UI |

---

## Doc maintenance

```bash
php dev-tools/docs/audit-markdown-inventory.php --write
php dev-tools/docs/audit-markdown-inventory.php --fail-on-broken --tier=1
```

Housekeeping: [`docs/workflows-pipelines/documentation-housekeeping-workflow.md`](docs/workflows-pipelines/documentation-housekeeping-workflow.md)
