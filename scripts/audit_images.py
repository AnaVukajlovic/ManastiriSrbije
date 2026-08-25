import os
import sys
import io
import sqlite3
import re

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')

conn = sqlite3.connect(DB_PATH)
c = conn.cursor()

c.execute("""
    SELECT m.id, m.name, m.slug, i.id, i.url, i.caption 
    FROM monastery_images i 
    JOIN monasteries m ON i.monastery_id = m.id 
    ORDER BY m.id, i.sort_order
""")
all_images = c.fetchall()

print(f"Ukupno slika u bazi: {len(all_images)}")

# Check for potential mismatches
suspicious = []
for m_id, m_name, slug, img_id, url, caption in all_images:
    u_low = url.lower()
    cap_low = caption.lower()
    
    # Check if url has words that are completely out of place
    bad_tokens = ['grad', 'tvrdjava', 'fortress', 'spomenik', 'monument', 'reka', 'river', 'flora', 'fauna', 'selo', 'groblje', 'cemetery', 'flag', 'zastava', 'map', 'karta', 'bridge', 'most', 'planina', 'sumska', 'pejzaz']
    for bt in bad_tokens:
        if bt in u_low and not any(ok in u_low for ok in ['manastir', 'church', 'crkva', 'fresk', 'fresco', 'ikon', 'icon', 'konak', 'zvonik', 'portal', 'olt']):
            suspicious.append((m_id, m_name, slug, img_id, url, caption, f"Sadrži '{bt}' u URL-u"))
            break

print(f"Pronađeno sumnjivih slika po kriterijumima: {len(suspicious)}")
for s in suspicious:
    print(f"  [{s[0]}] {s[1]}: ID {s[3]} | {s[4]} | Opis: {s[5]} ({s[6]})")
