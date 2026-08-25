import sqlite3
import json
import os
import glob

conn = sqlite3.connect('database/database.sqlite')

print("--- KTITORS ---")
cols = [c[1] for c in conn.execute("PRAGMA table_info(ktitors)").fetchall()]
print("Columns:", cols)
ktitors = conn.execute("SELECT * FROM ktitors").fetchall()
for k in ktitors:
    d = dict(zip(cols, k))
    print(f"ID: {d['id']} | Name: {d['name']} | Dynasty: {d['dynasty']} | Slug: {d['slug']}")

print("\n--- KTITOR_IMAGES ---")
cols_img = [c[1] for c in conn.execute("PRAGMA table_info(ktitor_images)").fetchall()]
print("Columns:", cols_img)
imgs = conn.execute("SELECT * FROM ktitor_images").fetchall()
print(f"Total ktitor images: {len(imgs)}")
for im in imgs:
    d = dict(zip(cols_img, im))
    print(d)

print("\n--- IMAGE FOLDERS ---")
for root, dirs, files in os.walk('public/images'):
    if 'ktitor' in root.lower() or any('ktitor' in f.lower() for f in files) or any('nemanj' in f.lower() for f in files):
        print(f"Directory: {root} -> {len(files)} files")
        for f in files[:20]:
            print("  ", f)

all_ktitor_files = glob.glob('public/images/ktitori/**', recursive=True) + glob.glob('public/images/ktitors/**', recursive=True) + glob.glob('public/images/*ktitor*', recursive=True)
print(f"\nAll potential ktitor image files: {len(all_ktitor_files)}")
for f in all_ktitor_files:
    print("  ", f)
