import os

print("--- public/images/ktitors ---")
for f in os.listdir('public/images/ktitors'):
    p = os.path.join('public/images/ktitors', f)
    if os.path.isfile(p):
        print(f"{f} ({os.path.getsize(p)} bytes)")

print("\n--- public/images/ktitors_gallery ---")
for f in os.listdir('public/images/ktitors_gallery'):
    p = os.path.join('public/images/ktitors_gallery', f)
    if os.path.isfile(p):
        print(f"{f} ({os.path.getsize(p)} bytes)")
