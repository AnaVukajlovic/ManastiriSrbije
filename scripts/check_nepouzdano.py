import sqlite3
import os

for db_path in ['storage/database.sqlite', 'database/database.sqlite']:
    if not os.path.exists(db_path): continue
    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    c.execute("SELECT id, slug, name, image_url FROM monasteries WHERE image_url LIKE '%ne_pouzdano%' OR image_url LIKE '%nepouzdano%'")
    print(db_path, "monasteries:", c.fetchall())
    c.execute("SELECT id, monastery_id, url, caption FROM monastery_images WHERE url LIKE '%ne_pouzdano%' OR caption LIKE '%ne_pouzdano%' OR url LIKE '%nepouzdano%' OR caption LIKE '%nepouzdano%'")
    print(db_path, "images:", c.fetchall())
    conn.close()
