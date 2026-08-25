import os
import sqlite3
import collections
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')

conn = sqlite3.connect(DB_PATH)
c = conn.cursor()
c.execute("SELECT id, name, slug, description, image_url FROM monasteries")
rows = c.fetchall()

print(f"Total monasteries in database: {len(rows)}")

# 1. Check duplicate or generic text
sentence_counts = collections.defaultdict(list)
empty_or_short_desc = []
for r in rows:
    desc = r[3] or ''
    if len(desc.strip()) < 150:
        empty_or_short_desc.append((r[0], r[1], len(desc)))
    for line in desc.split('\n'):
        if ':' in line:
            line = line.split(':', 1)[1]
        for s in line.split('.'):
            s = s.strip()
            if len(s) > 30:
                sentence_counts[s].append((r[0], r[1]))

print("\n--- TEKST: NAJČEŠĆE PONOVLJENE REČENICE (3+ manastira) ---")
reps = [(s, mons) for s, mons in sentence_counts.items() if len(mons) >= 3]
for s, mons in sorted(reps, key=lambda x: -len(x[1]))[:25]:
    print(f"[{len(mons)} manastira]: \"{s}\"")
    print(f"    Primeri: {', '.join([m[1] for m in mons[:4]])}")

print(f"\nPraznih ili prekratkih opisa: {len(empty_or_short_desc)}")

# 2. Check images and duplicates in monastery_images
print("\n--- SLIKE: DUPLIKATI I SUMNJIVE SLIKE ---")
c.execute("SELECT monastery_id, url, caption, count(*) FROM monastery_images GROUP BY monastery_id, url HAVING count(*) > 1")
dup_imgs = c.fetchall()
print(f"Ukupno duplikata URL-ova po manastiru u monastery_images: {len(dup_imgs)}")
for d in dup_imgs[:10]:
    print(f"  Monastery ID {d[0]}: {d[1]} (ponovljeno {d[3]} puta)")

# Check image counts per monastery
c.execute("SELECT m.id, m.name, m.slug, count(i.id) FROM monasteries m LEFT JOIN monastery_images i ON m.id = i.monastery_id GROUP BY m.id")
img_counts = c.fetchall()
no_imgs = [m for m in img_counts if m[3] == 0]
one_img = [m for m in img_counts if m[3] == 1]
many_imgs = [m for m in img_counts if m[3] > 1]
print(f"\nDistribucija slika u galeriji:")
print(f"  0 slika u galeriji: {len(no_imgs)}")
print(f"  1 slika u galeriji: {len(one_img)}")
print(f"  Više slika u galeriji: {len(many_imgs)}")

# Check image files in monastery_images that might not belong
c.execute("SELECT m.name, i.url, i.caption FROM monastery_images i JOIN monasteries m ON i.monastery_id = m.id")
all_imgs = c.fetchall()
suspect_imgs = []
for m_name, url, cap in all_imgs:
    low_u = url.lower()
    if 'commons-logo' in low_u or 'flag' in low_u or 'symbol' in low_u or 'icon' in low_u or 'pog' in low_u or 'ambox' in low_u or 'edit' in low_u:
        suspect_imgs.append((m_name, url, cap))

print(f"\nSumnjivih / neispravnih sistemskih ikona u bazi: {len(suspect_imgs)}")
for s in suspect_imgs[:10]:
    print(f"  {s[0]}: {s[1]} | {s[2]}")
