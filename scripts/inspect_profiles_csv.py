import csv
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

with open('storage/app/import/monastery_profiles.csv', encoding='utf-8-sig') as f:
    r = csv.DictReader(f)
    print("Columns:", r.fieldnames)
    count = 0
    for row in r:
        count += 1
        if count <= 3:
            print(f"\n--- Row {count}: slug={row['slug']} ---")
            print("intro:", row['intro'][:120] if row.get('intro') else '')
            print("history:", row['history'][:120] if row.get('history') else '')
            print("architecture:", row['architecture'][:120] if row.get('architecture') else '')
    print(f"\nTotal rows in monastery_profiles.csv: {count}")
