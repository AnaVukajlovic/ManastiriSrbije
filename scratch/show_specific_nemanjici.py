import json

with open('scratch/specific_frescoes_found.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for slug in [
    'stefan-nemanja', 'stefan-prvovencani', 'sveti-sava', 'stefan-radoslav',
    'stefan-vladislav', 'stefan-uros-i', 'kralj-dragutin', 'kralj-milutin',
    'stefan-decanski', 'car-dusan', 'uros-nejaki'
]:
    items = data.get(slug, [])
    print(f"\n==================== {slug} ({len(items)} items) ====================")
    for it in items:
        print(f"  - {it['title']} ({it['width']}x{it['height']}) -> {it['url']}")
