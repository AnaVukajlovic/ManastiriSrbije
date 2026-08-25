import sqlite3
import os
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')

conn = sqlite3.connect(DB_PATH)
c = conn.cursor()

eparchies = ['eparhija-banatska', 'eparhija-backa', 'eparhija-beogradska']

print("==========================================================================")
print("  VERIFIKACIJA PREUZETIH WIKIMEDIA COMMONS SLIKA (BEZ LOGOA)")
print("==========================================================================")

for ep in eparchies:
    c.execute("SELECT name FROM eparchies WHERE slug = ?", (ep,))
    ep_name = c.fetchone()[0]
    print(f"\n##########################################################################")
    print(f"  {ep_name.upper()} ({ep})")
    print(f"##########################################################################")
    
    c.execute("SELECT m.id, m.name, m.slug FROM monasteries m JOIN eparchies e ON m.eparchy_id = e.id WHERE e.slug = ? ORDER BY m.id", (ep,))
    rows = c.fetchall()
    
    for m_id, name, slug in rows:
        c.execute("SELECT url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order", (m_id,))
        imgs = c.fetchall()
        print(f"\n• {name} ({slug}) - {len(imgs)} slika:")
        for im in imgs:
            url, caption, sort_order = im
            full_path = os.path.join(BASE_DIR, 'public', url)
            size_kb = os.path.getsize(full_path) // 1024 if os.path.exists(full_path) else 0
            exists = "✓" if os.path.exists(full_path) and size_kb > 0 else "✗"
            print(f"   [{sort_order}] {exists} {url} ({size_kb} KB)")
            print(f"       Opis: {caption}")

conn.close()
