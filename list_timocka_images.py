import sqlite3, os, re, sys
sys.stdout.reconfigure(encoding='utf-8')
backend = r'D:\projekti\ManastiriSrbije\backend'
db_path = os.path.join(backend, 'database', 'database.sqlite')
img_dir = os.path.join(backend, 'public', 'images', 'monasteries')
conn = sqlite3.connect(db_path)
cur = conn.cursor()
cur.execute('SELECT id, name FROM monasteries WHERE eparchy_id = 12')
monasteries = cur.fetchall()
print(f'Timocka eparhija monasteries count: {len(monasteries)}')
for mon_id, mon_name in monasteries:
    print(f'\nMonastery {mon_id}: {mon_name}')
    cur.execute('SELECT url, caption FROM monastery_images WHERE monastery_id = ?', (mon_id,))
    images = cur.fetchall()
    for url, caption in images:
        src_match = re.search(r'\(Izvor:\s*([^\)]+)\)', caption or '')
        src = src_match.group(1) if src_match else 'MISSING'
        fpath = os.path.join(img_dir, os.path.basename(url))
        exists = os.path.exists(fpath)
        print(f'  {url} -> source: {src} | exists: {exists}')
conn.close()
