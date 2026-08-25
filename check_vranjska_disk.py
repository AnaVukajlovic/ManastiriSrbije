import os
import glob
import urllib.request
import urllib.parse
import json

img_dir = r"d:\projekti\ManastiriSrbije\backend\public\images\monasteries"

vranjska_slugs = [
    'bresnica', 'kacapun', 'lopardince', 'prohor-pcinjski', 'zapsko',
    'dubnica-milesevska', 'kozji-dol', 'lepcince', 'simeon-stolpnik',
    'mrtvica', 'palja', 'sveti-nikola-vranje'
]

print("=== EXISTING FILES ON DISK FOR VRANJSKA EPARCHY ===")
for s in vranjska_slugs:
    matches = glob.glob(os.path.join(img_dir, f"{s}*.*"))
    print(f"{s}: {len(matches)} files -> {[os.path.basename(m) for m in matches]}")
