import csv
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

with open('storage/app/import/monasteries.csv.bak', 'r', encoding='utf-8-sig', errors='ignore') as f:
    reader = csv.DictReader(f, delimiter=';')
    for r in reader:
        if r.get('name') in ['Manastir Bavanište', 'Manastir Studenica', 'Manastir Žiča']:
            print(f"\n==================== {r.get('name')} ====================")
            print(r.get('description'))
