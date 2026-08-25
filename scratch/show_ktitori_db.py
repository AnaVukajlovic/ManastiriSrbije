import sqlite3
import json

conn = sqlite3.connect('database/database.sqlite')
conn.row_factory = sqlite3.Row

ktitors = conn.execute("SELECT id, name, slug, dynasty, title, born_year, died_year, is_saint FROM ktitors ORDER BY id").fetchall()

print(f"Ukupno ktitora: {len(ktitors)}\n")
for k in ktitors:
    imgs = conn.execute("SELECT * FROM ktitor_images WHERE ktitor_id = ?", (k['id'],)).fetchall()
    print(f"ID {k['id']}: {k['name']} (slug: {k['slug']}, dynasty: {k['dynasty']}, titula: {k['title']})")
    for im in imgs:
        print(f"   [Slika ID {im['id']}] path: {im['path']} | caption: {im['caption']} | source: {im['source']}")
