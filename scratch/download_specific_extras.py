import urllib.request
import json
import time

headers = {
    'User-Agent': 'ManastiriSrbijeBot/1.0 (https://manastirisrbije.rs; contact@manastirisrbije.rs)'
}

files_to_download = [
    ("carica-jelena-2.jpg", "File:Uros Dusan Jelena Decani.jpg"),
    ("jelena-anzujska-2.jpg", "File:Serbian Queen Helen of Anjou Nemanjic Sopocani Monastery.jpg"),
    ("simonida-3.jpg", "File:Simonida Kraljeva.jpg"),
    ("knez-lazar-2.jpg", "File:Prince Lazar (Ravanica Monastery).jpg")
]

for filename, title in files_to_download:
    api_url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(title)}&prop=imageinfo&iiprop=url&format=json"
    req = urllib.request.Request(api_url, headers=headers)
    try:
        with urllib.request.urlopen(req) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            for pid, pinfo in pages.items():
                if 'imageinfo' in pinfo and len(pinfo['imageinfo']) > 0:
                    img_url = pinfo['imageinfo'][0]['url']
                    print(f"Downloading {title} -> {filename} from {img_url}...")
                    
                    time.sleep(2)
                    img_req = urllib.request.Request(img_url, headers=headers)
                    with urllib.request.urlopen(img_req) as img_resp:
                        img_data = img_resp.read()
                        with open(f"public/images/ktitors/{filename}", "wb") as f:
                            f.write(img_data)
                    print(f"Successfully saved {filename} ({len(img_data)} bytes)")
    except Exception as e:
        print(f"Error {title}: {e}")
    time.sleep(2)
