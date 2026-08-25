import os
import sys
import io
import sqlite3

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PUB_DIR = os.path.join(BASE_DIR, 'public')
DB_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')

conn = sqlite3.connect(DB_PATH)
cur = conn.cursor()

cur.execute("SELECT id, name, slug, image_url FROM monasteries")
monasteries = cur.fetchall()

missing_main = []
missing_gallery = []
total_gallery_count = 0

for m_id, name, slug, img_url in monasteries:
    # Check main image
    full_main = os.path.join(PUB_DIR, img_url) if img_url and not img_url.startswith('http') else None
    if not full_main or not os.path.exists(full_main) or os.path.getsize(full_main) < 1024:
        missing_main.append((name, slug, img_url))

    # Check gallery images
    cur.execute("SELECT url FROM monastery_images WHERE monastery_id = ?", (m_id,))
    g_rows = cur.fetchall()
    total_gallery_count += len(g_rows)
    for (g_url,) in g_rows:
        full_g = os.path.join(PUB_DIR, g_url) if g_url and not g_url.startswith('http') else None
        if not full_g or not os.path.exists(full_g) or os.path.getsize(full_g) < 1024:
            missing_gallery.append((name, slug, g_url))

conn.close()

print(f"=== REZULTAT PROVERE LOKALNIH SLIKA ===")
print(f"Ukupno manastira: {len(monasteries)}")
print(f"Ukupno slika u galeriji: {total_gallery_count}")
print(f"Nedostajuće/neispravne glavne slike: {len(missing_main)}")
if missing_main:
    for m in missing_main[:5]:
        print(f"  - {m}")
print(f"Nedostajuće/neispravne galerijske slike: {len(missing_gallery)}")
if missing_gallery:
    for g in missing_gallery[:5]:
        print(f"  - {g}")

if len(missing_main) == 0 and len(missing_gallery) == 0:
    print("\n✓ SAVRŠENO! 100% SVIH SLIKA POSTOJI LOKALNO NA DISKU I POTPUNO JE ISPRAVNO!")
