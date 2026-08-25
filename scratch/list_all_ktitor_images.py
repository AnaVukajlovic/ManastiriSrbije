import os

files = sorted(os.listdir('public/images/ktitors'))
print(f"Total files in public/images/ktitors: {len(files)}")
for f in files:
    size = os.path.getsize(os.path.join('public/images/ktitors', f))
    print(f"  - {f} ({size:,} bytes)")
