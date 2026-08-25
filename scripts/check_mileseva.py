import sqlite3
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()
c.execute('SELECT m.name, m.slug, i.url, i.caption, i.sort_order FROM monastery_images i JOIN monasteries m ON i.monastery_id = m.id WHERE m.slug = ?', ('mileseva',))
for r in c.fetchall():
    print(r)
