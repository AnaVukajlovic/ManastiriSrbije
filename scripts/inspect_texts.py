import sqlite3
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()
c.execute('''
    SELECT id, name, slug, 
           length(description), 
           length(history), 
           length(architecture), 
           length(art),
           source,
           source_url
    FROM monasteries 
    ORDER BY id ASC
''')
rows = c.fetchall()

print(f"Ukupno manastira u bazi: {len(rows)}")

empty_desc = 0
empty_hist = 0
has_manastiri_rs = 0

for r in rows:
    m_id, name, slug, len_d, len_h, len_arch, len_art, src, src_url = r
    if not len_d or len_d == 0:
        empty_desc += 1
    if not len_h or len_h == 0:
        empty_hist += 1
    if src and 'manastiri.rs' in src or (src_url and 'manastiri.rs' in src_url):
        has_manastiri_rs += 1

print(f"Praznih opisa (description): {empty_desc}")
print(f"Praznih istorijata (history): {empty_hist}")
print(f"Izvor manastiri.rs naveden kod: {has_manastiri_rs}")

print("\n--- Prvih 5 manastira i njihovi tekstovi ---")
c.execute('SELECT id, name, description, history, architecture FROM monasteries LIMIT 5')
for r in c.fetchall():
    print(f"\nID: {r[0]} | Naziv: {r[1]}")
    print(f"Description ({len(r[2]) if r[2] else 0} karaktera): {r[2][:150] if r[2] else 'PRAZNO'}...")
    print(f"History ({len(r[3]) if r[3] else 0} karaktera): {r[3][:150] if r[3] else 'PRAZNO'}...")
    print(f"Architecture ({len(r[4]) if r[4] else 0} karaktera): {r[4][:150] if r[4] else 'PRAZNO'}...")

conn.close()
