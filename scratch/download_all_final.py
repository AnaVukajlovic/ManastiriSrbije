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

def download_special_filepath(filename, dest_path, width=1600, max_retries=3):
    clean = filename.replace('Датотека:', '').replace('File:', '').strip()
    encoded = urllib.parse.quote(clean)
    url = f"https://commons.wikimedia.org/wiki/Special:FilePath/{encoded}?width={width}"
    
    for attempt in range(max_retries):
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
            print(f"  [Attempt {attempt+1}/{max_retries}] Error for {filename}: {e}")
            time.sleep(3 * (attempt + 1))
            
    return False

def download_direct_url(url, dest_path, max_dim=1920, max_retries=3):
    for attempt in range(max_retries):
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
            print(f"  [Attempt {attempt+1}/{max_retries}] Error for {url}: {e}")
            time.sleep(2 * (attempt + 1))
            
    return False

monasteries = {
    'grliste': {
        'id': 151,
        'images': [
            {'fn': 'grliste.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'grliste_gal_1.jpg', 'wm': 'Wiki.Biseri III Grlište Monastery 958.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'grliste_gal_2.jpg', 'wm': 'Wiki.Biseri III Grlište Monastery 955.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'grliste_gal_3.jpg', 'wm': 'Wiki.Biseri III Grlište Monastery 959.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'krepicevac': {
        'id': 154,
        'images': [
            {'fn': 'krepicevac.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'krepicevac_gal_1.jpg', 'wm': 'Wiki.Biseri V Manastir Krepičevac 61.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'krepicevac_gal_2.jpg', 'wm': 'Wiki.Biseri V Manastir Krepičevac 67.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'krepicevac_gal_3.jpg', 'wm': 'Wiki.Biseri V Manastir Krepičevac 69.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'lapusnja': {
        'id': 155,
        'images': [
            {'fn': 'lapusnja.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'lapusnja_gal_1.jpg', 'wm': 'Manastir Lapušnja 1.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'lapusnja_gal_2.jpg', 'wm': 'Manastir Lapušnja, detalj.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'lapusnja_gal_3.jpg', 'wm': 'Manastir Lapušnja, detalj ostataka freski.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'lozica': {
        'id': 156,
        'images': [
            {'fn': 'lozica.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'lozica_gal_1.jpg', 'wm': 'Wiki.Biseri V Manastir Lozica 144.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'lozica_gal_2.jpg', 'wm': 'Wiki.Biseri V Manastir Lozica 149.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'lozica_gal_3.jpg', 'wm': 'Wiki.Biseri V Manastir Lozica 153.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'vratna': {
        'id': 159,
        'images': [
            {'fn': 'vratna.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'vratna_gal_1.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/03/manastir-vratna-1.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'vratna_gal_2.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/03/manastir-vratna-3.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'vratna_gal_3.jpg', 'wm': 'Wiki.Zaleđe IV Vratna Monastery 345.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'suvodol': {
        'id': 158,
        'images': [
            {'fn': 'suvodol.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'suvodol_gal_1.jpg', 'wm': 'Wiki.Biseri III Suvodol Monastery (Zaječar) 1000.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'suvodol_gal_2.jpg', 'wm': 'Suvodol 7839.JPG', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'suvodol_gal_3.jpg', 'wm': 'Wiki.Biseri III Suvodol Monastery (Zaječar) 1007.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'lelic': {
        'id': 164,
        'images': [
            {'fn': 'lelic.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'lelic_gal_1.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-lelic-m1.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'lelic_gal_2.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-lelic-konak.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'lelic_gal_3.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-lelic-ikonostas.jpg', 'source': 'Izvor: manastiri.rs'}
        ]
    },
    'bogovadja': {
        'id': 160,
        'images': [
            {'fn': 'bogovadja.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'bogovadja_gal_1.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/02/manastiri-bogovadja-crkva.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'bogovadja_gal_2.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/02/manastiri-bogovadja-dvoriste.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'bogovadja_gal_3.jpg', 'wm': 'Bogovađa, Manastir Bogovađa, 06.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'dokmir': {
        'id': 161,
        'images': [
            {'fn': 'dokmir.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'dokmir_gal_1.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-dokmir-crkva.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'dokmir_gal_2.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-dokmir-konak.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'dokmir_gal_3.jpg', 'wm': 'Manastir Dokmir 005.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'grabovac': {
        'id': 162,
        'images': [
            {'fn': 'grabovac.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'grabovac_gal_1.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-grabovac-1.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'grabovac_gal_2.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-grabovac-2.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'grabovac_gal_3.jpg', 'wm': 'Manastir Grabovac 012.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'ribnica': {
        'id': 166,
        'images': [
            {'fn': 'ribnica.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'ribnica_gal_1.jpg', 'wm': 'Manastir Ribnica 004.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'ribnica_gal_2.jpg', 'wm': 'Manastir Ribnica 012.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'ribnica_gal_3.jpg', 'wm': 'Manastir Ribnica 016.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'pluzac': {
        'id': 165,
        'images': [
            {'fn': 'pluzac.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'pluzac_gal_1.jpg', 'wm': 'Manastir Plužac 005.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'pluzac_gal_2.jpg', 'wm': 'Manastir Plužac 009.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'pluzac_gal_3.jpg', 'wm': 'Manastir Plužac 014.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'jovanja': {
        'id': 163,
        'images': [
            {'fn': 'jovanja.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'fn': 'jovanja_gal_1.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-jovanja-crkva.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'jovanja_gal_2.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-jovanja-konak.jpg', 'source': 'Izvor: manastiri.rs'},
            {'fn': 'jovanja_gal_3.jpg', 'direct': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-jovanja-ikonostas.jpg', 'source': 'Izvor: manastiri.rs'}
        ]
    }
}

print("=== STARTING DOWNLOAD PROCESS ===")
for slug, mdata in monasteries.items():
    print(f"\nProcessing {slug} (ID {mdata['id']})...")
    for img in mdata['images']:
        fn = img['fn']
        dest = os.path.join('public', 'images', 'monasteries', fn)
        
        # If file already exists and is non-empty, check if we need to redownload
        if os.path.exists(dest) and os.path.getsize(dest) > 1000:
            print(f"  [EXISTS] {dest} ({os.path.getsize(dest)} B)")
            continue
            
        if 'direct' in img:
            download_direct_url(img['direct'], dest)
            time.sleep(1.0)
        elif 'wm' in img:
            download_special_filepath(img['wm'], dest)
            time.sleep(2.5)

print("\n=== UPDATING SQLITE DATABASE ===")
conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

for slug, mdata in monasteries.items():
    m_id = mdata['id']
    cursor.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (m_id,))
    
    order = 1
    for img in mdata['images']:
        fn = img['fn']
        dest = os.path.join('public', 'images', 'monasteries', fn)
        if os.path.exists(dest) and os.path.getsize(dest) > 1000:
            rel_url = f"images/monasteries/{fn}"
            cap = img['source']
            cursor.execute("""
                INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at)
                VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))
            """, (m_id, rel_url, cap, order))
            print(f"  [{m_id}] Inserted #{order}: {rel_url} | {cap}")
            order += 1
            
    # Set main image_url in monasteries
    main_fn = mdata['images'][0]['fn']
    cursor.execute("UPDATE monasteries SET image_url = ? WHERE id = ?", (f"images/monasteries/{main_fn}", m_id))

# Final clean of all captions across eparchies 12, 13, 14
print("\n=== SANITIZING ALL CAPTIONS IN EPARCHIES 12, 13, 14 ===")
cursor.execute("""
    SELECT mi.id, mi.caption, mi.url
    FROM monastery_images mi
    JOIN monasteries m ON mi.monastery_id = m.id
    WHERE m.eparchy_id IN (12, 13, 14)
""")
for img_id, cap, url in cursor.fetchall():
    if not cap or not cap.lower().startswith('izvor:'):
        if 'manastiri.rs' in (url or ''):
            new_cap = 'Izvor: manastiri.rs'
        elif 'manastiri-crkve.com' in (cap or ''):
            new_cap = 'Izvor: manastiri-crkve.com'
        else:
            new_cap = 'Izvor: commons.wikimedia.org'
        cursor.execute("UPDATE monastery_images SET caption = ? WHERE id = ?", (new_cap, img_id))
    else:
        # ensure it's just 'Izvor: ...' without extra descriptions
        import re
        m = re.search(r'Izvor:\s*([^,\n\r]+)', cap, re.IGNORECASE)
        if m:
            clean = f"Izvor: {m.group(1).strip()}"
            cursor.execute("UPDATE monastery_images SET caption = ? WHERE id = ?", (clean, img_id))

conn.commit()
conn.close()
print("\n=== SCRIPT FINISHED SUCCESSFULLY ===")
