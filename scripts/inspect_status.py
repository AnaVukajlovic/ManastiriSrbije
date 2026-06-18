import csv
from pathlib import Path
p = Path(__file__).resolve().parent.parent / 'storage' / 'app' / 'import' / 'monasteries.csv'
with p.open('r', encoding='utf-8-sig', newline='') as f:
    reader = csv.DictReader(f, delimiter=';', quotechar='"')
    rows = list(reader)
print('missing_kt', sum(1 for row in rows if not row.get('ktitor') or not row.get('godina_izgradnje')))
print('missing_coord', sum(1 for row in rows if not row.get('lat') or not row.get('lng')))
print('total_rows', len(rows))
print('sample_missing_coords', [row['slug'] for row in rows if not row.get('lat') or not row.get('lng')][:20])
