from pathlib import Path
import csv
import shutil

BASE_DIR = Path(__file__).resolve().parent.parent / 'storage' / 'app' / 'import'
ORIG = BASE_DIR / 'monasteries.csv'
BACKUP = BASE_DIR / 'monasteries.csv.bak4'
UPDATED = BASE_DIR / 'monasteries.updated5.csv'

BATCH = {
    'miljkovo': {
        'ktitor': 'Nepoznat',
        'godina_izgradnje': '1374',
        'lat': '44.1637115',
        'lng': '21.1466141',
    },
    'namasija': {
        'ktitor': 'Nije sačuvano (po predanju vezuje za vreme kneza Lazara)',
        'godina_izgradnje': '15. vek',
        'lat': '43.9388979',
        'lng': '21.5287952',
    },
    'sestroljin': {
        'ktitor': 'Nije sačuvano',
        'godina_izgradnje': '1895–1896',
        'lat': '44.5460806',
        'lng': '21.1993707',
    },
    'izvor': {
        'ktitor': 'Nepoznat (moguće Stefan Musić)',
        'godina_izgradnje': 'pre 1398',
        'lat': '43.8563889',
        'lng': '21.5997222',
    },
    'reskovica': {
        'ktitor': 'Knez Lazar Hrebeljanović',
        'godina_izgradnje': 'XIV vek',
        'lat': '44.3150152',
        'lng': '21.5300586',
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
