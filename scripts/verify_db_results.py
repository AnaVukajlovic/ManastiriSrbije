import sqlite3, sys, io, os
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

# Check naupare
c.execute('SELECT m.name, mi.url, mi.caption FROM monastery_images mi JOIN monasteries m ON mi.monastery_id=m.id WHERE m.slug=?', ('naupare',))
rows = c.fetchall()
print("=== NAUPARE ===")
for r in rows:
    p = os.path.join('public', r[1].replace('/', os.sep))
    exists = os.path.exists(p)
    sz = os.path.getsize(p) if exists else 0
    print(f'  {r[1]} | disk={exists} | {sz//1024}KB | caption="{r[2]}"')

# Summary of all eparchies
eparchies = ['eparhija-banatska','eparhija-backa','eparhija-beogradska','eparhija-branicevska','eparhija-krusevacka','eparhija-milesevska']
for ep in eparchies:
    c.execute('SELECT e.name FROM eparchies e WHERE e.slug=?', (ep,))
    ep_name = c.fetchone()[0]
    c.execute('''SELECT m.slug, COUNT(mi.id) FROM monasteries m 
                 LEFT JOIN monastery_images mi ON mi.monastery_id=m.id 
                 JOIN eparchies e ON m.eparchy_id=e.id 
                 WHERE e.slug=? GROUP BY m.slug''', (ep,))
    rows = c.fetchall()
    with_imgs = [(s,n) for s,n in rows if n>0]
    without = [s for s,n in rows if n==0]
    print(f'\n{ep_name}: {len(with_imgs)}/{len(rows)} sa slikama')
    for s,n in with_imgs:
        print(f'  + {s}: {n} slike')
    if without:
        print(f'  - bez slika: {without}')

# Check if any caption is non-empty
c.execute("SELECT COUNT(*) FROM monastery_images WHERE caption != '' AND caption IS NOT NULL")
cnt = c.fetchone()[0]
print(f'\nBroj zapisa sa nepraznim caption: {cnt}')

conn.close()
