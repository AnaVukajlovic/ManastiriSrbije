import csv
import sys
import time
import requests

UA = "PravoslavniSvetionikCoordsBot/2.0"

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
    eparchy = (row.get("eparchy") or row.get("eparchy_name") or "").strip()

    queries = []

    if city and city.lower() != "nepoznato":
        queries.append(f"Manastir {name}, {city}, Srbija")
        queries.append(f"{name} monastery, {city}, Serbia")

    if region and region.lower() != "nepoznato":
        queries.append(f"Manastir {name}, {region}, Srbija")
        queries.append(f"{name} monastery, {region}, Serbia")

    if eparchy and eparchy.lower() != "nepoznato":
        queries.append(f"Manastir {name}, {eparchy}, Srbija")

    queries.append(f"Manastir {name}, Srbija")
    queries.append(f"{name} monastery, Serbia")
    queries.append(f"{name}, Srbija")

    seen = set()
    final = []
    for q in queries:
        if q not in seen:
            seen.add(q)
            final.append(q)
    return final

def main(inp, outp):
    with open(inp, "r", encoding="utf-8-sig") as f:
        rows = list(csv.DictReader(f, delimiter=";"))

    session = requests.Session()
    changed = 0

    for i, row in enumerate(rows, start=1):
        lat = norm(row.get("lat"))
        lng = norm(row.get("lng"))

        if lat and lng:
            row["lat"] = lat
            row["lng"] = lng
            continue

        found = False
        for q in build_queries(row):
            try:
                coords = geocode(q, session)
                if coords:
                    row["lat"], row["lng"] = coords
                    row["coord_source"] = "nominatim-second-pass"
                    row["coord_url"] = q
                    row["coord_status"] = "ok"
                    changed += 1
                    found = True
                    print(f"[OK] {row.get('name')} -> {coords[0]}, {coords[1]} | {q}")
                    break
            except Exception as e:
                print(f"[ERR] {row.get('name')} | {q} | {e}")

            time.sleep(1.2)

        if not found and not row.get("coord_status"):
            row["coord_status"] = "not_found_second_pass"

        if i % 25 == 0:
            print(f"[{i}/{len(rows)}] additionally filled: {changed}")

    fieldnames = list(rows[0].keys())
    with open(outp, "w", encoding="utf-8-sig", newline="") as f:
        w = csv.DictWriter(f, fieldnames=fieldnames, delimiter=";")
        w.writeheader()
        w.writerows(rows)

    print(f"Done. Additional filled: {changed}/{len(rows)}")

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python fill_coords_second_pass.py input.csv output.csv")
        sys.exit(2)
    main(sys.argv[1], sys.argv[2])