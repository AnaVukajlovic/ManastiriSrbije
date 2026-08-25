import sqlite3, os, sys
sys.stdout.reconfigure(encoding='utf-8')
from PIL import Image

conn = sqlite3.connect('database/database.sqlite')
cur = conn.cursor()

cur.execute("""
    SELECT id, name, slug, image_url, description, history, architecture, ktitor, godina_izgradnje
    FROM monasteries 
    WHERE eparchy_id = 3 
    ORDER BY id
""")
monasteries = cur.fetchall()

for m in monasteries:
    m_id, name, slug, card_img, desc, hist, arch, ktitor, year = m
    print(f"\n==================================================")
    print(f"ID {m_id}: {name} (Slug: {slug})")
    print(f"  Card image: {card_img}")
    print(f"  Ktitor: {ktitor} | Year: {year}")
    print(f"  Description len: {len(desc) if desc else 0}")
    print(f"  History len: {len(hist) if hist else 0}")
    print(f"  Architecture len: {len(arch) if arch else 0}")
    
    # Check card image file
    if card_img:
        card_path = os.path.join('public', card_img)
        if os.path.exists(card_path):
            im = Image.open(card_path)
            print(f"  Card file size: {os.path.getsize(card_path)/1024:.1f} KB, dimensions: {im.size}")
        else:
            print(f"  Card file MISSING: {card_path}")

    # Check gallery images
    cur.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order", (m_id,))
    images = cur.fetchall()
    print(f"  Gallery images ({len(images)}):")
    for img in images:
        img_id, url, cap, sort = img
        p = os.path.join('public', url)
        if os.path.exists(p):
            im = Image.open(p)
            print(f"    [{sort}] {url} ({im.size[0]}x{im.size[1]}, {os.path.getsize(p)/1024:.1f} KB)")
        else:
            print(f"    [{sort}] {url} - MISSING")
        print(f"        Caption: {cap}")

conn.close()
