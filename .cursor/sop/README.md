# MyOMR Standard Operating Procedures (SOP)

Operational runbooks for MyOMR.in. All SOPs live in this folder and follow a shared structure.

## SOP index

| SOP | Purpose |
|-----|---------|
| [AMAZON-AFFILIATE-SYSTEM-SOP.md](AMAZON-AFFILIATE-SYSTEM-SOP.md) | Amazon affiliate blocks on article detail |
| [NEWS-ARTICLE-PUBLISHING-SOP.md](NEWS-ARTICLE-PUBLISHING-SOP.md) | End-to-end news article publish workflow |
| [NEWS-ARTICLE-DETAIL-QA-SOP.md](NEWS-ARTICLE-DETAIL-QA-SOP.md) | QA checklist for article detail pages |
| [SEO-CANONICAL-SITEMAP-SOP.md](SEO-CANONICAL-SITEMAP-SOP.md) | Canonical URLs, sitemaps, redirects |
| [SEARCH-CONSOLE-API-OPERATIONS-SOP.md](SEARCH-CONSOLE-API-OPERATIONS-SOP.md) | Search Console API / MCP operations |
| [GA4-EVENT-TRACKING-SOP.md](GA4-EVENT-TRACKING-SOP.md) | GA4 events, naming, validation |
| [AFFILIATE-CATALOG-MAINTENANCE-SOP.md](AFFILIATE-CATALOG-MAINTENANCE-SOP.md) | Affiliate registry maintenance & optimization |
| [AD-BANNER-SLOTS-REGISTRY-SOP.md](AD-BANNER-SLOTS-REGISTRY-SOP.md) | Banner ads registry and slot placements |
| [MODULAR-BOOTSTRAP-NEW-PAGE-SOP.md](MODULAR-BOOTSTRAP-NEW-PAGE-SOP.md) | New PHP page using ROOT_PATH bootstrap |
| [404-ERROR-HANDLING-SOP.md](404-ERROR-HANDLING-SOP.md) | Branded 404 and missing-entity handling |
| [REMOTE-DB-MIGRATION-SOP.md](REMOTE-DB-MIGRATION-SOP.md) | Remote MySQL migrations from dev machines |
| [CPANEL-DEPLOYMENT-SOP.md](CPANEL-DEPLOYMENT-SOP.md) | Git deploy via cPanel, post-deploy checks |
| [SECURITY-CHECKLIST-SOP.md](SECURITY-CHECKLIST-SOP.md) | Security review for changes |
| [HOMEPAGE-COMPONENT-RELEASE-SOP.md](HOMEPAGE-COMPONENT-RELEASE-SOP.md) | Homepage sections and `index.php` changes |
| [MODULE-LAUNCH-SOP.md](MODULE-LAUNCH-SOP.md) | Launching a new site module |
| [INCIDENT-DEBUG-TRIAGE-SOP.md](INCIDENT-DEBUG-TRIAGE-SOP.md) | Production incident response |

## Standard SOP structure (use for new SOPs)

Copy this skeleton into new files and fill in sections.

```markdown
# Title (MyOMR)

**Version:** 1.0  
**Last reviewed:** YYYY-MM  
**Owner:** Engineering / Content / Ops (as applicable)

---

## 1. Scope

What this SOP covers and what it explicitly excludes.

## 2. Ownership and RACI (lightweight)

- **R**esponsible: who executes
- **A**ccountable: who approves or owns outcome
- **C**onsulted / **I**nformed: as needed

## 3. Preconditions

Tools, access, files, or environment required before starting.

## 4. Procedure

Numbered steps. Include exact paths (`core/...`, `local-news/...`) and commands where useful.

## 5. Validation checklist

Checkbox list to confirm success before sign-off.

## 6. Rollback and failure handling

What to do if something breaks; how to revert or mitigate.

## 7. Evidence and logging

What to record (ticket, commit, Search Console status, etc.).

## 8. Related references

Links to `docs/`, `AGENTS.md`, other SOPs, and key code files.
```

## Cross-project references

- Agent summary: [AGENTS.md](../../AGENTS.md)
- Learnings: [.cursor/LEARNINGS.md](../LEARNINGS.md)
- Recent updates: [.cursor/RECENT-UPDATES.md](../RECENT-UPDATES.md)
