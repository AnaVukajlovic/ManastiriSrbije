import urllib.request
import json
import os

with open('scratch/wikimedia_rulers_category_files.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for cat, files in data.items():
    print(f"\n==================== {cat} ====================")
    for f in files:
        print(f"  {f['title']}: {f['url']}")
