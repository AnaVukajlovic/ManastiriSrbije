import json

with open('scratch/wikimedia_rulers_category_files.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for cat, files in data.items():
    print(f"\n=== Category: {cat} ({len(files)} files) ===")
    for f in files:
        print(f"   * {f['title']} ({f['width']}x{f['height']}) -> {f['url']}")
