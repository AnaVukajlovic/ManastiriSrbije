import os

monastery_slugs = [
    'grliste', 'krepicevac', 'lapusnja', 'lozica', 'vratna', 'suvodol',
    'lelic', 'bogovadja', 'dokmir', 'grabovac', 'ribnica', 'pluzac', 'jovanja'
]

dir_path = 'public/images/monasteries'
all_files = os.listdir(dir_path)

for slug in monastery_slugs:
    matching = [f for f in all_files if slug in f.lower()]
    print(f"\n[{slug}]:")
    for f in matching:
        full_path = os.path.join(dir_path, f)
        size = os.path.getsize(full_path)
        print(f"  - {f} ({size} bytes)")
