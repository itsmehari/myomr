"""
Utility to generate SQL insert statements for the first batch of
Hostels & PGs property owners based on the canonical CSV export.

The script filters listings that look like genuine hostels/PGs
and excludes obvious hotel-resort chains so that Batch 01 focuses
on community accommodations (ladies/gents hostels, PGs, co-living).

Outputs:
    dev-tools/output/hostel-owner-batch01.sql

Run (from project root):
    python dev-tools/scripts/build_hostel_owner_batch.py
"""

from __future__ import annotations

import csv
import re
from dataclasses import dataclass
from pathlib import Path
from typing import Iterable, List, Optional

CSV_SOURCE = Path("First-Set-OMR-Hostel-PG.csv")
OUTPUT_SQL = Path("dev-tools/output/hostel-owner-batch01.sql")
PASSWORD_HASH = "$2y$10$2dE6D9AGWk6gIUOq9nZL4O4sB6Msc4J44g7a5xQK0VX6uZ2qO1XXS"

PRIMARY_KEYWORDS = [
    "hostel",
    " pg",
    "pg ",
    " gents",
    " ladies",
    "men's pg",
    "women's pg",
    "coliving",
    "co-living",
    "service apartment",
    "guest house",
]

EXCLUDE_BRANDS = [
    "novotel",
    "marriott",
    "taj ",
    "vivanta",
    "fairfield",
    "ibis",
    "park plaza",
    "radisson",
    "days hotel",
    "treebo",
    "oyo ",
    "regenta",
    "mermaid",
    "shelter beach",
    "hotel ",
    "resort",
]


@dataclass
class OwnerRecord:
    """Normalized owner record ready for SQL export."""

    name: str
    email: str
    phone: str
    source_id: str

    @property
    def whatsapp(self) -> str:
        return self.phone

    def sanitized_name(self) -> str:
        return self.name.strip()

    def sanitized_email(self) -> str:
        return self.email.strip().lower()

    def sanitized_phone(self) -> str:
        cleaned = re.sub(r"[^\d+]", "", self.phone)
        if cleaned.startswith("91") and len(cleaned) == 12:
            cleaned = f"+{cleaned}"
        elif cleaned.startswith("0") and len(cleaned) > 10:
            cleaned = "+" + cleaned.lstrip("0")
        elif cleaned and not cleaned.startswith("+"):
            if len(cleaned) == 10:
                cleaned = "+91" + cleaned
            else:
                cleaned = "+" + cleaned
        return cleaned or "+910000000000"


def slugify(text: str) -> str:
    text = text.lower()
    text = re.sub(r"[^a-z0-9]+", "-", text)
    text = re.sub(r"-{2,}", "-", text).strip("-")
    return text or "owner"


def first_email(raw: Optional[str]) -> str:
    if not raw or str(raw).strip() == "nan":
        return ""
    return str(raw).split(",")[0].strip()


def primary_phone(raw: Optional[str]) -> str:
    if not raw or str(raw).strip() == "nan":
        return ""
    return str(raw).split(",")[0].strip()


def looks_like_hostel(name: str) -> bool:
    lowered = name.lower()
    matched = any(keyword in lowered for keyword in PRIMARY_KEYWORDS)
    if not matched:
        return False
    return not any(exclusion in lowered for exclusion in EXCLUDE_BRANDS)


def iter_hostel_rows() -> Iterable[dict]:
    with CSV_SOURCE.open(encoding="utf-8") as handle:
        reader = csv.DictReader(handle)
        for row in reader:
            name = (row.get("Name") or "").strip()
            if not name:
                continue
            if looks_like_hostel(name):
                yield row


def build_owner_records() -> List[OwnerRecord]:
    seen_keys = set()
    records: List[OwnerRecord] = []

    for row in iter_hostel_rows():
        name = (row.get("Name") or "").strip()
        email = first_email(row.get("Emails"))
        phone = primary_phone(row.get("Phone"))
        source_id = row.get("ID", "").strip()

        if not email and not phone:
            continue

        key = (email.lower(), phone)
        if key in seen_keys:
            continue

        seen_keys.add(key)

        if not email:
            email = f"pending-{slugify(name)}@hostel-import.local"
        if not phone:
            phone = "+910000000000"

        records.append(OwnerRecord(name=name, email=email, phone=phone, source_id=source_id))

    # Prioritize ladies/pg mentions and cap batch to manageable size
    def priority(record: OwnerRecord) -> tuple[int, str]:
        lowered = record.name.lower()
        score = 0
        if "ladies" in lowered or "women" in lowered:
            score -= 10
        if "gents" in lowered or "mens" in lowered or "men's" in lowered:
            score -= 9
        if "pg" in lowered:
            score -= 8
        if "coliving" in lowered or "co-living" in lowered:
            score -= 7
        if "service apartment" in lowered:
            score -= 6
        return (score, lowered)

    records.sort(key=priority)
    return records[:25]


def ensure_output_dir() -> None:
    OUTPUT_SQL.parent.mkdir(parents=True, exist_ok=True)


def build_sql(records: Iterable[OwnerRecord]) -> str:
    values = []
    for record in records:
        full_name = record.sanitized_name().replace("'", "''")
        email = record.sanitized_email().replace("'", "''")
        phone = record.sanitized_phone().replace("'", "''")
        whatsapp = record.sanitized_phone().replace("'", "''")
        password = PASSWORD_HASH.replace("'", "''")
        comment = f"/* source: {record.source_id} */" if record.source_id else ""

        values.append(
            f"    ('{full_name}', '{email}', '{phone}', '{whatsapp}', '{password}', 'pending', NOW(), NOW(), NULL){comment}"
        )

    header = [
        "-- ============================================================",
        "-- Auto-generated owner seed batch for Hostels & PGs (Batch 01)",
        "-- Source: First-Set-OMR-Hostel-PG.csv",
        "-- Generated by dev-tools/scripts/build_hostel_owner_batch.py",
        "-- ============================================================",
        "INSERT INTO property_owners (full_name, email, phone, whatsapp, password_hash, status, created_at, updated_at, verification_token)",
        "VALUES",
    ]

    body = ",\n".join(values)
    return "\n".join(header + [body + ";"])


def main() -> None:
    if not CSV_SOURCE.exists():
        raise SystemExit(f"CSV source not found: {CSV_SOURCE}")

    ensure_output_dir()
    records = build_owner_records()

    if not records:
        raise SystemExit("No owner records matched the filtering criteria.")

    sql = build_sql(records)
    OUTPUT_SQL.write_text(sql, encoding="utf-8")
    print(f"Wrote {len(records)} owner records to {OUTPUT_SQL}")


if __name__ == "__main__":
    main()
