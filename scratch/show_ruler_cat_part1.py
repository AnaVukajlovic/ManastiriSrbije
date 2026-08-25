import json

with open('scratch/wikimedia_rulers_category_files.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for cat in [
    "Stefan Nemanja", "Stefan the First-Crowned", "Saint Sava",
    "Stefan Radoslav", "Stefan Vladislav", "Stefan Uroš I",
    "Stefan Dragutin", "Stefan Milutin", "Stefan Dečanski", "Stefan Dušan"
]:
    files = data.get(cat, [])
    print(f"\n==================== Category: {cat} ({len(files)} files) ====================")
    for f in files:
        print(f"  - {f['title']} ({f['width']}x{f['height']}) -> {f['url']}")
