import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

cursor.execute('SELECT id, name, image_url FROM monasteries WHERE eparchy_id = 2 ORDER BY id')
rows = cursor.fetchall()
print(f"Total monasteries: {len(rows)}")
for r in rows:
    cursor.execute('SELECT url FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order', (r[0],))
    imgs = [x[0] for x in cursor.fetchall()]
    print(f"[{r[0]}] {r[1]} -> Card: {r[2]} | Gallery ({len(imgs)}): {', '.join(imgs)}")
