import urllib.request
import urllib.parse
import json
import os
import sys
import time
import sqlite3
from PIL import Image
import io

sys.stdout.reconfigure(encoding='utf-8')

headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
}

def download_special_filepath(filename, dest_path, width=1600):
    clean = filename.replace('Датотека:', '').replace('File:', '').strip()
    encoded = urllib.parse.quote(clean)
    url = f"https://commons.wikimedia.org/wiki/Special:FilePath/{encoded}?width={width}"
    
    for attempt in range(3):
        try:
            req = urllib.request.Request(url, headers=headers)
            with urllib.request.urlopen(req, timeout=15) as resp:
                data = resp.read()
                
            img = Image.open(io.BytesIO(data))
            if img.mode != 'RGB':
                img = img.convert('RGB')
                
            w, h = img.size
            if max(w, h) > 1920:
                scale = 1920 / max(w, h)
                img = img.resize((int(w * scale), int(h * scale)), Image.Resampling.LANCZOS)
                
            os.makedirs(os.path.dirname(dest_path), exist_ok=True)
            img.save(dest_path, 'JPEG', quality=88, optimize=True)
            print(f"  [OK] Saved {dest_path}: {img.size[0]}x{img.size[1]} ({os.path.getsize(dest_path)} B)")
            return True
        except Exception as e:
            print(f"  [Attempt {attempt+1}] Error for {filename}: {e}")
            time.sleep(4 * (attempt + 1))
            
    return False

def download_direct_url(url, dest_path, max_dim=1920):
    for attempt in range(3):
        try:
            req = urllib.request.Request(url, headers=headers)
            with urllib.request.urlopen(req, timeout=15) as resp:
                data = resp.read()
                
            img = Image.open(io.BytesIO(data))
            if img.mode != 'RGB':
                img = img.convert('RGB')
                
            w, h = img.size
            if max(w, h) > max_dim:
                scale = max_dim / max(w, h)
                img = img.resize((int(w * scale), int(h * scale)), Image.Resampling.LANCZOS)
                
            os.makedirs(os.path.dirname(dest_path), exist_ok=True)
            img.save(dest_path, 'JPEG', quality=88, optimize=True)
            print(f"  [OK] Saved {dest_path}: {img.size[0]}x{img.size[1]} ({os.path.getsize(dest_path)} B)")
            return True
        except Exception as e:
            print(f"  [Attempt {attempt+1}] Error for {url}: {e}")
            time.sleep(3 * (attempt + 1))
            
    return False

extras = [
    # lapusnja
    {'dest': 'public/images/monasteries/lapusnja_gal_1.jpg', 'wm': 'Manastir Lapušnja 1.jpg', 'm_id': 155, 'src': 'Izvor: commons.wikimedia.org'},
    {'dest': 'public/images/monasteries/lapusnja_gal_3.jpg', 'wm': 'Manastir Lapušnja, detalj ostataka freski.jpg', 'm_id': 155, 'src': 'Izvor: commons.wikimedia.org'},
    # pluzac
    {'dest': 'public/images/monasteries/pluzac_gal_1.jpg', 'wm': 'Manastir Plužac 005.jpg', 'm_id': 165, 'src': 'Izvor: commons.wikimedia.org'},
    {'dest': 'public/images/monasteries/pluzac_gal_3.jpg', 'wm': 'Manastir Plužac 011.jpg', 'm_id': 165, 'src': 'Izvor: commons.wikimedia.org'},
    # vratna
    {'dest': 'public/images/monasteries/vratna_gal_3.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/03/manastir-vratna-5.jpg', 'm_id': 159, 'src': 'Izvor: manastiri.rs'},
    # bogovadja
    {'dest': 'public/images/monasteries/bogovadja_gal_3.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/02/manastiri-bogovadja-kapija.jpg', 'm_id': 160, 'src': 'Izvor: manastiri.rs'},
    # grabovac
    {'dest': 'public/images/monasteries/grabovac_gal_3.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-grabovac-3.jpg', 'm_id': 162, 'src': 'Izvor: manastiri.rs'},
    # suvodol
    {'dest': 'public/images/monasteries/suvodol_gal_2.jpg', 'wm': 'Suvodol 7839.JPG', 'm_id': 158, 'src': 'Izvor: commons.wikimedia.org'},
    # lozica
    {'dest': 'public/images/monasteries/lozica_gal_2.jpg', 'wm': 'Wiki.Biseri V Manastir Lozica 149.jpg', 'm_id': 156, 'src': 'Izvor: commons.wikimedia.org'},
    # ribnica
    {'dest': 'public/images/monasteries/ribnica_gal_2.jpg', 'wm': 'Manastir Ribnica 008.jpg', 'm_id': 166, 'src': 'Izvor: commons.wikimedia.org'}
]

print("=== DOWNLOADING EXTRAS ===")
for item in extras:
    dest = item['dest']
    if os.path.exists(dest) and os.path.getsize(dest) > 1000:
        print(f"  [EXISTS] {dest}")
        continue
    if 'direct' in item:
        download_direct_url(item['direct'], dest)
        time.sleep(1.0)
    elif 'wm' in item:
        download_special_filepath(item['wm'], dest)
        time.sleep(3.5)

# Re-sync SQLite DB for all 13 monasteries
conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

all_13 = [151, 154, 155, 156, 159, 158, 164, 160, 161, 162, 166, 165, 163]

for m_id in all_13:
    cursor.execute("SELECT slug FROM monasteries WHERE id = ?", (m_id,))
    slug = cursor.fetchone()[0]
    
    # find all existing images on disk for this slug
    main_img = f"images/monasteries/{slug}.jpg"
    gallery_files = []
    
    # check main
    if os.path.exists(os.path.join('public', main_img)):
        gallery_files.append(main_img)
        
    for i in range(1, 10):
        gf = f"images/monasteries/{slug}_gal_{i}.jpg"
        if os.path.exists(os.path.join('public', gf)) and os.path.getsize(os.path.join('public', gf)) > 1000:
            gallery_files.append(gf)
            
    cursor.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (m_id,))
    
    for idx, gpath in enumerate(gallery_files, 1):
        if 'manastiri.rs' in slug or m_id in [160, 161, 162, 163, 164] or 'manastiri.rs' in gpath:
            # Let's check source
            if m_id in [160, 161, 162, 163, 164] and ('_gal_' in gpath or m_id == 164):
                cap = 'Izvor: manastiri.rs'
            elif m_id == 159 and '_gal_' in gpath:
                cap = 'Izvor: manastiri.rs'
            else:
                cap = 'Izvor: commons.wikimedia.org'
        else:
            cap = 'Izvor: commons.wikimedia.org'
            
        cursor.execute("""
            INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at)
            VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))
        """, (m_id, gpath, cap, idx))
        
    cursor.execute("UPDATE monasteries SET image_url = ? WHERE id = ?", (main_img, m_id))
    print(f"Monastery #{m_id} ({slug}) -> {len(gallery_files)} images synced to DB")

# Clean all captions across eparchies 12, 13, 14
cursor.execute("""
    SELECT mi.id, mi.caption, mi.url
    FROM monastery_images mi
    JOIN monasteries m ON mi.monastery_id = m.id
    WHERE m.eparchy_id IN (12, 13, 14)
""")
for img_id, cap, url in cursor.fetchall():
    if not cap or not cap.lower().startswith('izvor:'):
        if 'manastiri.rs' in (url or ''):
            clean = 'Izvor: manastiri.rs'
        else:
            clean = 'Izvor: commons.wikimedia.org'
    else:
        import re
        m = re.search(r'Izvor:\s*([^,\n\r]+)', cap, re.IGNORECASE)
        if m:
            clean = f"Izvor: {m.group(1).strip()}"
        else:
            clean = 'Izvor: commons.wikimedia.org'
    cursor.execute("UPDATE monastery_images SET caption = ? WHERE id = ?", (clean, img_id))

conn.commit()
conn.close()
print("\n=== ALL EXTRAS FINISHED ===")
