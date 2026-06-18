import csv
from pathlib import Path

INPUT = Path(r'd:/projekti/ManastiriSrbije/backend/storage/app/import/monasteries.csv')
OUTPUT = Path(r'd:/projekti/ManastiriSrbije/backend/storage/app/import/monasteries.updated2.csv')
BACKUP = INPUT.with_suffix('.csv.bak2')

with INPUT.open('r', encoding='utf-8', newline='') as f:
    reader = list(csv.reader(f, delimiter=';', quotechar='"'))
    header = reader[0]
    rows = reader[1:]

if 'source' not in header or 'ktitor' not in header or 'godina_izgradnje' not in header or 'napomena_podaci' not in header:
    raise SystemExit('Expected columns missing')

idx_source = header.index('source')
idx_kt = header.index('ktitor')
idx_gd = header.index('godina_izgradnje')
idx_note = header.index('napomena_podaci')

changed = []
for i,row in enumerate(rows, start=2):
    source = row[idx_source].strip().lower() if idx_source < len(row) else ''
    kt = row[idx_kt].strip() if idx_kt < len(row) else ''
    gd = row[idx_gd].strip() if idx_gd < len(row) else ''
    if (not kt or not gd) and source == 'nepoznato':
        row[idx_kt] = 'nema pouzdana informacija'
        row[idx_gd] = 'nema pouzdana informacija'
        note = row[idx_note].strip() if idx_note < len(row) else ''
        extra = 'Nema pouzdanih informacija o ktitoru i godini izgradnje.'
        row[idx_note] = (note + ' ' + extra).strip() if note else extra
        changed.append((i, row[0]))

with OUTPUT.open('w', encoding='utf-8', newline='') as f:
    writer = csv.writer(f, delimiter=';', quotechar='"', quoting=csv.QUOTE_MINIMAL, lineterminator='\n')
    writer.writerow(header)
    writer.writerows(rows)

print('Written', OUTPUT)
print('Changed rows count:', len(changed))
for item in changed[:20]:
    print(item)
if len(changed) > 20:
    print('...')
