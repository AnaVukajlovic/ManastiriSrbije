import csv
import sys
import time
import requests

UA = "PravoslavniSvetionikCoordsBot/3.0"

def norm(x):
    if x is None:
        return None
    x = str(x).strip()
    if x == "" or x.lower() == "nan":
        return None
    return x.replace(",", ".")

def geocode(query, session):
    url = "https://nominatim.openstreetmap.org/search"
    params = {"q": query, "format": "json", "limit": 1}
    r = session.get(url, params=params, headers={"User-Agent": UA}, timeout=30)
    r.raise_for_status()
    data = r.json()
    if not data:
        return None
    return data[0]["lat"], data[0]["lon"]

def build_queries(row):
    name = (row.get("name") or "").strip()
    city = (row.get("city") or "").strip()
    region = (row.get("region") or "").strip()

    queries = [
        f"Manastir {name}, Srbija",
        f"{name} monastery Serbia",
        f"{name}, Serbia",
    ]

    if city and city.lower() != "nepoznato":
        queries.append(f"Manastir {name}, {city}, Srbija")

    if region and region.lower() != "nepoznato":
        queries.append(f"{name}, {region}, Serbia")

    return queries

def main(inp, outp):
    with open(inp, "r", encoding="utf-8-sig") as f:
        rows = list(csv.DictReader(f, delimiter=";"))

    session = requests.Session()
    changed = 0

    for i, row in enumerate(rows, start=1):

        lat = norm(row.get("lat"))
        lng = norm(row.get("lng"))

        if lat and lng:
            continue

        for q in build_queries(row):
            try:
                coords = geocode(q, session)
                if coords:
                    row["lat"], row["lng"] = coords
                    changed += 1
                    print(f"[OK] {row.get('name')} -> {coords}")
                    break
            except:
                pass

            time.sleep(1.2)

        if i % 25 == 0:
            print(f"[{i}/{len(rows)}] added: {changed}")

    with open(outp, "w", encoding="utf-8-sig", newline="") as f:
        w = csv.DictWriter(f, fieldnames=rows[0].keys(), delimiter=";")
        w.writeheader()
        w.writerows(rows)

    print(f"Done. Added: {changed}/{len(rows)}")

if __name__ == "__main__":
    main(sys.argv[1], sys.argv[2])