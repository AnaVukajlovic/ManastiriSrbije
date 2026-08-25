import json

with open(r'd:\projekti\ManastiriSrbije\backend\wikimedia_vranjska.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for slug, items in data.items():
    print(f"\n==================== {slug} ({len(items)} found) ====================")
    for it in items:
        print(f"  Title: {it['title']}")
        print(f"  URL: {it['url']}")
        print(f"  Desc: {it['desc'][:120] if it['desc'] else 'None'}...")
