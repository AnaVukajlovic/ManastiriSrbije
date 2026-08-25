import sqlite3
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()
c.execute('SELECT id, name, excerpt, history, architecture FROM monasteries WHERE id IN (26, 69, 138, 214, 228, 239)')
for r in c.fetchall():
    print(f"\n==================== {r[1]} (ID: {r[0]}) ====================")
    print(f"[KRATAK OPIS / EXCERPT]:\n{r[2]}\n")
    print(f"[ISTORIJAT / HISTORY]:\n{r[3][:350]}...\n")
    print(f"[ARHITEKTURA I UMETNOST / ARCHITECTURE]:\n{r[4][:350]}...\n")
conn.close()
