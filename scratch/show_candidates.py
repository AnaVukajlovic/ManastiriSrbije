import json

with open('scratch/wikimedia_ktitori_candidates.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for slug, items in data.items():
    print(f"\n=== {slug} ({len(items)} found) ===")
    for item in items:
        print(f"  - {item['title']} ({item.get('width')}x{item.get('height')})")
        print(f"    URL: {item.get('url')}")
