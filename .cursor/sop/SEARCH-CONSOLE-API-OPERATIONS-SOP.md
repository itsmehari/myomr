# Search Console API Operations SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Engineering / SEO automation

---

## 1. Scope

Operational steps for submitting and verifying sitemaps using the **Google Search Console API** (and optional Cursor MCP), aligned with project practice: prefer API status over unreliable raw HTTP checks from some environments.

---

## 2. Preconditions

- Google Cloud project with Search Console API usage (as configured for this repo)
- **Service account JSON** — store outside web root; never commit
- Service account email added as user in Search Console (**Full** for submissions where required)
- Property identifier: **`sc-domain:myomr.in`** for domain property (per project history)

---

## 3. Key files

| File | Purpose |
|------|---------|
| [dev-tools/mcp/search_console_mcp.py](../../dev-tools/mcp/search_console_mcp.py) | MCP server for Search Console ops |
| [AGENTS.md](../../AGENTS.md) | High-level Search Console notes |

Environment variables (typical for MCP script):

- `GSC_CREDENTIALS_PATH` — absolute path to service account JSON
- `GSC_SITE_URL` — e.g. `sc-domain:myomr.in`
- Optional: `GSC_CHILD_SITEMAPS` — comma-separated URLs

---

## 4. Procedure

### 4.1 Verify access

1. Confirm service account email appears in Search Console → Settings → Users and permissions.
2. If API returns permission errors, fix Search Console access first (not only GCP IAM).

### 4.2 Submit sitemaps

1. Ensure sitemap URLs are **HTTPS** and match canonical generators.
2. Root index: `https://myomr.in/sitemap.xml`
3. Submit child sitemaps as listed in project docs (e.g. `sitemap-pages.xml`, `local-news/sitemap.xml`, module sitemaps).
4. Use API response fields: `isPending`, `errors`, `warnings`, `lastSubmitted`.

### 4.3 URL-prefix vs domain property

- Domain property submissions may succeed where URL-prefix property fails if SA lacks that property — track separately.

### 4.4 MCP (Cursor)

1. Ensure Python script path exists and Cursor MCP config points to it.
2. Validate credentials path and site URL env vars.

---

## 5. Validation checklist

- [ ] API returns success or expected pending state (not 403 permission denied)
- [ ] Sitemap status shows in GSC UI after propagation delay
- [ ] No credential JSON in repo or chat logs

---

## 6. Failure handling

| Symptom | Action |
|---------|--------|
| Permission denied | Add SA email to correct GSC property; use domain property if that is the automation target |
| 406 or odd HTTP from curl | Do not treat as authoritative; use API status |
| Script missing | Restore `dev-tools/mcp/search_console_mcp.py` from repo |

---

## 7. Security

- Rotate keys if `private_key` ever exposed
- Never paste full JSON into tickets or LLM chats

---

## 8. Related references

- [.cursor/LEARNINGS.md](../LEARNINGS.md) — Search Console automation section
- [SEO-CANONICAL-SITEMAP-SOP.md](SEO-CANONICAL-SITEMAP-SOP.md)
