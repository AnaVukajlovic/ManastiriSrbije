import json

with open('scratch/specific_frescoes_found.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for slug, items in data.items():
    print(f"\n==================== {slug} ({len(items)} items) ====================")
    for it in items:
        print(f"  - {it['title']} ({it['width']}x{it['height']}) -> {it['url']}")
