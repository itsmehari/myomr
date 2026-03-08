import csv
from collections import OrderedDict


def main():
    path = "First-Set-OMR-Hostel-PG.csv"
    owners = OrderedDict()

    with open(path, newline="", encoding="utf-8") as f:
        reader = csv.DictReader(f)
        for row in reader:
            emails = (row.get("Emails") or "").strip()
            primary_email = emails.split(",")[0].strip().lower() if emails else ""
            phone = (row.get("Phone") or "").strip()
            primary_phone = phone.split(",")[0].strip()
            name = (row.get("Name") or "").strip()

            key = (primary_email, primary_phone)
            owners.setdefault(
                key,
                {
                    "name": name,
                    "email": primary_email,
                    "phone": primary_phone,
                    "source_ids": [],
                },
            )["source_ids"].append(row.get("ID") or "")

    print("PrimaryEmail,PrimaryPhone,OwnerName,ListingCount,SampleSourceIDs")
    for (email, phone), data in owners.items():
        ids = [i for i in data["source_ids"] if i]
        ids_preview = ";".join(ids[:3])
        print(
            f"{email or 'N/A'},{phone or 'N/A'},{data['name']},"
            f"{len(data['source_ids'])},{ids_preview}"
        )


if __name__ == "__main__":
    main()


