import os
import hashlib
import sqlite3
import io
import sys
from PIL import Image

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PUBLIC_IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')

# Calculate MD5 hashes of all files in public/images/monasteries
hashes = {}
file_to_hash = {}
for fname in os.listdir(PUBLIC_IMG_DIR):
    fpath = os.path.join(PUBLIC_IMG_DIR, fname)
    if os.path.isfile(fpath) and fname.endswith(('.jpg', '.png', '.webp')):
        try:
            with open(fpath, 'rb') as f:
                h = hashlib.md5(f.read()).hexdigest()
            file_to_hash[fname] = h
            if h not in hashes:
                hashes[h] = []
            hashes[h].append(fname)
        except Exception as e:
            print(f"Greška za {fname}: {e}")

print("=== DETEKCIJA IDENTIČNIH FAJLOVA NA DISKU ===")
duplicates_found = 0
for h, fnames in hashes.items():
    if len(fnames) > 1:
        duplicates_found += 1
        print(f"Isti sadržaj (hash {h[:8]}): {fnames}")

print(f"\nUkupno grupa duplikata na disku: {duplicates_found}")
