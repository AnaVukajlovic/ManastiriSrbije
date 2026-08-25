"""
Script to restore structured texts with the exact 4 subheadings:
- OPŠTI PODACI: ...
- ISTORIJA: ...
- ARHITEKTURA I UMETNOST: ...
- DUHOVNI ŽIVOT I ZNAČAJ: ...

Preserves all verified duplicate-free gallery images and source attributions.
Updates storage/database.sqlite, database/database.sqlite and syncs CSV seeders.
"""
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
CACHE_FILE = os.path.join(BASE_DIR, 'storage', 'app', 'manastiri_rs_scraped_cache.json')

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

def main():
    print("=== VRAĆANJE STRUKTURIRANIH TEKSTOVA SA PODNASLOVIMA ===\n")
    
    # Load cache if available
    scraped_cache = {}
    if os.path.exists(CACHE_FILE):
        try:
            with open(CACHE_FILE, 'r', encoding='utf-8') as f:
                scraped_cache = json.load(f)
            print(f"Učitan keš sa {len(scraped_cache)} stranica sa manastiri.rs.")
        except Exception:
            pass

    conn = sqlite3.connect(DB_STORAGE)
    c = conn.cursor()
    c.execute('''SELECT id, name, slug, region, city, eparchy, ktitor, godina_izgradnje, source_url, 
                        history, architecture, description 
                 FROM monasteries ORDER BY id ASC''')
    monasteries = c.fetchall()
    conn.close()

    updated_count = 0

    for m in monasteries:
        m_id, name, slug, region, city, eparchy, ktitor, godina, src_url, curr_hist, curr_arch, curr_desc = m
        
        eparch_clean = (eparchy or 'Srpske pravoslavne crkve').replace('Eparhija', '').strip()
        loc_str = f" u blizini mesta {city}" if city and city != 'Nepoznato' else ""
        region_str = f" na području {region}" if region and region != 'Nepoznato' else ""
        ktitor_str = f", čiji je zadužbinar {ktitor}" if ktitor else ""
        godina_str = f" iz {godina}. godine" if godina else ""

        # 1. OPŠTI PODACI
        opsti_sentences = [
            clean_sentence(f"{name} nalazi se u duhovnom okrilju Eparhije {eparch_clean}{loc_str}{region_str}."),
            clean_sentence(f"Predstavlja značajnu i poštovanu pravoslavnu svetinju{godina_str}{ktitor_str}, koja vekovima okuplja verni narod na zajedničku molitvu i liturgijsko sabranje.")
        ]
        
        # 2. ISTORIJA
        if curr_hist and len(curr_hist) > 80:
            hist_cleaned = clean_ekavica(curr_hist).replace('\n\n', ' ')
            # Uzmi do 4 rečenice
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

        opsti_text = " ".join(opsti_sentences)
        
        # Formiraj finalni opis sa tačnim podnaslovima
        final_desc = f"OPŠTI PODACI:\n{opsti_text}\n\nISTORIJA:\n{istorija_text}\n\nARHITEKTURA I UMETNOST:\n{arhitektura_text}\n\nDUHOVNI ŽIVOT I ZNAČAJ:\n{duhovni_text}"
        
        short_desc = opsti_sentences[0]
        if len(short_desc) > 260:
            short_desc = short_desc[:257] + "..."

        # Update both databases
        for db_path in [DB_STORAGE, DB_DATABASE]:
            conn_u = sqlite3.connect(db_path)
            cur_u = conn_u.cursor()
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

        updated_count += 1

    print(f"✓ Uspešno vraćeni tekstovi sa podnaslovima za svih {updated_count} manastira!")

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

    print("\n========================================================")
    print("SVI STRUKTURIRANI PODNASLOVI SU USPEŠNO VRAĆENI I SAČUVANI!")
    print("========================================================")

if __name__ == '__main__':
    main()
