import urllib.request
import urllib.parse
import json
import time
import sys
import ssl

sys.stdout.reconfigure(encoding='utf-8')

USER_AGENT = 'ManastiriSrbijeResearch/1.0 (contact@pravoslavnisvetionik.rs)'

ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

targets = [
    ("bresnica", ["Манастир Бресница", "Бресница (Босилеград)", "Манастир Свете Петке у Бресници"], ["Category:Bresnica (Bosilegrad)", "Category:Monasteries in Vranje"]),
    ("kacapun", ["Манастир Кацапун", "Кацапун"], ["Category:Kacapun Monastery", "Category:Kacapun monastery", "Category:Manastir Kacapun", "Category:Кацапун"]),
    ("lopardince", ["Манастир Лопардинце", "Лопардинце"], ["Category:Lopardince", "Category:Lopardince monastery", "Category:Manastir Lopardince"]),
    ("prohor-pcinjski", ["Манастир Прохор Пчињски", "Прохор Пчињски"], ["Category:Prohor Pčinjski Monastery", "Category:Prohor Pčinjski", "Category:Manastir Prohor Pčinjski"]),
    ("zapsko", ["Манастир Жапско", "Жапско", "Манастир Светог Стефана у Жапском"], ["Category:Žapsko monastery", "Category:Žapsko", "Category:Manastir Žapsko"]),
    ("dubnica", ["Манастир Дубница (Врање)", "Дубница (Врање)", "Манастир Светих апостола Петра и Павла у Дубници"], ["Category:Dubnica Monastery (Vranje)", "Category:Dubnica (Vranje)", "Category:Manastir Dubnica"]),
    ("kozji-dol", ["Манастиir Козји Дол", "Козји Дол (Трговиште)", "Манастир Преobraženja Gospodnjeg u Kozjem Dolu"], ["Category:Kozji Dol", "Category:Kozji Dol monastery", "Category:Manastir Kozji Dol"]),
    ("lepcince", ["Манастир Лепчинце", "Лепчинце", "Манастир Светог Пантелејмона у Лепчинцу"], ["Category:Lepčince Monastery", "Category:Lepčince monastery", "Category:Lepčince", "Category:Manastir Lepčince"]),
    ("simeon-stolpnik", ["Манастир Свети Симеон Столпник", "Манастир Светог Симеона Столпника", "Симеон Столпник (Врање)"], ["Category:Simeon Stolpnik monastery", "Category:Manastir Simeon Stolpnik"]),
    ("mrtvica", ["Манастир Мртвица", "Мртвица (Владичин Хан)", "Манастир Успења Пресвете Богородице у Мртвици"], ["Category:Mrtvica Monastery", "Category:Mrtvica monastery", "Category:Mrtvica (Vladičin Han)", "Category:Manastir Mrtvica"]),
    ("palja", ["Манастир Паља", "Паља", "Манастир Ваведења Пресвете Богородице у Паљи"], ["Category:Palja Monastery", "Category:Palja monastery", "Category:Palja (Surdulica)", "Category:Manastir Palja"]),
    ("sveti-nikola-vranje", ["Манастир Светог Николе у Врању", "Црква Светог Николе у Врању", "Манастир Свети Никола (Врање)"], ["Category:Church of Saint Nicholas (Vranje)", "Category:Saint Nicholas Monastery (Vranje)", "Category:Manastir Sveti Nikola Vranje"]),
]

def get_wiki_images(title):
    url = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(title)}&prop=images|pageimages&piprop=original&format=json"
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            images = []
            for pid, page in pages.items():
                if 'original' in page:
                    images.append(page['original']['source'])
                if 'images' in page:
                    for im in page['images']:
                        im_title = im.get('title', '')
                        if not any(x in im_title.lower() for x in ['icon', 'logo', 'flag', 'map', 'stub', 'location']):
                            images.append(im_title)
            return images
    except Exception as e:
        return []

def get_category_files(cat):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&list=categorymembers&cmtitle={urllib.parse.quote(cat)}&cmtype=file&cmlimit=25&format=json"
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            members = data.get('query', {}).get('categorymembers', [])
            return [m['title'] for m in members]
    except Exception as e:
        return []

def get_image_url(file_title):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_title)}&prop=imageinfo&iiprop=url|size|extmetadata&format=json"
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            for pid, page in pages.items():
                if 'imageinfo' in page:
                    info = page['imageinfo'][0]
                    return {
                        'url': info.get('url', ''),
                        'width': info.get('width', 0),
                        'height': info.get('height', 0),
                        'title': file_title
                    }
    except Exception as e:
        pass
    return None

results = {}

for slug, titles, cats in targets:
    print(f"\n==================================================")
    print(f"SEARCHING FOR: {slug}")
    print(f"==================================================")
    found_urls = []
    seen = set()
    
    # 1. Wiki pages
    for t in titles:
        imgs = get_wiki_images(t)
        time.sleep(0.3)
        for im in imgs:
            if im.startswith('http') and im not in seen:
                seen.add(im)
                found_urls.append({'title': t, 'url': im})
                print(f"  [Wiki Page {t}] -> {im}")
            elif im.startswith('Датотека:') or im.startswith('File:'):
                info = get_image_url(im)
                time.sleep(0.2)
                if info and info['url'] and info['url'] not in seen:
                    seen.add(info['url'])
                    found_urls.append(info)
                    print(f"  [Wiki File {im}] -> {info['url']}")
                    
    # 2. Commons Categories
    for c in cats:
        cfiles = get_category_files(c)
        time.sleep(0.3)
        if cfiles:
            print(f"  [Category {c}] found {len(cfiles)} files")
        for cf in cfiles:
            info = get_image_url(cf)
            time.sleep(0.2)
            if info and info['url'] and info['url'] not in seen:
                seen.add(info['url'])
                found_urls.append(info)
                print(f"  [Commons Cat {c}] -> {info['url']}")

    results[slug] = found_urls

with open(r'd:\projekti\ManastiriSrbije\backend\vranjska_found_images.json', 'w', encoding='utf-8') as f:
    json.dump(results, f, ensure_ascii=False, indent=2)

print("\nDone! Saved to vranjska_found_images.json")
