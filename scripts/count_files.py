import os

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PUBLIC_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')
all_files = set(os.listdir(PUBLIC_DIR))

# Let's test all files in PUBLIC_DIR
print(f"Total image files in public/images/monasteries: {len(all_files)}")
