import sqlite3
import re
import io
import sys
import os
import csv

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE = os.path.join(BASE_DIR, 'database', 'database.sqlite')

# Rečnik za zamenu glagola i reči u čistu srpsku ekavicu
CORRECTIONS = {
    r'\bželio\b': 'želeo',
    r'\bželjela\b': 'želela',
    r'\bželjeli\b': 'želeli',
    r'\bhtio\b': 'hteo',
    r'\bhtjela\b': 'htela',
    r'\bhtjeli\b': 'hteli',
    r'\bvidio\b': 'video',
    r'\bvidjela\b': 'videla',
    r'\bvidjeli\b': 'videli',
    r'\bdoživio\b': 'doživeo',
    r'\bdoživjela\b': 'doživela',
    r'\bdoživjeli\b': 'doživeli',
    r'\bpreživio\b': 'preživeo',
    r'\bpreživjela\b': 'preživela',
    r'\bpreživjeli\b': 'preživeli',
    r'\bumro\b': 'preminuo',
    r'\bumrla\b': 'preminula',
    r'\bumrli\b': 'preminuli',
    r'\bkombinira\b': 'kombinuje',
    r'\bkombiniraju\b': 'kombinuju',
    r'\breflektira\b': 'odražava',
    r'\breflektiraju\b': 'odražavaju',
    r'\bfascinira\b': 'oduševljava',
    r'\bovdje\b': 'ovde',
    r'\bgdje\b': 'gde',
    r'\bnegdje\b': 'negde',
    r'\bsvugdje\b': 'svugde',
    r'\bonđe\b': 'onde',
    r'\bonamo\b': 'tamo',
    r'\btko\b': 'ko',
    r'\bnetko\b': 'neko',
    r'\bsvatko\b': 'svako',
    r'\bnitko\b': 'niko',
    r'\bumjetnik\b': 'umetnik',
    r'\bumjetnika\b': 'umetnika',
    r'\bumjetnici\b': 'umetnici',
    r'\bumjetnost\b': 'umetnost',
    r'\bumjetnosti\b': 'umetnosti',
    r'\bumjetnička\b': 'umetnička',
    r'\bumjetničko\b': 'umetničko',
    r'\bumjetnički\b': 'umetnički',
    r'\bumjetničke\b': 'umetničke',
    r'\bhistorija\b': 'istorija',
    r'\bhistorije\b': 'istorije',
    r'\bhistoriji\b': 'istoriji',
    r'\bhistorijski\b': 'istorijski',
    r'\bhistorijska\b': 'istorijska',
    r'\bhistorijsko\b': 'istorijsko',
    r'\bhistorijske\b': 'istorijske',
}

def clean_blob(text):
    if not text:
        return ""
    for pat, repl in CORRECTIONS.items():
        text = re.sub(pat, repl, text, flags=re.IGNORECASE)
    return text

print("=== POKRETANJE NORMALIZACIJE ČISTE EKAVICE I STILA ===")

for db_path in [DB_STORAGE, DB_DATABASE]:
    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    c.execute('SELECT id, name, description, history, architecture, excerpt, description_short FROM monasteries')
    rows = c.fetchall()
    
    for r in rows:
        m_id, name, desc, hist, arch, exc, short_d = r
        
        new_desc = clean_blob(desc)
        new_hist = clean_blob(hist)
        new_arch = clean_blob(arch)
        new_exc = clean_blob(exc)
        new_short = clean_blob(short_d)
        
        c.execute('''
            UPDATE monasteries 
            SET description = ?,
                history = ?,
                architecture = ?,
                excerpt = ?,
                description_short = ?
            WHERE id = ?
        ''', (new_desc, new_hist, new_arch, new_exc, new_short, m_id))
        
    conn.commit()
    conn.close()
    print(f"✓ Normalizovana baza: {db_path}")

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

print("=== SVE ZAVRŠENO 100%! ===")
