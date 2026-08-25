import os
from PIL import Image

folder = 'public/images/ktitors'
for f in sorted(os.listdir(folder)):
    p = os.path.join(folder, f)
    if os.path.isfile(p):
        try:
            with Image.open(p) as img:
                print(f"{f}: {img.format} {img.size} ({os.path.getsize(p)} bytes)")
        except Exception as e:
            print(f"{f}: error {e}")
