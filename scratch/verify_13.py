import sys
import os
import sqlite3
from PIL import Image

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

monasteries = [
    'grliste', 'krepicevac', 'lapusnja', 'lozica', 'vratna', 'suvodol',
    'lelic', 'bogovadja', 'dokmir', 'grabovac', 'ribnica', 'pluzac', 'jovanja'
]

print("=== VERIFICATION OF 13 MONASTERIES ===")
for slug in monasteries:
    cursor.execute("SELECT id, name, slug, image_url FROM monasteries WHERE slug = ?", (slug,))
    m = cursor.fetchone()
    if not m:
        continue
    m_id, name, m_slug, main_img = m
    cursor.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order", (m_id,))
    imgs = cursor.fetchall()
    print(f"\n[{slug.upper()}] #{m_id} {name} (Main: {main_img}) -> {len(imgs)} gallery images:")
    for img_id, url, cap, sort_o in imgs:
        clean_u = url.lstrip('/')
        disk_path = os.path.join('public', clean_u)
        if os.path.exists(disk_path):
            try:
                im = Image.open(disk_path)
                w, h = im.size
                size_kb = os.path.getsize(disk_path) / 1024
                print(f"  ✓ [{img_id}] sort:{sort_o} | {url} | {w}x{h} ({size_kb:.1f} KB) | {cap}")
            except Exception as e:
                print(f"  ✗ [{img_id}] INVALID IMAGE {disk_path}: {e}")
        else:
            print(f"  ✗ [{img_id}] MISSING ON DISK: {disk_path}")
