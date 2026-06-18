import csv
from pathlib import Path

INPUT = Path(r'd:/projekti/ManastiriSrbije/backend/storage/app/import/monasteries.csv')
OUTPUT = Path(r'd:/projekti/ManastiriSrbije/backend/storage/app/import/monasteries.updated3.csv')

UPDATES = {
    'kac': {
        'ktitor': 'Episkop Irinej Bulović',
        'godina': '2010',
        'url': 'https://manastiri.rs/eparhije/backa/manastir-kac/'
    },
    'kovilj': {
        'ktitor': 'Srpska monaška zajednica (po predanju povezan sa Sv. Savom)',
        'godina': '1741–1749',
        'url': 'https://manastiri.rs/eparhije/backa/manastir-kovilj/'
    },
    'sombor': {
        'ktitor': 'Stevan Ekem Konjović',
        'godina': '1928',
        'url': 'https://manastiri.rs/eparhije/backa/manastir-sombor/'
    },
    'vodica': {
        'ktitor': 'nema pouzdana informacija',
        'godina': '1370',
        'url': 'https://manastiri.rs/eparhije/backa/manastir-vodica/'
    },
    'mislodjin': {
        'ktitor': 'Kralj Stefan Dragutin Nemanjić',
        'godina': '1280',
        'url': 'https://manastiri.rs/eparhije/beogradska/manastir-mislodjin/'
    }
}

with INPUT.open('r', encoding='utf-8', newline='') as f:
    reader = list(csv.reader(f, delimiter=';', quotechar='"'))
    header = [h.strip().lstrip('\ufeff') for h in reader[0]]
    rows = reader[1:]

if 'slug' not in header or 'ktitor' not in header or 'godina_izgradnje' not in header or 'napomena_podaci' not in header:
    print('Header:', header)
    raise SystemExit('Expected columns missing')

idx_slug = header.index('slug')
idx_kt = header.index('ktitor')
idx_gd = header.index('godina_izgradnje')
idx_note = header.index('napomena_podaci')

changed = []
for i, row in enumerate(rows, start=2):
    slug = row[idx_slug].strip()
    if slug in UPDATES:
        info = UPDATES[slug]
        row[idx_kt] = info['ktitor']
        row[idx_gd] = info['godina']
        note = row[idx_note].strip() if row[idx_note] else ''
        extra = f"Dopunjeno iz manastiri.rs ({info['url']})."
        row[idx_note] = (note + ' ' + extra).strip() if note else extra
        changed.append(slug)

with OUTPUT.open('w', encoding='utf-8', newline='') as f:
    writer = csv.writer(f, delimiter=';', quotechar='"', quoting=csv.QUOTE_MINIMAL, lineterminator='\n')
    writer.writerow(header)
    writer.writerows(rows)

print('Written', OUTPUT)
print('Changed slugs:', changed)
