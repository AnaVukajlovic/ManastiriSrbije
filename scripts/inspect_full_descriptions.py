import sqlite3
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

c.execute('SELECT id, name, description FROM monasteries WHERE id IN (1, 4, 12, 26, 69, 138, 228, 239)')
for r in c.fetchall():
    print(f"\n==================== ID {r[0]}: {r[1]} ====================")
    print(r[2])

conn.close()
