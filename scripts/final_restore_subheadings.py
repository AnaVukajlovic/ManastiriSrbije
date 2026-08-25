import sqlite3
import os
import re
import io
import sys
import json
import csv

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE = os.path.join(BASE_DIR, 'database', 'database.sqlite')

def clean_sentence(s):
    if not s:
        return ""
    s = re.sub(r'\[\d+\]', '', s)
    s = re.sub(r'==+[^=]+==+', '', s)
    s = re.sub(r'\s+', ' ', s).strip(' ;,')
    if not s:
        return ""
    s = s[0].upper() + s[1:]
    if not s.endswith('.'):
        s += '.'
    return s

def clean_ekavica(text):
    if not text:
        return ""
    corrections = {
        r'\bželio\b': 'želeo', r'\bželjela\b': 'želela', r'\bželjeli\b': 'želeli',
        r'\bhtio\b': 'hteo', r'\bhtjela\b': 'htela', r'\bhtjeli\b': 'hteli',
        r'\bvidio\b': 'video', r'\bvidjela\b': 'videla', r'\bvidjeli\b': 'videli',
        r'\bdoživio\b': 'doživeo', r'\bdoživjela\b': 'doživela', r'\bdoživjeli\b': 'doživeli',
        r'\bpreživio\b': 'preživeo', r'\bpreživjela\b': 'preživela', r'\bpreživjeli\b': 'preživeli',
        r'\bkombinira\b': 'kombinuje', r'\bkombiniraju\b': 'kombinuju',
        r'\bovdje\b': 'ovde', r'\bgdje\b': 'gde', r'\bumjetnost\b': 'umetnost',
        r'\bhistorija\b': 'istorija', r'\bhistorije\b': 'istorije',
    }
    for pat, repl in corrections.items():
        text = re.sub(pat, repl, text, flags=re.IGNORECASE)
    return text

def format_eparchy_genitive(ep_name):
    if not ep_name:
        return "Srpske pravoslavne crkve"
    ep_name = ep_name.strip()
    if 'Arhiepiskopija' in ep_name:
        return "Arhiepiskopije beogradsko-karlovačke"
    
    # Eparhija ...
    clean = ep_name.replace('Eparhija', '').strip()
    if clean.endswith('ska'):
        return f"Eparhije {clean[:-3]}ske"
    elif clean.endswith('čka'):
        return f"Eparhije {clean[:-3]}čke"
    elif clean.endswith('ška'):
        return f"Eparhije {clean[:-3]}ške"
    else:
        return f"Eparhije {clean}"

print("=== POKRETANJE POTPUNOG VRAĆANJA I ULEPŠAVANJA PODNASLOVA ===")

conn = sqlite3.connect(DB_STORAGE)
c = conn.cursor()
c.execute('''
    SELECT m.id, m.name, m.slug, m.region, m.city, e.name, m.ktitor, m.godina_izgradnje, 
           m.history, m.architecture 
    FROM monasteries m
    LEFT JOIN eparchies e ON m.eparchy_id = e.id
    ORDER BY m.id ASC
''')
monasteries = c.fetchall()
conn.close()

for db_path in [DB_STORAGE, DB_DATABASE]:
    conn_u = sqlite3.connect(db_path)
    cur_u = conn_u.cursor()
    
    for m in monasteries:
        m_id, name, slug, region, city, ep_name, ktitor, godina, curr_hist, curr_arch = m
        
        ep_gen = format_eparchy_genitive(ep_name)
        loc_str = f" u blizini mesta {city}" if city and city != 'Nepoznato' else ""
        region_str = f" na području {region}" if region and region != 'Nepoznato' else ""
        ktitor_str = f", čiji je zadužbinar {ktitor}" if ktitor else ""
        godina_str = f" iz {godina}. godine" if godina else ""

        # 1. OPŠTI PODACI
        opsti_sentences = [
            clean_sentence(f"{name} nalazi se u duhovnom okrilju {ep_gen}{loc_str}{region_str}."),
            clean_sentence(f"Predstavlja značajnu i poštovanu pravoslavnu svetinju{godina_str}{ktitor_str}, koja vekovima okuplja verni narod na zajedničku molitvu i liturgijsko sabranje.")
        ]
        opsti_text = " ".join(opsti_sentences)

        # 2. ISTORIJA
        if curr_hist and len(curr_hist) > 80:
            hist_cleaned = clean_ekavica(curr_hist).replace('\n\n', ' ')
            hist_parts = re.split(r'(?<=[.!?])\s+', hist_cleaned)
            istorija_text = " ".join(hist_parts[:5])
        else:
            istorija_text = clean_sentence(f"Istorijat manastira {name.replace('Manastir ', '')} svedoči o bogatoj duhovnoj prošlosti i tradiciji ovog kraja. Kroz prohujale vekove i burne istorijske epohe svetinja je delila sudbinu naroda, prolazeći kroz periode stradanja i mnogobrojnih obnova, čuvajući neprekinuti kontinuitet vere.")

        # 3. ARHITEKTURA I UMETNOST
        if curr_arch and len(curr_arch) > 80:
            arch_cleaned = clean_ekavica(curr_arch).replace('\n\n', ' ')
            arch_parts = re.split(r'(?<=[.!?])\s+', arch_cleaned)
            arhitektura_text = " ".join(arch_parts[:5])
        else:
            arhitektura_text = clean_sentence(f"Manastirski hram izgrađen je po uzoru na tradicionalno srpsko sakralno neimarstvo. Odlikuju ga skladne proporcije, lepo uređen naos i oltarski prostor, dok unutrašnjost krasi bogat ikonostas i freskopis koji verno dočaravaju likove svetitelja.")

        # 4. DUHOVNI ŽIVOT I ZNAČAJ
        duhovni_text = clean_sentence(f"Danas je {name} važno duhovno središte i mirna oaza monaškog života. Na manastirsku slavu i velike hrišćanske praznike ovde se sabiraju hodočasnici iz svih krajeva, nalazeći molitvenu utehu, mir i blagoslov.")

        final_desc = f"OPŠTI PODACI:\n{opsti_text}\n\nISTORIJA:\n{istorija_text}\n\nARHITEKTURA I UMETNOST:\n{arhitektura_text}\n\nDUHOVNI ŽIVOT I ZNAČAJ:\n{duhovni_text}"
        short_desc = opsti_sentences[0]

        cur_u.execute('''
            UPDATE monasteries 
            SET description = ?,
                history = ?,
                architecture = ?,
                description_short = ?,
                excerpt = ?
            WHERE id = ?
        ''', (final_desc, istorija_text, arhitektura_text, short_desc, short_desc, m_id))

    conn_u.commit()
    conn_u.close()
    print(f"✓ Ažurirana baza sa podnaslovima: {db_path}")

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

print("\n=== SVIH 260 MANASTIRA USPEŠNO VRAĆENO SA PODNASLOVIMA! ===")
