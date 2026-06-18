import csv
import shutil
from pathlib import Path

INPUT = Path(r"d:\projekti\ManastiriSrbije\backend\storage\app\import\monasteries.csv")
BACKUP = INPUT.with_suffix('.csv.bak')
TEMP = INPUT.with_suffix('.csv.tmp')
OUTPUT = INPUT.with_name('monasteries.updated.csv')

# Updates found from manastiri.rs (initial batch)
UPDATES = {
    'bavaniste': {
        'ktitor': 'nema pouzdana informacija',
        'godina': 'kraj XVI veka (oko 1594)',
        'url': 'https://manastiri.rs/eparhije/banatska/manastir-bavaniste/'
    },
    'gaj': {
        'ktitor': 'nema pouzdana informacija',
        'godina': '1735 (prvi pomen)',
        'url': 'https://manastiri.rs/eparhije/banatska/manastir-gaj/'
    },
    'hajducica': {
        'ktitor': 'Olga S. Jovanović',
        'godina': '1939',
        'url': 'https://manastiri.rs/eparhije/banatska/manastir-hajducica/'
    },
    'mesic': {
        'ktitor': 'Despot Jovan Branković i Episkop Maksim',
        'godina': '1030 (predanje), obnova 1495-1502',
        'url': 'https://manastiri.rs/eparhije/banatska/manastir-mesic/'
    },
    'srediste': {
        'ktitor': 'Arhimandrit Nektarije (Tatarin)',
        'godina': 'obnovljen 1995',
        'url': 'https://manastiri.rs/eparhije/banatska/manastir-srediste/'
    },
    'sveta-trojica-kikinda': {
        'ktitor': 'nema pouzdana informacija',
        'godina': 'nema pouzdana informacija',
        'url': 'https://manastiri.rs/eparhije/banatska/manastir-sveta-trojica-kikinda/'
    },
    'svete-melanije': {
        'ktitor': 'Episkop banatski Georgije Letić',
        'godina': '1935',
        'url': 'https://manastiri.rs/eparhije/banatska/manastir-svete-melanije/'
    },
    'vlajkovac': {
        'ktitor': 'nema pouzdana informacija',
        'godina': '1872',
        'url': 'https://manastiri.rs/eparhije/banatska/manastir-vlajkovac/'
    },
    'vojlovica': {
        'ktitor': 'Despot Stefan Lazarević',
        'godina': '1383',
        'url': 'https://manastiri.rs/eparhije/banatska/manastir-vojlovica/'
    },
    'bodjani': {
        'ktitor': 'Bogdan (trgovac)',
        'godina': '1478',
        'url': 'https://manastiri.rs/eparhije/backa/manastir-bodjani/'
    }
}

if not INPUT.exists():
    print(f"INPUT file not found: {INPUT}")
    raise SystemExit(1)

# Backup
shutil.copy2(INPUT, BACKUP)
print(f"Backup written to {BACKUP}")

with INPUT.open('r', encoding='utf-8', newline='') as fh:
    reader = list(csv.reader(fh, delimiter=';', quotechar='"'))

if not reader:
    print('CSV is empty')
    raise SystemExit(1)

header = reader[0]
# Normalize header (strip BOM/whitespace) and find indices
header_norm = [h.strip().lstrip('\ufeff') for h in header]
try:
    idx_slug = header_norm.index('slug')
    idx_ktitor = header_norm.index('ktitor')
    idx_godina = header_norm.index('godina_izgradnje')
    idx_nap = header_norm.index('napomena_podaci')
except ValueError as e:
    print('Expected column missing in header (after normalization):', e)
    print('Header columns:', header_norm)
    raise

changed = []
for i, row in enumerate(reader[1:], start=1):
    if len(row) < len(header):
        # pad row
        row += [''] * (len(header) - len(row))
    slug = row[idx_slug].strip()
    if slug in UPDATES:
        u = UPDATES[slug]
        old_kt = row[idx_ktitor]
        old_gd = row[idx_godina]
        row[idx_ktitor] = u['ktitor']
        row[idx_godina] = u['godina']
        note_extra = f"Dopunjeno iz manastiri.rs ({u['url']})."
        if row[idx_nap].strip():
            row[idx_nap] = row[idx_nap].rstrip() + ' ' + note_extra
        else:
            row[idx_nap] = note_extra
        reader[i] = row
        changed.append(slug)

# Write updates to a new output file to avoid locking/permission issues
try:
    with OUTPUT.open('w', encoding='utf-8', newline='') as fh:
        writer = csv.writer(fh, delimiter=';', quotechar='"', quoting=csv.QUOTE_MINIMAL, lineterminator='\n')
        for row in reader:
            writer.writerow(row)
    print('Updated CSV written to', OUTPUT)
    print('Original backed up at', BACKUP)
    print('Changed slugs:', ', '.join(changed))
except Exception as e:
    print('Failed to write updated output file:', e)
    raise
