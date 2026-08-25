import json

with open(r'd:\projekti\ManastiriSrbije\backend\fast_vranjska_images.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for slug, items in data.items():
    print(f"\n==================== {slug} ({len(items)} found) ====================")
    for it in items:
        print(f"  [{it.get('source')}] {it.get('file_title', '')} -> {it.get('url')}")
