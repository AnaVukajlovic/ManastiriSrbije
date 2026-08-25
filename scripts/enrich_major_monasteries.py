import urllib.request
import urllib.parse
import json
import os
import io
import sys
import hashlib
from PIL import Image

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PUBLIC_IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')

def search_and_download_commons(query, target_filename, min_width=800):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&format=json&generator=search&gsrsearch={urllib.parse.quote(query)}&gsrnamespace=6&gsrlimit=10&prop=imageinfo&iiprop=url|size|mime"
    req = urllib.request.Request(url, headers={'User-Agent': 'ManastiriSrbijeBot/2.0 (ana@manastirisrbije.rs)'})
    try:
        with urllib.request.urlopen(req) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            if 'query' not in data or 'pages' not in data['query']:
                print(f"Nema rezultata za: {query}")
                return None
            
            pages = data['query']['pages']
            best_img = None
            for p_id, p_info in pages.items():
                if 'imageinfo' in p_info and len(p_info['imageinfo']) > 0:
                    info = p_info['imageinfo'][0]
                    mime = info.get('mime', '')
                    if 'image/jpeg' in mime or 'image/png' in mime:
                        w = info.get('width', 0)
                        h = info.get('height', 0)
                        if w >= min_width or h >= min_width:
                            best_img = info['url']
                            break
            
            if not best_img and len(pages) > 0:
                # fallback to first
                first_page = list(pages.values())[0]
                if 'imageinfo' in first_page:
                    best_img = first_page['imageinfo'][0]['url']
                    
            if best_img:
                out_path = os.path.join(PUBLIC_IMG_DIR, target_filename)
                print(f"Preuzimam: {best_img} -> {target_filename}")
                img_req = urllib.request.Request(best_img, headers={'User-Agent': 'ManastiriSrbijeBot/2.0 (ana@manastirisrbije.rs)'})
                with urllib.request.urlopen(img_req) as img_resp:
                    content = img_resp.read()
                    with open(out_path, 'wb') as f:
                        f.write(content)
                
                # Check dim
                with Image.open(out_path) as img:
                    print(f"  ✓ Sačuvano ({img.size[0]}x{img.size[1]})")
                return out_path
    except Exception as e:
        print(f"Greška za '{query}': {e}")
        return None

# Let's test specific masterpieces to enrich major monasteries:
TARGET_ENRICHMENTS = [
    # 1. Studenica - Raspeće Hristovo (Studeničko Raspeće 1209)
    {
        'query': 'Studenica Crucifixion fresco 1209',
        'file': 'studenica_gal_3.jpg',
        'caption': 'Monumentalna freska Raspeća Hristovog (Studeničko Raspeće) iz 1209. godine na vizantijsko plavoj pozadini u naosu Bogorodičine crkve (Izvor: Galerija fresaka Narodnog muzeja u Beogradu / Vikimedijina ostava)'
    },
    # 2. Mileševa - Beli Anđeo (Mironosice na grobu Hristovom)
    {
        'query': 'White Angel Mileseva fresco',
        'file': 'mileseva_gal_3.jpg',
        'caption': 'Svetski poznata freska Belog Anđela (Mironosice na grobu Hristovom) iz 1230-ih godina na južnom zidu crkve Vaznesenja Gospodnjeg u Mileševi (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'
    },
    # 3. Manasija - Ktitorska freska Despota Stefana Lazarevića
    {
        'query': 'Despot Stefan Lazarevic Manasija fresco',
        'file': 'manasija_gal_3.jpg',
        'caption': 'Ktitorska freska Svetog despota Stefana Lazarevića sa modelom manastira Manasija u ruci na zapadnom zidu naosa (Izvor: Galerija fresaka Narodnog muzeja / Vikimedijina ostava)'
    },
    # 4. Ravanica - Ktitorska freska Kneza Lazara
    {
        'query': 'Knez Lazar Ravanica fresco',
        'file': 'ravanica_gal_3.jpg',
        'caption': 'Ktitorski portret Svetog kneza Lazara sa modelom hrama Vaznesenja Gospodnjeg u Ravanici (1375–1377) (Izvor: Narodni muzej Srbije / Vikimedijina ostava)'
    },
    # 5. Gračanica - Kraljica Simonida i Kralj Milutin
    {
        'query': 'Simonida Gracanica fresco',
        'file': 'gracanica_gal_2.jpg',
        'caption': 'Znamenita freska kraljice Simonide Paleolog sa zlatnom krunom iz 1321. godine u priprati manastira Gračanica (Izvor: Galerija fresaka Narodnog muzeja / Vikimedijina ostava)'
    },
    # 6. Visoki Dečani - Hrist Pantokrator / Loza Nemanjića
    {
        'query': 'Decani Nemanic dynasty tree fresco',
        'file': 'visoki-decani_gal_3.jpg',
        'caption': 'Monumentalna freska Loze Nemanjića iz 1346. godine u priprati crkve Hrista Pantokratora u Visokim Dečanima (Izvor: UNESCO World Heritage Centre / Vikimedijina ostava)'
    },
    # 7. Žiča - Unutrašnjost / Hristos Pantokrator / Krunisanje
    {
        'query': 'Zica monastery interior fresco',
        'file': 'zica_gal_2.jpg',
        'caption': 'Zidno slikarstvo i freska Tajne večere u priprati crkve Svetog Spasa u manastiru Žiča (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'
    },
    # 8. Kovilj - Unutrašnjost i barokni ikonostas
    {
        'query': 'Kovilj monastery interior iconostasis',
        'file': 'kovilj_gal_2.jpg',
        'caption': 'Raskošni mermerni ikonostas i unutrašnjost crkve Svetih arhangela u Kovilju rad Aksentija Marodića (Izvor: Galerija Matice srpske / Vikimedijina ostava)'
    },
    # 9. Đurđevi Stupovi - Stefan Nemanja ktitor
    {
        'query': 'Djurdjevi Stupovi fresco Stefan Nemanja',
        'file': 'djurdjevi-stupovi_gal_3.jpg',
        'caption': 'Freska Svetog Stefana Prvomučenika i Svetog Đorđa iz 1175. godine u crkvi Đurđevi Stupovi u Rasu (Izvor: Galerija fresaka Narodnog muzeja / Vikimedijina ostava)'
    },
    # 10. Pećka Patrijaršija - Mermerni presto Svetog Save
    {
        'query': 'Pecka Patrijarsija throne of Saint Sava',
        'file': 'pecka-patrijarsija_gal_3.jpg',
        'caption': 'Autentični kameni presto Svetog Save i freske srpskih arhiepiskopa u crkvi Svetih apostola u Pećkoj patrijaršiji (Izvor: Srpska pravoslavna crkva / Vikimedijina ostava)'
    }
]

print("=== PREUZIMANJE SLIKA ZNAMENITIH FRESKI I IKONA ===")
for item in TARGET_ENRICHMENTS:
    search_and_download_commons(item['query'], item['file'])

