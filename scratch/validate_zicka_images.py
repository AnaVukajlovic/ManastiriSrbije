import os
import re

with open('update_zicka.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Extract all 'url' => '...' and 'card_image' => '...'
urls = re.findall(r"'url'\s*=>\s*'([^']+)'", content)
card_images = re.findall(r"'card_image'\s*=>\s*'([^']+)'", content)

all_images = set(urls + card_images)
print(f"Total unique images referenced in update_zicka.php: {len(all_images)}")

missing = []
for img in sorted(all_images):
    path = os.path.join('public', img)
    if not os.path.exists(path):
        missing.append((img, path))
    else:
        sz = os.path.getsize(path)
        if sz == 0:
            missing.append((img, f"{path} (0 bytes)"))

if missing:
    print(f"FAILED: {len(missing)} missing or empty files:")
    for m, p in missing:
        print(f"  - {m} -> {p}")
else:
    print("SUCCESS! All referenced images exist on disk and have valid sizes.")
