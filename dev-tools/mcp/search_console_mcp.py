#!/usr/bin/env python3
"""
Minimal MCP server for Google Search Console operations (stdio transport).

Tools exposed:
- submit_sitemap
- list_sitemaps
- submit_child_sitemaps
"""

from __future__ import annotations

import json
import os
import sys
from pathlib import Path
from typing import Any, Dict, List, Optional

from google.oauth2 import service_account
from googleapiclient.discovery import build


ROOT = Path(__file__).resolve().parents[2]
DEFAULT_CREDS = ROOT / ".cursor" / "secrets" / "search-console-sa.json"
DEFAULT_SITE = "sc-domain:myomr.in"
DEFAULT_CHILD_SITEMAPS = [
    "https://myomr.in/sitemap-pages.xml",
    "https://myomr.in/local-news/sitemap.xml",
    "https://myomr.in/omr-listings/sitemap.xml",
    "https://myomr.in/it-parks/sitemap.xml",
    "https://myomr.in/omr-local-events/sitemap.xml",
    "https://myomr.in/omr-local-job-listings/sitemap.xml",
    "https://myomr.in/omr-hostels-pgs/sitemap.xml",
    "https://myomr.in/omr-coworking-spaces/sitemap.xml",
    "https://myomr.in/omr-buy-sell/sitemap.xml",
    "https://myomr.in/pentahive/sitemap.xml",
    "https://myomr.in/elections-2026/sitemap.xml",
]


def _read_message() -> Optional[Dict[str, Any]]:
    content_length = None
    while True:
        line = sys.stdin.buffer.readline()
        if not line:
            return None
        if line in (b"\r\n", b"\n"):
            break
        header = line.decode("utf-8", errors="replace").strip()
        if header.lower().startswith("content-length:"):
            content_length = int(header.split(":", 1)[1].strip())
    if content_length is None:
        return None
    body = sys.stdin.buffer.read(content_length)
    if not body:
        return None
    return json.loads(body.decode("utf-8"))


def _write_message(payload: Dict[str, Any]) -> None:
    raw = json.dumps(payload, ensure_ascii=True).encode("utf-8")
    sys.stdout.buffer.write(f"Content-Length: {len(raw)}\r\n\r\n".encode("ascii"))
    sys.stdout.buffer.write(raw)
    sys.stdout.buffer.flush()


def _rpc_result(msg_id: Any, result: Any) -> Dict[str, Any]:
    return {"jsonrpc": "2.0", "id": msg_id, "result": result}


def _rpc_error(msg_id: Any, code: int, message: str) -> Dict[str, Any]:
    return {"jsonrpc": "2.0", "id": msg_id, "error": {"code": code, "message": message}}


def _credentials_path() -> Path:
    env_path = os.environ.get("GSC_CREDENTIALS_PATH") or os.environ.get("GOOGLE_APPLICATION_CREDENTIALS")
    return Path(env_path) if env_path else DEFAULT_CREDS


def _site_url() -> str:
    return os.environ.get("GSC_SITE_URL", DEFAULT_SITE)


def _child_sitemaps() -> List[str]:
    raw = os.environ.get("GSC_CHILD_SITEMAPS", "").strip()
    if not raw:
        return list(DEFAULT_CHILD_SITEMAPS)
    return [s.strip() for s in raw.split(",") if s.strip()]


def _gsc_service():
    creds_path = _credentials_path()
    if not creds_path.exists():
        raise FileNotFoundError(f"Credentials file not found: {creds_path}")
    creds = service_account.Credentials.from_service_account_file(
        str(creds_path),
        scopes=["https://www.googleapis.com/auth/webmasters"],
    )
    return build("searchconsole", "v1", credentials=creds, cache_discovery=False)


def _tool_submit_sitemap(arguments: Dict[str, Any]) -> Dict[str, Any]:
    site = arguments.get("siteUrl") or _site_url()
    sitemap = arguments.get("sitemapUrl")
    if not sitemap:
        raise ValueError("sitemapUrl is required")
    svc = _gsc_service()
    svc.sitemaps().submit(siteUrl=site, feedpath=sitemap).execute()
    listed = svc.sitemaps().list(siteUrl=site).execute().get("sitemap", [])
    entry = next((s for s in listed if s.get("path") == sitemap), None)
    return {"submitted": True, "siteUrl": site, "sitemapUrl": sitemap, "entry": entry}


def _tool_list_sitemaps(arguments: Dict[str, Any]) -> Dict[str, Any]:
    site = arguments.get("siteUrl") or _site_url()
    svc = _gsc_service()
    items = svc.sitemaps().list(siteUrl=site).execute().get("sitemap", [])
    return {"siteUrl": site, "count": len(items), "sitemaps": items}


def _tool_submit_child_sitemaps(arguments: Dict[str, Any]) -> Dict[str, Any]:
    site = arguments.get("siteUrl") or _site_url()
    sitemaps = arguments.get("sitemaps") or _child_sitemaps()
    svc = _gsc_service()
    results: List[Dict[str, Any]] = []
    for sm in sitemaps:
        try:
            svc.sitemaps().submit(siteUrl=site, feedpath=sm).execute()
            results.append({"sitemapUrl": sm, "submitted": True})
        except Exception as exc:  # noqa: BLE001
            results.append({"sitemapUrl": sm, "submitted": False, "error": str(exc)})
    listed = svc.sitemaps().list(siteUrl=site).execute().get("sitemap", [])
    idx = {s.get("path"): s for s in listed}
    for row in results:
        row["entry"] = idx.get(row["sitemapUrl"])
    return {"siteUrl": site, "results": results}


TOOLS = {
    "submit_sitemap": _tool_submit_sitemap,
    "list_sitemaps": _tool_list_sitemaps,
    "submit_child_sitemaps": _tool_submit_child_sitemaps,
}


def _tools_descriptor() -> List[Dict[str, Any]]:
    return [
        {
            "name": "submit_sitemap",
            "description": "Submit or re-submit a sitemap URL to Search Console.",
            "inputSchema": {
                "type": "object",
                "properties": {
                    "siteUrl": {"type": "string", "description": "Property ID, e.g. sc-domain:myomr.in"},
                    "sitemapUrl": {"type": "string", "description": "Full sitemap URL to submit"},
                },
                "required": ["sitemapUrl"],
            },
        },
        {
            "name": "list_sitemaps",
            "description": "List sitemap statuses for a Search Console property.",
            "inputSchema": {
                "type": "object",
                "properties": {
                    "siteUrl": {"type": "string", "description": "Property ID, e.g. sc-domain:myomr.in"},
                },
            },
        },
        {
            "name": "submit_child_sitemaps",
            "description": "Submit known child sitemaps, then return their status entries.",
            "inputSchema": {
                "type": "object",
                "properties": {
                    "siteUrl": {"type": "string", "description": "Property ID, e.g. sc-domain:myomr.in"},
                    "sitemaps": {
                        "type": "array",
                        "items": {"type": "string"},
                        "description": "Optional list of sitemap URLs. If omitted, uses configured defaults.",
                    },
                },
            },
        },
    ]


def _format_tool_result(data: Dict[str, Any]) -> Dict[str, Any]:
    return {"content": [{"type": "text", "text": json.dumps(data, ensure_ascii=True)}]}


def main() -> int:
    while True:
        msg = _read_message()
        if msg is None:
            break
        method = msg.get("method")
        msg_id = msg.get("id")
        params = msg.get("params", {})

        try:
            if method == "initialize":
                _write_message(
                    _rpc_result(
                        msg_id,
                        {
                            "protocolVersion": "2024-11-05",
                            "capabilities": {"tools": {"listChanged": False}},
                            "serverInfo": {"name": "search-console-mcp", "version": "1.0.0"},
                        },
                    )
                )
            elif method == "notifications/initialized":
                continue
            elif method == "ping":
                _write_message(_rpc_result(msg_id, {}))
            elif method == "tools/list":
                _write_message(_rpc_result(msg_id, {"tools": _tools_descriptor()}))
            elif method == "tools/call":
                name = params.get("name")
                arguments = params.get("arguments", {}) or {}
                if name not in TOOLS:
                    _write_message(_rpc_error(msg_id, -32601, f"Unknown tool: {name}"))
                    continue
                result = TOOLS[name](arguments)
                _write_message(_rpc_result(msg_id, _format_tool_result(result)))
            else:
                # Ignore unknown notifications, error on unknown requests.
                if msg_id is not None:
                    _write_message(_rpc_error(msg_id, -32601, f"Method not found: {method}"))
        except Exception as exc:  # noqa: BLE001
            if msg_id is not None:
                _write_message(_rpc_error(msg_id, -32000, str(exc)))

    return 0


if __name__ == "__main__":
    raise SystemExit(main())

