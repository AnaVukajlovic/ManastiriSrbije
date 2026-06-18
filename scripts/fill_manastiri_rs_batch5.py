from pathlib import Path
import csv
import shutil

BASE_DIR = Path(__file__).resolve().parent.parent / 'storage' / 'app' / 'import'
ORIG = BASE_DIR / 'monasteries.csv'
BACKUP = BASE_DIR / 'monasteries.csv.bak6'
UPDATED = BASE_DIR / 'monasteries.updated12.csv'

BATCH = {
    'stevanac': {
        'ktitor': 'Stefan (po predanju)',
        'godina_izgradnje': 'XIV vek',
        'lat': '43.6496711',
        'lng': '21.4216362',
    },
    'zilinci': {
        'ktitor': 'Knez Lazar Hrebeljanović',
        'godina_izgradnje': 'XIV vek',
        'lat': '43.390553',
        'lng': '21.1335523',
    },
    'petina': {
        'ktitor': 'Nepoznat',
        'godina_izgradnje': '1934-1935',
        'lat': '43.4893042',
        'lng': '21.4195203',
    },
    'ples': {
        'ktitor': 'Nemanjići',
        'godina_izgradnje': 'početak XV veka',
        'lat': '43.4746682',
        'lng': '20.9245992',
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
