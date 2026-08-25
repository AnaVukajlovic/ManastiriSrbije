import sqlite3
import os
import hashlib
import io
import sys
from PIL import Image

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
PUBLIC_IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')

conn = sqlite3.connect(DB_STORAGE)
c = conn.cursor()

c.execute('''
    SELECT m.id, m.name, m.slug, e.name, mi.url, mi.caption, mi.sort_order
    FROM monasteries m
    JOIN eparchies e ON m.eparchy_id = e.id
    LEFT JOIN monastery_images mi ON m.id = mi.monastery_id
    ORDER BY e.id, m.id, mi.sort_order
''')
rows = c.fetchall()

# 1. Check duplicate hashes of existing files in DB
file_hashes = {}
monastery_images = {}
major_monasteries = [
    'Studenica', 'Žiča', 'Sopoćani', 'Mileševa', 'Manasija', 'Ravanica', 'Krušedol',
    'Visoki Dečani', 'Pećka patrijaršija', 'Gračanica', 'Đurđevi Stupovi', 'Kalenić',
    'Ljubostinja', 'Poganovo', 'Banjska', 'Gornjak', 'Koporin', 'Prohor Pčinjski',
    'Tronoša', 'Velika Remeta', 'Novo Hopovo', 'Bođani', 'Kovilj', 'Ćelije', 'Lelić',
    'Bogorodica Ljeviška', 'Suvodol', 'Bukovo', 'Vraćevšnica', 'Rača'
]

print("=== AUDIT SLIKA U BAZI ===")
for r in rows:
    m_id, name, slug, ep, url, cap, sort_o = r
    if m_id not in monastery_images:
        monastery_images[m_id] = {'name': name, 'slug': slug, 'ep': ep, 'images': []}
    if url:
        disk_path = os.path.join(BASE_DIR, 'public', url.replace('/', os.sep))
        h = None
        dim = None
        if os.path.exists(disk_path):
            with open(disk_path, 'rb') as f:
                h = hashlib.md5(f.read()).hexdigest()
            try:
                with Image.open(disk_path) as img:
                    dim = img.size
            except Exception:
                dim = None
        monastery_images[m_id]['images'].append({'url': url, 'caption': cap, 'hash': h, 'dim': dim})

# Check for identical hashes within the same monastery or across DB
all_hashes = {}
duplicates = []
for m_id, data in monastery_images.items():
    seen_in_m = {}
    for img in data['images']:
        h = img['hash']
        if h:
            if h in seen_in_m:
                duplicates.append((data['name'], img['url'], seen_in_m[h]))
            seen_in_m[h] = img['url']

print(f"Pronađeno internih duplikata (identičan hash): {len(duplicates)}")
for d in duplicates:
    print(f"  Duplikat u {d[0]}: {d[1]} je identičan sa {d[2]}")

print("\n=== STATUS VELIKIH I POZNATIH MANASTIRA ===")
for m_id, data in monastery_images.items():
    clean_name = data['name'].replace('Manastir ', '')
    is_major = any(maj.lower() in clean_name.lower() or clean_name.lower() in maj.lower() for maj in major_monasteries)
    if is_major:
        img_count = len(data['images'])
        print(f"[{'OK' if img_count >= 3 else 'NEDOVOLJNO'}] {data['name']} ({data['ep']}) - Slika: {img_count}")
        for img in data['images']:
            print(f"    - {img['url']} ({img['dim']}): {img['caption'][:80]}...")

conn.close()
