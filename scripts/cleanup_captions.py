"""
Cleanup script:
1. Clear ALL captions (set to '') in monastery_images
2. Remove naupare image if size < 50KB (likely wrong/placeholder)
"""
import sqlite3, sys, io, os
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')

for db_path in [
    os.path.join(BASE_DIR, 'storage', 'database.sqlite'),
    os.path.join(BASE_DIR, 'database', 'database.sqlite'),
]:
    if not os.path.exists(db_path):
        continue
    conn = sqlite3.connect(db_path)
    c = conn.cursor()

    # 1. Clear all captions
    c.execute("UPDATE monastery_images SET caption = '' WHERE caption != '' OR caption IS NOT NULL")
    print(f"Cleared captions: {c.rowcount} rows in {os.path.basename(os.path.dirname(db_path))}/database.sqlite")

    # 2. Check naupare image
    c.execute("SELECT mi.id, mi.url FROM monastery_images mi JOIN monasteries m ON mi.monastery_id=m.id WHERE m.slug='naupare'")
    naupare_imgs = c.fetchall()
    for mid, url in naupare_imgs:
        fpath = os.path.join(BASE_DIR, 'public', url.replace('/', os.sep))
        if os.path.exists(fpath):
            sz = os.path.getsize(fpath)
            if sz < 50000:  # less than 50KB - likely a thumbnail/wrong image
                c.execute("DELETE FROM monastery_images WHERE id=?", (mid,))
                print(f"  Removed naupare image (too small: {sz//1024}KB): {url}")

    conn.commit()
    conn.close()

print("\nDone!")
