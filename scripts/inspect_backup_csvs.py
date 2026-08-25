import csv
import io
import sys
import os

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

backup_files = [
    'monasteries.csv.bak',
    'monasteries.csv.bak7',
    'monasteries.csv.bak6',
    'monasteries-stara verzija.csv',
    'monasteries_fixed.csv'
]

for bf in backup_files:
    p = os.path.join('storage', 'app', 'import', bf)
    if os.path.exists(p):
        with open(p, 'r', encoding='utf-8-sig', errors='ignore') as f:
            reader = csv.DictReader(f, delimiter=';')
            rows = list(reader)
            print(f"\n==================== {bf} ({len(rows)} redova) ====================")
            if rows:
                first = rows[0]
                print(f"Naziv: {first.get('name')}")
                desc = first.get('description', '')
                print(f"Description (dužina {len(desc)}): {desc[:250]}...")
