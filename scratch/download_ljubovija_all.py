import urllib.request
import os
from PIL import Image

os.makedirs('scratch/ljubovija_inspect', exist_ok=True)

urls = {
    '001': 'https://upload.wikimedia.org/wikipedia/commons/1/17/Manastir_Bjele_vode_001.jpg',
    '002': 'https://upload.wikimedia.org/wikipedia/commons/f/ff/Manastir_Bjele_vode_002.jpg',
    '003': 'https://upload.wikimedia.org/wikipedia/commons/1/17/Manastir_Bjele_vode_003.jpg',
    '004': 'https://upload.wikimedia.org/wikipedia/commons/9/96/Manastir_Bjele_vode_004.jpg',
    '005': 'https://upload.wikimedia.org/wikipedia/commons/9/9f/Manastir_Bjele_vode_005.jpg',
    '006': 'https://upload.wikimedia.org/wikipedia/commons/1/17/Manastir_Bjele_vode_006.jpg',
    '007': 'https://upload.wikimedia.org/wikipedia/commons/c/cb/Manastir_Bjele_vode_007.jpg',
    '008': 'https://upload.wikimedia.org/wikipedia/commons/b/b4/Manastir_Bjele_vode_008.jpg',
    '009': 'https://upload.wikimedia.org/wikipedia/commons/b/bb/Manastir_Bjele_vode_009.jpg',
    '010': 'https://upload.wikimedia.org/wikipedia/commons/f/f7/Manastir_Bjele_vode_010.jpg',
}

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'}

for k, u in urls.items():
    dest = f'scratch/ljubovija_inspect/bjele_vode_{k}.jpg'
    if not os.path.exists(dest):
        try:
            req = urllib.request.Request(u, headers=headers)
            with urllib.request.urlopen(req) as resp, open(dest, 'wb') as f:
                f.write(resp.read())
            print(f'Downloaded {k}')
        except Exception as e:
            print(f'Failed {k}: {e}')
