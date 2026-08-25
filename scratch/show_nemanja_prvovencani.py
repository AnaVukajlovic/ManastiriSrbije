import json

with open('scratch/specific_frescoes_found.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for slug in ['stefan-nemanja', 'stefan-prvovencani']:
    print(f"\n==================== {slug} ====================")
    for it in data.get(slug, []):
        print(f"  - {it['title']} ({it['width']}x{it['height']}) -> {it['url']}")
