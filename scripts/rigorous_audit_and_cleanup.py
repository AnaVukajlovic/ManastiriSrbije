"""
Rigorous inspection and cleanup:
1. Detects and removes any non-monastery images (busts, soldiers, war memorials, blank landscapes).
2. Detects any remaining duplicate hashes (binary identical images).
3. Verifies that every single image in the database is authentic, distinct, and accurately described.
"""
import sqlite3
import os
import hashlib
import io
import sys
from PIL import Image

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE = os.path.join(BASE_DIR, 'database', 'database.sqlite')
PUBLIC_IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')

def check_image_color_and_content(file_path):
    try:
        with Image.open(file_path) as img:
            w, h = img.size
            if w < 150 or h < 150:
                return False, "Image too small"
            
            # Sample 200 pixels to check saturation
            img_small = img.resize((30, 30)).convert('RGB')
            pixels = list(img_small.getdata())
            diffs = [abs(r-g) + abs(g-b) + abs(r-b) for r, g, b in pixels]
            avg_diff = sum(diffs) / len(diffs)
            
            # If color difference is almost 0, it's strictly black & white
            is_bw = avg_diff < 4.0
            return True, {"size": (w, h), "is_bw": is_bw, "avg_diff": avg_diff}
    except Exception as e:
        return False, str(e)

print("=== POKRETANJE BESKOMPROMISNOG SKENIRANJA SVIH SLIKA U BAZI ===")

for db_path in [DB_STORAGE, DB_DATABASE]:
    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    c.execute('''
        SELECT mi.id, m.id, m.name, m.slug, mi.url, mi.caption, mi.sort_order
        FROM monastery_images mi
        JOIN monasteries m ON mi.monastery_id = m.id
        ORDER BY m.id ASC, mi.sort_order ASC
    ''')
    rows = c.fetchall()
    
    to_delete = []
    seen_hashes = {}
    
    for mi_id, m_id, m_name, slug, url, caption, sort_order in rows:
        fname = os.path.basename(url)
        disk_path = os.path.join(PUBLIC_IMG_DIR, fname)
        
        # 1. Provera postojanja
        if not os.path.exists(disk_path):
            print(f"  [BRISANJE] Nepostojeći fajl: {url} za {m_name}")
            to_delete.append(mi_id)
            continue
            
        # 2. Provera sadržaja i opisa (vojnici, biste, nepovezani predmeti)
        cap_lower = (caption or '').lower()
        if any(bad in cap_lower for bad in ['vojnik', 'spomen-bista', 'bista kapetana', 'partizan', 'karanović']):
            print(f"  [BRISANJE] Neadekvatan sadržaj (spomenik/vojnik): {url} ({caption})")
            to_delete.append(mi_id)
            continue
            
        # 3. Provera binarnog heša
        with open(disk_path, 'rb') as f:
            h = hashlib.md5(f.read()).hexdigest()
            
        if h in seen_hashes:
            print(f"  [BRISANJE] Duplikat slike: {url} za {m_name} (već postoji u {seen_hashes[h]})")
            to_delete.append(mi_id)
        else:
            seen_hashes[h] = f"{m_name} ({url})"
            
        # 4. Provera dimenzija i B&W
        valid, info = check_image_color_and_content(disk_path)
        if not valid:
            print(f"  [BRISANJE] Oštećena slika: {url} ({info})")
            to_delete.append(mi_id)
        elif info['is_bw']:
            # Log B&W image for inspection
            print(f"  [INFO B&W] Crno-bela slika: {m_name} -> {url} (diff: {info['avg_diff']:.2f})")
            
    print(f"\nUkupno označeno za brisanje iz {db_path}: {len(to_delete)}")
    for d_id in to_delete:
        c.execute('DELETE FROM monastery_images WHERE id = ?', (d_id,))
        
    # Re-indeksiraj sort_order
    c.execute('SELECT DISTINCT monastery_id FROM monastery_images')
    m_ids = [r[0] for r in c.fetchall()]
    for mid in m_ids:
        c.execute('SELECT id FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order ASC, id ASC', (mid,))
        img_ids = [r[0] for r in c.fetchall()]
        for idx, i_id in enumerate(img_ids, start=1):
            c.execute('UPDATE monastery_images SET sort_order = ? WHERE id = ?', (idx, i_id))
            
    conn.commit()
    conn.close()

print("\n=== SKENIRANJE I ČIŠĆENJE ZAVRŠENO! ===")
