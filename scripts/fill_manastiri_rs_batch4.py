from pathlib import Path
import csv
import shutil

BASE_DIR = Path(__file__).resolve().parent.parent / 'storage' / 'app' / 'import'
ORIG = BASE_DIR / 'monasteries.csv'
BACKUP = BASE_DIR / 'monasteries.csv.bak5'
UPDATED = BASE_DIR / 'monasteries.updated8.csv'

BATCH = {
    'sveta-trojica-kikinda': {
        'ktitor': 'Melanija Nikolić (Lepa Mela)',
        'godina_izgradnje': '1885',
        'lat': '45.8188033',
        'lng': '20.4674669',
    },
    'vodica': {
        'ktitor': 'Nepoznat',
        'lat': '45.7141281',
        'lng': '20.0377082',
    },
    'radosin': {
        'ktitor': 'Despot Stefan Lazarević',
        'godina_izgradnje': '1427',
        'lat': '44.1124233',
        'lng': '21.1996684',
    },
    'braljina': {
        'ktitor': 'Nepoznat',
        'godina_izgradnje': 'pre 1377',
        'lat': '43.6562522',
        'lng': '21.4631728',
    },
    'drenova': {
        'ktitor': 'Nepoznat',
        'godina_izgradnje': 'XVI vek',
        'lat': '43.6307516',
        'lng': '21.1478109',
    },
    'komorane': {
        'ktitor': 'Nepoznat',
        'godina_izgradnje': 'između 1690. i 1740.',
        'lat': '43.6752393',
        'lng': '21.1770621',
    },
    'lepenac': {
        'ktitor': 'Nepoznat',
        'godina_izgradnje': 'XV vek',
        'lat': '43.3575619',
        'lng': '21.067057',
    },
    'naupare': {
        'ktitor': 'Vlastelin iz doba kneza Lazara',
        'godina_izgradnje': 'kraj XIV veka',
        'lat': '43.4779729',
        'lng': '21.3099839',
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
