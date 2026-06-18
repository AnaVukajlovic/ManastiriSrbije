import csv
from pathlib import Path

path = Path(__file__).resolve().parent.parent / 'storage' / 'app' / 'import' / 'monasteries.csv'
missing_kt = []
missing_coord = []
with path.open('r', encoding='utf-8-sig', newline='') as f:
    reader = csv.DictReader(f, delimiter=';', quotechar='"')
    for row in reader:
        ktitor = row['ktitor'].strip().lower()
        if not row['ktitor'].strip() or ktitor in {'nema pouzdana informacija', 'nema informacija', 'нема информација', 'nema'}:
            missing_kt.append(row['slug'])
        if not row['lat'].strip() or not row['lng'].strip():
            missing_coord.append(row['slug'])

print('missing_kt', len(missing_kt))
print('missing_coord', len(missing_coord))
print('sample_missing_kt', missing_kt[:30])
print('sample_missing_coord', missing_coord[:30])
print('missing_both', [slug for slug in missing_kt if slug in missing_coord][:30])
