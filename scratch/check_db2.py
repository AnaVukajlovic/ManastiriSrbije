import sys
import sqlite3

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

monastery_slugs = [
    'grliste', 'krepicevac', 'lapusnja', 'lozica', 'vratna', 'suvodol',
    'lelic', 'bogovadja', 'dokmir', 'grabovac', 'ribnica', 'pluzac', 'jovanja'
]

print("=== TARGET MONASTERIES ===")
for slug in monastery_slugs:
    cursor.execute("SELECT id, name, slug, eparchy, eparchy_id, image, image_url FROM monasteries WHERE slug = ? OR slug LIKE ? OR name LIKE ?", (slug, f"%{slug}%", f"%{slug}%"))
    rows = cursor.fetchall()
    for row in rows:
        m_id, name, m_slug, eparchy, eparchy_id, img, img_url = row
        print(f"\nID: {m_id} | Name: {name} | Slug: {m_slug} | Eparchy: {eparchy} | Eparchy ID: {eparchy_id}")
        print(f"  Main image: {img} | Image URL: {img_url}")
        cursor.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order", (m_id,))
        images = cursor.fetchall()
        print(f"  Gallery ({len(images)} images):")
        for img_id, url, caption, sort_order in images:
            print(f"    - [{img_id}] sort:{sort_order} | url: {url} | caption: {caption}")

print("\n=== EPARCHIES IN DB ===")
cursor.execute("SELECT id, name, slug FROM eparchies")
print("Eparchies table:", cursor.fetchall())
