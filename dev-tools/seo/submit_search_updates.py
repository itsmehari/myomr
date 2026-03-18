#!/usr/bin/env python3
"""
Submit sitemap + URL update notifications for MyOMR.

Usage:
  python dev-tools/seo/submit_search_updates.py
"""

from __future__ import annotations

import json
from pathlib import Path

from google.oauth2 import service_account
from googleapiclient.discovery import build
from google.auth.transport.requests import AuthorizedSession


ROOT = Path(__file__).resolve().parents[2]
SA_PATH = ROOT / ".cursor" / "secrets" / "search-console-sa.json"

SITEMAP_URL = "https://myomr.in/omr-local-job-listings/sitemap.xml"
URLS_TO_NOTIFY = [
    "https://myomr.in/office-jobs-omr-chennai.php",
    "https://myomr.in/manpower-jobs-omr-chennai.php",
    "https://myomr.in/omr-local-job-listings/",
]

# Try URL-prefix and Domain properties.
SITE_PROPERTIES = [
    "https://myomr.in/",
    "sc-domain:myomr.in",
]


def submit_sitemaps(creds) -> list[str]:
    outputs: list[str] = []
    service = build("searchconsole", "v1", credentials=creds, cache_discovery=False)
    for site in SITE_PROPERTIES:
        try:
            service.sitemaps().submit(siteUrl=site, feedpath=SITEMAP_URL).execute()
            outputs.append(f"[OK] Sitemap submitted for {site}")
        except Exception as exc:  # noqa: BLE001
            outputs.append(f"[FAIL] Sitemap submit for {site}: {exc}")
    return outputs


def notify_indexing_api(creds) -> list[str]:
    outputs: list[str] = []
    session = AuthorizedSession(creds)
    endpoint = "https://indexing.googleapis.com/v3/urlNotifications:publish"
    for url in URLS_TO_NOTIFY:
        try:
            resp = session.post(endpoint, json={"url": url, "type": "URL_UPDATED"}, timeout=30)
            if 200 <= resp.status_code < 300:
                outputs.append(f"[OK] Indexing notification sent: {url}")
            else:
                outputs.append(f"[FAIL] Indexing notification for {url}: {resp.status_code} {resp.text[:220]}")
        except Exception as exc:  # noqa: BLE001
            outputs.append(f"[FAIL] Indexing notification for {url}: {exc}")
    return outputs


def main() -> int:
    if not SA_PATH.exists():
        print(f"[ERROR] Service account file not found: {SA_PATH}")
        return 1

    search_creds = service_account.Credentials.from_service_account_file(
        str(SA_PATH),
        scopes=["https://www.googleapis.com/auth/webmasters"],
    )
    indexing_creds = service_account.Credentials.from_service_account_file(
        str(SA_PATH),
        scopes=["https://www.googleapis.com/auth/indexing"],
    )

    print("== Search Console sitemap submit ==")
    for line in submit_sitemaps(search_creds):
        print(line)

    print("\n== Indexing API URL notifications ==")
    for line in notify_indexing_api(indexing_creds):
        print(line)

    return 0


if __name__ == "__main__":
    raise SystemExit(main())

