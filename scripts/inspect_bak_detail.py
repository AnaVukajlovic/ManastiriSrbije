import csv
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

with open('storage/app/import/monasteries.csv.bak', 'r', encoding='utf-8-sig', errors='ignore') as f:
    reader = csv.DictReader(f, delimiter=';')
    rows = list(reader)
    print(f"Total rows in monasteries.csv.bak: {len(rows)}")
    print(f"Columns: {reader.fieldnames}")
    for r in rows[:4]:
        print(f"\nID: {r.get('id')} | Name: {r.get('name')}")
        print("DESCRIPTION:\n" + r.get('description', '')[:500] + "...")
