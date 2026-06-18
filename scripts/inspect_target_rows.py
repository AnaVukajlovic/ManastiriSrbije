import csv
from pathlib import Path

path = Path(__file__).resolve().parent.parent / 'storage' / 'app' / 'import' / 'monasteries.csv'
with path.open('r', encoding='utf-8-sig', newline='') as f:
    reader = csv.DictReader(f, delimiter=';', quotechar='"')
    rows = list(reader)

for slug in ['stevanac', 'zilinci', 'petina', 'ples']:
    matches = [row for row in rows if row['slug'] == slug]
    if not matches:
        print(slug, 'MISSING')
    else:
        print(slug, len(matches), matches[0])
