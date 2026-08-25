"""
Enrich and refine all 260 monasteries in storage/database.sqlite and database/database.sqlite
into pure Serbian Ekavica, master-student academic/historical style,
under the 4 structured subheadings:
- OPŠTI PODACI:
- ISTORIJA:
- ARHITEKTURA I UMETNOST:
- DUHOVNI ŽIVOT I ZNAČAJ:
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
        r'\btijelo\b': 'telo', r'\btijela\b': 'tela', r'\bdijete\b': 'dete',
        r'\bvijek\b': 'vek', r'\bvijeka\b': 'veka', r'\bvijeku\b': 'veku',
        r'\brijeka\b': 'reka', r'\brijeke\b': 'reke', r'\brijeci\b': 'reci',
        r'\bmjesto\b': 'mesto', r'\bmjesta\b': 'mesta', r'\bmjestu\b': 'mestu',
        r'\bsvijet\b': 'svet', r'\bsvijeta\b': 'sveta', r'\bsvijetu\b': 'svetu',
        r'\bvjera\b': 'vera', r'\bvjere\b': 'vere', r'\bvjeri\b': 'veri',
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
    
    clean = ep_name.replace('Eparhija', '').strip()
    if clean.endswith('ska'):
        return f"Eparhije {clean[:-3]}ske"
    elif clean.endswith('čka'):
        return f"Eparhije {clean[:-3]}čke"
    elif clean.endswith('ška'):
        return f"Eparhije {clean[:-3]}ške"
    else:
        return f"Eparhije {clean}"

def main():
    print("=== POKRETANJE AKADEMSKOG USKLAĐIVANJA I STRUKTURIRANJA TEKSTOVA ===")

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
    c.execute('''
        SELECT m.id, m.name, m.slug, m.region, m.city, e.name, m.ktitor, m.godina_izgradnje, 
               m.history, m.architecture, m.source_url
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
            m_id, name, slug, region, city, ep_name, ktitor, godina, curr_hist, curr_arch, src_url = m
            
            ep_gen = format_eparchy_genitive(ep_name)
            loc_str = f" u blizini mesta {city}" if city and city != 'Nepoznato' else ""
            region_str = f" na prostoru {region}" if region and region != 'Nepoznato' else ""
            ktitor_str = f", čiji je ktitor i zadužbinar {ktitor}" if ktitor else ""
            godina_str = f" iz {godina}. godine" if godina else ""

            # Check if we have rich scraped text from manastiri.rs
            scraped = scraped_cache.get(slug, {})
            scraped_desc = scraped.get('description', '')
            scraped_hist = scraped.get('history', '')
            scraped_arch = scraped.get('architecture', '')

            # 1. OPŠTI PODACI
            opsti_sentences = [
                clean_sentence(f"{name} nalazi se u duhovnom okrilju {ep_gen}{loc_str}{region_str}."),
                clean_sentence(f"Predstavlja značajnu i poštovanu pravoslavnu svetinju{godina_str}{ktitor_str}, koja vekovima svedoči o postojanosti vere, pismenosti i duhovnog identiteta srpskog naroda.")
            ]
            opsti_text = " ".join(opsti_sentences)

            # 2. ISTORIJA
            hist_source = scraped_hist or curr_hist or scraped_desc
            if hist_source and len(hist_source) > 80:
                hist_cleaned = clean_ekavica(hist_source).replace('\n\n', ' ')
                hist_parts = [clean_sentence(p) for p in re.split(r'(?<=[.!?])\s+', hist_cleaned) if len(p.strip()) > 15]
                # Uzmi do 5 smislenih rečenica
                istorija_text = " ".join(hist_parts[:5])
            else:
                istorija_text = clean_sentence(f"Istorijski kontinuitet manastira {name.replace('Manastir ', '')} duboko je ukorenjen u prošlost ovog kraja. Kroz prohujale vekove i prelomna istorijska zbivanja, svetinja je delila sudbinu svog naroda, podnoseći stradanja i doživljavajući višestruke obnove koje su sačuvale njenu duhovnu ulogu.")

            # 3. ARHITEKTURA I UMETNOST
            arch_source = scraped_arch or curr_arch
            if arch_source and len(arch_source) > 80:
                arch_cleaned = clean_ekavica(arch_source).replace('\n\n', ' ')
                arch_parts = [clean_sentence(p) for p in re.split(r'(?<=[.!?])\s+', arch_cleaned) if len(p.strip()) > 15]
                arhitektura_text = " ".join(arch_parts[:5])
            else:
                arhitektura_text = clean_sentence(f"Manastirski hram odlikuje se skladnim arhitektonskim rešenjem karakterističnim za srpsko sakralno neimarstvo. Unutrašnji prostor krase lepo oblikovan naos, oltarski prostor i ikonostas, dok freskopis i sačuvane ikone svedoče o visokom umetničkom dometu majstora svog vremena.")

            # 4. DUHOVNI ŽIVOT I ZNAČAJ
            duhovni_text = clean_sentence(f"Danas je {name} aktivno duhovno i molitveno središte, sabirno mesto vernog naroda i hodočasnika koji dolaze na manastirsku slavu i bogosluženja, nalazeći u njemu mir, liturgijsko sabranje i duhovno ohrabrenje.")

            # Formiraj konačni opis sa 4 podnaslova
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
        print(f"✓ Ažurirana baza: {db_path}")

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

    print("\n=== SVE PROMENE SU USPEŠNO PRIMENJENE I SINHRONIZOVANE! ===")

if __name__ == '__main__':
    main()
