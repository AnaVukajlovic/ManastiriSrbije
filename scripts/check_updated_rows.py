import csv
from pathlib import Path

path = Path(__file__).resolve().parent.parent / 'storage' / 'app' / 'import' / 'monasteries.csv'
with path.open('r', encoding='utf-8-sig', newline='') as f:
    reader = csv.DictReader(f, delimiter=';', quotechar='"')
    for row in reader:
        if row['slug'] in {'miljkovo', 'namasija', 'izvor', 'reskovica', 'sestroljin'}:
            print(row['slug'], row['ktitor'], row['godina_izgradnje'], row['lat'], row['lng'])
