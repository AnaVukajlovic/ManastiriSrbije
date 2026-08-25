import csv
import io
import sys
import os

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

backup_files = [
    'monasteries.updated13.csv',
    'monasteries.updated12.csv',
    'monasteries.updated8.csv',
    'monasteries.updated5.csv',
    'monasteries.updated4.csv',
    'monasteries.updated3.csv',
    'monasteries.updated2.csv',
    'monasteries.updated.csv',
    'monasteries132146546.csv',
    'monasteries1286.csv',
    'monasteries22222.csv',
]

for bf in backup_files:
    p = os.path.join('storage', 'app', 'import', bf)
    if os.path.exists(p):
        with open(p, 'r', encoding='utf-8-sig', errors='ignore') as f:
            reader = csv.DictReader(f, delimiter=';')
            rows = list(reader)
            if rows:
                print(f"\n==================== {bf} ({len(rows)} redova) ====================")
                for r in rows:
                    if r.get('name') == 'Manastir Studenica':
                        print(f"Studenica desc:\n{r.get('description')[:350]}...\n")
                        break
