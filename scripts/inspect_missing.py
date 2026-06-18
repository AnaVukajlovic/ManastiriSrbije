import csv
from pathlib import Path

INPUT = Path(r'd:/projekti/ManastiriSrbije/backend/storage/app/import/monasteries.csv')
with INPUT.open('r', encoding='utf-8', newline='') as f:
    reader = csv.DictReader(f, delimiter=';', quotechar='"')
    missing = []
    for i, row in enumerate(reader, start=2):
        if not row.get('ktitor') or not row.get('godina_izgradnje'):
            missing.append({
                'line': i,
                'slug': row.get('slug',''),
                'wikipedia': row.get('wikipedia_url',''),
                'source': row.get('source',''),
                'ktitor': row.get('ktitor',''),
                'godina': row.get('godina_izgradnje',''),
            })
            if len(missing) >= 50:
                break

print('First 50 missing rows:')
for row in missing:
    print(row['line'], row['slug'], 'wiki='+row['wikipedia'], 'src='+row['source'], 'kt='+repr(row['ktitor']), 'gd='+repr(row['godina']))

with INPUT.open('r', encoding='utf-8', newline='') as f:
    reader = csv.DictReader(f, delimiter=';', quotechar='"')
    count = sum(1 for row in reader if not row.get('ktitor') or not row.get('godina_izgradnje'))
print('Total missing count:', count)

# Unique sources for missing rows
with INPUT.open('r', encoding='utf-8', newline='') as f:
    reader = csv.DictReader(f, delimiter=';', quotechar='"')
    sources = {}
    for row in reader:
        if not row.get('ktitor') or not row.get('godina_izgradnje'):
            key = row.get('wikipedia_url') or row.get('source') or 'none'
            sources[key] = sources.get(key, 0) + 1
print('\nSources for missing rows:')
for k,v in sorted(sources.items(), key=lambda x: (-x[1], x[0])):
    print(v, k)
