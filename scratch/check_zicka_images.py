import os
import sqlite3
import urllib.request
import json
import time

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

cursor.execute("""
    SELECT m.id, m.name, m.slug, m.image_url, count(mi.id) as img_count
    FROM monasteries m
    LEFT JOIN monastery_images mi ON mi.monastery_id = m.id
    WHERE m.eparchy_id = 1
    GROUP BY m.id
    ORDER BY m.id
""")
rows = cursor.fetchall()
print(f"Total Žička monasteries: {len(rows)}")
for r in rows:
    print(f"ID {r[0]}: {r[1]} ({r[2]}) -> {r[4]} images currently")
