from pathlib import Path
import csv
import shutil

BASE_DIR = Path(__file__).resolve().parent.parent / 'storage' / 'app' / 'import'
ORIG = BASE_DIR / 'monasteries.csv'
BACKUP = BASE_DIR / 'monasteries.csv.bak3'
UPDATED = BASE_DIR / 'monasteries.updated4.csv'

BATCH = {
    'senjak': {'ktitor': 'Nije sačuvano', 'godina_izgradnje': '1935'},
    'trojerucica': {'ktitor': 'Novogradnja 21. veka', 'godina_izgradnje': '2015'},
    'zemun': {'ktitor': 'Kralj Milutin', 'godina_izgradnje': '1312/1313'},
    'dobres': {'ktitor': 'Nepoznat', 'godina_izgradnje': 'pre 1516'},
    'bradaca': {'ktitor': 'Nepoznat', 'godina_izgradnje': 'XIV vek'},
}


def normalize_header(name: str) -> str:
    return name.strip().lower()


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
    normalized = {normalize_header(name): name for name in fieldnames}
    if 'slug' not in normalized or 'ktitor' not in normalized or 'godina_izgradnje' not in normalized:
        raise RuntimeError('Expected slug, ktitor, godina_izgradnje columns')

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
