#!/usr/bin/env python3
"""
Notify Google to crawl/index specific URLs via the Google Indexing API (urlNotifications.publish).

This is the closest supported programmatic equivalent to Search Console "Request indexing"
for eligible pages. Google does NOT expose the URL Inspection "Request indexing" button as
a public API — the URL Inspection API only *reads* index status (index.inspect).

Eligibility (Google): pages with JobPosting or BroadcastEvent (in VideoObject) structured data.
MyOMR job detail pages output JobPosting JSON-LD (see omr-local-job-listings/includes/seo-helper.php).

Setup (one-time):
  1. Google Cloud: enable "Indexing API" for the same project as your service account.
  2. Service account JSON key with scope https://www.googleapis.com/auth/indexing
  3. In Search Console, add the service account email as a user with "Owner" on the property
     (same requirement as many GSC API workflows).

Env:
  GOOGLE_APPLICATION_CREDENTIALS or INDEXING_CREDENTIALS_PATH — path to service account JSON
  (defaults to .cursor/secrets/search-console-sa.json under repo root if present).

Usage:
  # All approved jobs (canonical URLs from DB via export_approved_job_urls.php)
  python dev-tools/jobs/publish_indexing_api_urls.py --all-approved

  python dev-tools/jobs/publish_indexing_api_urls.py --all-approved --dry-run
  python dev-tools/jobs/publish_indexing_api_urls.py --all-approved --limit 200 --sleep 0.2

  python dev-tools/jobs/publish_indexing_api_urls.py \\
    "https://myomr.in/omr-local-job-listings/job/19/creative-associate-internship"

  python dev-tools/jobs/publish_indexing_api_urls.py --file urls.txt
  php dev-tools/jobs/export_approved_job_urls.php | python dev-tools/jobs/publish_indexing_api_urls.py --stdin
"""

from __future__ import annotations

import argparse
import json
import os
import warnings
import subprocess
import sys
import time
from pathlib import Path

warnings.filterwarnings("ignore", category=FutureWarning, module="google.api_core")

try:
    from google.oauth2 import service_account
    from googleapiclient.discovery import build
except ImportError as e:
    print("Missing dependency: pip install google-api-python-client google-auth", file=sys.stderr)
    raise SystemExit(1) from e

ROOT = Path(__file__).resolve().parents[2]
DEFAULT_CREDS = ROOT / ".cursor" / "secrets" / "search-console-sa.json"
SCOPES = ["https://www.googleapis.com/auth/indexing"]
EXPORT_PHP = ROOT / "dev-tools" / "jobs" / "export_approved_job_urls.php"
DEFAULT_QUOTA_HINT = 200


def _credentials_path() -> Path:
    p = os.environ.get("INDEXING_CREDENTIALS_PATH") or os.environ.get("GOOGLE_APPLICATION_CREDENTIALS")
    if p:
        return Path(p)
    return DEFAULT_CREDS


def _build_service():
    creds_path = _credentials_path()
    if not creds_path.exists():
        raise FileNotFoundError(f"Credentials not found: {creds_path}")
    creds = service_account.Credentials.from_service_account_file(
        str(creds_path),
        scopes=SCOPES,
    )
    return build("indexing", "v3", credentials=creds, cache_discovery=False)


def publish_url(service, url: str, dry_run: bool) -> dict:
    body = {"url": url, "type": "URL_UPDATED"}
    if dry_run:
        return {"dry_run": True, "url": url, "body": body}
    return service.urlNotifications().publish(body=body).execute()


def load_urls_from_export_php() -> list[str]:
    if not EXPORT_PHP.is_file():
        raise FileNotFoundError(f"Missing {EXPORT_PHP}")
    proc = subprocess.run(
        ["php", str(EXPORT_PHP)],
        cwd=str(ROOT),
        capture_output=True,
        text=True,
    )
    if proc.returncode != 0:
        raise RuntimeError(proc.stderr or "export_approved_job_urls.php failed")
    return [line.strip() for line in proc.stdout.splitlines() if line.strip()]


def load_urls_from_stdin() -> list[str]:
    return [line.strip() for line in sys.stdin if line.strip() and not line.strip().startswith("#")]


def load_urls_from_file(path: Path) -> list[str]:
    text = path.read_text(encoding="utf-8")
    return [line.strip() for line in text.splitlines() if line.strip() and not line.strip().startswith("#")]


def run_publish(
    urls: list[str],
    *,
    dry_run: bool,
    sleep_s: float,
) -> tuple[list[dict], int]:
    for u in urls:
        if not u.startswith("https://"):
            raise ValueError(f"URL must be absolute https: {u!r}")

    service = None if dry_run else _build_service()

    results: list[dict] = []
    for i, u in enumerate(urls):
        try:
            out = publish_url(service, u, dry_run)
            results.append({"url": u, "ok": True, "response": out})
            print(json.dumps({"url": u, "ok": True, "response": out}, ensure_ascii=True))
        except Exception as exc:  # noqa: BLE001
            results.append({"url": u, "ok": False, "error": str(exc)})
            print(json.dumps({"url": u, "ok": False, "error": str(exc)}, ensure_ascii=True), file=sys.stderr)
        if not dry_run and sleep_s > 0 and i < len(urls) - 1:
            time.sleep(sleep_s)

    failed = sum(1 for r in results if not r.get("ok"))
    return results, failed


def main() -> int:
    parser = argparse.ArgumentParser(description="Indexing API: URL_UPDATED notifications")
    parser.add_argument("urls", nargs="*", help="Absolute https URLs to notify")
    parser.add_argument(
        "--file",
        "-f",
        type=Path,
        help="File with one URL per line (# comments and blank lines ignored)",
    )
    parser.add_argument(
        "--stdin",
        action="store_true",
        help="Read URLs from stdin (one per line)",
    )
    parser.add_argument(
        "--all-approved",
        action="store_true",
        help="Load all approved job URLs via export_approved_job_urls.php (canonical getJobDetailUrl)",
    )
    parser.add_argument(
        "--limit",
        type=int,
        default=0,
        help="Max URLs to send (0 = no cap). Google Indexing API default quota is often ~200/day.",
    )
    parser.add_argument(
        "--sleep",
        type=float,
        default=0.15,
        help="Seconds to wait between API calls (ignored for --dry-run)",
    )
    parser.add_argument("--dry-run", action="store_true", help="Print actions without calling API")
    args = parser.parse_args()

    urls: list[str] = []
    if args.all_approved:
        urls = load_urls_from_export_php()
    elif args.stdin:
        urls = load_urls_from_stdin()
    elif args.file:
        urls = load_urls_from_file(args.file)
    else:
        urls = list(args.urls)

    if not urls:
        parser.error("Provide URLs, --file, --stdin, or --all-approved")

    if args.limit and args.limit > 0:
        urls = urls[: args.limit]

    if len(urls) > DEFAULT_QUOTA_HINT:
        print(
            f"WARNING: {len(urls)} URLs — Google Indexing API daily quota is often ~{DEFAULT_QUOTA_HINT}. "
            "Use --limit to batch across days.",
            file=sys.stderr,
        )

    try:
        _results, failed = run_publish(urls, dry_run=args.dry_run, sleep_s=args.sleep if not args.dry_run else 0)
    except (ValueError, FileNotFoundError, RuntimeError) as e:
        print(str(e), file=sys.stderr)
        return 1

    return 1 if failed else 0


if __name__ == "__main__":
    raise SystemExit(main())
