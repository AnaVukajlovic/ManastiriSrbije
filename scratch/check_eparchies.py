import sqlite3, sys
sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cur = conn.cursor()

cur.execute("PRAGMA table_info(monasteries)")
cols = cur.fetchall()
print("Columns in monasteries:", [c[1] for c in cols])

cur.execute("SELECT id, name, image_url, description FROM monasteries WHERE eparchy_id = 3 ORDER BY id")
monasteries = cur.fetchall()
print(f"\nEparhija beogradska (ID 3) - Total monasteries: {len(monasteries)}")
for m in monasteries:
    cur.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order", (m[0],))
    images = cur.fetchall()
    print(f"\nMonastery ID {m[0]}: {m[1]}")
    print(f"  Card image: {m[2]}")
    desc_snippet = (m[3][:150] + '...') if m[3] else 'NO DESCRIPTION'
    print(f"  Description snippet: {desc_snippet}")
    print(f"  Gallery images ({len(images)}):")
    for img in images:
        cap_snip = (img[2][:80] + '...') if img[2] else 'NO CAPTION'
        print(f"    - [{img[3]}] {img[1]} -> {cap_snip}")

conn.close()
