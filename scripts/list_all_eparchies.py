import sqlite3
import sys
import io
import os

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

c.execute("SELECT id, name, slug FROM eparchies ORDER BY id")
all_ep = c.fetchall()
print("Sve eparhije u bazi:")
for ep_id, name, slug in all_ep:
    c.execute("SELECT count(*) FROM monasteries WHERE eparchy_id=?", (ep_id,))
    count = c.fetchone()[0]
    print(f"  {ep_id}. {name} ({slug}) -> {count} manastira")

conn.close()
