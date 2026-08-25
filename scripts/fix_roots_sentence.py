import os
import sys
import io
import re
import sqlite3
import csv

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE_PATH = os.path.join(BASE_DIR, 'database', 'database.sqlite')
CSV_IMPORT_PATH = os.path.join(BASE_DIR, 'storage', 'app', 'import', 'monasteries.csv')
CSV_SEEDER_PATH = os.path.join(BASE_DIR, 'database', 'seeders', 'data', 'monasteries.csv')

def clean_monastery_desc(desc, name, region, city):
    target = "Koreni osnivanja manastirskog kompleksa sežu u period srednjeg veka, o čemu svedoče sačuvana predanja i zapisi."
    if target in desc:
        loc_str = f"na području {region}" if (region and region.lower() != 'nepoznato') else "u ovom delu Srbije"
        repl = f"Manastirsko zdanje poseduje dugu istorijsku tradiciju i duboku ukorenjenost u duhovni život pravoslavnog naroda {loc_str}."
        desc = desc.replace(target, repl)
    return desc

# 1. Update CSVs
csv_rows = []
with open(CSV_IMPORT_PATH, 'r', encoding='utf-8-sig') as f:
    reader = csv.DictReader(f, delimiter=';')
    fieldnames = reader.fieldnames
    for r in reader:
        csv_rows.append(r)

for r in csv_rows:
    r['description'] = clean_monastery_desc(r.get('description', ''), r['name'], r.get('region', ''), r.get('city', ''))

for target_csv in [CSV_IMPORT_PATH, CSV_SEEDER_PATH]:
    with open(target_csv, 'w', encoding='utf-8-sig', newline='') as f:
        writer = csv.DictWriter(f, fieldnames=fieldnames, delimiter=';')
        writer.writeheader()
        for r in csv_rows:
            writer.writerow(r)

# 2. Update DBs
for db_p in [DB_STORAGE_PATH, DB_DATABASE_PATH]:
    if not os.path.exists(db_p):
        continue
    conn = sqlite3.connect(db_p)
    cur = conn.cursor()
    for r in csv_rows:
        cur.execute("UPDATE monasteries SET description = ? WHERE slug = ?", (r['description'], r['slug']))
    conn.commit()
    conn.close()

print("✓ Uspešno uklonjena sva ponavljanja u CSV i bazama podataka.")
