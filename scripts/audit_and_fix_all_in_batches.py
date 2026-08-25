import os
import sys
import io
import sqlite3
import re
import urllib.request

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE_PATH = os.path.join(BASE_DIR, 'database', 'database.sqlite')

def process_batch(start_idx, limit=50):
    print(f"\n========================================================")
    print(f"  PROVERA I OBRADA TURE: od {start_idx} do {start_idx + limit}")
    print(f"========================================================")

    for db_path in [DB_STORAGE_PATH, DB_DATABASE_PATH]:
        if not os.path.exists(db_path):
            continue
        conn = sqlite3.connect(db_path)
        cur = conn.cursor()

        cur.execute("SELECT id, name, slug, image_url FROM monasteries ORDER BY id LIMIT ? OFFSET ?", (limit, start_idx))
        batch = cur.fetchall()

        for m_id, name, slug, main_img in batch:
            cur.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order, id", (m_id,))
            imgs = cur.fetchall()

            # Verify each image URL
            valid_imgs = []
            seen = set()

            for img_id, url, cap, so in imgs:
                if not url or url in seen:
                    continue
                # Local image verification
                if url.startswith('images/monasteries/'):
                    local_fp = os.path.join(BASE_DIR, 'public', url)
                    if os.path.exists(local_fp):
                        seen.add(url)
                        valid_imgs.append((url, cap))
                elif url.startswith('http://') or url.startswith('https://'):
                    seen.add(url)
                    valid_imgs.append((url, cap))

            # If no images, ensure local fallback if file exists
            local_rel = f"images/monasteries/{slug}.jpg"
            if not valid_imgs and os.path.exists(os.path.join(BASE_DIR, 'public', local_rel)):
                valid_imgs.append((local_rel, f"Glavni hram – {name}"))

            # Save back cleaned images
            cur.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (m_id,))
            for s_idx, (u, c) in enumerate(valid_imgs[:3], 1):
                cur.execute(
                    "INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))",
                    (m_id, u, c, s_idx)
                )

            if valid_imgs:
                cur.execute("UPDATE monasteries SET image_url = ? WHERE id = ?", (valid_imgs[0][0], m_id))

        conn.commit()
        conn.close()

    print(f"✓ Uspešno obrađeno {len(batch)} manastira u turi {start_idx}-{start_idx+limit}!")

def main():
    for offset in range(0, 300, 50):
        process_batch(offset, 50)
    print("\n✓ SVI MANASTIRI USPEŠNO PROVERENI I AŽURIRANI PO TURAMA OD 50!")

if __name__ == '__main__':
    main()
