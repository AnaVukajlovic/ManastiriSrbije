import urllib.request
import urllib.parse
import json
import os
import sys
import sqlite3
from PIL import Image
import io

sys.stdout.reconfigure(encoding='utf-8')

headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
}

def get_wikimedia_file_url(filename):
    clean_name = filename.replace('Датотека:', 'File:').replace('Фајл:', 'File:')
    if not clean_name.startswith('File:'):
        clean_name = 'File:' + clean_name
    params = {
        'action': 'query',
        'titles': clean_name,
        'prop': 'imageinfo',
        'iiprop': 'url|size|mime',
        'format': 'json'
    }
    url = f"https://commons.wikimedia.org/w/api.php?{urllib.parse.urlencode(params)}"
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req, timeout=15) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            for pid, pdata in pages.items():
                if 'imageinfo' in pdata:
                    return pdata['imageinfo'][0]['url']
    except Exception as e:
        print(f"Error fetching WM url for {filename}: {e}")
    return None

def download_and_optimize(url, dest_path, max_dim=1920):
    try:
        req = urllib.request.Request(url, headers=headers)
        with urllib.request.urlopen(req, timeout=20) as resp:
            content = resp.read()
        
        img = Image.open(io.BytesIO(content))
        if img.mode != 'RGB':
            img = img.convert('RGB')
            
        w, h = img.size
        if max(w, h) > max_dim:
            scale = max_dim / max(w, h)
            new_w = int(w * scale)
            new_h = int(h * scale)
            img = img.resize((new_w, new_h), Image.Resampling.LANCZOS)
            
        os.makedirs(os.path.dirname(dest_path), exist_ok=True)
        img.save(dest_path, 'JPEG', quality=88, optimize=True)
        size = os.path.getsize(dest_path)
        print(f"  [OK] Saved {dest_path}: {img.size[0]}x{img.size[1]} ({size} bytes) from {url[:70]}...")
        return True
    except Exception as e:
        print(f"  [ERROR] Failed {dest_path} from {url}: {e}")
        return False

# Plan for 13 monasteries:
# Each monastery will have at least 3 high-quality unique images in the gallery (1 main + 2-3 gallery images)
monasteries_data = {
    'grliste': {
        'id': 151,
        'images': [
            {'filename': 'grliste.jpg', 'source_type': 'keep_existing', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'grliste_gal_1.jpg', 'wm_file': 'Wiki.Biseri III Grlište Monastery 958.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'grliste_gal_2.jpg', 'wm_file': 'Wiki.Biseri III Grlište Monastery 955.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'grliste_gal_3.jpg', 'wm_file': 'Wiki.Biseri III Grlište Monastery 959.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'krepicevac': {
        'id': 154,
        'images': [
            {'filename': 'krepicevac.jpg', 'source_type': 'keep_existing', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'krepicevac_gal_1.jpg', 'wm_file': 'Wiki.Biseri V Manastir Krepičevac 61.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'krepicevac_gal_2.jpg', 'wm_file': 'Wiki.Biseri V Manastir Krepičevac 67.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'krepicevac_gal_3.jpg', 'wm_file': 'Wiki.Biseri V Manastir Krepičevac 69.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'lapusnja': {
        'id': 155,
        'images': [
            {'filename': 'lapusnja.jpg', 'source_type': 'keep_existing', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'lapusnja_gal_1.jpg', 'wm_file': 'Manastir Lapušnja 1.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'lapusnja_gal_2.jpg', 'wm_file': 'Manastir Lapušnja, detalj.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'lapusnja_gal_3.jpg', 'wm_file': 'Manastir Lapušnja, detalj ostataka freski.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'lozica': {
        'id': 156,
        'images': [
            {'filename': 'lozica.jpg', 'source_type': 'keep_existing', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'lozica_gal_1.jpg', 'wm_file': 'Wiki.Biseri V Manastir Lozica 144.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'lozica_gal_2.jpg', 'wm_file': 'Wiki.Biseri V Manastir Lozica 149.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'lozica_gal_3.jpg', 'wm_file': 'Wiki.Biseri V Manastir Lozica 153.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'vratna': {
        'id': 159,
        'images': [
            {'filename': 'vratna.jpg', 'source_type': 'keep_existing', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'vratna_gal_1.jpg', 'direct_url': 'https://manastiri.rs/wp-content/uploads/2025/03/manastir-vratna-1.jpg', 'source': 'Izvor: manastiri.rs'},
            {'filename': 'vratna_gal_2.jpg', 'direct_url': 'https://manastiri.rs/wp-content/uploads/2025/03/manastir-vratna-3.jpg', 'source': 'Izvor: manastiri.rs'},
            {'filename': 'vratna_gal_3.jpg', 'wm_file': 'Wiki.Zaleđe IV Vratna Monastery 345.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'suvodol': {
        'id': 158,
        'images': [
            {'filename': 'suvodol.jpg', 'source_type': 'keep_existing', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'suvodol_gal_1.jpg', 'wm_file': 'Wiki.Biseri III Suvodol Monastery (Zaječar) 1000.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'suvodol_gal_2.jpg', 'wm_file': 'Suvodol 7839.JPG', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'suvodol_gal_3.jpg', 'wm_file': 'Wiki.Biseri III Suvodol Monastery (Zaječar) 1007.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'lelic': {
        'id': 164,
        'images': [
            {'filename': 'lelic.jpg', 'source_type': 'keep_existing', 'source': 'Izvor: manastiri-crkve.com'},
            {'filename': 'lelic_gal_1.jpg', 'direct_url': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-lelic-m1.jpg', 'source': 'Izvor: manastiri.rs'},
            {'filename': 'lelic_gal_2.jpg', 'direct_url': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-lelic-konak.jpg', 'source': 'Izvor: manastiri.rs'},
            {'filename': 'lelic_gal_3.jpg', 'direct_url': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-lelic-ikonostas.jpg', 'source': 'Izvor: manastiri.rs'}
        ]
    },
    'bogovadja': {
        'id': 160,
        'images': [
            {'filename': 'bogovadja.jpg', 'source_type': 'keep_existing', 'source': 'Izvor: manastiri-crkve.com'},
            {'filename': 'bogovadja_gal_1.jpg', 'direct_url': 'https://manastiri.rs/wp-content/uploads/2025/02/manastiri-bogovadja-crkva.jpg', 'source': 'Izvor: manastiri.rs'},
            {'filename': 'bogovadja_gal_2.jpg', 'direct_url': 'https://manastiri.rs/wp-content/uploads/2025/02/manastiri-bogovadja-dvoriste.jpg', 'source': 'Izvor: manastiri.rs'},
            {'filename': 'bogovadja_gal_3.jpg', 'wm_file': 'Bogovađa, Manastir Bogovađa, 06.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'dokmir': {
        'id': 161,
        'images': [
            {'filename': 'dokmir.jpg', 'source_type': 'keep_existing', 'source': 'Izvor: manastiri-crkve.com'},
            {'filename': 'dokmir_gal_1.jpg', 'direct_url': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-dokmir-crkva.jpg', 'source': 'Izvor: manastiri.rs'},
            {'filename': 'dokmir_gal_2.jpg', 'direct_url': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-dokmir-konak.jpg', 'source': 'Izvor: manastiri.rs'},
            {'filename': 'dokmir_gal_3.jpg', 'wm_file': 'Manastir Dokmir 005.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'grabovac': {
        'id': 162,
        'images': [
            {'filename': 'grabovac.jpg', 'source_type': 'keep_existing', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'grabovac_gal_1.jpg', 'direct_url': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-grabovac-1.jpg', 'source': 'Izvor: manastiri.rs'},
            {'filename': 'grabovac_gal_2.jpg', 'direct_url': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-grabovac-2.jpg', 'source': 'Izvor: manastiri.rs'},
            {'filename': 'grabovac_gal_3.jpg', 'wm_file': 'Manastir Grabovac 012.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'ribnica': {
        'id': 166,
        'images': [
            {'filename': 'ribnica.jpg', 'source_type': 'keep_existing', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'ribnica_gal_1.jpg', 'wm_file': 'Manastir Ribnica 004.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'ribnica_gal_2.jpg', 'wm_file': 'Manastir Ribnica 012.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'ribnica_gal_3.jpg', 'wm_file': 'Manastir Ribnica 016.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'pluzac': {
        'id': 165,
        'images': [
            {'filename': 'pluzac.jpg', 'source_type': 'keep_existing', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'pluzac_gal_1.jpg', 'wm_file': 'Manastir Plužac 005.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'pluzac_gal_2.jpg', 'wm_file': 'Manastir Plužac 009.jpg', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'pluzac_gal_3.jpg', 'wm_file': 'Manastir Plužac 014.jpg', 'source': 'Izvor: commons.wikimedia.org'}
        ]
    },
    'jovanja': {
        'id': 163,
        'images': [
            {'filename': 'jovanja.jpg', 'source_type': 'keep_existing', 'source': 'Izvor: commons.wikimedia.org'},
            {'filename': 'jovanja_gal_1.jpg', 'direct_url': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-jovanja-crkva.jpg', 'source': 'Izvor: manastiri.rs'},
            {'filename': 'jovanja_gal_2.jpg', 'direct_url': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-jovanja-konak.jpg', 'source': 'Izvor: manastiri.rs'},
            {'filename': 'jovanja_gal_3.jpg', 'direct_url': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-jovanja-ikonostas.jpg', 'source': 'Izvor: manastiri.rs'}
        ]
    }
}

print("=== STARTING DOWNLOAD & PROCESSING ===")
for slug, mdata in monasteries_data.items():
    print(f"\n--- Monastery: {slug} (ID {mdata['id']}) ---")
    for img_info in mdata['images']:
        fn = img_info['filename']
        dest = os.path.join('public', 'images', 'monasteries', fn)
        
        if img_info.get('source_type') == 'keep_existing' and os.path.exists(dest):
            print(f"  [EXISTS] {dest} ({os.path.getsize(dest)} bytes)")
            continue
            
        url = img_info.get('direct_url')
        if not url and 'wm_file' in img_info:
            url = get_wikimedia_file_url(img_info['wm_file'])
            
        if url:
            download_and_optimize(url, dest)
        else:
            print(f"  [WARN] No URL found for {fn}")

# Now update SQLite database
conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

print("\n=== UPDATING DATABASE RECORDS ===")
for slug, mdata in monasteries_data.items():
    m_id = mdata['id']
    # Delete old images for this monastery
    cursor.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (m_id,))
    
    # Insert new verified images
    order = 1
    for img_info in mdata['images']:
        fn = img_info['filename']
        dest = os.path.join('public', 'images', 'monasteries', fn)
        if os.path.exists(dest):
            rel_url = f"images/monasteries/{fn}"
            caption = img_info['source']
            cursor.execute("""
                INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at)
                VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))
            """, (m_id, rel_url, caption, order))
            print(f"  DB inserted: [{m_id}] {rel_url} | {caption} (sort: {order})")
            order += 1
            
    # Also update main monastery image_url if needed
    main_rel = f"images/monasteries/{mdata['images'][0]['filename']}"
    cursor.execute("UPDATE monasteries SET image_url = ? WHERE id = ?", (main_rel, m_id))

# Clean up any descriptive captions in all monasteries from eparchies 12, 13, 14
print("\n=== CLEANING ALL CAPTIONS IN EPARCHIES 12, 13, 14 ===")
cursor.execute("""
    SELECT mi.id, mi.caption, mi.url
    FROM monastery_images mi
    JOIN monasteries m ON mi.monastery_id = m.id
    WHERE m.eparchy_id IN (12, 13, 14)
""")
for img_id, caption, url in cursor.fetchall():
    clean_cap = caption
    if caption:
        # Extract 'Izvor: ...' if mixed
        import re
        m = re.search(r'Izvor:\s*([^,\n\r]+)', caption, re.IGNORECASE)
        if m:
            clean_cap = f"Izvor: {m.group(1).strip()}"
        elif not caption.lower().startswith('izvor:'):
            # If no Izvor: prefix, default based on url or common source
            if 'manastiri.rs' in url:
                clean_cap = 'Izvor: manastiri.rs'
            else:
                clean_cap = 'Izvor: commons.wikimedia.org'
    else:
        clean_cap = 'Izvor: commons.wikimedia.org'
        
    cursor.execute("UPDATE monastery_images SET caption = ? WHERE id = ?", (clean_cap, img_id))

# Remove broken gallery records for any other monastery in eparchy 14 (Vranjska) if files don't exist
cursor.execute("""
    SELECT mi.id, mi.url
    FROM monastery_images mi
    JOIN monasteries m ON mi.monastery_id = m.id
    WHERE m.eparchy_id = 14
""")
for img_id, url in cursor.fetchall():
    clean_u = (url or '').lstrip('/')
    p = os.path.join('public', clean_u)
    if not os.path.exists(p):
        print(f"  Removing non-existent Vranjska image record: #{img_id} {url}")
        cursor.execute("DELETE FROM monastery_images WHERE id = ?", (img_id,))

conn.commit()
conn.close()
print("\n=== ALL COMPLETE ===")
