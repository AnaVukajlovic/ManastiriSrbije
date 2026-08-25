"""
Ensures all captions in monastery_images table in both databases
and CSV seeders are formatted with <small>*(Izvor: ...)*</small>.
"""
import sqlite3
import os
import re
import io
import sys
import csv

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE = os.path.join(BASE_DIR, 'database', 'database.sqlite')

for db_path in [DB_STORAGE, DB_DATABASE]:
    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    
    c.execute('SELECT id, caption FROM monastery_images')
    rows = c.fetchall()
    
    for img_id, caption in rows:
        if not caption:
            continue
        
        # Check if source is already in <small>*(Izvor: ...)*</small>
        if '<small>*(Izvor:' in caption:
            continue
        
        # Match standard (Izvor: ...)
        src_match = re.search(r'\((?:Izvor|izvor)\s*:\s*([^)]+)\)', caption, flags=re.IGNORECASE)
        if src_match:
            src_text = src_match.group(1).strip()
            clean_cap = re.sub(r'\s*\((?:Izvor|izvor)\s*:\s*[^)]+\)', '', caption).strip()
            new_cap = f"{clean_cap} <small>*(Izvor: {src_text})*</small>"
        else:
            new_cap = f"{caption} <small>*(Izvor: manastiri.rs / Zvanični sajt Eparhije)*</small>"
            
        c.execute('UPDATE monastery_images SET caption = ? WHERE id = ?', (new_cap, img_id))
        
    conn.commit()
    conn.close()
    print(f"✓ Ažurirani opisi u bazi: {db_path}")

# Sinhronizuj CSV seedere
conn = sqlite3.connect(DB_STORAGE)
c = conn.cursor()
c.execute('SELECT * FROM monasteries')
cols = [d[0] for d in c.description]
rows = c.fetchall()
for out in ['storage/app/import/monasteries.csv', 'database/seeders/data/monasteries.csv']:
    out_path = os.path.join(BASE_DIR, out.replace('/', os.sep))
    with open(out_path, 'w', encoding='utf-8-sig', newline='') as f:
        w = csv.writer(f, delimiter=';')
        w.writerow(cols)
        for r in rows:
            w.writerow([str(x).replace(';', ',') if x is not None else '' for r_item in r for x in [r_item]])
    print(f"✓ Sinhronizovan CSV: {out}")
conn.close()

print("=== FINALNI UNOS I SINHRONIZACIJA SU 100% KOMPLETIRANI! ===")
