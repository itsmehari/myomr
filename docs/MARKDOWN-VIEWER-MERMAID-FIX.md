# Markdown Viewer Extension — Mermaid Fix Guide

**Extension ID:** `afieddjjmjkmmfiphnckhdckpechliad`  
**Errors:** CSP blocks inline script, Mermaid URL not provided, web_accessible_resources

---

## Prerequisites

1. **Developer mode** must be ON (chrome://extensions)
2. The extension must be **Load unpacked** — you need access to its source files

**If installed from Chrome Web Store:** You cannot edit it. Use "Load unpacked" with a copy of the extension source (if available from GitHub or similar).

---

## Locate the Extension

### If Loaded as Unpacked

The extension folder is wherever you chose when clicking "Load unpacked". Check chrome://extensions — the unpacked extension shows its path.

### If from Chrome Web Store

1. Find the extension on Chrome Web Store and get its source (e.g. GitHub link from the store page)
2. Download/clone the source
3. Go to chrome://extensions → Load unpacked → select the downloaded folder
4. Disable the original Store version to avoid conflicts

---

## Fix 1: Set Mermaid URL (Page-Context Loader)

The error: `[Page Context] Mermaid URL not provided (set window.__MDV_MERMAID_URL or use ?url= in loader src)`

**Find:** The script that injects the Mermaid loader (often `page-context-mermaid.js` or similar).

**Before** the loader runs, inject a script that sets:

```javascript
window.__MDV_MERMAID_URL = 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js';
```

**Or** if the loader is loaded via `<script src="...">`, add `?url=https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js` to the loader URL (if the extension supports it).

**Where to add:** In the content script, before creating/loading the page-context Mermaid script:

```javascript
// Inject config BEFORE loader
const configScript = document.createElement('script');
configScript.textContent = `window.__MDV_MERMAID_URL = 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js';`;
(document.head || document.documentElement).appendChild(configScript);
configScript.remove(); // optional: clean up after read
```

---

## Fix 2: Content Security Policy (CSP)

The error: `Executing inline script violates... Either the 'unsafe-inline' keyword, a hash ('sha256-...'), or a nonce is required`

The extension injects an inline script; CSP blocks it.

### Option A: Add Script Hash to manifest.json

The error provides: `sha256-0xmhMBOV07ecIvjfeXbN4IxjOpNQ7ZW6h5vV78n7GH0=` (verify exact hash from your error).

In `manifest.json`, find `content_security_policy` and add the hash to `script-src`:

```json
"content_security_policy": {
  "extension_pages": "script-src 'self' 'wasm-unsafe-eval' 'inline-speculation-rules' 'sha256-0xmhMBOV07ecIvjfeXbN4IxjOpNQ7ZW6h5vV78n7GH0='; ..."
}
```

*(Adjust the rest of the CSP to match your manifest structure.)*

### Option B: Move Inline Script to External File (Recommended)

Instead of:

```javascript
configScript.textContent = `window.__MDV_MERMAID_URL = '...';`;
```

Create a file `set-mermaid-url.js` in the extension:

```javascript
window.__MDV_MERMAID_URL = 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js';
```

Then inject it:

```javascript
const configScript = document.createElement('script');
configScript.src = chrome.runtime.getURL('set-mermaid-url.js');
configScript.onload = () => configScript.remove();
(document.head || document.documentElement).appendChild(configScript);
```

Add to `manifest.json` under `web_accessible_resources`:

```json
"web_accessible_resources": [{
  "resources": ["set-mermaid-url.js", "libs/mermaid.min.js"],
  "matches": ["<all_urls>", "file:///*"]
}]
```

---

## Fix 3: web_accessible_resources

The error: `Resources must be listed in the web_accessible_resources manifest key`

**In `manifest.json`**, add or extend `web_accessible_resources`:

```json
"web_accessible_resources": [
  {
    "resources": [
      "libs/mermaid.min.js",
      "set-mermaid-url.js"
    ],
    "matches": ["<all_urls>", "file:///*"]
  }
]
```

This allows `file://` pages (your local MD file) to load these scripts.

---

## Summary Checklist

| Step | Action |
|------|--------|
| 1 | Locate extension folder (unpacked) |
| 2 | Set `window.__MDV_MERMAID_URL` before Mermaid loader runs |
| 3 | Move inline config to external `set-mermaid-url.js` (avoids CSP) OR add script hash to CSP |
| 4 | Add Mermaid + config scripts to `web_accessible_resources` |
| 5 | Include `file:///*` in `matches` so local MD files work |
| 6 | Reload extension (chrome://extensions → Reload) |
| 7 | Refresh the Markdown page (F5) |

---

## Alternative: Use VS Code

If patching the extension is not feasible:

1. Open the project in **VS Code**
2. Install **"Markdown Preview Mermaid Support"**
3. Open the MD file and use **Ctrl+Shift+V** for preview

Mermaid diagrams will render without extension changes.
