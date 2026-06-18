import csv
from pathlib import Path
import shutil

BASE_DIR = Path(__file__).resolve().parent.parent / 'storage' / 'app' / 'import'
ORIG = BASE_DIR / 'monasteries.csv'
BACKUP = BASE_DIR / 'monasteries.csv.bak7'
UPDATED = BASE_DIR / 'monasteries.updated13.csv'

BATCH = {
    'vodena-poljana': {
        'ktitor': 'Preduzeće „Srbija šume“ (obezbedilo zemljište i materijal)',
        'godina_izgradnje': '2007',
        'lat': '43.4051975',
        'lng': '19.810092',
    },
    'seljani': {
        'ktitor': 'Nepoznat',
        'godina_izgradnje': 'kraj XIV veka',
        'lat': '43.3820075',
        'lng': '19.5666433',
    },
    'janja': {
        'ktitor': 'Stefan Dragutin',
        'godina_izgradnje': 'XIV vek',
        'lat': '43.5313727',
        'lng': '19.7240521',
    },
    'strmac': {
        'ktitor': 'Nepoznat (narodna zadužbina)',
        'godina_izgradnje': '1313 (postojeća crkva 1680)',
        'lat': '43.2953836',
        'lng': '21.060442',
    },
    'bazovik': {
        'ktitor': 'Nepoznat',
        'godina_izgradnje': 'XIII vek',
        'lat': '43.3484167',
        'lng': '22.4383155',
    },
}


def load_rows(path: Path):
    with path.open('r', encoding='utf-8-sig', newline='') as f:
        reader = csv.DictReader(f, delimiter=';', quotechar='"')
        rows = list(reader)
        if reader.fieldnames is None:
            raise RuntimeError('CSV header missing')
        return reader.fieldnames, rows


def write_rows(path: Path, fieldnames, rows):
    with path.open('w', encoding='utf-8', newline='') as f:
        writer = csv.DictWriter(f, fieldnames=fieldnames, delimiter=';', quotechar='"', quoting=csv.QUOTE_MINIMAL)
        writer.writeheader()
        writer.writerows(rows)


def main():
    shutil.copy2(ORIG, BACKUP)
    fieldnames, rows = load_rows(ORIG)
    normalized = {name.strip().lower(): name for name in fieldnames}
    if 'slug' not in normalized:
        raise RuntimeError('Expected slug column')
    slug_key = normalized['slug']

    changed = 0
    for row in rows:
        slug = row.get(slug_key, '').strip()
        if slug in BATCH:
            row.update(BATCH[slug])
            changed += 1

    write_rows(UPDATED, fieldnames, rows)
    shutil.copy2(UPDATED, ORIG)
    print(f'Applied {changed} updates and wrote {UPDATED}')


if __name__ == '__main__':
    main()
