import os
import sqlite3
import io
import sys
from PIL import Image

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()
c.execute("SELECT mi.id, m.name, mi.url, mi.caption FROM monastery_images mi JOIN monasteries m ON mi.monastery_id = m.id WHERE m.slug LIKE '%decan%'")
for r in c.fetchall():
    print(r)
conn.close()

files = [f for f in os.listdir('public/images/monasteries') if 'decan' in f]
print("Files on disk for decani:", files)
