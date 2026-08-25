import urllib.request
import urllib.parse
import json
import os
import sys
import time
from PIL import Image

sys.stdout.reconfigure(encoding='utf-8')

headers = {'User-Agent': 'ManastiriSrbijeAcademic/1.0 (Faculty research; student@example.com)'}

def download_and_save(img_url, target_name):
    try:
        time.sleep(1)
        req = urllib.request.Request(img_url, headers=headers)
        temp_path = f"scratch/temp_{target_name}"
        with urllib.request.urlopen(req, timeout=20) as resp:
            with open(temp_path, 'wb') as f:
                f.write(resp.read())
        with Image.open(temp_path) as im:
            im = im.convert('RGB')
            if im.width > 1920 or im.height > 1920:
                im.thumbnail((1920, 1920), Image.Resampling.LANCZOS)
            target_path = os.path.join('public/images/monasteries', target_name)
            im.save(target_path, 'JPEG', quality=88, optimize=True)
            print(f"  ✓ Saved {target_name} ({im.width}x{im.height})")
            if os.path.exists(temp_path):
                os.remove(temp_path)
            return True
    except Exception as e:
        print(f"  ✗ Error downloading {target_name} from {img_url}: {e}")
        return False

# Download verified images for major Žička monasteries:

# 1. STUDENICA
# - studenica_gal_1.jpg -> Kraljeva crkva
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/King%27s_Church_in_Studenica_monastery.jpg/1280px-King%27s_Church_in_Studenica_monastery.jpg", "studenica_gal_1.jpg")
# - studenica_gal_2.jpg -> Južni portal Bogorodičine crkve
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/f/f9/Bogorodi%C4%8Dina_crkva%2C_Studenica_05.jpg/1280px-Bogorodi%C4%8Dina_crkva%2C_Studenica_05.jpg", "studenica_gal_2.jpg")
# - studenica_gal_3.jpg -> Nemanjina trpezarija
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/c/cf/Studenica%2C_Nemanjina_trpezarija_03.jpg/1280px-Studenica%2C_Nemanjina_trpezarija_03.jpg", "studenica_gal_3.jpg")
# - studenica_gal_4.jpg -> Raspeće Hristovo (1209)
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Studenica_Crucifixion.jpg/1280px-Studenica_Crucifixion.jpg", "studenica_gal_4.jpg")

# 2. ŽIČA
# - zica_gal_1.jpg -> Mala crkva Svetih Petra i Pavla
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Manastir_%C5%BDi%C4%8Da%2C_Crkva_Svetih_apostola_Petra_i_Pavla.jpg/1280px-Manastir_%C5%BDi%C4%8Da%2C_Crkva_Svetih_apostola_Petra_i_Pavla.jpg", "zica_gal_1.jpg")
# - zica_gal_2.jpg -> Ulazna kula i zvonik
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Manastir_%C5%BDi%C4%8Da%2C_ulazna_kula.jpg/1280px-Manastir_%C5%BDi%C4%8Da%2C_ulazna_kula.jpg", "zica_gal_2.jpg")
# - zica_gal_3.jpg -> Svečani južni portal
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Manastir_%C5%BDi%C4%8Da%2C_sve%C4%8Dani_portal_na_ju%C5%BEnoj_strani.jpg/1280px-Manastir_%C5%BDi%C4%8Da%2C_sve%C4%8Dani_portal_na_ju%C5%BEnoj_strani.jpg", "zica_gal_3.jpg")
# - zica_gal_4.jpg -> Unutrašnjost hrama i freske
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Manastir_%C5%BDi%C4%8Da%2C_unutra%C5%A1njost.jpg/1280px-Manastir_%C5%BDi%C4%8Da%2C_unutra%C5%A1njost.jpg", "zica_gal_4.jpg")

# 3. VRAĆEVŠNICA
# - vracevsnica_gal_1.jpg -> Konaci sa cvetnom alejom
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/b/b0/Konak_u_manastiru_Vra%C4%87ev%C5%A1nica.jpg/1280px-Konak_u_manastiru_Vra%C4%87ev%C5%A1nica.jpg", "vracevsnica_gal_1.jpg")
# - vracevsnica_gal_2.jpg -> Barokni zvonik i hram
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/7/7c/Wiki_%C5%A0umadija_X_Manastir_Vra%C4%87ev%C5%A1nica%2C_2019._470.jpg/1280px-Wiki_%C5%A0umadija_X_Manastir_Vra%C4%87ev%C5%A1nica%2C_2019._470.jpg", "vracevsnica_gal_2.jpg")
# - vracevsnica_gal_3.jpg -> Crkva Svetog Đorđa sa osmostranom kupolom
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/3/34/Crkva_Svetog_Djordja_u_manastiru_Vracevsnica%2C_Srbija.jpg/1280px-Crkva_Svetog_Djordja_u_manastiru_Vracevsnica%2C_Srbija.jpg", "vracevsnica_gal_3.jpg")
# - vracevsnica_gal_4.jpg -> Manastirsko dvorište i zidine
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/d/d6/Wiki_%C5%A0umadija_X_Manastir_Vra%C4%87ev%C5%A1nica%2C_2019._521.jpg/1280px-Wiki_%C5%A0umadija_X_Manastir_Vra%C4%87ev%C5%A1nica%2C_2019._521.jpg", "vracevsnica_gal_4.jpg")

# 4. GRADAC
# - gradac_gal_1.jpg -> Monumentalni hram
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Gradac_Monastery_2013-09-02_%2820%29.JPG/1280px-Gradac_Monastery_2013-09-02_%2820%29.JPG", "gradac_gal_1.jpg")
# - gradac_gal_2.jpg -> Ostaci bedema i trpezarije
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/3/36/Gradac_Monastery_2013-09-02_%2814%29.JPG/1280px-Gradac_Monastery_2013-09-02_%2814%29.JPG", "gradac_gal_2.jpg")
# - gradac_gal_3.jpg -> Zapadni gotizovani mermerni portal
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/6/60/Gradac_Monastery_2013-09-02_%2827%29.JPG/1280px-Gradac_Monastery_2013-09-02_%2827%29.JPG", "gradac_gal_3.jpg")

# 5. STARA PAVLICA
# - stara-pavlica_gal_1.jpg -> Kamena fasada i kupola
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Stara_Pavlica_%28crkva_svetog_Petra%29_-_pogled_sa_jugozapada.jpg/1280px-Stara_Pavlica_%28crkva_svetog_Petra%29_-_pogled_sa_jugozapada.jpg", "stara-pavlica_gal_1.jpg")
# - stara-pavlica_gal_2.jpg -> Pogled sa padine iznad pruge
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Stara_Pavlica_pogled.jpg/1280px-Stara_Pavlica_pogled.jpg", "stara-pavlica_gal_2.jpg")
# - stara-pavlica_gal_3.jpg -> Kamena bifora
download_and_save("https://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Stara_Pavlica_%28crkva_svetog_Petra%29_-_bifora_na_severnoj_strani_oltara.jpg/1280px-Stara_Pavlica_%28crkva_svetog_Petra%29_-_bifora_na_severnoj_strani_oltara.jpg", "stara-pavlica_gal_3.jpg")

print("\nSva preuzimanja za veće manastire Žičke eparhije su završena!")
