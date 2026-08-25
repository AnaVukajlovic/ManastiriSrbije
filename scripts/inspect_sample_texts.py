import sqlite3
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()
c.execute('SELECT id, name, description, history, architecture FROM monasteries WHERE id IN (1, 3, 4, 10, 12, 18, 23, 26)')
for r in c.fetchall():
    print(f"\n==================== {r[1]} (ID: {r[0]}) ====================")
    print(f"--- OPIS ---\n{r[2]}")
    print(f"\n--- ISTORIJAT ---\n{r[3]}")
    print(f"\n--- ARHITEKTURA ---\n{r[4]}")
conn.close()
